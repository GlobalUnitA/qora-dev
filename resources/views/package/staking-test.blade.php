@extends('layouts.master')

@section('content')
<div class="container py-5">
   
    <h2 class="mb-3 text-center">{{ __('messages.purchase.staking_attend_history') }}</h2>
    <hr>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="mb-2">
                <tr>
                    <th class="text-center">{{ __('messages.layout.quantity') }}</th>
                    <th class="text-center">{{ __('messages.layout.date') }}</th>
                    <th class="text-center">{{ __('messages.layout.details') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $key => $value)
                <tr>
                    <td class="text-center align-middle">{{ number_format($value->ath) }}</td>
                    <td class="text-center align-middle">{{ date_format($value->created_at, 'Y-m-d') }}</td>
                    <td class="text-center align-middle ">
                        <button class="btn btn-primary m-1" @if(isset($value->bonus) || isset($value->allowance)) onclick="toggleSubTable({{$key}})" @endif>확인</button>
                    </td>
                </tr>
                @if(isset($value->bonus) || isset($value->allowance))
                <tr class="table-hover" id="sub-table-{{ $key }}" style="display: none;">
                    <td colspan="3">
                        @if(isset($value->bonus))
                        <h5 class="mb-3">{{ __('messages.purchase.bonus_history') }}</h5>
                        <hr>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ __('messages.purchase.daily') }}</th>
                                    <th class="text-center">{{ __('messages.purchase.payment') }}(30%)</th>
                                    <th class="text-center">{{ __('messages.purchase.lock_up') }}(70%)</th>
                                    <th class="text-center">{{ __('messages.layout.date') }}</th>
                                </tr>
                            </thead>
                            <tbody>         
                                @foreach ($value->bonus as $k => $v)
                                <tr>
                                    <td class="text-center">{{ $v->daily }}</td>
                                    <td class="text-center">{{ $v->paid }}</td>
                                    <td class="text-center">{{ $v->earn }}</td>
                                    <td class="text-center">{{ date_format($v->created_at, 'Y-m-d') }}</td>
                                </tr>
                                @endforeach 
                            </tbody>
                        </table>
                        @endif
                        @if(isset($value->allowance))
                        <h5 class="mb-3r">{{ __('messages.purchase.allowance_history') }}</h5>
                        <hr>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ __('messages.purchase.aff_id') }}</th>
                                    <th class="text-center">{{ __('messages.purchase.daily') }}</th>
                                    <th class="text-center">{{ __('messages.purchase.payment') }}(30%)</th>
                                    <th class="text-center">{{ __('messages.purchase.lock_up') }}(70%)</th>
                                    <th class="text-center">{{ __('messages.layout.date') }}</th>
                                </tr>
                            </thead>
                            <tbody>         
                                @foreach ($value->allowance as $k => $v)
                                <tr>
                                    <td class="text-center">{{ $v->aff_user_id }}</td>
                                    <td class="text-center">{{ $v->daily }}</td>
                                    <td class="text-center">{{ $v->paid }}</td>
                                    <td class="text-center">{{ $v->earn }}</td>
                                    <td class="text-center">{{ date_format($v->created_at, 'Y-m-d') }}</td>
                                </tr>
                                @endforeach 
                            </tbody>
                        </table>
                        @endif
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $list->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
