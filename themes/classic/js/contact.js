/**
 * JS for contact form page
 */
$(function() {
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
		
		ajaxHelper(
			'#contactform',
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
});
