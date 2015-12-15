<?php $this->Paginator->options(array('update' => '#ajaxTableContent','evalScripts' => true)); ?>
<?php echo $this->Paginator->options(array('url' => array('action'=>'sentMessages', $listType, "perpage"=>$perpage,"search"=>$search,'sort'=> $this->Session->read('sort'),'direction'=> $this->Session->read('direction'))));?>
<?php $isArchive = ($listType=="archive") ? 1 : 0;?>
<?php $actionUrl = 'messages/popupFunction'; ?>
<table class="col-md-12 table-bordered table-striped table-condensed no-more-tables cf data_table">
	<thead class="cf">
		<tr>
			<th width="1%"><input type="checkbox" id="checkallarchive"></th>
			<th width="30%">To </th>
			<th><?php echo $this->Paginator->sort('Message.subject', 'Subject'); ?></th>
			<th><?php echo $this->Paginator->sort('Message.created', 'Date'); ?></th>
			<th width="5%"></th>

		</tr>
	</thead>
	<tbody>
		<?php 
	      if(!empty($sentData)) {
	        foreach($sentData as $data){
            $messageId = $data['Message']['id'];
          ?>
	        <?php $readClass = ($data['Message']['is_read']==1) ? "read" : "unread";?>			        
	        <?php $attachmentData = $messageComponent->messageAttachment($data['Message']['id']);?>
			        <td class="<?php echo $readClass;?>"><input name="messageIds[]" type="checkbox" class="checkthis" id="message_<?php echo $data['Message']['id']?>" value="<?php echo $data['Message']['id']?>"></td>
        				<td class="<?php echo $readClass;?> tdCursor" onclick="showMessageDetail('<?php echo $data['Message']['id']?>')">
        					<?php echo $names = (strlen($data['Message']['recipient_users'])>30) ? substr($data['Message']['recipient_users'],0,30)."..." : $data['Message']['recipient_users'];?>
        				</td>
                		<td class="<?php echo $readClass;?> tdCursor" onclick="showMessageDetail('<?php echo $data['Message']['id']?>')">
                			<?php $subject = (strlen($data['Message']['subject'])>60) ? substr($data['Message']['subject'],0,60)."..." : $data['Message']['subject'];?>
                			<?php if(count($attachmentData)>0){?>
                			<i class="fa fa-paperclip"></i> <?php echo $subject;?>
                		<?php }else{?>
                			<?php echo $subject;?>
                		<?php }?>
                		</td>
                		<td class="<?php echo $readClass;?> tdCursor" onclick="showMessageDetail('<?php echo $data['Message']['id']?>')"><?php echo date('M d, Y ',strtotime($data['Message']['created']))?></td>
                   		<td>
                   		<?php if($listType=="archive"){?>
	                   		<a class="search_table_bg" href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="popUp('<?php echo $actionUrl;?>', '<?php echo $messageId; ?>','<?php echo 'messages'; ?>', '<?php echo 'removeMessage/sent/'.$isArchive; ?>', '<?php echo 'messages/sentMessages/archive'; ?>')" escape = false>
                          <span class="glyphicon glyphicon-trash table_search_icon" title="Delete"></span>
                        </a>
                   		<?php }else{?>
	                   		<a class="search_table_bg" href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="popUp('<?php echo $actionUrl;?>', '<?php echo $messageId; ?>','<?php echo 'messages'; ?>', '<?php echo 'removeMessage/sent/'.$isArchive; ?>', '<?php echo 'messages/sentMessages/sentmessage'; ?>')" escape = false>
                          <span class="fa fa-file-archive-o table_search_icon" title="Archive"></span>
                        </a>
		                <?php }?> 
                   		<div class="clearfix"></div>              
                     	</td>
        			</tr>
			      <?php }?>
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
<?php $archive = ($isArchive) ? array('archive') : array();?>
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
	
	$( "#searching" ).keyup(function() {
		searchval 	= $('#search_field_val').val($(this).val());
	});
	backurl 	= $('#saveurl_field_val').val('<?php echo $this->Paginator->url($archive)?>');
	
});
</script>
