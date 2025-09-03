$(document).ready(function() {

    $('.editBtn').click(function() {
        const commentId = $(this).data('comment');
        const container = $('#comment_' + commentId);

        container.find('.comment-content').addClass('d-none');
        container.find('.comment-date').addClass('d-none');
        container.find('.comment-edit').removeClass('d-none');
        container.find('.editBtn').addClass('d-none');
        container.find('.saveBtn').removeClass('d-none');
    });

    $('.saveBtn').click(function (e) {
        e.preventDefault();

        confirmModal('답글을 수정하시겠습니까?').then((isConfirmed) => {
            if (isConfirmed) {

                const commentId = $(this).data('comment');
                const container = $('#comment_' + commentId);
                const newContent = container.find('.comment-edit').val();
        
                const formData = new FormData($('#commentForm')[0]);
            
                formData.append('content', newContent);
                formData.append('comment_id', commentId);
            
                $.ajax({
                    url: $('#commentForm').attr('action'),
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