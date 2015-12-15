$(document).ready(function () {

	/**
	 * Validator for send referrals page 
	 */
	$("#ContactForm").validate({
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
	        	'VURL': true,
	        },
	        'data[Contact][note]': {
	            maxlength: 500
	        },
          'data[Contact][attachment][0]':{
              extension: "jpeg|jpg|png|doc|docx|pdf|xls|xlsx",
              fileSize:true
          },
	        'data[Contact][attachment][1]':{
              extension: "jpeg|jpg|png|doc|docx|pdf|xls|xlsx",
              fileSize:true
            },
	        'data[Contact][attachment][2]':{
              extension: "jpeg|jpg|png|doc|docx|pdf|xls|xlsx",
              fileSize:true
          },
	        'data[Contact][attachment][3]':{
              extension: "jpeg|jpg|png|doc|docx|pdf|xls|xlsx",
              fileSize:true
          },
	        'data[Contact][attachment][4]':{
              extension: "jpeg|jpg|png|doc|docx|pdf|xls|xlsx",
              fileSize:true
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
	        },
	        'data[Contact][address]': {
	            maxlength: "Address can have maximum 60 characters",
	            'ANPCSH' : "Only alphanumeric characters, space, period, comma, parenthesis and hyphen are allowed",
	        },
	        'data[Contact][email]': {
				required: "This field is required",
              	email: "Please enter valid email address.",                
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
  	        	maxlength: "City can have maximum 35 characters.",
  	        	specialCharCheck: "Only alphabetic characters are allowed."
	        },
	        'data[Contact][company]': {
              maxlength: "Company name can have maximum 35 characters.",
              'ANPSH':"Only alphanumeric characters, space, period and hyphen are allowed",
	        },
	        'data[Contact][zip]':{
  	        	minlength: "ZIP code should be minimum 3 and maximum 12 characters.",
  	        	maxlength: "ZIP code should be minimum 3 and maximum 12 characters.",
	        },
	        'data[Contact][website]' : {
	        	'VURL' : 'Please enter valid URL'
	        },
	        'data[Contact][note]': {
	            maxlength: "The message should not exceed 500 characters"
	        }
	    },
      errorPlacement: function(error, element) {
          if (element.attr("name") == "data[Contact][attachment][0]") {
              error.insertAfter("#maindiv");
          } else if(element.attr("name") == "data[Contact][attachment][1]"){
            error.insertAfter("#attachment2");
          } else if(element.attr("name") == "data[Contact][attachment][2]"){
            error.insertAfter("#attachment3");
          } else if(element.attr("name") == "data[Contact][attachment][3]"){
            error.insertAfter("#attachment4");
          } else if(element.attr("name") == "data[Contact][attachment][4]"){
            error.insertAfter("#attachment5");
          } else {
              error.insertAfter(element);
          }
      },
	    submitHandler: function (form) {
	    	if (!$('#multiselect').val()) {
	    		$('.btn-group').css("border", "1px solid red");
	    		$(".errorMember").remove();
	    		$(".custom").remove();
	    		$( ' <div class="clearfix custom"></div><label class="errorMember" style="color:#c83a2a;">Please select a member</label>' ).insertAfter( ".btn-group" );
	    	}
			else if($('.errorMember').length > 0){
				var valid1 = $('.errorMember').html();
				if(valid1 == '' ) {
					$('.file_sent_btn').attr('disabled',true);
					form.submit();
				}
			} else {
				$('.file_sent_btn').attr('disabled',true);
				form.submit();
			}
	    }
	});

	$("#referralUpdate").validate({
	    rules: {
	        'data[ReceivedReferral][first_name]': {
	            required: true,
	            'APSH': true,
	            maxlength: 20
	        },
	        'data[ReceivedReferral][last_name]': {
	            required: true,
	            'APSH': true,
              	maxlength: 20
	        },
	        'data[ReceivedReferral][company]' :{
	        	maxlength: 35,
	        	'ANPSH':true,
	        },
	        'data[ReceivedReferral][job_title]': {
	            required: true,
                maxlength: 35
	        },
	        'data[ReceivedReferral][address]': {
                'ANPCSH':true,
                 maxlength: 60
	        },
	        'data[ReceivedReferral][email]': {
	            required: true,
              	email: true,                
	        },
	        'data[ReceivedReferral][office_phone]': {
            	isNumeric:true,
                maxlength: 15, 
	        },
	        'data[ReceivedReferral][mobile]': {
            	isNumeric:true,
                maxlength: 15, 
	        },
	        'data[ReceivedReferral][country_id]':{
            	required: true
	        },
	        'data[ReceivedReferral][state_id]':{
            	required: true
	        },
	        'data[ReceivedReferral][city]':{
            	'APSH':true,
            	maxlength: 35
	        },
	        'data[ReceivedReferral][zip]':{
            	'ANPSH': true,
            	minlength: 3,
              maxlength: 12
	        },
	        'data[ReceivedReferral][website]': {
	        	'VURL' : true,
	        },
          'data[ReceivedReferral][monetary_value]': {
	            'intgersOnly':true
	        }
	    },
	    messages: {
	        'data[ReceivedReferral][first_name]': {
	            required: "This field is required",
	            maxlength: "First name can have maximum 20 characters",
	            'APSH':"First name can contain period, space and hyphen only including alphabets",
	        },
	        'data[ReceivedReferral][last_name]': {
	            required: "This field is required",
	            maxlength: "Last name can have maximum 20 characters",
	            'APSH':"Last name can contain period, space and hyphen only including alphabets",
	        },
	        'data[ReceivedReferral][job_title]': {
	            required: "This field is required",
	            maxlength: "Job title can have maximum 35 characters",
	        },
	        'data[ReceivedReferral][address]': {
	            maxlength: "Address can have maximum 60 characters",
	            'ANPCSH' : "Only alphanumeric characters, space, period, comma, parenthesis and hyphen are allowed",
	        },
	        'data[ReceivedReferral][email]': {
	            required: "This field is required",
              email: "Please enter valid email address.",                
	        },
	        'data[ReceivedReferral][office_phone]': {
            	isNumeric:"Only numeric characters are allowed",
                maxlength: "Office phone can have maximum 15 characters", 
	        },
	        'data[ReceivedReferral][mobile]': {
            	isNumeric:"Only numeric characters are allowed",
                maxlength: "Mobile phone can have maximum 15 characters", 
	        },
	        'data[ReceivedReferral][city]':{
  	        	maxlength: "City can have maximum 35 characters.",
  	        	specialCharCheck: "Only alphabetic characters are allowed."
	        },
	        'data[ReceivedReferral][company]': {
              maxlength: "Company name can have maximum 35 characters.",
              'ANPSH':"Only alphanumeric characters, space, period and hyphen are allowed",
	        },
	        'data[ReceivedReferral][zip]':{
  	        	minlength: "ZIP code should be minimum 3 and maximum 12 characters.",
  	        	maxlength: "ZIP code should be minimum 3 and maximum 12 characters.",
	        },
	        'data[ReceivedReferral][website]' : {
	        	'VURL' : 'Please enter valid URL',
	        },
	        'data[ReceivedReferral][monetary_value]': {
	            'intgersOnly':"&nbsp;Only numeric characters are allowed."
	        }
	    },
	    submitHandler: function (form) {
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

	// special character check with comma
	$.validator.addMethod("ANPCSH", function (value, element) {
	    var i = /^[A-Za-z0-9 \- . , ( ) ]+$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Only alphanumeric characters, space, period and hyphen are allowed.");
        
    // special character inclusing & @ check no number
    $.validator.addMethod("APRSH", function (value, element) {
	    var i = /^[A-Za-z \- . @ &]+$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Only alphabets, space, period, ampersand, at the rate (@) and hyphen are allowed");
  // to check whether the select box is selected
  $.validator.addMethod('selectcheck', function (value) {
        return (value == 0);
  }, "Please select a member");
  
  //to check whether value is integer
  $.validator.addMethod("intgersOnly", function (value, element) {
	    var i = /^[0-9]+$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Only numbers are allowed.");
  
  // check file size
	$.validator.addMethod("fileSize", function (val, element) {
        if(element.files[0] && element.files[0].size > 10000000) {
            return false;
        } else {
            return true;
        }
      }, "File size should be less than or equal to 10 MB");
	
	$.validator.addMethod("extension", function(value, element, param) {
		param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
		return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
	}, jQuery.format("File format not supported"));
	
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
});
