@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h2 class="mb-3 text-center">{{ __('asset.withdrawal_list') }}</h2>
    <hr>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="mb-2">
                <tr>
                    <th>{{ __('system.date') }}</th>
                    <th>{{ __('asset.assets') }}</th>
                    <th>{{ __('system.status') }}</th>
                    <th>{{ __('system.amount') }}</th>
                </tr>
            </thead>
            <tbody id="loadMoreContainer"> 
                @foreach($list as $key => $value)               
                <tr>
                    <td>{{ $value->created_at->format('Y-m-d') }}</td>
                    <td>{{ $value->income->coin->code }}</td>
                    <td>{{ $value->status_text }}</td>
                    <td>{{ $value->amount }}</td>
                </tr>
                @endforeach
            </tbody>
        </table> 
        @if($has_more)
        <form method="POST" action="{{ route('income.withdrawal.list.loadMore') }}" id="loadMoreForm">
            @csrf
            <input type="hidden" name="offset" value="10">
            <input type="hidden" name="limit" value="10">
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
        <td>{{coin_code}}</td>
        <td>{{status_text}}</td>
        <td>{{amount}}</td>
    </tr>
</template>
@endverbatim
@endpush