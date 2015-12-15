package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 14/9/15.
 */
public class InboxListInnerModel {

    @SerializedName("id")
    private String  id;

    @SerializedName("subject")
    private String  subject;

    @SerializedName("written_by_user")
    private String  written_by_user;

    @SerializedName("lname")
    private String  lname;

    @SerializedName("created")
    private String  created;

    @SerializedName("fname")
    private String  fname;

    @SerializedName("attachment")
    private boolean  attachment;

    @SerializedName("recipient_users")
    private String  recipient_users;

    @SerializedName("is_read")
    private boolean  is_read;


    private boolean selected;
    private boolean isChecked;

    public String getRecipient_users() {
        return recipient_users;
    }

    public void setRecipient_users(String recipient_users) {
        this.recipient_users = recipient_users;
    }

    public String getCreated() {
        return created;
    }

    public void setCreated(String created) {
        this.created = created;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getSubject() {
        return subject;
    }

    public void setSubject(String subject) {
        this.subject = subject;
    }

    public String getWritten_by_user() {
        return written_by_user;
    }

    public void setWritten_by_user(String written_by_user) {
        this.written_by_user = written_by_user;
    }

    public String getLname() {
        return lname;
    }

    public void setLname(String lname) {
        this.lname = lname;
    }

    public String getFname() {
        return fname;
    }

    public void setFname(String fname) {
        this.fname = fname;
    }

    public boolean is_read() {
        return is_read;
    }

    public void setIs_read(boolean is_read) {
        this.is_read = is_read;
    }

    public boolean isAttachment() {
        return attachment;
    }

    public void setAttachment(boolean attachment) {
        this.attachment = attachment;
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








}
