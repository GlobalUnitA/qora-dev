@extends('layouts.master')

@section('content')
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" style="transform: translateY(-71px);">
    <div class="position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-100">
                <div class="col-11">
                    <div class="card mb-0">
                        <div class="px-4 pt-4 text-end">
                            <a href="{{ url()->previous() }}">
                                <button type="button" class="btn-close"></button>
                            </a>
                        </div>
                        <div class="card-body px-0">                        
                            <div class="pb-3">
                                <h5 class="mb-5" >{{ __('auth.reset_password') }}</h5>
                                <form method="POST" action="{{ route('password.update') }}" id="ajaxForm">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="inputAccount" class="form-label">{{ __('auth.new_password') }}</label>
                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control required" required>
                                        </div>
                                        <div class="form-text">{{ __('auth.password_guide') }}</div>
                                    </div>
                                    <div>
                                        <label for="inputAccount" class="form-label">{{ __('auth.confirm_password') }}</label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" class="form-control required" required>
                                        </div>
                                        <div class="form-text">{{ __('auth.password_guide') }}</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 mt-5">{{ __('system.change') }}</button>    
                                </form>
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
