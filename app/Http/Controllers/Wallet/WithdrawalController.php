<?php

namespace App\Http\Controllers\Wallet;

use App\Models\Asset;
use App\Models\Wallet;
use App\Models\WalletTransfer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;

class WithdrawalController extends Controller
{
    public function __construct()
    {
        
    }
   
    public function index()
    { 
        $wallets = Wallet::where('user_id', auth()->id())
        ->whereHas('coin', function ($query) {
            $query->where('is_active', 'y');
        })
        ->get();

        return view('wallet.withdrawal', compact('wallets'));
    }

    public function list()
    {
        $limit = 10;

        $list = walletTransfer::where('user_id', auth()->id())
            ->where('type', 'withdrawal')
            ->latest()
            ->take($limit)
            ->get();

        $total_count = walletTransfer::where('user_id', auth()->id())
            ->where('type', 'deposit')
            ->count();

        $has_more = $total_count > $limit;

        return view('wallet.withdrawal-list', compact('list', 'has_more', 'limit'));
    }

    public function loadMore(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);

        $query = walletTransfer::where('user_id', auth()->id())
            ->where('type', 'withdrawal')
            ->orderByDesc('id');

        $items = $query->skip($offset)->take($limit + 1)->get();

        $hasMore = $items->count() > $limit;
        
        $items = $items->take($limit)->map(function ($item) {
            return [
                'created_at' => $item->created_at->format('Y-m-d'),
                'coin_code' => $item->wallet->coin->code,
                'amount' => $item->amount,
            ];
        });

        return response()->json([
            'items' => $items,
            'hasMore' => $hasMore,
        ]);
    }

    public function store(Request $request)
    { 
        $validated = $request->validate([
            'wallet' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {       
            
            $wallet_id = Hashids::decode($validated['wallet']);

            if (empty($wallet_id)) {
                throw new \Exception('잘못된 월렛 정보입니다.');
            }

            $wallet = Wallet::findOrFail($wallet_id[0]);
            $asset = Asset::where('user_id', $wallet->user_id)->where('coin_id', $wallet->coin_id)->first();

            if($wallet->balance < $validated['amount']) {
                throw new \Exception('출금 실패하였습니다. 월렛의 잔액을 확인하시길 바랍니다.');
            }

            $amount = $validated['amount'];
            $fee_rate = $wallet->withdrawal_fee_rate;
            $fee = round($amount * ($fee_rate / 100), 2);
            $actual_amount = $amount - $fee;

            WalletTransfer::create([
                'user_id' => auth()->id(),
                'wallet_id' => $wallet->id,
                'type' => 'withdrawal',
                'amount' => $amount,
                'fee' => $fee,
                'actual_amount' => $actual_amount,
                'before_balance' => $wallet->balance,
                'after_balance' => $wallet->balance - $amount,
            ]);

            $asset->increment('balance', $actual_amount); 
            $wallet->decrement('balance', $amount);

            DB::commit();
        
            return response()->json([
                'status' => 'success',
                'message' => '출금 성공하였습니다. 보유자산에서 확인하시길 바랍니다.',
                'url' => route('home'),
            ]);
        
            
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        
    }
}