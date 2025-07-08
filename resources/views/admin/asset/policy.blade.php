@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between">
                    <h5 class="card-title">자산 정책</h5>
                </div>
                <form method="POST" action="{{ route('admin.asset.policy.update') }}" id="ajaxForm" data-confirm-message="정책을 변경하시겠습니까?">
                    @csrf
                    <hr>
                    <table class="table table-bordered mt-5 mb-5">
                        <tbody>
                            <tr>
                                <th class="text-center align-middle">입금 반영 기간</th>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" name="deposit_period" value="{{ $policy->deposit_period }}" class="form-control w-25">
                                        <span>일</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">최소 보유금액</th>
                                <td class="align-middle">
                                    <input type="text" name="min_valid" value="{{ $policy->min_valid }}" class="form-control w-25">
                                </td>
                                <th class="text-center align-middle">최소 출금금액</th>
                                <td class="align-middle">
                                    <input type="text" name="min_withdrawal" value="{{ $policy->min_withdrawal }}" class="form-control w-25">
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">DAO 수수료 비율</th>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" name="fee_rate" value="{{ $policy->fee_rate }}" class="form-control w-25">
                                        <span>%</span>
                                    </div>
                                </td>
                                <th class="text-center align-middle">세금 비율</th>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" name="tax_rate" value="{{ $policy->tax_rate }}" class="form-control w-25">
                                        <span>%</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">출금 가능 요일</th>
                                <td colspan="3" class="align-middle">
                                @foreach($all_days as $key => $label)
                                    <label class="me-2">
                                        <input type="checkbox" name="withdrawal_days[]" value="{{ $label }}" class="form-check-input"
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
