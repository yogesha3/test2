/**
 * this file contains the validations for  panel
 */

/******* Homepage Newsletter Subscribe validator ******/
$(document).ready(function () {
	
	$.validator.addMethod("conditionCheck_numeric", function (value, element) {
    	var expr=/^[0-9]+$/;
        return (expr.test(value) > 0);
    }, "Please enter a valid number");
	
	$.validator.addMethod("conditionCheck_discount", function (value, element) {
    	
        return this.optional(element) || (value >0 && value<=99);
    }, "Discount% must be between 1 and 99");
	
	$("#NewsletterSubscribeForm").validate({
	    rules: {
	        'data[NewsletterSubscribe][subscribe_email_id]': {
	            required: true,
	            email: true,
	        }
	
	    },
	    messages: {
	        'data[NewsletterSubscribe][subscribe_email_id]': {
	            required: "This field is required",
	            email:"Please enter valid email id"
	        }
	    },
	    submitHandler: function (form) {
	    	actionurl = $('#NewsletterSubscribeForm').attr('action');	    		
	    	$.ajax({
                type:'POST',
                url: actionurl,
                success: function(response) {
                	if(response=='success'){
	                	label = '<label class="success_msg">Subscribed Successfully.</label>';
	                	$( label ).insertAfter("#subscribe_email_id").delay(3000).fadeOut(100);
	                	$("#subscribe_email_id").val('');
	                }else{
		                	if(response=='error'){
		                	label = '<label class="error">Not Subscribed. Please try again!</label>';
			                $( label ).insertAfter( "#subscribe_email_id" );
		                }else {
		                	label = '<label class="error">Already Subscribed. Please try with different Email-id!</label>';
			                $( label ).insertAfter( "#subscribe_email_id" );
		                }
	                }
                },
                data:jQuery('form').serialize()
            });
	    	return false;
	    }
	});
	
	/**
	 * Validator for mini profile page 
	 */
	$("#miniProfileForm").validate({
           ignore: [],
	    rules: {
	        'data[BusinessOwner][fname]': {
	            required: true,
	            'specialCharCheck': true,
	            maxlength: 20
	        },
	        'data[BusinessOwner][lname]': {
	            required: true,
	            'specialCharCheck': true,
	            maxlength: 20
	        },
	        'data[BusinessOwner][company]': {
	        	required: true,
	        	'zipCheck': true,
	            minlength: 2,
	            maxlength: 30
	        },
	        'data[BusinessOwner][professionCategory_id]': {
	        	required: true
	        },
	        'data[BusinessOwner][profession_id]': {
	        	required: true,
	        	/*'specialCharCheck': true,
	        	minlength: 5,
	            maxlength: 30*/
	        },
	        'data[User][user_email]': {
                required: true,
                email: true,                
	        },
	        'data[BusinessOwner][confirm_email_address]': {
                required: true,
                email: true,
                equalTo: "#email_address"
	        },
	        'data[BusinessOwner][password]': {
                required: true,
                nowhitespace: true,
                minlength: 6,
                maxlength: 20
	        },
	        'data[BusinessOwner][cpassword]': {
                required: true,
                minlength: 6,
                maxlength: 20,
                equalTo: "#password"
	        },
	        'data[BusinessOwner][country_id]':{
	        	required: function(element) {                	
                            if($("#country_id").val()!=''){
                                return false;
                            }else{
                                $('#country').addClass('error');
                                return true;
                            }
                        },
	        },
	        'data[BusinessOwner][state_id]':{
	        	required: function(element) {                	
                            if($("#state_id").val()!=''){
                                return false;
                            }else{
                                $('#state').addClass('error');
                                return true;
                            }
                        },
	        },
	        'data[BusinessOwner][city]':{
	        	maxlength: 35,
	        	'specialCharCheck':true
	        },	        
	        'data[BusinessOwner][zipcode]':{
	        	required: true,
	        	'zipCheck': true,
	        	minlength: 3,
                maxlength: 12,
	        },
	        'data[BusinessOwner][timezone_id]':{
	        	required: true
	        },
	        'data[BusinessOwner][CC_Name]':{	        	
                required: true,
                'specialCharCheck': true,
	        },
	        'data[BusinessOwner][CC_Number]':{	        	
                required: true,
                creditcard: true,
                minlength: 13,
                maxlength: 16,
	        },
	        'data[BusinessOwner][expiration_month][month]':{	        	
                required: true
	        },
	        'data[BusinessOwner][expiration_year][year]':{	        	
                required: true
	        },
	        'data[BusinessOwner][cvv]':{	        	
                required: true,
                'cvvCheck':true
	        }
	    },
	    messages: {
	        'data[BusinessOwner][fname]': {
	            required: "This field is required",
	            maxlength: "First name can have minimum 1 and maximum 20 characters.",
	        },
	        'data[BusinessOwner][lname]': {
	            required: "This field is required",
	            maxlength: "Last name can have minimum 1 and maximum 20 characters.",
	        },
	        /*'data[BusinessOwner][profession_id]': {
	        	minlength: "Profession name should be minimum 5 characters.",
                maxlength: "Profession name should be maximum 30 characters."
	        },*/
	        'data[User][user_email]': {
                email: "Please enter valid email address.",                
	        },
	        'data[BusinessOwner][confirm_email_address]': {
	        	email: "Please enter valid email address.",
                equalTo: "Email does not match."
	        },
	        'data[BusinessOwner][city]':{
	        	maxlength: "City can have minimum 1 and maximum 35 characters.",
	        	specialCharCheck: "Only alphabetic characters are allowed."
	        },
	        'data[BusinessOwner][company]': {
	        	'zipCheck': "Only alphanumeric characters, space, period and hyphen are allowed.",
	        	minlength: "Company name can have minimum 2 and maximum 30 characters.",
                maxlength: "Company name can have minimum 2 and maximum 30 characters."
	        },
	        'data[BusinessOwner][password]': {
	        	required: "This field is required",
	        	nowhitespace: "Space is not allowed in password.",
                minlength: "Password should be minimum 6 characters and maximum 20 characters.",
                maxlength: "Password should be minimum 6 characters and maximum 20 characters."
	        },
	        'data[BusinessOwner][cpassword]': {
	        	required: "This field is required",
	        	minlength: "Password should be minimum 6 characters and maximum 20 characters.",
	        	maxlength: "Password should be minimum 6 characters and maximum 20 characters.",
                equalTo: "Password does not match."
	        },
	        'data[BusinessOwner][zipcode]':{
	        	minlength: "ZIP code should be minimum 3 and maximum 12 characters.",
	        	maxlength: "ZIP code should be minimum 3 and maximum 12 characters.",
	        },
	        'data[BusinessOwner][CC_Number]':{	        	
                required: "This field is required",
                creditcard: "Please input a valid credit card number",
                minlength: "Credit Card must be of minimum 13 digits",
                maxlength: "Credit Card cannot exceed 16 digits",
	        },
	    },
	    submitHandler: function (form) {
			$('#next').attr('disabled',true);
	    	form.submit();
	    }
	});
	
	/**
	 * Validator for user login form
	 */
	$("#loginUserForm").validate({
	    rules: {
	    	'data[User][email]': {
                required: true
	        },
	        'data[User][password]': {
                required: true                
	        }
	    },
	    messages: {	        
	    	'data[User][email]': {
                required: "This field is required"             
	        },
	        'data[User][password]': {
                required: "This field is required"                
	        }
	    },
	    submitHandler: function (form) {
	    	$('#next').attr('disabled',true);
	    	form.submit();
	    }
	});
	
	/**
	 * Validator for reset password form
	 */
	$("#resetPasswordForm").validate({
	    rules: {
	    	'data[User][password]': {
                required: true,
                nowhitespace: true,
                minlength: 6,
                maxlength: 20
	        },
	        'data[User][cpassword]': {
                required: true,
                minlength: 6,
                maxlength: 20,
                equalTo: "#password"
	        },
	    },
	    messages: {	        
	        'data[User][password]': {
	        	required: "This field is required",
	        	nowhitespace: "Space is not allowed in password.",
                minlength: "Password should be minimum 6 characters and maximum 20 characters.",
                maxlength: "Password should be minimum 6 characters and maximum 20 characters."
	        },
	        'data[User][cpassword]': {
	        	required: "This field is required",
	        	minlength: "Password should be minimum 6 characters and maximum 20 characters.",
	        	maxlength: "Password should be minimum 6 characters and maximum 20 characters.",
                equalTo: "Password did not match."
	        }
	    },
	    submitHandler: function (form) {
	    	$('#next').attr('disabled',true);
	    	form.submit();
	    }
	});
	
	/**
	 * Validator for reset password form
	 */
	$("#forgetPasswordForm").validate({
	    rules: {
	    	'data[User][email]': {
                required: true,
                email: true
	        }
	    },
	    messages: {	        
	        'data[User][email]': {
	        	required: "This field is required",
	        	email: "Please enter a valid email address."
	        }
	    },
	    submitHandler: function (form) {
	    	$('#next').attr('disabled',true);
	    	form.submit();
	    }
	});
	
	/**
	 * Validator for create Group From
	 */
	$("#createGroupForm").validate({
            ignore: [],
	    rules: {
	    	'data[Group][group_type]': {
                required: true
	        },
	        'data[Group][group_name]': {
                required: true,
                'zipCheck':true,
                minlength: 4,
                maxlength: 25
	        },
	        'data[Group][first_meeting_date]': {
                required: true
	        },
	        'data[Group][second_meeting_date]': {
                required: true
	        },
	        'data[Group][meeting_time]': {
                required: true
	        },
	        'data[Group][country_id]': {
                    required: function(element) {                	
                            if($("#country_id").val()!=''){
                                return false;
                            }else{
                                $('#country').addClass('error');
                                return true;
                            }
                        },
	        },
	        'data[Group][state_id]': {
                    required: function(element) {                	
                            if($("#state_id").val()!=''){
                                return false;
                            }else{
                                $('#state').addClass('error');
                                return true;
                            }
                        },
	        },
	        'data[Group][city]': {
                maxlength: 35,
                'specialCharCheck':true
	        },
	        'data[Group][zipcode]': {
                required: true,
                'zipCheck':true,
                minlength: 3,
                maxlength: 12
	        },
	        'data[Group][timezone_id]': {
                required: true
	        }
	    },
	    messages: {	        
	    	'data[Group][group_name]': {
	    		'zipCheck':	"Only Alphanumeric characters, Space, Period and Hyphen are allowed",
	    		 minlength: "Title should be minimum 4 and maximum 25 characters.",
	             maxlength: "Title should be minimum 4 and maximum 25 characters."
	        },
	        'data[Group][city]': {
                maxlength: "City can have maximum 35 characters.",
                'specialCharCheck': "Only alphabetic characters are allowed."
	        },
	        'data[Group][zipcode]': {
	    		'zipCheck':	"Only Alphanumeric characters, Space, and Hyphen are allowed",
	    		 minlength: "ZIP code should be minimum 3 and maximum 12 characters.",
	             maxlength: "ZIP code should be minimum 3 and maximum 12 characters."
	        },
	    },
	    submitHandler: function (form) {
	    	$('.grpcreate').attr('disabled',true);
	    	form.submit();
	    }
	});
	
	// special character check
	$.validator.addMethod("specialCharCheck", function (value, element) {
	    var i = /^[A-Za-z \- . ]+$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Only alphabets, period, space and hyphen are allowed");
	
	// for password check
	$.validator.addMethod("pwcheck",
        function(value, element) {
            return /^[A-Za-z0-9\d=!\-$&+,:;=?@#|'<>.^*()%!-._*]+$/.test(value);
    });
	// special character check no number
	$.validator.addMethod("APSH", function (value, element) {
	    var i = /^[A-Za-z \- . ]+$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Only alphabets, period, space and hyphen are allowed");
	// for white space check
	$.validator.addMethod("nowhitespace", function(value, element) {
		return value.indexOf(" ") < 0;
    }, "No white space allowed"); 
	
	// for mobile check
	jQuery.validator.addMethod("phoneUS", 
		function(phone_number, element) {
		    phone_number = phone_number.replace(/\s+/g, "");
		    return this.optional(element) || phone_number.length > 9 && phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
		}
	);
	
	// Zip code check
	$.validator.addMethod("zipCheck", function (value, element) {
	    var i = /^[A-Za-z0-9 \- . ]+$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Only Alphanumeric characters, Space, and Hyphen are allowed.");
	
	// CVV code check
	$.validator.addMethod("cvvCheck", function (value, element) {
	    var i = /^[0-9]{3,4}$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Invalid CVC number");
	
	
	// dashboard pages validations start
	/**
	 * Validator for send message
	 */
	$("#composeMessageForm").validate({
		rules: {
	    	'data[Message][subject]': {
                required: true,               
                maxlength: 65
	        },
	        'data[Message][content]': {
                required: true,
                maxlength: 5000
	        },
	        'data[Message][attachment][0]':{
                extension: "gif|jpeg|jpg|png|doc|docx|pdf|xls|xlsx|csv",
                fileSize:true
            },
	        'data[Message][attachment][1]':{
                extension: "gif|jpeg|jpg|png|doc|docx|pdf|xls|xlsx|csv",
                fileSize:true
            },
	        'data[Message][attachment][2]':{
                extension: "gif|jpeg|jpg|png|doc|docx|pdf|xls|xlsx|csv",
                fileSize:true
            },
	        'data[Message][attachment][3]':{
                extension: "gif|jpeg|jpg|png|doc|docx|pdf|xls|xlsx|csv",
                fileSize:true
            },
	        'data[Message][attachment][4]':{
                extension: "gif|jpeg|jpg|png|doc|docx|pdf|xls|xlsx|csv",
                fileSize:true
            }
	    },
	    messages: {	        
	    	'data[Message][subject]': {	    		
	             maxlength: "Subject can have maximum 65 characters."
	        },
	        'data[Message][content]': {
	    		maxlength: "Message can have maximum 5000 characters."
	        },
	        'data[Message][attachment][0]':{
                extension: "File format not supported",
                fileSize: "File size too large"
            },
	        'data[Message][attachment][1]':{
                extension: "File format not supported",
                fileSize: "File size too large"
            },
	        'data[Message][attachment][2]':{
                extension: "File format not supported",
                fileSize: "File size too large"
            },
	        'data[Message][attachment][3]':{
                extension: "File format not supported",
                fileSize: "File size too large"
            },
	        'data[Message][attachment][4]':{
                extension: "File format not supported",
                fileSize: "File size too large"
            }     
	    },
	    errorPlacement: function(error, element) {
            if (element.attr("name") == "data[Message][attachment][0]") {
                error.insertAfter("#maindiv");
            }else if(element.attr("name") == "data[Message][attachment][1]"){
            	error.insertAfter("#attachment2");
            }else if(element.attr("name") == "data[Message][attachment][2]"){
            	error.insertAfter("#attachment3");
            }else if(element.attr("name") == "data[Message][attachment][3]"){
            	error.insertAfter("#attachment4");
            }else if(element.attr("name") == "data[Message][attachment][4]"){
            	error.insertAfter("#attachment5");
            }
            else {
                error.insertAfter(element);
            }
        },
	    submitHandler: function (form) {
	    	$('#next').attr('disabled',true);
	    	form.submit();
	    }
	});
	
	// check file size
	$.validator.addMethod("fileSize", function (val, element) {
        if(element.files[0] && element.files[0].size > 10000000) {
            return false;
        } else {
            return true;
        }
      }, "file size should be less than or equals to 10 MB");
	
	$.validator.addMethod("extension", function(value, element, param) {
		param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
		return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
	}, jQuery.format("Please enter a value with a valid extension."));
	
	/**
	 * Validator for Invite Partners Tagsinput
	 
	$("#invitePartners").validate({
		ignore: ".ignore",
        focusCleanup: false,
		rules: {
            'data[InvitePartner][user_email]':{
                required:true
            },
            'data[InvitePartner][message_body]':{
	        	required: true,
	        	maxlength: 350
            },
	    },
	    messages: {	        
	    	
	    	'data[InvitePartner][user_email]':{
                required:'Enter valid Email'
            },
            'data[InvitePartner][message_body]':{
	        	required: "This field is required",
	        	maxlength: 'Only 350 characters allowed'
            },
	    },
	    errorPlacement: function(error, element) {
			if(element.attr("name") == 'data[InvitePartner][user_email]'){
				$('#user_email_tagsinput').addClass('error');
                error.insertAfter('#user_email_tagsinput');
            }else{
                error.insertAfter(element);
            }
        },
	    submitHandler: function (form) {
	    	
            form.submit();
	    }
	});*/
	
	    
    /**
	 * Validator for Invite Partners
	 */
    
	$('#invitePartners').validate({
		rules: {
			'data[InvitePartner][email_id][0]':{
	        	required: true,
	        	email: true
            },
            'data[InvitePartner][email_id][1]':{
	        	required: true,
	        	email: true
            },
	        'data[InvitePartner][email_id][2]':{
	        	required: true,
	        	email: true
            },
            'data[InvitePartner][email_id][3]':{
	        	required: true,
	        	email: true
            },
            'data[InvitePartner][email_id][4]':{
	        	required: true,
	        	email: true
            },
            'data[InvitePartner][email_id][5]':{
	        	required: true,
	        	email: true
            },
            'data[InvitePartner][email_id][6]':{
	        	required: true,
	        	email: true
            },
            'data[InvitePartner][email_id][7]':{
	        	required: true,
	        	email: true
            },
            'data[InvitePartner][email_id][8]':{
	        	required: true,
	        	email: true
            },
            'data[InvitePartner][email_id][9]':{
	        	required: true,
	        	email: true
            },
            'data[InvitePartner][message_body]':{
	        	required: true,
	        	maxlength: 350
            },
            'data[InvitePartner][name][0]':{
            	required: true,
	            'APSH': true,
	            maxlength: 20
            },
            'data[InvitePartner][name][1]':{
            	required: true,
	            'APSH': true,
	            maxlength: 20
            },
	        'data[InvitePartner][name][2]':{
	        	required: true,
	            'APSH': true,
	            maxlength: 20
            },
            'data[InvitePartner][name][3]':{
            	required: true,
	            'APSH': true,
	            maxlength: 20
            },
            'data[InvitePartner][name][4]':{
            	required: true,
	            'APSH': true,
	            maxlength: 20
            },
            'data[InvitePartner][name][5]':{
            	required: true,
	            'APSH': true,
	            maxlength: 20
            },
            'data[InvitePartner][name][6]':{
            	required: true,
	            'APSH': true,
	            maxlength: 20
            },
            'data[InvitePartner][name][7]':{
            	required: true,
	            'APSH': true,
	            maxlength: 20
            },
            'data[InvitePartner][name][8]':{
            	required: true,
	            'APSH': true,
	            maxlength: 20
            },
            'data[InvitePartner][name][9]':{
            	required: true,
	            'APSH': true,
	            maxlength: 20
            },
	    },
	    messages: {    	
	        'data[InvitePartner][email_id][0]':{
	        	required: "This Field is Required",
	        	email: 'Enter valid email address'
            },
            'data[InvitePartner][email_id][1]':{
	        	required: "This Field is Required",
	        	email: 'Enter valid email address'
            },
            'data[InvitePartner][email_id][2]':{
	        	required: "This Field is Required",
	        	email: 'Enter valid email address'
            },
            'data[InvitePartner][email_id][3]':{
	        	required: "This Field is Required",
	        	email: 'Enter valid email address'
            },
            'data[InvitePartner][email_id][4]':{
	        	required: "This Field is Required",
	        	email: 'Enter valid email address'
            },
            'data[InvitePartner][email_id][5]':{
	        	required: "This Field is Required",
	        	email: 'Enter valid email address'
            },
            'data[InvitePartner][email_id][6]':{
	        	required: "This Field is Required",
	        	email: 'Enter valid email address'
            },
            'data[InvitePartner][email_id][7]':{
	        	required: "This Field is Required",
	        	email: 'Enter valid email address'
            },
            'data[InvitePartner][email_id][8]':{
	        	required: "This Field is Required",
	        	email: 'Enter valid email address'
            },
            'data[InvitePartner][email_id][9]':{
	        	required: "This Field is Required",
	        	email: 'Enter valid email address'
            },
            'data[InvitePartner][message_body]':{
	        	required: "This Field is Required",
	        	maxlength: 'Only 350 characters allowed'
            },
            'data[InvitePartner][name][0]':{
            	required: "This Field is Required",
	            maxlength: "Partner Name can have maximum 20 characters",
	            'APSH':"Partner Name can contain period, space and hyphen only including alphabets",
            },
            'data[InvitePartner][name][1]':{
            	required: "This Field is Required",
	            maxlength: "Partner Name can have maximum 20 characters",
	            'APSH':"Partner Name can contain period, space and hyphen only including alphabets",
            },
            'data[InvitePartner][name][2]':{
            	required: "This Field is Required",
	            maxlength: "Partner Name can have maximum 20 characters",
	            'APSH':"Partner Name can contain period, space and hyphen only including alphabets",
            },
            'data[InvitePartner][name][3]':{
            	required: "This Field is Required",
	            maxlength: "Partner Name can have maximum 20 characters",
	            'APSH':"Partner Name can contain period, space and hyphen only including alphabets",
            },
            'data[InvitePartner][name][4]':{
            	required: "This Field is Required",
	            maxlength: "Partner Name can have maximum 20 characters",
	            'APSH':"Partner Name can contain period, space and hyphen only including alphabets",
            },
            'data[InvitePartner][name][5]':{
            	required: "This Field is Required",
	            maxlength: "Partner Name can have maximum 20 characters",
	            'APSH':"Partner Name can contain period, space and hyphen only including alphabets",
            },
            'data[InvitePartner][name][6]':{
            	required: "This Field is Required",
	            maxlength: "Partner Name can have maximum 20 characters",
	            'APSH':"Partner Name can contain period, space and hyphen only including alphabets",
            },
            'data[InvitePartner][name][7]':{
            	required: "This Field is Required",
	            maxlength: "Partner Name can have maximum 20 characters",
	            'APSH':"Partner Name can contain period, space and hyphen only including alphabets",
            },
            'data[InvitePartner][name][8]':{
            	required: "This Field is Required",
	            maxlength: "Partner Name can have maximum 20 characters",
	            'APSH':"Partner Name can contain period, space and hyphen only including alphabets",
            },
            'data[InvitePartner][name][9]':{
	        	required: "This Field is Required",
				maxlength: "Partner Name can have maximum 20 characters",
	            'APSH':"Partner Name can contain period, space and hyphen only including alphabets",
            }
	    },
	    errorPlacement: function(error, element) {
	    	console.log(element.attr("name"));
            
                error.insertAfter(element);
           
        },
	    submitHandler: function (form) {
	    	$('#next').attr('disabled',true);
	    	form.submit();
	    }
	 });
	/**
     * Clearable search field
     *  * 
     */
    function tog(v){return v?'addClass':'removeClass';} 
    $(document).on('input', '.clearable', function(){
      $(this)[tog(this.value)]('x');
    }).on('mousemove', '.x', function( e ){
      $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');   
    }).on('touchstart click', '.onX', function( ev ){
      ev.preventDefault();
      $(this).removeClass('x onX').val('').change().keyup();
    });
    
    /**
	 * Validator for Credit Card Page
	 */
	$("#creditCardForm").validate({
           ignore: [],
	    rules: {	        
	        
	        'data[BusinessOwner][CC_Name]':{	        	
                required: true,
                'specialCharCheck': true,
	        },
	        'data[BusinessOwner][CC_Number]':{	        	
                required: true,
                creditcard: true,
                minlength: 13,
                maxlength: 16,
	        },
	        'data[BusinessOwner][CC_month][month]':{	        	
                required: true
	        },
	        'data[BusinessOwner][CC_year][year]':{	        	
                required: true
	        },
	        'data[BusinessOwner][CC_cvv]':{	        	
                required: true,
                'cvvCheck':true
	        }
	    },
	    messages: {
	    	
	    	'data[BusinessOwner][CC_Number]':{	        	
                required: 'This field is required.',
                creditcard: 'Please input valid credit card number.',
                minlength: 'Credit Card number must be of minimum 13 digits.',
                maxlength: 'Credit Card number cannot exceed 16 digits.',
	        },
	    },
	    submitHandler: function (form) {
			$('#next').attr('disabled',true);
	    	form.submit();
	    }
	});
	
	/** Import csv*/
    $('#importContactsForm').validate({
        errorElement: "div",
        rules: {
            'data[Contact][csv]': {
                required: true

            }
        },
        messages: {
            'data[Contact][csv]': {
                required: 'Please select a csv file',
            }
        },
        errorPlacement: function (error, element) {
            $('.uneditable-input').addClass('error1');
            $('.error').show();
            error.insertAfter('#csvDiv');
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
	
	/**
	 * Validator for Goals Page under Teams
	 */
	$("#goalsForm").validate({
           ignore: [],
	    rules: {	        
	        
	        'data[Goal][group_goals]':{	        	
                required: true,
                maxlength: 5,
                'conditionCheck_numeric': true
	        },
	        'data[Goal][group_member_goals]':{	        	
                required: true,
                'conditionCheck_numeric': true,
                maxlength: 5
	        },
	        'data[Goal][individual_goals]':{	        	
                required: true,
                'conditionCheck_numeric': true,
                maxlength: 5
	        }
	    },
	    messages: {
	    	'data[Goal][group_goals]':{	        	
                required: 'This field is Required .',
                'conditionCheck_numeric': 'Only numeric digits allowed'
	        },
	    	'data[Goal][group_member_goals]':{	        	
                required: 'This field is Required .',
                'conditionCheck_numeric': 'Only numeric digits allowed'
	        },
	    	'data[Goal][individual_goals]':{	        	
                required: 'This field is Required .',
                'conditionCheck_numeric': 'Only numeric digits allowed'
	        },
	    },
	    submitHandler: function (form) {
			$('#next').attr('disabled',true);
	    	form.submit();
	    }
	});
	
	/**
	 * Validator for Feedback form
	 */
	$("#suggestion_form").validate({
           ignore: [],
	    rules: {	        
	        
	        'data[Suggestion][message]':{	        	
                required: true,
                maxlength: 500,
	        },
	       
	    },
	    messages: {
	    	'data[Suggestion][message]':{	        	
                required: 'This field is required',
                'maxlength': 'Only 500 characters allowed'
	        }
	    },
	    submitHandler: function (form) {
			$('#next').attr('disabled',true);
	    	form.submit();
	    }
	});
});

