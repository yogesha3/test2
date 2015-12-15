<?php
/**
 *  add Coupon Form page
 *  @author Rohan Julka
 */
?>
<!-- start: PAGE HEADER -->
<?php
echo $this->Html->css('../assets/plugins/bootstrap-datepicker/css/datepicker');
echo $this->Html->script('../assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker');
echo $this->Html->css('../assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min');
echo $this->Html->script('../assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min');
echo $this->Html->css('../assets/plugins/jQuery-Tags-Input/jquery.tagsinput');
echo $this->Html->script('../assets/plugins/jQuery-Tags-Input/jquery.tagsinput');
$this->assign('title','Coupon Code');
?>

<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Coupon Code', array('controller' => 'coupons', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">
                Add Coupon
            </li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', 'Add Coupon');?>
        </div>
        <!-- end: PAGE TITLE & BREADCRUMB -->
    </div>
</div>

<!-- end: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: FORM WIZARD PANEL -->
        <div class="panel panel-default">

            <div class="panel-body">
                <?php
                echo $this->Form->create('Coupon', array('url' => array('controller' => 'coupons', 'action' => 'add', 'admin'=>true), 'class' => 'smart-wizard form-horizontal', 'id' => 'addCpnFrm'));
                ?>
                <div id="wizard" class="swMain">
                   
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Coupon Name'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'couponName')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-3">

                            <?php echo $this->Form->input('coupon_code', array('label' => false, 'class' => 'form-control', 'id' => 'couponName', 'placeholder'=>'Coupon Name','maxlength'=>9,'autocomplete'=>"off"));?>
                        </div>
                        <div class="col-sm-3 "><span class="pad_tp">OR &nbsp;&nbsp;&nbsp;<a href="#" class="gen_code">Generate Coupon</a></span></div>
                    </div>
                   
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Discount %'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'discountRate')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-2">

                            <?php echo $this->Form->input('discount_amount', array('type'=>'text','label' => false, 'class' => 'form-control', 'id' => 'discountRate', 'placeholder'=>'0', 'maxlength'=>3,'autocomplete'=>"off"));?>
                        </div>
                    </div>
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Coupon Start Date'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'start_date')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <?php
                                    echo $this->Form->input('start_date',array('type'=>'text','label'=>false,'data-date-viewmode'=>'years','data-date-format'=>'dd-mm-yyyy','class'=>'form-control date-picker','id'=>'start_date','readonly'=>'readonly','autocomplete'=>"off"));
                                ?>
                                <!--<input type="text" data-date-format="dd-mm-yyyy" data-date-viewmode="years" class="form-control date-picker">-->
                                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Coupon End Date'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'expiry_date')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <?php
                                    echo $this->Form->input('expiry_date',array('type'=>'text','label'=>false,'data-date-viewmode'=>'years','data-date-format'=>'dd-mm-yyyy','class'=>'form-control date-picker','id'=>'expiry_date','readonly'=>'readonly','autocomplete'=>"off"));
                                ?>
                                <!--<input type="text" data-date-format="dd-mm-yyyy" data-date-viewmode="years" class="form-control date-picker">-->
                                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
                            </div>
                        </div>
                    </div>
                   
                   <div class="form-group">
                            <?php echo $this->Form->label('', 'Coupon Type'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'metaTitle')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
							<label class="radio-inline">
                                <input type="radio" value="all" name="data[Coupon][coupon_types]" class="grey cpn_types" checked="checked" >
                                All
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="email" name="data[Coupon][coupon_types]" class="grey cpn_types" >
                                Email
                            </label>
                            <?php 
                            //$options = array('all' => 'All', 'email' => '');
                            //$attributes = array('legend' => false,'label'=>array('class'=>''),'separator'=>'&nbsp;','class'=>'');
                            //echo $this->Form->radio('coupon_types', $options, $attributes);
                            ?>
                        </div>
                    </div>
                    <div class="form-group type_cpn">
                            <?php echo $this->Form->label('', '', array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
							<label class="radio-inline">
                                <input type="radio" value="public" name="data[Coupon][coupon_type]" class="grey " checked="checked" >
                                Public
                            </label>
                            <div clas="clearfix"></div>
                            <label class="radio-inline">
                                <input type="radio" value="private" name="data[Coupon][coupon_type]" class="grey " >
                                Private
                            </label>
                            <?php 
                            //$options = array('public' => 'Public', 'private' => 'Private');
                           // $attributes = array('legend' => false,'label'=>array('class'=>''),'separator'=>'<div class="clearfix"></div>');
                            //echo $this->Form->radio('coupon_type', $options, $attributes);
                            ?>
                        </div>
                    </div>
                     <div class="form-group type_cpn ">
                            <?php echo $this->Form->label('', 'Usage Limit'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'usageLimit')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-2">

                            <?php echo $this->Form->input('usage_limit', array('type'=>'text','label' => false, 'class' => 'form-control', 'id' => 'usageLimit', 'maxlength'=>5,'value'=>0,'autocomplete'=>"off"));?>
                            
                        </div>
                        <div class="col-sm-4">Enter 0 for unlimited usage.</div>
                    </div>

					<div class="form-group hidden email_tag">
                            <?php echo $this->Form->label('', 'Enter Email'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'user_email')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-5">

                            <?php echo $this->Form->input('user_email', array('type'=>'text','label' => false, 'class' => 'form-control ignore', 'id' => 'user_email','autocomplete'=>"off"));?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"></label>                            
                        <div class="col-sm-2">
                            <div class="input text"></div>
                        </div>
                        <label class="col-sm-1 control-label"></label>
                        <div class="col-sm-4">
                            <div class="input text pull-right">
                           <?php echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky ladda-button','data-style'=>'slide-left'));?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        <!-- end: FORM WIZARD PANEL -->
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
    	$('#expiry_date').datepicker({
        	startDate: "today",
        	autoclose: true,
                format:"mm-dd-yyyy",
        }).on('changeDate', function(ev){
           $('#expiry_date').valid();
          
        });
        $('#start_date').datepicker({
            autoclose: true,
            startDate: "today",
            format:"mm-dd-yyyy",
        }).on('changeDate', function(ev){
           $('#start_date').valid();
            var date2 = $(this).datepicker( "getDate" );
            $('#expiry_date').datepicker('setStartDate',date2);
            $('#expiry_date').val('');
          
        });
        $('.cpn_types').on('ifClicked', function(event){
        	if($(this).val()=='all'){
                $('#usageLimit').removeClass('ignore');
                $('#usageLimit').removeClass('error');
				$('.type_cpn').removeClass('hidden');
			}else{
                $('#usageLimit').addClass('ignore');
				$('.type_cpn').addClass('hidden');
            }
			if($(this).val()=='email'){
                $('#user_email').removeClass('ignore');
                $('#user_email_tagsinput').removeClass('error');
				$('.email_tag').removeClass('hidden');
            }else{
                $('#user_email').addClass('ignore');
                $('.email_tag').addClass('hidden');
            }
               
        	});
        $('.gen_code').click(function(e){
            $("#couponName").focus();
            e.preventDefault();
        	var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for( var i=0; i < 9; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));
			
           	$(this).closest('.form-group').find('#couponName').val(text);
        });
        $("#couponName").keypress(function (event) {
        	var regex = new RegExp("^[a-zA-Z0-9\b]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
               event.preventDefault();
               return false;
            }
        });
        $("#usageLimit").keypress(function (e) {
                 //if the letter is not digit then display error and don't type anything
         if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
         	return false;
          }
        })
        var emailRegex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        $('#user_email').tagsInput({
            defaultText:'Add email',
            width: 'auto',
            pattern: emailRegex,
            onAddTag:validateThis,
            onTagExist:alertData
        });
    });
    function validateThis(){
        $('#user_email_tagsinput').removeClass('error');
        $('#user_email').valid();
    }
    function alertData(){
        return false;
    }   
 </script>