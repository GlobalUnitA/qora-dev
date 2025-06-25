$(document).ready(function() {

    $("input[name='wallet']").change(function () {

        const seletedWallet = $(this);
        const withdrawal_fee_rate = parseFloat(seletedWallet.closest('.selectedWallet').find('.withdrawal_fee_rate').val()).toFixed(0);
        
        $('#feeString').html(`출금 수수료 ${withdrawal_fee_rate}%이며, 출금 시 수수료 차감 후 재테크 월렛에 반영 됩니다.`);
        
    });
   
    $('#withdrawalForm').submit(function (event) {

        event.preventDefault();

        const wallet = $("input[name='wallet']:checked").val();
        const amount = $("input[name='amount']").val().trim();

        if (!wallet) {
            alertModal('출금할 가상자산을 선택하세요.');
            return;
        }

        if (!amount) {
            alertModal('출금 수량을 입력하세요.');
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
            error: function( xhr, status, error) {
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
                    alertModal('예기치 못한 오류가 발생했습니다.');
                }
            }
        });
    });
});