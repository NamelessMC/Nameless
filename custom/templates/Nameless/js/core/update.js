// @license magnet:?xt=urn:btih:d3d9a9a6595521f9666a5e94cc830dab83b65699&dn=expat.txt Expat/MIT
$(document).ready(function () {
    $('#closeUpdate').click(function (event) {
        event.preventDefault();
        let expiry = new Date();
        let length = 3600000;
        expiry.setTime(expiry.getTime() + length);
        $.cookie('update-alert-closed', 'true', {
            path: '/',
            expires: expiry
        });
    });
    if ($.cookie('update-alert-closed') === 'true') {
        $('#updateAlert').hide();
    }
});
// @license-end
