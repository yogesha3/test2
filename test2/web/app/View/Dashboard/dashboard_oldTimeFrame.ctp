<?php
echo $this->Html->css('../assets/plugins/select2/select2');
echo $this->Html->script('../assets/plugins/select2/select2.min');
echo $this->Html->css('datepicker');
echo $this->Html->css('picker');
echo $this->Html->script('tether.min');
echo $this->Html->script('datePicker');
echo $this->Html->script('Front/all');
?>
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
            <input type='text' placeholder='Select TimeFrame' value='' readonly class="form-control" />
            <i class="fa fa-calendar"></i>
        </div>
			
		</div>
		
        </div>
		</div>		
		
		<div class="col-md-5 col-sm-6 col-xs-7 width_at_mob myselect-width" id="groupListOptions">
			<?php echo $this->Form->input('group_id',array('type' => 'select','id' => 'groupList', 'options' => $groupList, 'multiple'=>'multiple', 'selected' => $groupId, 'class'=>'','label'=>false));?>
		</div>       
		
		<div class="col-md-1 col-sm-1 col-xs-7 width_at_mob go_btn">
		<a class="custom_go_button btn btn-sm back_btn text-center add_focus pull-right"  id="applyFilter" href="javascript:void(0)" class="pull-right">Go</a>
		</div>
	</div>
	<?php echo $this->Form->end();?>
	<div class="clearfix">&nbsp;</div>
	<div class=" row ">
		<div class="col-md-6">
			<div class="graph_container">
				<div class="graph_heading">
					<h2>CURRENT GROUP REFERRAL GOALS VS ACTUAL ACTIVITY</h2>
				</div>
				<div class="graph_div">
					<?php echo $this->Html->image('g1.png',array('class'=>'center-block img-responsive'));?>					
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="graph_container">
				<div class="graph_heading">
					<h2>REFERRAL ACTIVITY COMPARATIVE BY DATE ONLY</h2>
					<label class="checkbox-inline"> <input type="radio"
						value="option1" name="activity" id="inlineCheckbox1"> <span>Received</span>
					</label> <label class="checkbox-inline"> <input type="radio"
						value="option1" name="activity" id="inlineCheckbox2"> <span>Given</span>
					</label> <label class="checkbox-inline"> <input type="radio"
						value="option1" name="activity" id="inlineCheckbox3"> <span>Both</span>
					</label>
					<div class="select_year">
						<select class="form-control seclect_value seclect_bulk">
							<option>Bulk Action</option>
							<option>1 Bulk Action</option>
							<option>2 Bulk Action</option>
							<option>3 Bulk Action</option>
							<option>4 Bulk Action</option>
							<option>5 Marke as deleted</option>
						</select>
					</div>
					<div class="select_year borderR pull-right">
						<select class="form-control seclect_value seclect_bulk">
							<option>Bulk Action</option>
							<option>1 Bulk Action</option>
							<option>2 Bulk Action</option>
							<option>3 Bulk Action</option>
							<option>4 Bulk Action</option>
							<option>5 Marke as deleted</option>
						</select>
					</div>
					<div class="clearfix"></div>
					<div class=" to_text">To</div>
					<div class=" to_text vs_text">VS</div>
					<div class=" to_text">To</div>
					<div class="select_year mT0">
						<select class="form-control seclect_value seclect_bulk">
							<option>Bulk Action</option>
							<option>1 Bulk Action</option>
							<option>2 Bulk Action</option>
							<option>3 Bulk Action</option>
							<option>4 Bulk Action</option>
							<option>5 Marke as deleted</option>
						</select>
					</div>
					<div class="select_year borderR pull-right mT0">
						<select class="form-control seclect_value seclect_bulk">
							<option>Bulk Action</option>
							<option>1 Bulk Action</option>
							<option>2 Bulk Action</option>
							<option>3 Bulk Action</option>
							<option>4 Bulk Action</option>
							<option>5 Marke as deleted</option>
						</select>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="graph_div">
				<?php echo $this->Html->image('g2.png',array('class'=>'center-block img-responsive'));?>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix">&nbsp;</div>
	<div class=" row ">
		<div class="col-md-6">
			<div class="graph_container">
				<div class="graph_heading no_graph_border">
					<h2>REFERRALS BY STATUS</h2>
				</div>
				<div class="graph_div full_graph_container text-center" id="referralStatusChart">					
					<div id="rays"><?php echo $this->Html->image('loding-logo.png',array('id'=>'referralStatusWait','class'=>'center-block img-responsive'));?></div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="graph_container">
				<div class="graph_heading">
					<h2>REFERRALS BY PROFESSION</h2>
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
					<h2>REFERRALS ACTIVITY</h2>
					<label class="checkbox-inline"> <input type="radio"
						value="option1" name="activity1" id="inlineCheckbox11"> <span>Received</span>
					</label> <label class="checkbox-inline"> <input type="radio"
						value="option1" name="activity1" id="inlineCheckbox12"> <span>Given</span>
					</label> <label class="checkbox-inline"> <input type="radio"
						value="option1" name="activity1" id="inlineCheckbox13"> <span>Both</span>
					</label>
					<div class="clearfix"></div>
				</div>
				<div class="graph_div" style="min-height:344px">
					<?php echo $this->Html->image('g4.png',array('class'=>'center-block img-responsive'));?>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="graph_container">
				<div class="graph_heading">
					<h2>CURRENT INDIVIDUAL REFERRAL GOALS VS ACTUAL ACTIVITY</h2>
				</div>
				<div class="graph_div">
					<?php echo $this->Html->image('g1.png',array('class'=>'center-block img-responsive'));?>
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
var referralStatusByGroupUrl = "<?php echo Router::url(array('controller'=>'dashboard','action'=>'referralStatusChartByGroup'));?>";
var referralByProfessionUrl = "<?php echo Router::url(array('controller'=>'dashboard','action'=>'referralProfessionChartByGroup'));?>";
var liveFeedUpdateUrl = "<?php echo Router::url(array('controller'=>'dashboard','action'=>'liveFeedUpdates'));?>";
</script>

<style>

.selectMonths{ float:left; position:relative; display:block; }
            .selectMonthsselect {height: 30px; }
            .selectMonths > i {
    position: absolute;
    right: 11px;
    top: 5px;
    opacity: 1;
    font-style: normal;
    font-size: 16px;
    transition: 0.2s;
    pointer-events: none; color:#f05a28
}

            .selectMonths > input{ text-transform:capitalize; padding-left:10px; cursor:default; cursor:pointer; }
            .selectMonths:hover > i{ opacity:.7; }
            .selectMonths + .selectMonths{ float:none; }
			.preset{display:none !important;}
			.rangePicker.show>.wrap {
                max-height: 265px !important;
            }
</style>
		<script type="text/javascript">
		$(document).ready(function(){
			
			$('.selectMonths:first input').rangePicker({ minDate:[<?php echo date('n',strtotime('+1 months'));?>,<?php echo date('Y')?>], maxDate:[<?php echo date('n',strtotime('+1 months'));?>,<?php echo date('Y',strtotime('+10 years'))?>], RTL:false , closeOnSelect:true})
            // subscribe to the "done" event after user had selected a date
            .on('datePicker.done', function(e, result){
                if( result instanceof Array )
                    console.log(new Date(result[0][1], result[0][0] - 1), new Date(result[1][1], result[1][0] - 1));
                else
                    console.log(result);
            });

        // update settings
		$('.selectMonths:last input').rangePicker({ setDate:[[<?php echo date('n');?>,<?php echo date('Y')?>],[<?php echo date('n',strtotime('+1 months'));?>,<?php echo date('Y')?>]], closeOnSelect:true });
			
		});
		</script>
<?php echo $this->Html->script ( 'Front/dashboard' );