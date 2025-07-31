@extends('layouts.master')

@section('content')
<main class="container-fluid py-5 mb-5">
    <h2 class="mb-3 text-center">{{ __('asset.profit_detail') }}</h2>
    <hr>
    <div class="g-3 py-5">
        <div class="px-4 py-5 rounded bg-light text-body">
            <!--div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <p class="text-body fs-4 m-0">{{ __('asset.total_trading_profit') }}</p>
                    <h3 class="text-primary fs-6 mb-1">{{ $data['profit'] }}</h3>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <p class="text-body fs-4 m-0">{{ __('asset.total_subscription_bonus') }}</p>
                    <h3 class="text-primary fs-6 mb-1">{{ $data['subscription_bonus'] }}</h3>
                </div>
            </div-->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <p class="text-body fs-4 m-0">{{ __('staking.total_staking_profit') }}</p>
                    <h3 class="text-primary fs-6 mb-1">{{ $data['reward'] }}</h3>
                </div>
            </div>
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-start">
                    <p class="text-body fs-4 m-0">{{ __('asset.total_referral_bonus') }}</p>
                </div>
                <h3 class="text-primary fs-6 mb-1">{{ $data['referral_bonus'] }}</h3>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <p class="text-body fs-4 m-0">{{ __('asset.total_rank_bonus') }}</p>
                    <h3 class="text-primary fs-6 mb-1">{{ $data['rank_bonus'] }}</h3>
                </div>
            </div>
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-start">
                    <p class="text-body fs-4 m-0">{{ __('asset.total_external_withdrawal') }}</p>
                    <a href="{{ route('income.withdrawal') }}" class="btn btn-primary fs-4 py-1 px-3">{{ __('asset.withdrawal') }}</a>
                </div>
                <h3 class="text-primary fs-6 mb-1">{{ $data['withdrawal_total'] }}</h3>
            </div>
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-start">
                    <p class="text-body fs-4 m-0">{{ __('asset.total_internal_transfer') }}</p>
                    <a href="{{ route('income.deposit') }}" class="btn btn-primary fs-4 py-1 px-3">{{ __('asset.internal_transfer') }}</a>
                </div>
                <h3 class="text-primary fs-6 mb-1">{{ $data['deposit_total'] }}</h3>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-body fs-4 m-0">{{ __('asset.current_balance') }}</p>
                    <h3 class="text-primary fs-6 mb-1">{{ $data['balance'] }}</h3>
                </div>
            </div>         
        </div>
    </div>
    <div class="table-responsive pb-5">
        <table class="table table-striped table-bordered">
            <thead class="mb-2">
                <tr>
                    <th>{{ __('system.date') }}</th>
                    <th>{{ __('system.amount') }}</th>
                    <th>{{ __('asset.profit_rate') }}</th>
                    <th>{{ __('user.child_id') }}</th>
                    <th>
                        <select id="incomeTypeSelect" name="type" class="form-select form-select-sm">
                            <option value="">{{ __('system.category') }}</option>
                            <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>{{ __('asset.internal_transfer') }}</option>
                            <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>{{ __('asset.external_withdrawal') }}</option>
                            <option value="trading_profit" {{ request('type') == 'trading_profit' ? 'selected' : '' }}>{{ __('asset.trading_profit') }}</option>
                            <option value="subscription_bonus" {{ request('type') == 'subscription_bonus' ? 'selected' : '' }}>{{ __('asset.subscription_bonus') }}</option>
                            <option value="referral_bonus" {{ request('type') == 'referral_bonus' ? 'selected' : '' }}>{{ __('asset.referral_bonus') }}</option>
                            <option value="rank_bonus" {{ request('type') == 'rank_bonus' ? 'selected' : '' }}>{{ __('asset.rank_bonus') }}</option>
                            <option value="staking_reward" {{ request('type') == 'staking_reward' ? 'selected' : '' }}>{{ __('asset.staking_profit') }}</option>
                        <select>
                    </th>
                </tr>
            </thead>
            <tbody id="loadMoreContainer">              
                @if($list->isNotEmpty())
                @foreach($list as $key => $val)
                <tr>
                    <td>{{ date_format($val->created_at, 'Y-m-d') }}</td>
                    <td>{{ $val->amount }}</td>
                    <td>  
                        @if ($val->profit)
                            {{ $val->profit->trading->profit_rate }}%
                        @elseif ($val->reward)
                            {{ $val->reward->staking->policy->daily }}%
                        @else
                            {{ '' }}
                        @endif
                    </td>
                    <td>
                        @if ($val->type === 'subscription_bonus')
                            {{ $val->subscriptionBonus ? 'C' . $val->subscriptionBonus->referrer_id : '' }}
                        @elseif ($val->type === 'referral_bonus')
                            {{ $val->referralBonus ? 'C' . $val->referralBonus->referrer_id : '' }}
                        @else
                            {{ '' }}
                        @endif
                    </td>
                    <td>{{ $val->type_text }}</td>
                </tr>
                @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5">No data.</td>
                    </tr>
            </tbody>
        </table> 
        @if($has_more)
        <a href="{{ route('income.list',['id' => $data['encrypted_id']]) }}" class="btn btn-outline-primary w-100 py-2 my-4 fs-4">{{ __('system.load_more') }}</a>
        @endif
    </div>
    @endif
</main>
@endsection

@push('script')
<script src="{{ asset('js/income/income.js') }}"></script>
@endpush

