package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 12/10/15.
 */
public class BillingDetailsResponseModel {

    @SerializedName("code")
    private String  code;

    @SerializedName("result")
    private BillingDetailModel  result;

    @SerializedName("message")
    private String  message;

    public BillingDetailModel getResult() {
        return result;
    }

    public void setResult(BillingDetailModel result) {
        this.result = result;
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


}
