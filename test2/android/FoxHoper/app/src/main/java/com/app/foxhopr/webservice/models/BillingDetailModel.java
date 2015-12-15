package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 12/10/15.
 */
public class BillingDetailModel {

    @SerializedName("group_type")
    private String  group_type;

    @SerializedName("subscription_status")
    private boolean  subscription_status;

    @SerializedName("credit_card_number")
    private String  credit_card_number;

    @SerializedName("amount_local")
    private String  amount_local;

    @SerializedName("amount_global")
    private String  amount_global;

    @SerializedName("last_updated")
    private boolean  last_updated;

    public boolean getLast_updated() {
        return last_updated;
    }

    public void setLast_updated(boolean last_updated) {
        this.last_updated = last_updated;
    }




    public String getCredit_card_number() {
        return credit_card_number;
    }

    public void setCredit_card_number(String credit_card_number) {
        this.credit_card_number = credit_card_number;
    }

    public String getGroup_type() {
        return group_type;
    }

    public void setGroup_type(String group_type) {
        this.group_type = group_type;
    }

    public boolean getSubscription_status() {
        return subscription_status;
    }

    public void setSubscription_status(boolean subscription_status) {
        this.subscription_status = subscription_status;
    }

    public String getAmount_local() {
        return amount_local;
    }

    public void setAmount_local(String amount_local) {
        this.amount_local = amount_local;
    }

    public String getAmount_global() {
        return amount_global;
    }

    public void setAmount_global(String amount_global) {
        this.amount_global = amount_global;
    }



}
