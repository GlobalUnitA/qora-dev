$(document).ready(function () {
    $('#tradingForm').submit(function (e) {
        const maxCount = parseInt($('#maxCount').val());
        const currentCount = parseInt($('#currentCount').val());
        const balance = parseInt($('#balance').val());

        if (!$('#is_valid').length || $('#is_valid').val() == '0') {
            alertModal($('#msg_trading_disabled_user').data('label'));
            e.preventDefault();
            return;
        }

        if (!$('#available_day').length || $('#available_day').val() == '0') {
            alertModal($('#msg_trading_disabled_day').data('label'));
            e.preventDefault();
            return;
        }

        if(balance <= 0) {
            alertModal($('#msg_check_asset').data('label'));
            e.preventDefault();
            return;
        }

        if (currentCount >= maxCount) {
            alertModal($('#msg_trading_limit').data('label'));
            e.preventDefault();
            return;
        }
    });
});