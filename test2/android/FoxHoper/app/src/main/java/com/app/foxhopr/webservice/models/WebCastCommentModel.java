package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 20/8/15.
 */
public class WebCastCommentModel {

    @SerializedName("id")
    private String  id;

    @SerializedName("webcast_id")
    private String webcast_id;

    @SerializedName("user_id")
    private String user_id;

    @SerializedName("comments")
    private String  comment;

    @SerializedName("is_checked")
    private boolean  is_checked;

    @SerializedName("created")
    private String  created;

    @SerializedName("modified")
    private String  modified;

    @SerializedName("commented_by")
    private String  commented_by;

    @SerializedName("commented_by_profile_image")
    private String  commented_by_profile_image;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getWebcast_id() {
        return webcast_id;
    }

    public void setWebcast_id(String webcast_id) {
        this.webcast_id = webcast_id;
    }

    public String getUser_id() {
        return user_id;
    }

    public void setUser_id(String user_id) {
        this.user_id = user_id;
    }

    public String getComment() {
        return comment;
    }

    public void setComment(String comment) {
        this.comment = comment;
    }

    public boolean is_checked() {
        return is_checked;
    }

    public void setIs_checked(boolean is_checked) {
        this.is_checked = is_checked;
    }

    public String getModified() {
        return modified;
    }

    public void setModified(String modified) {
        this.modified = modified;
    }

    public String getCreated() {
        return created;
    }

    public void setCreated(String created) {
        this.created = created;
    }

    public String getCommented_by() {
        return commented_by;
    }

    public void setCommented_by(String commented_by) {
        this.commented_by = commented_by;
    }

    public String getCommented_by_profile_image() {
        return commented_by_profile_image;
    }

    public void setCommented_by_profile_image(String commented_by_profile_image) {
        this.commented_by_profile_image = commented_by_profile_image;
    }

}
