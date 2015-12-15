package com.app.foxhopr.utils;

import com.app.foxhopr.webservice.models.InboxListInnerModel;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 28/9/15.
 */
public interface MessageListClickCallBack {
    void itemClickAction(ArrayList<InboxListInnerModel> mList);
}
