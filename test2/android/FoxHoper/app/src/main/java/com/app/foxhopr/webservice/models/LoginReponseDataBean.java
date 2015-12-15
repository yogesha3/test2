package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

public class LoginReponseDataBean {
	@SerializedName("id")
	private String  id;

	@SerializedName("username")
	private String  username;

	@SerializedName("user_email")
	private String  user_email;

	@SerializedName("is_active")
	private Boolean  is_active;

	@SerializedName("is_confirm")
	private Boolean  is_confirm;

	@SerializedName("fname")
	private String  fname;

	@SerializedName("lname")
	private String  lname;

	@SerializedName("profile_image")
	private String  profile_image;

	@SerializedName("profile_completion_status")
	private String  profile_completion_status;

	@SerializedName("group_type")
	private String  group_type;

	@SerializedName("rating")
	private String  rating;

	@SerializedName("group_id")
	private String  group_id;

	@SerializedName("city")
	private String  city;

	@SerializedName("zipcode")
	private String  zipcode;

	@SerializedName("next_shuffle")
	private String  next_shuffle;

	public String getGroup_id() {
		return group_id;
	}

	public void setGroup_id(String group_id) {
		this.group_id = group_id;
	}

	public String getCity() {
		return city;
	}

	public void setCity(String city) {
		this.city = city;
	}

	public String getZipcode() {
		return zipcode;
	}

	public void setZipcode(String zipcode) {
		this.zipcode = zipcode;
	}


	public String getId() {
		return id;
	}

	public void setId(String id) {
		this.id = id;
	}

	public String getUsername() {
		return username;
	}

	public void setUsername(String username) {
		this.username = username;
	}

	public Boolean getIs_confirm() {
		return is_confirm;
	}

	public void setIs_confirm(Boolean is_confirm) {
		this.is_confirm = is_confirm;
	}

	public String getUser_email() {
		return user_email;
	}

	public void setUser_email(String user_email) {
		this.user_email = user_email;
	}

	public Boolean getIs_active() {
		return is_active;
	}

	public void setIs_active(Boolean is_active) {
		this.is_active = is_active;
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

	public String getProfile_image() {
		return profile_image;
	}

	public void setProfile_image(String profile_image) {
		this.profile_image = profile_image;
	}

	public String getProfile_completion_status() {
		return profile_completion_status;
	}

	public void setProfile_completion_status(String profile_completion_status) {
		this.profile_completion_status = profile_completion_status;
	}

	public String getGroup_type() {
		return group_type;
	}

	public void setGroup_type(String group_type) {
		this.group_type = group_type;
	}

	public String getRating() {
		return rating;
	}

	public void setRating(String rating) {
		this.rating = rating;
	}

	public String getNext_shuffle() {
		return next_shuffle;
	}

	public void setNext_shuffle(String next_shuffle) {
		this.next_shuffle = next_shuffle;
	}
}
