<?php $this->Paginator->options(array('update' => '#ajaxTableContent','evalScripts' => true)); ?>
<?php $heading = ($listType=="inbox") ? "Inbox": "Inbox Archive";?>
<?php $isArchive = ($listType=="archive") ? 1 : 0;?>
<?php $actionUrl = 'messages/popupFunction'; ?>
<?php $massActionUrl = 'messages/massActionFunction'; ?>
<form action="" id="massActionForm">
<style>.next {display:block !important;}</style>
<div class="row margin_top_referral_search">
  <div class="col-md-9 col-sm-8">
   <div class="row"> 
     <div class="col-md-12">
      <div class="referrals_reviews">
        <div class="referrals_reviews_head padd-top0"> <?php echo $heading;?> </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <div class="clearfix">&nbsp;</div>
  <div class="row">      
    <div class="col-md-6 col-sm-5 col-xs-5 width_at_mob">
      <div id="imaginary_container">
        <div class="input-group   ">
          <input autocomplete="off" type="text" id="searching" name="search" class="  search-query form-control innerpage_search clearable" placeholder="Search" value="<?php echo $search;?>">
          <span class="input-group-btn">
            <button class="btn inner_pagesbtn front_search" type="button">
              <span class=" glyphicon glyphicon-search"></span>
            </button>
          </span>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-sm-7 col-xs-7 width_at_mob">
     <div class="action_bulk  ">
      
      <label class="labelSelect">
          <select class="selectNew form-control seclect_value seclect_bulk" id="mass_action" name="mass_action">
            <option value="">More</option>
                <option value="massread">Mark As Read</option>
                <option value="massunread">Mark As Unread</option>
                <?php if($listType!="inbox") {?>
                <option value="massunarchive">Un Archive</option>
                <?php }?>
                <option value="massdelete"><?php echo ($listType=="inbox") ? "Archive": "Delete";?></option>
          </select>
      </label>
      </div>
      <!--<a class="apply" href="javascript:void(0);" id="">Apply</a>      -->
      <div class="select_box pull-right">
        <label class="labelSelect">
        <?php echo $this->Form->input('perpage', array('id'=>'perpage','type'=>'select','options'=>Configure::read('PERPAGE'),'empty' => false,'name'=>'perpage','class'=>"selectNew form-control seclect_value",'label'=>false));?>
        </label>
      </div>
      <div class="results_pages pull-right">Results per page&nbsp;&nbsp;&nbsp;</div>
      <?php
          $this->Js->get('#perpage');
          $this->Js->event('change',
          $this->Js->request(array(
                  'controller'=>'messages',
                  'action'=>'inbox',$listType),
                  array('async'=>true,
                        'update'=>'#ajaxTableContent',
                        'dataExpression'=>true,
                        'data' => '$(\'#searching,#perpage\').serializeArray()',
                        'method'=>'post')
               )
          );
           $this->Js->get('#searching');
           $this->Js->event('keyup',
           $this->Js->request(array(
                  'controller'=>'messages',
                  'action'=>'inbox',$listType),
                     array('async'=>true,
                        'update'=>'#ajaxTableContent',
                        'dataExpression'=>true,
                        'data' => '$(\'#searching,#perpage\').serializeArray()',
                        'method'=>'post')
                    )
            );
      ?>
    </div>

  </div>
  <div class="clearfix">&nbsp;</div>
  <div id="no-more-tables">
    <!--&gt;-->
    <div id="ajaxTableContent">
      <table class="col-md-12 table-bordered table-striped table-condensed no-more-tables cf data_table">
			<thead class="cf">
				<tr>
          			<th width="1%"><input type="checkbox" id="checkallarchive"></th>
					<th width="21%"><?php echo $this->Paginator->sort('BusinessOwners.fname', 'From'); ?></th>
					<th width="26%"><?php echo $this->Paginator->sort('Message.subject', 'Subject'); ?></th>
					<th width="26%"><?php echo $this->Paginator->sort('MessageRecipient.created', 'Date'); ?></th>
					<th width="10%"></th>
				</tr>
			</thead>
			<tbody>
				<?php 
			      if(!empty($inboxData)) {
			        foreach($inboxData as $data){
                $userid = $data['Message']['written_by_user'];
                $messageId = $data['MessageRecipient']['id'];
              ?>
			        <?php $readClass = ($data['MessageRecipient']['is_read']==1) ? "read" : "unread";?>
			        <?php $attachmentData = $messageComponent->messageAttachment($data['Message']['id']);?>
			        <tr>
				        <td class="<?php echo $readClass;?>">
				          <input name="messageIds[]" type="checkbox" class="checkthis" id="message_<?php echo $data['MessageRecipient']['id']?>" value="<?php echo $data['MessageRecipient']['id']?>">
				        </td>
        				<td class="<?php echo $readClass;?> tdCursor" onclick="showMessageDetail('<?php echo $data['Message']['id']?>')">
        					<?php echo ucwords($data['BusinessOwners']['fname'].' '.$data['BusinessOwners']['lname']);?>
        				</td>
                <td class="<?php echo $readClass;?> tdCursor" onclick="showMessageDetail('<?php echo $data['Message']['id']?>')">
                  <?php $subject = (strlen($data['Message']['subject']>60)) ? substr($data['Message']['subject'],0,60)."..." : $data['Message']['subject'];
                   if(count($attachmentData)>0) {
                    //echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-paperclip')).$subject,array('controller' => 'messages','action' => 'viewMessage', $data['Message']['id']),array('escape' => false));
                    ?>
                   <i class="fa fa-paperclip"></i> <?php echo $subject;?>
                   <?php } else { ?>
                      <?php echo $subject;?>
                    <?php //echo $this->Html->link($subject,array('controller' => 'messages','action' => 'viewMessage', $data['Message']['id']),array('escape' => false));
                    }?>
                </td>
                <td class="<?php echo $readClass;?> tdCursor" onclick="showMessageDetail('<?php echo $data['Message']['id']?>')"><?php echo date('M d, Y ',strtotime($data['MessageRecipient']['created']))?></td>
                   		<td>
                   		<?php if($listType=="archive"){?>
	                   		<?php /*echo $this->Js->link(
			                    '<span title="Delete" class="glyphicon glyphicon-trash table_search_icon"></span>', 
			                    '/messages/removeMessage/inbox/'.$isArchive.'/'.$data['MessageRecipient']['id'], 
			                    array('target' => '_self', 'update' => '#ajaxTableContent', 'escape' => false, 'confirm' => 'Do you want to permanently delete the message(s)?','class'=>'search_table_bg','complete' => "showmessage('Message(s) has been permanently deleted.'),updateCounter()"));*/ 
			                ?>
                      <a class="search_table_bg" href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="popUp('<?php echo $actionUrl;?>', '<?php echo $messageId; ?>','<?php echo 'messages'; ?>', '<?php echo 'removeMessage/inbox/'.$isArchive; ?>', '<?php echo 'messages/inbox/archive'; ?>')" escape = false>
                        <span class="glyphicon glyphicon-trash table_search_icon" title="Delete"></span>
                      </a>
                   		<?php }else{?>
	                   		<?php /*echo $this->Js->link(
			                    '<span title="Delete" class="glyphicon glyphicon-trash table_search_icon"></span>', 
			                    '/messages/removeMessage/inbox/'.$isArchive.'/'.$data['MessageRecipient']['id'], 
			                    array('target' => '_self', 'update' => '#ajaxTableContent', 'escape' => false, 'confirm' => 'Do you want to delete the message(s)?','class'=>'search_table_bg','complete' => "showmessage('Message(s) has been moved to inbox archive successfully.'),updateCounter()")); */
			                ?>
                      <a class="search_table_bg" href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="popUp('<?php echo $actionUrl;?>', '<?php echo $messageId; ?>','<?php echo 'messages'; ?>', '<?php echo 'removeMessage/inbox/'.$isArchive; ?>', '<?php echo 'messages/inbox/inbox'; ?>')" escape = false>
                        <span class="fa fa-file-archive-o table_search_icon" title="Archive"></span>
                      </a>
		                <?php }?>  
                   		<div class="clearfix"></div>              
                     	</td>
        			</tr>
			      <?php }?>
			      <?php } else {
			         echo "<tr><td colspan='5' style='text-align:center'>No record found</td></tr>";
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
    </div>
  </div>
</div>
</form>
<?php if($listType=="inbox"){?>
	<?php echo $this->element("Front/loginSidebar",array('tabpage' => 'messageinbox'));?>
<?php }else{?>
	<?php echo $this->element("Front/loginSidebar",array('tabpage' => 'inboxarchive'));?>
<?php }?>
</div>
<?php echo $this->element('Front/bottom_ads');?>
<form id="messageDetailPage" action="">
<?php
    $archive = ($isArchive) ? array('archive') : array();
    echo $this->Form->hidden('saveurl',array('id'=>'saveurl_field_val','value'=>$this->Paginator->url($archive)));
    echo $this->Form->hidden('search_val',array('id'=>'search_field_val','value'=>$search));
    echo $this->Html->script('Front/all');
?>
</form>
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

	$("#mass_action").bind("change", function (event) {
		msg = "";
		actionval = $("#mass_action").val();
		del_cnt = $('input.checkthis:checked').length;
		if(del_cnt<=0 || actionval==""){
        $('#mass_action').val( $('#mass_action').prop('defaultSelected') );
        $("#myModalNoRecord").modal('show');
        return false;
		}
    if(actionval==''){
        return false;
    }
		if(actionval=='massdelete') {
			<?php if($listType=="archive"){ ?>
          massAction('<?php echo $massActionUrl; ?>', 'massActionForm', 'messages', '<?php echo "bulkMessageAction/inbox/".$isArchive; ?>', '<?php echo "messages/inbox/".$listType; ?>');
//      var _confirm = confirm("Do you want to permanently delete the messages(s)?");			
//			msg = "Message(s) has been permanently deleted.";
			<?php }else{?>
          massAction('<?php echo $massActionUrl; ?>', 'massActionForm', 'messages', '<?php echo "bulkMessageAction/inbox/".$isArchive; ?>', '<?php echo "messages/inbox/".$listType; ?>');
			//var _confirm = confirm("Do you want to delete the messages(s)?");			
			//msg = "Message(s) has been moved to inbox archive successfully.";
			<?php }?>
		}else if(actionval=='massread'){
			msg = "Selected message(s) have been marked as read.";
      $.ajax({
        complete:function (XMLHttpRequest, textStatus) {showmessage(msg),updateCounter()}, 
        data:$("#massActionForm").serialize(), 
        dataType:"html", 
        success:function (data, textStatus) {$("#ajaxTableContent").html(data);}, 
        target:"_self", 
        type:"post", 
        url:"<?php echo Router::url(array('action'=>'bulkMessageAction','inbox',$isArchive))?>"
      });
      return false;
		}else if(actionval=='massunread'){
			msg = "Selected message(s) have been marked as unread.";
      $.ajax({
        complete:function (XMLHttpRequest, textStatus) {showmessage(msg),updateCounter()}, 
        data:$("#massActionForm").serialize(), 
        dataType:"html", 
        success:function (data, textStatus) {$("#ajaxTableContent").html(data);}, 
        target:"_self", 
        type:"post", 
        url:"<?php echo Router::url(array('action'=>'bulkMessageAction','inbox',$isArchive))?>"
      });
      return false;
	}else if(actionval=='massunarchive'){
			msg = "Selected message(s) have been moved to archive.";
		      $.ajax({
		        complete:function (XMLHttpRequest, textStatus) {showmessage(msg),updateCounter()}, 
		        data:$("#massActionForm").serialize(), 
		        dataType:"html", 
		        success:function (data, textStatus) {$("#ajaxTableContent").html(data);}, 
		        target:"_self", 
		        type:"post", 
		        url:"<?php echo Router::url(array('action'=>'bulkMessageAction','inbox',$isArchive))?>"
		      });
		      return false;
		}
	});

	$( "#searching" ).keyup(function() {
		searchval 	= $('#search_field_val').val($(this).val());
	});	

	$(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
	});
	
});
function showmessage(msg){
	$( "nav.navbar-fixed-top" ).after( '<div class="container topspace col-md-12 "><div id="flashMessage" class="alert alert-success">'+msg+'</div></div>' );
	$('#searching').val('');
	$('#mass_action').val('');
	$('html, body').animate({scrollTop: '0px'}, 300);
	setTimeout(function(){
		$("#flashMessage").html("");
		$('#flashMessage').slideUp();
	}, 5000);
}
function showMessageDetail(listId){
	backurl 	= $('#saveurl_field_val').val();
	searchval 	= $('#search_field_val').val();
	historyurl  =  backurl+'/search:'+searchval;
	var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
	historyurl  =  Base64.encode(historyurl);
	redirecturl  =  '<?php echo Router::url(array('controller'=>'messages','action'=>'viewMessage'));?>/'+listId+'/'+historyurl;
	window.location.href = redirecturl;
}
function updateCounter(){
	listtype = '<?php echo $listType?>';
	if(listtype=="inbox"){
		$("#message_counter_element").show();
		$.ajax({		 
			data:"loginUserId='<?php echo $loginUserId?>'", 
			dataType:"html", 
			success:function (data, textStatus) {$("#message_counter_element").html(data);if(data==0){$("#message_counter_element").hide();}},
			type:"post", 
			url:"<?php echo Router::url(array('action'=>'updateCounter','inbox'))?>"
		});
	}
}
</script>
