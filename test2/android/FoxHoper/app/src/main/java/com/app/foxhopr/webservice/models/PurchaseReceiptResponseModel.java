package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 12/10/15.
 */
public class PurchaseReceiptResponseModel {

    @SerializedName("code")
    private String  code;

    @SerializedName("result")
    private ArrayList<ReceiptDetailModel>  result;

    @SerializedName("message")
    private String  message;

    public ArrayList<ReceiptDetailModel> getResult() {
        return result;
    }

    public void setResult(ArrayList<ReceiptDetailModel> result) {
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
