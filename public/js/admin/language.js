$(document).ready(function() {
    $('#addLanguageBtn').click(function () {
        const count = $('.add_language').length;
        const newInputs = `
            <div class="row align-items-center mb-2 add_language">
                <div class="col-auto">
                    <label class="form-label mb-0">코드:</label>
                </div>
                <div class="col-3">
                    <input type="text" name="content[locale][${count}][code]" value="" class="form-control form-control-sm"/>
                </div>
                <div class="col-auto">
                    <label class="form-label mb-0">언어:</label>
                </div>
                <div class="col-3">
                    <input type="text" name="content[locale][${count}][name]" value="" class="form-control form-control-sm"/>
                </div>
                <div class="col-2">
                    <button type="button" id="removeLanguageBtn" class="btn btn-danger btn-sm">- 삭제</button>
                </div>
            </div>`;
        $('#input_language').append(newInputs);
    });

    $('#input_language').on('click', '#removeLanguageBtn', function () {
        $(this).closest('.add_language').remove(); 
    });

    $('#addPageBtn').click(function () {
        
        const newInputs = `
            <div class="row align-items-center mb-2 add_page">
                <div class="col-auto">
                    <label class="form-label mb-0">카테고리:</label>
                </div>
                <div class="col-3">
                    <input type="text" name="content[category][]" value="" class="form-control form-control-sm"/>
                </div>
                <div class="col-2">
                    <button type="button" id="removePageBtn" class="btn btn-danger btn-sm">- 삭제</button>
                </div>
            </div>`;
        $('#input_page').append(newInputs);
    });

    $('#input_page').on('click', '#removePageBtn', function () {
        $(this).closest('.add_page').remove(); 
    });

    $('.addMessageBtn').click(function () {
        
        const pageName = $(this).data('page');

        const newInputs = `
           <div class="row align-items-center mb-2 add_message">
                <div class="col-auto">
                    <label class="form-label mb-0">Id:</label>
                </div>
                <div class="col-3">
                    <input type="text" name="key[]" value="" class="form-control form-control-sm"/>
                </div>
                <div class="col-auto">
                    <label class="form-label mb-0">설명:</label>
                </div>
                <div class="col-5">
                    <input type="text" name="description[]" value="" class="form-control form-control-sm"/>
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-danger btn-sm removeNewMessageBtn" data-page="${pageName}">- 삭제</button>
                </div>
            </div>`;
            
        $('#input_message').append(newInputs);
    });

    $('.removeMessageBtn').click(function () {
        event.preventDefault();
        const key = $(this).data('key');
        let formData = new FormData($('#messageForm')[0]);
        formData.append('key', key);    

        console.log(formData);
        
        $.ajax({
            url: $('#messageForm').attr('action'),
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
    });

    $('#input_message').on('click', '.removeNewMessageBtn', function () {
        console.log('123');
        $(this).closest('.add_message').remove(); 
    });

});