$(document).ready(function () {
	/**
	 * Validator for send referrals page 
	 */
	$("#EditProfileForm").validate({
	    rules: {
	        'data[BusinessOwner][fname]': {
	            required: true,
	            'APSH': true,
	            maxlength: 20
	        },
	        'data[BusinessOwner][lname]': {
	            required: true,
	            'APSH': true,
	            maxlength: 20
	        },
	        'data[BusinessOwner][address]': {
	        	'ANPCSH':true,
		        maxlength: 60
	        },
	        'data[BusinessOwner][office_phone]': {
	        	required: true,
	        	isNumeric:true,
              	maxlength: 15, 
	        },
	        'data[BusinessOwner][mobile]': {
	        	isNumeric:true,
            	maxlength: 15, 
	        },
	        'data[BusinessOwner][city]':{
            	'APSH':true,
            	maxlength: 35
	        },
	        'data[BusinessOwner][website]': {
	        	'VURL': true,
	        },
	        'data[BusinessOwner][website1]': {
	        	'VURL': true,
	        },
	        'data[BusinessOwner][twitter_profile_id]': {
	        	'VURL': true,
	        },
	        'data[BusinessOwner][facebook_profile_id]': {
	        	'VURL': true,
	        },
	        'data[BusinessOwner][linkedin_profile_id]': {
	        	'VURL': true,
	        },
	        'data[BusinessOwner][aboutme]':{
            	maxlength: 500
	        },
	        'data[BusinessOwner][services]':{
            	maxlength: 500
	        },
	        'data[BusinessOwner][business_description]':{
            	maxlength: 500
	        },
	        'data[BusinessOwner][profile_image]':{
	        	extension: "jpeg|jpg|png",
	        	fileSize:true
	        },
	        
	    },
	    messages: {
	        'data[BusinessOwner][fname]': {
	            required: "This field is required",
	            maxlength: "First name can have maximum 20 characters",
	            'APSH': "First name can contain period, space and hyphen only including alphabets.",
	        },
	        'data[BusinessOwner][lname]': {
	            required: "This field is required",
	            maxlength: "Last name can have maximum 20 characters",
	            'APSH': "Last name can contain period, space and hyphen only including alphabets.",
	        },
	        'data[BusinessOwner][address]': {
	        	maxlength: "Address can have maximum 60 characters",
	            'ANPCSH' : "Only alphanumeric characters, space, period, comma, parenthesis and hyphen are allowed",
	        },
	        'data[BusinessOwner][office_phone]': {
            	required: "This field is required",
            	isNumeric:"Only numeric characters are allowed",
            	maxlength: "Office phone can have maximum 15 characters", 
	        },
	        'data[BusinessOwner][mobile]': {
	        	isNumeric:"Only numeric characters are allowed",
            	maxlength: "Mobile phone can have maximum 15 characters", 
	        },
	        'data[BusinessOwner][city]':{
  	        	maxlength: "City can have maximum 35 characters.",
  	        	specialCharCheck: "Only alphabetic characters are allowed."
	        },
	        'data[BusinessOwner][website]' : {
	        	'VURL' : 'Please enter valid URL'
	        },
	        'data[BusinessOwner][website1]' : {
	        	'VURL' : 'Please enter valid URL'
	        },
	        'data[BusinessOwner][twitter_profile_id]' : {
	        	'VURL' : 'Please enter valid URL'
	        },
	        'data[BusinessOwner][facebook_profile_id]' : {
	        	'VURL' : 'Please enter valid URL'
	        },
	        'data[BusinessOwner][linkedin_profile_id]' : {
	        	'VURL' : 'Please enter valid URL'
	        },
	        'data[BusinessOwner][aboutme]':{
            	maxlength: "Only 500 characters allowed."
	        },
	        'data[BusinessOwner][services]':{
            	maxlength: "Only 500 characters allowed."
	        },
	        'data[BusinessOwner][business_description]':{
            	maxlength: "Only 500 characters allowed."
	        },
	        'data[BusinessOwner][profile_image]':{
	        	extension: "Only JPG/JPEG and PNG files allowed",
	        	fileSize: "Image size should be less than 2 MB"
	        },
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
	
	// special character check with comma
	$.validator.addMethod("ANPCSH", function (value, element) {
	    var i = /^[A-Za-z0-9 \- . , ( ) ]+$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Only alphanumeric characters, space, period and hyphen are allowed.");
	
	//to check valid url without http and www
	$.validator.addMethod("VURL", function (value, element) {
	    var i = /(^|\s)((https?:\/\/)?[\w-]+(\.[\w-]+)+\.?(:\d+)?(\/\S*)?)/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "valid VURL");
	
	// check file size
	$.validator.addMethod("fileSize", function (val, element) {
        if(element.files[0] && element.files[0].size > 2000000) {
            return false;
        } else {
            return true;
        }
    }, "file size should be less than or equals to 2 MB");
	
	//to check only numeric value	
	$.validator.addMethod("isNumeric", function (value, element) {
	    var i = /^[0-9]+$/;
	    return this.optional(element) || (i.test(value) > 0);
	}, "Invalid Numeric value");
	
});

// Image uploader JS
$("#edit-image").click(function () {
    document.getElementById('profile-img').click();
})
$('input[type=file]').change(function (e) {
  $('#profile-image').html($(this).val());
  $("#edited-image").attr('src', $(this).val())
});
$("#imgContainer").hover(function(){
    $("#edit-image").css('display','block')
    $("#newProfile").css('opacity','0.2')
},function(){
    if($("#newProfile").data('flag')== '0'){
       $("#newProfile").css('opacity','1')
    }else {
    $("#edit-image").css('display','none');
    $("#newProfile").css('opacity','1')
    }
    
});
function readURL(input) {
  if (input.files && input.files[0]) {
	  var size = input.files[0].size;
	  if(size < 2000000){
	      var reader = new FileReader();
	      reader.onload = function (e) {
	            var dataURL = e.target.result;
	            var mimeType = dataURL.split(",")[0].split(":")[1].split(";")[0];
	            var type = input.files[0].name.substr((~-input.files[0].name.lastIndexOf(".") >>> 0) + 2)
	            if(mimeType == "image/jpeg" || mimeType == "image/jpg" || mimeType == "image/png"){
	                $('#newProfile').attr('src', dataURL);
	                $("label[for='profile_image']").remove();	                
	            }else {
	                changeType(type)
	            }
	            $('#edit-image').css('display', 'none');
	            $("#newProfile").data('flag',1);
	      };
	      reader.readAsDataURL(input.files[0]);
	      $('#updatebutton').attr('disabled', false);
	  }else{
		  FileTooLarge();
	  }
  }
}
function changeType(type){
	$('#newProfile').attr('src', imgPath);
	$("label[for='profile_image']").remove();
	$('#updatebutton').attr('disabled', 'disabled');
	$( '<label class="error" for="profile_image">Only JPG/JPEG and PNG files allowed</label>' ).insertAfter( "#profile-img" );
}
function FileTooLarge(){
	$('#newProfile').attr('src', imgPath);
	$("label[for='profile_image']").remove();
	$('#updatebutton').attr('disabled', 'disabled');
	$( '<label class="error" for="profile_image">File size should be less than 2 MB</label>' ).insertAfter( "#profile-img" );
}
