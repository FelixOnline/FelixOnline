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
		var comment = $(this).parents('.commentAction').attr('id');
		$('#loginForm').prepend('<input type="hidden" value="like" name="commenttype" id="commenttype"/><input type="hidden" value="'+comment+'" name="comment" id="comment"/>');
	});
	
	$('.dislikeComment').bind('click', function() {
		if ($('#loginForm #commenttype').length){
			$('#loginForm #commenttype').remove();
			$('#loginForm #comment').remove();
		}
		var comment = $(this).parents('.commentAction').attr('id');
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
	
	//Remove reply
	$(document).on("click", "#removeReply", function() {
		$(this).parent().remove();
		$('#commentLabel').show();
		return false;
	});
	
	/* AJAX Helper */

	function ajaxHelper(form, method, data, spinner, hideme, showme, successbox, failbox, callback) {
		method = method || 'POST';
		data = data || {};
		spinner = spinner || null;
		hideme = hideme || {};
		showme = showme || {};
		successbox = successbox || null;
		failbox = failbox || null;
		callback = callback || null;
		
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
				toggleBox(failbox);
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
			url: "ajax.php",
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
					$('#token').val(message.newtoken);
					return false;
				}
				
				// Set new token
				$('#token').val(message.newtoken);

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
					
					toggleBox(successbox);
				}
								
				return true;
			},
			error: function(msg){
				error(msg, failbox);
				
				if(message.reload) {
					location.reload();
				}
				$('#token').val(message.newtoken);
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

	$('#editProfileSave').click( function() {
		$('#personalLinksEdit label.error').hide();

		var data = {};
		data.desc = $('#descCont textarea').val();
		data.facebook = $('.facebook input').val();
		data.twitter = $('.twitter input').val();
		data.email = $('.useremail input').val();
		data.webname = $('.website #name').val();
		data.weburl = $('.website #url').val();
		data.action = 'profile_change';
		data.token = $('#token').val();
		data.check = 'userprofile';

		ajaxHelper('profileform', 'POST', data, '#userInfoCont .loading', ['#editProfileSave'], null, null, null, ajaxCallback);

		function ajaxCallback(data, message) {
			// Change profile info
			if(data.desc) {
				$('#descCont').text($("<div/>").html(data.desc).text());
			} else $('#descCont').text('Add some personal info....');
			if(data.facebook) {
				$('#personalLinks .facebook a').attr('href', data.facebook);
				$('#personalLinks .facebook').show();
			} else $('#personalLinks .facebook').hide();
			if(data.twitter) {
				$('#personalLinks .twitter a').attr('href', 'http://www.twitter.com/'+data.twitter).text($("<div/>").html('@'+data.twitter).text());
				$('#personalLinks .twitter').show();
			} else $('#personalLinks .twitter').hide();
			if(data.email) {
				$('#personalLinks .useremail a').attr('href', 'mailto:'+data.email+$("<div/>").html(data.email).text());
				$('#personalLinks .useremail').show();
			} else $('#personalLinks .useremail').hide();
			if(data.weburl) {
				$('#personalLinks .website a').attr('href', data.weburl);
				if(data.webname)
					$('#personalLinks .website a').text($("<div />").html(data.webname).text());
				else
					$('#personalLinks .website a').text(data.weburl);
				$('#personalLinks .website').show();
			} else $('#personalLinks .website').hide();
			$('#personalCont').show();
			$('#personalCont.edit').hide();
			$('#editProfileSave').hide();
			$('#editProfile').show();
		}
		
		return false;
	});

	/* Comment Rating */

	//Like comment
	$(document).on("click", '.commentAction #like', function() {
		rateComment(this, 'like');
		return false;
	});
	
	//Dislike comment
	$(document).on("click", '.commentAction #dislike', function() {
		rateComment(this, 'dislike');
		return false;
	});
	
	function rateComment(cobj, action) {
		var comment = $(cobj).parents('.commentAction').attr('id');
		var token = $(cobj).parents('.commentAction').find('#token').val();
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
			likelink.parent().prepend('Liked');
			likelink.parent().next().prepend('Disliked');
			likelink.parent().next().children('a').remove();
			likelink.remove();
		}
			
		function dislikeAjaxCallback(data, msg) {
			var likelink = $('#comment-'+window.com+'-dislike a');
			likelink.next('#dislikecounter').text('('+msg.count+')');
			likelink.parent().prepend('Disliked');
			likelink.parent().prev().prepend('Liked');
			likelink.parent().prev().children('a').remove();
			likelink.remove();
		}

		return false;
	}
});

