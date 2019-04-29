$(document).ready(function() {
    $('#closeUpdate').click(function(event) {
        event.preventDefault();
        let expiry = new Date();
        let length = 3600000;
        expiry.setTime(expiry.getTime() + length);
        $.cookie('update-alert-closed', 'true', {
            path: '/',
            expires: expiry
        });
    });
    if($.cookie('update-alert-closed') === 'true') {
        $('#updateAlert').hide();
    }
});