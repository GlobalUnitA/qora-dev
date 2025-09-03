@extends('layouts.master')

@section('content')
<main class="container-fluid py-5">
    <div class="d-flex justify-content-between align-items-center">
        <h3>{{ __('auth.reset_password') }}</h3>    
        <div></div>
    </div>
    <form method="POST" action="{{ route('profile.password.update') }}" id="ajaxForm" class="mb-5">
        @csrf
        <input type="hidden" name="id" value="{{ $view->user_id }}">
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered mt-5 pb-5">
                <tbody>
                    <tr>
                        <th class="text-center align-middle text-nowrap">{{ __('auth.login_id') }}</th>
                        <td class="align-middle">{{ $view->account }}</td>
                    </tr>
                    <tr>
                        <th class="text-center align-middle text-nowrap">{{ __('auth.current_password') }}</th>
                        <td class="align-middle">
                            <input type="password" name="current_password" class="form-control required" required>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center align-middle text-nowrap">{{ __('auth.new_password') }}</th>
                        <td class="align-middle">
                            <input type="password" name="password"  id="inputPassword1" class="form-control required" required>
                            <div class="form-text">{{ __('auth.password_guide') }}</div>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center align-middle text-nowrap">{{ __('auth.confirm_password') }}</th>
                        <td class="align-middle">
                            <input type="password" name="password_confirmation" id="inputPassword2" class="form-control required" required>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>
        <div class="d-flex justify-content-end pb-5">
            <button type="submit" class="btn btn-danger mb-3">{{ __('auth.reset_password') }}</button>
        </div>
    </form>
</main>
<form method="POST" id="confirmForm" >
    @csrf
</form>
@endsection

@push('message')
<div id="msg_password_guide" data-label="{{ __('auth.password_guide') }}"></div>
@endpush

@push('script')
<script src="{{ asset('js/profile/password.js') }}"></script>
@endpush