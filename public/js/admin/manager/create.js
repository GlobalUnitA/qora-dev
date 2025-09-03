$(document).ready(function() {

    $('#inputPassword').focusout(function() {
        const self = this;
        const password1 = $(self).val();

        const validate = validatePassword(password1);

        if(!validate) {
            alertModal('영문/숫자/특수문자를 조합하여 8자~16자리로 입력해 주세요.');
            $(self).val('');
        }
    });
});

function validatePassword(password) {
    const regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$/;
    return regex.test(password);
}