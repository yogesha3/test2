package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 17/8/15.
 */
public class NotificationListModel {
    @SerializedName("referralUnread")
    private Integer  referralUnread;

    @SerializedName("messageUnread")
    private Integer  messageUnread;

    @SerializedName("referralTotalUnread")
    private Integer  referralTotalUnread;

    @SerializedName("messageTotalUnread")
    private Integer  messageTotalUnread;

    @SerializedName("total")
    private Integer  total;

    @SerializedName("is_login")
    private String  is_login;

    public Integer getReferralTotalUnread() {
        return referralTotalUnread;
    }

    public void setReferralTotalUnread(Integer referralTotalUnread) {
        this.referralTotalUnread = referralTotalUnread;
    }

    public Integer getMessageTotalUnread() {
        return messageTotalUnread;
    }

    public void setMessageTotalUnread(Integer messageTotalUnread) {
        this.messageTotalUnread = messageTotalUnread;
    }




    public String getIs_login() {
        return is_login;
    }

    public void setIs_login(String is_login) {
        this.is_login = is_login;
    }

    public Integer getReferralUnread() {
        return referralUnread;
    }

    public void setReferralUnread(Integer referralUnread) {
        this.referralUnread = referralUnread;
    }

    public Integer getMessageUnread() {
        return messageUnread;
    }

    public void setMessageUnread(Integer messageUnread) {
        this.messageUnread = messageUnread;
    }

    public Integer getTotal() {
        return total;
    }

    public void setTotal(Integer total) {
        this.total = total;
    }


}
