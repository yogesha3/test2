package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by Chobey R. on 20/8/15.
 */
public class ReceivedDetailsModel {
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

    @SerializedName("message")
    private String  message;

    @SerializedName("files")
    private ArrayList<FileModel> files;

    @SerializedName("referral_status")
    private String  referral_status;

    @SerializedName("monetary_value")
    private String  monetary_value;

    @SerializedName("is_read")
    private Boolean  is_read;

    @SerializedName("is_archive")
    private Boolean  is_archive;

    @SerializedName("created")
    private String  created;

    @SerializedName("modified")
    private String  modified;

    @SerializedName("file_name")
    private List<String> file_name;

    @SerializedName("user_name")
    private String  user_name;

    @SerializedName("country_name")
    private String  country_name;

    @SerializedName("state_name")
    private String  state_name;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getTo_user_id() {
        return to_user_id;
    }

    public void setTo_user_id(String to_user_id) {
        this.to_user_id = to_user_id;
    }

    public String getFrom_user_id() {
        return from_user_id;
    }

    public void setFrom_user_id(String from_user_id) {
        this.from_user_id = from_user_id;
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

    public String getCountry_id() {
        return country_id;
    }

    public void setCountry_id(String country_id) {
        this.country_id = country_id;
    }

    public String getState_id() {
        return state_id;
    }

    public void setState_id(String state_id) {
        this.state_id = state_id;
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

    public String getMobile() {
        return mobile;
    }

    public void setMobile(String mobile) {
        this.mobile = mobile;
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

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public ArrayList<FileModel> getFiles() {
        return files;
    }

    public void setFiles(ArrayList<FileModel> files) {
        this.files = files;
    }

    public String getReferral_status() {
        return referral_status;
    }

    public void setReferral_status(String referral_status) {
        this.referral_status = referral_status;
    }

    public String getMonetary_value() {
        return monetary_value;
    }

    public void setMonetary_value(String monetary_value) {
        this.monetary_value = monetary_value;
    }

    public Boolean getIs_read() {
        return is_read;
    }

    public void setIs_read(Boolean is_read) {
        this.is_read = is_read;
    }

    public Boolean getIs_archive() {
        return is_archive;
    }

    public void setIs_archive(Boolean is_archive) {
        this.is_archive = is_archive;
    }

    public String getCreated() {
        return created;
    }

    public void setCreated(String created) {
        this.created = created;
    }

    public List<String> getFile_name() {
        return file_name;
    }

    public void setFile_name(List<String> file_name) {
        this.file_name = file_name;
    }

    public String getModified() {
        return modified;
    }

    public void setModified(String modified) {
        this.modified = modified;
    }

    public String getUser_name() {
        return user_name;
    }

    public void setUser_name(String user_name) {
        this.user_name = user_name;
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
