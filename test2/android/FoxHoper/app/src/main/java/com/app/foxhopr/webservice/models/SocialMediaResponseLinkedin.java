package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 3/12/15.
 */
public class SocialMediaResponseLinkedin {

    @SerializedName("code")
    private String  code;

    @SerializedName("result")
    private SocialMediaDetailsLinkedin result;

    @SerializedName("message")
    private String  message;

    public SocialMediaDetailsLinkedin getResult() {
        return result;
    }

    public void setResult(SocialMediaDetailsLinkedin result) {
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
