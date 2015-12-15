package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 6/10/15.
 */
public class ContactDetailModel {
    @SerializedName("id")
    private String  id;

    @SerializedName("user_id")
    private String  user_id;

    @SerializedName("user_groupid")
    private String  user_groupid;

    @SerializedName("first_name")
    private String  first_name;

    @SerializedName("last_name")
    private String  last_name;

    @SerializedName("company")
    private String  company;

    @SerializedName("job_title")
    private String  job_title;

    @SerializedName("address")
    private String  address;

    @SerializedName("country_id")
    private String  country_id;

    @SerializedName("state_id")
    private String  state_id;

    @SerializedName("city")
    private String  city;

    @SerializedName("zip")
    private String  zip;

    @SerializedName("office_phone")
    private String  office_phone;

    @SerializedName("mobile")
    private String  mobile;

    @SerializedName("email")
    private String  email;

    @SerializedName("website")
    private String  website;

    @SerializedName("note")
    private String  note;

    @SerializedName("created")
    private String  created;

    @SerializedName("modified")
    private String  modified;

    @SerializedName("country_name")
    private String  country_name;

    @SerializedName("state_name")
    private String  state_name;

    public String getMobile() {
        return mobile;
    }

    public void setMobile(String mobile) {
        this.mobile = mobile;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getUser_id() {
        return user_id;
    }

    public void setUser_id(String user_id) {
        this.user_id = user_id;
    }

    public String getUser_groupid() {
        return user_groupid;
    }

    public void setUser_groupid(String user_groupid) {
        this.user_groupid = user_groupid;
    }

    public String getLast_name() {
        return last_name;
    }

    public void setLast_name(String last_name) {
        this.last_name = last_name;
    }

    public String getFirst_name() {
        return first_name;
    }

    public void setFirst_name(String first_name) {
        this.first_name = first_name;
    }

    public String getCompany() {
        return company;
    }

    public void setCompany(String company) {
        this.company = company;
    }

    public String getJob_title() {
        return job_title;
    }

    public void setJob_title(String job_title) {
        this.job_title = job_title;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public String getState_id() {
        return state_id;
    }

    public void setState_id(String state_id) {
        this.state_id = state_id;
    }

    public String getCountry_id() {
        return country_id;
    }

    public void setCountry_id(String country_id) {
        this.country_id = country_id;
    }

    public String getCity() {
        return city;
    }

    public void setCity(String city) {
        this.city = city;
    }

    public String getZip() {
        return zip;
    }

    public void setZip(String zip) {
        this.zip = zip;
    }

    public String getOffice_phone() {
        return office_phone;
    }

    public void setOffice_phone(String office_phone) {
        this.office_phone = office_phone;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getWebsite() {
        return website;
    }

    public void setWebsite(String website) {
        this.website = website;
    }

    public String getNote() {
        return note;
    }

    public void setNote(String note) {
        this.note = note;
    }

    public String getCreated() {
        return created;
    }

    public void setCreated(String created) {
        this.created = created;
    }

    public String getModified() {
        return modified;
    }

    public void setModified(String modified) {
        this.modified = modified;
    }

    public String getCountry_name() {
        return country_name;
    }

    public void setCountry_name(String country_name) {
        this.country_name = country_name;
    }

    public String getState_name() {
        return state_name;
    }

    public void setState_name(String state_name) {
        this.state_name = state_name;
    }

}
