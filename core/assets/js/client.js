var MCAssoc = (function() {
	var MCAssoc = {
		baseurl: 'https://mcassoc.lukegb.com/'
	};

	var iframe;

	MCAssoc.init = function(siteid, key, postback, mcusername) {
		iframe = document.getElementById('mcassoc');

		// mcusername is optional
		mcusername = mcusername || null;

		// formulate the URL:
		this.url = this.baseurl + 'perform?';

		var pieces = {
			siteid: siteid,
			key: key,
			postback: postback,
			mcusername: mcusername
		};
		var qs = '';
		for (var piecen in pieces) {
			if (!pieces.hasOwnProperty(piecen)) continue;
			var piecev = pieces[piecen];
			if (piecev) {
				if (qs != '') qs += '&';
				qs += encodeURIComponent(piecen) + '=' + encodeURIComponent(piecev);
			}
		}

		this.url += qs;

		iframe.src = this.url;
	};

	return MCAssoc;
})();