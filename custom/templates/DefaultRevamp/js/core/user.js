// @license magnet:?xt=urn:btih:d3d9a9a6595521f9666a5e94cc830dab83b65699&dn=expat.txt Expat/MIT
if (!('Notification' in window))
    window.Notification = null;

if (loggedIn == 1) {

    let countPms = 0;
    let countAlerts = 0;

    function updateAlerts(data) {
        if (data.value > 0) {
            $("#button-alerts").removeClass('default').addClass("red");
            let alerts_list = '';
            for (const alert of data.alerts) {
                alerts_list += '<a class="item" href="' + URLBuild('user/alerts/?view=' + alert.id) + '">' + alert.content_short + '</a>';
            }
            $('#list-alerts').html(alerts_list);
        } else {
            $('#list-alerts').html('<a class="item">' + noAlerts + '</a>');
        }

        countAlerts = data.value;
    }

    function notifyAlerts(data) {
        if (data.value > 0) {
            toastr.options.onclick = function () { redirect(URLBuild('user/alerts')) };
            toastr.info(data.value == 1 ? newAlert1 : newAlertsX.replace("{{count}}", data.value));

            if (window.isSecureContext) {
                if (Notification.permission !== "granted") {
                    Notification.requestPermission();
                } else {
                    const notification = new Notification(siteName, {
                        body: data.value == 1 ? newAlert1 : newAlertsX.replace("{{count}}", data.value),
                    });
                    notification.onclick = function () {
                        window.open(URLBuild('user/alerts', true));
                    };
                }
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
            $('#list-pms').html('<a class="item">' + noMessages + '</a>');
        }

        countPms = data.value;
    }

    function notifyPMs(data) {
        if (data.value > 0) {
            if (data.value == 1) {
                toastr.options.onclick = function () { redirect(URLBuild('user/messaging')) };
                toastr.info(newMessage1);
            } else {
                toastr.options.onclick = function () { redirect(URLBuild('user/messaging')) };
                toastr.info(newMessagesX.replace("{{count}}", data.value));
            }
            if (window.isSecureContext) {
                if (Notification.permission !== "granted") {
                    Notification.requestPermission();
                } else {
                    const notification = new Notification(siteName, {
                        body: data.value == 1 ? newMessage1 : newMessagesX.replace("{{count}}", data.value),
                    });
                    notification.onclick = function () {
                        window.open(URLBuild('user/messaging', true));
                    };
                }
            }
            countPms = data.value;
        }
    }

    $(document).ready(function () {
        if (window.isSecureContext && Notification.permission !== "granted") {
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
                    notifyAlerts(data);
                }

                updateAlerts(data);
            });

            $.getJSON(URLBuild('queries/pms'), function (data) {
                if (countPms < data.value) {
                    notifyPMs(data);
                }

                updatePMs(data);
            });
        }, 10000);
    });

}
// @license-end
