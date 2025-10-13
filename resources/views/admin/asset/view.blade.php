@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        @if($view->type == 'withdrawal')
                            {{ __('출금 정보') }}
                        @else
                            {{ __('입금 정보') }}
                        @endif
                    </h5>
                    <div>{{ $view->created_at }}</div>
                </div>
                @if($view->type == 'withdrawal')
                <form method="POST" action="{{ route('admin.asset.withdrawal.update') }}" id="ajaxForm">
                @else
                <form method="POST" action="{{ route('admin.asset.deposit.update') }}" id="ajaxForm">
                @endif
                    @csrf
                    <input type="hidden" name="id" value="{{ $view->id }}">
                    <hr>
                    <table class="table table-bordered table-fixed mt-5 mb-5">
                        <tbody>
                            <tr>
                                <th class="text-center align-middle">아이디</th>
                                <td class="align-middle" style="min-width: 150px;">{{ $view->user->account }}</td>
                                <th class="text-center align-middle">이름</th>
                                <td class="align-middle" style="min-width: 150px;">{{ $view->user->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">종류</th>
                                <td class="align-middle">{{ $view->asset->coin->name }}</td>
                                <th class="text-center align-middle">수량</th>
                                <td class="align-middle">
                                    @if($view->type == 'deposit' && $view->status == 'pending')
                                    <input type="text" name="amount" value="{{ $view->amount }}" class="form-control w-25" />
                                    @else
                                    {{ $view->amount }}
                                    @endif
                                </td>
                            </tr>
                            @if($view->type == 'deposit')
                            <tr>
                                <th class="text-center align-middle">상태</th>
                                <td colspan="3" class="align-middle">
                                    @if($view->status == 'pending')
                                    <select name="status" id="category" class="form-select w-25">
                                        <option value="pending">입금신청</option>
                                        {{--<option value="waiting">입금대기</option>--}}
                                        <option value="completed">입금완료</option>
                                        <option value="canceled">입금취소</option>
                                        <option value="refunded">입금반환</option>
                                    </select>
                                    @else
                                    {{ $view->status_text }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">TXID</th>
                                <td colspan="3" class="align-middle">{{ $view->txid }}</td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">이미지</th>
                                <td colspan=3 class="align-middle">
                                    <div class="text-center align-middle">
                                        @if($download_url)
                                            <a href="{{ $download_url }}" target='_blank'>
                                                <img src="{{ $download_url }}" class="img-fluid" style="height:300px">
                                            </a>
                                        @else
                                            <span>이미지가 없습니다.</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @elseif($view->type == 'withdrawal')
                            <tr>
                                <th class="text-center align-middle">세금</th>
                                <td class="align-middle">{{ $view->tax }}</td>
                                <th class="text-center align-middle">수수료</th>
                                <td class="align-middle">{{ $view->fee }}</td>
                            </tr>
                            <tr>
                                <th class="text-center align-middle">상태</th>
                                <td colspan="3" class="align-middle">
                                    @if($view->status == 'pending')
                                    <select name="status" id="category" class="form-select w-25">
                                        <option value="pending" @if($view->status == 'pending') selected @endif>출금신청</option>
                                        <option value="completed" @if($view->status == 'completed') selected @endif>출금완료</option>
                                        <option value="canceled" @if($view->status == 'canceled') selected @endif>출금취소</option>
                                    </select>
                                    @else
                                    {{ $view->status_text }}
                                    @endif
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th class="text-center align-middle">메모</th>
                                <td colspan=3 class="align-middle">
                                    <textarea name="memo" class="form-control" id="memo" rows="12" >{{ $view->memo }}</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('admin.asset.list') }}" class="btn btn-secondary">목록</a>
                        </div>
                        @if (auth()->guard('admin')->user()->admin_level >= 2 )
                        <div>
                            <button type="submit" class="btn btn-danger">수정</button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        @if(!empty($referral_bonus))
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">추천 보너스 목록</h5>
                </div>
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0 table-striped table-hover">
                        <thead>
                        <tr class="border-2 border-bottom border-primary border-0">
                            <th scope="col" class="text-center">번호</th>
                            <th scope="col" class="text-center">UID</th>
                            <th scope="col" class="text-center">이름</th>
                            <th scope="col" class="text-center">등급</th>
                            <th scope="col" class="text-center">종류</th>
                            <th scope="col" class="text-center">보너스 / 매칭</th>
                            <th scope="col" class="text-center">상태</th>
                            <th scope="col" class="text-center">산하ID</th>
                            <th scope="col" class="text-center">입금금액 / 보너스</th>
                            <th scope="col" class="text-center">일자</th>
                        </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach ($referral_bonus as $bonus )
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $bonus->user_id }}</td>
                                <td class="text-center">{{ $bonus->user->name }}</td>
                                <td class="text-center">{{ $bonus->user->profile->grade->name }}</td>
                                <td class="text-center">{{ $bonus->transfer->income->coin->name }}</td>
                                <td class="text-center">{{ $bonus->bonus }}</td>
                                <td scope="col" class="text-center">
                                    @switch($bonus->transfer->status)
                                        @case('pending')
                                            {{ __('신청') }}
                                            @break
                                        @case('waiting')
                                            {{ __('대기') }}
                                            @break
                                        @case('completed')
                                            {{ __('완료') }}
                                            @break
                                        @case('canceled')
                                            {{ __('취소') }}
                                            @break
                                        @default
                                            {{ __('환불') }}
                                    @endswitch
                                </td>
                                <td class="text-center">{{ $bonus->referrer_id }}</td>
                                <td class="text-center">{{ $bonus->deposit->amount }}</td>
                                <td class="text-center">{{ $bonus->transfer->created_at }}</td>
                            </tr>
                                @foreach($bonus->matchings as $matching)
                                    <tr>
                                        <td class="text-center"><i class="bi bi-arrow-return-right"></i></td>
                                        <td class="text-center">{{ $matching->user_id }}</td>
                                        <td class="text-center">{{ $matching->user->name }}</td>
                                        <td class="text-center">{{ $matching->user->profile->grade->name }}</td>
                                        <td class="text-center">{{ $matching->transfer->income->coin->name }}</td>
                                        <td class="text-center">{{ $matching->matching }}</td>
                                        <td scope="col" class="text-center">
                                            @switch($matching->transfer->status)
                                                @case('pending')
                                                    {{ __('신청') }}
                                                    @break
                                                @case('waiting')
                                                    {{ __('대기') }}
                                                    @break
                                                @case('completed')
                                                    {{ __('완료') }}
                                                    @break
                                                @case('canceled')
                                                    {{ __('취소') }}
                                                    @break
                                                @default
                                                    {{ __('환불') }}
                                            @endswitch
                                        </td>
                                        <td class="text-center">{{ $matching->referrer_id }}</td>
                                        <td class="text-center">{{ $matching->bonus->bonus }}</td>
                                        <td class="text-center">{{ $matching->transfer->created_at }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
