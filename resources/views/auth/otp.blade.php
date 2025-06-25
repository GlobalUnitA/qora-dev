@extends('layouts.master')

@section('content')
<header class="px-4 py-5 w-100 border-top-title" style="background: url('../images/tit_bg_01.png') center right no-repeat, #1e1e1f;" >
    <h2 class="text-white mb-1 px-1">OTP</h2>
    <h6 class="text-white px-1">{{ __('user.otp_connect') }}</h6>
</header>
<main class="container-fluid py-5 mb-5">
    <!-- <div class="d-flex justify-content-between align-items-center">
        <h3>{{ __('auth.otp_verification') }}</h3>
    </div>
    <hr> -->
    <div class="pt-2 pb-5">
        @if($is_new)
            @if($is_mobile)
                <h5 class="pt-3 break-keep-all lh-base text-center">{{ __('auth.otp_verification_guide_m1') }}</h5>
                <div class="p-4 rounded bg-light text-body mt-5 mb-1 text-center">
                    <h6>{{ __('auth.otp_issuer') }}: {{ config('app.name') }}</h6>
                    <h6>{{ __('auth.login_id') }}: {{ $user->account }}</h6>
                    <h4 class="mt-4 mb-1">
                        <strong>{{ __('auth.secret_key') }}</strong>
                    </h4>
                    <div class="d-flex justify-content-center align-items-center">
                        <h4 class="m-0">
                            <code class="fs-7 text-primary">{{ $secret_key }}</code>
                        </h4>
                        <a href="#">
                            <span class="btn btn-outline-primary btn-sm py-1 px-3 ms-2 copyBtn" type="button" data-copy="{{ $secret_key }}">{{ __('system.copy') }}</span>
                        </a>
                    </div>
                    
                </div>
        
                <p class="pt-4 break-keep-all fs-4 text-center w-85" style="margin: 0 auto 1rem;">{{ __('auth.otp_verification_guide_m2') }}</p>
        
                @if(str_contains(request()->header('User-Agent'), 'iPhone') || str_contains(request()->header('User-Agent'), 'iPad'))
                    <a href="https://apps.apple.com/app/google-authenticator/id388497605" target="_blank" class="btn btn-inverse text-center w-75 mb-5 d-block m-0-auto">
                        {{ __('auth.otp_download_app') }}
                    </a>
                @elseif(str_contains(request()->header('User-Agent'), 'Android'))
                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank" class="btn btn-inverse text-center w-75 mb-5 d-block m-0-auto">
                        {{ __('auth.otp_download_apk') }}
                    </a>
                @else
                    <p>{{ __('auth.otp_verification_guide_m3') }}</p>
                @endif
            @else
                <div class="text-center">
                    <h4 class="py-4 m-0">{{ __('auth.otp_verification_guide1') }}</h4>
                    <div class="p-4 rounded bg-light text-body mb-4 d-inline-block rounded-4">
                        <img src="{{ $qrcode_url }}" alt="OTP QR Code">
                    </div>
                    <h4 class="pb-4 d-flex justify-content-center align-items-center">
                        <strong>{{ __('auth.otp_personal_key') }}</strong>
                        <code class="fs-7 ps-2 text-primary">{{ $secret_key }}</code>
                        <a href="#">
                            <span class="btn btn-outline-primary btn-sm py-1 px-3 ms-2 copyBtn" type="button" data-copy="{{ $secret_key }}">{{ __('system.copy') }}</span>
                        </a>                        
                    </h4>
                </div>
                <p class="mb-2">{{ __('auth.otp_verification_guide_p1') }}</br>
                    {{ __('auth.otp_verification_guide_p2') }}
                </p>
                <div class="alert alert-danger mt-2 mb-5" role="alert">
                    <h6 class="text-danger fw-bold fs-3 m-0 lh-base break-keep-all">{{ __('auth.otp_verification_notice') }}
                    </h6>
                </div>
            @endif
        @endif
    
        <form method="POST" action="{{ route('otp.verify') }}" id="ajaxForm" class="text-start">
            @csrf
            <input type="hidden" name="guard" value="{{ $guard ?? 'web' }}">
            @if($is_new)
                <input type="hidden" name="secret_key" value="{{ $secret_key }}">
            @endif
            <label for="otp_code" class="form-label pe-2 break-keep-all fw-bold fs-5">OTP {{ __('auth.verify_code') }}</label>
            <div class="d-flex">
                <input id="otp_code" name="otp_code" type="text" required pattern="\d{6}" maxlength="6" autofocus class="form-control">
                <button type="submit" class="btn btn-primary ms-2 break-keep-all">{{ __('auth.verify') }}</button>
            </div>
            <p class="pt-2 fs-3">{{ __('auth.verify_code_6') }}</p>
        </form>
    </div>
</main>
@endsection

@push('script')
<script src="{{ asset('js/auth/otp.js') }}"></script>
@endpush