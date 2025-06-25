@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h2 class="mb-3 text-center">{{ __('asset.profit_detail') }}</h2>
    <hr>
    <div class="table-responsive overflow-x-auto mb-5">
        <table class="table table-striped table-bordered break-keep-all m-0 mb-5">
            <thead class="mb-2">
                <tr>
                    <th>{{ __('system.date') }}</th>
                    <th>{{ __('system.amount') }}</th>
                    <th>{{ __('user.child_id') }}</th>
                    <th>{{ __('system.category') }}</th>
                </tr>
            </thead>
            <tbody id="loadMoreContainer">
                @foreach($list as $key => $value)
                <tr>
                    <td>{{ $value->created_at->format('Y-m-d') }}</td>
                    <td>{{ $value->amount }}</td>
                    <td>{{ $value->bonus ? 'C'. $value->bonus->referrer_id : '' }}</td>
                    <td>{{ $value->type_text }}</td>
                </tr>
                @endforeach                
            </tbody>
        </table>
        @if($has_more)
        <form method="POST" action="{{ route('income.list.loadMore') }}" id="loadMoreForm">
            @csrf
            <input type="hidden" name="offset" value="3">
            <input type="hidden" name="limit" value="3">
            <button type="submit" class="btn btn-outline-primary w-100 py-2 my-4 fs-4">{{ __('system.load_more') }}</button>
        </form>
        @endif
    </div>
</div>
@endsection

@push('script')
@verbatim
<template id="loadMoreTemplate">
    <tr>
        <td>{{created_at}}</td>
        <td>{{amount}}</td>
        <td>{{trading_profit}}%</td>
        <td>{{referrer_id}}</td>
        <td>{{type_text}}</td>
    </tr>
</template>
@endverbatim
@endpush