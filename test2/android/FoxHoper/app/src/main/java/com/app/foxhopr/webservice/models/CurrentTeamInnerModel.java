package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.io.Serializable;

/**
 * Created by Chobey R. on 12/10/15.
 */
public class CurrentTeamInnerModel implements Serializable {

    @SerializedName("id")
    private String  id;

    @SerializedName("user_id")
    private String  user_id;

    @SerializedName("email")
    private String  email;

    @SerializedName("lname")
    private String  lname;

    @SerializedName("company")
    private String  company;


    @SerializedName("created")
    private String  created;

    @SerializedName("member_id")
    private String  member_id;

    @SerializedName("fname")
    private String  fname;

    @SerializedName("country_name")
    private String  country_name;

    @SerializedName("state_name")
    private String  state_name;

    @SerializedName("profession_name")
    private String  profession_name;

    private boolean selected;
    private boolean isChecked;


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

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getLname() {
        return lname;
    }

    public void setLname(String lname) {
        this.lname = lname;
    }

    public String getCreated() {
        return created;
    }

    public void setCreated(String created) {
        this.created = created;
    }

    public String getCompany() {
        return company;
    }

    public void setCompany(String company) {
        this.company = company;
    }

    public String getMember_id() {
        return member_id;
    }

    public void setMember_id(String member_id) {
        this.member_id = member_id;
    }

    public String getFname() {
        return fname;
    }

    public void setFname(String fname) {
        this.fname = fname;
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

    public String getProfession_name() {
        return profession_name;
    }

    public void setProfession_name(String profession_name) {
        this.profession_name = profession_name;
    }

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












}
