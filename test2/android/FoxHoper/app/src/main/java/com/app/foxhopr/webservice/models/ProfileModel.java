package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 22/8/15.
 */
public class ProfileModel {
    @SerializedName("fname")
    private String  fname;

    @SerializedName("lname")
    private String  lname;

    @SerializedName("profile_image")
    private String  profile_image;


    @SerializedName("group_id")
    private String  group_id;

    @SerializedName("group_role")
    private String  group_role;

    @SerializedName("is_level_message_viewed")
    private String  is_level_message_viewed;

    @SerializedName("profile_completion_status")
    private String  profile_completion_status;

    @SerializedName("shuffling_date")
    private String  shuffling_date;

    @SerializedName("rating")
    private String  rating;

    @SerializedName("membership_type")
    private String  membership_type;

    @SerializedName("totalReview")
    private String  totalReview;

    public String getIs_level_message_viewed() {
        return is_level_message_viewed;
    }

    public void setIs_level_message_viewed(String is_level_message_viewed) {
        this.is_level_message_viewed = is_level_message_viewed;
    }

    public String getGroup_id() {
        return group_id;
    }

    public void setGroup_id(String group_id) {
        this.group_id = group_id;
    }

    public String getGroup_role() {
        return group_role;
    }

    public void setGroup_role(String group_role) {
        this.group_role = group_role;
    }

    public String getProfile_completion_status() {
        return profile_completion_status;
    }

    public void setProfile_completion_status(String profile_completion_status) {
        this.profile_completion_status = profile_completion_status;
    }

    public String getShuffling_date() {
        return shuffling_date;
    }

    public void setShuffling_date(String shuffling_date) {
        this.shuffling_date = shuffling_date;
    }

    public String getRating() {
        return rating;
    }

    public void setRating(String rating) {
        this.rating = rating;
    }

    public String getMembership_type() {
        return membership_type;
    }

    public void setMembership_type(String membership_type) {
        this.membership_type = membership_type;
    }

    public String getTotalReview() {
        return totalReview;
    }

    public void setTotalReview(String totalReview) {
        this.totalReview = totalReview;
    }




    public String getProfile_image() {
        return profile_image;
    }

    public void setProfile_image(String profile_image) {
        this.profile_image = profile_image;
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
