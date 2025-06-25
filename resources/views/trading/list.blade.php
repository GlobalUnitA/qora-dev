@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h2 class="mb-3 text-center">{{ __('asset.subscription_bonus_list') }}</h2>
    <hr>
    <div class="table-responsive">
        <table class="table table-striped table-bordered m-0">
            <thead class="mb-2">
                <tr>
                    <th>{{ __('system.date') }}</th>
                    <th>{{ __('user.recommender_uid') }}</th>
                    <th>{{ __('system.amount') }}</th>
                </tr>
            </thead>
            <tbody id="loadMoreContainer"> 
                @foreach($list as $key => $value)
                <tr>
                    <td>{{ $value->created_at->format('Y-m-d h:i:s') }}</td>
                    <td>C{{ $value->referrer_id }}</td>
                    <td>{{ number_format(floor( $value->bonus * 10000) / 10000, 4) }}</td>
                </tr>
                @endforeach                
            </tbody>
        </table>
        @if($has_more)
        <form method="POST" action="{{ route('trading.list.loadMore') }}" id="loadMoreForm">
            @csrf
            <input type="hidden" name="offset" value="10">
            <input type="hidden" name="limit" value="10">
            <button type="submit" class="btn btn-outline-primary w-100 py-2 my-4 fs-4">더보기</button>
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
        <td>C{{referrer_id}}</td>
        <td>{{bonus}}</td>
    </tr>
</template>
@endverbatim
@endpush