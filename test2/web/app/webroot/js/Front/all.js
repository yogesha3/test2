/**
 * getStateList() to fetch State list on country selection
 * @param url
 * @param countryId: country id
 * @param countryId: state list to be fetched
 */
function getStateList(countryId) {
    if (countryId!= '') {
        $.ajax({
            'type': 'post',
            'data': {'countryId': countryId},
            'url': ajaxUrl,
            success: function (msg) {
                $('#stateDiv').html(msg);
                $('#state').focus();
            }
        });
    }
    if (countryId == '') {
        $('#stateDiv').html("<select id='state' class='form-control' name='data[Contact][state_id]'><option value=''>Select State</option></select>");
    }
}

/**
 * Function to alert modal on delete message, referral or contact
 * @param string url
 * @param string id
 * @param string controller
 * @param string action
 * @param string listPage
 * @author Priti Kabra
 */
function popUp(url, id, controller, action, listPage){
    $.ajax({
        type: 'post',
        url: BASE_URL+url,
        data:{id:id, controller:controller, action:action, listPage:listPage},
        success: function(data,textStatus,xhr){
            $("#myModal").html(data);
        },
        error: function(xhr,textStatus,error){
        }
    });
    return false;
}

/**
 * function to delete the message, referral or contact
 * @param string id
 * @param string controller
 * @param string action
 * @param string listPage
 * @author Priti Kabra
 */
function actionOnPopUp(id, controller, action, listPage) {
  if (action == "removeMessage/inbox/0") {
      var msg = "Message(s) has been moved to inbox archive successfully.";
  } else if (action == "removeMessage/inbox/1") {
      var msg = "Message(s) has been permanently deleted.";
  } else if (action == "removeMessage/sent/0") {
      var msg = "Message(s) has been moved to sent archive successfully.";
  } else if (action == "removeMessage/sent/1") {
      var msg = "Message(s) has been permanently deleted.";
  }
  if(action=='changeRequest') {
	  $('button.ok_btn').addClass('disabled');
  }
  $.ajax({
        type: 'get',
        url: BASE_URL+controller+'/'+action+'/'+id,
        success: function(data,textStatus,xhr){
            if (msg) {
                showmessage(msg);
            } else {
                showmessage();
            }
            $( "#ajaxTableContent" ).load( BASE_URL+listPage );
            $('#myModal').modal('hide');
        },
        error: function(xhr,textStatus,error){
        }
    });
    return false;
}

/**
 * Function to alert modal for mass action on delete message, referral or contact
 * @param string url
 * @param string formId
 * @param string controller
 * @param string action
 * @param string listPage
 * @author Priti Kabra
 */
function massAction(url, formId, controller, action, listPage){
    $.ajax({
        type: 'post',
        url: BASE_URL+url,
        data:$('#'+formId).serialize()+ "&formId="+formId+"&controller="+controller+"&action="+action+"&listPage="+listPage,
        success: function(data,textStatus,xhr){
            $("#myModal").modal('show');
            $("#myModal").html(data);
        },
        error: function(xhr,textStatus,error){
        }
    });
    return false;
}

/**
 * function to delete the message, referral or contact and kickoff the team members
 * @param string id
 * @param string controller
 * @param string action
 * @param string listPage
 * @author Priti Kabra
 */
function actionOnMassDelete(formId, controller, action, listPage) {
  if (action == "bulkMessageAction/inbox/0") {
      var msg = "Message(s) has been moved to inbox archive successfully.";
  } else if (action == "bulkMessageAction/inbox/1") {
      var msg = "Message(s) has been permanently deleted.";
  } else if (action == "bulkMessageAction/sent/0") {
      var msg = "Message(s) has been moved to sent archive successfully.";
  } else if (action == "bulkMessageAction/sent/1") {
      var msg = "Message(s) has been permanently deleted.";
  }
  $('.popup_footer').html('Please Wait...');
  $.ajax({
        type: 'post',
        url: BASE_URL+controller+'/'+action,
        data:$('#'+formId).serialize()+ "&mass_action=massdelete",
        //data: {referralIds:rId, mass_action:'massdelete'},
        success: function(data,textStatus,xhr){
            if (msg) {
                showmessage(msg);
            } else {
                showmessage();
            }
            $( "#ajaxTableContent" ).load( BASE_URL+listPage );
            $('#myModal').modal('hide');
        },
        error: function(xhr,textStatus,error){
        }
    });
    return false;
}

/**
 * function to group select action
 * @param string id
 * @param string controller
 * @param string action
 * @param string listPage
 * @author Priti Kabra
 */
function groupAction(id, controller, action, UID, redirect) {
    $('.popup_footer').html('Please Wait...');
    $.ajax({
        type: 'get',
        url: BASE_URL+controller+'/'+action+'/'+id+'/'+UID,
        success: function(data,textStatus,xhr){
            $('#myModal').modal('hide');
            window.location.href = BASE_URL+redirect;
        },
        error: function(xhr,textStatus,error) {
			$('#myModal').modal('hide');
            window.location.href = BASE_URL+'dashboard/dashboard';
        }
    });
    return false;
}

function removeCheck(checkId) {
    $('#'+checkId).prop('checked', false);
    $('.checkthis').prop('checked', false);
    $('#mass_action').val('');
    $('#bulkaction').val('');
}
/**
 * function to show flashmessage
 * @author Rohan Julka
 */
function showmessage(){
	$( "nav.navbar-fixed-top" ).after( '<div class="container topspace col-md-12 "><div id="flashMessage" class="alert alert-success">Your request has been registered successfully</div></div>' );
	$('#searching').val('');
	$('.change_group').addClass('disabled');
	$('#mass_action').val('');
	$('html, body').animate({scrollTop: '0px'}, 300);
	setTimeout(function(){
		$("#flashMessage").html("");
		$('#flashMessage').slideUp();
	}, 5000);
}
