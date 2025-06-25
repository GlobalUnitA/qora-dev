$(document).ready(function() {
   
    $('#deleteBtn').on('click', function (event) {
        event.preventDefault();
        
        const form = $('#deleteForm')[0];

        confirmModal("관리자를 삭제하시겠습니까?").then((isConfirmed) => {
            if (isConfirmed) {
                submitAjax(form);
            }
        });
    }); 
});