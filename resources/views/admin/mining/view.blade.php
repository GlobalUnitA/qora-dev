@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                            {{ __('마이닝 정보') }}
                    </h5>
                    <div>{{ $view->created_at }}</div>
                </div>
                <hr>
                <table class="table table-bordered mt-5 mb-5">
                    <tbody>
                        <tr>
                            <th class="text-center align-middle">아이디</th>
                            <td class="align-middle">{{ $view->user->account }}</td>
                            <th class="text-center align-middle">이름</th>
                            <td class="align-middle">{{ $view->user->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-center align-middle">종류</th>
                            <td class="align-middle">{{ $view->income->coin->name }}</td>
                            <th class="text-center align-middle">참여수량</th>
                            <td class="align-middle">{{ $view->node_amount }}</td>
                        </tr>
                        <tr>
                            <th class="text-center align-middle">상태</th>
                            <td class="align-middle">
                                @if($view->status == 'pending')
                                    {{ __('진행중') }}
                                @elseif($view->status == 'completed')
                                    {{ __('완료') }}
                                @else
                                    {{ __('오류') }}
                                @endif
                            </td>
                            <th class="text-center align-middle">계약날짜</th>
                            <td class="align-middle">{{ date_format($view->maturity_at, 'Y-m-d') }}</td>
                        </tr>
                        <tr>
                            <th class="text-center align-middle">시작일</th>
                            <td class="align-middle">{{ date_format($view->started_at, 'Y-m-d') }}</td>
                            <th class="text-center align-middle">종료일</th>
                            <td class="align-middle">{{ date_format($view->ended_at, 'Y-m-d') }}</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                @if($view->type == 'rank_bonus')
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap align-middle mb-0 table-striped table-hover">
                                <thead>
                                    <tr class="border-2 border-bottom border-primary border-0">
                                        <th scope="col" class="text-center">UID</th>
                                        <th scope="col" class="text-center">이름</th>
                                        <th scope="col" class="text-center">등급</th>
                                        <th scope="col" class="text-center">개인매출</th>
                                        <th scope="col" class="text-center">그룹매출</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    @foreach($view->rankBonus->referrals as $referral)
                                    <tr>
                                        <td scope="col" class="text-center">{{ $referral->user->id }}</td>
                                        <td scope="col" class="text-center">{{ $referral->user->name }}</td>
                                        <td scope="col" class="text-center">{{ $referral->user->profile->grade->name }}</td>
                                        <td scope="col" class="text-center">{{ $referral->self_sales }}</td>
                                        <td scope="col" class="text-center">{{ $referral->group_sales }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('admin.asset.list') }}" class="btn btn-secondary">목록</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
