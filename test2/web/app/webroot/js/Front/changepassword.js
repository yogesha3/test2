$(document).ready(function () { 
	$('#resetbutton').on('click', function () {
		var validator = $( "#ChangePassword" ).validate();
		validator.resetForm();
	});
});
