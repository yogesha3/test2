package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

import java.io.Serializable;

/**
 * Created by Chobey R. on 7/12/15.
 */
public class WebCastListInnerModel implements Serializable {


    @SerializedName("id")
    private  String id;

    @SerializedName("title")
    private  String title;

    @SerializedName("slug")
    private  String slug;

    @SerializedName("description")
    private  String description;

    @SerializedName("link")
    private  String link;

    @SerializedName("created")
    private  String created;

    @SerializedName("modified")
    private  String modified;

    @SerializedName("thumbnail")
    private  String thumbnail;

    public String getLink() {
        return link;
    }

    public void setLink(String link) {
        this.link = link;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getSlug() {
        return slug;
    }

    public void setSlug(String slug) {
        this.slug = slug;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getCreated() {
        return created;
    }

    public void setCreated(String created) {
        this.created = created;
    }

    public String getModified() {
        return modified;
    }

    public void setModified(String modified) {
        this.modified = modified;
    }

    public String getThumbnail() {
        return thumbnail;
    }

    public void setThumbnail(String thumbnail) {
        this.thumbnail = thumbnail;
    }


}
