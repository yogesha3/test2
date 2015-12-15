$(document).ready(function () {

	/**
	 * Validator for change password page 
	 */
	$("#ChangePassword").validate({
	    rules: {
	        'data[User][password]': {
	            required: true,
	            nowhitespace:true,
	        },
	        'data[User][new_password]': {
	            required: true,
	            nowhitespace:true,
	            minlength : 6,
              	maxlength: 20
	        },
	        'data[User][confirm_password]' :{
	        	required: true,
	        	nowhitespace:true,
	        	minlength : 6,
              	maxlength: 20,
              	equalTo: "#new_password",
	        }	        
	    },
	    messages: {
	        'data[User][password]': {
	            required: "This field is required"
	        },
	        'data[User][new_password]': {
	            required: "This field is required",
	            minlength : "Password should be minimum 6 characters and maximum 20 characters long",
	            maxlength: "Password should be minimum 6 characters and maximum 20 characters long",
	        },
	        'data[User][confirm_password]': {
	        	required: "This field is required",
	            minlength : "Password should be minimum 6 characters and maximum 20 characters long",
	            maxlength: "Password should be minimum 6 characters and maximum 20 characters long",
	            equalTo : "Password does not match",
	        }
	    },
	    submitHandler: function (form) {
			$('#submitbutton').attr('disabled',true);
			form.submit();
	    }
	});	
	
	// for white space check
	$.validator.addMethod("nowhitespace", function(value, element) {
		return value.indexOf(" ") < 0;
    }, "Space is not allowed in password");
});
