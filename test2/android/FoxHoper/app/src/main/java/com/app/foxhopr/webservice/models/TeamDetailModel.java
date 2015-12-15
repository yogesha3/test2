package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 12/10/15.
 */
public class TeamDetailModel {
    @SerializedName("id")
    private String  id;

    @SerializedName("user_id")
    private String  user_id;

    @SerializedName("fname")
    private String  fname;

    @SerializedName("lname")
    private String  lname;

    @SerializedName("email")
    private String  email;

    @SerializedName("company")
    private String  company;

    @SerializedName("job_title")
    private String  job_title;

    @SerializedName("profession_id")
    private String  profession_id;

    @SerializedName("group_id")
    private String  group_id;

    @SerializedName("group_role")
    private String  group_role;

    @SerializedName("is_unlocked")
    private Boolean  is_unlocked;

    @SerializedName("user_plan_id")
    private String  user_plan_id;

    @SerializedName("address")
    private String  address;

    @SerializedName("state_id")
    private String  state_id;

    @SerializedName("city")
    private String  city;

    @SerializedName("country_id")
    private String  country_id;

    @SerializedName("timezone_id")
    private String  timezone_id;

    @SerializedName("zipcode")
    private String  zipcode;

    @SerializedName("office_phone")
    private String  office_phone;

    @SerializedName("mobile")
    private String  mobile;

    @SerializedName("profile_image")
    private String  profile_image;

    @SerializedName("business_description")
    private String  business_description;

    @SerializedName("aboutme")
    private String  aboutme;

    @SerializedName("services")
    private String  services;

    @SerializedName("profile_video_link")
    private String  profile_video_link;

    @SerializedName("website")
    private String  website;

    @SerializedName("website1")
    private String  website1;

    @SerializedName("skype_id")
    private String  skype_id;

    @SerializedName("twitter_profile_id")
    private String  twitter_profile_id;

    @SerializedName("linkedin_profile_id")
    private String  linkedin_profile_id;

    @SerializedName("facebook_profile_id")
    private String  facebook_profile_id;

    @SerializedName("is_leader_permission")
    private String  is_leader_permission;

    @SerializedName("is_newsletter_subscriber")
    private Boolean  is_newsletter_subscriber;

    @SerializedName("is_leadership_interest")
    private Boolean  is_leadership_interest;

    @SerializedName("notifications_enabled")
    private String  notifications_enabled;

    @SerializedName("profile_completion_status")
    private String  profile_completion_status;

    @SerializedName("shuffling_percent")
    private String  shuffling_percent;

    @SerializedName("is_deleted")
    private Boolean  is_deleted;

    @SerializedName("is_active")
    private Boolean  is_active;

    @SerializedName("is_kicked")
    private Boolean  is_kicked;

    @SerializedName("group_update")
    private String group_update;

    @SerializedName("created")
    private String  created;

    @SerializedName("modified")
    private String  modified;

    @SerializedName("member_name")
    private String  member_name;

    @SerializedName("profession_name")
    private String  profession_name;

    @SerializedName("country_name")
    private String  country_name;

    @SerializedName("state_name")
    private String  state_name;

    @SerializedName("member_profile_image")
    private String  member_profile_image;

    public String getRating() {
        return rating;
    }

    public void setRating(String rating) {
        this.rating = rating;
    }

    @SerializedName("rating")
    private String  rating;

    @SerializedName("totalReview")
    private String  totalReview;



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

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getCompany() {
        return company;
    }

    public void setCompany(String company) {
        this.company = company;
    }

    public String getJob_title() {
        return job_title;
    }

    public void setJob_title(String job_title) {
        this.job_title = job_title;
    }

    public String getGroup_id() {
        return group_id;
    }

    public void setGroup_id(String group_id) {
        this.group_id = group_id;
    }

    public String getProfession_id() {
        return profession_id;
    }

    public void setProfession_id(String profession_id) {
        this.profession_id = profession_id;
    }

    public String getGroup_role() {
        return group_role;
    }

    public void setGroup_role(String group_role) {
        this.group_role = group_role;
    }

    public Boolean getIs_unlocked() {
        return is_unlocked;
    }

    public void setIs_unlocked(Boolean is_unlocked) {
        this.is_unlocked = is_unlocked;
    }

    public String getUser_plan_id() {
        return user_plan_id;
    }

    public void setUser_plan_id(String user_plan_id) {
        this.user_plan_id = user_plan_id;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public String getState_id() {
        return state_id;
    }

    public void setState_id(String state_id) {
        this.state_id = state_id;
    }

    public String getCity() {
        return city;
    }

    public void setCity(String city) {
        this.city = city;
    }

    public String getCountry_id() {
        return country_id;
    }

    public void setCountry_id(String country_id) {
        this.country_id = country_id;
    }

    public String getTimezone_id() {
        return timezone_id;
    }

    public void setTimezone_id(String timezone_id) {
        this.timezone_id = timezone_id;
    }

    public String getZipcode() {
        return zipcode;
    }

    public void setZipcode(String zipcode) {
        this.zipcode = zipcode;
    }

    public String getOffice_phone() {
        return office_phone;
    }

    public void setOffice_phone(String office_phone) {
        this.office_phone = office_phone;
    }

    public String getMobile() {
        return mobile;
    }

    public void setMobile(String mobile) {
        this.mobile = mobile;
    }

    public String getBusiness_description() {
        return business_description;
    }

    public void setBusiness_description(String business_description) {
        this.business_description = business_description;
    }

    public String getProfile_image() {
        return profile_image;
    }

    public void setProfile_image(String profile_image) {
        this.profile_image = profile_image;
    }

    public String getAboutme() {
        return aboutme;
    }

    public void setAboutme(String aboutme) {
        this.aboutme = aboutme;
    }

    public String getServices() {
        return services;
    }

    public void setServices(String services) {
        this.services = services;
    }

    public String getProfile_video_link() {
        return profile_video_link;
    }

    public void setProfile_video_link(String profile_video_link) {
        this.profile_video_link = profile_video_link;
    }

    public String getWebsite() {
        return website;
    }

    public void setWebsite(String website) {
        this.website = website;
    }

    public String getWebsite1() {
        return website1;
    }

    public void setWebsite1(String website1) {
        this.website1 = website1;
    }

    public String getSkype_id() {
        return skype_id;
    }

    public void setSkype_id(String skype_id) {
        this.skype_id = skype_id;
    }

    public String getTwitter_profile_id() {
        return twitter_profile_id;
    }

    public void setTwitter_profile_id(String twitter_profile_id) {
        this.twitter_profile_id = twitter_profile_id;
    }

    public String getLinkedin_profile_id() {
        return linkedin_profile_id;
    }

    public void setLinkedin_profile_id(String linkedin_profile_id) {
        this.linkedin_profile_id = linkedin_profile_id;
    }

    public String getFacebook_profile_id() {
        return facebook_profile_id;
    }

    public void setFacebook_profile_id(String facebook_profile_id) {
        this.facebook_profile_id = facebook_profile_id;
    }

    public String getIs_leader_permission() {
        return is_leader_permission;
    }

    public void setIs_leader_permission(String is_leader_permission) {
        this.is_leader_permission = is_leader_permission;
    }

    public Boolean getIs_newsletter_subscriber() {
        return is_newsletter_subscriber;
    }

    public void setIs_newsletter_subscriber(Boolean is_newsletter_subscriber) {
        this.is_newsletter_subscriber = is_newsletter_subscriber;
    }

    public Boolean getIs_leadership_interest() {
        return is_leadership_interest;
    }

    public void setIs_leadership_interest(Boolean is_leadership_interest) {
        this.is_leadership_interest = is_leadership_interest;
    }

    public String getNotifications_enabled() {
        return notifications_enabled;
    }

    public void setNotifications_enabled(String notifications_enabled) {
        this.notifications_enabled = notifications_enabled;
    }

    public String getShuffling_percent() {
        return shuffling_percent;
    }

    public void setShuffling_percent(String shuffling_percent) {
        this.shuffling_percent = shuffling_percent;
    }

    public String getProfile_completion_status() {
        return profile_completion_status;
    }

    public void setProfile_completion_status(String profile_completion_status) {
        this.profile_completion_status = profile_completion_status;
    }

    public Boolean getIs_deleted() {
        return is_deleted;
    }

    public void setIs_deleted(Boolean is_deleted) {
        this.is_deleted = is_deleted;
    }

    public Boolean getIs_active() {
        return is_active;
    }

    public void setIs_active(Boolean is_active) {
        this.is_active = is_active;
    }

    public Boolean getIs_kicked() {
        return is_kicked;
    }

    public void setIs_kicked(Boolean is_kicked) {
        this.is_kicked = is_kicked;
    }

    public String getGroup_update() {
        return group_update;
    }

    public void setGroup_update(String group_update) {
        this.group_update = group_update;
    }

    public String getCreated() {
        return created;
    }

    public void setCreated(String created) {
        this.created = created;
    }

    public String getMember_name() {
        return member_name;
    }

    public void setMember_name(String member_name) {
        this.member_name = member_name;
    }

    public String getModified() {
        return modified;
    }

    public void setModified(String modified) {
        this.modified = modified;
    }

    public String getProfession_name() {
        return profession_name;
    }

    public void setProfession_name(String profession_name) {
        this.profession_name = profession_name;
    }

    public String getCountry_name() {
        return country_name;
    }

    public void setCountry_name(String country_name) {
        this.country_name = country_name;
    }

    public String getState_name() {
        return state_name;
    }

    public void setState_name(String state_name) {
        this.state_name = state_name;
    }

    public String getMember_profile_image() {
        return member_profile_image;
    }

    public void setMember_profile_image(String member_profile_image) {
        this.member_profile_image = member_profile_image;
    }
    public String getTotalReview() {
        return totalReview;
    }

    public void setTotalReview(String totalReview) {
        this.totalReview = totalReview;
    }





}
