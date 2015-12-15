package com.app.foxhopr.request.models;

/**
 * Created by Chobey R. on 23/9/15.
 */
public class InboxMessageCommentModel {

    private String detailPage;
    private String messageId;
    private String sendMailTo;
    private String comment;

    public String getMessageId() {
        return messageId;
    }

    public void setMessageId(String messageId) {
        this.messageId = messageId;
    }

    public String getDetailPage() {
        return detailPage;
    }

    public void setDetailPage(String detailPage) {
        this.detailPage = detailPage;
    }

    public String getSendMailTo() {
        return sendMailTo;
    }

    public void setSendMailTo(String sendMailTo) {
        this.sendMailTo = sendMailTo;
    }

    public String getComment() {
        return comment;
    }

    public void setComment(String comment) {
        this.comment = comment;
    }








}
