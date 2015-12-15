$(function(){
    getreviewslisting();


    var $container = $('.ajaxUpdate2');
    $container.infinitescroll({  
        navSelector  : '.viewmorecomment',    // selector for the paged navigation 
        nextSelector : '.viewmorecomment',  // selector for the NEXT link (to page 2)
        itemSelector : '.media',
        path : [BASE_URL+"reviews/reviewsListing/page:", "/"],
        dataType: 'json',
        appendCallback: false,
        loading: {
            finishedMsg: 'No more reviews to load',
            msgText: "Loading reviews...",
        },
        errorCallback: function (){ 
            //$('.viewmorecomment').delay(2000).fadeOut(); 
            $('.reviews_description').last().css('border-bottom','none');
        	$('.view_comment').remove();
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
});
function getreviewslisting()
{
    $.ajax({
        async:true,   
        success:function (data, textStatus) {
            var jsonData = JSON.parse(data); 
            $( ".ajaxUpdate2" ).html('<div class="clearfix"></div>'+ jsonData.response );
            //$('.reviews_description').last().css('border-bottom','none');            
        }, 
        type:"POST", 
        url:BASE_URL+"reviews\/reviewsListing"
    });
    return false; 
}
