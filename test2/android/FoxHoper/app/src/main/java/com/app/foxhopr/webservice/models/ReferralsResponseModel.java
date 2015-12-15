package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

/**
 * Created by a3logics on 28/07/15.
 */
public class ReferralsResponseModel {

    @SerializedName("code")
    public String responseCode;

    @SerializedName("message")
    public String response;

    @SerializedName("page_no")
    public String page_no;

    @SerializedName("totalReferrals")
    public String totalReferrals;

    @SerializedName("result")
    public ArrayList<ReferralsListInnerModel> getListData;

    public String getReponseCode() {
        return responseCode;
    }

    public String getMessage() {
        return response;
    }

    public ArrayList<ReferralsListInnerModel> getTimeList() {
        return getListData;
    }

    public String getPage_no() {
        return page_no;
    }

    public String getTotalReferrals() {
        return totalReferrals;
    }
}
