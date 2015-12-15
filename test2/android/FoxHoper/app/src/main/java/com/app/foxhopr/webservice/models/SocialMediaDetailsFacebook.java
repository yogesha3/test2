package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 3/12/15.
 */
public class SocialMediaDetailsFacebook {

    @SerializedName("twitter")
    private boolean  twitter;

    @SerializedName("facebook")
    private boolean  facebook;

    @SerializedName("linkedin")
    private boolean  linkedin;

    @SerializedName("fbReferralSend")
    private boolean  fbReferralSend;


    @SerializedName("fbMessageSend")
    private boolean  fbMessageSend;

    @SerializedName("fbInviteSend")
    private boolean  fbInviteSend;

    public boolean isFbMessageSend() {
        return fbMessageSend;
    }

    public void setFbMessageSend(boolean fbMessageSend) {
        this.fbMessageSend = fbMessageSend;
    }

    public boolean isFbInviteSend() {
        return fbInviteSend;
    }

    public void setFbInviteSend(boolean fbInviteSend) {
        this.fbInviteSend = fbInviteSend;
    }

    public boolean isFbReferralSend() {
        return fbReferralSend;
    }

    public void setFbReferralSend(boolean fbReferralSend) {
        this.fbReferralSend = fbReferralSend;
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
