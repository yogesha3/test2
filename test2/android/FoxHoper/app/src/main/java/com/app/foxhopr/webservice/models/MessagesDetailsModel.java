package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 23/9/15.
 */
public class MessagesDetailsModel {

    @SerializedName("ReceiversName")
    private String  ReceiversName;

    @SerializedName("sendMailTo")
    private String  sendMailTo;

    @SerializedName("subject")
    private String  subject;

    @SerializedName("content")
    private String  content;

    @SerializedName("files")
    private ArrayList<FileModel> files;

    @SerializedName("created")
    private String  created;

    @SerializedName("user_name")
    private String  user_name;

    @SerializedName("message_type")
    private String  message_type;

    public String getMessage_type() {
        return message_type;
    }

    public void setMessage_type(String message_type) {
        this.message_type = message_type;
    }

    public String getReceiversName() {
        return ReceiversName;
    }

    public void setReceiversName(String receiversName) {
        ReceiversName = receiversName;
    }

    public String getSendMailTo() {
        return sendMailTo;
    }

    public void setSendMailTo(String sendMailTo) {
        this.sendMailTo = sendMailTo;
    }

    public String getSubject() {
        return subject;
    }

    public void setSubject(String subject) {
        this.subject = subject;
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }

    public String getUser_name() {
        return user_name;
    }

    public void setUser_name(String user_name) {
        this.user_name = user_name;
    }




    public ArrayList<FileModel> getFiles() {
        return files;
    }

    public void setFiles(ArrayList<FileModel> files) {
        this.files = files;
    }

    public String getCreated() {
        return created;
    }

    public void setCreated(String created) {
        this.created = created;
    }




}
