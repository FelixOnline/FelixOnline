window.LiveBlog = (function(LiveBlog, $, SockJS, templates) {
	"use strict";

	var init = function(url, $feed) {
        var sockjs = new SockJS(url);

        sockjs.onopen = function() {
			$('#disconnected').hide();
			$('#connected').show();
        };

        sockjs.onmessage = function(e) {
            console.log('[.] message', e.data);
			var data = JSON.parse(e.data);
			var template = templates[data.type];
			var html = template.render(data);
			$(html).hide().prependTo($feed).fadeIn(400);
			if (data.type == 'tweet') {
				twttr.widgets.load();
			}
        };

        sockjs.onclose = function() {
			$('#connected').hide();
			$('#disconnected').show();
        };

	};

	LiveBlog.init = init;
	return LiveBlog;
})(window.LiveBlog || {}, window.jQuery, window.SockJS, window.templates);
