@extends('admin.layouts.master')

@section('content')
<div class="position-relative min-vh-100 overflow-hidden px-0 bg-white border border-sm-0">
    <div class="layoutContainer container position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
            <main class="container-fluid py-5 mb-5">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>{{ __('auth.otp_verification') }}</h3>
                </div>
                <hr>
                <div class="pt-4 pb-5 text-center">
                    @if($is_new)
                        @if($is_mobile)
                            <h5 class="pt-3 break-keep-all lh-base">{{ __('auth.otp_verification_guide_m1') }}</h5>
                            <div class="p-4 rounded bg-light text-body my-4">
                                <h6>{{ __('auth.otp_issuer') }}: {{ config('app.name') }}</h6>
                                <h6>{{ __('auth.login_id') }}: {{ $user->account }}</h6>
                                <h4 class="m-0"><strong>{{ __('auth.secret_key') }}</strong> <code class="fs-7 text-primary">{{ $secret_key }}</code></h4>
                            </div>
                    
                            <p class="pt-4">{{ __('auth.otp_verification_guide_m2') }}</p>
                    
                            @if(str_contains(request()->header('User-Agent'), 'iPhone') || str_contains(request()->header('User-Agent'), 'iPad'))
                                <a href="https://apps.apple.com/app/google-authenticator/id388497605" target="_blank" class="btn btn-outline-inverse mb-5">
                                    {{ __('auth.otp_download_app') }}
                                </a>
                            @elseif(str_contains(request()->header('User-Agent'), 'Android'))
                                <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank" class="btn btn-success mb-5">
                                    {{ __('auth.otp_download_apk') }}
                                </a>
                            @else
                                <p>{{ __('auth.otp_verification_guide_m3') }}</p>
                            @endif
                        @else
                            <h4 class="py-4">{{ __('auth.otp_verification_guide1') }}</h4>
                            <div class="p-4 rounded bg-light text-body mb-4 d-inline-block rounded-4">
                                <img src="{{ $qrcode_url }}" alt="OTP QR Code">
                            </div>
                            <p class="mb-2">{{ __('auth.otp_verification_guide2') }}</p>
                            <h4 class="pb-5"><strong>{{ __('auth.secret_key') }}</strong><code class="fs-7 ps-2 text-primary">{{ $secret_key }}</code></h4>
                        @endif
                    @endif
                
                    <form method="POST" action="{{ route('admin.otp.verify') }}" id="ajaxForm" class="text-start">
                        @csrf
                        <input type="hidden" name="guard" value="{{ $guard ?? 'web' }}">
                        @if($is_new)
                            <input type="hidden" name="secret_key" value="{{ $secret_key }}">
                        @endif
                        <label for="otp_code" class="form-label pe-2 break-keep-all">{{ __('auth.verify_code_6') }}</label>
                        <div class="d-flex">
                            <input id="otp_code" name="otp_code" type="text" required pattern="\d{6}" maxlength="6" autofocus class="form-control">
                            <button type="submit" class="btn btn-primary ms-2 break-keep-all">{{ __('auth.verify') }}</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('js/auth/otp.js') }}"></script>
@endpush