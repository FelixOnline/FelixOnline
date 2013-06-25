/*
 * Author: J.Kim
 */

$(document).ready(function() {
	
	// Reload css to fix @font face issue in IE8
	//$('#main_css')[0].href=$('#main_css')[0].href;
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=200482590030408";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	
	/* Phoenix box */
	$('#phoenixcont .acts').hover(function(){
		if (!$(this).find(".cover").hasClass('animated')) {
			$(this).find(".cover").dequeue().stop().animate({ left: '300px' });
		}
	}, function() {
		$(this).find(".cover").addClass('animated').animate({ left: '0' }, "normal", "linear", function() {
			$(this).removeClass('animated').dequeue();
		});
	});
	
	// Load sharing links
	if ($('.sidebar2 #sharebuttons').length) { // If sidebar 2 exists
		var facebook = '<fb:like send="false" layout="button_count" width="140" show_faces="false" font="arial"></fb:like>';
		var twitter = '<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="feliximperial">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
		var google = '<g:plusone size="medium" annotation="inline" width="140"></g:plusone>';
		var reddit = '<iframe src="http://www.reddit.com/static/button/button3.html?width=69&url='+encodeURIComponent(window.location.href)+'" height="52" width="69" scrolling="no" frameborder="0"></iframe>';
		
		$('#facebookLike').append(facebook);
		$('#twitterShare').append(twitter);
		$('#googleShare').append(google);
		$('#redditShare').append(reddit);
	};
	
	if ($('.articleShare').length) { //If the sharing thing at the bottom exists
		var facebook2 = '<fb:like send="true" width="300" show_faces="false" font="arial"></fb:like>';
		var twitter2 = '<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="feliximperial">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
		var google2 = '<g:plusone size="medium"></g:plusone>';

		$('#facebookLike2').append(facebook2);
		$('#twitterShare2').append(twitter2);
		$('#googleShare2').append(google2);
	};

	// Media box tabs 
	var mediaTabContainers = $('div#mediaBox > div');
	$('div#mediaBox ul.mediaBoxNav a').click(function () {
		mediaTabContainers.hide().filter(this.hash).show();
		$('div#mediaBox ul.mediaBoxNav a').parent().removeClass('selected');
		$(this).parent().addClass('selected');
		return false;
	});
	//.filter(':first').click()
	
	
	// Most popular tabs 
	var tabContainers = $('div#mostPopular > div'); 
	$('div#mostPopular ul.popularNav a').click(function () {
	tabContainers.hide().filter(this.hash).show();
		
	$('div#mostPopular ul.popularNav a').parent().removeClass('selected');
		$(this).parent().addClass('selected');
		return false;
	}).filter(':first').click();
	
	//Captify plugin
	$('img.captify').captify({
		speedOver: 100,
		// speed of the mouseout effect
		speedOut: 100,
		// how long to delay the hiding of the caption after mouseout (ms)
		hideDelay: 50,  
		// 'fade', 'slide', 'always-on'
		animation: 'slide',	 
		// text/html to be placed at the beginning of every caption
		prefix: '',	 
		// opacity of the caption on mouse over
		opacity: '0.7',				 
		// the name of the CSS class to apply to the caption box
		className: 'caption-bottom',	
		// position of the caption (top or bottom)
		position: 'bottom',
		// caption span % of the image
		spanWidth: '100%'
	});

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
	
	$('a[rel*=facebox]').facebox();
	
	$(document).bind('reveal.facebox', function() { 
		$('#loginForm #user').focus();
	})
	
	//Twitter style countdown on comment form
	$("#commentForm #comment").charCount();
	
	//Comment form validation
	$("#commentForm form").submit(function() {
		var commentText = $("#commentForm #comment").val();
		if(!commentText) {
			$("#commentForm label.error").show();
			return false;
		} else {
			$("#commentForm label.error").hide();
			//if(!validateCaptcha()) {
				//return false;
			//} 
		}
	});
	
	function validateCaptcha() {
		challengeField = $("input#recaptcha_challenge_field").val();
		responseField = $("input#recaptcha_response_field").val();
		var html = $.ajax({
			type: "POST",
			url: "inc/ajax.recaptcha.php",
			data: "recaptcha_challenge_field=" + challengeField + "&recaptcha_response_field=" + responseField,
			async: false
		}).responseText;
	 
		if(html == "success") {
			//Add the Action to the Form
			//Indicate a Successful Captcha
			$("#captchaStatus").removeClass('error').addClass('success').html("Success!").show();
			// Uncomment the following line in your application
			return true;
		} else {
			$("#captchaStatus").html("The security code you entered did not match. Please try again.").show();
			Recaptcha.reload();
			return false;
		}
	}   
	
	//Post a comment 
	$('#postComment').click(function() {
		if ($('#commentForm #name').length) { // if #name exits
			$('#name').focus();
		} else {
			$('#comment').focus();
		}
		return false;
	});
	
	//Reply to comment
	$('.replyToComment').live("click", function() {
		var comment = $(this).attr('id');
		var commentURL = $(this).attr('href');
		var name = $(this).parents('.singleComment').find('#commentUser').text().trim();
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
	$('#removeReply').live("click", function() {
		$(this).parent().remove();
		$('#commentLabel').show();
		return false;
	});
	
/* ------------------------------------------- */
/* AJAX Helper */
/* ------------------------------------------- */

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
				$("#"+form+" input[name="+obj+"]").addClass('invalidField');
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

/* ------------------------------------------- */
/* Edit user profile */
/* ------------------------------------------- */

	$('#editProfile').live('click', function() {
		$('#personalCont').hide();
		$('#personalCont.edit').show();
		$(this).hide();
		$('#editProfileSave').show();
		return false;
	});

	$("#profileform").validate();

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
				$('#descCont').text(data.desc);
			} else $('#descCont').text('Add some personal info....');
			if(data.facebook) {
				$('#personalLinks .facebook a').attr('href', data.facebook);
				$('#personalLinks .facebook').show();
			} else $('#personalLinks .facebook').hide();
			if(data.twitter) {
				$('#personalLinks .twitter a').attr('href', 'http://www.twitter.com/'+data.twitter).text('@'+data.twitter);
				$('#personalLinks .twitter').show();
			} else $('#personalLinks .twitter').hide();
			if(data.email) {
				$('#personalLinks .useremail a').attr('href', 'mailto:'+data.email).text(data.email);
				$('#personalLinks .useremail').show();
			} else $('#personalLinks .useremail').hide();
			if(data.weburl) {
				$('#personalLinks .website a').attr('href', data.weburl);
				if(data.webname)
					$('#personalLinks .website a').text(data.webname);
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

/* END */

	//Like comment
	$('.commentAction #like').live("click", function() {
		rateComment(this, 'like');
		return false;
	});
	
	//Dislike comment
	$('.commentAction #dislike').live("click", function() {
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

	$('.circle').mosaic({
		opacity	 :   0.8		 //Opacity for overlay (0-1)
	});

	$('.play').mosaic({
		opacity	 :   0.8		 //Opacity for overlay (0-1)
	});

	//Contact form validation
	$("#contactform").submit(function() {
		$("#contactform label.error").hide();
		var name = $('#contactform #name').val();
		var email = $('#contactform #email').val();
		var message = $('#contactform #message').val();
		var token = $('#contactform').find('#token').val();
		var check = 'contact_us';

		data = {};
		data.action = 'contact_us';
		data.name = name;
		data.email = email;
		data.message = message;
		data.token = token;
		data.check = check;
		
		ajaxHelper('#contactform', 'POST', data, '#contactform #sending', ['#contactform #submit'], null, '#sent', null, ajaxCallback);
	
		function ajaxCallback(data, message) {
			$('#contactform').hide();
		}

		return false;
	});

	function toggleBox(box) {
		$(box).show();
		$(box).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
		$(box).delay(300).hide();
	}
	
	$('input').change(function() {
		$(this).removeClass('invalidField');
	});
});


