<?php 
$style = "display:none;";
if(isset($this->request->data['Message']['sendto']) && $this->request->data['Message']['sendto']==0){
	$style = "display:block;";
}
?>
<?php 
echo $this->Html->script('Front/dropzone');
echo $this->Html->css('dropzone');
echo $this->Html->script('Front/all');
?>
<div class="row margin_top_referral_search">
	<div class="col-md-9 col-sm-8">

		<div class="row">
			<div class="col-md-12">
				<div class="referrals_reviews">
					<div class="referrals_reviews_head padd-top0">Compose Message</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		<div class="clearfix">&nbsp;</div>
		<div class="clearfix">&nbsp;</div>
		<?php echo $this->Form->create('Message', array('type'=>'file','url' => array('controller' => 'messages', 'action' => 'composeMessage'), 'class' => 'form-horizontal form_compose', 'id' => 'composeMessageForm','inputDefaults' => array('div'=>false,'label' => false,'errorMessage' => false)));?>				
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-3 col-md-2  control-label">Send
				To<span class="star">*</span>
			</label>
			<div class="col-sm-4 col-md-3">
				<?php echo $this->Form->select('Message.sendto', array('1'=>'All Team Members','0'=>'Choose Group Members'),array('class' => 'form-control seclect_value seclect_bulk','id' => 'sendto','required'=>false,'empty' => false,'tabindex'=>"1"));?>							
			</div>
		</div>
		<div class="form-group" style="<?php echo $style;?>" id="recipient_list_field">
			<label for="inputEmail3" class="col-sm-3 col-md-2  control-label">Add
				Recipients<span class="star">*</span>
			</label>
			<div class="col-sm-4 col-md-3">
				<?php echo $this->Form->select('Message.recipient_list', $usersList , array('class' => 'form-control seclect_value seclect_bulk', 'multiple'=>'multiple','id' => 'recipient_list', 'placeholder'=>'Add Recipients','autocomplete'=>'off','required'=>false,'tabindex'=>"2",'style'=>"height: 25px"));?>
			</div>
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-3 col-md-2  control-label">Subject<span
				class="star">*</span></label>
			<div class="col-sm-5">
				<?php echo $this->Form->input('Message.subject', array('class' => 'form-control', 'id' => 'subject', 'placeholder'=>'Subject','autocomplete'=>'off','required'=>false,'tabindex'=>"2", 'autofocus'=>true));?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 col-md-2  control-label" for="inputPassword3">Message<span class="star">*</span></label>
			<div class="col-md-10 col-sm-9">
				<div class="compose_message"> 
				<?php echo $this->Form->input('Message.content', array('type'=>'textarea','class' => 'form-control', 'id' => 'content', 'required'=>false,'tabindex'=>"3",'rows'=>10));?>
					<div class="clearfix">&nbsp;</div> 
					<div id="dropZoneArea" class="file_attatch dropZoneArea">
						<i class="fa fa-paperclip"></i> To attach files, drag &amp; drop here or  <a id="clickable" href="javascript:void(0);"> Select files from your computer.</a> 
						<div class="clearfix">&nbsp;</div>  
					</div>
					<div class="clearfix"></div> 
					<br>
					<div class="informationnote">NOTE: Maximum 5 attachments are allowed.</div>
					<div class="clearfix"> </div>
					<?php echo $this->Form->button('Send Message',array('class'=>'btn btn-sm file_sent_btn pull-right', 'type'=>'submit','tabindex'=>"4"));?>
					<div class="clearfix"></div>
				</div>
				
			</div>
			<!-- DROPZONE -->
			<div class="table table-striped" class="files" id="previewContainer">
				<div id="template" class="file-row row">
					<!-- This is used as the file preview template -->
					<div class="col-md-1 first_row">
						<span class="preview"><img data-dz-thumbnail /></span>
					</div>
					<div class=" col-md-7">
						<p class="name img_name" data-dz-name></p>                       
						<div>
							<p class="size size2" data-dz-size></p>
							<strong style="font-size:12px;" class="error text-danger" data-dz-errormessage></strong>
							<div class="uplod_bar">
								<div style="background-color: #fefefe" class="progress progress-striped  progress2" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
									<div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress> </div>
								</div>
								<a href="javascript:void(0);"><i class="fa fa-close close_icon" data-dz-remove=""></i></a> 
							</div>                    
						</div>
					</div>
					<div class=" col-md-2">
					</div>
				</div>
			</div>
			<div class="clearfix">&nbsp;</div>
			<!-- DROPZONE -->
		</div>
	<?php echo $this->Form->end();?>
	</div>
<?php echo $this->element("Front/loginSidebar",array('tabpage' => 'messagecompose'));?>
</div>
<?php echo $this->element('Front/bottom_ads');?>
<div class="clearfix"></div>
<div class="clearfix"></div>
<?php /*?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<?php echo $this->Html->image('advirtise-bottom.jpg',array('alt'=> '','class'=>'thumbnail img-responsive img-centered'));?>			
		</div>
	</div>
</div>
<?php */?>
<script>
var path = '<?php echo $this->webroot; ?>';
</script>
<?php 
echo $this->Html->script('Front/composemessage');
?>