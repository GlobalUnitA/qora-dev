@extends('layouts.master')

@section('content')
<main class="container-fluid py-5">
    <div class="text-center">
        <div class="py-4">
            <img src="{{ asset('images/icon/complete.svg') }}" width="46" alt="" class="img-fluid">
            <h5 class="mt-3" >{{ __('asset.withdrawal_apply_notice') }}</h5>
            <p class="break-keep-all">{{ __('asset.withdrawal_arrival_guide') }}</p>
        </div>
        <div class="card bg-light p-5 mx-3 mb-5">
            <h4 class="mb-2">{{ __('asset.withdrawal_amount') }}</h4>
            <h2 class="text-primary fs-8">{{ $amount }} USDT</h2>
        </div>        
    </div>
</main>

@endsection