@extends('layouts.master')

@section('content')

<main class="container-fluid py-5 mb-5">
    <div class="px-3 mb-5">
        <div class="d-flex justify-content-between align-items-center w-100 mb-5">
            <h5 class="card-title m-0">재테크 스테이킹 참여하기</h5>
        </div>            
        <div class="my-4">
            <label class="form-label">기간</label>
            <input type="text" value="{{ $staking->period }}" class="form-control mb-3" readonly>
        </div>
        <div class="my-4">
            <label class="form-label">예상수익률</label>
            <input type="text" value="{{ $staking->daily }}%" class="form-control mb-3" readonly>
        </div>
        <div class="mt-4 mb-5">
            <label class="form-label">참여수량</label>
            <input type="text" value="{{ $staking->min_quantity }} ~ {{ $staking->max_quantity }}" class="form-control mb-3" readonly>
        </div>
        <div class="p-4 rounded bg-light text-black mb-2">
            <div class="row g-3">
                <div class="col-6">
                    <p class="text-dark fs-4 m-0">시작일</p>
                    <h3 class="text-primary fs-6 mb-1">{{ date_format($date['start'], 'Y-m-d') }}</h3>
                </div>                        
                <div class="col-6">
                    <p class="text-dark fs-4 m-0">마감일</p>
                    <h3 class="text-primary fs-6 mb-1">{{ date_format($date['end'], 'Y-m-d') }}</h3>
                </div>                        
            </div>                                          
        </div>
        <p class="mb-5">스테이킹 마감되면 재테크 월렛에 참여 수량 및 수익이 반영 됩니다.</p>
        <form method="post" action="{{ route('staking.store') }}" id="stakingForm">
            @csrf
            <input type="hidden" name="staking" value="{{ $staking->id }}">
            <div class="my-4 pb-3">
                <label class="form-label">참여수량을 입력하세요.</label>
                <input type="text" name="amount" id="amount" class="form-control mb-3" placeholder=0 min="{{ $staking->min_quantity }}" max="{{ $staking->max_quantity }}">
            </div>
            <button type="submit" class="btn btn-primary w-100 py-3 mb-4 fs-4" >참여하기</button>
        </form>
    </div>
</main>

@endsection

@push('script')
<script src="{{ asset('js/staking/staking.js') }}"></script>
@endpush