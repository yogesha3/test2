<?php $this->Paginator->options(array('update' => '#ajaxTableContent','evalScripts' => true)); ?>
<?php $actionUrl = 'referrals/popupFunction'; ?>
<?php echo $this->Paginator->options(array('url' => array('action'=>'archive',$archiveType,"perpage"=>$perpage,"search"=>$search,'sort'=> $this->Session->read('sort'),'direction'=> $this->Session->read('direction'))));
?>
<table class="col-md-12 table-bordered table-striped table-condensed no-more-tables cf data_table">
        <thead class="cf">
        <?php if($archiveType=="sent"){?>
          <tr>
            <th width="1%"><input type="checkbox" id="checkallarchive"></th>
            <th width="26%"><?php echo $this->Paginator->sort('first_name', 'Referral'); ?> </th>
            <th width="26%"><?php echo $this->Paginator->sort('BusinessOwners.fname', 'Sent To'); ?></th>
            <th width="26%"><?php echo $this->Paginator->sort('created', 'Date'); ?></th>
            <th width="10%"></th>
          </tr>
          <?php }else{?>
          <tr>
            <th width="1%"><input type="checkbox" id="checkallarchive"></th>
            <th width="26%"><?php echo $this->Paginator->sort('first_name', 'Referral'); ?> </th>
            <th width="22%"><?php echo $this->Paginator->sort('BusinessOwners.fname', 'From'); ?></th>
            <th width="20%"><?php echo $this->Paginator->sort('created', 'Date'); ?></th>
            <th width="13%"><?php echo $this->Paginator->sort('ReceivedReferral.referral_status', 'Status'); ?></th>
      		<th width="18%"><?php echo $this->Paginator->sort('ReceivedReferral.monetary_value', 'Value'); ?></th>
            <th width="10%"></th>
          </tr>
          <?php }?>
        </thead>
        <tbody>
        <?php
	        if(!empty($archiveData)) {
	          if($archiveType=="sent"){
	          	foreach($archiveData as $data) :
                $referralId = $data[$model]['id'];?>
		          <tr>
                <td>
                  <input name="referralIds[]" type="checkbox" class="checkthis" id="referral_<?php echo $data[$model]['id']?>" value="<?php echo $data[$model]['id']?>">
                </td>
		            <td onclick="showReferralDetail('<?php echo $data[$model]['id']?>')" class="tdCursor">
		            <?php if (isset($data[$model]['files']) && !empty($data[$model]['files'])) {echo '<i class="fa fa-paperclip"></i>';}?>
		              <?php echo ucfirst($data[$model]['first_name']).' '.ucfirst($data[$model]['last_name']);?>
		            </td>
		            <td onclick="showReferralDetail('<?php echo $data[$model]['id']?>')" class="tdCursor"><?php echo $data['BusinessOwners']['fname'].' '.$data['BusinessOwners']['lname']?></td>
		            <td onclick="showReferralDetail('<?php echo $data[$model]['id']?>')" class="tdCursor"><?php echo date('M d, Y ',strtotime($data[$model]['created']))?></td>
		            <td>
		              <a class="search_table_bg" href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="popUp('<?php echo $actionUrl;?>', '<?php echo $referralId; ?>','<?php echo 'referrals'; ?>', '<?php echo 'removeArchive/sent'; ?>', '<?php echo 'referrals/archive/sent'; ?>')" escape = false>
                    <span class="glyphicon glyphicon-trash table_search_icon" title="Delete"></span>
                  </a>
		              <div class="clearfix"></div>              
		            </td>
		          </tr>
	        	<?php endforeach;
	        }else{
	        	foreach($archiveData as $data) :
              $referralId = $data[$model]['id'];?>
        		<tr>
		            <td>
                  <input name="referralIds[]" type="checkbox" class="checkthis" id="referral_<?php echo $data[$model]['id']?>" value="<?php echo $data[$model]['id']?>">
                </td>
		            <td onclick="showReferralDetail('<?php echo $data[$model]['id']?>')" class="tdCursor">
		              <?php echo ucfirst($data[$model]['first_name']).' '.ucfirst($data[$model]['last_name']);?>
		            </td>
		            <td onclick="showReferralDetail('<?php echo $data[$model]['id']?>')" class="tdCursor"><?php echo ucfirst($data['BusinessOwners']['fname']).' '.ucfirst($data['BusinessOwners']['lname']);?></td>
		            <td onclick="showReferralDetail('<?php echo $data[$model]['id']?>')" class="tdCursor"><?php echo date('M d, Y ',strtotime($data[$model]['created']))?></td>
		            <td onclick="showReferralDetail('<?php echo $data[$model]['id']?>')" class="tdCursor"><?php echo ucfirst($data[$model]['referral_status']); ?></td>
		            <td onclick="showReferralDetail('<?php echo $data[$model]['id']?>')" class="tdCursor"><?php echo !empty($data[$model]['monetary_value']) ? '$'.$data[$model]['monetary_value']: '$0'; ?></td>
		            <td>
                  <a class="search_table_bg" href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="popUp('<?php echo $actionUrl;?>', '<?php echo $referralId; ?>','<?php echo 'referrals'; ?>', '<?php echo 'removeArchive/received'; ?>', '<?php echo 'referrals/archive/received'; ?>')" escape = false>
                    <span class="glyphicon glyphicon-trash table_search_icon" title="Delete"></span>
                  </a>
		              <div class="clearfix"></div>              
		            </td>
		        </tr>
				<?php endforeach;	            
	          } // endif ?>	          
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
	backurl 	= $('#saveurl_field_val').val('<?php echo $this->Paginator->url(array($archiveType))?>');
	
});
</script>
