<?php $this->Paginator->options(array('update' => '#ajaxTableContent','evalScripts' => true)); ?>
<?php $actionUrl = 'referrals/popupFunction'; ?>
<?php echo $this->Paginator->options(array('url' => array('action'=>'sent',"perpage"=>$perpage,"search"=>$search,'sort'=> $this->Session->read('sort'),'direction'=> $this->Session->read('direction'))));
?>
<table class="col-md-12 table-bordered table-striped table-condensed no-more-tables cf data_table">
  <thead class="cf">
    <tr>
      <th width="1%"><input type="checkbox" id="checkallarchive"></th>
      <th width="26%"><?php echo $this->Paginator->sort('SendReferral.first_name', 'Referral'); ?> </th>
      <th><?php echo $this->Paginator->sort('BusinessOwners.fname', 'Sent To'); ?></th>
      <th><?php echo $this->Paginator->sort('SendReferral.created', 'Date'); ?></th>
      <th width="10%"></th>
    </tr>
  </thead>
  <tbody>
    <?php 
    if(!empty($referralData)) {
      foreach($referralData as $data) :
        $referralId = $data['SendReferral']['id'];?>
      <tr>
        <td>
              <input name="referralIds[]" type="checkbox" class="checkthis" id="referral_<?php echo $data['SendReferral']['id']?>" value="<?php echo $data['SendReferral']['id']?>">
        </td>
        <td onclick="showReferralDetail('<?php echo $data['SendReferral']['id']?>')" class="tdCursor">
        <?php if (isset($data['SendReferral']['files']) && !empty($data['SendReferral']['files'])) {echo '<i class="fa fa-paperclip"></i>';}?>
          <?php echo ucfirst($data['SendReferral']['first_name']).' '.ucfirst($data['SendReferral']['last_name']);?>
        </td>
        <td onclick="showReferralDetail('<?php echo $data['SendReferral']['id']?>')" class="tdCursor"><?php echo ucfirst($data['BusinessOwners']['fname']).' '.ucfirst($data['BusinessOwners']['lname'])?></td>
        <td onclick="showReferralDetail('<?php echo $data['SendReferral']['id']?>')" class="tdCursor"><?php echo date('M d, Y ',strtotime($data['SendReferral']['created']))?></td>
        <td> 
              <?php /*echo $this->Js->link(
		                    '<span title="Delete" class="glyphicon glyphicon-trash table_search_icon"></span>', 
		                    '/Referrals/removeReferral/sent/'.$data['SendReferral']['id'], 
		                    array('target' => '_self', 'update' => '#ajaxTableContent', 'escape' => false, 'confirm' => 'Do you want to delete the referral(s)?','class'=>'search_table_bg','complete' => 'showmessage()')); */
		                    ?>
          <a class="search_table_bg" href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="popUp('<?php echo $actionUrl;?>', '<?php echo $referralId; ?>','<?php echo 'referrals'; ?>', '<?php echo 'removeReferral/sent'; ?>', '<?php echo 'referrals/sent'; ?>')" escape = false>
            <span class="fa fa-file-archive-o table_search_icon" title="Archive"></span>
          </a>
          <div class="clearfix"></div>            
        </td>
     </tr>
   <?php endforeach;?>
   <?php } else {
     echo "<tr><td colspan='5' style='text-align:center'>No results found</td></tr>";
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
