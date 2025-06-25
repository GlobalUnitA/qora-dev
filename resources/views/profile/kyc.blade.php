@extends('layouts.master')

@section('content')
<main class="container-fluid py-5">
    <div class="d-flex justify-content-between align-items-center">
        <h3>{{ __('user.kyc_verification') }}</h3>
    </div>
    <hr>
    <div class="mt-4 mb-5">
        <label for="state" class="text-body fs-4 form-label">Nationality</label>
        <select class="form-select text-body" id="nationality">
            @foreach($countries as $code => $name)
            <option>{{ $name }}</option>
            @endforeach
        </select>
    </div>
    <div class="accordion pb-5 mb-5" id="accordionForm">
        <p class="text-body fs-4">{{ __('user.select_identity') }}</p>            
        <div class="accordion-item">
            <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                {{ __('etc.passport') }}
            </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionForm">
                <div class="accordion-body">
                    <h6 class="mt-3">{{ __('etc.psssport_verify') }}</h6>
                    <div class="card-body py-4 px-2">
                        <form method="POST" action="{{ route('kyc.store') }}" class="kycForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="passport">
                            <div class="mb-4">
                                <label for="given_name" class="form-label required text-body">Given Name</label>
                                <input type="text" name="given_name" id="given_name" class="form-control required" required>
                            </div>
                            <div class="mb-4">
                                <label for="surname" class="form-label required text-body">Surname</label>
                                <input type="text" name="surname" id="surname" class="form-control required" required>
                            </div>
                            <div class="mb-4">
                                <label for="id_number" class="form-label required text-body">{{ __('etc.passport_number') }}</label>
                                <input type="text" name="id_number" id="id_number" class="form-control required" required>
                            </div>
                            <div class="mb-5">
                                <label for="date" class="form-label required text-body">Date of Birth</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    <input type="date" name="date_of_birth" id="date" class="form-control required" value="" required>
                                </div>
                            </div>                               
                            <div id="uploadBox_1" class="position-relative bg-light rounded p-5 text-center mb-3">
                                <input type="file" name="file[]" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept="image/jpeg,image/jpg,image/png" id="fileInput_1" style="cursor: pointer;">
                                <div id="defaultContent_1">
                                    <h6 class="fw-normal m-0 py-3">{{ __('etc.passport_front_upload') }}</h6>
                                </div>
                                <img id="imagePreview_1" class="d-none w-100 rounded" style="object-fit: contain; max-height: 140px;">
                            </div>                            
                            <div id="uploadBox_2" class="position-relative bg-light rounded p-5 text-center mb-5">
                                <input type="file" name="file[]" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept="image/jpeg,image/jpg,image/png" id="fileInput_2" style="cursor: pointer;">
                                <div id="defaultContent_2">
                                    <h6 class="fw-normal m-0 py-3">{{ __('etc.verification_upload') }}</h6>
                                </div>
                                <img id="imagePreview_2" class="d-none w-100 rounded" style="object-fit: contain; max-height: 140px;">
                            </div>                            
                            <div class="mb-3 break-keep-all">
                                <p class="mb-1">※ {{ __('etc.verification_photo_upload_notice') }}</p>
                                <p class="mb-1">1. {{ __('etc.verification_photo_upload_guide1') }}</p>
                                <p class="mb-1">2. {{ __('etc.verification_photo_upload_guide2', ['id' => __('etc.id.passport')]) }}</p>
                            </div>
                            <button type="submit" class="btn btn-info w-100 py-8 fs-4 my-4">{{ __('system.submit') }}</button>  
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                {{ __('etc.id_card') }}
            </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionForm">
                <div class="accordion-body">
                    <h6 class="mt-3">{{ __('etc.id_card_verify') }}</h6>
                    <div class="card-body py-4 px-2">
                        <form method="POST" action="{{ route('kyc.store') }}" class="kycForm">
                            @csrf
                            <input type="hidden" name="type" value="id_card">
                            <div class="mb-4">
                                <label for="given_name" class="form-label required text-body">Given Name</label>
                                <input type="text" name="given_name" id="given_name" class="form-control required" required>
                            </div>
                            <div class="mb-4">
                                <label for="surname" class="form-label required text-body">Surname</label>
                                <input type="text" name="surname" id="surname" class="form-control required" required>
                            </div>
                            <div class="mb-4">
                                <label for="id_number" class="form-label required text-body">{{ __('etc.id_card_number') }}</label>
                                <input type="text" name="id_number" id="id_number" class="form-control required" required>
                            </div>
                            <div class="mb-5">
                                <label for="date" class="form-label required text-body">Date of Birth</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    <input type="date" name="date_of_birth" id="date" class="form-control required" value="" required>
                                </div>
                            </div>                               
                            <div id="uploadBox_3" class="position-relative bg-light rounded p-5 text-center mb-3">
                                <input type="file" name="file[]" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept="image/jpeg,image/jpg,image/png" id="fileInput_3" style="cursor: pointer;">
                                <div id="defaultContent_3">
                                    <h6 class="fw-normal m-0 py-3">{{ __('etc.id_card_front_upload') }}</h6>
                                </div>
                                <img id="imagePreview_3" class="d-none w-100 rounded" style="object-fit: contain; max-height: 140px;">
                            </div>                            
                            <div id="uploadBox_4" class="position-relative bg-light rounded p-5 text-center mb-5">
                                <input type="file" name="file[]" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept="image/jpeg,image/jpg,image/png" id="fileInput_4" style="cursor: pointer;">
                                <div id="defaultContent_4">
                                    <h6 class="fw-normal m-0 py-3">{{ __('etc.verification_upload') }}</h6>
                                </div>
                                <img id="imagePreview_4" class="d-none w-100 rounded" style="object-fit: contain; max-height: 140px;">
                            </div>                            
                            <div class="mb-3 break-keep-all">
                                <p class="mb-1">※ {{ __('etc.verification_photo_upload_notice') }}</p>
                                <p class="mb-1">1. {{ __('etc.verification_photo_upload_guide1') }}</p>
                                <p class="mb-1">2. {{ __('etc.verification_photo_upload_guide2', ['id' => __('etc.id.id_card')]) }}</p>
                            </div>
                            <button type="submit" class="btn btn-info w-100 py-8 fs-4 my-4">{{ __('system.submit') }}</button>  
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                {{ __('etc.driver_license') }}
            </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionForm">
            <div class="accordion-body">
                    <h6 class="mt-3"> {{ __('etc.driver_license_verify') }}</h6>
                    <div class="card-body py-4 px-2">
                        <form method="POST" action="{{ route('kyc.store') }}" class="kycForm">
                            @csrf
                            <input type="hidden" name="type" value="driver_license">
                            <div class="mb-4">
                                <label for="given_name" class="form-label required text-body">Given Name</label>
                                <input type="text" name="given_name" id="given_name" class="form-control required" required>
                            </div>
                            <div class="mb-4">
                                <label for="surname" class="form-label required text-body">Surname</label>
                                <input type="text" name="surname" id="surname" class="form-control required" required>
                            </div>
                            <div class="mb-4">
                                <label for="id_number" class="form-label required text-body">{{ __('etc.driver_license_number') }}</label>
                                <input type="text" name="id_number" id="id_number" class="form-control required" required>
                            </div>                                
                            <div class="mb-5">
                                <label for="date" class="form-label required text-body">Date of Birth</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    <input type="date" name="date_of_birth" id="date" class="form-control required" value="" required>
                                </div>
                            </div>                               
                            <div id="uploadBox_5" class="position-relative bg-light rounded p-5 text-center mb-3">
                                <input type="file" name="file[]" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept="image/jpeg,image/jpg,image/png" id="fileInput_5" style="cursor: pointer;">
                                <div id="defaultContent_5">
                                    <h6 class="fw-normal m-0 py-3">{{ __('etc.driver_license_front_upload') }}</h6>
                                </div>
                                <img id="imagePreview_5" class="d-none w-100 rounded" style="object-fit: contain; max-height: 140px;">
                            </div>                            
                            <div id="uploadBox_6" class="position-relative bg-light rounded p-5 text-center mb-5">
                                <input type="file" name="file[]" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept="image/jpeg,image/jpg,image/png" id="fileInput_6" style="cursor: pointer;">
                                <div id="defaultContent_6">
                                    <h6 class="fw-normal m-0 py-3">{{ __('etc.verification_upload') }}</h6>
                                </div>
                                <img id="imagePreview_6" class="d-none w-100 rounded" style="object-fit: contain; max-height: 140px;">
                            </div>                            
                            <div class="mb-3 break-keep-all">
                                <p class="mb-1">※ {{ __('etc.verification_photo_upload_notice') }}</p>
                                <p class="mb-1">1. {{ __('etc.verification_photo_upload_guide1') }}</p>
                                <p class="mb-1">2. {{ __('etc.verification_photo_upload_guide2', ['id' => __('etc.id.driver_license')]) }}</p>
                            </div>
                            <button type="submit" class="btn btn-info w-100 py-8 fs-4 my-4">{{ __('system.submit') }}</button>  
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection


@push('script') 
<script src="{{ asset('js/profile/kyc.js') }}"></script>
@endpush