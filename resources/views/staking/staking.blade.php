@extends('layouts.master')

@section('content')
<header class="p-4 w-100 border-top-title" style="background: url('../images/tit_bg_01.png') center right no-repeat, #1e1e1f;" >
    <h2 class="text-white mb-1 px-1">Staking</h2>
    <h6 class="text-white mb-4 px-1">{{ __('staking.staking') }}</h6>
    <div class="m-0 px-1">
        <a href="{{ route('staking.detail') }}">
            <h5 class="btn btn-primary border-0 m-0" style="background: linear-gradient(to right, #3163F8, #8d3efb);">{{ __('staking.participation_list') }}</h5>
        </a>
    </div>
</header>
<main class="container-fluid py-5 mb-5">
    <div class="px-3 mb-5">
        <fieldset class="mb-4">
            <legend class="fs-4 text-body mb-3">{{ __('staking.select_staking_asset_guide') }}</legend>
            <div class="d-grid d-grid-col-2 mb-3">        
                @foreach($assets as $asset)   
                <div>
                    <input type="radio" name="coin_check" value="{{ $asset->coin->id }}" id="{{ $asset->coin->code }}" class="btn-check" autocomplete="off">
                    <label class="btn btn-light w-100 p-4 rounded text-center fs-5 d-flex flex-column align-items-center" for="{{ $asset->coin->code }}">
                        <img src="{{ asset($asset->coin->image_urls[0]) }}" width="40" alt="{{ $asset->coin->code }}" class="img-fluid mb-2">
                        {{ $asset->coin->name }}
                    </label>
                </div>
                @endforeach                        
            </div>                
        </fieldset>
        <fieldset id="stakingData" class="d-none">
            <!-- <legend class="fs-3 mb-3">{{ __('staking.select_staking_category_guide') }}</legend> -->
            <div id="stakingDataContainer"></div>
        </fieldset>
        <!-- <ul class="nav nav-underline mt-3 mb-3 fs-6" id="staking-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="staking-01-tab" data-bs-toggle="pill" data-bs-target="#staking-01" type="button" role="tab" aria-controls="staking-01" aria-selected="true">Set01</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="staking-02-tab" data-bs-toggle="pill" data-bs-target="#staking-02" type="button" role="tab" aria-controls="staking-02" aria-selected="false">Set02</button>
            </li>
        </ul>
        <div class="tab-content" id="staking-tabContent">
            <div class="tab-pane fade show id="staking-01" role="tabpanel" aria-labelledby="staking-01-tab" tabindex="0">
                여기에 첫번째 탭 컨텐츠
            </div>
            <div class="tab-pane fade show active id="staking-02" role="tabpanel" aria-labelledby="staking-02-tab" tabindex="0">
                여기에 두번째 탭 컨텐츠
            </div>
        </div> -->
        <div class="mt-4">
            <h6>{{ __('staking.profit_generated') }}</h6>
            <p class="mb-1">- {{ __('staking.profit_generated_guide1') }}</p>
            <p class="mb-1">- {{ __('staking.profit_generated_guide2') }}</p>
        </div>
    </div>
    <form method="POST" action="{{ route('staking.data')}}" id="stakingDataForm">
        @csrf
        <input type="hidden" name="coin" value="">
    </form>
</main>

@endsection

@push('script')
<template id="stakingDataTemplate">
    <div class="mb-4 stakingData">
        <div class="bg-light w-100 p-4 rounded fs-5">
            <div class="row g-3 text-start">
                <div class="col-12 p-0">
                    <span class="fs-5 text-primary fw-semibold staking-name"></span>
                </div>
                <div class="col-12">
                    <p class="fs-4 fw-light m-0">{{ __('staking.participation_quantity') }}</p>
                    <p class="fs-6 m-0 fw-semibold text-body staking-amount"></p>
                </div>                        
                <div class="col-6">
                    <p class="fs-4 fw-light m-0">{{ __('staking.expected_daily_profit_rate') }}</p>
                    <p class="fs-6 m-0 fw-semibold text-body staking-rate"></p>
                </div>                        
                <div class="col-6">
                    <p class="fs-4 fw-light m-0">{{ __('system.period') }}</p>
                    <p class="fs-6 m-0 fw-semibold text-body staking-period"></p>
                </div>
            </div>
            <button type="button" class="btn btn-primary w-100 py-2 mt-4 fs-4 staking-btn">{{ __('staking.participate') }}</button>
        </div>        
    </div>
</template>
<script src="{{ asset('js/staking/staking.js') }}"></script>
@endpush