$(document).ready(function() {
    $('#summernote').summernote({
        placeholder: '내용을 입력하세요...',
        tabsize: 2,
        height: 500,
    });

    $('#popupForm').on('submit', function() {
        
        $('#contentBody').val($('#summernote').summernote('code'));

        event.preventDefault();
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