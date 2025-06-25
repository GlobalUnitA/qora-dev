@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between">
                    <h5 class="card-title">보너스 정책</h5>
                    <a href="{{ route('admin.bonus.policy.export') }}" class="btn btn-primary">Excel</a>
                </div>
                <hr>
                <div>
                    <table class="table text-nowrap align-middle mb-0 table-striped">
                        <thead>
                            <tr class="border-2 border-bottom border-primary border-0"> 
                                <th scope="col" class="ps-0 text-center">직추천 인원</th>
                                <th scope="col" class="text-center">보너스</th>
                                <th scope="col" class="text-center" >수정일자</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                        @if($policies->isNotEmpty())
                            @foreach($policies as $policy)
                            <tr class="bonus_policy"> 
                                <input type="hidden" name="id" value="{{ $policy->id }}">
                                <td scope="col" class="ps-0 text-center">
                                    <input type="text" name="count" value="{{ $policy->count }}" class="form-control d-inline-block w-25"> 명
                                </td>
                                <td scope="col" class="text-center">
                                    <input type="text" name="bonus" value="{{ $policy->bonus }}" class="form-control d-inline-block w-25">
                                </td>
                                <td class="text-center">{{ $policy->updated_at }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-danger updateBtn">수정</button>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">정책이 없습니다.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <hr>
                    <form method="POST" action="{{ route('admin.bonus.policy.store') }}" id="ajaxForm" data-confirm-message="정책을 추가하시겠습니까?">
                    @csrf
                    <div class="row justify-content-center align-items-center mb-2">
                        <div class="col-auto">
                            <label class="form-label mb-0">인원:</label>
                        </div>
                        <div class="col-2 me-3">
                            <div class="d-flex align-items-center">
                                <input type="text" name="count" value="" class="form-control form-control-sm" style="width: 80px;">
                                <span class="ms-1">명</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <label class="form-label mb-0">보너스:</label>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center">
                                <input type="text" name="bonus" value="" class="form-control form-control-sm" style="width: 80px;">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-danger btn-sm">추가</button>
                        </div>
                    </div>
                    </form>
                </div>
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

<form method="POST" id="bonusPolicyForm" action="{{ route('admin.bonus.policy.update') }}">
    @csrf
</form>

@endsection

@push('script')
<script src="{{ asset('js/admin/bonus/policy.js') }}"></script>
@endpush