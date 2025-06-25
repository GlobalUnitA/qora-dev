@extends('layouts.master')

@section('content')
<main class="container-fluid py-5 mb-5">
    <div>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body text-center py-4">
                <p class="text-muted fs-4 d-block mb-1">{{ __('asset.total_deposit_amount') }}</p>
                <h3 class="fw-bold mb-0">{{ number_format($amount) }} {{ $asset->coin->name }}</h3>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <form method="POST" id="ajaxForm" action = "{{ route('asset.deposit.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="asset" value="{{ $asset->encrypted_id }}">
                    <input type="hidden" name="amount" value="{{ $amount }}">
                    <p class="mb-3 fs-4 text-body">{{ __('etc.image_upload') }}</p>
                    <div id="uploadBox" class="position-relative bg-light rounded p-4 text-center mb-5">
                        <input type="file" name="file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept="image/jpeg,image/jpg,image/png" id="fileInput" style="cursor: pointer;">
                        <div id="defaultContent">
                            <h6 class="fw-bold mb-3">{{ __('etc.upload') }}</h6>
                            <p class="text-gray mb-3 break-keep-all">
                            {{ __('asset.usdt_image_upload_guide') }}
                            </p>
                            <ul class="text-gray text-start mb-0 text-center">
                                <li>{{ __('etc.upload_extension_guide') }}</li>
                                <li>{{ __('etc.upload_size_guide') }}</li>
                            </ul>
                        </div>
                        <img id="imagePreview" class="d-none w-100 rounded" style="object-fit: contain; max-height: 200px;">
                    </div>
                    <div class="mb-5">
                        <label class="form-label fs-4 text-body">{{ __('asset.usdt_deposit_address') }}</label>
                        <div class="input-group">
                            <input type="text" id="textToCopy" class="form-control" value="{{ $asset->coin->address }}" readonly>
                            <button type="button" class="btn btn-dark rounded-end-3 copyBtn" data-copy="{{ $asset->coin->address }}">{{ __('system.copy') }}</button>
                        </div>
                        <h6 class="mt-4 text-danger">{{ __('asset.usdt_deposit_notice') }}</h6>
                        <div class="alert alert-danger mb-2" role="alert">
                            <h6 class="text-danger text-center fw-bold fs-4 m-0 lh-base break-keep-all">{{ __('asset.usdt_deposit_guide') }}</h6>
                        </div>
                        <p class="mb-4 break-keep-all">
                            {{ __('user.meta_id_guide_2') }}
                        </p>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fs-4 text-body">TXID</label>
                        <input type="text" name="txid" class="form-control" placeholder="">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 mb-4">{{ __('system.apply') }}</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@push('script') 
<script src="{{ asset('js/asset/deposit.js') }}"></script>
@endpush