@php
    $message = 'UID C' . $info->deposit->user_id . ' 회원님이 <br>' . $info->deposit->asset->coin->name . ' ' . number_format($info->deposit->amount) . ' 입금 신청하였습니다.';
    $timestamp = \Carbon\Carbon::parse($info->created_at)->diffForHumans();
@endphp


<div class="toast border-0 opacity-75 show" role="alert" aria-live="assertive" aria-atomic="true" id="toast-{{ $info->id }}">
    <div class="toast-header text-white border-0 p-3" style="background: #2f2f30;">
        <strong class="me-auto opacity-75">CubeAI</strong>
        <small>{{ $timestamp }}</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" data-id="{{ $info->id }}" aria-label="Close" style="filter: invert(1);"></button>
    </div>
    <a class="cursor-pointer btn-view" data-id="{{ $info->id }}">
        <div class="toast-body text-white border-0 p-3" style="background: #2f2f30;">
            <p class="fs-4">{!! $message !!}</p>
        </div>
    </a>
</div>

<script>
$(function() {
    $('.btn-view').on('click', function() {
        const toastId = $(this).data('id');
        const $toast = $('#toast-' + toastId);

        $.ajax({
            url: '/admin/deposit-toast/' + toastId + '/read',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response);
                alertModal(response.message, response.url);
            },
            error: function() {
                alertModal('서버 오류가 발생했습니다.');
            }
        });
    });

    $('.btn-close').on('click', function() {
        const toastId = $(this).data('id');
        const $toast = $('#toast-' + toastId);

        $.ajax({
            url: '/admin/deposit-toast/' + toastId + '/read',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response);
                alertModal(response.message);
            },
            error: function() {
                alertModal('서버 오류가 발생했습니다.');
            }
        });
    });
});
</script>