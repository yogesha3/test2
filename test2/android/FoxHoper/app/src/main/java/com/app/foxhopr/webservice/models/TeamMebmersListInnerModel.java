package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.io.Serializable;

/**
 * Created by a3logics on 28/07/15.
 */
public class TeamMebmersListInnerModel  implements Serializable{

    @SerializedName("id")
    private String  id;

    @SerializedName("user_id")
    private String  user_id;

    @SerializedName("fname")
    private String  fname;

    @SerializedName("lname")
    private String  lname;

    @SerializedName("member_type")
    private String  member_type;

    public String getMember_type() {
        return member_type;
    }

    public void setMember_type(String member_type) {
        this.member_type = member_type;
    }

    public String getList_user_id() {
        return list_user_id;
    }

    public void setList_user_id(String list_user_id) {
        this.list_user_id = list_user_id;
    }

    @SerializedName("list_user_id")
    private String  list_user_id;

    private boolean selected;
    private boolean isChecked;

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

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getUser_id() {
        return user_id;
    }

    public void setUser_id(String user_id) {
        this.user_id = user_id;
    }

    public String getFname() {
        return fname;
    }

    public void setFname(String fname) {
        this.fname = fname;
    }

    public String getLname() {
        return lname;
    }

    public void setLname(String lname) {
        this.lname = lname;
    }
}
