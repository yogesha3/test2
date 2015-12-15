

    /* <!-- =============================================== --> */
    /* <!-- ============ Herader Animation =========== --> */
    /* <!-- =============================================== -->  */
    $(window).scroll(function() {
        if ($(this).scrollTop() > 15) {
            $('nav').addClass("navbar-alt")
        } else {
            $('nav').removeClass("navbar-alt")
        }
    });
    if ($(window).width() < 768) {
        $('#nav').removeClass('navbar-default').addClass('navbar-inverse');
        $('.floated .with-border').removeClass('with-border');
    }
 
 
 
 
 
 /* <!-- =============================================== --> */
  /* <!-- ============ lode to top always =========== --> */
  /* <!-- =============================================== -->  */
 
  $(window).on('beforeunload', function() {
	 $(window).scrollTop(0); 
	});
	
	
	/* <!-- =============================================== --> */
  /* <!-- ============ vido controls =========== --> */
  /* <!-- =============================================== -->  */
	
var vid = document.getElementById("bgvid");
var pauseButton = document.getElementById("vidpause");
function vidFade() {
vid.classList.add("stopfade");
}
vid.addEventListener('ended', function() {
// only functional if "loop" is removed
// to capture IE10
vidFade();
});