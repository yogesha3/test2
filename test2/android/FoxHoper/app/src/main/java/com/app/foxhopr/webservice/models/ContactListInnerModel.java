package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 14/9/15.
 */
public class ContactListInnerModel {

    @SerializedName("id")
    private String  id;

    @SerializedName("email")
    private String  email;

    @SerializedName("job_title")
    private String  job_title;

    @SerializedName("last_name")
    private String  last_name;

    @SerializedName("created")
    private String  created;

    @SerializedName("first_name")
    private String  first_name;

    private boolean selected;
    private boolean isChecked;


    public String getJob_title() {
        return job_title;
    }

    public void setJob_title(String job_title) {
        this.job_title = job_title;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getLast_name() {
        return last_name;
    }

    public void setLast_name(String last_name) {
        this.last_name = last_name;
    }

    public String getFirst_name() {
        return first_name;
    }

    public void setFirst_name(String first_name) {
        this.first_name = first_name;
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
