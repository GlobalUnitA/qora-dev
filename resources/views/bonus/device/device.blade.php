@extends('layouts.master')

@section('content')
<main class="container-fluid py-5 mb-5">
    <h2 class="mb-3 text-center">{{ __('messages.purchase.usdt_bonus_history') }}</h2>
    <hr>
    <div class="row g-3 my-5">
        <div class="col-12">
            <a href="{{ route('bonus.device.list' , ['mode' => 'ref']) }}">
                <div class="p-3 rounded bg-light text-black">
                    <p class="text-black fs-5 fw-medium">{{ __('messages.purchase.ref_bonus') }}</p>
                    <p class="text-black">{{ __('messages.purchase.weekly_total') }} {{ number_format($bonus['week_ref']) }}</p>
                    <p class="text-black">{{ __('messages.purchase.prefix_total') }} {{ number_format($bonus['full_ref']) }}</p>
                </div>
            </a>
        </div>
        <div class="col-12">
            <a href="{{ route('bonus.device.list' , ['mode' => 'aff']) }}">
                <div class="p-3 rounded text-black bg-primary-subtle">
                    <p class="text-black fs-5 fw-medium">{{ __('messages.purchase.aff_bonus') }}</p>
                    <p class="text-black">{{ __('messages.purchase.weekly_total') }} {{ number_format($bonus['week_aff']) }}</p>
                    <p class="text-black">{{ __('messages.purchase.prefix_total') }} {{ number_format($bonus['full_aff']) }}</p>
                </div>
            </a>
        </div>
    </div>
</main>
@endsection
