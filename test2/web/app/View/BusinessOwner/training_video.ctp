<div class="row margin_top_referral_search">
    <div class="col-md-9 col-sm-8 video_frame">      
        <div class="row">
            <div class="col-md-8">
                <div class="referrals_reviews">
                    <div class="referrals_reviews_head padd-top0">TRAINING VIDEO </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-4 text-right">
            </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <input id="videoPlayTime" value="1" type="hidden"></input>
        <div class="video_here training_video">
        <img id="playbutton" onclick="checkDuration('play');" src="../img/play-video.png" class="play-video" />
          
        
            <?php echo $this->Html->media(
                array(
                    '../trainingvideo/'.$video['Trainingvideo']['video_name'],
                    ),
                array('autoplay','id'=>'video1','width'=>836)
                ); ?>

            </div>   


        </div>
        <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'videotraining'));?>
    </div>
    <?php
    echo $this->Html->script ( 'Front/trainingvideo' );