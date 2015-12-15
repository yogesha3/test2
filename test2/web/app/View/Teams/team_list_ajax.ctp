<?php $this->Paginator->options(array('update' => '#ajaxTableContent','evalScripts' => true)); ?>
<?php echo $this->Paginator->options(array('url' => array('action'=>'team-list',"perpage"=>$perpage,"search"=>$search,'sort'=> $this->Session->read('sort'),'direction'=> $this->Session->read('direction'))));
?>
<table class="col-md-12 table-bordered table-striped table-condensed no-more-tables cf data_table ">
            <thead class="cf">
              <tr>
                <th width="1%"><input type="checkbox" id="checkallteam"></td>
                <th width="25%"><?php echo $this->Paginator->sort('BusinessOwner.fname', 'Name'); ?></th>
                  <th width="25%"><?php echo $this->Paginator->sort('BusinessOwner.company', 'Company'); ?></th>
                  <th width="30%">Location</th>
                  <th width="15%"></th>
              </tr>
            </thead>
            <tbody>
              <?php 
                if(!empty($groupData)) {
                  foreach($groupData as $data){
                      $encrypted=$this->Encryption->encode($data['BusinessOwner']['user_id']);
                      ?>
              <tr>
                <td>
                    <span class="check_inpurt">
                        <input name="teamMembers[]" type="checkbox" class="checkthis" value="<?php echo $data['BusinessOwner']['user_id']?>">
                    </span>
                </td>
                <td onclick="showMemberDetail('<?php echo $encrypted;?>','memberDetail');" class="tdCursor"><strong><?php echo $data['BusinessOwner']['fname'].' '.$data['BusinessOwner']['lname'];?> </strong><br>
                    <span class="destination_name"><?php echo $data['Profession']['profession_name']; ?> </span>
                </td>
                <td onclick="showMemberDetail('<?php echo $encrypted;?>','memberDetail');" class="tdCursor"><strong><?php echo $data['BusinessOwner']['company']; ?></strong></td>
                <td onclick="showMemberDetail('<?php echo $encrypted;?>','memberDetail');" class="tdCursor"><?php echo $data['State']['state_subdivision_name'].", ".$data['Country']['country_name']; ?></td>
                 <td>
                 <?php 
                 $webLink=$data['BusinessOwner']['website'];
                 /*if($webLink != '') {?>
                 <a target="_blank" title="Website" href="<?php if (!preg_match("~^(?:f|ht)tps?://~i", $webLink)) {echo 'http://'.$webLink;} else { echo $webLink;}?>" class="search_table_bg">
                    <span class=" fa fa-globe table_search_icon icon_font_s"></span> 
                    
                  </a>
                 <?php } else {?>
                 <a title="Website" href="#" class="search_table_bg2">
                    <span class=" fa fa-globe table_search_icon icon_font_s"></span> 
                  </a>
                 <?php }*/
                 ?>
                  
                  <a title="Compose Message" href="<?php echo Router::url(array('action'=>'action','message',$data['User']['id']));?>" class="search_table_bg">                  
                    <span class=" fa fa-envelope table_search_icon icon_font_s"></span> 
                  </a> 
                  <a title="Send a referral"  href="<?php echo Router::url(array('action'=>'action','referral',$data['User']['id']));?>" class="search_table_bg">
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
<script>
    $(document).ready(function(){
      $( "#checkallteam" ).change(function() {   
        if($("#checkallteam").prop("checked")) {
          $(".checkthis").prop('checked', true);
        }else{
          $(".checkthis").prop('checked', false);
        }
      });
      $( "#searching" ).keyup(function() {
          searchval = $('#search_field_val').val($(this).val());
      });
      backurl = $('#saveurl_field_val').val('<?php echo $this->Paginator->url()?>');
    });
    </script>
