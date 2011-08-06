/* Author: J.Kim

*/

$(document).ready(function() {
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
	
	//Printing Signup
	$("#emailform").validate({
		submitHandler: function() {
			var email = $('#email').val();
			$('#submit').hide();
			$('.loading').show();
			//alert(email);
			$.ajax({
				url: "/maintenance/addemail.php",
				type: "POST",
				data: ({
					email: email
				}),
				async:true,
				success: function(msg){
					//alert(msg);
					setTimeout(function(){ 
						$('#emailsignup').fadeOut(500, function() {
							$('#emailsuccess').fadeIn(500);
						}); 
					}, 500);
				},
				error: function(msg){
					alert(msg);
				}
			});
			return false;
		}
	});
	
	//Get time till 25th of April
	var date = new Date("April 24, 2011 23:59:59");
	var now = new Date();
	
	var days, hours, minutes, seconds;
	days = date.getDate() - now.getDate();
	hours = Math.abs(date.getHours() - now.getHours());
	minutes = Math.abs(date.getMinutes() - now.getMinutes());
	seconds = Math.abs(date.getSeconds() - now.getSeconds());
 
	seconds = add_leading_zero( seconds );
	minutes = add_leading_zero( minutes );
	hours = add_leading_zero( hours );
	days = add_leading_zero( days ); 
 
	//log(days + ':' + hours + ':' + minutes + ':' + seconds);
	
	function add_leading_zero(n) {
		if(n.toString().length < 2) {
			return '0' + n;
		} else {
			return n;
		}
	}
		
	//Counter 
	$('#counter').countdown({
        image: '/maintenance/img/digits.png',
		startTime: days+':'+hours+':'+minutes+':'+seconds,
		timerEnd: function(){ alert('Wow you actually stayed until the end! You win sir!'); }
    });

});






















