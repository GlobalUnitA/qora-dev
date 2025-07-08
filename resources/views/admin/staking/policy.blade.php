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
                                <th scope="col" class="text-center">타입</th>
                                <th scope="col" class="text-center">참여수량</th>
                                <th scope="col" class="text-center">데일리 <br> 수익률</th>
                                <th scope="col" class="text-center">기간</th>
                                <th scope="col" class="text-center">수정일자</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                        @if($policies->isNotEmpty())
                            @foreach($policies as $key => $val)
                            <tr class="staking_policy" style ="cursor:pointer;" onclick="window.location='{{ route('admin.staking.policy.view', ['mode' => 'view', 'id' => $val->id]) }}'">
                                <td class="text-center">{{ $val->staking_locale_name }}</td>
                                <td class="text-center">
                                    @if($val->staking_type == 'daily')
                                    원리금 지급형
                                    @else
                                    원금 반환형
                                    @endif
                                </td>
                                <td class="text-center">{{ $val->min_quantity }}&nbsp; ~ &nbsp;{{ $val->max_quantity }}</td>
                                <td class="text-center">{{ $val->daily }}%</td>
                                <td class="text-center">{{ $val->period }}일</td>
                                <td class="text-center">{{ $val['updated_at'] }}</td>
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
                    <div class="d-flex mt-5">     
                        <a href="{{ route('admin.staking.policy.view', ['mode' => 'create']) }}" class="btn btn-info ms-auto">스테이킹 추가</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form method="POST" id="stakingPolicyForm" action="{{ route('admin.staking.policy.update') }}" >
    @csrf
</form>

@endsection

@push('script')
<script src="{{ asset('js/admin/staking/policy.js') }}"></script>
@endpush