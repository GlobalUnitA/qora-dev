$(document).ready(function() {
    $('.updateBtn').click(function () {
        confirmModal('정책을 변경하시겠습니까?').then((isConfirmed) => {
            if (isConfirmed) {
                
                const recoad = $(this).closest('.user_policy');
                const formData = new FormData($('#updateForm')[0]);
                
                recoad.find('input').each(function() {
                    const name = $(this).attr('name');
                    const value = $(this).val();

                    formData.append(name, value);
                });

                $.ajax({
                    url: $('#updateForm').attr('action'),
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