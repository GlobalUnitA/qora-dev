<?php

namespace App\Http\Controllers\Asset;


use App\Models\UserProfile;
use App\Models\Asset;
use App\Models\AssetTransfer;
use App\Models\TradingProfit;
use App\Models\Bonus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;

class AssetController extends Controller
{
    public function __construct()
    {
        
    }

   
    public function index(Request $request)
    {

        $asset_id = Hashids::decode($request->id);
        $asset = Asset::findOrFail($asset_id[0]);

        if ($asset->user_id != Auth()->id() ) {
             return redirect()->route('home');
        }

        $data = $asset->getAssetInfo();

        return view('asset.asset', compact('data'));
    }

    public function detail(Request $request)
    {   
        $limit = 10;

        $trading_label = __('asset.trading');
        $bonus_label = __('asset.subscription_bonus');
        $downline_label = __('user.child');

        switch($request->mode) {
            case 'today' :
                

                $today = Carbon::today();
                $profits = DB::table('trading_profits')
                ->select([
                    'profit as amount',
                    DB::raw("'" . addslashes($trading_label) . "' as label"),
                    DB::raw("null as referrer_id"),
                    'created_at',
                ])
                ->whereDate('created_at', $today)
                ->where('user_id', auth()->id());
            
                $bonuses = DB::table('bonuses')
                    ->select([
                        'bonus as amount',
                        DB::raw("'" . addslashes($bonus_label) . "' as label"),
                        'referrer_id',
                        'created_at',
                    ])
                    ->whereDate('created_at', $today)
                    ->where('user_id', auth()->id());
                
                $union = $profits->unionAll($bonuses);

                $query = DB::table(DB::raw("({$union->toSql()}) as combined"))
                    ->mergeBindings($union)
                    ->orderBy('created_at', 'desc');
            
            break;

            case 'profit' :

                $trading_label = __('asset.trading');
                $query =  DB::table('trading_profits')
                    ->select([
                        'profit as amount',
                        DB::raw("'" . addslashes($trading_label) . "' as label"),
                        DB::raw("null as referrer_id"),
                        'created_at',
                    ])
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc');                 
                
            break;

            case 'bonus' :
                $query = DB::table('bonuses')
                    ->select([
                        'bonus as amount',
                        DB::raw("'" . addslashes($bonus_label) . "' as label"),
                        'referrer_id',
                        'created_at',
                    ])
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc');

            break;

            case 'group' :

                $user_profile = UserProfile::where('user_id', auth()->id())->first();
                $childrens = $user_profile->getChildrenTree(21);

                $referrer_id = [];
                foreach ($childrens as $level => $profiles) {
                    foreach ($profiles as $profile) {
                        $user = $profile->user;
                        if(!$user) continue;
                        $referrer_id[] = $user->id;
                    }
                }
                
                $query =  DB::table('asset_transfers')
                    ->select([
                        'amount',
                        DB::raw("'" . addslashes($downline_label) . "' as label"),
                        'user_id as referrer_id',
                        'created_at',
                    ])
                    ->whereIn('user_id', $referrer_id)
                    ->whereIn('type', ['deposit', 'manual_deposit'])
                    ->where('status', 'completed')
                    ->orderBy('created_at', 'desc');
                    
            break;
            
        }
        
        $total_count = $query->count();
        $list = $query->take($limit)->get();
         
        $has_more = $total_count > $limit;

        return view('asset.detail', compact('list', 'has_more', 'limit'));
    }


    public function loadMore(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $mode = $request->input('mode');

        $items = collect();

        $trading_label = __('asset.trading');
        $bonus_label = __('asset.subscription_bonus');
        $downline_label = __('user.child');

        switch($mode) {
            case 'today' :
                $today = Carbon::today();

                
                $profits = DB::table('trading_profits')
                ->select([
                    'profit as amount',
                    DB::raw("'" . addslashes($trading_label) . "' as label"),
                    DB::raw("null as referrer_id"),
                    'created_at',
                ])
                ->whereDate('created_at', $today)
                ->where('user_id', auth()->id());
            
                
                $bonuses = DB::table('bonuses')
                    ->select([
                        'bonus as amount',
                        DB::raw("'" . addslashes($bonus_label) . "' as label"),
                        'referrer_id',
                        'created_at',
                    ])
                    ->whereDate('created_at', $today)
                    ->where('user_id', auth()->id());
                
                $union = $profits->unionAll($bonuses);
                
                $items = DB::table(DB::raw("({$union->toSql()}) as combined"))
                    ->mergeBindings($union) 
                    ->orderBy('created_at', 'desc')
                    ->skip($offset)
                    ->take($limit + 1)
                    ->get();
     
            break;

            case 'profit' :
                $trading_label = __('asset.trading');

                $items = DB::table('trading_profits')
                    ->select([
                        'profit as amount',
                        DB::raw("'" . addslashes($trading_label) . "' as label"),
                        DB::raw("null as referrer_id"),
                        'created_at',
                    ])
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->skip($offset)
                    ->take($limit + 1)
                    ->get();

            break;

            case 'bonus' :
                $items = DB::table('bonuses')
                    ->select([
                        'bonus as amount',
                        DB::raw("'" . addslashes($bonus_label) . "' as label"),
                        'referrer_id',
                        'created_at',
                    ])
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->skip($offset)
                    ->take($limit + 1)
                    ->get();

            break;

            case 'group' :

                $user_profile = UserProfile::where('user_id', auth()->id())->first();
                $childrens = $user_profile->getChildrenTree(21);

                $referrer_id = [];
                foreach ($childrens as $level => $profiles) {
                    foreach ($profiles as $profile) {
                        $user = $profile->user;
                        if(!$user) continue;
                        $referrer_id[] = $user->id;
                    }
                }
                
                $items =  DB::table('asset_transfers')
                    ->select([
                        'amount',
                        DB::raw("'" . addslashes($downline_label) . "' as label"),
                        'user_id as referrer_id',
                        'created_at',
                    ])
                    ->whereIn('user_id', $referrer_id)
                    ->whereIn('type', ['deposit', 'manual_deposit'])
                    ->where('status', 'completed')
                    ->orderBy('created_at', 'desc')
                    ->skip($offset)
                    ->take($limit + 1)
                    ->get();
                    
            break;
            
        }

        $hasMore = $items->count() > $limit;
        
        $items = $items->take($limit)->map(function ($item) {
            return [
                'created_at' => Carbon::parse($item->created_at)->format('Y-m-d'),
                'amount' => $item->amount,
                'referrer_id' => $item->referrer_id,
                'label' => $item->label,
            ];
        });

        return response()->json([
            'items' => $items,
            'hasMore' => $hasMore,
        ]);
    }
}