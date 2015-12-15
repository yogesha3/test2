package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

/**
 * Created by a3logics on 28/07/15.
 */
public class ExistTeamMembersResponseModel {

    @SerializedName("code")
    public String code;


    @SerializedName("message")
    public String message;

    @SerializedName("result")
    public ArrayList<ExistTeamMebmersListInnerModel> getResult;

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public ArrayList<ExistTeamMebmersListInnerModel> getGetResult() {
        return getResult;
    }

    public void setGetResult(ArrayList<ExistTeamMebmersListInnerModel> getResult) {
        this.getResult = getResult;
    }

    public String getCode() {
        return code;
    }

    public void setCode(String code) {
        this.code = code;
    }







}
