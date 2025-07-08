@extends('layouts.master')

@section('content')
<header class="px-4 py-5 w-100 border-top-title" style="background: url('https://cubeaibot.com/images/tit_bg_01.png') center right no-repeat, #1e1e1f;" >
    <h2 class="text-white mb-1 px-1">Staking</h2>
    <h6 class="text-white px-1">{{ __('staking.staking') }}</h6>
    <!-- <div class="m-0 px-1">
        <a href="{{ route('asset.withdrawal.list') }}">
            <h5 class="btn btn-outline-light m-0">출금내역</h5>
        </a>
    </div> -->
</header>
<main class="container-fluid py-5 mb-5">
    <div class="px-3 mb-5">          
        <div class="my-4">
            <label class="form-label">{{ __('system.period') }}</label>
            <input type="text" value="{{ $staking->period }}" class="form-control mb-3" readonly>
        </div>
        <div class="my-4">
            <label class="form-label">{{ __('staking.expected_daily_profit_rate') }}</label>
            <input type="text" value="{{ $staking->daily }}%" class="form-control mb-3" readonly>
        </div>
        <div class="mt-4 mb-5">
            <label class="form-label">{{ __('staking.participation_quantity') }}</label>
            <input type="text" value="{{ $staking->min_quantity }} ~ {{ $staking->max_quantity }}" class="form-control mb-3" readonly>
        </div>
        <div class="p-4 rounded bg-light text-black mb-2">
            <div class="row g-3">
                <div class="col-6">
                    <p class="text-body fs-4 m-0">{{ __('system.started_at') }}</p>
                    <h3 class="text-primary fs-6 mb-1">{{ date_format($date['start'], 'Y-m-d') }}</h3>
                </div>                        
                <div class="col-6">
                    <p class="text-body fs-4 m-0">{{ __('system.ended_at') }}</p>
                    <h3 class="text-primary fs-6 mb-1">{{ date_format($date['end'], 'Y-m-d') }}</h3>
                </div>                        
            </div>                                          
        </div>
        <p class="mb-5">{{ $staking->staking_locale_memo }}</p>
        <form method="post" action="{{ route('staking.store') }}" id="ajaxForm">
            @csrf
            <input type="hidden" name="staking" value="{{ $staking->id }}">
            <div class="mb-3">
                <label class="form-label fs-4 text-body">{{ __('staking.participation_quantity_guide') }}</label>
                <input type="text" name="amount" id="amount" class="form-control" placeholder=0 min="{{ $staking->min_quantity }}" max="{{ $staking->max_quantity }}">
            </div>
            <p class="mb-5 opacity-50 fw-light fs-4">{{ __('asset.assets_held') }}: <span class="fw-bold">{{ $balance }}</span></p>
            <button type="submit" class="btn btn-primary w-100 py-3 mb-4 fs-4" >{{ __('staking.participate') }}</button>
        </form>
    </div>
</main>

@endsection

@push('script')
<script src="{{ asset('js/staking/staking.js') }}"></script>
@endpush