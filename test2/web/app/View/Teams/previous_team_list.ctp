<?php $this->Paginator->options(array('update' => '#ajaxTableContent','evalScripts' => true)); 

echo $this->Html->script('Front/all');?>
<?php
        echo $this->Form->create('Team',array('id'=>'teamform','url'=>array('controller'=>'teams','action'=>'action'),'inputDefaults' => array('label' => false,'div' => false,'errorMessage'=>true),'novalidate'=>true));
        echo $this->Form->hidden('saveurl',array('id'=>'saveurl_field_val','value'=>$this->Paginator->url()));
        echo $this->Form->hidden('search_val',array('id'=>'search_field_val','value'=>$search));
    ?>
<div class="row margin_top_referral_search">
      <div class="col-md-9 col-sm-8">
               <div class="row"> 
         <div class="col-md-12">
      <div class="referrals_reviews">
                    <div class="referrals_reviews_head padd-top0">PREVIOUS TEAM MEMBERS</div>
            
            <div class="clearfix"></div>
            </div>
            </div>
            </div>
         <div class="clearfix">&nbsp;</div>
      <div class="row ">
      <div class="col-md-6 col-sm-5 col-xs-5 width_at_mob">
        <div id="imaginary_container">
          <div class="input-group   ">
              <input autocomplete="off" type="text" id="searching" name="search" class="search-query form-control innerpage_search clearable" placeholder="Search" value="<?php echo $search;?>">
              <span class="input-group-btn">
                  <button type="button" class="btn inner_pagesbtn front_search">
                      <span class=" glyphicon glyphicon-search"></span>
                  </button>
              </span>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-6 width_at_mob">
        <div class=" action_bulk">
          <label class="labelSelect">
            <select id="bulkaction" name="bulkaction" class="selectNew form-control seclect_value seclect_bulk">
              <option value=""> More</option>
              <option value="message">Compose Message</option>
              <option value="referral">Send A Referral</option>
            </select>
          </label>
        </div>
        <!--<a href="javascript:void(0);" id="nonsubmit" class="apply">Apply</a>-->
        <!--<a style="display: none;" href="javascript:void(0);" id="submit" class="apply">Apply</a>-->
        
        <div class="select_box pull-right">
          <label class="labelSelect">
          <?php echo $this->Form->input('perpage', array('id'=>'perpage','type'=>'select','options'=>Configure::read('PERPAGE'),'empty' => false,'name'=>'perpage','class'=>"selectNew form-control seclect_value",'label'=>false));?>
          </label>
        </div><div class="results_pages pull-right">Results per page &nbsp;&nbsp;&nbsp;</div>
        <?php
          $this->Js->get('#perpage');
          $this->Js->event('change',
          $this->Js->request(array(
                  'controller'=>'teams',
                  'action'=>'previousTeamList'),
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
                  'controller'=>'teams',
                  'action'=>'previousTeamList'),
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
      <div id="ajaxTableContent">
            <table class="col-md-12 table-bordered table-striped table-condensed no-more-tables cf data_table ">
        		<thead class="cf">
        			<tr>
                <th width="1%"><input type="checkbox" id="checkallteam"></td>
                <th width="25%">
                <?php echo $this->Paginator->sort('BusinessOwner.fname', 'Name'); ?>
                </th>
                  <th width="25%"><?php echo $this->Paginator->sort('BusinessOwner.company', 'Company'); ?></th>
                  <th width="30%">Location</th>
                  <th width="15%"></th>
        			</tr>
        		</thead>
        		<tbody>
              <?php 
                if (!empty($groupData)) {
                  foreach ($groupData as $data) {
                      $encrypted=$this->Encryption->encode($data['BusinessOwner']['user_id']);
                      $previousUserID = $this->Encryption->encode('prev_'.$data['BusinessOwner']['user_id']);
                  ?>
        			<tr>
				        <td>
				            <span class="check_inpurt">
				                <input name="teamMembers[]" type="checkbox" class="checkthis" value="prev_<?php echo $data['BusinessOwner']['user_id']?>">
				            </span>
				        </td>
        				<td onclick="showMemberDetail('<?php echo $encrypted;?>','memberDetail');" class="tdCursor"><strong><?php echo $data['BusinessOwner']['fname'].' '.$data['BusinessOwner']['lname'];?> </strong><br>
                    <!--<a href="javascript:void(0);" onclick="showMemberDetail('<?php echo $encrypted;?>','memberDetail');"><b><?php echo $data['BusinessOwner']['fname'].' '.$data['BusinessOwner']['lname'];?> </b><br>-->
                        <span class="destination_name"><?php echo $data['Profession']['profession_name']; ?> </span><!--</a>-->
                </td>
                <td onclick="showMemberDetail('<?php echo $encrypted;?>','memberDetail');" class="tdCursor"><strong><?php echo ucfirst($data['BusinessOwner']['company']); ?></strong></td>
                <td onclick="showMemberDetail('<?php echo $encrypted;?>','memberDetail');" class="tdCursor"><?php echo $data['State']['state_subdivision_name'].", ".$data['Country']['country_name']; ?></td>
                 <td>
                 <?php
                 $webLink=$data['BusinessOwner']['website'];
                 if($webLink != '') {
                 ?>
                 <a target="_blank" title="Website" href="<?php if (!preg_match("~^(?:f|ht)tps?://~i", $webLink)) {echo 'http://'.$webLink;} else { echo $webLink;}?>" class="search_table_bg">
                    <span class=" fa fa-globe table_search_icon icon_font_s"></span> 
                    
                  </a>
                 <?php } else {?>
                 <a title="Website" href="#" class="search_table_bg2">
                    <span class=" fa fa-globe table_search_icon icon_font_s"></span> 
                  </a>
                 <?php }
                 ?>
                   <a title="Compose Message" href="<?php echo Router::url(array('action'=>'action','message',$previousUserID));?>" class="search_table_bg">                  
                    <span class=" fa fa-envelope table_search_icon icon_font_s"></span> 
                  </a> 
                  <a title="Send a referral"  href="<?php echo Router::url(array('action'=>'action','referral',$previousUserID));?>" class="search_table_bg">
                    <span class=" fa fa-users table_search_icon icon_font_s"></span>
                  </a> 
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
                  <?php echo $this->Paginator->prev(__('«',array('tag' => false))); ?>
                </li>
                <li><?php echo $this->Paginator->numbers(array('separator'=>false)); ?> </li>
                
                <li><?php echo $this->Paginator->next(__('»',array('tag' => false))); ?></li>
              </ul>
      <?php }?>
      <?php echo $this->Js->writeBuffer(); ?>
              </div>
        </div>        
      </div>
         <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'previousTeam'));?>
    </div>
    <?php echo $this->element('Front/bottom_ads');?>
    <?php echo $this->Form->end();?>
<script>
  $(document).ready(function(){
    $(".checkthis").prop('checked', false);
    $("#checkallteam").prop('checked', false);
    $('select option:first-child').attr("selected", "selected");
    $("input[type='text']").on('keypress', function(e) { return e.keyCode != 13; });

    $( "#checkallteam" ).change(function() {   
      if($("#checkallteam").prop("checked")) {
        $(".checkthis").prop('checked', true);
        if($('#bulkaction').val()!="") {
          $("#submit").show();
          $("#nonsubmit").hide();
        }
      }else{
        $(".checkthis").prop('checked', false);
        $("#submit").hide();
        $("#nonsubmit").show();
      }
    });
    $('.prev').html("&#171");
  });

  $('#submit').on('click', function(e) {
    $('#teamform').submit();
  });

  $("#bulkaction").change(function(){
    if($(this).val()==""){
      //$("#submit").hide();
      //$("#nonsubmit").show();
    }else{
      del_cnt = $('input.checkthis:checked').length;
      if(del_cnt>0){
        $('#teamform').submit();
      } else {
          $('#bulkaction').val( $('#bulkaction').prop('defaultSelected') );
          $("#myModalNoRecord").modal('show');
        //alert('Please select atleast one record');
        //$('#bulkaction').val( $('#bulkaction').prop('defaultSelected') );
      }
    }
  });

  $( ".checkthis" ).change(function() {
    if($(this).prop("checked")) {
      if($('#bulkaction').val()!="") {
        $("#submit").show();
        $("#nonsubmit").hide();
      }
      else {
        $("#submit").hide();
        $("#nonsubmit").show();
      }
    }else{
      $("#checkallarchive").prop('checked', false);
    }
  });
  function showMemberDetail(listId,redirectTo){
      backurl 	= $('#saveurl_field_val').val();
      searchval 	= $('#search_field_val').val();
      historyurl  =  backurl+'/search:'+searchval;	
      var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}
      historyurl  =  Base64.encode(historyurl);
      if (redirectTo=="memberDetail"){
        redirecturl  =  '<?php echo Router::url(array('controller'=>'teams','action'=>'memberDetail'));?>/'+listId+'/'+historyurl;
      }
      window.location.href = redirecturl;
    }
</script>
