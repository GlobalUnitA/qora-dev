<?php

namespace App\Http\Controllers\Trading;

use App\Models\User;
use App\Models\Coin;
use App\Models\Asset;
use App\Models\Income;
use App\Models\IncomeTransfer;
use App\Models\Trading;
use App\Models\TradingProfit;
use App\Models\TradingPolicy;
use App\Models\Bonus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;

class TradingController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(Request $request)
    {  

        $user = User::find(auth()->id());
        $assets = Asset::where('user_id', $user->id)
        ->whereHas('coin', function ($query) {
            $query->where('is_active', 'y');
        })
        ->get();

        $trading_policy = TradingPolicy::first();

        $profit_rate = $trading_policy->profit_rate;

        $is_valid = false;
        
        if ($user->profile->is_valid === 'y') {
            $is_valid = true;
        }
        
        $available_day = $trading_policy->isTradingAvailableToday();

        $selected_asset = null;
        $data = null;

        if ($request->has('asset')) {
            $selected_asset = $assets->firstWhere('id', Hashids::decode($request->input('asset'))[0]);
        }

        if (!$selected_asset && $request->has('team')) {
            $selected_asset = $assets->first();
        }

        if ($selected_asset) {
            $data = $selected_asset->getAssetInfo();
        }

        $subscription_bonuses = Bonus::where('user_id', $user->id)->latest('created_at')->get();
        
        $tab = $request->has('team') ? 'team' : 'my';

        return view('trading.trading', compact('assets', 'selected_asset', 'data', 'tab', 'subscription_bonuses', 'available_day', 'is_valid', 'profit_rate'));
    }

    public function wait(Request $request)
    {   
        $coin = $request->coin;

        return view('trading.wait', compact('coin'));
    }

    public function done(Request $request)
    {
        $asset = $request->asset;

        return view('trading.done', compact('asset'));
    }

    public function store(Request $request)
    {
       
        DB::beginTransaction();

        try {

            $user = User::find(auth()->id());
            $coin = Coin::find($request->coin);

            $asset = Asset::where('user_id', $user->id)
                ->where('coin_id', $coin->id)
                ->first();

            $income = Income::where('user_id', $user->id)
                ->where('coin_id', $coin->id)
                ->first();

            $trading_policy = TradingPolicy::first();

            $today = Carbon::today();
            $tomorrow = Carbon::tomorrow();
         
            $trading = Trading::where('user_id', $user->id)
                ->where('coin_id', $coin->id)
                ->whereBetween('created_at', [$today, $tomorrow->copy()->subSecond()])
                ->first();
            
            if ($trading) {
                if ($trading->current_count >= $trading->max_count) {
                    throw new \Exception( __('asset.trading_limit_notice'));
                }

                $trading->increment('current_count');
        
            } else {

                if (0 >= $trading->max_count) {
                    throw new \Exception( __('asset.trading_limit_notice'));
                }
    
                $balance = $asset->balance;

                $daily = ($balance * $trading_policy->profit_rate) / 100;

                $trading = Trading::create([
                    'user_id' => $user->id,
                    'coin_id' => $coin->id,
                    'balance' => $balance,
                    'daily' => $daily,
                    'current_count' => 1,
                    'max_count' => $trading_policy->trading_count,
                    'profit_rate' => $trading_policy->profit_rate,
                ]);

            }

            $trading_profit = $trading->daily / $trading->max_count;

            $incomeTransfer = IncomeTransfer::create([
                'user_id'   => $user->id,
                'income_id'  => $income->id,
                'type' => 'trading_profit',
                'status' => 'completed',
                'amount'    => $trading_profit,
                'actual_amount' => $trading_profit,
                'before_balance' => $income->balance,
                'after_balance' => $income->balance + $trading_profit,
            ]);

            TradingProfit::create([
                'user_id' => $user->id,
                'trading_id' => $trading->id,
                'transfer_id' => $incomeTransfer->id,
                'profit' => $trading_profit,
            ]);
            
            $income->increment('balance', $trading_profit);

            DB::commit();

            return redirect()->route('trading.done', ['asset' => $asset->encrypted_id]);
    
        } catch (\Exception $e) {

            DB::rollBack();

            throw new \Exception( __('system.error_notice'). $e->getMessage());
        
        }
    
    }

    public function list()
    {
        $limit = 10;

        $list = Bonus::where('user_id', auth()->id())
            ->latest()
            ->take($limit)
            ->get();

        $total_count = Bonus::where('user_id', auth()->id())
            ->count();

        $has_more = $total_count > $limit;

        return view('trading.list', compact('list', 'has_more', 'limit'));
    }

    public function loadMore(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);

        $query = Bonus::where('user_id', auth()->id())->latest();

        $items = $query->skip($offset)->take($limit + 1)->get();

        $hasMore = $items->count() > $limit;
        
        $items = $items->take($limit)->map(function ($item) {
            return [
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'referrer_id' => $item->referrer_id,
                'bonus' => number_format(floor($item->bonus * 10000) / 10000, 4),
            ];
        });

        return response()->json([
            'items' => $items,
            'hasMore' => $hasMore,
        ]);
    }

}