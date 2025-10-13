const copyNotice = $('#msg_copy').data('label');
const errorNotice = $('#msg_error').data('label');

let isSubmitting = false;

// number format
function number_format(number) {

    number = parseFloat(number);
    if (isNaN(number)) return '0';
    const fixedNumber = number.toFixed(0);

    const parts = fixedNumber.split('.');
    let integerPart = parts[0];
    let decimalPart = parts[1] || '';

    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    return decimalPart ? `${integerPart}${'.'}${decimalPart}` : integerPart;
}

// toggle
function toggleSubTable(key) {
    const subTable = $("#sub-table-" + key);
    subTable.stop(true, true).slideToggle(400);
}

// modal alert
function alertModal(message, url) {

    if (message) {
        $('#alertMessage').html(message);
        $('#alertModal').modal('show');

        $('#confirmBtn').off('click').on('click', function() {
            $('#alertModal').modal('hide');
        });
    }

    $('#alertModal').off('hidden.bs.modal').on('hidden.bs.modal', function () {
        if (url) {
            if (url === 'back') {
                history.back();
            } else if (url === 'reload') {
                location.reload();
            } else {
                window.location.href = url;
            }
        }
    });
}
// modal confirm
function confirmModal(message, url) {
    return new Promise((resolve) => {
        $('#confirmMessage').html(message);
        $('#confirmModal').modal('show');

        $('#confirmOkBtn').off('click').on('click', function () {
            $('#confirmModal').modal('hide');
            resolve(true);
        });

        $('#confirmCancelBtn').off('click').on('click', function () {
            $('#confirmModal').modal('hide');
            resolve(false);
        });
    });
}

// ajax submit
function submitAjax(form) {

    if (isSubmitting) return;
    isSubmitting = true;

    const submitBtn = $(form).find('[type="submit"]');
    submitBtn.prop('disabled', true);

    const formData = new FormData(form);
    $.ajax({
        url: $(form).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log(response);
            alertModal(response.message, response.url);
        },
        error: function(xhr, status, error) {
            console.log(error);
            if (xhr.status === 419) {
                alertModal($('#msg_session_expried').data('label'), '/');
                setTimeout(() => location.reload(), 2000);
            }
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = '';

                for (var field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        errorMessage += errors[field].join('<br>');
                    }
                }

                alertModal(errorMessage.trim());
                return;
            } else {
                alertModal($('#msg_error').data('label'));
                return;
            }
        },
        complete: function() {
            isSubmitting = false;
            submitBtn.prop('disabled', false);
        }
    });
}

function logout() {
    const form = $('#logoutForm')[0];
    const confirmMessage = $('#msg_logout').data('label');

    confirmModal(confirmMessage).then((isConfirmed) => {
        if (isConfirmed) {
            submitAjax(form);
        }
    });
}

$(document).ready(function() {

    $('#ajaxForm').on('submit', function(e) {

        e.preventDefault();

        const form = $(this)[0];
        const confirmMessage = $(this).data('confirm-message');

        if (confirmMessage) {
            confirmModal(confirmMessage).then((isConfirmed) => {
                if (isConfirmed) {
                    submitAjax(form);
                }
            });
        } else {
            submitAjax(form);
        }
    });

    $('#loadMoreForm').on('submit', function(e) {

        e.preventDefault();

        const form = $(this);
        const container = $('#loadMoreContainer');
        const template = $('#loadMoreTemplate').html();
        const formData = new FormData(this);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                console.log(res);
                $.each(res.items, function (i, item) {
                    let row = template;
                    for (const key in item) {
                        row = row.replaceAll(`{{${key}}}`, item[key] ?? '');
                    }
                    console.log(row);
                    container.append(row);
                });

                const offset = form.find('input[name="offset"]');
                const limit = parseInt(form.find('input[name="limit"]').val());
                offset.val(parseInt(offset.val()) + limit);

                if (!res.hasMore) {
                    form.find('button[type="submit"]').hide();
                }
            },
            error: function (e) {
                console.log(e);
                alertModal(errorNotice);
            }
        });

    });

    $('.copyBtn').click(function () {
        const text = $(this).data('copy');
        const $textarea = $('<textarea readonly></textarea>');
        $textarea.css({
            position: 'fixed',
            top: 0,
            left: 0,
            width: '1px',
            height: '1px',
            opacity: 0
        }).val(text);

        $('body').append($textarea);

        $textarea.focus();
        $textarea.select();

        try {
            const succes = document.execCommand('copy');
            if (succes) {
                alertModal(copyNotice);
            }
        } catch (err) {
            console.error('복사 실패:', err);

        }

        $textarea.remove();
    });

    $('.preview-box').each(function () {

        const $box = $(this);
        import('./upload.js').then(module => {
            module.upload(
                $box.find('.file-input'),
                $box.find('.svg-icon'),
                $box.find('.img-preview'),
                $box
            );
        });
    });

    $('.closePopup').on('click', function () {

        const popupId = $(this).data('popup');

        if ($(`#dismissPopup-${popupId}`).prop('checked')) {

            $.ajax({
                url: '/popup/hide',
                method: 'POST',
                data: {
                    id: popupId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        }

        $(`#popupModal-${popupId}`).modal('hide');
    });

    $('[id^="popupModal-"]').each(function () {
        $(this).modal('show');
    });
});
