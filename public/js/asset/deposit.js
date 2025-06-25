$(document).ready(function() {
   
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

    // upload
    import('../upload.js').then(module => {
        module.upload($('#fileInput'), $('#defaultContent'), $('#imagePreview'), $('#uploadBox'));
    }).catch(err => {
        alertModal(errorNotice);
    });
    
});