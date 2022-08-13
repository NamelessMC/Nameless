// @license magnet:?xt=urn:btih:d3d9a9a6595521f9666a5e94cc830dab83b65699&dn=expat.txt Expat/MIT
function URLBuild(path, full = false) {
    return (full ? fullSiteURL : siteURL) + path;
}

function redirect(url) {
    window.location.href = url;
}

function copy(element) {
    const temp = $('<input>');
    $('body').append(temp);
    temp.val($(element).text()).select();
    document.execCommand('copy');
    temp.remove();
    $('body').toast({
        showIcon: 'checkmark',
        message: copied,
        class: 'success',
        progressUp: true,
        displayTime: 6000,
        showProgress: 'bottom',
        pauseOnHover: false,
        position: 'bottom left',
    });
}

$(document).ready(function () {
    $('[data-action="logout"]').click(function () {
        const url = $(this).data('link');
        $.post(url, {
            token: csrfToken
        }).done(function () {
            window.location.reload();
        });
    });
});

$(function () {
    $('.ui.sidebar').sidebar('attach events', '.toc.item');

    $('.ui.dropdown:not(.search):not(.upward)').dropdown();
    $('.ui.dropdown.upward:not(.search)').dropdown({ direction: 'upward' });

    $('[data-toggle="tooltip"]').popup({
        hoverable: true
    });

    $('[data-toggle="popup"]').popup({
        hoverable: true,
        variation: 'special flowing',
    });

    $('[data-toggle="modal"]').click(function () {
        let att = $(this).attr("data-target");
        $(att).modal('show');
    });

    $('.message .close').on('click', function () {
        $(this).closest('.message').transition('fade');
    });

    $('.tabular.menu .item').tab();
});

$(function () {
    if (loadingTime !== '') {
        $('#page_load').html(loadingTime);
    }
});

$(function () {
    const cachedUsers = {};

    $('*[data-poload]').mouseenter(function () {
        const elem = this;
        $.get($(elem).data('poload'),
            function (d) {
                (debugging ? console.log(d) : '');
                const data = JSON.parse(d);
                cachedUsers[$(elem).data('poload')] = data;
                const tmp = document.createElement('div');
                tmp.innerHTML = data.html;
                const img = tmp.getElementsByTagName('img')[0];
                const image = new Image();
                image.src = img.src;
            }
        );
    });

    $('*[data-poload]').popup({
        hoverable: true,
        html: '<i class="circle notch loading icon"></i>',
        delay: { show: 500, hide: 200 },
        onShow: function (e) { this.html(cachedUsers[$(e).data('poload')].html) }
    });

    const timezone = document.getElementById('timezone');

    if (timezone) {
        const timezoneValue = Intl.DateTimeFormat().resolvedOptions().timeZone;
        if (timezoneValue) {
            timezone.value = timezoneValue;
        }
    }

});

const announcements = document.querySelectorAll('[id^="announcement"]');
announcements.forEach((announcement) => {
    const closeButton = announcement.querySelector('.close');
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            document.cookie = announcement.id + '=true; path=/';
        });
    }
});
// @license-end
