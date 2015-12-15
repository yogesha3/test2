package com.app.foxhopr.request.models;

/**
 * Created by Chobey R. on 21/10/15.
 */
public class GroupSelectionRequestModel {
    private String group;
    private String milesfilter;
    private String searchbylocation;
    private String record_per_page;
    private String page_no;
    private String sorting;
    private String[] time;
    private String[] day;

    public String getGroup() {
        return group;
    }

    public void setGroup(String group) {
        this.group = group;
    }

    public String getMilesfilter() {
        return milesfilter;
    }

    public void setMilesfilter(String milesfilter) {
        this.milesfilter = milesfilter;
    }

    public String getSearchbylocation() {
        return searchbylocation;
    }

    public void setSearchbylocation(String searchbylocation) {
        this.searchbylocation = searchbylocation;
    }

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

    public String getSorting() {
        return sorting;
    }

    public void setSorting(String sorting) {
        this.sorting = sorting;
    }

    public String[] getTime() {
        return time;
    }

    public void setTime(String[] time) {
        this.time = time;
    }

    public String[] getDay() {
        return day;
    }

    public void setDay(String[] day) {
        this.day = day;
    }



}
