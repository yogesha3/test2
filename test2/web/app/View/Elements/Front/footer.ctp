<?php
/**
 * Front end footer
 */
?>
<style>
  .modal {
    background-clip: padding-box;
    background: none;
    border: none;
    border-radius: 0;
    bottom: auto;
    box-shadow: none;
    left: 50%;
    margin-left:none;
    padding: none;
    right: auto;
    width: auto;
}
.modal-scrollable{background:rgba(0,0,0,0.1)}
  }
</style>
</div></div></section>
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
<div id="popup" class="modal fade modal-sm" tabindex="-1" data-width='auto' style="display: none;">
</div>
<div data-backdrop="static" data-keyboard="false" class="modal fade popup" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
</div>
<div aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModalNoRecord" class="modal fade popup in" style="display: none" aria-hidden="false">
  <div role="document" class="modal-dialog">
    <div class="modal-content">
      <div style="border:none" class="modal-header">
      </div>
      <div class="modal-body popup_body">
        <h2>Please select atleast one record</h2>
      </div>
      <div class="modal-footer popup_footer text-center">
        <button data-dismiss="modal" class="btn btn-default ok_btn" type="button"><span class="pull-left">Ok</span>  <i class="fa fa-check pull-right"></i></button>
      </div>
    </div>
  </div>
</div>
<!-- Contact Section -->
<?php if ($this->params['controller'] == 'pages' || $this->params['action'] == "signUp")  {?>
<section id="contact">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 text-center">
				<h2>Get in touch with us!</h2>
			</div>
		</div>
		<div class="row">
			<div class=" col-md-12 ">
				<?php echo $this->Form->create('NewsletterSubscribe', array('url' => Router::Url(array('controller' => 'newsletters','action' => 'subscribe'), TRUE), 'id' => 'NewsletterSubscribeForm','role'=>"search"));  ?>					
					<div class="input-group">
						<?php echo $this->Form->input('subscribe_email_id',array('id'=>'subscribe_email_id','type'=>'text','class'=>'form-control subscribe_search','maxlength'=>'64','placeholder'=>'Enter your email address','label'=>false))?>
						<div class="input-group-btn">
							<?php echo $this->Form->button('Subscribe', array('type' => 'submit','class'=>'btn btn-default subscribe_btn'));?>
						</div>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</section>
<?php } ?>
<footer class="text-center">
	<div class="footer-above footer_inner1">
		<div class="container">
			<div class="row">
				<div class="footer-col col-md-3 col-xs-12 col-sm-4">
				<!--	<div class="logo_bottom">
						<?php echo $this->Html->image('logo-bottom.png', array('alt'=>'Foxhopr'));?>
					</div>-->
					<div class="main-menu">
						<h3>COMPANY</h3>
						<ul class="footer_link">
                            <li><?php echo $this->Html->link('About Us', array('controller' => 'pages', 'action' => 'aboutUs'), array('escape' => false));?></li>
							<li><a href="#"> Membership</a></li>
                            <li><a href="#"> Blog</a></li>
                            <li><?php echo $this->Html->link('Contact', array('controller' => 'pages', 'action' => 'contactUs'), array('escape' => false));?></li>
                            <li><a href="#">Careers</a></li>
                            <li><a href="#">Press</a></li>
                            <?php
                            //if ($this->Session->read('Auth.Front')) { ?>
                                <li><?php //echo $this->Html->link('My Account', array('controller' => 'dashboard', 'action' => 'dashboard'), array('escape' => false));?></li>
                            <?php //} else {?>
                                <li><?php //echo $this->Html->link('Sign Up', array('controller' => 'users', 'action' => 'signUp'), array('escape' => false));?></li>
                            <?php //} ?>
						</ul>
					</div>
				</div>
				<div class="footer-col col-md-3 col-xs-12 col-sm-4">
					<h3>MOBILE</h3>
					<ul class="footer_link">
						<li><a href="#"> Download for iOS</a></li>
						<li><a href="#"> Download for Android </a></li>
					</ul>
				</div>
				<div class="footer-col col-md-3 col-xs-12 col-sm-4 col-bottom-left-padd0">
					<h3>SUPPORT</h3>
					<ul class="footer_link">
						<li><a href="#"> FAQ</a></li>
						<li><?php echo $this->Html->link('Contact Support', array('controller' => 'pages', 'action' => 'contactUs'), array('escape' => false));?></li>
					</ul>
				</div>
				<div class="footer-col col-md-3 col-xs-12 col-sm-4 sm-icon">
					<h3>STAY CONNECTED</h3>
					<ul class="list-inline social_icon">
						<li class="padd-left0"><a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-facebook"></i></a></li>
                        <li><a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-linkedin"></i></a></li>
						<li><a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-google-plus"></i></a></li>
						<li><a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-twitter"></i></a></li>
                    </ul>
                </div>
            <!--    <div class="footer-col col-md-2 col-xs-12 col-sm-4">
                    <h3>Get The App</h3>
                    <p>never miss an opportunity again</p>
                    <div class="app-download">
                        <?php echo $this->Html->image('logo-icon.png', array('class'=>'logo-icon','alt'=>'Foxhopr'));?>
                        <?php echo $this->Html->link($this->Html->image('googel-play.png', array('class'=>'logo-icon','alt'=>'Foxhopr')),'',array('escape'=>false));?>
                        <?php echo $this->Html->link($this->Html->image('iphon-app.png', array('class'=>'logo-icon','alt'=>'Foxhopr')),'',array('escape'=>false));?>						
                    </div>
                </div>-->
            </div>
        </div>
    </div>
    <div class="footer-below footer_inner2">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ">
                    <ul class="term-policy">
                        <li class="copy_right">Copyright &copy; FoxHopr</li>
                        <li><?php echo $this->Html->link('Term', array('controller' => 'pages', 'action' => 'termsOfServices'), array('escape' => false)); ?></li>
                        <li><?php echo $this->Html->link('Privacy Policy', array('controller' => 'pages', 'action' => 'privacyPolicy'), array('escape' => false)); ?></li>
                    </ul>               
                </div>
                
              <!--  <div class="col-md-8 col-md-offset-2">
                    <ul class="term-policy">
                        <li>
                            <?php echo $this->Html->link('Term', array('controller' => 'pages', 'action' => 'termsOfServices'), array('escape' => false)); ?>
                        </li>                           
                        <li>
                            <?php echo $this->Html->link('Privacy Policy', array('controller' => 'pages', 'action' => 'privacyPolicy'), array('escape' => false)); ?>
                        </li>
                    </ul>
                </div>-->
            </div>
        </div>
    </div>
</footer>
<!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
<div class="scroll-top page-scroll visible-xs visible-sm">
	<a class="btn btn-primary" href="#page-top"> <i
		class="fa fa-chevron-up"></i>
	</a>
</div>
<?php echo $this->Html->script(array('Front/bootstrap.min','Front/jquery.easing.min',/*'Front/classie','Front/cbpAnimatedHeader',*/'Front/freelancer.js','cbpAnimatedHeader.js','classie.js','../assets/plugins/bootstrap-modal/js/bootstrap-modal','../assets/plugins/bootstrap-modal/js/bootstrap-modalmanager','../assets/js/ui-modals','Front/script.js','Front/jquery.nav.js'));?>


<script type="text/javascript">
$( ".underline" ).hover(function() {
  $( "#testdiv" ).fadeIn(1300);
});
$( "#closebtn" ).click(function() {
  $( "#testdiv" ).hide();
});
$( ".pic" ).hover(function() {
 ulid = $(this).attr('id');
 $( ".testimonial-right" ).hide();	
 $( "#text" + ulid).show();
 
});
$(document).ready(function(){
	var tmp = $.fn.tooltip.Constructor.prototype.show;
	$.fn.tooltip.Constructor.prototype.show = function () {
	  tmp.call(this);
	  
	  if (this.options.callback) {
	    this.options.callback();
	  }
	}
	$('[data-toggle="tooltip"]').tooltip();
	$('.custom_tooltip').tooltip({
	     html: true,
		  callback: function() { 
			  var postUrl = "<?php echo Router::url(array('controller'=>'businessOwners','action'=>'ajaxUpdateLevelMembership'))?>";
			  $.ajax({
	                'type': 'post',
	                'data': {'update':'membershil_popup'},
	                'url': postUrl,
	                success: function (msg) {
		                	                    
	                }
	            });
		  } 
		});
	<?php if(isset($search) && $search!='') {?>
    $('.clearable').addClass('x');
  <?php }?>
});
</script>
<?php echo $this->Js->writeBuffer(); ?>
</body>
</html>
