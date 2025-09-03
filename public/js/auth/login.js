$(document).ready(function() {
    $('#locale').change(function() {
         window.location.href = $(this).val();
    });
});