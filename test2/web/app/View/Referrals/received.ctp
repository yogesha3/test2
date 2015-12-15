<?php $this->Paginator->options(array('update' => '#ajaxTableContent','evalScripts' => true)); ?>
<?php $actionUrl = 'referrals/popupFunction'; ?>
<?php $massActionUrl = 'referrals/massActionFunction'; ?>
<form action="" id='massForm' >
<div class="row margin_top_referral_search">
<div class="col-md-9 col-sm-8">
    <div class="row"> 
        <div class="col-md-8">
            <div class="referrals_reviews">
                <div class="referrals_reviews_head padd-top0">Received</div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-4 text-right"><a href="<?php echo Router::url(array('controller'=>'referrals','action'=>'downloadReferralList'),true)?>" class="back_btn_new pull-right text-center padauto" ><i class="fa fa-download"></i> Download All</a></div>
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
            <?php //echo $this->Js->submit('Apply',array('url'=>'/Referrals/bulkReferralAction/received','target' => '_self', 'update' => '#ajaxTableContent', 'escape' => false, 'div' => false, 'confirm' => 'Do you want to delete the referral(s)?','class'=>'apply','complete' => 'showmessage()','id'=>'bulkapplysubmit','style'=>'padding:0'));?>
            <!--<a class="apply" href="javascript:void(0);" id="bulkdeleteblank">Apply</a>-->
            <div class="select_box pull-right">
                <label class="labelSelect">
                    <?php echo $this->Form->input('perpage', array('id'=>'perpage','type'=>'select','options'=>Configure::read('PERPAGE'),'empty' => false,'name'=>'perpage','class'=>"selectNew form-control seclect_value",'label'=>false,'div'=>false));?>
                </label>
            </div><div class="results_pages pull-right" >Results per page &nbsp;&nbsp;&nbsp;</div>
            <?php
            $this->Js->get('#perpage');
            $this->Js->event('change',
                $this->Js->request(array(
                    'controller'=>'referrals',
                    'action'=>'received'),
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
                    'action'=>'received'),
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
                <div class="col-md-12 col-sm-12 padleftzero"><div class="business_referrals">You’ve received $<?php echo isset($value) ? CakeNumber::format($value) : '0' ;?> in business referrals.  Let's get hopping!</div></div>
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
                                    <!--fa fa-user-plus-->
                                    <div class="clearfix"></div>                 
                                </td>
                            </tr>
                        <?php endforeach;?>
                        <?php } else {
                            echo "<tr><td colspan='7' style='text-align:center'>No record found</td></tr>";
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
<?php echo $this->element("Front/loginSidebar",array('tabpage' => 'referralsReceived'));?>
</div>
<?php echo $this->element('Front/bottom_ads');?>
<form id="referralDetailPage" action="">
<?php
echo $this->Form->hidden('saveurl',array('id'=>'saveurl_field_val','value'=>$this->Paginator->url()));
echo $this->Form->hidden('search_val',array('id'=>'search_field_val','value'=>$search));
if (isset($isReferralExist)) {
    echo $this->Form->hidden('referralContactId', array('id' => 'referralContactId', 'value' => $referralContactId));
} else {
	$referralContactId = null;
}
echo $this->Html->script('Front/all');
?> 
</form>
<div data-backdrop="static" data-keyboard="false" id="contactModal" class="modal fade modal-sm" tabindex="-1" data-width='auto'>
     <div class="modal-content">
        <div class="modal-header" style="border:none">
        </div>
        <div class="modal-body popup_body">
            <h2>
              Contact already exists.<br/> Do you want to override the contact?
            <div class="modal_text"></div>
            
        </div>
        <div class="modal-footer popup_footer text-center">
            <?php echo $this->Form->create('ReferralContact', array('id' => 'referralContactForm', 'url' => array('controller' => 'contacts', 'action' => 'addReferralContact', $referralContactId))); ?>
            <button type="button" class="btn btn-primary popup_cancel" data-dismiss="modal"><span class="pull-left">Cancel</span>  <i class="fa fa-close pull-right"></i></button>
            <button type="submit" class="btn btn-default ok_btn"><span class="pull-left"  data-dismiss="modal">Ok</span>  <i class="fa fa-check pull-right"></i></button>
            <?php $this->Form->end(); ?>
        </div>
    </div>
</div>
<script>
//var msgLoader = '<?php echo $this->Html->image('spinner.gif', array('height' => '20px', 'width' => '20px'));?>';
var msgLoader = '<div class="blockClass_comment2"><div id="rays"><?php echo $this->Html->image('loding-logo.png',array('id'=>'referralStatusWait','class'=>'center-block img-responsive'));?></div></div>';
$( document ).ready(function() {
    var referralContactId = $('#referralContactId').val();
    if (referralContactId != null) {
        $('#contactModal').modal('show');
    }
    
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
        if ($(this).val()=="") {
            $("#bulkapplysubmit").hide();
            $("#bulkdeleteblank").show();
        } else {
            del_cnt = $('input.checkthis:checked').length;
            if(del_cnt>0){
                $("#bulkdeleteblank").hide();
                massAction('<?php echo $massActionUrl; ?>', 'massForm', 'referrals', 'bulkReferralAction/received', 'referrals/received');
            } else {
                $('#mass_action').val( $('#mass_action').prop('defaultSelected') );
                $("#myModalNoRecord").modal('show');
            }
        }
    });

    $( "#searching" ).keyup(function() {
        searchval	= $('#search_field_val').val($(this).val());
    });

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

});
function requestRating(rid) {
    $("body").block({ message: msgLoader });
    $.ajax({
        data :{referral : rid},
        type: "POST",
        url : 'requestRating',
        success : function (data) {
            var jsonData = JSON.parse(data);
            $("body").unblock();
            if(jsonData.code == 200) {
                $( "nav.navbar-fixed-top" ).after( '<div class="container topspace col-md-12 "><div id="flashMessage2" class="alert alert-success"><button data-dismiss="alert" class="close"> × </button>Your review request has been sent successfully</div></div>' );
            } else if(jsonData.code == 201){
                $( "nav.navbar-fixed-top" ).after( '<div class="container topspace col-md-12 "><div id="flashMessage2" class="alert alert-warning"><button data-dismiss="alert" class="close"> × </button>You have received review from this referral.</div></div>' );
            } else {
                $( "nav.navbar-fixed-top" ).after( '<div class="container topspace col-md-12 "><div id="flashMessage2" class="alert alert-danger"><button data-dismiss="alert" class="close"> × </button>Invalid request</div></div>' );
            }           
            $('html, body').animate({scrollTop: '0px'}, 300);
            setTimeout(function(){
            $("#flashMessage2").html("");
            $('#flashMessage2').slideUp();
            }, 5000);
        }

    });
}
function showmessage(){
$( "nav.navbar-fixed-top" ).after( '<div class="container topspace col-md-12 "><div id="flashMessage" class="alert alert-success">Referral(s) has been moved to archive successfully.</div></div>' );
$('#searching').val('');
$('#mass_action').val('');
$('html, body').animate({scrollTop: '0px'}, 300);
setTimeout(function(){
$("#flashMessage").html("");
$('#flashMessage').slideUp();
}, 5000);
}
function showReferralDetail(listId,redirectTo){
backurl 	= $('#saveurl_field_val').val();
searchval 	= $('#search_field_val').val();
historyurl  =  backurl+'/search:'+searchval;	
var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
historyurl  =  Base64.encode(historyurl);
if(redirectTo=="referralDetail"){
redirecturl  =  '<?php echo Router::url(array('controller'=>'referrals','action'=>'referralDetails','received'));?>/'+listId+'/'+historyurl;
}else{
redirecturl  =  '<?php echo Router::url(array('controller'=>'referrals','action'=>'referralUpdate'));?>/'+listId+'/'+historyurl;
}
window.location.href = redirecturl;
}
</script>
