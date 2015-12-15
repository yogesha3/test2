<?php
/**
 * Plan view page
 * @author Laxmi Saini
 */
?>
<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php 
                 echo $this->Html->link('Plans',array('controller'=>'plans','action'=>'index','admin'=>true));
                ?>
            </li>
            <li class="active">
              <?php echo $plan['Plan']['plan_name'];?>
            </li>
        </ol>
        <div class="page-header">
            <?php 
            echo $this->Html->tag('h1', $plan['Plan']['plan_name']);
            ?>
            
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
                <div class="smart-wizard form-horizontal">
                
                <div id="wizard" class="swMain">
                    <div class="form-group">
                        <?php echo $this->Form->label('', 'Plan Title ' , array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-7">
                            <?php 
                            echo $this->Form->input('Plan.plan_name', array('label' => false, 'class' => 'form-control', 'id' => 'planTitle', 'placeholder' => 'Plan Title','readonly'=>true,'value'=>$plan['Plan']['plan_name']));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo $this->Form->label('', 'Plan Content ' , array('class' => 'col-sm-3 control-label'));?>
                        <div class="col-sm-7">
                            <?php echo $this->Form->textarea('Plan.plan_details', array('rows'=>5,'label' => false, 'class' => 'form-control', 'id' => 'planContent', 'value'=>$plan['Plan']['plan_details'],'readonly'=>true,'style'=>"resize: none")); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->label('', 'Membership Fee ($)' . $this->Html->tag('span', '', array('for' => 'membership_price')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('Plan.membership_price', array('type'=>'text','label' => false, 'class' => 'form-control', 'id' => 'membershipPrice', 'value'=>$plan['Plan']['membership_price'], 'readonly'=>true)); ?>
                        </div>
                    </div>
                    

                    <?php if($id!=$this->Encryption->encode(3)):?>


                    <div class="form-group">
                        
                        <div class="col-sm-4" style="text-align: right">
                             <hr>
                            <?php echo $this->Form->label('','<b>Discount Information (Per Member)</b>',array()); ?> 
                            <hr>
                                    
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo $this->Form->label('', 'No. of Discounted Members ' . $this->Html->tag('span', '', array('for' => 'discountedMembers')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('Plan.discounted_members', array('type'=>'text','label' => false, 'class' => 'form-control', 'id' => 'discountedMembers', 'value'=>$plan['Plan']['discounted_members'], 'readonly'=>true)); ?>
                        </div>
                        <?php echo $this->Form->label('', 'Discounted Price ($)' . $this->Html->tag('span', '', array('for' => 'discountedFee')), array('class' => 'col-sm-3 control-label')); ?>
                        <div class="col-sm-2">
                            <?php echo $this->Form->input('Plan.discounted_amount', array('type'=>'text','label' => false, 'class' => 'form-control', 'id' => 'discountedFee', 'value'=>$plan['Plan']['discounted_amount'], 'readonly'=>true)); ?>
                        </div>
                    </div>

                    
                <?php endif;?>
                <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-8">
                        <?php 
                        echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i> Back', array('type' => 'button', 'class' => 'btn btn-light-grey go-back pull-right')),array('controller'=>'plans','action'=>'index','admin'=>true), array('escape'=>false));
                        ?>
                        </div>
                    </div>

                </div>
            
        </div>

            </div>
        </div>
        <!-- end: FORM WIZARD PANEL -->
    </div>
</div>
