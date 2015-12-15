package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 14/9/15.
 */
public class MessagesResponseModel {

    @SerializedName("code")
    public String code;

    @SerializedName("message")
    public String message;

    @SerializedName("page_no")
    public String page_no;

    @SerializedName("totalMessages")
    public String totalMessages;

    @SerializedName("result")
    public ArrayList<InboxListInnerModel> getListData;

    public String getReponseCode() {
        return code;
    }

    public String getMessage() {
        return message;
    }

    public ArrayList<InboxListInnerModel> getTimeList() {
        return getListData;
    }

    public String getPage_no() {
        return page_no;
    }

    public String getTotalReferrals() {
        return totalMessages;
    }
}
