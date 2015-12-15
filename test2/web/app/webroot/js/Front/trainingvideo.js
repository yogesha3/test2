$(document).ready(function(){
    $('#playbutton').hide();
    $('#video1').bind('contextmenu',function() { return false; });
});
var myVar = setInterval(function(){ checkDuration() }, 5000);
function checkDuration(value)
{
    $('#playbutton').show();
    var video = document.getElementById('video1');
    if(value === undefined){
        video.pause();                    
    } else {
            $('#playbutton').hide();
            video.play();
            clearInterval(myVar);
            myVar = setInterval(function(){ checkDuration() }, 5000);
            video.onended = function(e) {
            $.ajax({
                type:'POST',
                url: 'training-video',
                success:function (data, textStatus) {
                    var jsonData = JSON.parse(data); 
                    $('#playbutton').hide();
                    clearInterval(myVar);
                    $('html, body').animate({scrollTop: '0px'}, 300);
                    $( "nav.navbar-fixed-top" ).after('<div class="container topspace col-md-12 "><div id="flashMessage" class="alert alert-success"><button data-dismiss="alert" class="close"> × </button>'+jsonData.response+'</div></div>');
                    setTimeout(function(){
                        $("#flashMessage").html("");
                        $('#flashMessage').slideUp();
                    }, 5000);
                },
            });            
        }       
    }
}
