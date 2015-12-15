package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by a3logics on 28/07/15.
 */
public class StateListInnerModel {

    @SerializedName("state_subdivision_id")
    private String  state_subdivision_id;

    @SerializedName("state_subdivision_name")
    private String  state_subdivision_name;

    public String getState_subdivision_id() {
        return state_subdivision_id;
    }

    public void setState_subdivision_id(String state_subdivision_id) {
        this.state_subdivision_id = state_subdivision_id;
    }

    public String getState_subdivision_name() {
        return state_subdivision_name;
    }

    public void setState_subdivision_name(String state_subdivision_name) {
        this.state_subdivision_name = state_subdivision_name;
    }
}
