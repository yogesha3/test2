<?php 
/**
 * choose Plan 
 * @author Jitendra
 */
?>
<section class="contact-detail" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header margin0">
                    <div class="about_text">
                        <h1 class="H-Text-Bold">Choose your plan</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12 margin50">
                <div class="row text-center">
                    <div class="col-md-12">
                        <div class="shodow">
                            <div class="box_border" data-hovercolor="#ffdd3f" id="local_plan_area">
                                <article>
                                    <div class="icon_div sti-item " data-type="icon">
                                        <i class="fa fa-group"></i>
                                    </div>
                                    <h5 class="icon_head sti-item" data-type="sText">Local</h5>
                                    <p style="font-size: 15px; margin-bottom: 0px;" class="subhead subhead_bg">Professionals &lt; 50 Miles</p>
                                    <p style="font-size: 15px; margin-bottom: 0px;" class="subhead subhead_bg2">Bi-Monthly Meetings</p>
                                    <p style="font-size: 15px; margin-bottom: 30px;" class="subhead subhead_bg">Quarterly Group Shuffling</p>
                                    <?php echo $this->Html->link('JOIN US', '#',array(
                                        'style' => 'padding: 20px 100px',
                                        'class' => 'become_btn become_btn_border sti-item',
                                        'data-type' => 'sText',
                                        'onclick' =>"choose_plan('local');",
                                        'escape' =>false,
                                        ));?>
                                    
                                </article>
                            </div>
                            <div class="box_border" data-hovercolor="#ffdd3f" id="global_plan_area">
                                <article>
                                    <div class="icon_div " data-type="icon">
                                        <i class=" hi-icon fa fa-globe hvr-pulse"></i>
                                    </div>
                                    <h5 class="icon_head sti-item" data-type="sText">Global</h5>
                                    <p style="font-size: 15px; margin-bottom: 0px;" class="subhead subhead_bg">Professionals &lt; 50 Miles</p>
                                    <p style="font-size: 15px; margin-bottom: 0px;" class="subhead subhead_bg2">Bi-Monthly Meetings</p>
                                    <p style="font-size: 15px; margin-bottom: 30px;" class="subhead subhead_bg">Quarterly Group Shuffling</p>
                                    <?php echo $this->Html->link('JOIN US', '#',array(
                                        'style' => 'padding: 20px 100px',
                                        'class' => 'become_btn become_btn_border sti-item',
                                        'data-type' => 'sText',
                                        'onclick' =>"choose_plan('global');",
                                        'escape' =>false,
                                        ));?>
<!--                                    <a href="#" style="padding: 20px 100px;" data-type="sText"
                                       class="become_btn become_btn_border sti-item" onclick="choose_plan('global')">JOIN US</a>-->
                                </article>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="text-left listoption_text">
                            <p>
                                Don't feel like networking, try our 
                                   <?php echo $this->Html->link('Listing Only', '#',array(
                                        'class' => 'signup-link',
                                        'onclick' =>"choose_plan('listing');",
                                        'escape' =>false,
                                        ))." option";?>                                
                            </p>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->create('User',array('id'=>'planform','type'=>'post','url'=>array('action'=>'choosePlan')));?>
                <div class="row text-center">
                    <div class="col-lg-12">
                        <div class="clearfix">&nbsp;</div>
                        <div class="clearfix">&nbsp;</div>
                        <?php echo $this->Html->link("Proceed", '#', array(
                            'id' => 'join_plan',
                            'style' => 'padding: 12px 65px',
                            'class' => 'become_btn1 become_btn',
                            'escape' =>false
                            ));?>
                        <!--<a href="#" id="join_plan" style="padding: 12px 65px" class="become_btn1 become_btn"> Proceed</a>-->
			<?php echo $this->Form->hidden('User.plan_selected',array('id'=>'plan_selected'));?>
                    </div>
                </div>
		<?php echo $this->Form->end();?>
                <div class="clearfix">&nbsp;</div>
                <div class="clearfix">&nbsp;</div>
                <div class="row ">
                    <div class="col-md-6 col-sm-6 hero-feature col-md-offset-3">
                        <div class="thumbnail">
                            <h3 class="forums_h col-md-12 border_plan_box">Every plan includes ~</h3>
                            <div class="caption">
                                <p class="label_text margin_clear">
                                    <i class="fa fa-caret-right arrow_icon"></i> Unlimited support.
                                </p>
                                <p class="label_text margin_clear">
                                    <i class="fa fa-caret-right arrow_icon"></i> There are many variations of passages.
                                </p>
                                <p class="label_text margin_clear">
                                    <i class="fa fa-caret-right arrow_icon"></i> Unlimited support </p>
                                <p class="label_text margin_clear">
                                    <i class="fa fa-caret-right arrow_icon"></i> There are many variations.
                                </p>
                                <p class="label_text margin_clear">
                                    <i class="fa fa-caret-right arrow_icon"></i> Unlimited support.</p>
                                <p class="label_text margin_clear">
                                    <i class="fa fa-caret-right arrow_icon"></i> There are many variations of passages.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script type="text/javascript">
    function choose_plan(plan) {
        $("#plan_selected").val(plan);
    }
    $("#join_plan").click(function () {
        if ($("#plan_selected").val() == "") {
            $("#local_plan_area").css("border", "2px solid red");
            return false;
        }
        $("#planform").submit();
    });
</script>