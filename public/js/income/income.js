$(document).ready(function() {
    $("#incomeTypeSelect").change(function () {
        const type = $(this).val();

        const url = new URL(window.location.href);
        url.searchParams.set('type', type);

        window.location.href = url.toString();
        
    });
});