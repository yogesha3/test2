package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 21/10/15.
 */
public class GroupResponseModel {

    @SerializedName("code")
    public String code;

    @SerializedName("message")
    public String message;

    @SerializedName("page_no")
    public String page_no;

    @SerializedName("totalGroups")
    public String totalGroups;

    @SerializedName("result")
    public ArrayList<GroupListInnerModel> groupList;

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

    public String getTotalGroups() {
        return totalGroups;
    }

    public void setTotalGroups(String totalGroups) {
        this.totalGroups = totalGroups;
    }

    public ArrayList<GroupListInnerModel> getGroupList() {
        return groupList;
    }

    public void setGroupList(ArrayList<GroupListInnerModel> groupList) {
        this.groupList = groupList;
    }





}
