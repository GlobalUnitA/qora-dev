@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between">
                    <h5 class="card-title">트레이딩 정책</h5>
                </div>
                <form method="POST" action="{{ route('admin.trading.policy.update') }}" id="ajaxForm" data-confirm-message="정책을 변경하시겠습니까?">
                    @csrf
                    <hr>
                    <table class="table table-bordered mt-5 mb-5">
                        <tbody>
                            <tr>
                                <th class="text-center align-middle">트레이딩 횟수</th>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" name="trading_count" value="{{ $policy->trading_count }}" class="form-control w-25">
                                        <span>회</span>
                                    </div>
                                </td>
                                <th class="text-center align-middle">수익률</th>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" name="profit_rate" value="{{ $policy->profit_rate }}" class="form-control w-25">
                                        <span>%</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">트레이딩 가능 요일</th>
                                <td colspan="3" class="align-middle">
                                @foreach($all_days as $key => $label)
                                    <label class="me-2">
                                        <input type="checkbox" name="trading_days[]" value="{{ $label }}" class="form-check-input"
                                            {{ in_array($label, $selected_days) ? 'checked' : '' }}>
                                        {{ $label }}
                                    </label>
                                @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="d-flex justify-content-end align-items-center">
                        <button type="submit" class="btn btn-danger">수정</button>
                    </div>
                </form>
            </div>
        </div>
        @if($modify_logs->isNotEmpty())
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between">
                    <h5 class="card-title">정책 변경 로그</h5>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0 table-striped">
                        <thead>
                            <tr class="border-2 border-bottom border-primary border-0"> 
                                <th scope="col" class="ps-0 text-center">변경 내용</th>
                                <th scope="col" class="ps-0 text-center">변경 전</th>
                                <th scope="col" class="ps-0 text-center">변경 후</th>
                                <th scope="col" class="ps-0 text-center">관리자</th>
                                <th scope="col" class="ps-0 text-center">수정일자</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach($modify_logs as $key => $val)
                            <tr>
                                <td class="text-center">{{ $val->column_description }}</td>
                                <td class="text-center">{{ $val->old_value }}</td>
                                <td class="text-center">{{ $val->new_value }}</td>
                                <td class="text-center">{{ $val->name }}</td>
                                <td class="text-center">{{ $val->created_at }}</td>
                            </tr>                
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
