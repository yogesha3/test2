package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 20/8/15.
 */
public class UserProfileResponseModel {

    @SerializedName("code")
    private String  code;

    @SerializedName("result")
    private ProfileModel  result;

    @SerializedName("message")
    private String  message;

    public ProfileModel getResult() {
        return result;
    }

    public void setResult(ProfileModel result) {
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
