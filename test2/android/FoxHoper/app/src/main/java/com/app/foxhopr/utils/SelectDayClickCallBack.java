package com.app.foxhopr.utils;

import android.widget.CheckBox;

import com.app.foxhopr.webservice.models.MeetingDayModel;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 05/10/15.
 */
public interface SelectDayClickCallBack {
    void itemClickAction(ArrayList<MeetingDayModel> mList, CheckBox chckBoxMember, int position);
}
