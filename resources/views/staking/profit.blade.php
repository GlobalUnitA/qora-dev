@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h2 class="mb-3 text-center">재테크 스테이킹 수익
    </h2>
    <hr>
    <div class="table-responsive overflow-x-auto pt-3">        
        <table class="table table-striped table-bordered break-keep-all">
            <thead class="mb-2">
                <tr>
                    <th>일자</th>
                    <th>참여수량</th>
                    <th>상태</th>
                </tr>
            </thead>
            <tbody>                
                <tr>
                    <td>{{ date_format($staking->created_at, 'Y-m-d h:i:s') }}</td>
                    <td>{{ number_format($staking->amount) }}</td>
                    <td>{{ $staking->status_text }}</td>
                </tr>
            </tbody>
        </table>        
    </div>
    <div class="table-responsive overflow-x-auto pt-3">
        <h5>상세내역</h5>
        <table class="table table-striped table-bordered break-keep-all">
            <thead class="mb-2">
                <tr>
                    <th>일자</th>
                    <th>수익</th>
                    <th>상태</th>
                </tr>                
            </thead>
            <tbody>
                @foreach($profits as $profit)            
                <tr>
                    <td>{{ date_format($profit->created_at, 'Y-m-d h:i:s') }}</td>
                    <td>{{ rtrim(rtrim(number_format($profit->profit, 9, '.', ''), '0'), '.') }}</td>
                    <td>{{ $profit->status_text }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>        
    </div>
</div>
@endsection
