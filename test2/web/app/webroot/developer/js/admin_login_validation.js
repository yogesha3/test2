/**
 * this file contains the validations for admin panel
 */

$(document).ready(function (e) {
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

});
