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
                            <td class="align-middle">{{ $view->wallet->coin->name }}</td>
                            <th class="text-center align-middle">수량</th>
                            <td class="align-middle">{{ $view->amount }}</td>
                        </tr>
                        <tr>
                            <th class="text-center align-middle">수수료</th>
                            <td colspan=3 class="align-middle">{{ $view->fee }}</td>
                        </tr>
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
                    <div>
                        <button type="submit" class="btn btn-danger">수정</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection