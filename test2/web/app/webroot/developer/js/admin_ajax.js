$(document).ready(function(){    
      $('body').on('click','.popup_btn',function(){
        $(this).css('pointer-events','none');
            });
    $('.delete').hover(function(){
        $('.delete').tooltip('enable');
    });
   
    $('.activeInactive').hover(function(){
        $('.activeInactive').tooltip('enable');
    });
    
    $("body").on("mouseover", "a.tooltips", function(){
    	
    	$('a.tooltips').tooltip('enable');
    });
    $("body").on("mouseover", ".activeInactive", function(){
    	$('a.activeInactive').tooltip('enable');
    });
});
function popUp(url,id){
	
    $.ajax({      
        type: 'post',
        url: BASE_URL+url,
        data:{id:id},
        success: function(data,textStatus,xhr){
            $("#popup").html(data);
        },
        error: function(xhr,textStatus,error){
        }
    });
    return false;
}