@extends('layouts.master')

@section('content')
<main class="container-fluid py-5 mb-5">
    <h2 class="mb-3 text-center">{{ $data['coin_name'] }} {{ __('asset.asset_detail') }}</h2>
    <hr>
    <div class="g-3 my-5">
        <div class="p-4 rounded bg-primary-subtle text-body mb-4">
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-primary fs-4 m-0">{{ __('asset.today_profit') }}</p>
                    <a href="{{ route('asset.detail', ['mode' => 'today']) }}">
                        <p class="btn btn-outline-primary btn-sm py-1 px-3 m-0">{{ __('system.detail') }}</p>
                    </a>
                </div>
                <h3 class="text-primary fs-6 mb-1">{{ $data['profit']['today'] }}</h3>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-primary fs-4 m-0">{{ __('asset.total_asset') }}</p>
                    <h3 class="text-primary fs-6 mb-1">{{ $data['balance'] }}</h3>
                </div>
                <!--a href="{{ route('asset.detail') }}">
                    <p class="btn btn-outline-primary btn-sm py-1 px-3 mb-1 ms-2">상세</p>
                </a-->
            </div>            
        </div>
        <div class="p-4 rounded bg-light text-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <p class="text-body fs-4 m-0">{{ __('asset.total_profit') }}</p>
                    <h3 class="text-body fs-6 mb-1">{{ $data['profit']['total'] + $data['bonus']['total'] }}</h3>
                </div>
                <!-- <a href="{{ route('asset.detail') }}">
                    <p class="btn btn-outline-inverse btn-sm py-1 px-3 mb-1 ms-2">상세</p>
                </a> -->
            </div>
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-body fs-4 m-0">{{ __('asset.total_trading_profit') }}</p>
                    <a href="{{ route('asset.detail', ['mode' => 'profit']) }}">
                        <p class="btn btn-outline-inverse btn-sm py-1 px-3 mb-1 ms-2">{{ __('system.detail') }}</p>
                    </a>
                </div>
                <h3 class="text-body fs-6 mb-1">{{ $data['profit']['total'] }}</h3>
            </div>
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-body fs-4 m-0">{{ __('asset.total_subscription_bonus') }}</p>
                    <a href="{{ route('asset.detail', ['mode' => 'bonus']) }}">
                        <p class="btn btn-outline-inverse btn-sm py-1 px-3 mb-1 ms-2">{{ __('system.detail') }}</p>
                    </a>
                </div>
                <h3 class="text-body fs-6 mb-1">{{ $data['bonus']['total'] }}</h3>
            </div>
            <div>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-body fs-4 m-0">{{ __('asset.total_group_sales') }}</p>
                    <a href="{{ route('asset.detail', ['mode' => 'group']) }}">
                        <p class="btn btn-outline-inverse btn-sm py-1 px-3 mb-1 ms-2">{{ __('system.detail') }}</p>
                    </a>
                </div>
                <h3 class="text-body fs-6 mb-1">{{ $data['group_sales'] }}</h3>
            </div>            
        </div>
    </div>
</main>
@endsection
