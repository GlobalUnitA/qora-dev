<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="" id="htmlPage">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @stack('meta')
    <link rel="icon" type="image/png" href="{{ asset('images/logos/emblem.png') }}" size="32x32">
    <script src="{{ asset('js/theme_set.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.min.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body>

    <div class="layoutContainer container min-vh-100 overflow-hidden px-0 bg-body border border-sm-0 layout-padding">
        @if(Auth::check() && !Request::is('register*'))
            @include('layouts.header')
        @endif
        
        <div class="contentContainer">
            @yield('content')
        </div>
        
        @if(Auth::check() && !Request::is('register*'))
            @include('layouts.footer')
        @endif
    </div>

    @include('components.alert-form')
    @include('components.confirm-form')

<div id="msg_error" data-label="{{ __('system.error_notice') }}"></div>
<div id="msg_session_expried" data-label="{{ __('auth.session_expired_notice') }}"></div>
<div id="msg_logout" data-label="{{ __('user.logout_confirm') }}"></div>
<div id="msg_required" data-label="{{ __('system.required_fields_notice') }}"></div>
<div id="msg_copy" data-label="{{ __('system.copy_notice') }}"></div>
@stack('message')

<script src="{{ asset('libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>
<script src="{{ asset('js/theme.js') }}"></script>
@stack('script')

</body>
</html>
