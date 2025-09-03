@extends('admin.layouts.master')

@section('content')

<div class="layoutContainer container min-vh-100 overflow-hidden px-0 bg-white border border-sm-0">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-90">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="mb-4">
                            <h3 class="text-center">{{ config('app.name', 'Laravel') }}</h3>
                            <h3 class="text-center">{{ __('관리자 로그인') }}</h3>
                        </div>
                        <form method="POST" id="ajaxForm" action="{{ route('admin.login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="inputAccount" class="form-label">{{ __('아이디') }}</label>
                                <input type="text" name="account" class="form-control" id="inputAccount" >
                            </div>
                            <div class="mb-4">
                                <label for="inputPassword" class="form-label">{{ __('비밀번호') }}</label>
                                <input type="password" name="password" class="form-control" id="inputPassword">
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="flexCheckChecked" checked>
                                    <label class="form-check-label text-dark" for="flexCheckChecked">
                                        {{ __('로그인 상태 유지') }}
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">{{ __('로그인') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
