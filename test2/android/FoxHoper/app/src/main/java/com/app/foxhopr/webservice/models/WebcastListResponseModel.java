package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 7/12/15.
 */
public class WebcastListResponseModel {
    @SerializedName("code")
    public String code;

    @SerializedName("message")
    public String message;

    @SerializedName("result")
    public ArrayList<WebCastListInnerModel> result;

    @SerializedName("page_no")
    public String page_no;

    @SerializedName("totalWebcast")
    public String totalWebcast;


    public String getTotalWebcast() {
        return totalWebcast;
    }

    public void setTotalWebcast(String totalWebcast) {
        this.totalWebcast = totalWebcast;
    }

    public String getPage_no() {
        return page_no;
    }

    public void setPage_no(String page_no) {
        this.page_no = page_no;
    }


    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public ArrayList<WebCastListInnerModel> getResult() {
        return result;
    }

    public void setResult(ArrayList<WebCastListInnerModel> result) {
        this.result = result;
    }

    public String getCode() {
        return code;
    }

    public void setCode(String code) {
        this.code = code;
    }

}
