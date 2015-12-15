package com.app.foxhopr.request.models;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 7/10/15.
 */
public class InvitePartnerRequestModel {
    private String message;
    private ArrayList<PartnerBean> data;

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public ArrayList<PartnerBean> getData() {
        return data;
    }

    public void setData(ArrayList<PartnerBean> data) {
        this.data = data;
    }

    /*{"data" : [{"name":"kamal","email":"test3@mailimate.com"},{"name":"test","email":"test2@mailimate.com"}],
         "message" : "You've been invited by Fox- Three to be a part for FoxHopr network. Sign up today and get FoxHopping."}*/

}
