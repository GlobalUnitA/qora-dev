@extends('layouts.master')

@section('content')
<div class="container py-5">
    @if(request()->route('mode') == 'bonus')
    <h2 class="mb-3 text-center">{{ __('messages.purchase.staking_attend_history') }}</h2>
    <hr>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="mb-2">
                <tr>
                    <th class="text-center">{{ __('messages.layout.date') }}</th>
                    <th class="text-center">{{ __('messages.layout.quantity') }}</th>
                    <th class="text-center">{{ __('messages.layout.status') }}</th>
                    <th class="text-center">{{ __('messages.layout.details') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $key => $value)
                <tr>
                    <td class="text-center align-middle">{{ date_format($value->created_at, 'Y-m-d') }}</td>
                    <td class="text-center align-middle">{{ number_format($value->ath) }}</td>
                    <td class="text-center align-middle">
                        @if($value->status == 'p')
                        {{ __('messages.purchase.in_progress') }}
                        @elseif($value->status == 'c')
                        {{ __('messages.layout.cancel') }}
                        @elseif($value->status == 'e')
                        {{ __('messages.purchase.expiration') }}
                        @else
                        {{ __('messages.purchase.not_approved') }}
                        @endif
                    </td>
                    <td class="text-center align-middle ">
                        <a class="btn btn-primary m-1" href="{{ route('bonus.staking.view', ['mode' => request('mode'), 'id' => $value->id]) }}">확인</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $list->links('pagination::bootstrap-4') }}
    </div>
    <div class="d-flex justify-content-end mt-5">
        <a href="{{ route('bonus.staking.list', ['mode' => 'allowance']) }}" class="btn btn-primary">수당내역</a>
    </div>
    @else
    <h2 class="mb-3 text-center">{{ __('messages.purchase.aff_staking_attend_history') }}</h2>
    <hr>
    <div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="mb-2">
            <tr>
                <th class="text-center">{{ __('messages.layout.date') }}</th>
                <th class="text-center">MID</th>
                <th class="text-center">{{ __('messages.layout.level') }}</th>
                <th class="text-center">{{ __('messages.layout.quantity') }}</th>
                <th class="text-center">{{ __('messages.layout.details') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $key => $value)
            <tr>
                <td class="text-center align-middle">{{ date_format($value->created_at, 'Y-m-d') }}</td>
                <td class="text-center align-middle">{{ $value->aff_user_id }}</td>
                <td class="text-center align-middle">{{ number_format($value->aff_user_level) }}</td>
                <td class="text-center align-middle">{{ number_format($value->ath) }}</td>
                <td class="text-center align-middle ">
                    <a class="btn btn-primary m-1" href="{{ route('bonus.staking.view', ['mode' => request('mode'), 'id' => $value->id]) }}">확인</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $list->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection
