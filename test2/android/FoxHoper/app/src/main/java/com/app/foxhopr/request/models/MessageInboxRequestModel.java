package com.app.foxhopr.request.models;

/**
 * Created by Chobey R. on 14/9/15.
 */
public class MessageInboxRequestModel {

    private String page_no;
    private String record_per_page;
    private String list_page;
    private String search_filter;
    private String sort_data;
    private String sort_direction;

    public String getPage_no() {
        return page_no;
    }

    public void setPage_no(String page_no) {
        this.page_no = page_no;
    }

    public String getRecord_per_page() {
        return record_per_page;
    }

    public void setRecord_per_page(String record_per_page) {
        this.record_per_page = record_per_page;
    }

    public String getSearch_filter() {
        return search_filter;
    }

    public void setSearch_filter(String search_filter) {
        this.search_filter = search_filter;
    }

    public String getList_page() {
        return list_page;
    }

    public void setList_page(String list_page) {
        this.list_page = list_page;
    }

    public String getSort_direction() {
        return sort_direction;
    }

    public void setSort_direction(String sort_direction) {
        this.sort_direction = sort_direction;
    }

    public String getSort_data() {
        return sort_data;
    }

    public void setSort_data(String sort_data) {
        this.sort_data = sort_data;
    }



}


