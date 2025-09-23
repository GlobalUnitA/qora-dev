@extends('layouts.master')

@section('content')

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" style="transform: translateY(-71px);">
    <div class="position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-100">
                <div class="px-1 py-3 col-sm-11">
                    <div class="card mb-0">
                        <div class="px-4 py-2 text-end">
                            <a href="{{ url()->previous() }}">
                                <button type="button" class="btn-close"></button>
                            </a>
                        </div>
                        <div class="card-body py-0 px-3">
                            <div class="mb-5">
                                <h3 class="text-center">이용약관</h3>
                            </div>                   
                            <div class="consent-form">
                                <div class="mb-4">
                                    <div class="form-check d-flex align-items-center mb-2">
                                        <input class="form-check-input" type="checkbox" id="agreeAll">
                                        <label class="form-check-label section-title fs-5 fw-semibold ms-2" for="agreeAll">
                                            전체 동의하기
                                        </label>
                                    </div>
                                    <div class="consent-text fs-4 text-gray">
                                        QORA 이용약관, 개인정보 수집 및 이용, 이벤트 및 혜택정보수신 동의를 포함합니다.
                                    </div>
                                </div>
                                <div class="mb-5">
                                    <div class="form-check d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="terms" required>
                                            <label class="form-check-label section-title fw-semibold fs-4" for="terms">
                                                <span class="text-primary">[필수]</span> QORA 이용약관
                                            </label>
                                        </div>
                                        <a href="" class="btn btn-dark btn-sm">
                                            <span>전체보기</span>
                                        </a>
                                    </div>
                                    <div class="bg-body-tertiary rounded-3 mt-3 scroll-container">
                                        <p class="p-4 text-gray">QORA 이용약관, 개인정보 수집 및 이용, 이벤트 및 혜택정보수신 동의를 포함합니다. QORA 이용약관, 개인정보 수집 및 이용, 이벤트 및 혜택정보수신 동의를 포함합니다.QORA 이용약관, 개인정보 수집 및 이용, 이벤트 및 혜택정보수신 동의를 포함합니다.</p>
                                    </div>                                    
                                </div>  
                                <div class="mb-5">
                                    <div class="form-check d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="terms" required>
                                            <label class="form-check-label section-title fw-semibold fs-4" for="terms">
                                                <span class="text-primary">[필수]</span> 개인정보 수집 및 이용
                                            </label>
                                        </div>
                                        <a href="" class="btn btn-dark btn-sm">
                                            <span>전체보기</span>
                                        </a>
                                    </div>
                                    <div class="bg-body-tertiary rounded-3 mt-3 scroll-container">
                                        <p class="p-4 text-gray">QORA 이용약관, 개인정보 수집 및 이용, 이벤트 및 혜택정보수신 동의를 포함합니다. QORA 이용약관, 개인정보 수집 및 이용, 이벤트 및 혜택정보수신 동의를 포함합니다.QORA 이용약관, 개인정보 수집 및 이용, 이벤트 및 혜택정보수신 동의를 포함합니다.</p>
                                    </div>                                    
                                </div>  
                                <div class="mb-5">
                                    <div class="form-check d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="terms" required>
                                            <label class="form-check-label section-title fw-semibold fs-4" for="terms">
                                                <span class="text-primary">[필수]</span> 이벤트 및 혜택정보수신
                                            </label>
                                        </div>
                                        <a href="" class="btn btn-dark btn-sm">
                                            <span>전체보기</span>
                                        </a>
                                    </div>
                                    <div class="bg-body-tertiary rounded-3 mt-3 scroll-container">
                                        <p class="p-4 text-gray">QORA 이용약관, 개인정보 수집 및 이용, 이벤트 및 혜택정보수신 동의를 포함합니다. QORA 이용약관, 개인정보 수집 및 이용, 이벤트 및 혜택정보수신 동의를 포함합니다.QORA 이용약관, 개인정보 수집 및 이용, 이벤트 및 혜택정보수신 동의를 포함합니다.</p>
                                    </div>                                    
                                </div>  
                                <p class="fs-5 fw-semibold">
                                    안내사항: 전자서명 시 하단 서명란에 "정자"로 이름을 작성할 수 있도록 작성해주세요.
                                </p>
                                <div class="py-5 rounded bg-primary-subtle-75 text-primary text-center fs-7 mb-4">
                                    이름 정자로 작성하세요.
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mt-4">
                                    다음
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form method="POST" action="{{ route('register.accountCheck') }}"  id="accountCheckForm" >
    @csrf
    <input type="hidden" name="account" id="inputAccountCheck">
</form>
<form method="POST" action="{{ route('register.emailCheck') }}"  id="emailCheckForm" >
    @csrf
    <input type="hidden" name="email" id="inputEmailCheck">
</form>
<form method="POST" action="{{ route('register.parentCheck') }}"  id="parentCheckForm" >
    @csrf
    <input type="hidden" name="parentId" id="inputParentCheck">
</form>

@endsection
