<?php $this->Paginator->options(array('update' => '#ajaxTableContent','evalScripts' => true)); ?>
<?php $actionUrl = 'referrals/popupFunction'; ?>
<?php $massActionUrl = 'referrals/massActionFunction'; ?>
<form action="" id="massForm">
<style>.next {display:block !important;}</style>
<div class="row margin_top_referral_search">
  <div class="col-md-9 col-sm-8">
   <div class="row"> 
     <div class="col-md-12">
      <div class="referrals_reviews">
        <div class="referrals_reviews_head padd-top0"> Sent</div>

        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <div class="clearfix">&nbsp;</div>
  <div class="row">      
    <div class="col-md-7 col-sm-5 col-xs-5 width_at_mob">
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
    <div class="col-md-5 col-sm-7 col-xs-7 width_at_mob">
     <div class="action_bulk">
        <label class="labelSelect">
          <select class="selectNew form-control seclect_value seclect_bulk" id="mass_action" name="mass_action">
            <option value="">More</option>
            <option value="massdelete">Archive</option>
          </select>
        </label>
      </div>
      <?php //echo $this->Js->submit('Apply',array('url'=>'/Referrals/bulkReferralAction/sent','target' => '_self', 'update' => '#ajaxTableContent', 'escape' => false, 'div' => false, 'confirm' => 'Do you want to delete the referral(s)?','class'=>'apply','complete' => 'showmessage()','id'=>'bulkapplysubmit','style'=>'padding:0'));?>
     <!-- <a class="apply" href="javascript:void(0);" id="bulkdeleteblank">Apply</a>-->
    
      <div class="select_box pull-right">
        <label class="labelSelect">
        <?php echo $this->Form->input('perpage', array('id'=>'perpage','type'=>'select','options'=>Configure::read('PERPAGE'),'empty' => false,'name'=>'perpage','class'=>"selectNew form-control seclect_value",'label'=>false));?>
        </label>
      </div>  <div class="results_pages pull-right" >Results per page &nbsp;&nbsp;&nbsp;</div>
      <?php
          $this->Js->get('#perpage');
          $this->Js->event('change',
          $this->Js->request(array(
                  'controller'=>'referrals',
                  'action'=>'sent'),
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
                  'controller'=>'referrals',
                  'action'=>'sent'),
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
            <th width="26%"><?php echo $this->Paginator->sort('SendReferral.first_name', 'Referral'); ?>
            </th>
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
<?php echo $this->element("Front/loginSidebar",array('tabpage' => 'referralsSent'));?>
</div>
<?php echo $this->element('Front/bottom_ads');?>
<form id="referralDetailPage" action="">
<?php echo $this->Form->hidden('saveurl',array('id'=>'saveurl_field_val','value'=>$this->Paginator->url()));
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

	$("#bulkapplysubmit").hide();
	$("#mass_action").change(function(){
       var  actionval = $($("#mass_action").val());
      //  console.log(actionval);
      if ($(this).val()=="") {
        $("#bulkapplysubmit").hide();
        $("#bulkdeleteblank").show();
      } else {
          del_cnt = $('input.checkthis:checked').length;
          if (del_cnt>0) {
              $("#bulkdeleteblank").hide();
              massAction('<?php echo $massActionUrl; ?>', 'massForm', 'referrals', 'bulkReferralAction/sent', 'referrals/sent');
          } else {
              $('#mass_action').val( $('#mass_action').prop('defaultSelected') );
              $("#myModalNoRecord").modal('show');
          }
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
function showmessage(){
	$( "nav.navbar-fixed-top" ).after( '<div class="container topspace col-md-12 "><div id="flashMessage" class="alert alert-success">Referral(s) has been moved to archive successfully.</div></div>' );
	$('html, body').animate({scrollTop: '0px'}, 300);
	$('#searching').val('');
	$('#mass_action').val('');
	setTimeout(function(){
		$("#flashMessage").html("");
		$('#flashMessage').slideUp();
	}, 5000);
}
function showReferralDetail(listId){
	backurl 	= $('#saveurl_field_val').val();
	searchval 	= $('#search_field_val').val();
	historyurl  =  backurl+'/search:'+searchval;	
	var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
	historyurl  =  Base64.encode(historyurl);
	redirecturl  =  '<?php echo Router::url(array('controller'=>'referrals','action'=>'referralDetails','sent'));?>/'+listId+'/'+historyurl;
	window.location.href = redirecturl;
}
</script>
