$(document).ready(function() {
    const passwordGuide = $('#msg_password_guide').data('label');

    $('#inputPassword1').focusout(function() {
        const self = this;
        const password1 = $(self).val();

        const validate = validatePassword(password1);

        if(!validate) {
            alertModal(passwordGuide);
            $(self).val('');
        }
    });

    $('#inputPassword2').focusout(function() {
        const self = this;
        const password1 = $('#inputPassword1').val();
        const password2 = $(self).val();

        const validate = validatePassword(password2);

        if(!validate) {
            alertModal(passwordGuide);
            $(self).val('');
        }

        if(password1 !== password2) {
            alertModal($('#msg_password_missmatch').data('label'));
            $(self).val('');
        }
    });

});

function validatePassword(password) {
    const regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d])[A-Za-z\d\S]{8,16}$/;
    return regex.test(password);
}