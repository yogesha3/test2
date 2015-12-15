package com.app.foxhopr.webservice.models;

import java.io.Serializable;

/**
 * Created by Chobey R. on 23/10/15.
 */
public class MeetingDayModel implements Serializable {

    private  String MeetingDay;
    private  String MeetingValue;
    private  String MeetingTimeExactValue;
    private boolean selected;
    private boolean isChecked;

    public String getMeetingValue() {
        return MeetingValue;
    }

    public void setMeetingValue(String meetingValue) {
        MeetingValue = meetingValue;
    }

    public String getMeetingDay() {
        return MeetingDay;
    }

    public void setMeetingDay(String meetingDay) {
        MeetingDay = meetingDay;
    }

    public boolean isSelected() {
        return selected;
    }

    public void setSelected(boolean selected) {
        this.selected = selected;
    }

    public boolean isChecked() {
        return isChecked;
    }

    public void setIsChecked(boolean isChecked) {
        this.isChecked = isChecked;
    }

    public String getMeetingTimeExactValue() {
        return MeetingTimeExactValue;
    }

    public void setMeetingTimeExactValue(String meetingTimeExactValue) {
        MeetingTimeExactValue = meetingTimeExactValue;
    }
}
