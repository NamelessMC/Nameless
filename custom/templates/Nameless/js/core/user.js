// @license magnet:?xt=urn:btih:d3d9a9a6595521f9666a5e94cc830dab83b65699&dn=expat.txt Expat/MIT
if (!('Notification' in window)) {
    window.Notification = null;
}

if (loggedIn == 1) {

    let countPms = 0;
    let countAlerts = 0;

    function updateAlerts(data) {
        if (data.value > 0) {
            $("#button-alerts").removeClass('default').addClass('red');
            let alerts_list = '';
            for (const alert of data.alerts) {
                alerts_list += '<a class="item" href="' + URLBuild('user/alerts/?view=' + alert.id) + '">' + alert.content_short + '</a>';
            }
            $('#list-alerts').html(alerts_list);
        } else {
            $("#button-alerts").removeClass('red').addClass('default');
            $('#list-alerts').html('<a class="item">' + noAlerts + '</a>');
        }

        countAlerts = data.value;
    }

    function notifyAlerts(data, canNotify) {
        if (data.value > 0) {
            const message = data.value == 1 ? newAlert1 : newAlertsX.replace("{{count}}", data.value);
            $('body').toast({
                showIcon: 'exclamation move-right',
                message: message,
                showProgress: 'top',
                displayTime: 5000,
                position: 'bottom left',
                onClick: () => redirect(URLBuild('user/alerts')),
            });

            if (canNotify) {
                const notification = new Notification(siteName, {
                    body: message,
                    icon: logoImage !== null ? logoImage : undefined,
                });
                notification.onclick = () => window.open(URLBuild('user/alerts', true));
            }
            countAlerts = data.value;
        }
    }

    function updatePMs(data) {
        if (data.value > 0) {
            $("#button-pms").removeClass('default').addClass("red");
            let pms_list = '';
            for (const pm of data.pms) {
                pms_list += '<a class="item" href="' + URLBuild('user/messaging/?action=view&amp;message=' + pm.id) + '">' + pm.title + '</a>';
            }
            $('#list-pms').html(pms_list);
        } else {
            $("#button-pms").removeClass('red').addClass('default');
            $('#list-pms').html('<a class="item">' + noMessages + '</a>');
        }

        countPms = data.value;
    }

    function notifyPMs(data, canNotify) {
        if (data.value > 0) {
            const message = data.value == 1 ? newMessage1 : newMessagesX.replace("{{count}}", data.value);
            $('body').toast({
                showIcon: 'comments move-right',
                message: message,
                showProgress: 'top',
                displayTime: 5000,
                position: 'bottom left',
                onClick: () => redirect(URLBuild('user/messaging')),
            });

            if (canNotify) {
                const notification = new Notification(siteName, {
                    body: message,
                    icon: logoImage !== null ? logoImage : undefined,
                });
                notification.onclick = () => window.open(URLBuild('user/messaging', true));
            }
            countPms = data.value;
        }
    }

    $(document).ready(function () {
        const canNotify = window.isSecureContext && Notification.permission === "granted";
        if (!canNotify) {
            Notification.requestPermission();
        }

        $.getJSON(URLBuild('queries/alerts'), function (data) {
            updateAlerts(data);
        });

        $.getJSON(URLBuild('queries/pms'), function (data) {
            updatePMs(data);
        });

        window.setInterval(function () {
            $.getJSON(URLBuild('queries/alerts'), function (data) {
                if (countAlerts < data.value) {
                    notifyAlerts(data, canNotify);
                }

                updateAlerts(data);
            });

            $.getJSON(URLBuild('queries/pms'), function (data) {
                if (countPms < data.value) {
                    notifyPMs(data, canNotify);
                }

                updatePMs(data);
            });
        }, 10000);
    });

}
// @license-end
