$(document).ready(function () {
    let presignedData = {};
    $('.file-input').on('change', function () {
        const file = this.files[0];
        if (!file) return;

        const index = $(this).attr('name').match(/\d+/)[0];
        const hiddenInput = $(this).closest('label').find("input[type='hidden']");

        $.post('/file/presigned-url', {
            file_name: file.name,
            directory: 'post',
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function (res) {

            if (res.status !== 'success') return alertModal(errorNotice);
            hiddenInput.val(res.file_key);
            presignedData[index] = {
                file: file,
                uploadUrl: res.upload_url,
                fileKey: res.file_key
            };

        }).fail(function () {
                alertModal(errorNotice);
        });
    });

    $('#boardForm').on('submit', function (e) {
        e.preventDefault();

        const form = this;
        const uploadPromises = [];

        Object.keys(presignedData).forEach(function (idx) {
            const data = presignedData[idx];
            uploadPromises.push(
                $.ajax({
                    url: data.uploadUrl,
                    type: 'PUT',
                    data: data.file,
                    processData: false,
                    contentType: data.file.type
                })
            );
        });

        $.when.apply($, uploadPromises).done(function () {
            submitAjax(form);
        }).fail(function () {
            alertModal(errorNotice);
        });
    });
});
