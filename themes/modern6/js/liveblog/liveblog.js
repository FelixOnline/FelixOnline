window.LiveBlog = (function(LiveBlog, $, SockJS, templates) {
	"use strict";

	function getImageDetails(imageId, blogId) {
		var data = {};
		data.action = 'liveblog_image';
		data.imageId = imageId;
		data.check = 'liveblog-' + blogId + '-token';
		data.token = $('#liveblog-' + blogId + '-token').val();

		return $.ajax({
			url: 'ajax.php',
			type: 'post',
			data: data,
			async: true,
			success: function(msg){
				try {
					var message = msg;
				} catch(err) {
					var message = {};
					message.error = err;
					message.reload = false;
				}
				if(message.error) {
					$('#liveblog-' + blogId + '-token').val(message.newtoken);

					if(message.reload) {
						location.reload();
					}

					return false;
				}
				
				// Set new token
				$('#liveblog-' + blogId + '-token').val(message.newtoken);

				return true;
			},
			error: function(msg){
				try {
					var message = JSON.parse(msg.responseText);
				} catch(err) {
					var message = {};
					message.error = err;
					message.reload = false;
				}
				if(message.error) {
					$('#liveblog-' + blogId + '-token').val(message.newtoken);

					if(message.reload) {
						location.reload();
					}
				}

				return false;
			}
		});
	}

	function renderPost(render, animate, reverse) {
		var postsinnercont = $('.inner-feed');

		if(animate) {
			if(reverse) {
				postsinnercont.append(render);
			} else {
				postsinnercont.prepend(render);
			}

			render.hide().fadeIn(1000);
		} else {
			if(reverse) {
				postsinnercont.append(render);
			} else {
				postsinnercont.prepend(render);
			}
		}

		window.loadImages();
		window.doSizeyImage();
	}

	var addPost = function(post, animate, reverse, blogId) {
		function prefixNumber(number) {
			if(number < 10) {
				number = '0'+number;
			}
			return number;
		}

		var time = new Date(post.timestamp*1000);
		post.time = prefixNumber(time.getHours())+':'+prefixNumber(time.getMinutes());
		var render;
		var postsinnercont = $('.inner-feed');
		var template = {
			normal: new Hogan.Template(T.post),
			twitter: new Hogan.Template(T.posttwitter),
			picture: new Hogan.Template(T.postpicture),
			quote: new Hogan.Template(T.postquote),
			video: new Hogan.Template(T.postvideo)
		}

		if(postsinnercont.data('oldest-post') == null || postsinnercont.data('oldest-post') > post.id) {
			postsinnercont.data('oldest-post', post.id);
		}

		var render;

		if(!post.data.type) {
			render = $(template.normal.render(post));
		} else {
			switch(post.data.type) {
				case 'feliximage':
					// TODO: Get image details from AJA
					var picData = getImageDetails(post.data.data.image, blogId);

					picData.success(function (picData) {
						post.data.data.picWidth = picData.width;
						post.data.data.picHeight = picData.height;
						post.data.data.picTall = picData.tall;
						post.data.data.picUrl = picData.url;
						post.data.data.showLink = true;

						if(post.data.data.attattributionLink == '') {
							post.data.data.showLink = false;
						}

						render = $(template.picture.render(post));
						renderPost(render, animate, reverse);
					});

					break;
				case 'video':
					// Mustache does not have If support, so we must set some booleans
					if(post.data.data.source == "youtube") {
						post.youtube = true;
						post.vimeo = false;
					} else {
						post.youtube = false;
						post.vimeo = true;
					}
					render = $(template.video.render(post));
					renderPost(render, animate, reverse);
					break;   
				case 'quote':
				case 'video':
					render = $(template[post.data.type].render(post));
					renderPost(render, animate, reverse);
					break;
				case 'tweet':
					render = $(template.twitter.render(post));
					renderPost(render, animate, reverse);
					break;
				default:
					post.data.data.text = micromarkdown.parse(post.data.data.text); // Convert markdown
					render = $(template.normal.render(post));
					renderPost(render, animate, reverse);
					break;
			}
		}

		return post;
	}

	var getPosts = function(blogId, startAt) {
		var fetchButton = $('#loadPosts');
		var data = {};
		data.action = 'liveblog_archive';
		data.blogId = blogId;
		data.check = 'liveblog-' + blogId + '-token';
		data.token = $('#liveblog-' + blogId + '-token').val();

		if(startAt) {
			data.startAt = startAt;
		}

		$.ajax({
			url: 'ajax.php',
			type: 'post',
			data: data,
			async: true,
			success: function(msg){
				try {
					var message = msg;
				} catch(err) {
					var message = {};
					message.error = err;
					message.reload = false;
				}
				if(message.error) {
					$('#liveblog-' + blogId + '-token').val(message.newtoken);

					if(message.reload) {
						location.reload();
					}

					return false;
				}
				
				// Set new token
				$('#liveblog-' + blogId + '-token').val(message.newtoken);

				message.posts.forEach(function(post) {
					addPost(post.post, true, true, blogId);
				});

				fetchButton.text("Load older posts");
				fetchButton.attr('disabled', false);
		
				return true;
			},
			error: function(msg){
				try {
					var message = JSON.parse(msg.responseText);
				} catch(err) {
					var message = {};
					message.error = err;
					message.reload = false;
				}
				if(message.error) {
					$('#liveblog-' + blogId + '-token').val(message.newtoken);

					if(message.reload) {
						location.reload();
					}
				}

				if(message.noposts) {
					fetchButton.text("There are no more posts");
				} else {
					fetchButton.text("Load older posts");
					fetchButton.attr('disabled', false);
				}

				return false;
			}
		});
	}

	var init = function(url, blogId) {
		var blogId = blogId;
		var socket = null;
		var postsinnercont = $('.inner-feed');

	 // Still to do:
	 // AJAX for images
	 // NOTe: need to make article content OPTIONAL

		var template = {
			normal: new Hogan.Template(window.T.post),
			twitter: new Hogan.Template(window.T.posttwitter),
			picture: new Hogan.Template(window.T.postpicture),
			quote: new Hogan.Template(window.T.postquote),
			video: new Hogan.Template(window.T.postvideo)
		}

		var sockettrouble = $('#connection-error');
		var loadposts = $('#loadPosts');
		var postscont = $('.feed');

		function socketRunner() {
			socket = new SockJS(url);

			socket.onopen = function() {
				sockettrouble.fadeOut(1000);
				postscont.removeClass('loading');
				loadposts.css('display', 'block');
			};

			socket.onclose = function() {
				sockettrouble.fadeIn(1000);
			};

			socket.onmessage = function(data) {
				var data = JSON.parse(data.data);
				if(data.type) {
					switch(data.type) {
						case 'post':
							// data.posts = array of all posts
							// need to determine whether post is already displayed
							if($('[data-post-id="' + data.post.id + '"]').length == 0) {
								addPost(data.post, true, false, blogId);
							}
							break;
						case 'delete':
							$('[data-post-id="' + data.delete + '"]').remove();

							if (postsinnercont.data('oldest-post') == data.delete) { // If oldest post deleted
								postsinnercont.data('oldest-post', postsinnercont.data('oldest-post') + 1); // Reflect that the oldest post is now newer
							}
							break;
						default:
							// Unimplemented data type
							break;
					}
				}
			};
		}

		function checkAlive(){
			if(!socket || socket.readyState == 3) {
				socketRunner();
			}

			if(socket && socket.readyState == 1) {
			}
		}

		getPosts(blogId);

		setInterval(checkAlive, 1000);
	}

	var fetchMorePosts = function(blogId) {
		var fetchButton = $('#loadPosts');
		fetchButton.text("Loading...");
		fetchButton.attr('disabled', true);

		getPosts(blogId, $('.inner-feed').data('oldest-post'));

		return false;
	}

	LiveBlog.init = init;
	LiveBlog.addPost = addPost;
	LiveBlog.getPosts = getPosts;
	LiveBlog.fetchMorePosts = fetchMorePosts;
	return LiveBlog;
})(window.LiveBlog || {}, window.jQuery, window.SockJS, window.T);
