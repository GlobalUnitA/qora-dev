$(document).ready(function() {

    let presignedData = null;

    $('#depositForm').submit(function (event) {
        event.preventDefault();

        const asset = $("input[name='asset']:checked").val();
        const amount = $("input[name='amount']").val().trim();

        if (!asset) {
            alertModal($('#msg_deposit_asset').data('label'));
            return;
        }

        if (!amount || isNaN(amount)) {
            alertModal($('#msg_deposit_amount').data('label'));
            return;
        }

        this.submit();
        
    });

    $('#confirmForm').submit(function (event) {

        event.preventDefault();

        const form = this; 
        const file = $('#fileInput')[0].files[0];

        console.log(file);
        console.log(presignedData);
        if (!file || !presignedData) return alertModal(errorNotice);

       
        $.ajax({
            url: presignedData.uploadUrl,
            type: 'PUT',
            data: file,
            processData: false,
            contentType: file.type,
            success: function() {
                submitAjax(form);
            },
            error: function() {
                alertModal(errorNotice);
            }
        });
    });

    $('#fileInput').on('change', function() {
        const file = this.files[0];
        if (!file) return;

        $.post('/file/presigned-url', {
            file_name: file.name,
            directory: 'deposit',
            _token: $('meta[name="csrf-token"]').attr('content')
            }, function(res) {
                if (res.status !== 'success') return alertModal(errorNotice);
                $("input[name='file_key']").val(res.file_key);

                presignedData = {
                    uploadUrl: res.upload_url,
                    fileKey: res.file_key
                };

            }).fail(function() {
                alertModal(errorNotice);
        });
    });

    // upload
    import('../upload.js').then(module => {
        module.upload($('#fileInput'), $('#defaultContent'), $('#imagePreview'), $('#uploadBox'));
    }).catch(err => {
        alertModal(errorNotice);
    });
    
});