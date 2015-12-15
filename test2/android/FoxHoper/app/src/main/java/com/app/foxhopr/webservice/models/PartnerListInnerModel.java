package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 7/10/15.
 */
public class PartnerListInnerModel {

    @SerializedName("id")
    private String id;

    @SerializedName("invitee_name")
    private String invitee_name;

    @SerializedName("invitee_email")
    private String invitee_email;

    @SerializedName("status")
    private String status;

    @SerializedName("referral_amount")
    private String referral_amount;

    @SerializedName("created")
    private String created;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getInvitee_name() {
        return invitee_name;
    }

    public void setInvitee_name(String invitee_name) {
        this.invitee_name = invitee_name;
    }

    public String getInvitee_email() {
        return invitee_email;
    }

    public void setInvitee_email(String invitee_email) {
        this.invitee_email = invitee_email;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public String getReferral_amount() {
        return referral_amount;
    }

    public void setReferral_amount(String referral_amount) {
        this.referral_amount = referral_amount;
    }

    public String getCreated() {
        return created;
    }

    public void setCreated(String created) {
        this.created = created;
    }


}
