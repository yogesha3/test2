<?php
/**
 *  add Group content manager page
 */
?>
<!-- start: PAGE HEADER -->
<?php
echo $this->Html->css('../assets/plugins/bootstrap-datepicker/css/datepicker');
echo $this->Html->script('../assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker');
echo $this->Html->css('../assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min');
echo $this->Html->script('../assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min');
?>

<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Groups', array('controller' => 'groups', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">
                Add Group
            </li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', 'Add Group');?>
        </div>
        <!-- end: PAGE TITLE & BREADCRUMB -->
    </div>
</div>

<!-- end: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: FORM WIZARD PANEL -->
        <div class="panel panel-default">
            <?php
            $ajaxUrl = Configure::read("SITE_URL").'admin/groups/addGroup';
            echo $this->Form->input('',array('type'=>'hidden','id'=>'ajaxUrl','value'=>$ajaxUrl));
            ?>

            <div class="panel-body">
                <?php
                echo $this->Form->create('groups', array('url' => array('controller' => 'groups', 'action' => 'createNewGroup', 'admin'=>false), 'class' => 'smart-wizard form-horizontal', 'id' => 'addGroup','inputDefaults' => array('error' => false)));
                ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Group Type'.$this->Html->tag('span', '', array('class' => 'symbol', 'for' => 'metaTitle')), array('class' => 'col-sm-5 control-label')); ?>
                        <div class="col-sm-7">
                            <label class="radio-inline">
                                <input type="radio" value="2" name="data[Group][group_type]" class="grey" checked="checked">
                                Local
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="1" name="data[Group][group_type]" class="grey">
                                Global
                            </label>
                            
                        </div>
                    </div>

                    <!-- <div class="form-group">
                            <?php //echo $this->Form->label('', 'Group Name'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'groupName')), array('class' => 'col-sm-5 control-label')); ?>
                        <div class="col-sm-4">

                            <?php //echo $this->Form->input('Group.group_name', array('label' => false, 'class' => 'form-control', 'id' => 'groupName', 'placeholder'=>'Group Name','autocomplete'=>'off', 'maxlength'=>25,'required'=>false));?>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <?php echo $this->Form->label('', 'First Meeting Date'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'metaKeywords')), array('class' => 'col-sm-5 control-label')); ?>
                        <div class="col-sm-4">
                            <!-- <div class="input-group"> -->
                             <?php
                                echo $this->Form->input('Group.first_meeting_date',array('type'=>'text','label'=>false,'div'=>false,'data-date-viewmode'=>'years','data-date-format'=>'dd-mm-yyyy','class'=>'form-control date-picker','readonly'=>'1'));
                            ?>
                            <!-- <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span> -->
                           <!--  </div> -->
                        </div>
                    </div>
                    <div class="form-group">
                            <?php 
                            echo $this->Form->label('', 'Second Meeting Date'.$this->Html->tag('span', '', array('class' => 'symbol', 'for' => 'metaKeywords')), array('class' => 'col-sm-5 control-label')); 
                            
                            ?>
                        <div class="col-sm-4">
                           <!--  <div class="input-group"> -->
                                <?php
                                    echo $this->Form->input('second_meeting_date',array('type'=>'text','label'=>false,'div'=>false,'data-date-viewmode'=>'years','data-date-format'=>'dd-mm-yyyy','class'=>'form-control','id'=>'next','disabled'=>'disabled'));
                                    echo $this->Form->input('Group.second_meeting_date', array('type' => 'hidden','id'=>'second_meeting_date'));
                                ?>
                               <!--  <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span> -->
                            <!-- </div> -->
                        </div>
                    </div>
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Group Meeting Time'.$this->Html->tag('span', '', array('class' => 'symbol', 'for' => 'meetingTime')), array('class' => 'col-sm-5 control-label')); ?>
                        <div>
                            <div class="input-group bootstrap-timepicker col-sm-4">
                            	<select name="data[Group][slot]" class="form-control" id="check">
                            		<option>Select Meeting Time</option>
                            	</select>
                            </div>
                        </div>
                    </div> 
                   
                    
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Select Country', array('class' => 'col-sm-5 control-label')); ?>
                            <div class="col-sm-4" id="countryDiv">
                            <?php echo $this->Form->input('country',array('type'=>'text','label'=>false,'div'=>false,'id'=>'country','placeholder'=>"Country",'class'=>'form-control','required' => false,'maxlength'=>40));?>
                            <?php echo $this->Form->input('Group.country_id',array('type'=>'hidden','id'=>'country_id','class'=>'form-control'));?>
                            </div>
                        </div>
                    
                    <div class="form-group">
                           <?php echo $this->Form->label('', 'Select State', array('class' => 'col-sm-5 control-label')); ?>
                            <div class="col-sm-4" id="stateDiv">
                           <?php echo $this->Form->input('state',array('type'=>'text','label'=>false,'div'=>false,'id'=>'state','placeholder'=>'State','class'=>'form-control', 'required' => false,'maxlength'=>40));?>
                           <?php echo $this->Form->input('state_id',array('type'=>'hidden','id'=>'state_id','class'=>'form-control'));?>
                            </div>     
                        </div>
                    </div>
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Select City', array('class' => 'col-sm-5 control-label')); ?>
                        <div class="col-sm-4" id="cityDiv">
                            <?php echo $this->Form->input('Group.city', array('label' => false, 'class' => 'form-control', 'id' => 'cityName', 'placeholder'=>'City', 'maxlength'=>40));
                            
                            ?> 
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'ZIP Code'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'zipCode')), array('class' => 'col-sm-5 control-label')); ?>
                        <div class="col-sm-4">
                            <?php echo $this->Form->input('Group.zipcode', array(
                                'label' => false, 
                                'class' => 'form-control', 
                                'id' => 'zipCode',
                                'placeholder'=>'ZIP Code', 
                                'maxlength'=>12,
                                'autocomplete'=>'off'));?>
                        </div>
                    </div>
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Timezone'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'timezone')), array('class' => 'col-sm-5 control-label')); ?>
                        <div class="col-sm-4">
                            <?php echo $this->Form->select('Group.timezone_id',$timezones, array(
                                'label' => false, 
                                'class' => 'form-control', 
                                'id' => 'timeZone','empty'=>"Select Timezone",
                              ));?>
                        </div>
                        <div class="clearfix"></div>
                    </div>



                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-7">
                            <?php echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right ladda-button','data-style'=>'slide-left')); ?>
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
                </div>
                </div>
            </div>
        </div>
        <!-- end: FORM WIZARD PANEL -->
    </div>
</div>
<script type="text/javascript">
$(document).ready(function () {
	// set datepicker
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
            'url': 'addGroup',
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
</script>

