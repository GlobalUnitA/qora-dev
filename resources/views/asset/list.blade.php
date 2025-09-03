@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h2 class="mb-3 text-center">
   
    </h2>
    <hr>
    <div class="table-responsive overflow-x-auto">
        <table class="table table-striped table-bordered break-keep-all">
            <thead class="mb-2">
                <tr>
                    <th>{{ __('system.date') }}</th>
                    <th>{{ __('system.amount') }}</th>
                    <th>{{ __('system.category') }}</th>
                </tr>
            </thead>
            <tbody id="loadMoreContainer">
                @if(!empty($list))
                @foreach($list as $key => $val)
                <tr>
                    <td>{{ $val->created_at }}</td>
                    <td>{{ $val->amount }}</td>
                    <td>{{ $val->type_text }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan=4 class="text-center">no data.</td>
                </tr>
                @endif
            </tbody>
        </table>
        @if($has_more)
        <form method="POST" action="{{ route('asset.list.loadMore') }}" id="loadMoreForm" class="mb-5">
            @csrf
            <input type="hidden" name="mode" value="{{ request()->input('mode') }}">
            <input type="hidden" name="offset" value="10">
            <input type="hidden" name="limit" value="10">
            <input type="hidden" name="encrypted_id" value="{{ $encrypted_id }}">
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
        <td>{{type_text}}</td>
    </tr>
</template>
@endverbatim
@endpush
