@extends('layouts.master')

@section('content')
<div class="container py-5">
   
    <h2 class="mb-3 text-center">{{ __('messages.purchase.purchase_history') }}</h2>
    <hr>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="mb-2">
                <tr>
                    <th>{{ __('messages.layout.date') }}</th>
                    <th>{{ __('messages.layout.status') }}</th>
                    <th>{{ __('messages.layout.quantity') }}</th>
                    <th>{{ __('messages.layout.price') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $key => $value)
                <tr>
                    <td>{{ $value->created_at }}</td>
                    <td>
                        @if($value->status == 'p')
                        {{ __('messages.purchase.approved') }}
                        @elseif($value->status == 'c')
                        {{ __('messages.layout.cancel') }}
                        @else
                        {{ __('messages.purchase.not_approved') }}
                        @endif
                    </td>
                    <td>{{ number_format($value->ea) }} EA</td>
                    <td>{{ number_format($value->usdt) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $list->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
