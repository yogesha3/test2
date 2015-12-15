package com.app.foxhopr.utils;

import com.app.foxhopr.webservice.models.ContactListInnerModel;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 05/10/15.
 */
public interface ContactListClickCallBack {
    void itemClickAction(ArrayList<ContactListInnerModel> mList);
}
