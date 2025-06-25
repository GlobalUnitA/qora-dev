$(document).ready(function() {
    $('.deleteBtn').click(function () {
        confirmModal('등급을 삭제하시겠습니까?').then((isConfirmed) => {
            if (isConfirmed) {
                let formData = new FormData($('#deleteForm')[0]);

                const id = $(this).data('id');

                formData.append('id', id);
                
                $.ajax({
                    url: $('#deleteForm').attr('action'),
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