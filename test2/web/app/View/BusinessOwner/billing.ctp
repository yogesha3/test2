<?php $actionUrl = 'businessOwners/popupFunction';
$cancelPopup = "popUp('$actionUrl', '','users', 'cancelMembership', 'cancelMembership')";
$upgradeDowngradeBtn="";
//debug($userData['Subscription']);
//$userData['Groups']['group_type'] = 'global';
$cancelButton='<a class="search_table_bg" href="javascript:void(0);" onclick="'.$cancelPopup.'" escape = false type="button" data-toggle="modal" data-target="#myModal" title="Cancel"><span class="glyphicon glyphicon-remove table_search_icon"></span></a>';
if($userData['Groups']['group_type'] == 'local') {
    if(!empty($subscriptionData['Subscription']['is_active'])) {
        if(date('Y-m-d') > date('Y-m-d', strtotime($userData['BusinessOwners']['group_update']. ' + 30 days'))){
            $upgradeDowngradeBtn = '<a class="search_table_bg" href="'.Router::url(array('controller'=>'groups','action'=>'group-change',$this->Encryption->encode('local'))).'" title="Upgrade"><span class="glyphicon glyphicon-upload table_search_icon"></span></a>';
        } else {
            $upgradeDowngradeBtn = '<a class="disabled search_table_bg2" href="#"><span class="glyphicon glyphicon-upload table_search_icon" title="Upgrade"></span></a>';
        }        
    } else {
        $cancelButton = '<a class="search_table_bg2" href="javascript:void(0);" escape = false type="button" title="Cancel"><span class="glyphicon glyphicon-remove table_search_icon"></span></a>';
        $upgradeDowngradeBtn = '<a class="disabled search_table_bg2" href="#"><span class="glyphicon glyphicon-upload table_search_icon" title="Upgrade"></span></a>';
    }
} elseif($userData['Groups']['group_type'] == 'global') {
    if(!empty($subscriptionData['Subscription']['is_active'])) {
        if(date('Y-m-d') > date('Y-m-d', strtotime($userData['BusinessOwners']['group_update']. ' + 30 days'))){
            $upgradeDowngradeBtn = '<a class="search_table_bg" href="'.Router::url(array('controller'=>'groups','action'=>'group-change',$this->Encryption->encode('global'))).'" title="Downgrade"><span class="glyphicon glyphicon-download table_search_icon"></span></a>';
        } else {
            $upgradeDowngradeBtn = '<a class="disabled search_table_bg2" href="#" title="Downgrade"><span class="glyphicon glyphicon-download table_search_icon"></span></a>';
        } 
        
    } else {
        $cancelButton = '<a class="search_table_bg2" href="javascript:void(0);" escape = false type="button" title="Cancel"><span class="glyphicon glyphicon-remove table_search_icon"></span></a>';
        $upgradeDowngradeBtn = '<a class="disabled search_table_bg2" href="#" title="Downgrade"><span class="glyphicon glyphicon-download table_search_icon"></span></a>';
    }
}
$currentGrp = $userData['Groups']['group_type'];
$currentGrpHtml = $inactiveGrpHtml = "";
$currentGrpHtml.= $cancelButton.'<a class="search_table_bg" href="'.Router::url(array('controller'=>'businessOwners','action'=>'creditCard'),true).'" title="Credit Card"><span class="glyphicon glyphicon-credit-card table_search_icon"></span></a> 
                  <a class="search_table_bg" href="'.Router::url(array('controller'=>'businessOwners','action'=>'purchaseReceipts'),true).'" title="Receipts"><span class="glyphicon glyphicon-list-alt table_search_icon"></span></a> ';
$inactiveGrpHtml.= $upgradeDowngradeBtn.'<a class="disabled search_table_bg2" href="#" title="Credit Card"><span class="glyphicon glyphicon-credit-card table_search_icon"></span></a> 
                  <a class="disabled search_table_bg2" href="#" title="Receipts"><span class="glyphicon glyphicon-list-alt table_search_icon"></span></a>';
?>
<div class="row margin_top_referral_search">
    <div class="col-md-9 col-sm-8">
  
               <div class="row"> 
         <div class="col-md-12">
      <div class="referrals_reviews">
            <div class="referrals_reviews_head padd-top0">Billing</div>
            
            <div class="clearfix"></div>
           
            </div>
            </div>
            </div>
         <div class="clearfix">&nbsp;</div>
  
      <div id="no-more-tables">      
            <table class="col-md-12 table-bordered table-striped   table-condensed table-condensed no-more-tables cf payment_receipt_table data_table belling_table">
        		<thead class="cf">
        			<tr>
        		<th>   Plans</th>
              <th>Fee </th>
              <th>&nbsp;</th>
        			</tr>
        		</thead>
        		<tbody>
        			<tr>
              											
               
                <td>Local</td>
                <td>$49.99	</td>
                  <td>
                 <?php if($currentGrp == 'local') {echo $currentGrpHtml;} else {echo $inactiveGrpHtml;}?>
                   <div class="clearfix"></div> 
                
                </td>
              </tr>
        			<tr>
               <td>Global</td>
               <td>$49.99	</td>
                 <td>
                 <?php if($currentGrp == 'global') {echo $currentGrpHtml;} else {echo $inactiveGrpHtml;}?>
                   <div class="clearfix"></div> 
                </td>
              </tr>
        		</tbody>
        	</table>
        	<div id="billingpageid">*Plan can be changed once every 30 days</div>
            <div class="clearfix">&nbsp;</div>
            
        </div>
      </div>
        <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'billing'));?>
    </div>
    <?php echo $this->element('Front/bottom_ads');
    echo $this->Html->script('Front/all');