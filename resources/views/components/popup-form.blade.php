<div id="popupModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white"> {{ $popup->head }} </h5>
            </div>
            <div class="modal-body py-5 px-4">
                <p class="m-0"> {!! $popup->body !!} </p>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center border-top">
                <div class="form-check">
                    <input type="checkbox" id="dismissPopup" class="form-check-input">
                    <label for="dismissPopup" class="ps-1 lh-base_v2">{{ __('messages.layout.dismiss_today') }}</label>
                </div>
                <button type="button" class="btn btn-dark" id="closePopup" data-dismiss="modal">{{ __('system.cancel') }}</button>
            </div>
        </div>
    </div>
</div>