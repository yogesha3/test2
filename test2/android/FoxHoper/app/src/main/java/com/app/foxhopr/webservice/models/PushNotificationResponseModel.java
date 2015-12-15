package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by a3logics on 27/10/15.
 */
public class PushNotificationResponseModel {

    @SerializedName("code")
    public String code;

    @SerializedName("message")
    public String message;

    @SerializedName("result")
    public PushNotificationListInnerModel getResult;



    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public String getCode() {
        return code;
    }

    public void setCode(String code) {
        this.code = code;
    }

    public PushNotificationListInnerModel getGetResult() {
        return getResult;
    }

    public void setGetResult(PushNotificationListInnerModel getResult) {
        this.getResult = getResult;
    }




}
