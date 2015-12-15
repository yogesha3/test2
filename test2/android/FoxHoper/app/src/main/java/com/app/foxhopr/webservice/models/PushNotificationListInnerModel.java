package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by a3logics on 28/07/15.
 */
public class PushNotificationListInnerModel {

    @SerializedName("weeklySummery")
    public String weeklySummery;

    @SerializedName("receiveReferral")
    public String receiveReferral;

    @SerializedName("commentMadeOnReferral")
    public String commentMadeOnReferral;

    @SerializedName("receiveMessage")
    public String receiveMessage;

    @SerializedName("commentMadeOnMessage")
    public String commentMadeOnMessage;

    @SerializedName("receiveEventInvitation")
    public String receiveEventInvitation;

    @SerializedName("commentMadeOnEvent")
    public String commentMadeOnEvent;



    private boolean selected;
    private boolean isChecked;

    public String getReceiveMessage() {
        return receiveMessage;
    }

    public void setReceiveMessage(String receiveMessage) {
        this.receiveMessage = receiveMessage;
    }

    public String getWeeklySummery() {
        return weeklySummery;
    }

    public void setWeeklySummery(String weeklySummery) {
        this.weeklySummery = weeklySummery;
    }

    public String getReceiveReferral() {
        return receiveReferral;
    }

    public void setReceiveReferral(String receiveReferral) {
        this.receiveReferral = receiveReferral;
    }

    public String getCommentMadeOnReferral() {
        return commentMadeOnReferral;
    }

    public void setCommentMadeOnReferral(String commentMadeOnReferral) {
        this.commentMadeOnReferral = commentMadeOnReferral;
    }

    public String getCommentMadeOnMessage() {
        return commentMadeOnMessage;
    }

    public void setCommentMadeOnMessage(String commentMadeOnMessage) {
        this.commentMadeOnMessage = commentMadeOnMessage;
    }

    public String getReceiveEventInvitation() {
        return receiveEventInvitation;
    }

    public void setReceiveEventInvitation(String receiveEventInvitation) {
        this.receiveEventInvitation = receiveEventInvitation;
    }

    public String getCommentMadeOnEvent() {
        return commentMadeOnEvent;
    }

    public void setCommentMadeOnEvent(String commentMadeOnEvent) {
        this.commentMadeOnEvent = commentMadeOnEvent;
    }

    public boolean isSelected() {
        return selected;
    }
    public void setSelected(boolean selected) {
        this.selected = selected;
    }
    public boolean isChecked() {
        return isChecked;
    }
    public void setIsChecked(boolean isChecked) {
        this.isChecked = isChecked;
    }


}
