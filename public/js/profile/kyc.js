$(document).ready(function() {
   
    $('.kycForm').submit(function (event) {
        event.preventDefault();

        const nationality = $('#nationality').val();
        
        const formData = new FormData(this);
        formData.append('nationality', nationality); 

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

    // upload
    for(let i=1; i<7; i++) {
        import('../upload.js').then(module => {
            module.upload($('#fileInput_'+i), $('#defaultContent_'+i), $('#imagePreview_'+i), $('#uploadBox_'+i));
        }).catch(err => {
            alertModal(errorNotice);
        });
    }
});