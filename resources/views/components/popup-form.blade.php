<div id="popupModal-{{ $popup->id }}" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white"> {{ $popup->subject }} </h5>
            </div>
            <div class="modal-body py-5 px-4">
                <p class="m-0"> {!! $popup->content !!} </p>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center border-top">
                <div class="form-check">
                    <input type="checkbox" id="dismissPopup-{{ $popup->id }}" class="form-check-input" data-cookie="{{ $cookie_name }}">
                    <label for="dismissPopup" class="ps-1 lh-base_v2">{{ __('system.dismiss_today') }}</label>
                </div>
                <button type="button" class="btn btn-dark closePopup" data-dismiss="modal" data-popup="{{ $popup->id }}">{{ __('system.close') }}</button>
            </div>
        </div>
    </div>
</div>