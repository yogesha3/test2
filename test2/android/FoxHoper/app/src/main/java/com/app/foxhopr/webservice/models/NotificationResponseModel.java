package com.app.foxhopr.webservice.models;

import com.google.gson.JsonObject;
import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 17/8/15.
 */
public class NotificationResponseModel {

    @SerializedName("code")
    private String  code;

    @SerializedName("result")
    private JsonObject result;

    @SerializedName("message")
    private String  message;

    public String getCode() {
        return code;
    }

    public void setCode(String code) {
        this.code = code;
    }

    public JsonObject getResult() {
        return result;
    }

    public void setResult(JsonObject result) {
        this.result = result;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }


}
