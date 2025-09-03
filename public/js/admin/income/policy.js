$(document).ready(function() {
    $('.addConditionBtn').click(function () {
       
        const index = $(this).data('index');
        const count = $('.add_condition_'+index).length;

        const newInputs = `
            <div class="row gx-3 align-items-center mb-2 add_condition_${index}">
                <div class="col-auto">
                    <label class="form-label mb-0">최소 레벨:</label>
                </div>
                <div class="col-2">
                    <input type="text" name="conditions[${count}][min_level]" class="form-control form-control-sm"/>
                </div>
                <div class="col-auto">
                    <label class="form-label mb-0">최대 레벨:</label>
                </div>
                <div class="col-2">
                    <input type="text" name="conditions[${count}][max_level]" class="form-control form-control-sm"/>
                </div>
                <div class="col-auto">
                    <label class="form-label mb-0">인원 수:</label>
                </div>
                <div class="col-2">
                    <input type="text" name="conditions[${count}][referral_count]" class="form-control form-control-sm"/>
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-danger btn-sm removeConditionBtn" data-index="${index}" data-count="${count}">- 삭제</button>
                </div>
            </div>`;

        $('#input_condition_'+index).append(newInputs);   
    });

    $(document).on('click', '.removeConditionBtn', function () {
        const index = $(this).data('index');
        $(this).closest('.add_condition_' + index).remove();
    });

    $('.updateBtn').click(function (e) {
        e.preventDefault();

        confirmModal('정책을 변경하시겠습니까?').then((isConfirmed) => {
            if (isConfirmed) {
                
                const recoad = $(this).closest('.income_policy');
                const formData = new FormData($('#updateForm')[0]);
                
                recoad.find('input').each(function() {
                    const name = $(this).attr('name');
                    const value = $(this).val();

                    formData.append(name, value);
                });
                console.log(formData);
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