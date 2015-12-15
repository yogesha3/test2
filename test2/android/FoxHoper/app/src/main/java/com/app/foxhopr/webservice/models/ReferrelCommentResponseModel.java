package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 20/8/15.
 */
public class ReferrelCommentResponseModel {

    @SerializedName("code")
    private String  code;

    @SerializedName("result")
    private ArrayList<CommentModel> result;

    @SerializedName("message")
    private String  message;

    public boolean getWebcastExist() {
        return webcastExist;
    }

    public void setWebcastExist(boolean webcastExist) {
        this.webcastExist = webcastExist;
    }

    @SerializedName("webcastExist")
    private boolean  webcastExist;

    public ArrayList<CommentModel> getResult() {
        return result;
    }

    public void setResult(ArrayList<CommentModel> result) {
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
