@extends('layouts.master')

@section('content')
<div class="container py-5">
    @if(request()->route('mode') == 'bonus')
    <h2 class="mb-3 text-center">{{ __('messages.purchase.staking') }} {{ __('messages.purchase.bonus_history') }}</h2>
    @else
    <h2 class="mb-3 text-center">{{ __('messages.purchase.staking') }} {{ __('messages.purchase.allowance_history') }}</h2>
    @endif
    <hr>
    <div class="mb-3">
        @if(request()->route('mode') == 'allowance')
        <div class="">{{ __('messages.purchase.aff_id') }}: {{ $view->user_id }}</div>
        @endif
        <div class="">{{ __('messages.purchase.mining_start_date') }}: {{ date_format($view->started_at, 'Y-m-d') }}</div>
        <div class="">{{ __('messages.purchase.mining_end_date') }}: {{ date_format($view->ended_at, 'Y-m-d') }}</div>
        <div class="">{{ __('messages.purchase.contract_period') }}: {{ $view->term }}년</div>
        <div class="">{{ __('messages.purchase.payment_price') }}: {{ number_format($view->sum, 2) }}</div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="mb-2">
                <tr>
                    <th class="text-center">{{ __('messages.layout.date') }}</th>
                    <th class="text-center">{{ __('messages.purchase.number_of_days') }}</th>
                    <th class="text-center">{{ __('messages.purchase.payment') }}</th>
                    <th class="text-center">{{ __('messages.purchase.first_lock_up') }}</th>
                    <th class="text-center">{{ __('messages.purchase.second_lock_up') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($view->bonus ?? $view->allowance as $key => $value)
                <tr>
                    <td class="text-center align-middle">{{ date_format($value->created_at, 'Y-m-d') }}</td>
                    <td class="text-center align-middle">{{ $value->day }}</td>
                    <td class="text-center align-middle">{{ number_format($value->instant_reward, 2) }}</td>
                    <td class="text-center align-middle ">{{ number_format($value->first_lock, 2) }} ({{ date_format($value->first_unlocked_at, 'Y-m-d') }})</td>
                    <td class="text-center align-middle ">{{ number_format($value->second_lock, 2) }} ({{ date_format($value->second_unlocked_at, 'Y-m-d') }})</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ ($view->bonus ?? $view->allowance)->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection