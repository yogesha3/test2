// JavaScript Document
$(document).ready(function () {
    //toggle the component with class accordion_body
    $(".accordion_head").click(function () {
		$(".H-Text-Login").css("color","#000");
		 $(".accordion_head").css({"background-color":"#fff","color":"#525252","border":"1px solid #e5e5e5"});
		 $(".plusminus").css("color","#1ab8ee");
		 
		 
        if ($('.accordion_body').is(':visible')) {
            $(".accordion_body").slideUp(300);
            $(".plusminus").text('+');
			$(this).css("background-color","#fff");
        }
        if ($(this).next(".accordion_body").is(':visible')) {
            $(this).next(".accordion_body").slideUp(300);
            $(this).children(".plusminus").text('+');
			$(this).find(".accordion_head").css("background-color","#fff");
			
        } else {
            $(this).next(".accordion_body").slideDown(300);
            $(this).children(".plusminus").text('-').css("color","#fff");
			$(this).css({"background-color":"#1ab8ee","color":"#fff","border":"none"});
			$(".open-position").css("color","#000");
        }
    });
});