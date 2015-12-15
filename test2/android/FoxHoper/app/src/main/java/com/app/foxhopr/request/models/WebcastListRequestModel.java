package com.app.foxhopr.request.models;

/**
 * Created by Chobey R. on 7/12/15.
 */
public class WebcastListRequestModel {

   private String page_no;

    public String getRecord_per_page() {
        return record_per_page;
    }
    public void setRecord_per_page(String record_per_page) {
        this.record_per_page = record_per_page;
    }
    public String getPage_no() {
        return page_no;
    }
    public void setPage_no(String page_no) {
        this.page_no = page_no;
    }
    private String record_per_page;

}
