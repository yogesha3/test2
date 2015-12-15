package com.app.foxhopr.request.models;

/**
 * Created by Chobey R. on 4/12/15.
 */
public class SocialListRequestModel {

    private String mode;
    private String list;
    private String updateSocial;
    private String [] updateFields;


    public String getList() {
        return list;
    }

    public void setList(String list) {
        this.list = list;
    }

    public String getMode() {
        return mode;
    }

    public void setMode(String mode) {
        this.mode = mode;
    }

    public String getUpdateSocial() {
        return updateSocial;
    }

    public void setUpdateSocial(String updateSocial) {
        this.updateSocial = updateSocial;
    }

    public String[] getUpdateFields() {
        return updateFields;
    }

    public void setUpdateFields(String [] updateFields) {
        this.updateFields = updateFields;
    }


}
