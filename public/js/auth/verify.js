$(document).ready(function() {
    $('#verifyForm').submit(function (e) {
        e.preventDefault();
      
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
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = '';
    
                    for (let field in errors) {
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