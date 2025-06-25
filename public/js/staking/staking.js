$(document).ready(function() {
  
    $("input[name='coin_check']").change(function() {

        const coinId = $(this).val();

        $("input[name='coin']").val(coinId);

        const stakingDataForm = $('#stakingDataForm')[0];
        const stakingDataFormData = new FormData(stakingDataForm);

        $.ajax({
            url: $(stakingDataForm).attr('action'),
            type: 'POST',
            data: stakingDataFormData,
            processData: false,
            contentType: false,
            success: function(stakingData) {

                $('#stakingDataContainer').html('');

                $.each(stakingData, function(index, item) {
                    const $template = $($('#stakingDataTemplate').html());
                    const url = `/staking/confirm/${item.id}`;
                
                    $template.find('.staking-name').text(item.staking_name);
                    $template.find('.staking-amount').text(number_format(item.min_quantity)+' ~ '+number_format(item.max_quantity));
                    $template.find('.staking-rate').text(parseFloat(item.daily)+' %');
                    $template.find('.staking-period').text(item.period+' 일');
                    $template.find('.staking-btn').attr('onclick', `location.href='${url}'`);
                
                    $('#stakingDataContainer').append($template);
                });
                
                $('#stakingData').removeClass('d-none');
            },
            error: function(response) {
                console.log(response);
                alertModal('예기치 못한 오류가 발생했습니다.');
                $('#stakingData').addClass('d-none');
            }
        });
    });


    $('#stakingForm').submit(function (event) {
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