@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h2 class="mb-3 text-center">{{ __('asset.profit_detail') }}</h2>
    <hr>
    <div class="table-responsive overflow-x-auto mb-5">
        <table class="table table-striped table-bordered break-keep-all m-0 mb-5">
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
                @foreach($list as $key => $value)
                <tr>
                    <td>{{ $value->created_at->format('Y-m-d') }}</td>
                    <td>{{ $value->amount }}</td>
                    <td>  
                        @if ($value->profit)
                            {{ $value->profit->trading->profit_rate }}%
                        @elseif ($value->reward)
                            {{ $value->reward->staking->policy->daily }}%
                        @else
                            {{ '' }}
                        @endif
                    </td>
                    <td>
                        @if ($value->type === 'subscription_bonus')
                            {{ $value->subscriptionBonus ? 'C' . $value->subscriptionBonus->referrer_id : '' }}
                        @elseif ($value->type === 'referral_bonus')
                            {{ $value->referralBonus ? 'C' . $value->referralBonus->referrer_id : '' }}
                        @else
                            {{ '' }}
                        @endif
                    </td>
                    <td>{{ $value->type_text }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="text-center" colspan="5">No data.</td>
                </tr>
                @endif
            </tbody>
        </table>
        @if($has_more)
        <form method="POST" action="{{ route('income.list.loadMore') }}" id="loadMoreForm">
            @csrf
            <input type="hidden" name="offset" value="10">
            <input type="hidden" name="limit" value="10">
            <input type="hidden" name="type" value="{{ request('type') }}">
            <button type="submit" class="btn btn-outline-primary w-100 py-2 my-4 fs-4">{{ __('system.load_more') }}</button>
        </form>
        @endif
    </div>
</div>
@endsection

@push('script')
@verbatim
<template id="loadMoreTemplate">
    <tr>
        <td>{{created_at}}</td>
        <td>{{amount}}</td>
        <td>{{trading_profit}}</td>
        <td>{{referrer_id}}</td>
        <td>{{type_text}}</td>
    </tr>
</template>
@endverbatim
<script src="{{ asset('js/income/income.js') }}"></script>
@endpush