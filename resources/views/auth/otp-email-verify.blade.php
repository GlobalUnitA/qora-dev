@extends('layouts.master')

@section('content')
<header class="px-4 py-5 w-100 border-top-title" style="background: url('../images/tit_bg_01.png') center right no-repeat, #1e1e1f;" >
    <h2 class="text-white mb-1 px-1">Security</h2>
    <h6 class="text-white px-1">{{ __('system.security') }}</h6>
    <!-- <div class="m-0 px-1">
        <a href="{{ route('asset.withdrawal.list') }}">
            <h5 class="btn btn-outline-light m-0">출금내역</h5>
        </a>
    </div> -->
</header>
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden d-flex align-items-center justify-content-center mt-5">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-100">
                <div class="col-11">
                    <div class="card mb-0">
                        <div class="card-body px-0">
                            <h3 class="mb-4"> {{ __('auth.identity_verification') }}</h3>
                            <div class="pb-3">
                                <h5 class="mb-3" >{{ __('auth.email_verification') }}</h5>
                                <form method="POST" action="{{ route('verify.code.send') }}" id="ajaxForm">
                                    @csrf
                                    <input type="hidden" name="mode" value="account"/>
                                    <div class="mb-3">
                                        <label for="inputAccount" class="form-label">{{ __('auth.email') }}</label>
                                        <div class="input-group">
                                            <input type="email" name="email" class="form-control required" required>
                                            <button type="submit" class="btn btn-primary bord rounded-end-3">{{ __('system.send') }}</button>
                                        </div>
                                    </div>
                                </form>
                                <form method="POST" action="{{ route('verify.code.check') }}" id="verifyForm">
                                    @csrf
                                    <input type="hidden" name="mode" value="otp"/>
                                    <div class="mb-5">
                                        <label for="inputEmail" class="form-label">{{ __('auth.verify_code') }}</label>
                                        <input type="text" name="code" class="form-control required" required>
                                        <button type="submit" class="btn btn-primary w-100 py-3 fs-4 mt-5">{{ __('auth.verify') }}</button>
                                    </div>
                                </form>
                            </div>
                            <!--div class="pb-3">
                                <h5 class="mb-3">휴대폰 인증</h5>
                                <form method="POST" id="ajaxForm" action="">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="inputAccount" class="form-label">휴대폰</label>
                                        <div class="input-group">
                                            <input type="text" name="account" class="form-control required"  id="inputAccount" required>
                                            <button type="button"  id="copyBtn" class="btn btn-primary rounded-end-3">발송</button>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="inputEmail" class="form-label">인증번호</label>
                                        <input type="email" name="email" class="form-control required"  id="inputEmail" aria-describedby="emailHelp" required>
                                    </div>
                                </form>
                            </div-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('js/auth/verify.js') }}"></script>
@endpush
