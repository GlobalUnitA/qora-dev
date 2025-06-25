$(document).ready(function() {
    $('.updateBtn').click(function () {
        confirmModal('코인을 변경하시겠습니까?').then((isConfirmed) => {
            if (isConfirmed) {
                let formData = new FormData($('#updateForm')[0]);

                const id = $(this).data('id');

                const address = $('input[name="address['+id+']"]').val();
                const active = $('input[name="is_active['+id+']"]:checked').val();

                formData.append('id', id);
                formData.append('address', address);
                formData.append('is_active', active);
                
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