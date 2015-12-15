package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 20/8/15.
 */
public class CommentModel {

    @SerializedName("id")
    private String  id;

    @SerializedName("type")
    private String  type;

    @SerializedName("referral_id")
    private String  referral_id;

    @SerializedName("commented_by_id")
    private String  commented_by_id;

    @SerializedName("comment")
    private String  comment;

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

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getReferral_id() {
        return referral_id;
    }

    public void setReferral_id(String referral_id) {
        this.referral_id = referral_id;
    }

    public String getCommented_by_id() {
        return commented_by_id;
    }

    public void setCommented_by_id(String commented_by_id) {
        this.commented_by_id = commented_by_id;
    }

    public String getCreated() {
        return created;
    }

    public void setCreated(String created) {
        this.created = created;
    }

    public String getComment() {
        return comment;
    }

    public void setComment(String comment) {
        this.comment = comment;
    }

    public String getModified() {
        return modified;
    }

    public void setModified(String modified) {
        this.modified = modified;
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
