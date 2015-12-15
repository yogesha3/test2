<!-- Header -->
<div class="inner_pages_heading" style="background: #fff; border: 0">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="intro-text"></div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<?php echo $this->Form->create('Review',array('id'=>'ratingForm','class'=>'your_comment','type'=>'post','inputDefaults' => array('label' => false,'div' => false,'errorMessage'=>true),'novalidate'=>true));?>
<section id="inner_pages_top_gap">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
            <div class="share_your_review text-left">You are currently reviewing <?php echo $userInfo['BusinessOwners']['fname'] .' '.$userInfo['BusinessOwners']['lname'];?></div>
            </div> 
        	<div class="col-md-4">   
        		<div class="rating_box1">
        			<div class=" member_name">Name <span class="pull-right">:</span></div><div class=" member_name2"><?php echo $userInfo['BusinessOwners']['fname'] .' '.$userInfo['BusinessOwners']['lname'];?></div>
        			<div class="clearfix"></div>
        			<div class=" member_name">Email <span class="pull-right">:</span></div><div class=" member_name2"><?php echo $userInfo['BusinessOwners']['email'];?></div>
        			<div class="clearfix"></div>
        			<div class=" member_name">Country <span class="pull-right">:</span></div><div class=" member_name2"><?php echo $userInfo['Country']['country_name'];?></div>
        			<div class="clearfix"></div>
        			<div class=" member_name">State <span class="pull-right">:</span></div><div class=" member_name2"><?php echo $userInfo['State']['state_subdivision_name'];?></div>
        			<div class="clearfix"></div>
        		</div>
    		</div>             
        </div>    
        <div class="row">
            <div class="col-md-4">
                <div class="rating_box">
                    <div class="rating_box_heading">Services</div>
                    <?php $service = isset($data['Review']['services']) ? $data['Review']['services'] : 0; ?> 
                    <div id="stars-existing" class="starrr stars_rat services" data-rating='<?php echo $service; ?>'></div> 
                    <?php echo $this->Form->input('services',array('type'=>'hidden','id'=>'services' ,'value' => $service));?> 
                </div>
            </div>    
            <div class="col-md-4">
                <div class="rating_box">
                    <div class="rating_box_heading">Knowledge</div>
                    <?php $knowledge = isset($data['Review']['knowledge']) ? $data['Review']['knowledge'] : 0; ?> 
                    <div id="stars-existing" class="starrr stars_rat knowledge" data-rating='<?php echo $knowledge; ?>'></div>  
                    <?php echo $this->Form->input('knowledge',array('type'=>'hidden','id'=>'knowledge', 'value' => $knowledge))?>
                </div>
            </div>   
            <div class="col-md-4">
                <div class="rating_box">
                    <div class="rating_box_heading">Communication</div>
                    <?php $communication = isset($data['Review']['communication']) ? $data['Review']['communication'] : 0; ?>
                    <div id="stars-existing" class="starrr stars_rat communication" data-rating='<?php echo $communication; ?>'></div>  
                    <?php echo $this->Form->input('communication',array('type'=>'hidden','id'=>'communication', 'value' => $communication));?>
                </div>
            </div>
            <div class="col-md-12 "> 
                <div class="rating_box_border_bottom"></div>
            </div> 
            <?php echo $this->Form->input('referral_id',array('type'=>'hidden','value'=>$refid))?>
            <?php echo $this->Form->input('user_id',array('type'=>'hidden','value'=>$userid))?>
        </div>    
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="rating_comment">
                <?php echo $this->Html->image('comment_icon.png')?>  Comment</div>
            </div>      
        </div>
        <div class="row">
            <div class="col-md-12"> 
                <div class="form-group">
                    <?php $comments = isset($data['Review']['comments']) ? $data['Review']['comments'] : NULL; ?>
                    <?php echo $this->Form->textarea('comments',array('rows'=>5,'class'=>'form-control','placeholder'=>'Write your comment..', 'value' => $comments))?>
                    <?php if(isset($data['Review'])) { ?>
                    <div id="reviewpageid">You can edit your review and rating within <label id="timecounter"></label> minutes</div>
                    <?php }?>
                </div>
                <button id="submit" class="back_btn btn pull-right add_focus" type="submit"><?php echo isset($data['Review']) ? 'Update' : 'Submit'; ?></button>
            </div>
        </div>    
    </div> 
</section>
<div class="clearfix"></div>
<div class="clearfix"></div>
<?php echo $this->Form->end();?>
<script>
var action = '<?php echo $action;?>';
var timeStart = '<?php echo $timeleft;?>';
if(timeStart < 900){
	timeStart = 900 - timeStart;
} else {
	timeStart = 0;
}
$( document ).ready(function() {    
    showTimeLeft('timecounter',timeStart);
});
</script>
<?php echo $this->Html->script('Front/rating');?>
