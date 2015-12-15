package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 3/12/15.
 */
public class SocialMediaDetailsLinkedin {

    @SerializedName("twitter")
    private boolean  twitter;

    @SerializedName("facebook")
    private boolean  facebook;

    @SerializedName("linkedin")
    private boolean  linkedin;

    @SerializedName("linkedinReferralSend")
    private boolean  linkedinReferralSend;


    @SerializedName("linkedinMessageSend")
    private boolean  linkedinMessageSend;

    @SerializedName("linkedinInviteSend")
    private boolean  linkedinInviteSend;



    public boolean isLinkedinReferralSend() {
        return linkedinReferralSend;
    }

    public void setLinkedinReferralSend(boolean linkedinReferralSend) {
        this.linkedinReferralSend = linkedinReferralSend;
    }

    public boolean isLinkedinMessageSend() {
        return linkedinMessageSend;
    }

    public void setLinkedinMessageSend(boolean linkedinMessageSend) {
        this.linkedinMessageSend = linkedinMessageSend;
    }

    public boolean isLinkedinInviteSend() {
        return linkedinInviteSend;
    }

    public void setLinkedinInviteSend(boolean linkedinInviteSend) {
        this.linkedinInviteSend = linkedinInviteSend;
    }


    public boolean getFacebook() {
        return facebook;
    }

    public void setFacebook(boolean facebook) {
        this.facebook = facebook;
    }

    public boolean getTwitter() {
        return twitter;
    }

    public void setTwitter(boolean twitter) {
        this.twitter = twitter;
    }

    public boolean getLinkedin() {
        return linkedin;
    }

    public void setLinkedin(boolean linkedin) {
        this.linkedin = linkedin;
    }


}
