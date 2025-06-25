@extends('layouts.master')

@section('content')

<header class="px-4 py-5 w-100 border-top-title" style="background: url('../images/tit_bg_01.png') center right no-repeat, #1e1e1f;" >
    <h2 class="text-white mb-1 px-1">Trading</h2>
    <h6 class="text-white px-1">{{ __('asset.trading') }}</h6>
    <!-- <div class="m-0 px-1">
        <a href="{{ route('asset.withdrawal.list') }}">
            <h5 class="btn btn-outline-light m-0">출금내역</h5>
        </a>
    </div> -->
</header>
<main class="container-fluid py-5 mb-5">
    <div class="d-flex flex-column align-items-center px-3 mb-5">
        <div class="text-center trading-video" style="border-radius: 50%; overflow: hidden; width: 17rem; height: 17rem; background-size: contain;">
        </div>
        <h3 class="mt-4 text-primary">{{ __('asset.trading_in_progress') }}</h3>
        <form method="POST" action="{{ route('trading.store') }}" id="tradingForm">
            @csrf
            <input type="hidden" name="coin" value="{{ $coin }}" >
        </form>
    </div>   
</main>

@endsection


@push('script')
<script src="{{ asset('js/trading/wait.js') }}"></script>
@endpush

