package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by a3logics on 28/07/15.
 */
public class ReferralsListInnerModel {

    @SerializedName("id")
    private String  id;

    @SerializedName("from_user_id")
    private String  from_user_id;

    @SerializedName("to_user_id")
    private String  to_user_id;

    @SerializedName("first_name")
    private String  first_name;

    @SerializedName("last_name")
    private String  last_name;

    @SerializedName("created")
    private String  created;

    @SerializedName("fname")
    private String  fname;

    @SerializedName("lname")
    private String  lname;

    @SerializedName("referral_status")
    private String  referral_status;

    @SerializedName("monetary_value")
    private String  monetary_value;

    public Boolean getIs_read() {
        return is_read;
    }

    public void setIs_read(Boolean is_read) {
        this.is_read = is_read;
    }

    @SerializedName("is_read")
    private Boolean  is_read;

    private boolean selected;
    private boolean isChecked;

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

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getFrom_user_id() {
        return from_user_id;
    }

    public void setFrom_user_id(String from_user_id) {
        this.from_user_id = from_user_id;
    }

    public String getTo_user_id() {
        return to_user_id;
    }

    public void setTo_user_id(String to_user_id) {
        this.to_user_id = to_user_id;
    }

    public String getFirst_name() {
        return first_name;
    }

    public void setFirst_name(String first_name) {
        this.first_name = first_name;
    }

    public String getLast_name() {
        return last_name;
    }

    public void setLast_name(String last_name) {
        this.last_name = last_name;
    }

    public String getReferral_status() {
        return referral_status;
    }

    public void setReferral_status(String referral_status) {
        this.referral_status = referral_status;
    }

    public String getCreated() {
        return created;
    }

    public void setCreated(String created) {
        this.created = created;
    }

    public String getFname() {
        return fname;
    }

    public void setFname(String fname) {
        this.fname = fname;
    }

    public String getLname() {
        return lname;
    }

    public void setLname(String lname) {
        this.lname = lname;
    }

    public String getMonetary_value() {
        return monetary_value;
    }

    public void setMonetary_value(String monetary_value) {
        this.monetary_value = monetary_value;
    }
}
