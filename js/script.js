/* 
	Author: J.Kim
*/

$(document).ready(function() {
	
	// Reload css to fix @font face issue in IE8
	$('#main_css')[0].href=$('#main_css')[0].href;
	
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
	
	// Felix twitter
	if ($('#felixtwitterlist').length && !$('.sidebar .twitterbox').length) {
		$.getJSON('http://api.twitter.com/1/statuses/user_timeline.json?screen_name=feliximperial&count=5&include_rts=true&callback=?', function(twitters) {
			var statusHTML = [];
			for (var i=0; i<twitters.length; i++){
				var username = twitters[i].user.screen_name;
				var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
					return '<a href="'+url+'">'+url+'</a>';
				}).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
					return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
				});
				statusHTML.push('<li class="twitter-item"><span>'+status+'</span> <a style="font-size:85%" href="http://twitter.com/'+username+'/statuses/'+twitters[i].id+'" class="tweet">'+relative_time(twitters[i].created_at)+'</a></li>');
			}
			document.getElementById('felixtwitterlist').innerHTML = statusHTML.join('');
		});
	}
	
	// Category twitter
	if($('.sidebar .twitterbox').length) {
		var id = $('.sidebar .twitterbox').attr('id');
		$.getJSON('http://api.twitter.com/1/statuses/user_timeline.json?screen_name='+id+'&count=4&include_rts=true&callback=?', function(twitters) {
			var statusHTML = [];
			for (var i=0; i<twitters.length; i++){
				var username = twitters[i].user.screen_name;
				var userimage = twitters[i].user.profile_image_url;
				var location = twitters[i].user.location;
				var name = twitters[i].user.name;
				var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
					return '<a href="'+url+'">'+url+'</a>';
				}).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
					return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
				});
				statusHTML.push('<li class="twitter-item"><span>'+status+'</span> <a style="font-size:85%" href="http://twitter.com/'+username+'/statuses/'+twitters[i].id+'" class="tweet">'+relative_time(twitters[i].created_at)+'</a></li>');
			}
			$('.sidebar #twitheader #twitpiclink').attr('href', 'http://twitter.com/'+username);
			$('.sidebar #twitheader img').attr('src', userimage);
			$('.sidebar #twitheader h5').text(name);
			$('.sidebar #twitheader p a').attr('href', 'http://twitter.com/'+username).text('@'+username);
			$('.sidebar #twitheader p span').text(location);
			
			document.getElementById('felixtwitterlist').innerHTML = statusHTML.join('');
		});
	}
	
    // Load sharing links
    if ($('.sidebar2 #sharebuttons').length) { // If sidebar 2 exists
    	var facebook = '<fb:like send="false" layout="button_count" width="140" show_faces="false" font="arial"></fb:like>';
		var twitter = '<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="feliximperial">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
		var digg = '<a class="DiggThisButton DiggCompact"></a>';
		
		$('#facebookLike').append(facebook);
		$('#twitterShare').append(twitter);
		$('#diggShare').append(digg);
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
		//$('#facebox #loginForm').prepend('Test');
		//console.log("test");
		if ($('#loginForm #commenttype').length){
			$('#loginForm #commenttype').remove();
			$('#loginForm #comment').remove();
		}
		var comment = $(this).parents('.commentAction').attr('id');
		$('#loginForm').prepend('<input type="hidden" value="like" name="commenttype" id="commenttype"/><input type="hidden" value="'+comment+'" name="comment" id="comment"/>');
	});
	
	$('.dislikeComment').bind('click', function() {
		//$('#facebox #loginForm').prepend('Test');
		//console.log("test");
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
/* Edit user profile */
/* ------------------------------------------- */
	
	$('#editProfile').live('click', function() {
		$('#personalCont').hide();
		$('#personalCont.edit').show();
		$(this).hide();
		$('#editProfileSave').show();
		return false;
	});
	
	// Placeholder support 
	$('[placeholder]').focus(function() {
	  var input = $(this);
	  if (input.val() == input.attr('placeholder')) {
		input.val('');
		input.removeClass('placeholder');
	  }
	}).blur(function() {
	  var input = $(this);
	  if (input.val() == '' || input.val() == input.attr('placeholder')) {
		input.addClass('placeholder');
		input.val(input.attr('placeholder'));
	  }
	}).blur().parents('form').submit(function() {
	  $(this).find('[placeholder]').each(function() {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
		  input.val('');
		}
	  })
	});

	$("#profileform").validate();
	
	$('#editProfileSave').click( function() {
		$('.user').find('[placeholder]').each(function() {
			var input = $(this);
			if (input.val() == input.attr('placeholder')) {
				input.val('');
			}
		});
		
		if(($('.facebook input').valid() || $('.facebook input').val() == '' ) && ($('.useremail input').valid() || $('.useremail input').val() == '' ) && ($('.website #url').valid() || $('.website #url').val() == '' )) {
			$('#personalLinksEdit label.error').hide();
			// Get new information and then save through ajax
			$('#userInfoCont .loading').show();
			var link = $(this).hide();
			
			var desc = $('#descCont textarea').val();
			var facebook = $('.facebook input').val();
			var twitter = $('.twitter input').val();
			var email = $('.useremail input').val();
			var webname = $('.website #name').val();
			var weburl = $('.website #url').val();
			
			//log(desc+' '+facebook+' '+twitter+' '+email+' '+webname+' '+weburl);
			
			$.ajax({
				url: "ajax.php",
				type: "POST",
				data: ({
					type: 'profilechange', 
					desc: desc, 
					facebook: facebook, 
					twitter: twitter, 
					email: email, 
					webname: webname, 
					weburl: weburl, 
					user: user 
				}),
				async:true,
				success: function(msg){
					//alert(msg);
					
					// Change profile info
					if(desc) {
						$('#descCont').text(desc);
					} else $('#descCont').text('Add some personal info....');
					if(facebook) {
						$('#personalLinks .facebook a').attr('href', facebook);
						$('#personalLinks .facebook').show();
					} else $('#personalLinks .facebook').hide();
					if(twitter) {
						$('#personalLinks .twitter a').attr('href', 'http://www.twitter.com/'+twitter).text('@'+twitter);
						$('#personalLinks .twitter').show();
					} else $('#personalLinks .twitter').hide();
					if(email) {
						$('#personalLinks .useremail a').attr('href', 'mailto:'+email).text(email);
						$('#personalLinks .useremail').show();
					} else $('#personalLinks .useremail').hide();
					if(weburl) {
						$('#personalLinks .website a').attr('href', weburl);
						if(webname) 
							$('#personalLinks .website a').text(webname);
						else 
							$('#personalLinks .website a').text(weburl);
						$('#personalLinks .website').show();
					} else $('#personalLinks .website').hide();
					
					$('#personalCont').show();
					$('#personalCont.edit').hide();
					setTimeout(function(){ 
						$('#userInfoCont .loading').fadeOut(500, function() {
							$('#editProfile').fadeIn(500);
						}); 
					}, 500);
				},
				error: function(msg){
					alert(msg);
				}
			});
		}
		return false;
	});

/* END */
	
	//Like comment
	// TODO: add ajax loader when "liking"
	$('.commentAction #like').live("click", function() {
		var likelink = $(this); 
		var comment = $(this).parents('.commentAction').attr('id');
		$.ajax({
			url: "ajax.php",
			type: "POST",
			data: ({type: 'like', comment:comment, user: user }),
			async:true,
			success: function(msg){
				//alert(msg);
				likelink.next('#likecounter').text('('+msg+')');
				likelink.parent().prepend('Liked');
				likelink.parent().next().prepend('Disliked');
				likelink.parent().next().children('a').remove();
				likelink.remove();
			},
			error: function(msg){
				alert(msg);
			}
		});
		return false;
	});
	
	//Dislike comment
	$('.commentAction #dislike').live("click", function() {
		var likelink = $(this); 
		var comment = $(this).parents('.commentAction').attr('id');
		$.ajax({
			url: "ajax.php",
			type: "POST",
			data: ({type: 'dislike', comment:comment, user: user }),
			async:true,
			success: function(msg){
				//alert(msg);
				likelink.next('#dislikecounter').text('('+msg+')');
				likelink.parent().prepend('Disliked');
				likelink.parent().prev().prepend('Liked');
				likelink.parent().prev().children('a').remove();
				likelink.remove();
			},
			error: function(msg){
				alert(msg);
			}
		});
		return false;
	});
	
	$('.circle').mosaic({
		opacity		:	0.8			//Opacity for overlay (0-1)
	});
	
	$('.play').mosaic({
		opacity		:	0.8			//Opacity for overlay (0-1)
	});
				
	
	//Contact form validation
	$("#contactform").submit(function() {
		var messageText = $("#contactform #message").val();
		if(!messageText) {
			$("#contactform label.error").show();
			return false;
		} else {
			$("#contactform label.error").hide();
			var name = $('#contactform #name').val();
			var email = $('#contactform #email').val();
			
			$('#contactform #submit').hide();
			$('#contactform #sending').show();
			
			submit_message(name, email, messageText);
			return false;
		}
	});
	
	function submit_message(name, email, message) {
		$.ajax({
			url: "ajax.php",
			type: "POST",
			data: ({type: 'sendmessage', name:name, email: email, message:message }),
			async:true,
			success: function(msg){
				//alert(msg);
				setTimeout(function(){ 
					$('#contactform').fadeOut(500, function() {
						$('#sent').fadeIn(500);
					}); 
				}, 500);
			},
			error: function(msg){
				alert(msg);
			}
		});
		return false;
	}
	
	// Summer ball feedback validation
	$("#sbform").validate();
	
	$('#sbform #didyou input').click(function() {
		var val = $(this).val();
		if(val == 'Yes'){
			$('#sbform #commentlabel').html('What did you think of it? <span>(required)</span>');
		} else {
			$('#sbform #commentlabel').html('Why not? <span>(required)</span>');
		}
	});
	
});


