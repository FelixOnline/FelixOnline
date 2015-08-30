/*
 * Author: J.Kim, P.Kent
 */

$(document).ready(function() {
	/* Facebook */

	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=200482590030408";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

	/* Comment Binders */

	$('.likeComment').bind('click', function() {
		if ($('#loginForm #commenttype').length){
			$('#loginForm #commenttype').remove();
			$('#loginForm #comment').remove();
		}
		var comment = $(this).parents('.comment-meta').attr('id');
		$('#loginForm').prepend('<input type="hidden" value="like" name="commenttype" id="commenttype"/><input type="hidden" value="'+comment+'" name="comment" id="comment"/>');
	});
	
	$('.dislikeComment').bind('click', function() {
		if ($('#loginForm #commenttype').length){
			$('#loginForm #commenttype').remove();
			$('#loginForm #comment').remove();
		}
		var comment = $(this).parents('.comment-meta').attr('id');
		$('#loginForm').prepend('<input type="hidden" value="dislike" name="commenttype" id="commenttype"/><input type="hidden" value="'+comment+'" name="comment" id="comment"/>');
	});
	
	//Reply to comment
	$(document).on("click", '.replyToComment', function() {
		var comment = $(this).attr('id');
		var commentURL = $(this).attr('href');
		var name = $(this).parents('.comment-meta').find('.comment-author').text().trim();
		if ($('#commentReply').length) {
			$('#commentReply').children('#replyLink').attr('href', commentURL).text('@'+name);
			$('#commentReply').children('#replyURL').attr('value', commentURL);
			$('#commentReply').children('#replyName').attr('value', name);
		} else {
			var reply = '<div id="commentReply"><a href="'+commentURL+'" id="replyLink">@'+name+'</a> <a href="#" id="removeReply"><img src="img/x_11x11.png" title="Remove reply"/></a><input type="hidden" id="replyURL" name="replyURL" value="'+commentURL+'"/><input type="hidden" id="replyName" name="replyName" value="'+name+'"/><input type="hidden" id="replyComment" name="replyComment" value="'+comment+'"/></div>';
			$('#comment').before(reply);
		}
		$('#commentLabel').hide();
		$('#comment').focus();
		return false;  
	});

	//Paginator page
	$(document).on("click", 'ul.pagination li a', function() {
		var item = $(this);

		return handlePaginator(item, function(data, json) {
			$('.pagination-content').html(json.content);

			$('html, body').animate({
				scrollTop: $("body").offset().top
			}, 500);
		});
	});

	//Paginator page
	$(document).scroll(function() {
		if($('ul.pagination li a.next').visible(false, true)) {
			var item = $('ul.pagination li a.next');

			return handlePaginator(item, function(data, json) {
				$('.pagination-centered').remove();

				$('.pagination-content').append(json.content);
			});
		}

	});

	//Post comment
	$(document).on("click", '.comment-form input[type="submit"]', function() {
		var data = {
			"action": "post_comment",
			"token": $('#new-token').val(),
			"check": "new_comment",
			"article": $('.comment-form input[name="article"]').val(),
			"name": $('.comment-form input[name="name"]').val(),
			"email": $('.comment-form input[name="email"]').val(),
			"comment": $('.comment-form textarea[name="comment"]').val(),
			"reply_to": $('.comment-form input[name="replyComment"]').val()
		};

		ajaxHelper(null, 'POST', data, '.comment-form-spin', ['.comment-form'], null, null, '.ajax-comment-error', function(data, response) {
			if(!response.content) {
				$('.ajax-comment-error').text(response.details);
				$('.ajax-comment-error').show();
			} else {
				$('.article-comment').last().before(response.content);
			}

			if(response.clearform) {
				$('.comment-form').trigger("reset");
				$('#commentReply').remove();
			}
		}, '#new-token');

		return false;
	});

	//Poll vote
	$(document).on("click", '.poll-option', function() {
		var pollid = $(this).attr('data-poll');

		var data = {
			"action": "poll_vote",
			"token": $('#poll-token').val(),
			"check": "poll_vote",
			"article": $(this).attr('data-article'),
			"poll": $(this).attr('data-poll'),
			"option": $(this).attr('data-option')
		};

		ajaxHelper(null, 'POST', data, '.poll-spin-'+pollid, ['.poll-form-'+pollid], null, null, null, function(data, response) {
			$('.poll-area-'+pollid).each(function() {
				$(this).html(response.content);	
			});
		}, '#poll-token');

		return false;
	});

	//Ajax login
	$(document).on("click", '.login-button', function() {
		var pollid = $(this).attr('data-poll');

		var data = {
			"action": "login_authenticate",
			"token": $('#login-token').val(),
			"check": "login",
			"username": $('#loginForm input[name="username"]').val(),
			"password": $('#loginForm input[name="password"]').val(),
			"commenttype": $('#loginForm input[name="commenttype"]').val(),
			"comment": $('#loginForm input[name="comment"]').val()
		};

		ajaxHelper(null, 'POST', data, '.login-spin', ['#loginForm'], null, null, '.ajax-login-error', function(data, response) {
			// We can assume a succesful login. We now need to load in the session

			if(response.hash) {
				var direction = $('#loginForm input[name="goto"]').val() + '#' + response.hash;
			} else {
				var direction = $('#loginForm input[name="goto"]').val();
			}

			var data = {
				"action": "login_session",
				"token": $('#login-token').val(),
				"check": "login",
				"session": response.session
			};

			ajaxHelper(null, 'POST', data, '.login-spin', ['#loginForm'], null, '.ajax-login-error', '.ajax-login-error', function(data, response) {
				$('#loginForm .row .columns .row').each(function() { $(this).remove(); });

				window.location.replace(direction);
			}, '#login-token');

		}, '#login-token', $('#loginForm input[name="endpoint"]').val());

		return false;
	});


	// Callback runs at end of pagination ajax
	function handlePaginator(item, callback) {
		if(!item.attr('data-page') || !item.attr('data-type') || !item.attr('data-key')) {
			return true;
		}

		switch(item.attr('data-type')) {
			case 'category':
				var action = "get_category_page";
				break;
			case 'user':
				var action = "get_user_page";
				break;
			case 'search':
				var action = "get_search_page";
				break;
			default:
				return true; // not supported yet
				break;
		}

		// Run ajax
		data = {
			"action": action,
			"token": $('#token').val(),
			"check": "pagination",
			"key": item.attr('data-key'),
			"page": item.attr('data-page')
		};

		ajaxHelper(null, 'POST', data, '.pagination-spin', ['.pagination'], null, null, null, callback);

		return false;
	}
	
	//Remove reply
	$(document).on("click", "#removeReply", function() {
		$(this).parent().remove();
		$('#commentLabel').show();
		return false;
	});
	
	/* AJAX Helper */

	function ajaxHelper(form, method, data, spinner, hideme, showme, successbox, failbox, callback, token_name, endpoint) {
		method = method || 'POST';
		data = data || {};
		spinner = spinner || null;
		hideme = hideme || {};
		showme = showme || {};
		successbox = successbox || null;
		failbox = failbox || null;
		callback = callback || null;
		token_name = token_name || "#token";
		endpoint = endpoint || "ajax.php";
		
		// hidemes are hidden during the AJAX call and are shown again at the end
		// showme are shown at the beginning of the call and are hidden at the end
		// Ignore spinners here
		function hideStart(hideme, showme, spinner) {
			jQuery.each(hideme, function(index, obj) {
				$(obj).hide();
			});
			
			jQuery.each(showme, function(index, obj) {
				$(obj).show();
			});
			
			if(successbox) {
				$(successbox).show();
			}

			if(failbox) {
				$(failbox).hide();
			}

			if(spinner != null) {
				$(spinner).show();
			}
		}
	
		function hideEnd(hideme, showme, spinner) {
			jQuery.each(hideme, function(index, obj) {
				$(obj).show();
			});
			
			jQuery.each(showme, function(index, obj) {
				$(obj).hide();
			});
			
			if(spinner != null) {
				$(spinner).hide();
			}
		}
		
		function error(error, failbox) {
			if(failbox != null) {
				$(failbox).text(error);
				$(failbox).show();
			} else {
				alert(error);
			}
		}
		
		function handleValidation(fields, form) {
			jQuery.each(fields, function(index, obj) {
				if($("#"+form+" input[name="+obj+"]").length != 0) {
					$("#"+form+" input[name="+obj+"]").addClass('invalidField');
				} else {
					$("#"+form+" #"+obj).addClass('invalidField');
				}
			});
		}
				
		hideStart(hideme, showme, spinner);
		
		$.ajax({
			url: endpoint,
			type: method,
			data: data,
			async:true,
			success: function(msg){
				try {
					var message = JSON.parse(msg);
				} catch(err) {
					var message = {};
					message.error = err;
					message.reload = false;
				}
				if(message.error) {
					if(message.validator) {
						handleValidation(message.validator_data, form);
					}
					
					error(message.details, failbox);

					if(message.reload) {
						location.reload();
					}
					hideEnd(hideme, showme, spinner);
					$(token_name).val(message.newtoken);
					return false;
				}
				
				// Set new token
				$(token_name).val(message.newtoken);

				hideEnd(hideme, showme, spinner);
				
				// Run callback if one exists
				if(callback) {
					callback(data, message);
				}

				if(successbox != null) {
					if(data.success != '') {
						$(successbox).text(message.success);
					} else {
						$(successbox).text('Success');
					}
					
					$(successbox).show();
				}
								
				return true;
			},
			error: function(msg){
				error(msg, failbox);
				
				if(message.reload) {
					location.reload();
				}
				$(token_name).val(message.newtoken);
				hideEnd(hideme, showme, spinner);
				return false;
			}
		});
	}


	$('input').change(function() {
		$(this).removeClass('invalidField');
	});
	$('textarea').change(function() {
		$(this).removeClass('invalidField');
	});

	/* User Profile */

	$(document).on("click", '#editProfileSubmit', function() {
		var data = {};
		data.facebook = $('.profile-facebook').val();
		data.twitter = $('.profile-twitter').val();
		if($('.profile-email').is(':checked')) { data.email = 1 } else { data.email = 0 };
		data.webname = $('.profile-webname').val();
		data.weburl = $('.profile-weburl').val();
		data.bio = $('.profile-bio').val();
		if($('.profile-ldap').is(':checked')) { data.ldap = 1 } else { data.ldap = 0 };
		data.action = 'profile_change';
		data.token = $('#token').val();
		data.check = 'edit_profile';

		ajaxHelper('profileform', 'POST', data, '#profile-spinner', ['#profile-saver'], ['#profile-saver'], null, null, profileAjaxCallback);

		function profileAjaxCallback(data, message) {
			location.reload();
		}

		return false;
	});

	/* Comment Rating */

	//Like comment
	$(document).on("click", '.comment-meta #like', function() {
		rateComment(this, 'like');
		return false;
	});
	
	//Dislike comment
	$(document).on("click", '.comment-meta #dislike', function() {
		rateComment(this, 'dislike');
		return false;
	});
	
	function rateComment(cobj, action) {
		var comment = $(cobj).parents('.comment-meta').attr('id');
		var token = $('#token-rate-'+comment).val();
		var check = comment+'ratecomment';
		
		data = {};
		data.action = action+'_comment';
		data.type = action;
		data.comment = comment;
		data.token = token;
		data.check = check;
		
		window.com = comment;
		
		if(action == 'like') {
			call = likeAjaxCallback;
		} else {
			call = dislikeAjaxCallback;
		}
		
		ajaxHelper(null, 'POST', data, '#likespinner_'+comment+' .loading', ['#comment-'+comment+'-like', '#comment-'+comment+'-dislike'], ['#likespinner_'+comment], null, null, call);

		function likeAjaxCallback(data, msg) {
			var likelink = $('#comment-'+window.com+'-like a');
			likelink.next('#likecounter').text('('+msg.count+')');
			likelink.parent().prepend('<b>YOU LIKED THIS</b>');
			likelink.parent().next().prepend('<b>DISLIKES</b>');
			likelink.parent().next().children('a').remove();
			likelink.remove();
		}
			
		function dislikeAjaxCallback(data, msg) {
			var likelink = $('#comment-'+window.com+'-dislike a');
			likelink.next('#dislikecounter').text('('+msg.count+')');
			likelink.parent().prepend('<b>YOU DISLIKED THIS</b>');
			likelink.parent().prev().prepend('<b>LIKES</b>');
			likelink.parent().prev().children('a').remove();
			likelink.remove();
		}

		return false;
	}

	/* Contact Form */
	$("#contactform").submit(function() {
		$("#contactform label.error").hide();
		var name = $('#contactform #name').val();
		var email = $('#contactform #email').val();
		var message = $('#contactform #message').val();
		var token = $('#contactform').find('#token').val();
		var check = 'generic_page';

		data = {};
		data.action = 'contact_us';
		data.name = name;
		data.email = email;
		data.message = message;
		data.token = token;
		data.check = check;
		
		ajaxHelper(
			'contactform',
			'POST',
			data,
			'#contactform #sending',
			['#contactform #submit'],
			null,
			'#sent',
			null,
			function(data, message) {
				$('#contactform').hide();
			}
		);

		return false;
	});

	/* Abuse modal */
	$(document).on("click", '.reportAbusive', function() {
		$('#abuseModalBlurbResult').hide();
		$('#abuseModalBlurb').show();
		$('#abuseModalButtons').show();
		$('#abuseModalButtonsResult').hide();
		$('#bad-comment-id').html('');
		var comment = $(this).attr('id');
		$('#bad-comment-id').html(comment);

		$('#abuseModal').foundation('reveal', 'open');

		return false;
	});

	$(document).on("click", '.closeAbusive', function() {
		$('#abuseModal').foundation('reveal', 'close');

		$('#bad-comment-id').html('');

		return false;
	});

	$(document).on("click", '.confirmAbusive', function() {
		abuseComment(this);
		return false;
	});

	function abuseComment(cobj) {
		var comment = $('#bad-comment-id').html();
		var token = $('#token-rate-'+comment).val();
		var check = comment+'ratecomment';

		data = {};
		data.comment = comment;
		data.token = token;
		data.check = check;
		data.action = 'report_abuse';
		
		call = reportAjaxCallback;

		ajaxHelper(null, 'POST', data, '#abuseModalBlurbWait', ['#abuseModalBlurb', '#abuseModalButtons'], null, null, null, call);

		function reportAjaxCallback(data, msg) {
			$('#abuseModalBlurbResult').html(msg.msg);
			$('#abuseModalBlurbResult').show();
			$('#abuseModalBlurb').hide();
			$('#abuseModalButtons').hide();
			$('#abuseModalButtonsResult').show();
			$('#'+comment).find('#token').val(msg.newtoken);
		}

		return false;
	}
});

