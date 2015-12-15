<?php
echo $this->Html->css('../assets/plugins/select2/select2');
echo $this->Html->script('../assets/plugins/select2/select2.min');
echo $this->Html->css('datepicker');
echo $this->Html->css('picker');
echo $this->Html->script('tether.min');
echo $this->Html->script('datePicker');
echo $this->Html->script('Front/all');
?>
<?php echo $this->Html->script(array('Front/charts/Chart.min'))?>
<style>
.select2-container-multi .select2-choices{min-width: 200px;background: none repeat scroll 0 0 #F2F2F2 !important;}
.select2-container-multi .select2-choices .select2-search-field input {padding: 0px 3px 0px;font-family: "Graphik-Regular";color:#666666!important;font-size:13px}
</style>
<div class="row margin_top_referral_search">
<div class="col-md-9 col-sm-8">
	<div class="row">
		<div class="col-md-12">
			<div class="referrals_reviews">
				<div class="referrals_reviews_head padd-top0"><?php echo $loginUserName;?>’s Dashboard</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<div class="clearfix">&nbsp;</div>
	
	<div class=" row ">
		<div class="col-md-6">
			<div class="graph_container">
				<div class="graph_heading">
					<h2>Current Group Referral Goals VS Actual Activity</h2>
				</div>
				<div class="graph_div full_graph_container text-center" id="currentGroupReferralChart">
					<div id="rays"><?php echo $this->Html->image('loding-logo.png',array('id'=>'currentGroupWait','class'=>'center-block img-responsive'));?></div>
				</div>
			</div>
		</div>
		
		<div class="col-md-6">
			<div class="graph_container">
				<div class="graph_heading no_graph_border">
					<h2>Current Individual Referral Goals VS Actual Activity</h2>
				</div>
				<div class="graph_div full_graph_container text-center" id="currentIndividualReferralChart">
					<div id="rays"><?php echo $this->Html->image('loding-logo.png',array('id'=>'currentIndividualWait','class'=>'center-block img-responsive'));?></div>
				</div>
			</div>
		</div>
		
	</div>
	<div class="clearfix">&nbsp;</div>
	<?php echo $this->Form->create('Dashboard',array('id'=>'graphFilterForm','class'=>'','inputDefaults' => array('label' => false,'div' => false)));?>
	<div class="row ">
		<div class="col-md-3 col-sm-5 col-xs-7 width_at_mob">
			<?php echo $this->Form->input('search_entity',array('type' => 'select','id' => 'searchEntity', 'options' => array('1'=>'Number of Referrals','2'=>'Value of Referrals'), 'class'=>'form-control seclect_value seclect_bulk','label'=>false));?>			
		</div>
		<div class="col-md-3 col-sm-6 col-xs-7 width_at_mob">
			<?php echo $this->Form->input('search_for',array('type' => 'select','id' => 'searchFor', 'options' => array('1'=>'Group','2'=>'Time Frame'), 'class'=>'form-control seclect_value seclect_bulk','label'=>false));?>
		</div>
		
		<div class="width_at_mob col-md-5 calendar-input-color" id="timeFrameOptions">
        <div class="row ">
		<div class="col-md-8 col-sm-8 col-xs-7 width_at_mob" style="position:relative;">
		   <div class='selectMonths'>
			
		    </div> 
	    <div class='selectMonths'>
            <input type='text' placeholder='Select TimeFrame' id="choosetimeFrame" readonly class="form-control" />
            <i class="fa fa-calendar"></i>
        </div>
		<input type='hidden' id="timeRangeFromValue" class="form-control" />
		<input type='hidden' id="timeRangeToValue" class="form-control" />	
		</div>		
        </div>
		</div>		
		
		<div class="col-md-5 col-sm-6 col-xs-7 width_at_mob myselect-width" id="groupListOptions">
			<?php echo $this->Form->input('group_id',array('type' => 'select','id' => 'groupList', 'options' => $groupList, 'multiple'=>'multiple', 'selected' => $groupId, 'class'=>'','label'=>false));?>
		</div>       
		
		<div class="col-md-1 col-sm-1 col-xs-7 width_at_mob go_btn">
		<a class="custom_go_button btn btn-sm back_btn text-center add_focus pull-right "  id="applyFilter" href="javascript:void(0)" >Go</a>
		</div>
	</div>
	<?php echo $this->Form->end();?>
	<div class="clearfix">&nbsp;</div>
	<div class=" row ">
		<div class="col-md-6">
			<div class="graph_container">
				<div class="graph_heading no_graph_border">
					<h2>Referrals By Status</h2>
				</div>
				<div class="graph_div full_graph_container text-center" id="referralStatusChart">					
					<div id="rays"><?php echo $this->Html->image('loding-logo.png',array('id'=>'referralStatusWait','class'=>'center-block img-responsive'));?></div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="graph_container">
				<div class="graph_heading no_graph_border">
					<h2>Referrals By Profession</h2>
				</div>
				<div class="graph_div full_graph_container text-center" id="referralByProfessionChart">					
					<div id="rays"><?php echo $this->Html->image('loding-logo.png',array('id'=>'referralByProfessionWait','class'=>'center-block img-responsive'));?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix">&nbsp;</div>
	<div class=" row ">
		<div class="col-md-6">
			<div class="graph_container">
				<div class="graph_heading">
					<h2>Referrals Activity</h2>
					<label class="checkbox-inline "> <input type="radio"
						value="option1" name="activity1" id="inlineCheckbox11"> <span>Received</span>
					</label> <label class="checkbox-inline"> <input type="radio"
						value="option1" name="activity1" id="inlineCheckbox12"> <span>Given</span>
					</label> <label class="checkbox-inline"> <input type="radio"
						value="option1" name="activity1" id="inlineCheckbox13"> <span>Both</span>
					</label>
					<div class="clearfix"></div>
				</div>
				<div class="graph_div" style="min-height:331px">
					<?php echo $this->Html->image('g4.png',array('class'=>'center-block img-responsive'));?>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="graph_container">
				<div class="graph_heading">
				<?php echo $this->Form->create('Dashboard',array('id'=>'refGraphFilterForm','class'=>'','inputDefaults' => array('label' => false,'div' => false)));?>
					<h2>Referral Activity Comparative By Date</h2>
					<label class="checkbox-inline nopadding-left"> <input type="radio" value="received" name="data[Dashboard][ref_type]" id="inlineCheckbox1" checked="checked"> <span>Received</span>
					</label>
					<label class="checkbox-inline"> <input type="radio" value="sent" name="data[Dashboard][ref_type]" id="inlineCheckbox2"> <span>Sent</span>
					</label>
					<label class="checkbox-inline"> <input type="radio" value="both" name="data[Dashboard][ref_type]" id="inlineCheckbox3"> <span>Both</span>
					</label>
					
					<div class="select_year selectMonths">
						<input type="text" placeholder="Select TimeFrame" id="choosetimeFrame2" class="form-control " readonly="readonly">
					</div>
					<div class="col-md-3 col-sm-1 col-xs-7 width_at_mob go_btn ref_go_btn" style="margin-top: 3px;margin-left:0px;" >
		<a class="custom_go_button btn btn-sm back_btn text-center add_focus pull-right disabled" id="refApplyFilter" href="javascript:void(0)">Go</a>
		</div>
					<!--  <div class="select_year borderR pull-right">
						<select class="form-control seclect_value seclect_bulk">
							<option>Bulk Action</option>
							<option>1 Bulk Action</option>
							<option>2 Bulk Action</option>
							<option>3 Bulk Action</option>
							<option>4 Bulk Action</option>
							<option>5 Marke as deleted</option>
						</select>
					</div>-->
					<div class="clearfix"></div>
					<div class=" to_text ">vs</div>
					
					<div class="clearfix"></div>
					<div class="select_year selectMonths">
						<input type="text" placeholder="Select TimeFrame" id="choosetimeFrame3" class="form-control " readonly="readonly">
					</div>
					<input type='hidden' id="timeRangeFromValue1" class="form-control" />
            		<input type='hidden' id="timeRangeToValue1" class="form-control" />	
            		<input type='hidden' id="timeRangeFromValue2" class="form-control" />
            		<input type='hidden' id="timeRangeToValue2" class="form-control" />	
					<div class="clearfix"></div>
					<?php echo $this->Form->end();?>
				</div>
				<div class="graph_div">
				<div id="rays" class="referralActivityWait hidden"><?php echo $this->Html->image('loding-logo.png',array('class'=>'center-block img-responsive '));?></div>
    				<div class="ajax_response_graph">
    				    <?php echo $this->Html->image('referral_graph_placeholder.jpg',array('class'=>'center-block img-responsive placeholder_img'));?>
    				</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
<?php echo $this->element('Front/dashboard_right',array('percentage'=>$percentage,'webcast'=>$latestWebcast));?>
</div>
<?php echo $this->element('Front/bottom_ads');?>
<!-- Contact Section -->
<script>
var referralByStatusUrl = "<?php echo Router::url(array('controller'=>'dashboard','action'=>'referralStatusChartBy'));?>";
var referralActivityUrl = "<?php echo Router::url(array('controller'=>'dashboard','action'=>'referralActivityChart'));?>";
var referralByProfessionUrl = "<?php echo Router::url(array('controller'=>'dashboard','action'=>'referralProfessionChartBy'));?>";
var currentIndividualReferralUrl = "<?php echo Router::url(array('controller'=>'dashboard','action'=>'currentIndividualReferralChart'));?>";
var currentGroupReferralUrl = "<?php echo Router::url(array('controller'=>'dashboard','action'=>'currentGroupReferralChart'));?>";
var liveFeedUpdateUrl = "<?php echo Router::url(array('controller'=>'dashboard','action'=>'liveFeedUpdates'));?>";
</script>
<script type="text/javascript">
$(document).ready(function(){
	$('#choosetimeFrame').rangePicker({ minDate:[6,2013], maxDate:[<?php echo date('n').",".date('Y')?>]})
		// subscribe to the "done" event after user had selected a date
    .on('datePicker.done', function(e, result){
        $('#timeRangeFromValue').val(result[0]);
        $('#timeRangeToValue').val(result[1]);
        if($('#timeRangeFromValue').val()!='' && $('#timeRangeToValue').val()!='') {
            $('#applyFilter').removeClass('disabled');    
        }
    });

	$('#choosetimeFrame2').rangePicker({ minDate:[6,2013], maxDate:[<?php echo date('n').",".date('Y')?>]})
	// subscribe to the "done" event after user had selected a date
    .on('datePicker.done', function(e, result){
        $('#timeRangeFromValue1').val(result[0]);
        $('#timeRangeToValue1').val(result[1]);
        if($('#timeRangeFromValue1').val()!='' && $('#timeRangeToValue1').val()!='' && $('#timeRangeFromValue2').val()!='' && $('#timeRangeToValue2').val()!='') {
            $('#refApplyFilter').removeClass('disabled');
        }
    });
	$('#choosetimeFrame3').rangePicker({ minDate:[6,2013], maxDate:[<?php echo date('n').",".date('Y')?>]})
	// subscribe to the "done" event after user had selected a date
    .on('datePicker.done', function(e, result){
        $('#timeRangeFromValue2').val(result[0]);
        $('#timeRangeToValue2').val(result[1]);
        if($('#timeRangeFromValue1').val()!='' && $('#timeRangeToValue1').val()!='' && $('#timeRangeFromValue2').val()!='' && $('#timeRangeToValue2').val()!='') {
            $('#refApplyFilter').removeClass('disabled');
        }
    });

    // update settings
	/*$('.selectMonths:last input').rangePicker({ setDate:[[<?php echo date('n');?>,<?php echo date('Y')?>],[<?php echo date('n',strtotime('+1 months'));?>,<?php echo date('Y')?>]], closeOnSelect:true });*/
	$('#timeRangeFromValue').val("<?php echo date('n').",".date('Y')?>");
	$('#timeRangeToValue').val("<?php echo date('n',strtotime('+1 months')).",".date('Y')?>");
});
</script>
<?php echo $this->Html->script ( 'Front/dashboard' );