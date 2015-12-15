/**
 * this file contains the validations for admin panel
 */

$(document).ready(function (e) {
    $("#discountedMember").keydown(function (event) {
        if (event.keyCode == 32) {
            event.preventDefault();
        }
    });
    $("#discountedMember").keyup(function (event) {
        str = $(this).val();
        str = str.replace(/-/g, '');
        $(this).val(str);
    });
    $("#membershipPrice").keydown(function (event) {
        if (event.keyCode == 32) {
            event.preventDefault();
        }
    });
    $("#membershipPrice").keyup(function (event) {
        str = $(this).val();
        str = str.replace(/-/g, '');
        $(this).val(str);
    });
    $("#discountedPrice").keydown(function (event) {
        if (event.keyCode == 32) {
            event.preventDefault();
        }
    });
    $("#discountedPrice").keyup(function (event) {
        str = $(this).val();
        str = str.replace(/-/g, '');
        $(this).val(str);
    });

    if ($('#discountedMember').length > 0) {
        $('#discountedPrice').attr('disabled', true);
        if ($('#discountedMember').val().length > 0 && $('#discountedMember').val()!=0)
            $('#discountedPrice').attr('disabled', false);
        else
            $('#discountedPrice').attr('disabled', true);
        $('#discountedMember').keyup(function () {
            if ($(this).val().length > 0 && $(this).val() < 21 && $(this).val() > 0)
            {
                $('#discountedPrice').attr('disabled', false);
            }
            else
            {
                $('#discountedPrice').val('');
                $('#discountedPrice').removeClass('error');
                $('#discountedPrice').next().hide();
                $('#discountedPrice').attr('disabled', true);
            }

        });
    }
    var isValid = false;

    /* Jquery Add Extra Method Validation Starts*/


    /* Jquery Add Extra Method Validation Ends*/

    /** admin login page js **/
    $('#password').keydown(function (event) {
        if (event.keyCode == '32') {
            event.preventDefault();
        }
    });

    /** admin login validation */
    $("#UserLoginForm").validate({
        onfocusout: false,
        rules: {
            'data[User][user_email]': {
                required: true,
                email: true,
            },
            'data[User][password]': {
                required: true,
            }
        },
        messages: {
            'data[User][user_email]': {
                required: "This field is required",
                email: "Please enter valid e-mail",
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /** admin forgot Password form validation js */
    $("#UserForgotPasswordForm").validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        rules: {
            'data[User][email]': {
                required: true,
                email: true,
            },
        },
        messages: {
            'data[User][user_email]': {
                required: "This field is required",
                email: "Please enter valid e-mail",
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });


    /** admin reset password form validation js */
    $('#UserPassword').keydown(function (event) {
        if (event.keyCode == '32') {
            event.preventDefault();
        }
    });
    $('#UserCpassword').keydown(function (event) {
        if (event.keyCode == '32') {
            event.preventDefault();
        }
    });

    $("#UserResetPasswordForm").validate({
        rules: {
            'data[User][password]': {
                required: true,
                minlength: 6,
                maxlength: 15,
            },
            'data[User][cpassword]': {
                required: true,
                equalTo: "#UserPassword"
            },
        },
        messages: {
            'data[User][password]': {
                required: "This field is required",
                minlength: "<?php echo __('Minimum 6 and Maximum 15 characters are allowed'); ?>",
                maxlength: "<?php echo __('Minimum 6 and Maximum 15 characters are allowed'); ?>",
            },
            'data[User][cpassword]': {
                required: "This field is required",
                equalTo: "Passwords entered in new password and confirm Password field does not match"
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /*plan module validation*/
    $.validator.addMethod("lessThan", function (value, max, min) {
        return parseFloat(value) < parseFloat($(min).val());
    }, "Discounted price should be less than membership fee"
            );
    $.validator.addMethod('isPositive', function (value, element) {
        if (value >>> 0 === parseFloat(value) || value == '') {
            isValid = true;
        }
        else {
            isValid = false;
        }
        return isValid; // return bool here if valid or not.
    }, 'Please enter number');

    $.validator.addMethod("decimalCheck", function (value, element) {
        return this.optional(element) || /^\d{0,5}(\.\d{0,2})?$/i.test(value);
    }, "You must include two decimal places");

    $("#editPlanForm").validate({
        rules: {
            'data[Plan][membership_price]': {
                required: true,
                number: true,
                decimalCheck: true,
            },
            'data[Plan][discounted_members]': {
                required: false,
                max: 20,
                min: 0,
                minlength: 1,
                maxlength: 2,
                isPositive: true,
            },
            'data[Plan][discounted_amount]': {
                required: true,
                number: true,
                lessThan: '#membershipPrice',
                decimalCheck: true,
            },
            'data[Plan][plan_details]': {
                maxlength: 500,
            }
        },
        messages: {
            'data[Plan][membership_price]': {
                required: "This field is required",
                number: "Please enter number",
                minlength: "<?php echo __('Minimum of 2 and maximum of 5 digits allowed for Membership Fee'); ?>",
                maxlength: "<?php echo __('Minimum of 2 and maximum of 5 digits allowed for Membership Fee'); ?>",
            },
            'data[Plan][discounted_members]': {
                minlength: "<?php echo __('Minimum 1 and Maximum 2 characters are allowed'); ?>",
                maxlength: "<?php echo __('Minimum 1 and Maximum 2 characters are allowed'); ?>",
                number: "Please enter number",
            },
            'data[Plan][discounted_amount]': {
                minlength: "<?php echo __('Minimum 1 and Maximum 5 characters are allowed'); ?>",
                maxlength: "<?php echo __('Minimum 1 and Maximum 5 characters are allowed'); ?>",
                number: "Please enter number",
            },
            'data[Plan][plan_details]': {
                //maxlength: "<?php echo __('Maximum 500 characters are allowed'); ?>",
                maxlength: "Maximum 500 characters are allowed",
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /** profession module js **/
    $.validator.addMethod("customname", function (value, element) {
        var i = /^[A-Za-z \- . ]+$/;
        return this.optional(element) || (i.test(value) > 0);
    }, "Profession name can contain period, space and hyphen only including alphabets");

    $("#addProfessionForm").validate({
        rules: {
            'data[Profession][profession_name]': {
                required: true,
                'customname': true,
                minlength: 5,
                maxlength: 30,
            },
            'data[Profession][category_id]': {
                required: true
            }

        },
        messages: {
            'data[Profession][profession_name]': {
                required: "This field is required",
                'customname': 'Profession name can contain period, space and hyphen only including alphabets',
                minlength: "Minimum of 5 and maximum of 30 characters allowed for profession name",
                maxlength: "Minimum of 5 and maximum of 30 characters allowed for profession name",
            },
            'data[Profession][category_id]': {
                required: "Please select a category"
            }

        },
        submitHandler: function (form) {
            form.submit();
        }
    });


    //edit profession validation
    $("#editProfessionForm").validate({
        rules: {
            'data[Profession][profession_name]': {
                required: true,
                'customname': true,
                minlength: 5,
                maxlength: 30,
            }

        },
        messages: {
            'data[Profession][profession_name]': {
                required: "This field is required",
                'customname': 'Profession name can contain period, space and hyphen only including alphabets',
                minlength: "Minimum of 5 and maximum of 30 characters allowed for profession name",
                maxlength: "Minimum of 5 and maximum of 30 characters allowed for profession name",
            }

        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /** profession category module js **/
    $("#addProfessionCategoryForm").validate({
        rules: {
            'data[ProfessionCategory][name]': {
                required: true,
                minlength: 3,
                maxlength: 35,
                remote: {
                    url: BASE_URL+"admin/professions/checkCategoryExist",
                    type: "post"
                }
            }

        },
        messages: {
            'data[ProfessionCategory][name]': {
                required: "This field is required",
                minlength: "Minimum of 3 and maximum of 35 characters allowed for Category name",
                maxlength: "Minimum of 3 and maximum of 35 characters allowed for Category name",
                remote: "Category name already exist"
            }

        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    //edit profession category validation
    $("#editProfessionCategoryForm").validate({
        rules: {
            'data[ProfessionCategory][name]': {
                required: true,
                minlength: 3,
                maxlength: 35,
                remote: {
                    url: BASE_URL+"admin/professions/checkCategoryExist/"+$('#categoryEditId').val(),
                    type: "post"
                }
            }
        },
        messages: {
            'data[ProfessionCategory][name]': {
                required: "This field is required",
                minlength: "Minimum of 3 and maximum of 35 characters allowed for Category name",
                maxlength: "Minimum of 3 and maximum of 35 characters allowed for Category name",
                remote: "Category name already exist"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /** pages about us form validation  */
    $.validator.addMethod("alphaNumericSpaceDash2", function (value, element) {
        var i = /^[A-Za-z0-9 ,-]+$/;
        return this.optional(element) || (i.test(value) > 0);
    }, "Only Alphanumeric Characters,Space,Comma and Hyphen are allowed");
    $("#editAboutUsForm").validate({
        ignore: [],
        debug: false,
        rules: {
            'data[Page][meta_title]': {
                required: true,
            },
            'data[Page][meta_keywords]': {
                required: true,
                alphaNumericSpaceDash2:true
            },
            'data[Page][meta_desc]': {
                required: true,
                alphaNumericSpaceDash2:true
            },
        },
        messages: {
            'data[Page][meta_title]': {
                required: "This field is required",
            },
            'data[Page][meta_keywords]': {
                required: "This field is required",
            },
            'data[Page][meta_desc]': {
                required: "This field is required",
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /** FAQ form page validations */
    $("#editFaqForm").validate({
        rules: {
            'data[Page][meta_title]': {
                required: true,
            },
            'data[Page][meta_keywords]': {
                required: true,
            },
            'data[Page][meta_desc]': {
                required: true,
            },
            'data[Page][page_content]': {
                required: true,
            }
        },
        messages: {
            'data[Page][meta_title]': {
                required: "This field is required",
            },
            'data[Page][meta_keywords]': {
                required: "This field is required",
            },
            'data[Page][meta_desc]': {
                required: "This field is required",
            },
            'data[Page][page_content]': {
                required: "This field is required",
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /** privacy policy form page validations*/
    $("#editPrivacyPolicyForm").validate({
        rules: {
            'data[Page][meta_title]': {
                required: true,
            },
            'data[Page][meta_keywords]': {
                required: true,
                alphaNumericSpaceDash2:true
            },
            'data[Page][meta_desc]': {
                required: true,
                alphaNumericSpaceDash2:true
            },
            'data[Page][page_content]': {
                required: true,
            }
        },
        messages: {
            'data[Page][meta_title]': {
                required: "This field is required",
            },
            'data[Page][meta_keywords]': {
                required: "This field is required",
            },
            'data[Page][meta_desc]': {
                required: "This field is required",
            },
            'data[Page][page_content]': {
                required: "This field is required",
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /**terms and condtionsform validations */
    $("#editTermsConditionsForm").validate({
        rules: {
            'data[Page][meta_title]': {
                required: true,
            },
            'data[Page][meta_keywords]': {
                required: true,
                alphaNumericSpaceDash2:true
            },
            'data[Page][meta_desc]': {
                required: true,
                alphaNumericSpaceDash2:true
            },
            'data[Page][page_content]': {
                required: true,
            }
        },
        messages: {
            'data[Page][meta_title]': {
                required: "This field is required",
            },
            'data[Page][meta_keywords]': {
                required: "This field is required",
            },
            'data[Page][meta_desc]': {
                required: "This field is required",
            },
            'data[Page][page_content]': {
                required: "This field is required",
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /** Import csv*/
    $('#importProfessionForm').validate({
        errorElement: "div",
        rules: {
            'data[Profession][csv]': {
                required: true

            }
        },
        messages: {
            'data[Profession][csv]': {
                required: 'Please select a csv file',
            }
        },
        errorPlacement: function (error, element) {
            $('.uneditable-input').addClass('error1');

            error.insertAfter('#csvDiv');
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /** Training Video validation*/
    $.validator.addMethod("uploadFile", function (val, element) {
        if(element.files[0].size > 10000000) {
            return false;
        } else {
            return true;
        }
      }, "Video file size should be maximum of 10 MB");
    $('#trainingvideosForm').validate({
        errorElement: "div",
        rules: {
            'data[Trainingvideo][video_name]': {
                required: true,
                uploadFile : true,
                extension: "mp4",
            }
        },
        messages: {
            'data[Trainingvideo][video_name]': {
                required: 'Please select a valid video file',
                uploadFile : 'Video file size should be maximum of 10 MB',
                extension: 'Only MP4 format is allowed',
            }
        },
        errorPlacement: function (error, element) {
            $('.uneditable-input').addClass('error1');

            error.insertAfter('#csvDiv');
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /** Add Group validation*/
    $.validator.addMethod("conditionCheck", function (value, element) {
        var i = /^[A-Za-z0-9 .-]+$/;
        return this.optional(element) || (i.test(value) > 0);
    }, "Only Alphanumeric Characters,Space,Period and Hyphen are allowed");
    $.validator.addMethod("alphaNumericSpaceDash", function (value, element) {
        var i = /^[A-Za-z0-9 -]+$/;
        return this.optional(element) || (i.test(value) > 0);
    }, "Only Alphanumeric Characters,Space,and Hyphen are allowed");
    $('#addGroup').validate({
        rules: {
            'data[Group][group_name]': {
                required: true,
                conditionCheck : true,
                minlength:4,
                remote: {
                    url: BASE_URL+"admin/groups/addGroup",
                    type: "post"
                }
            },
            'data[Group][first_meeting_date]':{
                required:true
            },
            'data[groups][country]':{
                 required: true
            },
            'data[groups][state]':{
                 required: true
            },
            'data[Group][country_id]':{
                 required: true
            },
            'data[Group][state_id]':{
                 required: true
            },
            'data[Group][city_id]':{
                 required: true
            },
            'data[Group][zipcode]':{
                 required: true,
                 minlength:3,
                 alphaNumericSpaceDash:true
            },
            'data[Group][timezone_id]':{
                required: true,
            }
        },
        messages: {
            'data[Group][group_name]': {
                required: 'This field is required',
                minlength:'Group name should be minimum 4 and maximum 25 characters long',
                remote : 'Group name already exist',
            },
            'data[Group][first_meeting_date]':{
                required:'This field is required',
            },
            'data[Group][country_id]':{
                 required:'This field is required',
            },
            'data[Group][state_id]':{
                 required:'This field is required',
            },
            'data[Group][city_id]':{
                 required:'This field is required',
            },
            'data[Group][zipcode]':{
                 required:'This field is required',
                 minlength:'Zip Code should be minimum 3 and maximum 12 characters long'
            },
            'data[Group][timezone_id]':{
                required:'This field is required',
            }
        },
        submitHandler: function (form) {
            var l = Ladda.create( document.querySelector( 'button[type=submit]' ) );
            l.start();
            form.submit();
        }
    });

    /*validation for coupns*/
    /** Add Group validation*/
    $.validator.addMethod("conditionCheck_cpn", function (value, element) {
        var i = /^[A-Za-z0-9 .-]+$/;
        return this.optional(element) || (i.test(value) > 0);
    }, "Special characters not allowed");
    $.validator.addMethod("conditionCheck_discount", function (value, element) {
    	
        return this.optional(element) || (value >0 && value<=99);
    }, "Discount% must be between 1 and 99");
    
 $.validator.addMethod("conditionCheck_numeric", function (value, element) {
    	var expr=/^[0-9]+$/;
        return (expr.test(value) > 0);
    }, "Discount% must be Numeric");
 
    $('#addCpnFrm').validate({
        ignore: ".ignore",
        focusCleanup: false,
        rules: {
           'data[Coupon][coupon_code]':{
                required:true,
                conditionCheck_cpn:true,
                remote: {
                    url: BASE_URL+"admin/coupons/add",
                    type: "post"
                }
            },
            'data[Coupon][discount_amount]':{
                 required: true,
                 conditionCheck_numeric:true,
                 conditionCheck_discount:true,
                 
            },
            'data[Coupon][start_date]':{
                 required: true
                 
            },
            'data[Coupon][expiry_date]':{
                 required: true
            },
            'data[Coupon][user_email]':{
                required:true
            },
            
        },
        errorPlacement: function (error, element) {
            if(element.attr("name") == 'data[Coupon][user_email]'){
            	
                $('#user_email_tagsinput').addClass('error');
                error.insertAfter('#user_email_tagsinput');
            }else{
                error.insertAfter(element);
            }            
        },
        messages: {
            'data[Coupon][coupon_code]': {
                remote : 'Coupon name already exist',
            },
            'data[Coupon][user_email]':{
                required:'Enter valid Email'
            },
            
        },
        submitHandler: function (form) {
            var l = Ladda.create( document.querySelector( 'button[type=submit]' ) );
            l.start();
            form.submit();
        }
    });

    /** edit group validation js */
    $('#editGroup').validate({
        rules: {
            'data[Group][group_name]': {
                required: true,
                conditionCheck : true,
                minlength:4,
                remote: {
                    url: BASE_URL+"admin/groups/edit/"+$('#groupId').val(),
                    type: "post"
                }
            },
        },
        messages: {
            'data[Group][group_name]': {
                required: 'This field is required',
                minlength:'Group name should be minimum 4 and maximum 25 characters long',
                remote : 'Group name already exist',
            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /** Add Category for FAQ module js **/
    $.validator.addMethod("FaqCatCheck", function (value, element) {
        var i = /^[A-Za-z \/ -]+$/;
        return this.optional(element) || (i.test(value) > 0);
    }, "Special characters not allowed");

    $("#addFaqCategoryForm").validate({
        rules: {
            'data[Faqcategorie][category_name]': {
                required: true,
                FaqCatCheck: false,
                maxlength: 35,
            }

        },
        messages: {
            'data[Faqcategorie][category_name]': {
                required: "This field is required",
                FaqCatCheck: 'Category name can contain Alphabets, space and hyphen and slash',
                maxlength: 'Maximum of 35 characters allowed for category name',
            }

        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /** Edit Category for FAQ module js **/
    $("#editFaqCategoryForm").validate({        
        rules: {
            'data[Faqcategorie][category_name]': {
                required: true,
                FaqCatCheck: false,
                maxlength: 35,
            }

        },
        messages: {
            'data[Faqcategorie][category_name]': {
                required: "This field is required",
                FaqCatCheck: 'Category name can contain Alphabets, space and hyphen and slash',
                maxlength: 'Maximum of 35 characters allowed for category name',
            }

        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /** Add FAQ for FAQ module js **/
    $("#addQuestionForm").validate({
        /*errorPlacement: function (error, element) { // render error placement for each input type
                if (element.hasClass("ckeditor")) {
                    error.appendTo($(element).closest('.col-sm-7'));
                } else {
                    error.insertAfter(element);
                }
            },*/
        ignore: "",
        rules: {
            'data[Faq][category_id]': {
                required: true,
            },
            'data[Faq][question]': {
                required: true,
                /*remote: {
                    url: BASE_URL+"admin/faqs/add/",
                    data: {"data[Faq][category_id]":function(){return $('#category').val()}},
                    type: "post",
                    async:false
                }*/
            },
            /*'data[Faq][answers]' :{
                required : true,
                checkData: true
            }*/
        },
        messages: {
            'data[Faq][category_id]': {
                required: "This field is required",                
            },
            'data[Faq][question]': {
                required: "This field is required",
            },

        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    
    /** Edit FAQ for FAQ module js **/
    $("#editQuestionForm").validate({        
        ignore: "",
        rules: {
            'data[Faq][category_id]': {
                required: true,
            },
            'data[Faq][question]': {
                required: true,                
            }
        },
        messages: {
            'data[Faq][category_id]': {
                required: "This field is required",                
            },
            'data[Faq][question]': {
                required: "This field is required",
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    
    /** webcasts validations*/
      
    /** youtube link validation method*/
    $.validator.addMethod("youtubeLink", function (value, element) {
        var p = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;
        return this.optional(element) || (p.test(value) > 0);
    }, "Please enter a valid YouTube link");
        
    $('#addWebcastsForm').validate({
        rules: {
            'data[Webcast][title]': {
                required:true,
                minlength: 5,
                maxlength: 70,
                conditionCheck : true,
            },
            'data[Webcast][link]': {
                required:true,
                youtubeLink: true
            },                
            'data[Webcast][description]': {
                maxlength: 250,
            },
        },
        messages: {
           'data[Webcast][title]': {
                required: 'This field is required',
                maxlength: 'Webcast title should be minimum 5 and maximum 70 characters long',
                minlength: 'Webcast title should be minimum 5 and maximum 70 characters long',
            },
            'data[Webcast][link]': {
                required: 'This field is required',
            },
            'data[Webcast][description]': {
                maxlength: 'Maximum 250 characters are allowed for webcast description',
            }
        },
        submitHandler: function (form) {
        	var l = Ladda.create( document.querySelector( 'button[type=submit]' ) );
            l.start();
            form.submit();
        }
    });

    $('#editWebcastsForm').validate({
        rules: {
            'data[Webcast][title]': {
                required:true,
                minlength: 5,
                maxlength: 70,
                conditionCheck : true,
            },
            'data[Webcast][description]': {
                 maxlength: 250,
            },
        },
        messages: {
            'data[Webcast][title]': {
                required: 'This field is required',
                maxlength: 'Webcast title should be minimum 5 and maximum 70 characters long',
                minlength: 'Webcast title should be minimum 5 and maximum 70 characters long',
            },
            'data[Webcast][description]': {
                maxlength: 'Maximum 250 characters are allowed for webcast description',
            }
        },
        submitHandler: function (form) {
        	var l = Ladda.create( document.querySelector( 'button[type=submit]' ) );
            l.start();
            form.submit();
        }
    });
    
    // Newsletter create/edit template form valiation
    $('#addNewsletterForm,#editNewsletterForm').validate({     	
    	ignore: [],       
        rules: {
        	'data[Newsletter][template_name]': {
                required:true,
                minlength: 5,
                maxlength: 25,
                conditionCheck : true,
            },
            'data[Newsletter][subject]': {
            	required:true,
                minlength: 5,
                maxlength: 60,
                conditionCheck : true,
            },
            'data[Newsletter][content]': {
            	required: function(element) { 
            		CKEDITOR.instances['template_content'].updateElement();
            		if(element.value==""){
            			$('#cke_template_content').css('border', '1px solid red');
            		}else{
            			$('#cke_template_content').css('border', '');
            		}
                }
            }
        },
        messages: {
        	'data[Newsletter][template_name]': {
                required: 'This field is required',
                maxlength: 'Template name should be minimum 5 and maximum 25 characters long',
                minlength: 'Template name should be minimum 5 and maximum 25 characters long',
            },
            'data[Newsletter][subject]': {
                required: 'This field is required',
                maxlength: 'Subject should be minimum 5 and maximum 60 characters long',
                minlength: 'Subject should be minimum 5 and maximum 60 characters long',
            },
            template_content: "This field is required"
        },
        errorPlacement: function(error, element) 
        {
        	if (element.is('textarea')) {
        		element.next().css('border', '1px solid red');
            	error.insertAfter("#cke_template_content");
            } else {            	
            	error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

	// Send newsletter to subscriber form validation
    $('#sendNewsletterForm').validate({
        rules: {            
            'data[Newsletter][template_id]': {
            	required:true,
           }
        },
        messages: {        	
            'data[Newsletter][template_id]': {
                required: 'Please select a template'
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    
    /** Add advertisement validations */
    
    $.validator.addMethod("uploadImage", function (val, element) {
        if(element.files[0] && element.files[0].size > 2000000) {
            return false;
        } else {
            return true;
        }
      }, "Image size should be less than or equals to 2 MB");
    
    $.validator.addMethod("targetUrl", function (value, element) {
        var i = /(https?:\/\/(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})/;
        return (i.test(value) > 0);
    }, "Please enter valid URL (Like- http://www.example.com)");
    
    $('#addAdvertisementsForm').validate({
        rules: {
            'data[Advertisement][profession_id]':{
                required:true,
            },
            'data[Advertisement][position]':{
                required:true,
            },
            'data[Advertisement][title]':{
                required:true,
                conditionCheck:true,
                minlength: 5,
                maxlength: 25,
            },
            'data[Advertisement][target_url]':{
                required:true,
                targetUrl:true,
            },
           'data[Advertisement][ad_image]':{
                required:true,
                extension: "gif|jpeg|jpg|png",
                uploadImage:true,
            }, 

        },
        messages: {
            'data[Advertisement][profession_id]':{
                required: "This field is required",
            },
            'data[Advertisement][position]':{
                required: "This field is required",
            },
            'data[Advertisement][title]':{
                required: "This field is required",
                maxlength: 'Advertisement title should be minimum 5 and maximum 25 characters long',
                minlength: 'Advertisement title should be minimum 5 and maximum 25 characters long',
            },
            'data[Advertisement][target_url]':{
                required: "This field is required",
            },
             'data[Advertisement][ad_image]':{
                required: "Please upload an ad image",
                extension: 'Only jpeg, jpg, gif and png image format are allowed',
            },

        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "data[Advertisement][ad_image]" ) {
                error.insertAfter("#uploadButton");
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
         form.submit();
     }
    });

    $('#editAdvertisementsForm').validate({
        rules: {
            'data[Advertisement][profession_id]':{
                required:true,
            },
            'data[Advertisement][position]':{
                required:true,
            },
            'data[Advertisement][title]':{
                required:true,
                conditionCheck:true,
                minlength: 5,
                maxlength: 25,
            },
            'data[Advertisement][target_url]':{
            	required:true,
                targetUrl:true,
            },
           'data[Advertisement][upload]':{
                required: function(element) {                	
                    if($("#adImage").val()!=''){
                    	return false;
                    }else{
                    	return true;
                    }
                },
        	    extension: "gif|jpeg|jpg|png",
                uploadImage: true,
            },
        },
        messages: {
            'data[Advertisement][profession_id]':{
                required: "This field is required",
            },
            'data[Advertisement][position]':{
                required: "This field is required",
            },
            'data[Advertisement][title]':{
                required: "This field is required",
                maxlength: 'Advertisement title should be minimum 5 and maximum 25 characters long',
                minlength: 'Advertisement title should be minimum 5 and maximum 25 characters long',
            },
            'data[Advertisement][target_url]':{
                required: "This field is required",
            },
             'data[Advertisement][upload]':{
                required: "Please upload an ad image",
                extension: 'Only jpeg, jpg, gif and png image format are allowed',
            },
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "data[Advertisement][upload]") {
                error.insertAfter("#uploadButton");
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
         form.submit();
     }
    });
    
    $('#updateShuffleCriteria').validate({
        rules: {
            'data[Setting][shuffling_criteria_1]':{
                required:true,
                conditionCheck_numeric:true,
                conditionCheck_discount:true,
            },
            'data[Setting][shuffling_criteria_2]':{
                required:true,
                conditionCheck_numeric:true,
                conditionCheck_discount:true,
            },            
        },
        messages: {
        	'data[Setting][shuffling_criteria_1]':{
                required:'This field is required',
                conditionCheck_numeric: 'Only numeric values are allowed',
                conditionCheck_discount: 'Criteria % must be between 1 and 99',
            },
            'data[Setting][shuffling_criteria_2]':{
            	required:'This field is required',
            	conditionCheck_numeric: 'Only numeric values are allowed',
            	conditionCheck_discount: 'Criteria % must be between 1 and 99',
            },
        },        
        submitHandler: function (form) {
         form.submit();
     }
    });
    
    /**
	 * Validator for Membership Levels Page in admin
	 */
	$("#membershipLevels").validate({
	    rules: {	        
	        
	        'data[Membership][Bronze][lower_limit]':{	        	
                required: true,
                number: true
	        },
	        'data[Membership][Bronze][upper_limit]':{	        	
                required: true,
                number: true
	        },
	        'data[Membership][Silver][lower_limit]':{	        	
                required: true,
                number: true
	        },
	        'data[Membership][Silver][upper_limit]':{	        	
                required: true,
                number: true
	        },
	        'data[Membership][Gold][lower_limit]':{	        	
                required: true,
                number: true
	        },
	        'data[Membership][Gold][upper_limit]':{	        	
                required: true,
                number: true
	        },
	        'data[Membership][Platinum][lower_limit]':{	        	
                required: true,
                number: true
	        },
	        'data[Membership][Platinum][upper_limit]':{	        	
                required: true,
                number: true
	        },
	    },
	    messages: {
	    	'data[Membership][Bronze][lower_level]':{	        	
                required: "This field is required",
                number: "Only numbers are allowed"
	        },
	    	
	    },
	    submitHandler: function (form) {
			$('#next').attr('disabled',true);
	    	form.submit();
	    }
	});
	
    // add affiliate validation
    $('#addAffiliateFrm').validate({
        rules: {
            'data[Affiliate][name]':{
            	required: true,
                maxlength: 20,
                conditionCheck: true
            },
            'data[Affiliate][email]':{
                required:true,
                email:true
            },
            'data[Affiliate][link]':{
                required:true
            },            
        },
        messages: {
        	'data[Affiliate][name]':{
            	required: 'This field is required',
                maxlength: 'Affiliate name can have maximum 20 characters'
            },
            'data[Affiliate][email]':{
            	required: 'This field is required',
            	email: 'Please enter valid e-mail address'
            },
            'data[Affiliate][link]':{
            	required: 'This field is required'
            }, 
        },        
        submitHandler: function (form) {
         form.submit();
     }
    });
    
 // add change password validation for admin
    $('#userChangePasswordForm').validate({
        rules: {
        	'data[User][current_password]': {
                required: true,
                minlength: 6,
                maxlength: 20,
            },
            'data[User][new_password]': {
            	required: true,
                nowhitespace: true,
                minlength: 6,
                maxlength: 20,
            },
            'data[User][cpassword]': {
                required: true,
                equalTo: "#newPassword"
            },         
        },
        messages: {
        	'data[User][current_password]':{
            	required: 'This field is required',
            	minlength: "Password should be minimum 6 characters and maximum 20 characters.",
	        	maxlength: "Password should be minimum 6 characters and maximum 20 characters.",
            },
            'data[User][new_password]':{
            	required: 'This field is required',
            	nowhitespace: "Space is not allowed in password.",
            	minlength: "Password should be minimum 6 characters and maximum 20 characters.",
	        	maxlength: "Password should be minimum 6 characters and maximum 20 characters.",
            },
            'data[User][cpassword]':{
            	required: "This field is required",
                equalTo: "Password does not match"
            }, 
        },        
        submitHandler: function (form) {
     		form.submit();
     	}
    });

	// add meeting slots
    $('#addHostedForm').validate({
        rules: {
        	'data[adobeConnect][hostedCount]': {
                required: true,
                number : true,
            }                    
        },
        messages: {
        	'data[adobeConnect][hostedCount]':{
            	required: 'This field is required',
            	number: 'Only numeric characters are allowed',
            }
        },        
        submitHandler: function (form) {
     		var l = Ladda.create( document.querySelector( 'button[type=submit]' ) );
            l.start();
            form.submit();
     	}
    });

    $(".search-select").select2({
            placeholder: "Select a State",
            allowClear: true
        });
    
});
/**
     * ajaxChange() to fetch State /City list on country selection
     * @param url
     * @param countryId: country id
     * @added by Laxmi Saini
     */
    function ajaxChange(url,countryId){
        url=$('#ajaxUrl').val();
        var divUpdate = 'stateDiv';
         if(countryId != ''){
            $.ajax({
             'type':'post',
             'data':{'countryId':countryId},
             'url':url,
             success:function(msg){                 
                 $('#'+divUpdate).html(msg);
             }
         });
        }
        if(countryId == ''){
            $('#stateDiv').html("<select id='state' class='form-control' name='data[groups][state_id]'><option value=''>Select State</option></select>");
        }        
    }
