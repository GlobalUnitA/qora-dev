<div id="popupModal-{{ $popup->id }}" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white"> {{ $popup->subject }} </h5>
            </div>
            <div class="modal-body py-5 px-4">
                <p class="m-0"> {!! $popup->content !!} </p>
            </div>
            <div class="modal-footer d-flex justify-content-end align-items-center border-top">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input dismissPopup" data-cookie="{{ $cookie_name }}" data-popup="{{ $popup->id }}">
                    <label for="dismissPopup" class="ps-1 lh-base_v2">{{ __('system.dismiss_today') }}</label>
                </div>
            </div>
        </div>
    </div>
</div>