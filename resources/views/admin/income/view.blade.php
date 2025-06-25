@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        @if($view->type == 'deposit')
                            {{ __('내부이체 정보') }}
                        @elseif($view->type == 'withdrawal')
                            {{ __('외부출금 정보') }}
                        @elseif($view->type == 'subcripion_bonus')
                            {{ __('보너스 정보') }}
                        @else
                            {{ __('수익 정보') }}
                        @endif
                    </h5>    
                    <div>{{ $view->created_at }}</div>
                </div>
                @if($view->type == 'deposit')
                <form method="POST" action="{{ route('admin.income.deposit.update') }}" id="ajaxForm">  
                @elseif($view->type == 'withdrawal')
                <form method="POST" action="{{ route('admin.income.withdrawal.update') }}" id="ajaxForm">  
                @else
                <form method="POST" action="{{ route('admin.income.update') }}" id="ajaxForm">  
                @endif
                    @csrf
                    <input type="hidden" name="id" value="{{ $view->id }}">
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
                                <th class="text-center align-middle">수량</th>
                                <td class="align-middle">{{ $view->amount }}</td>
                            </tr>
                            @if($view->type == 'deposit')
                            <tr>
                                <th class="text-center align-middle">상태</th>
                                <td colspan="3" class="align-middle">
                                    @if($view->status == 'pending')
                                    <select name="status" id="category" class="form-select w-25">
                                        <option value="pending" @if($view->status == 'pending') selected @endif>입금신청</option>
                                        <option value="waiting" @if($view->status == 'completed') selected @endif>입금대기</option>
                                        <option value="canceled" @if($view->status == 'canceled') selected @endif>입금취소</option>
                                        <option value="refunded" @if($view->status == 'refunded') selected @endif>입금반환</option>
                                    </select>
                                    @else
                                    {{ $view->status_text }}
                                    @endif
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
    </div>
</div>
@endsection