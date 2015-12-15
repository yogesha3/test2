package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 1/10/15.
 */
public class PartnerListBean {
    @SerializedName("partner_name")
    private String  partner_name;

    @SerializedName("partner_email")
    private String  partner_email;

    public String getPartner_name() {
        return partner_name;
    }

    public void setPartner_name(String partner_name) {
        this.partner_name = partner_name;
    }

    public String getPartner_email() {
        return partner_email;
    }

    public void setPartner_email(String partner_email) {
        this.partner_email = partner_email;
    }


}
