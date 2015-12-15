<?php

/**
 * users listing landing page
 * @author Jitendra
 */
//adding Page specific css and javascript files
echo $this->Html->css('../assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min');
echo $this->Html->script('../assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min');
echo $this->Paginator->options(array(
    'url' => array(
        "perpage"=>$perpage,
        "search"=>$search,
        'sort'=> $this->Session->read('sort'),
        'direction'=> $this->Session->read('direction'),
        'profession'=>$professionId,
        'meeting_time'=>$meetingTime,
        'country'=>$countryId,
        'state'=>$stateId
        ),
    'update' => '.panel-body',
    'evalScripts' => true
    ));
?>

<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li><i class="clip-file"></i>
                <?php echo $this->Html->link('Business Owners', array('controller' => 'businessOwners', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">User List</li>
            <li class="search-box">
                <form class="sidebar-search">
                    <div class="form-group">
                        <input type="text" id="searching" name="search" placeholder="Start Searching...">
                    </div>
                <?php
                    $this->Js->get('#searching');
                    $this->Js->event('keyup',
                    $this->Js->request(array(
                            'controller'=>'businessOwners',
                            'action'=>'index'),
                            array('async'=>true,
                                  'update'=>'.panel-body',
                                  'dataExpression'=>true,
                                    'data' => '$(\'#searching,#perpage,#category,#profession,#meeting_time,#state,#country\').serializeArray()',
                                  'method'=>'post')
                         )
                    );
                ?>

                </form>
            </li>
        </ol>
        <div class="page-header">
            <h1>
                User List
                <?php echo $this->Element('records_per_page');?>
            </h1>
        </div>
        <?php
            $this->Js->get('#perpage');
            $this->Js->event('change',
            $this->Js->request(array(
                    'controller'=>'businessOwners',
                    'action'=>'index'),
                    array('async'=>true,
                          'update'=>'.panel-body',
                          'dataExpression'=>true,
                          /*'data' => $this->Js->serializeForm(array(
                                        'isForm' => true,
                                        'inline' => true
                                    )),*/
                            'data' => '$(\'#searching,#perpage,#category,#profession,#meeting_time,#state,#country\').serializeArray()',
                          'method'=>'post')
                 )
            );
        ?>
        <!-- end: PAGE TITLE & BREADCRUMB -->
    </div>
</div>
<div class="row">
    <div align="right" class="col-md-12">
	<?php echo $this->Html->link(
    '<i class="clip-folder-download">&nbsp;</i>Download Business Owners',
    '#', array('onclick'=>'exportBusinessOwners()','escape' => false,'style'=>'font-weight: bold;','class'=>'dropdown-toggle','data-toggle'=>'dropdown'));?>
    <ul class="dropdown-menu export-heading">
    <?php echo $this->Form->create('ExportBusinessOwner',array('id'=>'#exportBusinessOwner','inputDefaults' => array('label' => false,'div' => false)))?>
    	<?php echo $this->Form->hidden('filter_params',array('id'=>'filter_params'))?>
    	<?php echo $this->Form->hidden('search_params',array('id'=>'search_params'))?>
		<li><div class="checkbox"><input type="checkbox" name="data[heading][]" value="BusinessOwner.member_name" id="member_name" class="grey" checked /> Member Name</div></li>
		<li><div class="checkbox"><input type="checkbox" name="data[heading][]" value="Profession.profession_name" id="profession_name" class="grey" checked />Profession</div></li>
		<li><div class="checkbox"><input type="checkbox" name="data[heading][]" value="Group.group_name" id="group_name" class="grey" checked /> Group Name</div></li>
		<li><div class="checkbox"><input type="checkbox" name="data[heading][]" value="Group.meeting_time" id="group_name" class="grey" checked /> Meeting Time</div></li>
		<li><div class="checkbox"><input type="checkbox" name="data[heading][]" value="Group.first_meeting_date" id="group_name" class="grey" checked /> Meeting Date</div></li>
		<li><div class="checkbox"><input type="checkbox" name="data[heading][]" value="Country.country_name" id="country_name" class="grey" checked /> Country</div></li>		
		<li><div class="checkbox"><input type="checkbox" name="data[heading][]" value="State.state_subdivision_name" id="state_name" class="grey" checked /> State</div></li>
		<!--<li><div class="checkbox"><input type="checkbox" name="data[heading][]" value="BusinessOwner.city" id="city_name" class="grey" checked /> City</div></li>-->
		<li><?php echo $this->Form->input('OK',array('type'=>'submit','id'=>'heading_submit'))?></li>
	<?php echo $this->Form->end();?>
	</ul>
	</div>
    <div class="col-md-12">
        <!-- start: BASIC TABLE PANEL -->
        <div class="panel panel-default">
         
            <?php
//          $ajaxUrl = Configure::read("SITE_URL").'admin/businessOwners/getData';
            $ajaxUrl = Router::url(array('controller'=>'businessOwners','action'=>'getData','admin'=>true));
            $professionUrl = Router::url(array('controller'=>'businessOwners','action'=>'getProfessionList','admin'=>true));
            echo $this->Form->input('',array('type'=>'hidden','id'=>'ajaxUrl','value'=>$ajaxUrl));
            echo $this->Form->input('',array('type'=>'hidden','id'=>'professionUrl','value'=>$professionUrl));
            ?>
            <div>
               <?php
                echo $this->Form->create('BusinessOwner', array('url' => array('controller' => 'businessOwners', 'action' => 'index', 'admin'=>true), 'class' => 'smart-wizard form-horizontal', 'id' => 'filterBusinessOwners','inputDefaults' => array('error' => false)));
                ?> <p style="padding: 15px 5px 5px 20px;"><strong style="color:#707070;">Filter Users By :</strong></p>
                <table id="filter-table-1" class="table table-hover">
                    <thead></thead><tr>
                        <td colspan="5" id="errormsgdi">
                            <label class="error" for='deshdash' id="errorMsg" style="display: none">Please select some criteria to apply the filter</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="col-md-2">
                            <?php 
                            echo $this->Form->select('BusinessOwner.category_id', $categories,
                                array(
                                        'label' => false, 
                                        'class' => 'form-control filter', 
                                        'id' => 'category',
                                        'onChange' => 'ajaxChange("'.$professionUrl.'", this.value)',
                                        'empty' => 'Select Category'));
                            ?>
                        </td>
                        <td class="col-md-2">
                            <div id="professionDiv">
                            <?php 
                            echo $this->Form->select('BusinessOwner.profession_id', $professions,
                                array(
                                        'label' => false, 
                                        'class' => 'form-control filter', 
                                        'id' => 'profession',
                                        'empty' => 'Select Profession'));
                            ?>
                            </div>
                        </td>
                        <td class="col-md-2">
                            <?php
                                echo $this->Form->input('BusinessOwner.country',array('type'=>'text','id'=>'country','placeholder'=>"Country",'class'=>'form-control','required' => false, 'label' => false));
                                echo $this->Form->input('BusinessOwner.country_id',array('type'=>'hidden','id'=>'country_id','class'=>'form-control'));?>
                            <?php 
                            ?>
                        </td>
                        <td class="col-md-2">
                            <div id="stateDiv">
                                <?php
                                    echo $this->Form->input('BusinessOwner.state',array('type'=>'text','id'=>'state','placeholder'=>'State','class'=>'form-control',  'label' => false));
                                    echo $this->Form->input('BusinessOwner.state_id',array('type'=>'hidden','id'=>'state_id','class'=>'form-control'));?>
                            </div>
                        </td>
                        <!--<td class="col-md-2">
                            <div id="cityDiv">
                            <?php 
                            //echo $this->Form->input('BusinessOwner.city_id', array('type' => 'text', 'label' => false, 'div' =>false, 'class' => 'form-control', 'id' => 'city', 'placeholder' =>'City')); 
                            ?>
                            </div>
                        </td>-->
                        <td class="col-md-2">
                            <div id="meetingTimeDiv">
                                <div class="input-group bootstrap-timepicker">
                                <?php
                                    echo $this->Form->input('BusinessOwner.meeting_time',array('type'=>'text','label'=>false,'div'=>false,'class'=>'form-control time-picker filter','id'=>'meeting_time','placeholder'=>'Meeting Time'));
                                ?>                                
                                <!-- <span class="input-group-addon"><i class="fa fa-clock-o"></i></span> -->
                                </div>
                            </div>
                        </td>
                       
                    </tr>
                    <tr>
                        <td colspan="5">
                            <div class="input text pull-right">
                                    <?php echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i>Clear Filter', array('type' => 'button','onclick'=>'resetForm()', 'class' => 'btn btn-light-grey go-back cancel')), array('controller' => 'businessOwners', 'action' => 'index', 'admin' => true), array('escape' => false)); ?>
                                    <?php echo $this->Form->button('Filter <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky'));?>
                            </div>
                        </td>
                    </tr>
                </table>
                <?php echo $this->Form->end(); ?>
                
            </div>
        </div>
    </div>
	<div class="col-md-12">
		<!-- start: BASIC TABLE PANEL -->
		<div class="panel panel-default">

            <div class="panel-body">
                <table id="sample-table-1" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="center">S.No.</th>
                            <th><?php echo $this->Paginator->sort('fname', 'Member Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('Profession.profession_name', 'Profession'); ?></th>
                            <th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>                          
                            <th><?php echo $this->Paginator->sort('Country.country_name', 'Country'); ?></th>
                            <th><?php echo $this->Paginator->sort('AvailableSlot.slot_id','Meeting Time');?></th>
                            <th><?php echo $this->Paginator->sort('Group.first_meeting_date','Meeting Date1');?></th>
                            <th><?php echo $this->Paginator->sort('Group.id','Group Name');?></th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="professionContent">
                        <?php         
                        if (!empty($businessOwners)) {
                            foreach ($businessOwners as $businessOwner) {
                                $businessOwnerId = $businessOwner['BusinessOwner']['id'];
                                $createdDate      = date('m-d-Y' , strtotime($businessOwner['BusinessOwner']['created']));
                                $modifiedDate      = date('m-d-Y' , strtotime($businessOwner['BusinessOwner']['modified']));
                                $groupId=$this->Encryption->decode($businessOwner['Group']['id']);
                        ?>
                        <tr>
                            <td class="center"><?php echo $counter;?></td>
                            <td class="hidden-xs"><?php echo ucfirst($businessOwner['BusinessOwner']['fname'])." ".ucfirst($businessOwner['BusinessOwner']['lname']); ?></td>
                            <td><?php echo ucfirst($businessOwner['Profession']['profession_name']);?></td>
                            <td><?php echo $businessOwner['BusinessOwner']['email'];?></td>                      
                            <td><?php echo ucfirst($businessOwner['Country']['country_name']);?></td>
                            <td><?php if($groupId) { echo $this->Adobeconnect->getFirstMeetingTime($businessOwner['AvailableSlot']['slot_id']) ; } else { echo '-';}?></td>
                            <td><?php if($groupId) { echo date('m-d-Y',strtotime($businessOwner['Group']['first_meeting_date']));} else { echo '-';}?></td>
                            <td><?php if($groupId) { echo Configure::read('GROUP_PREFIX').' '.$groupId;} else { echo '-';}
                            ?></td>

                            <td class="center">
                                <div class="visible-md visible-lg hidden-sm hidden-xs">
                                            <?php
                                            echo $this->Html->link('<i class="clip-search"></i>', array('controller' => 'businessOwners', 'action' => 'view', $businessOwnerId,'admin'=>true), array('class' => 'btn btn-xs btn-teal tooltips', 'data-original-title' => 'View', 'data-placement' => 'top', 'escape' => false));
                                            echo '&nbsp;';
                                            /*echo $this->Html->link($this->Html->tag('i', '', array('class' => 'clip-checkmark')), '#', array(
                                                'class' => 'btn btn-xs btn-bricky tooltips deleteProfession',
                                                'data-original-title' => 'Check',
                                                'data-toggle' => 'modal',
                                                'id'=>$businessOwnerId,
                                                'data-target' => '#deleteConfirmation',
                                                'data-id'=>$businessOwnerId,
                                                'data-action' => Router::url(
                                                        array('action' => 'admin_delete', $businessOwnerId)
                                                ),
                                                'escape' => false), false);*/
                                            ?>
                                </div>
                            </td>
                        </tr>

                                <?php
                                $counter++;
                            }
                        }else{
                            echo "<tr><td colspan='8' style='text-align:center'>No record found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <?php if($this->Paginator->numbers()){ ?>

                <div class="paging" style="float: right;">
                    <ul class="pagination" style="margin: 0px;">
                        <li>
              <?php echo $this->Paginator->prev(__('Previous',true)); ?>      
                        </li>
                        <li>
              <?php echo $this->Paginator->numbers(array('separator'=>false)); ?>      
                        </li>
                        <li>
             <?php echo $this->Paginator->next(__('Next',true)); ?>
                        </li>
                    </ul>
                </div>
                <?php } ?>
    <?php echo $this->Js->writeBuffer(); ?>
            </div>
        </div>
        <!-- end: BASIC TABLE PANEL -->
    </div>
</div>
<script type="text/javascript">
    function resetForm() {
        $("select option:selected").attr('selected', false);
        $("select option:first").attr('selected', true);
    }
   $(document).ready(function(){
    	$( "#filterBusinessOwners" ).submit(function( event ) {
        	category_val = $('#category').val();
        	prof_val 	= $('#profession').val();
        	country_val = $('#country').val();
        	state_val 	= $('#state').val();
        	//city_val 	= $('#city').val();
        	meeting_val = $('#meeting_time').val();        	        	
    		if (category_val == "" && prof_val =="" && country_val=="" && state_val=="" && meeting_val=="") {
    			$( "#errorMsg" ).show();
    			setTimeout( "$('#errorMsg').hide();",5000 );
	    		return false;
    		}
    		return;    		
    		event.preventDefault();
    	});  

        $('#meeting_time').timepicker({
            showMeridian:true,
            minuteStep: 30,
            showInputs: true,
            disableFocus: true,
        });
        
        <?php if(isset($reqMeetingTime)) {echo "$('#meeting_time').val('".$reqMeetingTime."');"; } else {echo "$('#meeting_time').val('');";}?>
    });
    
    function exportBusinessOwners(){
        flterparmas = 'category='+category_val+'&profession='+profession_val+'&country='+country_val+'&state='+state_val+'&meeting_time='+meeting_val;    	
    	$("#search_params").val($('form.sidebar-search').serialize());
    	$("#filter_params").val(flterparmas);    
    }   
    <?php if(isset($this->request->data['BusinessOwner'])){?>
	    var profession_val = "<?php echo $this->request->data['BusinessOwner']['profession_id']?>";
	    var country_val = "<?php echo $this->request->data['BusinessOwner']['country_id']?>";
	    var state_val = "<?php echo $this->request->data['BusinessOwner']['state_id']?>";
	    var meeting_val = "<?php echo $this->request->data['BusinessOwner']['meeting_time']?>";  
    <?php }else{?>
	    var profession_val = "";
	    var country_val = "";
	    var state_val = "";
	    var city_val = "";
	    var meeting_val = "";
    <?php }?>   
</script>
<script type="text/javascript">
    $(document).ready(function(){
        var professionUrl = $('#professionUrl').val();
        category_val = $('#category').val();
        //alert(category_val);
        prof_val = $('#profession').val();
        //alert(prof_val);
        ajaxChange(professionUrl, category_val);
    });
    
      function ajaxChange(professionUrl,categoryId) {
        var professionId = '<?php echo (!empty($professionId)) ? $professionId : ''?>';
        //alert(professionId);
        var divUpdate = 'professionDiv';
        if (categoryId!= '') {
            $.ajax({
                'type': 'post',
                'data': {'categoryId': categoryId,'professionId' : professionId},
                'url': professionUrl,
                success: function (msg) {
                    //console.log(msg);
                    $('#' + divUpdate).html(msg);
                }
            });
        }
        if (categoryId == '') {
            $('#professionDiv').html("<select id='profession' class='form-control' name='data[businessOwner][profession_id]'><option value=''>Select Profession</option></select>");
        }
    }
</script>
