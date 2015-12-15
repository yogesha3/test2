package com.app.foxhopr.utils;

import com.app.foxhopr.webservice.models.WebCastListInnerModel;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 12/10/15.
 */
public interface WebCastListClickCallBack {
    void itemClickAction(ArrayList<WebCastListInnerModel> mList);
}
