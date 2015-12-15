$(function(){
    getWebcastComments();
    var $container = $('.left_video');
    $container.infinitescroll({  
        navSelector  : '.show-mpre-btn',    // selector for the paged navigation 
        nextSelector : '.show-mpre-btn',  // selector for the NEXT link (to page 2)
        itemSelector : '.left_video_wrapper',
        loading: {
            finishedMsg: 'No more videos to load',
            msgText: "Loading webcasts...",
        },
        errorCallback: function (){ 
            //$('.show-mpre-btn').delay(2000).fadeOut(); 
        	$('.show-mpre-btn').remove();
        }
    });

    var $container = $('.ajaxUpdate2');
    $container.infinitescroll({  
        navSelector  : '.viewmorecomment',    // selector for the paged navigation 
        nextSelector : '.viewmorecomment',  // selector for the NEXT link (to page 2)
        itemSelector : '.media',
        path : [BASE_URL+"events/webcastGetComments/id:"+$('#webcastid').val()+"/page:", "/"],
        dataType: 'json',
        appendCallback: false,
        loading: {
            finishedMsg: 'No more comments to load',
            msgText: "Loading comments...",
        },
        errorCallback: function (){ 
            //$('.viewmorecomment').delay(2000).fadeOut(); 
        	$('.viewmorecomment').remove();
        },
    }, function(json, opts) {
      $('.ajaxUpdate2').append(json.response);  
    });

    $(window).unbind('.infscr');
    $('.show-mpre-btn').click(function(){
        $('.left_video').infinitescroll('retrieve');
        $('.show-mpre-btn').show();
        return false;
    });
    $('.viewmorecomment').click(function(){
        $('.ajaxUpdate2').infinitescroll('retrieve');
        $('.viewmorecomment').show();
        return false;
    });

    $("#addCommentsWebcast").click(function () {
        var comment = $('#commentbox').val();
        var webcastid = $('#webcastid').val();
        var countkey = $('#commentbox').val().length;
        if(comment == '') {
            $('#commentbox').css('border','1px solid #c83a2a');
            $( '.error' ).remove();
            $( '<label class="error">Comment field cannot be left blank.</label>' ).insertAfter( "#commentbox" );
        } else if(countkey > 300){
            $( '.error' ).remove();
            $('#commentbox').css('border','1px solid #c83a2a');
            $( '<label class="error">Only 300 characters allowed</label>' ).insertAfter( "#commentbox" );
        } else {
            $('div.blockClass').block({message:'<div class="blockClass_comment2"><div id="rays"><img width="35" height="35" src="'+BASE_URL+'img/loding-logo.png" /></div></div>'});
            $('#addCommentsWebcast').attr('disabled','disabled');
            $.ajax({
                async:true,  
                data:{comment:comment,webcastid:webcastid}, 
                success:function (data, textStatus) {
                    if($('#ncp').length > 0) {
                        $('#ncp').remove();
						$('.attachments_head').html('Comments');
                    }
                    $("div.blockClass").unblock();
                    $('#addCommentsWebcast').removeAttr('disabled');
                    $('#commentbox').val('');
                    var jsonData = JSON.parse(data); 
                    if(jsonData.responsecode == '200') {
                        $( ".ajaxUpdate2" ).prepend('<div class="clearfix"></div>'+ jsonData.response );
                    } else {
                        $( '.error' ).remove();
                        $( '<label class="error">This Webcast not exist</label>' ).insertAfter( "#commentbox" );
                    }
                }, 
                type:"POST", 
                url:BASE_URL+"events\/webcastAddComment"
            });
            return false;
        }    
    });
});
function checkContent()
{
    $('#commentbox').css('border','');
    var countkey = $('#commentbox').val().length;
    if(countkey > 300){
        $( '.error' ).remove();
        $('#commentbox').css('border','1px solid red');
        $( '<label class="error">Only 300 characters allowed</label>' ).insertAfter( "#commentbox" );
    } else {
        $( '.error' ).remove();
        $('#commentbox').css('border','');
    }
}
function getWebcastComments()
{
    if($('#webcastid').length > 0) {
        var webcastid = $('#webcastid').val();
        $.ajax({
            async:true,  
            data:{webcastid:webcastid}, 
            success:function (data, textStatus) {
                var jsonData = JSON.parse(data); 
                $( ".ajaxUpdate2" ).html('<div class="clearfix"></div>'+ jsonData.response );            
            }, 
            type:"POST", 
            url:BASE_URL+"events\/webcastGetComments"
        });
        return false;
    }    
}
