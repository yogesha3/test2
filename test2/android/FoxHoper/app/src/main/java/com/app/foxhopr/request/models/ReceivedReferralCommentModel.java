package com.app.foxhopr.request.models;

/**
 * Created by Chobey R. on 19/8/15.
 */
public class ReceivedReferralCommentModel {

    private String detailPage;
    private String referralId;
    private String type;
    private String comment;


    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getDetailPage() {
        return detailPage;
    }

    public void setDetailPage(String detailPage) {
        this.detailPage = detailPage;
    }

    public String getReferralId() {
        return referralId;
    }

    public void setReferralId(String referralId) {
        this.referralId = referralId;
    }

    public String getComment() {
        return comment;
    }

    public void setComment(String comment) {
        this.comment = comment;
    }


}
