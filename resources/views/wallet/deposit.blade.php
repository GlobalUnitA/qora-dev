@extends('layouts.master')

@section('content')

<main class="container-fluid py-5 mb-5">
    <div class="px-3 mb-5">
        <div class="d-flex justify-content-between align-items-center w-100 mb-5">
            <h5 class="card-title m-0">재테크 월렛 입금하기</h5>
            <div class="m-0">
                <a href="{{ route('wallet.deposit.list') }}">
                    <h5 class="btn btn-outline-primary m-0">입금내역</h5>
                </a>
            </div>
        </div>
        <form method="POST" action="{{ route('wallet.deposit.store') }}" id="depositForm">
        @csrf
        <fieldset>
            <legend class="fs-3 text-dark mb-3">입금할 가상자산을 선택 해주세요.</legend>
            <div class="d-grid d-grid-col-2 mb-3">
                @foreach($wallets as $wallet)
                <div class="selectedWallet">
                    <input type="radio" name="wallet" value="{{ $wallet->encrypted_id }}" id="{{ $wallet->coin->code }}" class="btn-check" autocomplete="off">
                    <label class="btn btn-light w-100 p-4 rounded text-center fs-5 d-flex flex-column align-items-center" for="{{ $wallet->coin->code }}">
                        <img src="{{ $wallet->coin->image_urls[0] }}" width="40" alt="{{ $wallet->coin->code }}" class="img-fluid mb-2">
                        {{ $wallet->coin->code }}
                    </label>
                    <input type="hidden" value="{{ $wallet->deposit_fee_rate }}" class="deposit_fee_rate">
                </div>
                @endforeach                       
            </div>                
        </fieldset>
            <div class="mt-4 mb-3">
                <label class="form-label">입금 수량을 입력하세요.</label>
                <input type="text" name="amount" id="amount" class="form-control" placeholder="0">
            </div>
            <p id="feeString" class="mb-5"></p>
            <button type="submit" class="btn btn-primary w-100 py-3 mb-4 fs-4">입금하기</button>                                
        </form>
    </div>
</main>

@endsection

@push('script')
<script src="{{ asset('js/wallet/deposit.js') }}"></script>
@endpush
