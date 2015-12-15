$(document).ready(function () {

	/**
	 * Validator for send referrals page 
	 */
	$("#ContactForm").validate({
	    rules: {
	        'data[Contact][first_name]': {
	            required: true,
	            'APSH': true,
	            maxlength: 20
	        },
	        'data[Contact][last_name]': {
	            required: true,
	            'APSH': true,
              maxlength: 20
	        },
	        'data[Contact][job_title]': {
              'APSH':true,
	            maxlength: 35
	        },
	        'data[Contact][address]': {
              'ANPSH':true,
	            maxlength: 60
	        },
	        'data[Contact][email]': {
              email: true,                
	        },
	        'data[Contact][office_phone]': {
            	required:true,
            	number:true,
              maxlength: 15, 
	        },
	        'data[Contact][mobile]': {
            	number:true,
              maxlength: 15, 
	        },
	        'data[Contact][country_id]':{
            	required: true
	        },
	        'data[Contact][state_id]':{
            	required: true
	        },
	        'data[Contact][city]':{
            	'APSH':true,
            	maxlength: 35
	        },
	        'data[Contact][zip]':{
            	'ANPSH': true,
            	minlength: 3,
              maxlength: 12
	        }
	    },
	    messages: {
	        'data[Contact][first_name]': {
	            required: "This field is required",
	            maxlength: "First name can have maximum 20 characters",
	        },
	        'data[Contact][last_name]': {
	            required: "This field is required",
	            maxlength: "Last name can have maximum 20 characters",
	        },
	        'data[Contact][job_title]': {
	            maxlength: "Job title can have maximum 35 characters",
	        },
	        'data[Contact][address]': {
	            maxlength: "Address can have maximum 60 characters",
	        },
	        'data[Contact][email]': {
              email: "Please enter valid email address.",                
	        },
	        'data[Contact][office_phone]': {
            	required: "This field is required",
            	number:"Only numeric characters, are allowed",
              maxlength: "Office phone can have maximum 15 characters", 
	        },
	        'data[Contact][mobile]': {
            	number:"Only numeric characters, are allowed",
              maxlength: "Mobile phone can have maximum 15 characters", 
	        },
	        'data[BusinessOwner][city]':{
  	        	maxlength: "City can have minimum 1 and maximum 35 characters.",
  	        	specialCharCheck: "Only alphabetic characters are allowed."
	        },
	        'data[Contact][company]': {
              maxlength: "Company name can have maximum 30 characters "
	        },
	        'data[Contact][zip]':{
  	        	minlength: "ZIP code should be minimum 3 and maximum 12 characters.",
  	        	maxlength: "ZIP code should be minimum 3 and maximum 12 characters.",
	        }
	    },
	    submitHandler: function (form) {
			$('#next').attr('disabled',true);
	    	form.submit();
	    }
	});

	// special character check no number
	$.validator.addMethod("APSH", function (value, element) {
	    var i = /^[A-Za-z \- . ]+$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Only alphabets, period, space and hyphen are allowed");

	// special character check with number
	$.validator.addMethod("ANPSH", function (value, element) {
	    var i = /^[A-Za-z0-9 \- . ]+$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Only alphanumeric characters, space, period and hyphen are allowed.");
});