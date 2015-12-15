<?php $show = isset($this->request->data['Newsletter']['subscriber_list']) ? "display: block;" : "display: none;"?>
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Newsletters', array('controller' => 'newsletters', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">
                Send Newsletter
            </li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', 'Send Newsletter');?>
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
                echo $this->Form->create('newsletters', array('url' => array('controller' => 'newsletters', 'action' => 'sendNewsletter', 'admin'=>true), 'class' => 'smart-wizard form-horizontal', 'id' => 'sendNewsletterForm','inputDefaults' => array('errorMessage' => true)));
                ?>
                <div id="wizard" class="swMain">
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Subscriber\'s List'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'choosetemplate')), array('class' => 'col-sm-5 control-label')); ?>
                        <div class="col-sm-4">
                            <?php echo $this->Form->select('Newsletter.subscriber_list', array('all'=>'All','register_user'=>'Registered Users','not_register_user'=>'Unregistered Users'),array('label' => false, 'empty'=>false, 'class' => 'form-control', 'id' => 'subscriber_list'));?>
                        </div>
                    </div>
                    <div class="form-group" id="professionList" style="<?php echo $show;?>">
                        <?php echo $this->Form->label('', 'Select Profession'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'choosetemplate')), array('class' => 'col-sm-5 control-label')); ?>
                        <div class="col-sm-4">
                            <?php echo $this->Form->select('Newsletter.profession_list', $profesionList, array('label' => false, 'class' => 'form-control', 'id' => 'profession_list','multiple'=>true));?>
                        </div>
                    </div> 
                    <div class="clearfix"></div>                   
                    <div class="form-group">
                            <?php echo $this->Form->label('', 'Choose Template'.$this->Html->tag('span', '', array('class' => 'symbol required', 'for' => 'choosetemplate')), array('class' => 'col-sm-5 control-label')); ?>
                        <div class="col-sm-4">
                            <?php echo $this->Form->select('Newsletter.template_id', $templateList, array('label' => false, 'class' => 'form-control', 'id' => 'template_list', 'empty'=>'Select a template'));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->label('', '&nbsp;', array('class' => 'col-sm-5')); ?>
                        <div class="col-sm-4">
                            <div class="input text pull-right">
		                       <?php echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i>Back', array('type' => 'button', 'class' => 'btn btn-light-grey go-back')), array('controller' => 'newsletters', 'action' => 'index', 'admin' => true), array('escape' => false)); ?>&nbsp;
                               <?php echo $this->Form->button('Send <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky pull-right')); ?>
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
$('#subscriber_list').on('change', function() {
	$('#professionList').hide();
	if(this.value=='register_user'){
		$('#professionList').show();
	}
});
$("select#profession_list").select2({
    placeholder: "All",
    allowClear: true
});
</script>
<style>.select2-container{height:auto}</style>
