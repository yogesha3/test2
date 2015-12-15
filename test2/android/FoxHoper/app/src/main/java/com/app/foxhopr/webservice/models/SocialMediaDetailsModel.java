package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 3/12/15.
 */
public class SocialMediaDetailsModel {

    @SerializedName("twitter")
    private boolean  twitter;

    @SerializedName("facebook")
    private boolean  facebook;

    @SerializedName("linkedin")
    private boolean  linkedin;

    @SerializedName("tweetReferralSend")
    private boolean  tweetReferralSend;

    @SerializedName("tweetMessageSend")
    private boolean  tweetMessageSend;

    @SerializedName("tweetInviteSend")
    private boolean  tweetInviteSend;


    public boolean isTweetReferralSend() {
        return tweetReferralSend;
    }

    public void setTweetReferralSend(boolean tweetReferralSend) {
        this.tweetReferralSend = tweetReferralSend;
    }

    public boolean isTweetMessageSend() {
        return tweetMessageSend;
    }

    public void setTweetMessageSend(boolean tweetMessageSend) {
        this.tweetMessageSend = tweetMessageSend;
    }

    public boolean isTweetInviteSend() {
        return tweetInviteSend;
    }

    public void setTweetInviteSend(boolean tweetInviteSend) {
        this.tweetInviteSend = tweetInviteSend;
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
