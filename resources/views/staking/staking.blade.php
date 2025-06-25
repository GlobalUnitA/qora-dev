@extends('layouts.master')

@section('content')

<main class="container-fluid py-5 mb-5">
    <div class="px-3 mb-5">        
        <div class="d-flex justify-content-between align-items-center w-100 mb-5">
            <h5 class="card-title m-0">재테크 스테이킹 참여하기</h5>
            <div class="m-0">
                <a href="{{ route('staking.detail') }}">
                    <h5 class="btn btn-outline-primary m-0">참여내역</h5>
                </a>
            </div>
        </div>
        <fieldset class="mb-4">
            <legend class="fs-3 text-dark mb-3">스테이킹 참여할 가상자산을 선택 해주세요.</legend>
            <div class="d-grid d-grid-col-2 mb-3">        
                @foreach($wallets as $wallet)   
                <div>
                    <input type="radio" name="coin_check" value="{{ $wallet->coin->id }}" id="{{ $wallet->coin->code }}" class="btn-check" autocomplete="off">
                    <label class="btn btn-light w-100 p-4 rounded text-center fs-5 d-flex flex-column align-items-center" for="{{ $wallet->coin->code }}">
                        <img src="{{ asset($wallet->coin->image_urls[0]) }}" width="40" alt="{{ $wallet->coin->code }}" class="img-fluid mb-2">
                        {{ $wallet->coin->name }}
                    </label>
                </div>
                @endforeach                        
            </div>                
        </fieldset>
        <fieldset id="stakingData" class="d-none">
            <!-- <legend class="fs-3 text-dark mb-3">스테이킹 유형을 선택 해주세요.</legend> -->
            <div id="stakingDataContainer"></div>
        </fieldset>        
        <div class="mt-4">
            <h6>수익발생</h6>
            <p class="mb-1">- 스테이킹 참여 후, +1일 00시부터 수익 발생</p>
            <p class="mb-1">- 예시) 1월 1일 참여, 1월 2일 00시 이후 첫 수익 발생</p>
            <p class="mb-1">- 스테이킹 기간 만료 후, 수익 및 스테이킹 수량 반환</p>
            <p class="mb-1">- 주의사항: 30일 동안, 한 계정은 스테이킹 1회만 참여 가능.</p>
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
    <div class="mb-4">
        <div class="bg-light w-100 p-4 rounded fs-5">
            <div class="row g-3 text-start">
                <div class="col-12">
                    <span class="fs-4 text-primary fw-semibold staking-name"></span>
                </div>
                <div class="col-12">
                    <p class="fs-4 fw-light m-0">참여수량</p>
                    <p class="fs-6 m-0 fw-semibold text-black staking-amount"></p>
                </div>                        
                <div class="col-6">
                    <p class="fs-4 fw-light m-0">예상수익률(1일)</p>
                    <p class="fs-6 m-0 fw-semibold text-black staking-rate"></p>
                </div>                        
                <div class="col-6">
                    <p class="fs-4 fw-light m-0">기간</p>
                    <p class="fs-6 m-0 fw-semibold text-black staking-period"></p>
                </div>
            </div>
            <button type="button" class="btn btn-primary w-100 py-2 mt-4 fs-4 staking-btn">참여하기</button>
        </div>        
    </div>
</template>
<script src="{{ asset('js/staking/staking.js') }}"></script>
@endpush

