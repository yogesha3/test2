package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 5/10/15.
 */
public class ContactResponseModel {
    @SerializedName("code")
    public String code;

    @SerializedName("message")
    public String message;

    @SerializedName("page_no")
    public String page_no;

    @SerializedName("totalContacts")
    public String totalContacts;

    @SerializedName("result")
    public ArrayList<ContactListInnerModel> getListData;


    public String getPage_no() {
        return page_no;
    }

    public void setPage_no(String page_no) {
        this.page_no = page_no;
    }

    public String getCode() {
        return code;
    }

    public void setCode(String code) {
        this.code = code;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public String getTotalContacts() {
        return totalContacts;
    }

    public void setTotalContacts(String totalContacts) {
        this.totalContacts = totalContacts;
    }

    public ArrayList<ContactListInnerModel> getGetListData() {
        return getListData;
    }

    public void setGetListData(ArrayList<ContactListInnerModel> getListData) {
        this.getListData = getListData;
    }





}
