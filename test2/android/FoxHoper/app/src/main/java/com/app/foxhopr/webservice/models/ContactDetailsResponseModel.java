package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 20/8/15.
 */
public class ContactDetailsResponseModel {

    @SerializedName("code")
    private String  code;

    @SerializedName("result")
    private ContactDetailModel  result;

    @SerializedName("message")
    private String  message;

    public ContactDetailModel getResult() {
        return result;
    }

    public void setResult(ContactDetailModel result) {
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
