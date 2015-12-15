<?php
/**
 * FAQ detailed view
 */
?> 
<div class="inner_pages_heading">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="intro-text">
                    <span class="inner_page_name">FAQ</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<section id="inner_pages_top_gap">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="sub_head"><?php echo $faqData['Faq']['question'];?></div>
            </div>
            <div class="col-sm-12 ">
           <p><?php echo $faqData['Faq']['answers'];?></p> </div>
        </div>
    </div>
</section>
<div class="clearfix"></div>
</div>
