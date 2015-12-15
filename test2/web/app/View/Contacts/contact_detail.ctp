<div class="row margin_top_referral_search">
    <div class="col-md-9 col-sm-8">
        <div class="row"> 
            <div class="col-md-8">
                <div class="referrals_reviews">
                    <div class="referrals_reviews_head padd-top0">Contact Detail <?php echo $this->Html->link('', array('controller' => 'contacts', 'action' => 'contactUpdate', $contactData['Contact']['id']), array('class' => 'fa fa-edit referral_edit', 'title'=>'Edit'))?> </div>
                    <div class="clearfix"></div>
                </div>
            </div>
			<div class="col-md-4 text-right">
        		<?php if (!empty($referer)) {?>
                	<a href="<?php echo base64_decode($referer);?>" class="btn btn-sm back_btn_new pull-right text-center padauto "><i class="fa fa-arrow-circle-left"></i> Back</a>
    			<?php } ?>
    		</div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="row">
            <div class="col-md-12">
                <div class="referral_profile_head"></div>
            </div>
            <div class="col-md-12">
                <div class="media  edit_profile">
                    <div class="media-body">
                        <h4 class="media-heading"><?php echo ucfirst($contactData['Contact']['first_name']) . " " . ucfirst($contactData['Contact']['last_name']); ?></h4>
                        <div class="contact_detial_text">
                            <?php
                                $jobTitle = !empty($contactData['Contact']['job_title']) ? ucfirst($contactData['Contact']['job_title']) : '';
                                $company = !empty($contactData['Contact']['company']) ? "<span>" . ucfirst($contactData['Contact']['company']) . "</span>" : '';
                                if (!empty($jobTitle) && !empty($company)) {
                                    echo !empty($jobTitle) ? $jobTitle . ", " . $company : '';
                                } elseif (!empty($jobTitle)) {
                                    echo $jobTitle;
                                } elseif (!empty($company)) {
                                    echo "<span>" . $company . "</span>";
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="clearfix">&nbsp;</div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 col-xs-12 office_ph"><i class="fa fa-mobile"></i> Mobile <br><span> <?php echo !empty($contactData['Contact']['mobile']) ? $contactData['Contact']['mobile'] : 'NA'; ?></span></div>
                    <div class="col-md-6 col-xs-12 office_ph"><i class="fa fa-phone"></i> Office Phone <br><span><?php echo !empty($contactData['Contact']['office_phone']) ? $contactData['Contact']['office_phone'] : 'NA'; ?><!--339.330.3330--></span></div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="col-md-12 col-xs-12 office_ph"><i class="fa fa-map-marker"></i> Address <br><span>
                        <?php
                            if (trim($contactData['Contact']['address']) != "")
                                $addressinfo1[] = $contactData['Contact']['address'];
                            if (trim($contactData['Contact']['city']) != "")
                                $addressinfo1[] = $contactData['Contact']['city'];
                            if (trim($contactData['Contact']['zip']) != "")
                                $addressinfo1[] = $contactData['Contact']['zip'];
                            if (trim($contactData['State']['state_subdivision_name']) != "")
                                $addressinfo1[] = $contactData['State']['state_subdivision_name'];
                            if (trim($contactData['Country']['country_name']) != "")
                                $addressinfo1[] = $contactData['Country']['country_name'];
                            echo !empty($addressinfo1) ? implode(",&nbsp;", array_filter($addressinfo1)) : 'NA';
                        ?>
                    </span></div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="col-md-6 col-xs-12 office_ph"><i class="fa fa-chrome"></i> Website <br><span>
                        <?php
                            $website_link = (strlen($contactData['Contact']['website']) > 30 ) ? substr($contactData['Contact']['website'], 0 , 30)."..." : $contactData['Contact']['website'];
                            $formatURL = $this->Functions->formatURL($contactData['Contact']['website']);?>
                        <?php echo !empty($contactData['Contact']['website']) ? '<a href="'.$formatURL.'" target="_blank" title="'.$contactData['Contact']['website'].'">'.$website_link.'</a>' : "NA"; ?>
                    </span></div>
                    <div class="col-md-6 col-xs-12 office_ph"><i class="fa fa-envelope"></i> Email <br><span>
                        <?php
                            $email = (strlen($contactData['Contact']['email']) > 30 ) ? substr($contactData['Contact']['email'], 0 , 30)."..." : $contactData['Contact']['email'];

                            echo !empty($contactData['Contact']['email']) ?  '<a href="mailto:'.$contactData['Contact']['email'].'" title="'.$contactData['Contact']['email'].'">'.$email.'</a>' : 'NA';
                        ?>
                    </span></div>
                </div>
            <!--<button class="btn btn-sm file_sent_btn pull-right" type="button">Back</button>-->
            </div>
        </div>
    </div>
    <?php echo $this->element("Front/loginSidebar",array('tabpage' => 'contactDetail'));?>
</div>
<?php echo $this->element('Front/bottom_ads');