@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h2 class="mb-3 text-center">스테이킹 참여내역</h2>
    <hr>
    @foreach($stakings as $staking)
    <div class="table-responsive overflow-x-auto pt-3">        
        <table class="table table-striped table-bordered break-keep-all">
            <thead class="mb-2">
                <tr>
                    <th>일자</th>
                    <th>참여수량</th>
                    <th>수익(1일)</th>
                </tr>
            </thead>
            <tbody>                
                <tr>
                    <td>{{ date_format($staking->created_at, 'Y-m-d h:i:s') }}</td>
                    <td>{{ number_format($staking->amount) }}</td>
                    <td>{{ rtrim(rtrim(number_format($staking->getDailyProfit(), 9, '.', ''), '0'), '.') }}</td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-center align-items-center w-100 mb-3">
            <a href="{{ route('staking.profit', ['id' => $staking->id]) }}">
                <h5 class="btn btn-outline-primary m-0">스테이킹 수익</h5>
            </a>
        </div>
    </div>
    @endforeach
</div>
@endsection
