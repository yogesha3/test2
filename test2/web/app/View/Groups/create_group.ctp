<?php
echo $this->Html->css('../assets/plugins/bootstrap-datepicker/css/datepicker');
echo $this->Html->script('../assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker');
echo $this->Html->css('../assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min');
echo $this->Html->script('../assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min');
$localcheck = "";
$globalcheck = "";
if(isset($this->request->data['Group']['group_type']) && $this->request->data['Group']['group_type']=="local"){
	$localcheck = "checked";
}else{
	$globalcheck = "checked";
}
?>
<div class="inner_pages_heading" style="background: #fff; border: 0">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="intro-text"></div>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<section id="inner_pages_top_gap">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 ">
				<div class="add-new-group-head">Add New Group</div>
			</div>
		</div>
		<?php
            $ajaxUrl = Configure::read("SITE_URL").'groups/createGroup';
            echo $this->Form->input('',array('type'=>'hidden','id'=>'ajaxUrl','value'=>$ajaxUrl));
            ?>
		<?php echo $this->Form->create('Group', array('url' => array('controller' => 'groups', 'action' => 'createNewGroup'), 'class' => 'enter-group-name', 'id' => 'createGroupForm','inputDefaults' => array('div'=>false,'errorMessage' => false)));?>
		<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="select_group">
						<div class="group_type">Type of Group</div>
						<div class="type1">
							<input type="radio" value="local" name="data[Group][group_type]" class="height_auto" <?php echo $localcheck;?> class="height_auto" tabindex="1"> Local Group
						</div>
						<div class="type1">
							<input type="radio" value="global" name="data[Group][group_type]" class="height_auto" <?php echo $globalcheck;?> class="height_auto" tabindex="1"> Global Group
						</div>
					</div>
					<!-- <div class="enter_group">Enter Group Name*</div>
					<div class="clearfix"></div>
					<?php //echo $this->Form->input('Group.group_name', array('label' => false, 'class' => 'form-control input-group-name', 'id' => 'groupName', 'placeholder'=>'Enter Group Name','autocomplete'=>'off','required'=>false,'tabindex'=>"2"));?> -->
				</div>
			</div>
			<div class="clearfix">&nbsp;</div>
			<div class="clearfix">&nbsp;</div>
			<div class="clearfix">&nbsp;</div>
			<div class="clearfix">&nbsp;</div>
			<div class="row">
				<div class="col-md-4 ">
					<div class="row">
						<div class="form-group  group-meeting-date col-md-12 ">
							<label for="exampleInputPassword1">Group Meeting Date 1*</label>
							<?php echo $this->Form->input('Group.first_meeting_date',array('type'=>'text','label'=>false,'div'=>false,'data-date-viewmode'=>'years','data-date-format'=>'dd-mm-yyyy','class'=>'form-control date-picker','readonly'=>'1','tabindex'=>"3"));?>
						</div>
					</div>
				</div>
				<div class="col-md-4 ">
					<div class="row">
						<div class="form-group  group-meeting-date col-md-12 ">
							<label for="exampleInputPassword1">Group Meeting Date 2 <i
								class="fa fa-question-circle help_icon" data-toggle="tooltip"
								data-placement="top"
								title="Second meeting date is scheduled automatic for the next 15th day"></i>
							</label> 
							<?php echo $this->Form->input('second_meeting_date',array('type'=>'text','label'=>false,'div'=>false,'data-date-viewmode'=>'years','data-date-format'=>'dd-mm-yyyy','class'=>'form-control','id'=>'next','disabled'=>'disabled','tabindex'=>"4"));
                                  echo $this->Form->input('Group.second_meeting_date', array('type' => 'hidden','id'=>'second_meeting_date'));
                            ?>
						</div>
					</div>
				</div>
				<div class="col-md-4 ">
					<div class="row">
						<div class="form-group  group-meeting-date col-md-12 ">
							<label for="exampleInputPassword1">Group Meeting Time*</label>
							<div class="row">
							<div class="input-group bootstrap-timepicker col-sm-8">
							<div class="col-lg-12">
                                <?php
                                    //echo $this->Form->input('Group.meeting_time',array('type'=>'text','label'=>false,'div'=>false,'class'=>'form-control time-picker','tabindex'=>"5"));
                                ?>
                                <select name="data[Group][slot]" class="form-control" id="check">
                            		<option>Select Meeting Time</option>
                            	</select>
                            </div>
                            </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix">&nbsp;</div>
			<div class="row">
				<div class="col-md-4 ">
					<div class="form-group  group-meeting-date">
						<label for="exampleInputPassword1">Select Country*</label>
						 <?php echo $this->Form->input('Group.country',array('type'=>'text','id'=>'country','placeholder'=>"Country",'class'=>'form-control','required' => false, 'label' => false));?>
                                                 <?php echo $this->Form->input('Group.country_id',array('type'=>'hidden','id'=>'country_id','class'=>'form-control'));?>
					</div>
				</div>
				<div class="col-md-4 ">
					<div class="form-group  group-meeting-date">
						<label for="exampleInputPassword1">Select State*</label>
						<div id="stateDiv">
                                                    <?php echo $this->Form->input('BusinessOwner.state',array('type'=>'text','id'=>'state','placeholder'=>'State','class'=>'form-control',  'label' => false));?>
                                                    <?php echo $this->Form->input('BusinessOwner.state_id',array('type'=>'hidden','id'=>'state_id','class'=>'form-control'));?>
                                                </div>
					</div>
				</div>
				<div class="col-md-4 ">
					<div class="form-group  group-meeting-date">
						<label for="exampleInputPassword1">Select City</label> 
						<?php echo $this->Form->input('Group.city', array('label' => false, 'class' => 'form-control', 'id' => 'cityName', 'placeholder'=>'Select City'));?>
					</div>
				</div>
			</div>
			<div class="clearfix">&nbsp;</div>
			<div class="row">
				<div class="col-md-4 ">
					<div class="form-group  group-meeting-date">
						<label for="exampleInputPassword1">Zip Code*</label> 
						<?php echo $this->Form->input('Group.zipcode', array(
                                'label' => false, 
                                'class' => 'form-control', 
                                'id' => 'zipCode',
                                'placeholder'=>'Zip Code', 
                                'maxlength'=>50,
                                'autocomplete'=>'off')
							);?>						
					</div>
				</div>
				<div class="col-md-4 ">
					<div class="form-group  group-meeting-date">
						<label for="exampleInputPassword1">Time Zone*</label>
						<?php echo $this->Form->select('Group.timezone_id',$timezones, array(
                                'label' => false, 
                                'class' => 'form-control', 
                                'id' => 'timeZone','empty'=>"Select Timezone"
                              ));?>
					</div>
				</div>
				<div class="col-md-4 ">
					<div class="form-group  group-meeting-date">
						<label for="exampleInputPassword1">&nbsp;</label>
						<?php echo $this->Form->button('Submit',array('class'=>'btn btn-sm file_sent_btn grpcreate', 'type'=>'submit'));?>
						&nbsp&nbsp
						<?php echo $this->Html->link('Back','group-selection',array('class'=>'btn btn-sm back_btn text-center padauto add_focus grpcreate'))?>
					</div>
				</div>
			</div>
		<?php echo $this->Form->end();?>
</section>
<style>
.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {background-color: #F2F2F2;}
.icon-chevron-up:before { content: "";}
.icon-chevron-down:before {content: "";}
[class^="icon-"], [class*=" icon-"] {
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
}
table a:not(.btn), .table a:not(.btn) {text-decoration: none;}
</style>
<script type="text/javascript">
var ajaxUrl = "<?php echo Router::url(array('controller'=>'groups','action'=>'addGroup','admin'=>true))?>";
$(document).ready(function(){
	// tooltip block
	$('[data-toggle="tooltip"]').tooltip();
	// date block
	$('.date-picker').datepicker({
        autoclose: true,
        startDate: "today",
        endDate: '+30d',
        format:"mm-dd-yyyy",
    }).on('changeDate', function(ev){
        $('.date-picker').valid();
        var date2 = $( ".date-picker" ).datepicker( "getDate" );
        var month = date2.getMonth() +1;
        var currentDate = date2.getFullYear()+"-"+month+"-"+date2.getDate()
        $.ajax({
            'type': 'post',
            'dataType': 'json',
            'data': {'date': currentDate},
            'url': ajaxUrl,
            success: function (response) {
            	$("#check").html('');
            	if(response.length > 0){
            		for(var i=0;i<response.length;i++){
            			var obj = response[i];
            			for(var key in obj){
            				$("#check").append('<option value="'+key+'">'+obj[key]+'</option>');
            			}
            		}
            		date2.setDate(date2.getDate()+14);
            		$('#next').datepicker({format:"mm-dd-yyyy"});
            		$('#next').datepicker('setDate', date2);
            		var nextDate = $('#next').val();
            		$('#second_meeting_date').val(nextDate);
            	} else {
            		$("#check").append('<option value="">No time slots available</option>');
            	}            	
            }
        });        
    });
});

/**
 * ajaxChange() to fetch State /City list on country selection
 * @param url
 * @param countryId: country id
 * @added by Jitndra
 */
function ajaxChange(url,countryId){
    url=$('#ajaxUrl').val();
    var divUpdate = 'stateDiv';
     if(countryId != ''){
        $.ajax({
         'type':'post',
         'data':{'countryId':countryId},
         'url':url,
         success:function(msg){                 
             $('#'+divUpdate).html(msg);
         }
     });
    }
    if(countryId == ''){
        $('#stateDiv').html("<select id='state' class='form-control' name='data[groups][state_id]'><option value=''>Select State</option></select>");
    }        
}
</script>

