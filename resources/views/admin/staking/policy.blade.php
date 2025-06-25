.@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <ul class="nav nav-tabs mt-3" id="tableTabs" role="tablist" >
            @foreach($coins as $coin)
            <li class="nav-item" role="presentation">
                <a href="{{ route('admin.staking.policy', ['id' => $coin->id]) }}" class="nav-link @if(request('id') == $coin->id) active @endif">
                    {{ $coin->name }}
                </a>
            </li>
            @endforeach
        </ul>
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between">
                    <h5 class="card-title">스테이킹 정책</h5>
                    <a href="{{ route('admin.staking.policy.export') }}" class="btn btn-primary">Excel</a>
                </div>
                <hr>
                <div>
                    <table class="table text-nowrap align-middle mb-0 table-striped">
                        <thead>
                            <tr class="border-2 border-bottom border-primary border-0"> 
                                <th scope="col" class="ps-0 text-center">상품이름</th>
                                <th scope="col" class="text-center">참여수량</th>
                                <th scope="col" class="text-center">데일리 <br> 수익률</th>
                                <th scope="col" class="text-center">기간</th>
                                <th scope="col" class="text-center" >수정일자</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                        @if($policies->isNotEmpty())
                            @foreach($policies as $key => $val)
                            <tr class="staking_policy">
                                <input type="hidden" name="id" value="{{ $val->id }}">
                                <td class="text-center">{{ $val['staking_name'] }}</td>
                                <td class="text-center d-flex align-items-center justify-content-center">
                                    <input type="text" name="min_quantity" value="{{ $val['min_quantity'] }}" class="form-control d-inline-block w-45">
                                    &nbsp; ~ &nbsp;
                                    <input type="text" name="max_quantity" value="{{ $val['max_quantity'] }}" class="form-control d-inline-block w-45">
                                </td>
                                <td class="text-center">
                                    <input type="text" name="daily" value="{{ $val['daily'] }}" class="form-control">
                                </td>
                                <td class="text-center">
                                    <input type="text" name="period" value="{{ $val['period'] }}" class="form-control">
                                </td>
                                <td class="text-center">{{ $val['updated_at'] }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-danger updateBtn">수정</button>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">스테이킹 상품이 없습니다.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <hr>
                    <form method="POST" action="{{ route('admin.staking.policy.store') }}" id="ajaxForm" data-confirm-message="상품을 추가하시겠습니까?">
                        @csrf
                        <input type="hidden" name="coin_id" value="{{ request('id') }}">
                        <div class="row justify-content-center align-items-center mb-2 gx-2">
                            <div class="col-auto">
                                <label class="form-label mb-0">상품이름:</label>
                            </div>
                            <div class="col-2 me-3">
                                <input type="text" name="staking_name" value="" class="form-control form-control-sm">
                            </div>

                            <div class="col-auto">
                                <label class="form-label mb-0">참여 수량:</label>
                            </div>
                            <div class="col-auto me-3">
                                <div class="d-flex align-items-center gap-1">
                                    <input type="text" name="min_quantity" value="" class="form-control form-control-sm" style="width: 80px;">
                                    <span class="mx-1">~</span>
                                    <input type="text" name="max_quantity" value="" class="form-control form-control-sm" style="width: 80px;">
                                </div>
                            </div>

                            <div class="col-auto">
                                <label class="form-label mb-0">수익률:</label>
                            </div>
                            <div class="col-auto me-3">
                                <div class="d-flex align-items-center">
                                    <input type="text" name="daily" value="" class="form-control form-control-sm" style="width: 80px;">
                                    <span class="ms-1">%</span>
                                </div>
                            </div>

                            <div class="col-auto">
                                <label class="form-label mb-0">기간:</label>
                            </div>
                            <div class="col-auto me-3">
                                <div class="d-flex align-items-center">
                                    <input type="text" name="period" value="" class="form-control form-control-sm" style="width: 80px;">
                                    <span class="ms-1">일</span>
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
                                <th scope="col" class="ps-0 text-center">변경 상품</th>
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
                                <td class="text-center">{{ $val->staking_name }}</td>        
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

<form method="POST" id="stakingPolicyForm" action="{{ route('admin.staking.policy.update') }}" >
    @csrf
</form>

@endsection

@push('script')
<script src="{{ asset('js/admin/staking/policy.js') }}"></script>
@endpush