@extends('layouts.master')

@section('content')
<main class="container-fluid py-5 mb-5">
    <div class="px-3 mb-5">
        <form method="POST" action="{{ route('device.apply') }}">
            @csrf
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="mt-4 mb-4">
                    <a href="{{ route('device.list') }}">
                        <h5>{{ __('messages.purchase.purchase_history') }}</h5>
                    </a>
                </div>
                <div class="text-danger fs-3">
                    @if($waiting > 0)
                    {{ __('messages.purchase.waiting') }} ({{ $waiting }})
                    @endif
                </div>
            </div>
                <div class="text-center mb-4">
                    <img src="{{ asset('images/img_aethir_edge.png') }}" alt="Aethir Edge Device" class="img-fluid" style="max-width: 200px;">
                    <h2 class="mt-3">{{ config('app.name', 'Laravel') }}</h2>
                </div>
                <div class="mb-4">
                    <label class="form-label">{{ __('messages.purchase.purchase_quantity') }}</label>
                    <input type="text" name="ea" id="ea" value="0" class="form-control mb-3" readonly data-bs-toggle="modal" data-bs-target="#quantityModal">
                </div>
                <div class="mb-4">
                    <label class="form-label">USDT</label>
                    <input type="text" id="usdt" class="form-control" value="0" disabled>
                    <input type="hidden"  name="usdt" >
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3 mb-4">{{ __('messages.purchase.purchase') }}</button>
        </form>
    </div>

    <div class="modal fade" id="quantityModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 mt-2">
                    <h5 class="modal-title">{{ __('messages.purchase.purchase_quantity') }}</h5>
                </div>
                <div class="modal-body pt-2">
                    <div class="list-group list-group-flush mt-3">
                        @foreach ($device as $data)
                        <label class="list-group-item border-0 py-3 d-flex">
                            <input type="radio" name="quantity" id="{{ $data['ea'] }}" class="form-check-input me-3" value="{{ $data['usdt'] }}">
                            <span class="d-flex justify-content-between align-items-center w-100">
                                <span>{{ $data['ea'] }} EA</span>
                                <span>{{ number_format($data['usdt']) }} U</span>
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <div class="row w-100 g-2">
                        <div class="col">
                            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">{{ __('messages.layout.cancel') }}</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-primary w-100" id="confirmQuantity">{{ __('messages.layout.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('script')
<script src="{{ asset('js/package/device.js') }}"></script>
@endpush