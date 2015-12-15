<?php $this->Paginator->options(array('update' => '#ajaxTableContent','evalScripts' => true)); ?>
<?php $actionUrl = 'referrals/popupFunction'; ?>
<?php echo $this->Paginator->options(array('url' => array('action'=>'received',"perpage"=>$perpage,"search"=>$search,'sort'=> $this->Session->read('sort'),'direction'=> $this->Session->read('direction'))));
?>
    <div class="col-md-12 col-sm-12 padleftzero"><div class="business_referrals">You’ve received $<?php echo isset($value) ? CakeNumber::format($value) : '0' ;?> in business referrals. Let's get hopping!</div></div>
<table class="col-md-12 table-bordered table-striped table-condensed no-more-tables cf data_table blockClass">
                    <thead class="cf">
                        <tr>
                            <th width="1%"><input type="checkbox" id="checkallarchive"></th>
                            <th width="15%"><?php echo $this->Paginator->sort('ReceivedReferral.first_name', 'Referral'); ?> </th>
                            <th width="19%"><?php echo $this->Paginator->sort('BusinessOwners.fname', 'From'); ?></th>
                            <th width="15%"><?php echo $this->Paginator->sort('ReceivedReferral.created', 'Date'); ?></th>
                            <th width="12%"><?php echo $this->Paginator->sort('ReceivedReferral.referral_status', 'Status'); ?></th>
                            <th width="16%"><?php echo $this->Paginator->sort('ReceivedReferral.monetary_value', 'Value'); ?></th>
                            <th width="26%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(!empty($referralData)) {
                            foreach($referralData as $data) :
                                $referralId = $data['ReceivedReferral']['id'];?>
                            <?php $readClass = ($data['ReceivedReferral']['is_read']==1) ? "read" : "unread";?>
                            <tr>
                                <td class="<?php echo $readClass;?>">
                                    <input name="referralIds[]" type="checkbox" class="checkthis" id="referral_<?php echo $data['ReceivedReferral']['id']?>" value="<?php echo $data['ReceivedReferral']['id']?>">
                                </td>
                                <td onclick="showReferralDetail('<?php echo $data['ReceivedReferral']['id']?>','referralDetail')" class="<?php echo $readClass;?> tdCursor">
                                <?php if (isset($data['ReceivedReferral']['files']) && !empty($data['ReceivedReferral']['files'])) {echo '<i class="fa fa-paperclip"></i>';}?>
                                    <?php echo ucfirst($data['ReceivedReferral']['first_name']).' '.ucfirst($data['ReceivedReferral']['last_name']);?>
                                </td>
                                <td onclick="showReferralDetail('<?php echo $data['ReceivedReferral']['id']?>','referralDetail')"  class="<?php echo $readClass;?> tdCursor"><?php echo ucfirst($data['BusinessOwners']['fname']).' '.ucfirst($data['BusinessOwners']['lname']);?></td>
                                <td onclick="showReferralDetail('<?php echo $data['ReceivedReferral']['id']?>','referralDetail')"  class="<?php echo $readClass;?> tdCursor"><?php echo date('M d, Y ',strtotime($data['ReceivedReferral']['created']))?></td>
                                <td onclick="showReferralDetail('<?php echo $data['ReceivedReferral']['id']?>','referralDetail')"  class="<?php echo $readClass;?> tdCursor"><?php echo ucfirst($data['ReceivedReferral']['referral_status']); ?></td>
                                <td onclick="showReferralDetail('<?php echo $data['ReceivedReferral']['id']?>','referralDetail')"  class="<?php echo $readClass;?> tdCursor"><?php echo !empty($data['ReceivedReferral']['monetary_value']) ? '$'.CakeNumber::format($data['ReceivedReferral']['monetary_value']): '$0'; ?></td>
                                <td class="<?php echo $readClass;?>">
                                <?php if($data['ReceivedReferral']['referral_status'] == 'success' && empty($data['ReceivedReferral']['rating_status'])) { ?>
                                <a title="Request Review" href="javascript:void(0);" class="search_table_bg" onclick="requestRating('<?php echo $data['ReceivedReferral']['id']?>','referralUpdate')"><span class="fa fa-star-o table_search_icon"></span>
                                    </a>
                                <?php } else if($data['ReceivedReferral']['referral_status'] == 'success' && !empty($data['ReceivedReferral']['rating_status'])){ ?>
                                    <a title="Review Received" href="javascript:void(0);" class="search_table_bg">
                                        <span class="fa fa-star table_search_icon"></span>
                                    </a>
                                <?php } else { ?>
                                <a title="Request Review" href="javascript:void(0);" class="search_table_bg2">
                                    <span class="fa fa-star-o table_search_icon"></span>
                                </a>
                                <?php } ?>                                            
                                    <a title="Edit" href="javascript:void(0);" class="search_table_bg" onclick="showReferralDetail('<?php echo $data['ReceivedReferral']['id']?>','referralUpdate')"><span class="glyphicon glyphicon-pencil table_search_icon"></span>
                                    </a>
                                    <a class="search_table_bg" href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="popUp('<?php echo $actionUrl;?>', '<?php echo $referralId; ?>','<?php echo 'referrals'; ?>', '<?php echo 'removeReferral/received'; ?>', '<?php echo 'referrals/received'; ?>')" escape = false>
                                        <span class="fa fa-file-archive-o table_search_icon" title="Archive"></span>
                                    </a>
                                    <?php
                                    echo $this->Html->link(
                                                           '<i class="fa fa-user-plus referContact"></i>',
                                                           array('controller' => 'contacts', 'action' => 'addReferralContact', $data['ReceivedReferral']['id']),
                                                           array('escape' => FALSE, 'class' => 'search_table_bg', 'title' => 'Add to contact'));
                                    ?>
                                    <div class="clearfix"></div>                 
                                </td>
                            </tr>
                        <?php endforeach;?>
                        <?php } else {
                            echo "<tr><td colspan='7' style='text-align:center'>No results found</td></tr>";
                        }?>

                    </tbody>
                </table>

<div class="clearfix">&nbsp;</div>
<?php if($this->Paginator->numbers()) {?>
<ul class="pagination pagination_table pagination-sm pull-right">
  <li>
    <?php echo $this->Paginator->prev(__('«',true)); ?>
  </li>
  <li><?php echo $this->Paginator->numbers(array('separator'=>false)); ?> </li>

  <li><?php echo $this->Paginator->next(__('»',true)); ?></li>
</ul>
<?php }?>
<?php echo $this->Js->writeBuffer(); ?>
<script>
$( document ).ready(function() {
	$( "#checkallarchive" ).change(function() {		
		if($("#checkallarchive").prop("checked")) {
			$(".checkthis").prop('checked', true);
		}else{
			$(".checkthis").prop('checked', false);
		}
	});

	$( ".checkthis" ).change(function() {
		if($(this).prop("checked")) {
			$("#mass_action").val('');
		}else{
			$("#checkallarchive").prop('checked', false);
		}
	});

	del_cnt = $('input.checkthis:checked').length;
	if(del_cnt>0){
		$("#bulkapplysubmit").show();
		$("#bulkdeleteblank").hide();
	}else{
		$('#mass_action').val('');
		$("#bulkapplysubmit").hide();
		$("#bulkdeleteblank").show();
	}
	$( "#searching" ).keyup(function() {
		searchval 	= $('#search_field_val').val($(this).val());
	});
	backurl 	= $('#saveurl_field_val').val('<?php echo $this->Paginator->url()?>');
	
});
</script>
