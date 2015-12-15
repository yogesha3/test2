package com.app.foxhopr.request.models;

/**
 * Created by Chobey R. on 28/9/15.
 */
public class MessageReadUnreadRequestModel {
    private String listPage;
    private String messageId;
    private String messageStatus;

    public String getMessageStatus() {
        return messageStatus;
    }

    public void setMessageStatus(String messageStatus) {
        this.messageStatus = messageStatus;
    }

    public String getMessageId() {
        return messageId;
    }

    public void setMessageId(String messageId) {
        this.messageId = messageId;
    }

    public String getListPage() {
        return listPage;
    }

    public void setListPage(String listPage) {
        this.listPage = listPage;
    }
}