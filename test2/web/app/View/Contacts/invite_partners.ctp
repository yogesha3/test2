<div class="row margin_top_referral_search">
    <div class="col-md-9 col-sm-8">
               <div class="row"> 
         <div class="col-md-12">
      <div class="referrals_reviews">
            <div class="referrals_reviews_head padd-top0">Invite Partner</div>
            
            <div class="clearfix"></div>
            </div>
            </div>
            </div>
         <div class="clearfix">&nbsp;</div>
      
      <?php echo $this->Form->create('InvitePartner',array('class'=>'form-horizontal form_compose','id'=>'invitePartners'));?>
      
  <div class="form-group">
    <label for="inputEmail-1" class="col-sm-3 col-md-2 col-xs-12  control-label">Partner Details<span class="star">*</span></label>
    <div class="col-sm-6 col-xs-9">
    <div class="col-sm-6 col-xs-9" style="margin-left: -15px;">
    <?php echo $this->form->input('InvitePartner.name.0',array('type'=>"text",'id'=>"inviteName-1",'placeholder'=>"Partner Name",'class'=>"form-control ",'label' => false, 'autofocus'=>true));?>
    </div>
    <div class="col-sm-6 col-xs-9">
    <?php echo $this->form->input('InvitePartner.email_id.0',array('type'=>"email",'id'=>"inputEmail-1",'placeholder'=>"Partner Email",'class'=>"form-control ",'label' => false));?>
    </div>
    <div id="add_more_emails_input"></div>
    </div>
    
    <div class="col-sm-4 col-xs-3">
       <a class="add_email" href="javascript:void(0);"><i class="fa fa-plus-circle"></i> Add</a>
    </div>
    
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 col-md-2  control-label">Message<span class="star">*</span></label>
    <div class="col-md-10 col-sm-9">
    
    <?php $msg = "$userName has invited you to become a member of Foxhopr – the #1 site for modern face to face business referral networking.  Sign up today and get Foxhopping.";?>
    <?php echo $this->form->textarea('InvitePartner.message_body',array('class'=>"form-control ",'rows'=>"10",'value'=>$msg,'id'=>'messageContent'));?>
     
     <div class="clearfix">&nbsp;</div>
    <button class="btn btn-sm file_sent_btn pull-right" type="submit">Send Invitations</button>
    </div>
  </div>
 <?php echo $this->Form->end();?>
      </div>
    <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'invitePartners'));?>
</div>
<?php echo $this->element('Front/bottom_ads');?>
<script>
    var ajaxUrl = "<?php echo Router::url(array('controller'=>'users','action'=>'getStateCity'));?>";
</script>
<script>
$(document).ready(function(){
	var max_fields      = 9; //maximum input boxes allowed
    var wrapper         = $("#add_more_emails_input"); //Fields wrapper    
    var counter 		= 0; //initlal text box count    
    $('#clear_file').hide();
    $('a.add_email').click(function(e){ //on add input button click
        e.preventDefault();
        if(counter < max_fields){ //max input box allowed
            counter++; //text box increment
            var nameField='<div class="col-sm-6 col-xs-9 name" style="margin-left: -15px;"><input name="data[InvitePartner][name]['+counter+']" id="inviteName-'+(counter+1)+'" placeholder="Partner Name" class="form-control" type="text" value=""></div>';
            var emailField='<div class="col-sm-6 col-xs-9 "><input name="data[InvitePartner][email_id]['+counter+']" id="inputEmail-'+(counter+1)+'" placeholder="Partner Email" class="form-control" type="email" value=""></div>';
            $(wrapper).append('<div class="clearfix"></div><div class="input email  marginTop10 ">'+nameField+emailField+'<a href="#" class="remove_field" title="Remove" id="remove-'+counter+'">x</a></div>'); //add input box
            
        }
        if(counter == max_fields){
        	$('a.add_email').hide();
        }
    }); 
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); 
        elmid = $(this).parent('div').attr('id');
        $(this).parent('div').remove();        
        $("label[for='inputEmail-"+elmid+"']").remove();  
        counter--;
        if(counter < max_fields){ //max input box allowed
        	$('a.add_email').show();
        }
        //Reorder Indices
        reorder();   
    });
});
function reorder()
{
	var next;
	$('input[type="email"]').each(function(i) {
	   next=(i+1);
	   //alert(next);
	   $(this).attr('id','inputEmail-'+next);
	   $(this).attr('name','data[InvitePartner][email_id]['+i+']')
	   $(this).closest('div.email').find('a.remove_field').attr('id','remove-'+next+'');
	   
	});
	$('input[type="text"]').each(function(i) {
		   next=(i+1);
		   $(this).attr('id','inviteName-'+next);
		   $(this).attr('name','data[InvitePartner][name]['+i+']');		   
		   
		});
	}

</script>
<?php
echo $this->Html->script('Front/all');
?>