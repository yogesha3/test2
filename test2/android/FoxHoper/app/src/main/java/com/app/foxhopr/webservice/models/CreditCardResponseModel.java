package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 25/11/15.
 */
public class CreditCardResponseModel {
    @SerializedName("code")
    private String  code;

    @SerializedName("result")
    private CreditDetailModel  result;

    @SerializedName("message")
    private String  message;

    public CreditDetailModel getResult() {
        return result;
    }

    public void setResult(CreditDetailModel result) {
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
