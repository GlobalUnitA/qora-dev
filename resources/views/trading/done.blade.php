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
    <div class="px-3 mb-5">
        <div class="text-center">
            <img src="{{ asset('images/robot.png') }}" width="300" alt="" >
            <h3 class="mt-4 text-primary">{{ __('asset.trading_completed') }}</h3>
            <a href="{{ route('trading', ['asset' => $asset]) }}">
                <h5 class="btn btn-primary w-100 py-3 mt-3 fs-4">{{ __('system.back') }}</h5>
            </a>
        </div>
    </div>   
</main>

@endsection