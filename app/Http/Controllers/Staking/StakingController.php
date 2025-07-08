<?php

namespace App\Http\Controllers\Staking;

use App\Models\Asset;
use App\Models\Income;
use App\Models\Staking;
use App\Models\StakingPolicy;
use App\Models\StakingReward;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;

class StakingController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        $assets = Asset::where('user_id', auth()->id())
            ->whereHas('coin', function ($query) {
            $query->where('is_active', 'y');
        })
        ->get();
        
        return view('staking.staking', compact('assets'));
    }

    public function detail()
    {
        $stakings = Staking::where('user_id', auth()->id())->get();

        return view('staking.detail', compact('stakings'));
    }

    public function profit(Request $request)
    {
        $staking = Staking::find($request->id);
        $profits = StakingReward::where('staking_id', $staking->id)->get();

        return view('staking.profit', compact('staking', 'profits'));
    }
    
    public function confirm($id)
    {
        $staking = StakingPolicy::find($id);

        $asset = Asset::where('user_id', auth()->id())
            ->where('coin_id', $staking->coin_id)
            ->first();
        $balance = $asset->balance;

        $date = $this->getStakingDate($staking->period);

        return view('staking.confirm', compact('staking', 'date', 'balance'));
    }

    
    public function data(Request $request)
    {
        $staking = StakingPolicy::where('coin_id', $request->coin)->get();

        return response()->json($staking->toArray());
    }
    public function store(Request $request)
    {
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now(); 
        
        $staking = StakingPolicy::find($request->staking);

        $min = $staking->min_quantity;
        $max = $staking->max_quantity;

        if ($request->amount < $min || $request->amount > $max) {

            return response()->json([
                'status' => 'error',
                'message' =>  __('staking.participation_quantity_notice', ['min' => $min, 'max' => $max]),
            ]);
        }

        DB::beginTransaction();

        try {

            $asset = Asset::where('user_id', auth()->id())->where('coin_id', $staking->coin_id)->first();
            $income = Income::where('user_id', auth()->id())->where('coin_id', $staking->coin_id)->first();

            if ($asset->balance < $request->amount) {
                throw new \Exception(__('asset.lack_balance_notice'));
            }
    
            $date = $this->getStakingDate($staking->period);

            Staking::create([
                'user_id' => auth()->id(),
                'asset_id' => $asset->id,
                'income_id' => $income->id,
                'staking_id' => $staking->id,
                'amount' => $request->amount,
                'period' => $staking->period,
                'started_at' => $date['start'],
                'ended_at' => $date['end'],
            ]);

            $asset->update([
                'balance' => $asset->balance - $request->amount
            ]);

            DB::commit();
        
            return response()->json([
                'status' => 'success',
                'message' => __('staking.staking_success_notice'),
                'url' => route('home'),
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' =>  $e->getMessage(),
            ]);
        
        }
        
    }

    private function getStakingDate($period)
    {
        $start = Carbon::today()->addDays(1);
        return [
            'start' => $start,
            'end' => $start->copy()->addDays($period-1),
        ];
    }
    
}   