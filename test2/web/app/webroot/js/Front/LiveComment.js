// set global interval time
var REFRESH_TIME = 5000;

// DOM ready execution
$(document).ready(function(){	
	ajacContainer.scrollTop(ajacContainer[0].scrollHeight);
	
	//get updated comment live on specific interval
	intervalTime = setInterval(function(){ refresh(entity) }, REFRESH_TIME);
});

//function to get latest comment live
function refresh(entity){
	if(entity=="message"){
		var messages_appended = ajacContainer;
		var entityId = $('#mid').val();
	}else if(entity=="referral"){
		var messages_appended = ajacContainer;
		var entityId = $('#rid').val();
	}	
  	var current_last_comment = $("#last-msg").attr("last-database-message");
  	
	$.ajax({
  		dataType:"html", 
  		success:
  			function (data) {  			
  				if(data.length>5){
		       		$("#last-msg").remove();
		       		$("#no_comments").remove();  				
		        	messages_appended.append(data);
  				}
  			}, 
  		type:"get", 
  		url: liveCommentUrl + '/' + entityId +'/'+current_last_comment
  	});
}

// function to check content length of comment
function checkContent()
{
    $('#commentbox').css('border','');
    var countkey = $('#commentbox').val().length;
    if(countkey > 350){
      $( '.error' ).remove();
      $('#commentbox').css('border','1px solid red');
      $( '<label class="error">Only 350 characters allowed</label>' ).insertAfter( "#commentbox" );
    } else {
      $( '.error' ).remove();
      $('#commentbox').css('border','');
    }
}

// function to add live comment
function addComment(){
  var comment = $('#commentbox').val();    
  var sendMailTo = $('#sendMailTo').val();
  var type = $('#type').val();
  var countkey = $('#commentbox').val().length;
  if(comment == '') {
    $('#commentbox').css('border','1px solid red');
    $( '.error' ).remove();
    $( '<label class="error">Comment field cannot be left blank.</label>' ).insertAfter( "#commentbox" );
  } else if(countkey > 350){
      $( '.error' ).remove();
      $('#commentbox').css('border','1px solid red');
      $( '<label class="error">Only 350 characters allowed</label>' ).insertAfter( "#commentbox" );
    }  else {
      $("div.blockClass").block({ message: msgLoader });
      $('#addbutton').attr('disabled','disabled');
      $('#backButton').attr('disabled','disabled');
      clearInterval(intervalTime);
      if(entity=="message"){
    	  var mid = $('#mid').val();
    	  var addCommentUrl = BASE_URL + 'messages/addComment';
    	  var dataParams = {comment:comment,mid:mid,type:type,sendMailTo:sendMailTo};
      }else if(entity=="referral"){
    	  var rid = $('#rid').val();
    	  var addCommentUrl = BASE_URL + 'referrals/addComment';
    	  var dataParams = {comment:comment,rid:rid,type:type,sendMailTo:sendMailTo};
      }
      var messages_appended = ajacContainer;
    $.ajax({      
      type: 'post',
      url: addCommentUrl,
      data:dataParams,
      success: function(data,textStatus,xhr){
        $("div.blockClass").unblock();
        $('#addbutton').removeAttr('disabled');
        $('#backButton').removeAttr('disabled');
        var content = $('.ttt').html();
          if(content == 'No Comments') {
            $('.ttt').html('');
        }
        $('#commentbox').val('');
        var jsonData = JSON.parse(data);
        lastCommentid = $("#last-msg").attr("last-database-message");
        newCommentId = jsonData.commentId;                
        if(jsonData.commentId!='NULL' && lastCommentid!=newCommentId){
        	$("#last-msg").attr("last-database-message",newCommentId);
        	messages_appended.append( jsonData.response ); 
        	intervalTime = setInterval(function(){ refresh(entity) }, REFRESH_TIME);
        }
      },
      error: function(xhr,textStatus,error){
      }
  });
  return false;
  }  
}