// @license magnet:?xt=urn:btih:d3d9a9a6595521f9666a5e94cc830dab83b65699&dn=expat.txt Expat/MIT
if (!('Notification' in window))
	window.Notification = null;

if (loggedIn == 1) {

	var countPms = 0;
	var countAlerts = 0;

    function updateAlerts(data) {
		if (data.value > 0) {
		    $("#button-alerts").removeClass('default').addClass("red");
			var alerts_list = '';
			for (var i in data.alerts) {
				alerts_list += '<a class="item" href="' + URLBuild('user/alerts/?view=' + data.alerts[i].id) + '">' + data.alerts[i].content_short + '</a>';
			}
			$('#list-alerts').html(alerts_list);
		} else {
			$('#list-alerts').html('<a class="item">' + noAlerts + '</a>');
		}

		countAlerts = data.value;
    }

    function notifyAlerts(data) {
		if (data.value > 0) {
			if (data.value == 1) {
                toastr.options.onclick = function () {redirect(URLBuild('user/alerts'))};
                toastr.info(newAlert1);
		    } else {
                toastr.options.onclick = function () {redirect(URLBuild('user/alerts'))};
                toastr.info(newAlertsX.replace("{x}", data.value));
			}
            if (Notification.permission !== "granted") {
        		Notification.requestPermission();
        	} else {
        		if (data.value == 1) {
        		    var notification = new Notification(
        			    siteName, {body: newAlert1}
        			);
        		} else {
        		    var notification = new Notification(
        			    siteName, {body: newAlertsX.replace("{x}", data.value)}
        			);
        		}
        		notification.onclick = function () {
        			window.open(URLBuild('user/alerts', true));
        		};
        	}
            countAlerts = data.value;
    	}
    }

    function updatePMs(data) {
		if (data.value > 0) {
			$("#button-pms").removeClass('default').addClass("red");
			var pms_list = '';
			for (var i in data.pms) {
				pms_list += '<a class="item" href="' + URLBuild('user/messaging/?action=view&amp;message=' + data.pms[i].id) + '">' + data.pms[i].title + '</a>';
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
                toastr.options.onclick = function () {redirect(URLBuild('user/messaging'))};
                toastr.info(newMessage1);
		    } else {
                toastr.options.onclick = function () {redirect(URLBuild('user/messaging'))};
                toastr.info(newMessagesX.replace("{x}", data.value));
			}
            if (Notification.permission !== "granted") {
        		Notification.requestPermission();
        	} else {
        		if (data.value == 1) {
        		    var notification = new Notification(
        			    siteName, {body: newMessage1}
        			);
        		} else {
        		    var notification = new Notification(
        			    siteName, {body: newMessagesX.replace("{x}", data.value)}
        			);
        		}
        		notification.onclick = function () {
        			window.open(URLBuild('user/messaging', true));
        		};
        	}
            countPms = data.value;
    	}
    }

    $(document).ready(function () {

		if (Notification) {
			if (Notification.permission !== "granted")
				Notification.requestPermission();
		}

        $.getJSON(URLBuild('queries/alerts'), function(data) {
			updateAlerts(data);
		});

		$.getJSON(URLBuild('queries/pms'), function(data) {
			updatePMs(data);
		});

		window.setInterval(function () {

            $.getJSON(URLBuild('queries/alerts'), function(data) {
                if (countAlerts < data.value) {
                    notifyAlerts(data);
                }

				updateAlerts(data);
    		});

    		$.getJSON(URLBuild('queries/pms'), function(data) {
                if (countPms < data.value) {
                    notifyPMs(data);
                }

				updatePMs(data);
		    });

		}, 10000);

    });

} else if (cookie == 1) {
	toastr.options.onclick = () => $('.toast .toast-close-button').focus();
	toastr.options.onHidden = () => $.cookie('accept', 'accepted', {path: '/'});
	toastr.options.timeOut = 0;
	toastr.info(cookieNotice);
}
// @license-end
