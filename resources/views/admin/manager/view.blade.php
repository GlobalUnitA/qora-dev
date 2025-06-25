@extends('admin.layouts.master')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-between">
                            <h5 class="card-title">관리자 정보</h5>
                        </div>
                        @if(auth()->guard('admin')->user()->admin_level < 4)
                        <form method="POST" action="{{ route('admin.manager.update') }}" id="ajaxForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $view->id }}"/>
                            <hr>
                            <table class="table table-bordered mt-5 mb-5">
                                <tbody>
                                    <tr>
                                        <th class="text-center align-middle">이름</th>
                                        <td class="align-middle">{{ $view->name }}</td>
                                        <th class="text-center align-middle">아이디</th>
                                        <td class="align-middle">
                                            {{ $view->account }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center align-middle">레벨</th>
                                        <td class="align-middle">{{ $view->admin_level }}</td>
                                         <th class="text-center align-middle">비밀번호</th>
                                        <td class="align-middle">
                                            <input type="password" name="password" value="" placeholder="변경을 희망하지 않으면 빈칸으로 두세요." class="form-control">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.manager.list') }}" class="btn btn-secondary">목록</a>
                                @if(auth()->guard('admin')->user()->id == $view->id)
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-danger">수정</button>
                                </div>
                                @endif
                            </div>
                        </form>
                        @else
                        <form method="POST" action="{{ route('admin.manager.update') }}" id="ajaxForm">
                            @csrf    
                            <input type="hidden" name="id" value="{{ $view->id }}"/>
                            <hr>
                            <table class="table table-bordered mt-5 mb-5">
                                <tbody>
                                    <tr>
                                        <th class="text-center align-middle">이름</th>
                                        <td class="align-middle">
                                        @if($view->admin_level >= 4)
                                            {{ $view->name }}
                                        @else
                                            <input type="text" name="name" value="{{ $view->name }}" class="form-control w-50" />
                                        @endif
                                        </td>
                                        <th class="text-center align-middle">아이디</th>
                                        <td class="align-middle">
                                            {{ $view->account }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-center align-middle">레벨</th>
                                        <td class="align-middle">
                                        @if($view->admin_level >= 4)
                                            {{ $view->admin_level }}
                                        @else
                                        <select name="admin_level" class="form-control w-50">
                                            <option value="">레벨 선택</option>
                                            <option value="1" @selected($view->admin_level == '1')>1</option>
                                            <option value="2" @selected($view->admin_level == '2')>2</option>
                                            <option value="3" @selected($view->admin_level == '3')>3</option>
                                        </select>
                                        @endif
                                        </td>
                                         <th class="text-center align-middle">비밀번호</th>
                                        <td class="align-middle">
                                            <input type="password" name="password" value="" placeholder="변경을 희망하지 않으면 빈칸으로 두세요." class="form-control">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.manager.list') }}" class="btn btn-secondary">목록</a>
                                <div class="d-flex justify-content-between">
                                    <button type="button" id="deleteBtn" class="btn btn-info me-2" data-id="{{ $view->id }}">삭제</button>
                                    <button type="submit" class="btn btn-danger">수정</button>
                                </div>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('admin.manager.delete') }}" id="deleteForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $view->id }}"/>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('js/admin/manager/delete.js') }}"></script>
@endpush
