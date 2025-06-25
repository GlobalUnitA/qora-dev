$(document).ready(function() {
    $('#usdtResetBtn').click(function () {
        confirmModal('USDT 주소를 초기화하시겠습니까?').then((isConfirmed) => {
            if (isConfirmed) {
                
                let formData = new FormData($('#resetForm')[0]);
                formData.append('mode', 'usdt');
                
                $.ajax({
                    url: $('#resetForm').attr('action'),
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
                        alertModal('예기치 못한 오류가 발생했습니다.');
                    }
                });     
            } else {
               return;        
            }
        });
    });

    $('#otpResetBtn').click(function () {
        confirmModal('OTP를 초기화하시겠습니까?').then((isConfirmed) => {
            if (isConfirmed) {
                
                let formData = new FormData($('#resetForm')[0]);
                formData.append('mode', 'otp');
                
                $.ajax({
                    url: $('#resetForm').attr('action'),
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
                        alertModal('예기치 못한 오류가 발생했습니다.');
                    }
                });     
            } else {
               return;        
            }
        });
    });
});