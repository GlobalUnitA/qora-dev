$(document).ready(function() {
    $('#exchangeBtn').click(function(e) {
        e.preventDefault();

        confirmModal('환율을 변경하시겠습니까?').then((isConfirmed) => {
            if (isConfirmed) {

                const formData = new FormData($('#ajaxForm')[0]);
                formData.append('mode', 'exchange');

                $.ajax({
                    url: $('#ajaxForm').attr('action'),
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

    $('#nodeBtn').click(function(e) {
        e.preventDefault();

        confirmModal('채굴량을 변경하시겠습니까?').then((isConfirmed) => {
            if (isConfirmed) {

                const formData = new FormData($('#ajaxForm')[0]);
                formData.append('mode', 'node');

                $.ajax({
                    url: $('#ajaxForm').attr('action'),
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
