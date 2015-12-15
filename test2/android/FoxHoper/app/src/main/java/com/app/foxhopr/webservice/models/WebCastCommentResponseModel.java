package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 20/8/15.
 */
public class WebCastCommentResponseModel {

    @SerializedName("code")
    private String  code;

    @SerializedName("result")
    private ArrayList<WebCastCommentModel> result;

    @SerializedName("message")
    private String  message;

    public ArrayList<WebCastCommentModel> getResult() {
        return result;
    }

    public void setResult(ArrayList<WebCastCommentModel> result) {
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
