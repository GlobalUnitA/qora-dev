@extends('layouts.master')

@section('content')

<main class="container-fluid py-5 mb-5">
    <div class="px-3 mb-5">
        <h5 class="m-0 card-title mb-5">재테크</h5>
        <div class="mb-5">
            <div>
                <table class="table w-auto m-0">
                    <thead>
                        <tr>
                            <th class="border-0 px-0"><h5 class="mb-1">재테크 월렛</h5></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wallet_infos as $info)
                        <tr>
                            <th class="border-0 px-0 py-2 text-dark fs-5 fw-light lh-md">{{ $info['coin_name'] }}</th>
                            <td class="border-0 px-0 py-2 text-primary fs-6 fw-semibold text-end">{{ number_format($info['balance'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>            
            <div class="row g-3 my-4">
                <div class="col-6">
                    <a href="{{ route('wallet.deposit') }}" class="btn btn-outline-primary w-100">입금</a>
                </div>
                <div class="col-6">
                    <a href="{{ route('wallet.withdrawal') }}" class="btn btn-outline-primary w-100">출금</a>
                </div>
            </div>
            <p class="mb-1">- 월렛에 이체 시 2일차부터 {{ $wallet_policy->profit_rate }}% 수익발생 자동 발생</p>
            <p class="mb-5">- 입금 수수료 {{ number_format($wallet_policy->deposit_fee_rate) }}%, 출금 수수료 {{ number_format($wallet_policy->withdrawal_fee_rate) }}% 발생
            @foreach($wallet_infos as $info)
            <div class="p-4 rounded bg-light mb-3">
                <h5 class="btn btn-dark btn-point-none border-0 py-1 px-3 mb-3 text-white">{{ $info['coin_name'] }}</h5>
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <p class="text-dark fs-4 m-0">오늘 수익</p>
                        <h3 class="text-black fs-6 mb-1">{{ number_format($info['profit']['today']['all']) }}</h3>
                    </div>                        
                    <div class="col-12 col-sm-6">
                        <p class="text-dark fs-4 m-0">총 수익</p>
                        <h3 class="text-black fs-6 mb-1">{{ number_format($info['profit']['total']['all']) }}</h3>
                    </div>                        
                </div>
            </div>
            @endforeach
            <a class="btn btn-primary w-100 py-3 mt-5 fs-4" href="{{ route('staking') }}">스테이킹 참여하기</a>
        </div>
    </div>
</main>

@endsection
