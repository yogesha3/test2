package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

/**
 * Created by a3logics on 28/07/15.
 */
public class CountryResponseModel {

    @SerializedName("code")
    public String responseCode;

    @SerializedName("message")
    public String response;


    @SerializedName("result")
    public ArrayList<CountryListInnerModel> getCountryListData;

    public String getReponseCode() {
        return responseCode;
    }

    public String getMessage() {
        return response;
    }

    public ArrayList<CountryListInnerModel> getCountryList() {
        return getCountryListData;
    }
}
