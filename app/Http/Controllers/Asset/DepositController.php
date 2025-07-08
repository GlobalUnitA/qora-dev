<?php

namespace App\Http\Controllers\Asset;

use App\Models\Asset;
use App\Models\AssetTransfer;
use App\Models\DepositToast;
use App\Models\KakaoApi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;

class DepositController extends Controller
{
    protected $kakaoApi;

    public function __construct()
    {
        $this->kakaoApi = new KakaoApi();
    }
    
    public function index()
    {
        $assets = Asset::where('user_id', auth()->id())
        ->whereHas('coin', function ($query) {
            $query->where('is_active', 'y');
        })
        ->get();

        return view('asset.deposit', compact('assets'));
    }

    public function confirm(Request $request)
    {

        $asset_id = Hashids::decode($request['asset']);
        $asset = Asset::findOrFail($asset_id[0]);

        $amount = $request['amount'];
        
        return view('asset.deposit-confirm', compact(['asset', 'amount']));
    }

    public function store(Request $request)
    {
    

        if (!$request->hasFile('file')) {
            return response()->json([
                'status' => 'error',
                'message' => __('etc.iamge_upload_notice'),
            ]);
        }
        
        $validated = $request->validate([
            'asset' => 'required|string',
            'amount' => 'required|numeric',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'txid' => 'required|string|max:200',
        ]);

        $file = $request->file('file');

        if (!$file->isValid()) {
            return response()->json([
                'status' => 'error',
                'message' => __('etc.iamge_upload_notice'),
            ]);
        }
       
        DB::beginTransaction();

        try {       
            
            $asset_id = Hashids::decode($validated['asset']);
            $asset = Asset::findOrFail($asset_id[0]);

            $file_name = '_' . time() . '_' . auth()->id() . '_' . $file->getClientOriginalName();
            $file_path = $file->storeAs('public/uploads/deposit/', $file_name);
            $file_url[] = asset('storage/uploads/deposit/' . $file_name);

            $deposit = AssetTransfer::create([
                'user_id' => auth()->id(),
                'asset_id' => $asset->id,
                'type' => 'deposit',
                'amount' => $validated['amount'],
                'txid' => $validated['txid'],
                'actual_amount' => $validated['amount'],
                'image_urls' => $file_url,
            ]);

            DepositToast::create([
                'deposit_id' => $deposit->id,
            ]);

            DB::commit();

            $message = 'UID C'.auth()->id().' 회원님이 입금 신청하였습니다.';
            $this->kakaoApi->sendPurchaseNotification($message);

            return response()->json([
                'status' => 'success',
                'message' => __('asset.deposit_apply_notice'),
                'url' => route('home'),
            ]);
        
            
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => __('system.error_notice') . $e->getMessage(),
            ]);
        }
    }

    public function list()
    {
        $limit = 10;

        $list = AssetTransfer::where('user_id', Auth()->id())
            ->where('type', 'deposit')
            ->latest()
            ->take($limit)
            ->get();

        $total_count = AssetTransfer::where('user_id', auth()->id())
            ->where('type', 'deposit')
            ->count();

        $has_more = $total_count > $limit;


        return view('asset.deposit-list', compact('list', 'has_more', 'limit'));
    }

    public function loadMore(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);

        $query = AssetTransfer::with('asset.coin')
            ->where('user_id', auth()->id())
            ->where('type', 'deposit')
            ->orderByDesc('id');

        $items = $query->skip($offset)->take($limit + 1)->get();

        $hasMore = $items->count() > $limit;
        
        $items = $items->take($limit)->map(function ($item) {
            return [
                'created_at' => $item->created_at->format('Y-m-d'),
                'waiting_period' => $item->waiting_period,
                'coin_code' => $item->asset->coin->code,
                'status_text' => $item->status_text,
                'amount' => $item->amount,
            ];
        });

        return response()->json([
            'items' => $items,
            'hasMore' => $hasMore,
        ]);
    }
}