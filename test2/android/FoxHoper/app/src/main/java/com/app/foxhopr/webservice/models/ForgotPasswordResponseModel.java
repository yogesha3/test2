package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by a3logics on 28/07/15.
 */
public class ForgotPasswordResponseModel {

    @SerializedName("code")
    public String responseCode;

    @SerializedName("message")
    public String response;

    public String getReponseCode() {
        return responseCode;
    }

    public String getMessage() {
        return response;
    }
}
