$(document).ready(function() {

    $("input[name='asset']").change(function () {
        calculateFinalAmount();
    });

    $("input[name='amount']").on('input', function () {
        calculateFinalAmount();
    });
    
    $('#withdrawalForm').submit(function (event) {
        event.preventDefault();

        const asset = $("input[name='asset']:checked").val();
        const amount = $("input[name='amount']").val().trim();

        if (!asset) {
            alertModal($('#msg_withdrawal_asset').data('label'));
            return;
        }

        if (!amount) {
            alertModal($('#msg_withdrawal_amount').data('label'));
            return;
        }

        const formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
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
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';

                    for (var field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            errorMessage += errors[field].join('<br>');
                        }
                    }

                    alertModal(errorMessage.trim());
                } else {
                    alertModal(errorNotice);
                }
            }
        });
        
    });
});

function calculateFinalAmount()
{
    const selectedAsset = $("input[name='asset']:checked");

    if (selectedAsset.length === 0) return;
    const amount = parseFloat($("input[name='amount']").val()) || 0;

    const taxRate = selectedAsset.closest('.selectedAsset').find('.tax_rate').val();
    const feeRrate = selectedAsset.closest('.selectedAsset').find('.fee_rate').val();

    const tax = ((amount * taxRate) / 100).toFixed(2);
    const fee = ((amount * feeRrate) / 100).toFixed(2);

    //const finalAmount = (amount - tax - fee).toFixed(2);
    const finalAmount = (amount - tax).toFixed(2);

    $('#tax').html(tax);
    //$('#fee').html(fee);
    $('#finalAmount').html(finalAmount);

    $("input[name='tax']").val(tax);
    $("input[name='fee']").val(fee);

}