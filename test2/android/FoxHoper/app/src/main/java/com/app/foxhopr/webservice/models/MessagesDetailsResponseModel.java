package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 23/9/15.
 */
public class MessagesDetailsResponseModel {

    @SerializedName("code")
    private String  code;

    @SerializedName("result")
    private MessagesDetailsModel  result;

    @SerializedName("message")
    private String  message;

    public MessagesDetailsModel getResult() {
        return result;
    }

    public void setResult(MessagesDetailsModel result) {
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
