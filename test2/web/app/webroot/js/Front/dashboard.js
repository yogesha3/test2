$(document).ready(function(){
	$('#timeFrameOptions').hide();
	
	// get REFERRALS BY STATUS chart
	getReferralStatusByGroupChart();
	// get REFERRALS BY PROFESSION chart
	getReferralByProfessionChart();
	// get CURRENT INDIVIDUAL REFERRAL GOALS VS ACTUAL ACTIVITY chart
	getCurrentIndividualChart();
	// get CURRENT GROUP REFERRAL GOALS VS ACTUAL ACTIVITY chart
	getCurrentGroupChart();
	
	// click on apply filter link
	$('#applyFilter').click(function(){
		searchFor = $('#searchFor').val();
		if(searchFor=="1"){
			getReferralStatusByGroupChart();
			getReferralByProfessionChart();
		}
		if(searchFor=="2"){
			getReferralStatusTimeFrameChart();
			getReferralByProfessionTimeFrameChart();
		}
	});
// click on Last graph apply filter link
	$('#refApplyFilter').click(function(){
		getReferralGraphTimeFrameChart();
	});
	
	// criteria change in filter
	$('#searchFor').change(function(){
		searchFor = $(this).val();
		if(searchFor=="1"){
			if($('#applyFilter').hasClass('disabled')) {
				$('#applyFilter').removeClass('disabled');
			}
		    $('#timeFrameOptions').hide();
		    $('.go_btn').removeClass('col-md-2').addClass('col-md-1');
		    $('#groupListOptions').show();
		}else{
			$('#applyFilter').addClass('disabled');
			$('.go_btn').removeClass('col-md-1').addClass('col-md-1');
			$('#timeFrameOptions').show(0,function(){});
			$('#groupListOptions').hide();
		}
	});
	
	// entiry change in filter
	/*$('#searchEntity').change(function(){
		getReferralStatusByGroupChart();
	});*/
	
	// function to get default parameter values for group option
	function getParams(){
		searchVal = $('#groupList').val();
		searchEntity = $('#searchEntity').val();
	}
	
	// function to get default parameter values for time frame option
	function getParamsForTimeFrame(){
		fromTimeVal = $('#timeRangeFromValue').val();
		toTimeVal = $('#timeRangeToValue').val();
		searchEntity = $('#searchEntity').val();
	}
	
	// function to draw chart for referral status according group
	function getReferralStatusByGroupChart(){
		$('#referralStatusWait').show();
		getParams();
		$.ajax({
			url: referralByStatusUrl+'Group',
	        context: document.body,
	        method: "POST",
	        data: { search_entity: searchEntity, entity_val: searchVal },
	        success: function(response){
	           $('#referralStatusWait').hide();
	           $('#referralStatusChart').html(response);
	     }});
	}
	
	// function to draw chart for referral status according time frame
	function getReferralStatusTimeFrameChart(){
		$('#referralStatusWait').show();
		getParamsForTimeFrame();
		$.ajax({
			url: referralByStatusUrl+'TimeFrame',
	        context: document.body,
	        method: "POST",
	        data: { search_entity: searchEntity, from_time_val: fromTimeVal, to_time_val: toTimeVal },
	        success: function(response){
	           $('#referralStatusWait').hide();
	           $('#referralStatusChart').html(response);
	     }});
	}
	
	// function to draw chart for referral status according time frame
	function getReferralGraphTimeFrameChart(){
		$('.referralActivityWait').removeClass('hidden');
			fromTimeVal1 = $('#timeRangeFromValue1').val();
			toTimeVal1 = $('#timeRangeToValue1').val();
			fromTimeVal2 = $('#timeRangeFromValue2').val();
			toTimeVal2 = $('#timeRangeToValue2').val();
			var selected = $("input[type='radio'][name='data[Dashboard][ref_type]']:checked");
			if (selected.length > 0) {
				searchEntity = selected.val();
			}
		$.ajax({
			url: referralActivityUrl,
	        context: document.body,
	        method: "POST",
	        data: { search_entity: searchEntity, from_time_val1: fromTimeVal1, to_time_val1: toTimeVal1, from_time_val2: fromTimeVal2, to_time_val2: toTimeVal2 },
	        success: function(response){
	        	//alert(response);	           
	           $('.ajax_response_graph').html(response);
	           $('.referralActivityWait').addClass('hidden');
	     }});
	}
	
	// function to draw chart for REFERRALS BY PROFESSION
	function getReferralByProfessionChart(){
		$('#referralByProfessionWait').show();
		getParams();
		$.ajax({
			url: referralByProfessionUrl+'Group',
	        context: document.body,
	        method: "POST",
	        data: { search_entity: searchEntity, entity_val: searchVal },
	        success: function(response){
	           $('#referralByProfessionWait').hide();
	           $('#referralByProfessionChart').html(response);
	     }});
	}
	
	// function to draw chart for REFERRALS BY PROFESSION of time frame
	function getReferralByProfessionTimeFrameChart(){
		$('#referralByProfessionWait').show();
		getParamsForTimeFrame();
		$.ajax({
			url: referralByProfessionUrl+'TimeFrame',
	        context: document.body,
	        method: "POST",
	        data: { search_entity: searchEntity, from_time_val: fromTimeVal, to_time_val: toTimeVal },
	        success: function(response){
	           $('#referralByProfessionWait').hide();
	           $('#referralByProfessionChart').html(response);
	     }});
	}
	
	// function to draw CURRENT INDIVIDUAL REFERRAL GOALS VS ACTUAL ACTIVITY chart
	function getCurrentIndividualChart(){
		$('#currentIndividualWait').show();
		getParams();
		$.ajax({
			url: currentIndividualReferralUrl,
	        context: document.body,
	        method: "POST",
	        data: { entity_val: searchVal },
	        success: function(response){
	           $('#currentIndividualWait').hide();
	           $('#currentIndividualReferralChart').html(response);
	     }});
	}
	
	// function to draw CURRENT GROUP REFERRAL GOALS VS ACTUAL ACTIVITY chart
	function getCurrentGroupChart(){
		$('#currentGroupWait').show();
		getParams();
		$.ajax({
			url: currentGroupReferralUrl,
	        context: document.body,
	        method: "POST",
	        data: { entity_val: searchVal },
	        success: function(response){
	           $('#currentGroupWait').hide();
	           
	           $('#currentGroupReferralChart').html(response);
	     }});
	}
	
	
	// Right panel js
	
	// get live feed
	getLiveFeed();
	window.setInterval(function(){
			getLiveFeed();
		}, 120000); //every 10 min (1000*60*10)
	
	// function to get live feed
	function getLiveFeed(){				
		$('#liveFeedWait').show();
		$.ajax({
			url: liveFeedUpdateUrl,
	        context: document.body,
	        method: "POST",
	        success: function(response){
	           $('#liveFeedWait').hide();
	           $('.live_feeds').html(response);
	        }
		});
	}
	
	// showing group drowdown multiselect
	$("#groupList").select2({
	    placeholder: "Select Group",
	    allowClear: true
	});
	

	
});