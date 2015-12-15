package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by a3logics on 28/07/15.
 */
public class CountryListInnerModel {

    @SerializedName("country_name")
    private String  country_name;

    @SerializedName("country_iso_code_2")
    private String  country_iso_code_2;

    public String getCountry_name() {
        return country_name;
    }

    public void setCountry_name(String country_name) {
        this.country_name = country_name;
    }

    public String getCountry_iso_code_2() {
        return country_iso_code_2;
    }

    public void setCountry_iso_code_2(String country_iso_code_2) {
        this.country_iso_code_2 = country_iso_code_2;
    }
}
