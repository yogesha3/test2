package com.app.foxhopr.request.models;

/**
 * Created by Chobey R. on 27/10/15.
 */
public class NotificationRequestModel {

    private String notifPage;

    public String[] getNotifArr() {
        return notifArr;
    }

    public void setNotifArr(String[] notifArr) {
        this.notifArr = notifArr;
    }

    private String [] notifArr;
    public String getNotifPage() {
        return notifPage;
    }

    public void setNotifPage(String notifPage) {
        this.notifPage = notifPage;
    }


}
