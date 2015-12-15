<?php echo $this->Html->css('../assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min');
echo $this->Html->script('../assets/plugins/bootstrap/js/bootstrap.min');
echo $this->Html->script('../assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js');
?>

<style>
.btn-light-grey {
    background-color: #999;
    border-color: #8c8c8c;
    color: #ffffff;
}

.btn-bricky {
    background-color: #c83a2a;
    border-color: #b33426;
    color: #ffffff;
}

</style>
<div class="row margin_top_referral_search">
    <div class="col-md-9 col-sm-8">
        <div class="row"> 
            <div class="col-md-8">
                <div class="referrals_reviews">
                    <div class="referrals_reviews_head padd-top0">Import Contacts</div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-4 text-right">
                	<a href="<?php echo Router::url(array('action'=>'addContact'));?>" class="back_btn_new pull-right text-center padauto " ><i class="fa fa-arrow-circle-left"></i> Back</a>
    		</div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="row">
            <div class="col-md-12">
                <div class="referral_profile_head"></div>
            </div>
           <div class="col-md-12" id="integration">
           <div class="widget box-border">
                <h4 class="title page-section-header">Sync Contacts</h4>
                <ul class="widget-list integration ">
                
                <li class="widget-list-item " data-integration-name="email" data-integration-service="Email" data-integration-title="Gmail" data-integration-type="sync" data-integration-settings="false">
                <span class="widget-list-item-image icon icon-checkmark-small integration-icon-synced"></span>
                <span class="widget-list-item-image icon icon-warning-small integration-icon-warning"></span>
                <span class="widget-list-item-image icon icon-email-large integration-icon"></span>
                
                <div class="row">
               <div class="col-md-8">
   
                <div class="media"> 
                <div class="media-left"> 
                <a href="#"> 
                <img alt="64x64" class="media-object" style="width: 64px; height: 64px;" src="<?php echo $this->webroot; ?>img/gmail.png" data-holder-rendered="true"> 
                </a> </div> <div class="media-body"> 
                <h4 style="text-transform: none;">Gmail</h4>
                <p class="integration-description"> Sync your email contacts</p> 
                </div> 
                </div>
                </div>
                <div class="col-md-4" style="margin-top: 23px;">
                <?php if(isset($contactCount) && $contactCount>0) {?>
                <a class="btn btn-sm back_btn pull-right text-center padauto add_focus" href="<?php echo Router::url(array('action'=>'gmailRemove'));?>">Remove</a>
                <?php }?>
                <a class="btn btn-sm back_btn pull-right text-center padauto add_focus" style="margin-right: 10px;" href="<?php echo Router::url(array('action'=>'gmailSync'));?>">Sync</a>
                
                </div>
            </div>
        </li>
    </ul>
            </div>
            <div class="clearfix"></div>
            <br/><br/>
            <div class="widget box-border">
                <h4 class="title page-section-header">Import contacts from CSV</h4>
                <div class="panel panel-default">
            <div class="panel-body">
            <?php echo $this->Form->create('Contact',array('class'=>'smart-wizard form-horizontal','id'=>'importContactsForm','type'=>"file",'method'=>"post",'accept-charset'=>"utf-8" ,'action' => 'importContacts'))?>
                  
             
                   <div id="wizard" class="swMain">
                    <div class="col-sm-3 control-label"></div>
                    <div class="col-sm-9" >
                       <p style="color: #8c8c8c;font-size: 12px;">(Please use "First Name, Last Name, E-mail Address, Job Title" in the CSV as column header else the contacts will not be imported)</p>
                    </div>
                   
                    <div class="form-group margin_none">
                    <div class="col-sm-3">
                        <label for="Profession" class="control-label pull-right">Upload a CSV File<span class="symbol required" for="professionName"></span></label>
                        <div class="clearfix"></div>
                        <p class="pull-left informative">*Only Microsoft Outlook CSV allowed</p>
                        </div>                        
                        <div class="col-sm-9">                            
                            					
                                <div class="fileupload fileupload-new" data-provides="fileupload" id="csvdiv">
                                    <div class="input-group">
                                        <div class="form-control uneditable-input" style="height:34px;padding: 5px 16px;">
                                            <i class="fa fa-file fileupload-exists"></i>
                                            <span class="fileupload-preview"></span>
                                        </div>
                                        <div class="input-group-btn">
                                            <div class="btn btn-light-grey btn-file" style="padding: 5.5px 15px;">
                                                <span class=""><i class="fa fa-folder-open-o"></i> Select file</span>
                                                <input type="file" class="file-input" name="data[Contact][csv]" id="profession_csv">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                           
                            <label class="error" for='deshdash' style="display: none">Please select a CSV file</label>
                            <span class="error_msg" style="color: red"><?php echo $error = isset($errormsg) ? $errormsg : ""; ?></span>
                        </div>
                    </div>                    
                    <div class="form-group">
                        <div class="col-sm-12 col-sm-offset-0">
                            <button type="submit" class="btn btn-sm back_btn pull-right text-center padauto add_focus">Import <i class="fa fa-arrow-circle-right"></i></button>                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end();?>            </div>
        </div>
            </div>
       </div>
            <!--</form>-->
        </div>
    </div>
    <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'contactsimport'));?>
</div>
<?php echo $this->element('Front/bottom_ads');?>
<script>
    var ajaxUrl = "<?php echo Router::url(array('controller'=>'users','action'=>'getStateCity'));?>";
</script>
<?php echo $this->Html->script('Front/all');