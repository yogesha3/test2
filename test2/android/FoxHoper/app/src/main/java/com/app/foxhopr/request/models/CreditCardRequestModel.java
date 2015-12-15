package com.app.foxhopr.request.models;

/**
 * Created by Chobey R. on 25/11/15.
 */
public class CreditCardRequestModel {

    private String CC_Number;
    private String CC_year;
    private String CC_month;
    private String CC_cvv;
    private String CC_Name;

    public String getCC_year() {
        return CC_year;
    }

    public void setCC_year(String CC_year) {
        this.CC_year = CC_year;
    }

    public String getCC_Number() {
        return CC_Number;
    }

    public void setCC_Number(String CC_Number) {
        this.CC_Number = CC_Number;
    }

    public String getCC_month() {
        return CC_month;
    }

    public void setCC_month(String CC_month) {
        this.CC_month = CC_month;
    }

    public String getCC_cvv() {
        return CC_cvv;
    }

    public void setCC_cvv(String CC_cvv) {
        this.CC_cvv = CC_cvv;
    }

    public String getCC_Name() {
        return CC_Name;
    }

    public void setCC_Name(String CC_Name) {
        this.CC_Name = CC_Name;
    }


}
