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
                
                    $template.find('.staking-name').text(item.staking_locale_name);
                    $template.find('.staking-amount').text(number_format(item.min_quantity)+' ~ '+number_format(item.max_quantity));
                    $template.find('.staking-rate').text(parseFloat(item.daily)+' %');
                    $template.find('.staking-period').text(item.period);
                    $template.find('.staking-btn').attr('onclick', `location.href='${url}'`);
                
                    $('#stakingDataContainer').append($template);
                });
                
                $('#stakingData').removeClass('d-none');
            },
            error: function(response) {
                console.log(response);
                alertModal(errorNotice);
                $('#stakingData').addClass('d-none');
            }
        });
    });
});