package com.app.foxhopr.request.models;

/**
 * Created by Chobey R. on 19/8/15.
 */
public class ReferralsDetailsRequestModel {

    private String detailPage;

    public String getReferralId() {
        return referralId;
    }

    public void setReferralId(String referralId) {
        this.referralId = referralId;
    }

    private String referralId;

    public String getDetailPage() {
        return detailPage;
    }

    public void setDetailPage(String detailPage) {
        this.detailPage = detailPage;
    }


}
