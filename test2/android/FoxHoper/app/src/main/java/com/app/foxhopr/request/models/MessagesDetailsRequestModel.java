package com.app.foxhopr.request.models;

/**
 * Created by Chobey R. on 23/9/15.
 */
public class MessagesDetailsRequestModel {

    private String detailPage;


    public String getMessageId() {
        return messageId;
    }

    public void setMessageId(String messageId) {
        this.messageId = messageId;
    }

    private String messageId;

    public String getDetailPage() {
        return detailPage;
    }

    public void setDetailPage(String detailPage) {
        this.detailPage = detailPage;
    }


}
