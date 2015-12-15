package com.app.foxhopr.utils;

import com.app.foxhopr.webservice.models.CurrentTeamInnerModel;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 12/10/15.
 */
public interface CurrentTeamListClickCallBack {
    void itemClickAction(ArrayList<CurrentTeamInnerModel> mList);
}
