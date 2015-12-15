package com.app.foxhopr.request.models;

/**
 * Created by Chobey R. on 13/8/15.
 */
public class ReceivedReferralDeleteRequestModel {
    private String listPage;

    public String getDeleteId() {
        return deleteId;
    }

    public void setDeleteId(String deleteId) {
        this.deleteId = deleteId;
    }

    public String getListPage() {
        return listPage;
    }

    public void setListPage(String listPage) {
        this.listPage = listPage;
    }

    private String deleteId;
}
