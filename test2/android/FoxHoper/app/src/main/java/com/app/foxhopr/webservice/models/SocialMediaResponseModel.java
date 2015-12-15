package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 3/12/15.
 */
public class SocialMediaResponseModel {

    @SerializedName("code")
    private String  code;

    @SerializedName("result")
    private SocialMediaDetailsModel result;

    @SerializedName("message")
    private String  message;

    public SocialMediaDetailsModel getResult() {
        return result;
    }

    public void setResult(SocialMediaDetailsModel result) {
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
