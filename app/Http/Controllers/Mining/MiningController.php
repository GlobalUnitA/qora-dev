<?php

namespace App\Http\Controllers\Mining;


use App\Models\Asset;
use App\Models\AssetTransfer;
use App\Models\Income;
use App\Models\IncomeTransfer;
use App\Models\Mining;
use App\Models\MiningPolicy;
use App\Models\MiningReward;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class MiningController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $assets = Asset::where('user_id', auth()->id())
            ->whereHas('coin', function ($query) {
                $query->where('is_mining', 'y');
            })
            ->get();

        return view('mining.mining', compact('assets'));
    }

    public function data(Request $request)
    {
        $Mining = MiningPolicy::where('coin_id', $request->coin)->get();

        return response()->json($Mining->toArray());
    }

    public function list()
    {
        $minings = Mining::where('user_id', auth()->id())->get();

        return view('mining.list', compact('minings'));
    }

    public function confirm($id)
    {
        $mining = MiningPolicy::find($id);

        $asset = Asset::where('user_id', auth()->id())
            ->where('coin_id', $mining->coin_id)
            ->first();

        $balance = $asset->balance;

        $date = $this->getMiningDate($mining);

        return view('mining.confirm', compact('mining', 'date', 'balance'));
    }

    public function store(Request $request)
    {

        $policy = MiningPolicy::find($request->policy);

        $asset = Asset::where('user_id', auth()->id())->where('coin_id', $policy->coin_id)->first();
        $refund = Asset::where('user_id', auth()->id())->where('coin_id', $policy->refund_coin_id)->first();
        $reward = Income::where('user_id', auth()->id())->where('coin_id', $policy->reward_coin_id)->first();

        if ($asset->balance < $request->coin_amount) {
            return response()->json([
                'status' => 'error',
                'message' =>  __('asset.lack_balance_notice'),
            ]);
        }

        DB::beginTransaction();

        try {

            $date = $this->getMiningDate($policy);

            $mining = Mining::create([
                'user_id' => auth()->id(),
                'asset_id' => $asset->id,
                'refund_id' => $refund->id,
                'reward_id' => $reward->id,
                'policy_id' => $policy->id,
                'coin_amount' => $request->coin_amount,
                'refund_coin_amount' => $request->refund_coin_amount,
                'node_amount' => $request->node_amount,
                'exchange_rate' => $request->exchange_rate,
                'period' => $policy->period,
                'reward_count' => 0,
                'started_at' => $date['start'],
                'ended_at' => $date['end'],
            ]);

            AssetTransfer::create([
                'user_id' => $mining->user_id,
                'asset_id' => $asset->id,
                'type' => 'mining',
                'status' => 'completed',
                'amount' => $request->coin_amount,
                'actual_amount' => $request->coin_amount,
                'before_balance' => $asset->balance,
                'after_balance' => $asset->balance - $request->coin_amount,
            ]);

            $asset->update([
                'balance' => $asset->balance - $request->coin_amount
            ]);

            $instant_reward = $mining->getInstantReward();

            $transfer = IncomeTransfer::create([
                'user_id' => $mining->user_id,
                'income_id' => $reward->id,
                'type' => 'mining_reward',
                'status' => 'completed',
                'amount' => $instant_reward,
                'actual_amount' => $instant_reward,
                'before_balance' => $reward->balance,
                'after_balance' => $reward->balance + $instant_reward,
            ]);

            MiningReward::create([
                'user_id' => $mining->user_id,
                'mining_id' => $mining->id,
                'transfer_id' => $transfer->id,
                'type' => 'instant',
                'reward' => $instant_reward,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => __('mining.mining_success_notice'),
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

    private function getMiningDate($policy)
    {
        $start = Carbon::today()->addDays($policy->waiting_period+1);
        return [
            'start' => $start,
            'end' => $start->copy()->addDays($policy->period-1),
        ];
    }

}
