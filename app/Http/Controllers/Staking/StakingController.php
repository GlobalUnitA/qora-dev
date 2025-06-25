<?php

namespace App\Http\Controllers\Staking;

use App\Models\Wallet;
use App\Models\Staking;
use App\Models\StakingPolicy;
use App\Models\StakingProfit;
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
        $wallets = Wallet::where('user_id', auth()->id())
            ->whereHas('coin', function ($query) {
            $query->where('is_active', 'y');
        })
        ->get();
        
        return view('staking.staking', compact('wallets'));
    }

    public function detail()
    {
        $stakings = Staking::where('user_id', auth()->id())->get();

        return view('staking.detail', compact('stakings'));
    }

    public function profit(Request $request)
    {
        $staking = Staking::find($request->id);
        $profits = StakingProfit::where('staking_id', $staking->id)->get();

        return view('staking.profit', compact('staking', 'profits'));
    }
    
    public function confirm($id)
    {
        $staking = StakingPolicy::find($id);

        $date = $this->getStakingDate($staking->period);

        return view('staking.confirm', compact('staking', 'date'));
    }

    
    public function data(Request $request)
    {
        $staking = StakingPolicy::where('coin_id', $request->coin)->get();

        return response()->json($staking);
    }
    public function store(Request $request)
    {
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now(); 

        $last_staking = Staking::where('user_id', auth()->id())->whereBetween('created_at', [$startDate, $endDate])->first();
        
        if ($last_staking) {
            return response()->json([
                'status' => 'error',
                'message' =>  '이미 스테이킹에 참여하셨습니다.',
            ]);
        } 

        $staking = StakingPolicy::find($request->staking);

        $min = $staking->min_quantity;
        $max = $staking->max_quantity;

        $validated = $request->validate([
            'amount' => "required|numeric|min:$min|max:$max",
        ], [
            'amount.required' => '금액을 입력해주세요.',
            'amount.numeric' => '숫자만 입력할 수 있습니다.',
            'amount.min' => "최소 수량은 {$min} 입니다.",
            'amount.max' => "최대 수량은 {$max} 입니다.",
        ]);

        DB::beginTransaction();

        try {

            $wallet = Wallet::where('user_id', auth()->id())->where('coin_id', $staking->coin_id)->first();

            if ($wallet->balance < $validated['amount']) {
                throw new \Exception('스테이킹에 실패하였습니다. 월렛의 잔액을 확인하시길 바랍니다.');
            }
    
            $date = $this->getStakingDate($staking->period);

            Staking::create([
                'user_id' => auth()->id(),
                'wallet_id' => $wallet->id,
                'staking_id' => $staking->id,
                'amount' => $validated['amount'],
                'period' => $staking->period,
                'started_at' => $date['start'],
                'ended_at' => $date['end'],
            ]);

            $wallet->update([
                'balance' => $wallet->balance - $validated['amount']
            ]);

            DB::commit();
        
            return response()->json([
                'status' => 'success',
                'message' => '스테이킹에 성공하였습니다.',
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