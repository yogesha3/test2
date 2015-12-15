package com.app.foxhopr.utils;

import android.widget.CheckBox;

import com.app.foxhopr.webservice.models.ExistTeamMebmersListInnerModel;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 05/10/15.
 */
public interface ExistContactClickCallBack {
    void itemClickAction(ArrayList<ExistTeamMebmersListInnerModel> mList, CheckBox chckBoxMember,int position);
}
