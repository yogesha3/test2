package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

/**
 * Created by a3logics on 28/07/15.
 */
public class TeamMembersResponseModel {

    @SerializedName("code")
    public String responseCode;

    @SerializedName("message")
    public String response;

    @SerializedName("result")
    public ArrayList<TeamMebmersListInnerModel> getListData;

    public String getReponseCode() {
        return responseCode;
    }

    public String getMessage() {
        return response;
    }

    public ArrayList<TeamMebmersListInnerModel> getTimeList() {
        return getListData;
    }
}
