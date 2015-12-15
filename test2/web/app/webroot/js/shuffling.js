$(document).ready(function(){
	$('.cursor').click(function(){
		groupId = $(this).attr('group-val');
		$.ajax({
			url: showGroupMemberList,
	        context: document.body,
	        method: "POST",
	        data: { groupId: groupId},
	        success: function(data){
	            $("#popup").html(data);
	        },
	    });
	});
	
	$('.letgroupshuffle').click(function(){
		groupType = $(this).attr('id');
		shuffledate = $('#'+groupType+'-shuffle-params').attr('shuffle-date');
		shuffletime = $('#'+groupType+'-shuffle-params').attr('shuffle-time');		
		$(".loader").show();
		
		$.ajax({
			url: groupShuffling,
	        context: document.body,
	        method: "POST",
	        data: { shuffledate: shuffledate, shuffletime: shuffletime, shuffleGroupType : groupType},
	        success: function(data){
	            window.location.href =  shuffleRedirectUrl;
	        }
	    });
	});
	
});