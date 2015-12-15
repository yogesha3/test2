<div class="col-md-9 col-sm-8">
  
               <div class="row"> 
         <div class="col-md-8" style="margin-top: 10px;">
      <div class="referrals_reviews">
            <div class="referrals_reviews_head padd-top0">Purchase Receipts</div>
            
            <div class="clearfix"></div>
           
            </div>
            </div>
            <div class="col-md-4 text-right" style="margin-top: 10px;">
                <a href="<?php echo Router::url(array('controller'=>'businessOwners','action'=>'billing'),true);?>" class="back_btn_new pull-right text-center padauto "><i class="fa fa-arrow-circle-left"></i> Back</a>
            </div>
            <div class="col-md-4 text-right"><a href="#" class="back_btn_new pull-right text-center padauto hidden"><i class="fa fa-download"></i> Download All</a></div>
            </div>
         <div class="clearfix">&nbsp;</div>
  
      <div id="no-more-tables">
      
            <table class="col-md-12 table-bordered table-striped   table-condensed table-condensed no-more-tables cf payment_receipt_table data_table ">
        		<thead class="cf">
        			<tr>
        		<th>Invoice Date</th>
              <th>Joined On</th>
              <th>Membership Plan</th>
              <th></th>
        				
        			</tr>
        		</thead>
        		<tbody>
        		<?php if(!empty($txData)) {
        		foreach($txData as $row) { ?>
        			<tr>								
               
                <td><?php echo date('M d, Y',strtotime($row['Transaction']['modified']));?></td>
                <td><?php echo date('M d, Y',strtotime($row['Transaction']['purchase_date']));?></td>
                  <td><?php echo ucfirst($row['Transaction']['group_type']);?></td>
                  <td><a href="<?php echo Router::url(array('controller'=>'businessOwners','action'=>'renderPDF',$row['Transaction']['id']),true);?>" class="" target="_blank" title="Download PDF" ><?php echo $this->Html->image('pdf19.png', array('alt' => 'PDF Image','style'=>"width:32px;"));?></a></td>
              </tr>
              <?php } }?>
        		</tbody>
        	</table>
            <div class="clearfix">&nbsp;</div>
            
        </div>
      </div>
      <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'billing'));?>
    </div>
    <?php echo $this->element('Front/bottom_ads');
    echo $this->Html->script('Front/all');