package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 7/10/15.
 */
public class PartnerResponseModel {
    @SerializedName("code")
    public String code;

    @SerializedName("message")
    public String message;

    @SerializedName("page_no")
    public String page_no;

    @SerializedName("totalPartners")
    public String totalPartners;

    @SerializedName("result")
    public ArrayList<PartnerListInnerModel> getListData;

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

    public String getTotalPartners() {
        return totalPartners;
    }

    public void setTotalPartners(String totalPartners) {
        this.totalPartners = totalPartners;
    }

    public ArrayList<PartnerListInnerModel> getGetListData() {
        return getListData;
    }

    public void setGetListData(ArrayList<PartnerListInnerModel> getListData) {
        this.getListData = getListData;
    }


}
