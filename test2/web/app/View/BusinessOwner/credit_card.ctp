<div class="row margin_top_referral_search">
      <div class="col-md-9 col-sm-8">
  
               <div class="row"> 
         <div class="col-md-12">
      <div class="referrals_reviews">
            <div class="referrals_reviews_head padd-top0">Credit Card Details</div>
            
            <div class="clearfix"></div>
           
            </div>
            </div>
            </div>
         <div class="clearfix">&nbsp;</div>
     <?php
        echo $this->Form->create('BusinessOwner',array('url'=>array('controller'=>'businessOwners','action'=>'creditCard'),'class'=>'form-horizontal form_compose  credit_card_information','id'=>'creditCardForm','inputDefaults' => array('label' => false,'div' => false,'errorMessage'=>true),'novalidate'=>true));
        ?>
        <?php if(!empty($savedCards)) {
        if($savedCards['BusinessOwners']['credit_card_number']!='') {
            ?>
       <div class="credit_card_text">
       The credit card ending with <?php echo $this->Encryption->decode($savedCards['BusinessOwners']['credit_card_number']);?> is currently associated with your account.<br/>
       
       </div>
       <?php }?>
       <?php }?>
         <div class="clearfix">&nbsp;</div>
       <div class="card_info_heading">UPDATE CREDIT CARD INFORMATION</div>
         <div class="clearfix">&nbsp;</div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 col-md-4  control-label">Card Number<span class="star">*</span></label>
    <div class="col-sm-4 col-md-3">
    <?php echo $this->Form->input('CC_Number',array('style'=>"height:30px",'id'=>'card_number','autocomplete'=>"off",'placeholder'=>"Card Number",'class'=>'form-control','maxlength'=>'16','autocomplete'=>false, 'autofocus'=>true));?>
     
    </div>
    
    <div class="col-sm-4 col-md-2">
    <?php echo $this->Form->input('CC_cvv',array('style'=>"height:30px",'placeholder'=>"CVC",'class'=>"form-control",'type'=>'password','maxlength'=>3,'autocomplete'=>false))?>
    </div>
    
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 col-md-4  control-label">Expiration Date<span class="star">*</span></label>
    <div class="col-sm-4 col-md-3">
    <?php echo $this->Form->month('CC_month', array('id'=>'expiration_month','empty' => 'Month','class'=>"form-control seclect_value seclect_bulk sel_custom"));?>
    </div>
    
    <div class="col-sm-4 col-md-2">
    
    <?php echo $this->Form->year('CC_year', 2030, date('Y'),array('class'=>'form-control seclect_value seclect_bulk sel_custom','orderYear'=>'asc','empty'=>'Year'));?>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 col-md-4  control-label">Name as it appears on card<span class="star">*</span></label>
    
    <div class="col-sm-4 col-md-5">
     <?php echo $this->Form->input('CC_Name',array('style'=>"height:30px",'placeholder'=>"",'class'=>"form-control"));?>
    </div>
    
  </div>
  
<!--   <div class="form-group"> -->
<!--     <label for="inputPassword3" class="col-sm-3 col-md-4  control-label">Billing street address<span class="star">*</span></label> -->
    
<!--     <div class="col-sm-4 col-md-5"> 
    <input class="form-control" type="text" placeholder="Billing street address" style="height:25px">
          -->
        
<!--     </div> -->
    
<!--   </div> -->
  
<!--   <div class="form-group"> -->
<!--     <label for="inputPassword3" class="col-sm-3 col-md-4  control-label">&nbsp;<span class="star"></span></label> -->
    
<!--     <div class="col-sm-4 col-md-5"> 
    <input class="form-control" type="text" placeholder="Billing street address2" style="height:25px">
          -->
        
<!--     </div> -->
    
<!--   </div> -->
  
<!--   <div class="form-group"> -->
<!--     <label for="inputPassword3" class="col-sm-3 col-md-4  control-label">City<span class="star">*</span></label> -->
    
<!--     <div class="col-sm-4 col-md-5"> 
    <input class="form-control" type="text" placeholder="City" style="height:25px">
          -->
        
<!--     </div> -->
    
<!--   </div> -->
  
<!--   <div class="form-group"> -->
<!--     <label for="inputPassword3" class="col-sm-3 col-md-4  control-label">Country<span class="star">*</span></label> -->
    
<!--     <div class="col-sm-4 col-md-5"> 
    <input class="form-control" type="text" placeholder="Country" style="height:25px">
       -->   
        
<!--     </div> -->
    
<!--   </div> -->
  
<!--   <div class="form-group"> -->
<!--     <label for="inputPassword3" class="col-sm-3 col-md-4  control-label">State<span class="star">*</span></label> -->
    
<!--     <div class="col-sm-4 col-md-5"> 
    <input class="form-control" type="text" placeholder="State" style="height:25px">
       -->   
        
<!--     </div> -->
    
<!--   </div> -->
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 col-md-4  control-label" style="visibility: hidden;">Zip<span class="star">*</span></label>
    
    <div class="col-sm-4 col-md-5">
    <input class="form-control" type="text" placeholder="Zip" style="height:25px;visibility: hidden;">
          
           <div class="clearfix">&nbsp;</div>
           <?php echo $this->Form->button('Save',array('class'=>"btn btn-sm file_sent_btn pull-right ML_btn",'type'=>"submit"));?>
    <a href="<?php echo Router::url(array('controller'=>'businessOwners','action'=>'billing'),true);?>" class="btn btn-sm file_sent_btn pull-right" >Back</a>
    </div>
  </div>
</form>
      </div>
          <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'billing'));?>
    </div>

<?php echo $this->element('Front/bottom_ads');
    echo $this->Html->script('Front/all');