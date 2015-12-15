$(document).ready(function () {

	/**
	 * Validator for add contact page 
	 */
	$("#addContactForm").validate({
            ignore: [],
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
	        'data[Contact][company]' :{
	        	maxlength: 35,
	        	'ANPSH':true,
	        },
	        'data[Contact][job_title]': {
	            required: true,
	           maxlength: 35
	        },
	        'data[Contact][address]': {
              'ANPCSH':true,
	           maxlength: 60
	        },
	        'data[Contact][email]': {
	            required: true,
                email: true,
                remote: {
                    url: BASE_URL+"contacts/checkContactExist",
                    type: "post"
                }
	        },
	        'data[Contact][office_phone]': {
            	isNumeric:true,
                maxlength: 15, 
	        },
	        'data[Contact][mobile]': {
            	isNumeric:true,
                maxlength: 15, 
	        },
	        'data[Contact][city]':{
            	'APSH':true,
            	maxlength: 35
	        },
	        'data[Contact][zip]':{
            	'ANPSH': true,
            	minlength: 3,
              maxlength: 12
	        },
	        'data[Contact][website]': {
	        	'VURL' : true,
	        }
	    },
	    messages: {
	        'data[Contact][first_name]': {
	            required: "This field is required",
	            maxlength: "First name can have maximum 20 characters",
	            'APSH':"First name can contain period, space and hyphen only including alphabets",
	        },
	        'data[Contact][last_name]': {
	            required: "This field is required",
	            maxlength: "Last name can have maximum 20 characters",
	            'APSH':"Last name can contain period, space and hyphen only including alphabets",
	        },
	        'data[Contact][job_title]': {
	            required: "This field is required",
	            maxlength: "Job title can have maximum 35 characters",
	            'APSH':"Only alphabets, space, period and hyphen are allowed",
	        },
	        'data[Contact][address]': {
	            maxlength: "Address can have maximum 60 characters",
	            'ANPCSH' : "Only alphanumeric characters, space, period, comma, parenthesis and hyphen are allowed",
	        },
	        'data[Contact][email]': {
	            required: "This field is required",
                email: "Please enter valid email address",
                remote: "Contact with same email already exists"
	        },
	        'data[Contact][office_phone]': {
            	isNumeric:"Only numeric characters are allowed",
                maxlength: "Office phone can have maximum 15 characters", 
	        },
	        'data[Contact][mobile]': {
            	isNumeric:"Only numeric characters are allowed",
                maxlength: "Mobile phone can have maximum 15 characters", 
	        },
	        'data[Contact][city]':{
  	        	maxlength: "City can have maximum 35 characters",
  	        	specialCharCheck: "Only alphabetic characters are allowed"
	        },
	        'data[Contact][company]': {
              maxlength: "Company name can have maximum 35 characters",
              'ANPSH':"Only alphanumeric characters, space, period and hyphen are allowed",
	        },
	        'data[Contact][zip]':{
  	        	minlength: "ZIP code should be minimum 3 and maximum 12 characters",
  	        	maxlength: "ZIP code should be minimum 3 and maximum 12 characters",
	        },
	        'data[Contact][website]' : {
	        	'VURL' : 'Please enter valid URL',
	        }
	    },
	    /*submitHandler: function (form) {
		if($('.errorMember').length > 0){
			var valid1 = $('.errorMember').html();
			if(valid1 == '' ) {
				$('.file_sent_btn').attr('disabled',true);
				form.submit();
			}
		} else {
			$('.file_sent_btn').attr('disabled',true);
			form.submit();
		}
	    }*/
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
	}, "Only alphanumeric characters, space, period and hyphen are allowed");

	// special character check with comma
	$.validator.addMethod("ANPCSH", function (value, element) {
	    var i = /^[A-Za-z0-9 \- . , ( ) ]+$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Only alphanumeric characters, space, period and hyphen are allowed");
  
  // to check whether the select box is selected
  $.validator.addMethod('selectcheck', function (value) {
        return (value == 0);
  }, "Please select a member");
  
  //to check whether value is integer
  $.validator.addMethod("intgersOnly", function (value, element) {
	    var i = /^[0-9]+$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Only numbers are allowed");

	//to check valid url without http and www
	  $.validator.addMethod("VURL", function (value, element) {
		    var i = /(^|\s)((https?:\/\/)?[\w-]+(\.[\w-]+)+\.?(:\d+)?(\/\S*)?)/;
		    return this.optional(element) || (i.test(value) > 0);
		}, "valid VURL");
	
	//to check only numeric value	
	$.validator.addMethod("isNumeric", function (value, element) {
	    var i = /^[0-9]+$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Invalid Numeric value");

	// special character inclusing & @ check no number
    $.validator.addMethod("APRSH", function (value, element) {
	    var i = /^[A-Za-z \- . @ &]+$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Only alphabets, space, period, ampersand, at the rate (@) and hyphen are allowed");
});
