<style>
    .noData{
        text-align:center;
    }
</style>
<?php echo $this->Html->script('Front/all');?>
<?php $this->Paginator->options(array('update' => '#ajaxTableContent', 'evalScripts' => true)); ?>
<form action="" id="massForm">
    <div class="row margin_top_referral_search">
        <div class="col-md-9 col-sm-8">
            <div class="row"> 
                <div class="col-md-12">
                    <div class="referrals_reviews">
                        <div class="referrals_reviews_head padd-top0">Partners</div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="row">
                <div class="col-md-7 col-sm-5 col-xs-5 width_at_mob">
                    <div id="imaginary_container">
                        <div class="input-group   ">
                            <input autocomplete="off" type="text" id="searching" name="search" placeholder="Search" class="  search-query form-control innerpage_search clearable" value="<?php echo $search;?>"/>
                            <span class="input-group-btn">
                                <button type="button" class="btn inner_pagesbtn front_search">
                                    <span class=" glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-sm-7 col-xs-7 width_at_mob">
                    
                    <?php //echo $this->Js->submit('Apply', array('url' => '/Contacts/bulkAction', 'target' => '_self', 'update' => '#ajaxTableContent', 'escape' => false, 'div' => false, 'confirm' => 'Do you want to permanently delete the contact(s)?', 'class' => 'apply', 'complete' => 'showmessage()', 'id' => 'bulkapplysubmit', 'style' => 'padding:0'));?>
                    <!--<a class="apply" href="javascript:void(0);" id="bulkdeleteblank">Apply</a>-->
                    <!--<a href="#" class="apply">Apply</a>-->
                    <!--<div class="results_pages">Results per page</div>-->
                    <div class="select_box pull-right">
                      <label class="labelSelect">
                        <?php echo $this->Form->input('perpage', array('id'=>'perpage','type'=>'select','options'=>Configure::read('PERPAGE'),'empty' => false,'name'=>'perpage','class'=>"selectNew form-control seclect_value",'label'=>false));?>
                      </label>
                    </div><div class="results_pages pull-right" >Results per page &nbsp;&nbsp;&nbsp;</div>
                    <?php
                        $this->Js->get('#perpage');
                        $this->Js->event('change', $this->Js->request(array(
                                            'controller'=>'contacts',
                                            'action'=>'partnersList'
                                            ), array(
                                                'async'=>true,
                                                'update'=>'#ajaxTableContent',
                                                'dataExpression'=>true,
                                                'data' => '$(\'#searching,#perpage\').serializeArray()',
                                                'method'=>'post')
                                            )
                                        );
                        $this->Js->get('#searching');
                        $this->Js->event('keyup',$this->Js->request(array(
                                            'controller'=>'contacts',
                                            'action'=>'partnersList'
                                            ), array(
                                                'async' => true,
                                                'update'=>'#ajaxTableContent',
                                                'dataExpression'=>true,
                                                'data' => '$(\'#searching,#perpage\').serializeArray()',
                                                'method'=>'post'
                                                )
                                            )
                                        );
                    ?>
                </div>
            </div>
        <div class="clearfix">&nbsp;</div>
            <div id="no-more-tables">
                <div id="ajaxTableContent">
                    <table class="col-md-12 table-bordered table-striped table-condensed no-more-tables cf data_table word_break invite_partners_table">
                        <thead class="cf">
                            <tr >
                                <th width="20%"><?php echo $this->Paginator->sort('InvitePartner.invitee_name', 'Invitee Name'); ?></th>
                                <th width="25%"><?php echo $this->Paginator->sort('InvitePartner.invitee_email', 'Invitee Email'); ?></th>
                                <th width="15%"><?php echo $this->Paginator->sort('InvitePartner.created', 'Sent On'); ?></th>
                                <th width="10%"><?php echo $this->Paginator->sort('InvitePartner.status', 'Status'); ?></th>
                                <th width="30%"><?php echo $this->Paginator->sort('InvitePartner.referral_amount', 'Referral Amount'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                              <?php if (isset($partnersList) && !empty($partnersList)) {
                                foreach ($partnersList as $partnersList) { ?>
                                    <tr>
                                        <td class="partner_name">
                                                <?php echo ucfirst($partnersList['InvitePartner']['invitee_name']) ;?></td>
                                        <td>
                                            <?php echo $partnersList['InvitePartner']['invitee_email'] ;?></td>
                                            <td><?php echo date('M d, Y ',strtotime($partnersList['InvitePartner']['created'])) ;?>
                                        </td>
                                        <td><?php echo !empty($partnersList['InvitePartner']['status']) ? ucfirst($partnersList['InvitePartner']['status']) : 'NA' ;?>
                                        </td>
                                        <td><?php echo '$'.(!empty($partnersList['InvitePartner']['referral_amount']) ? $partnersList['InvitePartner']['referral_amount'] : '0') ;?></td>
                                       
                                    </tr>
                            <?php }
                            } else {
                            ?>
                                <tr><td colspan="5" class="noData"><?php echo isset ($noDataMsg) ? $noDataMsg : "No record found"; ?></td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="clearfix">&nbsp;</div>
                    <?php if ($this->Paginator->numbers()) { ?>
                        <ul class="pagination pagination_table pagination-sm pull-right">
                            <li><?php echo $this->Paginator->prev("&#171", array('tag' => false)); ?></li>
                            <li><?php echo $this->Paginator->numbers(array('separator' => false)); ?> </li>
                            <li><?php echo $this->Paginator->next("&#187", array('tag' => false)); ?></li>
                        </ul>
                    <?php } ?>
                    <?php echo $this->Js->writeBuffer(); ?>
                </div>
            </div>
        </div>
        <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'partnersList'));?>
    </div>
    <?php echo $this->element('Front/bottom_ads');?>
</form>
<form id="contactDetailPage" action="">
<?php echo $this->Form->hidden('saveurl',array('id'=>'saveurl_field_val','value'=>$this->Paginator->url()));?>
<?php echo $this->Form->hidden('search_val',array('id'=>'search_field_val','value'=>$search));?> 
</form>
<script>
    $( document ).ready(function() {
        $( "#checkall" ).change(function() {		
          if($("#checkall").prop("checked")) {
            $(".checkthis").prop('checked', true);
          }else{
            $(".checkthis").prop('checked', false);
          }
        });
      
        $( ".checkthis" ).change(function() {
          if($(this).prop("checked")) {
            $("#mass_action").val('');
          }else{
            $("#checkall").prop('checked', false);
          }
        });

        $("#bulkapplysubmit").hide();
        $("#mass_action").change(function(){
          if($(this).val()==""){
              $("#bulkapplysubmit").hide();
              $("#bulkdeleteblank").show();
          } else {
              del_cnt = $('input.checkthis:checked').length;
              if(del_cnt>0) {
                //$("#bulkapplysubmit").show();
                $("#bulkdeleteblank").hide();
                if(confirm("Do you want to permanently delete the messages(s)?")) {
                    $.ajax({
                        data:$("#massForm").serialize(), 
                        dataType:"html", 
                        success:function (data, textStatus) {$("#ajaxTableContent").html(data);}, 
                        target:"_self", 
                        type:"post", 
                        url:"bulkAction"
                    });
                } else {
                    return false;}
              } else {
                  $('#mass_action').val( $('#mass_action').prop('defaultSelected') );
                  alert('Please select atleast one record');
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
        $('.prev').html("&#171");
        $('.next').html("&#187");
    });
    function showmessage(){
      $( "nav.navbar-fixed-top" ).after( '<div class="container topspace col-md-12 "><div id="flashMessage" class="alert alert-success">Contact(s) has been removed successfully</div></div>' );
      $('#searching').val('');
      $('#mass_action').val('');
      $('html, body').animate({scrollTop: '0px'}, 300);
      setTimeout(function(){
        $("#flashMessage").html("");
        $('#flashMessage').slideUp();
      }, 5000);
    }
    function showContactDetail(listId,redirectTo){
      backurl 	= $('#saveurl_field_val').val();
      searchval 	= $('#search_field_val').val();
      historyurl  =  backurl+'/search:'+searchval;	
      var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
      historyurl  =  Base64.encode(historyurl);
      if (redirectTo=="contactDetail"){
        redirecturl  =  '<?php echo Router::url(array('controller'=>'contacts','action'=>'contactDetail'));?>/'+listId+'/'+historyurl;
      } else {
        redirecturl  =  '<?php echo Router::url(array('controller'=>'contacts','action'=>'contactUpdate'));?>/'+listId+'/'+historyurl;
      }
      window.location.href = redirecturl;
    }
</script>
