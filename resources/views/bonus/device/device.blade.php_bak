@extends('layouts.master')

@section('content')
<main class="container-fluid py-3">
    <h2 class="mt-5 mb-3 text-center">USDT 보너스 내역</h2>
    <hr>
    <div class="row g-3 mt-3">
        <div class="col-12">
            <a href="{{ route('bonus.device.list' , ['mode' => 'ref']) }}">
                <div class="p-3 rounded bg-light text-black">
                    <p class="text-black fs-5">추천보너스</p>
                    <p class="text-black">주간합계 {{ number_format($bonus['week_ref']) }}</p>
                    <p class="text-black">누적합계 {{ number_format($bonus['full_ref']) }}</p>
                </div>
            </a>
        </div>
        <div class="col-12">
            <a href="{{ route('bonus.device.list' , ['mode' => 'aff']) }}">
                <div class="p-3 rounded text-black" style="background-color: #e8f5e9;">
                    <p class="text-black fs-5">산하보너스</p>
                    <p class="text-black">주간합계 {{ number_format($bonus['week_aff']) }}</p>
                    <p class="text-black">누적합계 {{ number_format($bonus['full_aff']) }}</p>
                </div>
            </a>
        </div>
    </div>
</main>
@endsection
