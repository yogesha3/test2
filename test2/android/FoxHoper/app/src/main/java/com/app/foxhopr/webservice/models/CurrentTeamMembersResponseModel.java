package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

/**
 * Created by a3logics on 12/10/15.
 */
public class CurrentTeamMembersResponseModel {
    @SerializedName("code")
    public String code;

    @SerializedName("message")
    public String message;

    @SerializedName("page_no")
    public String page_no;

    @SerializedName("totalMembers")
    public String totalContacts;

    @SerializedName("result")
    public ArrayList<CurrentTeamInnerModel> getListData;

    public String getTotalContacts() {
        return totalContacts;
    }

    public void setTotalContacts(String totalContacts) {
        this.totalContacts = totalContacts;
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

    public String getPage_no() {
        return page_no;
    }

    public void setPage_no(String page_no) {
        this.page_no = page_no;
    }

    public ArrayList<CurrentTeamInnerModel> getGetListData() {
        return getListData;
    }

    public void setGetListData(ArrayList<CurrentTeamInnerModel> getListData) {
        this.getListData = getListData;
    }


}
