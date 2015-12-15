package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

public class AttachmentsListBean {
	@SerializedName("name")
	private String  name;

	@SerializedName("fileUrl")
	private String  fileUrl;

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getFileUrl() {
		return fileUrl;
	}

	public void setFileUrl(String fileUrl) {
		this.fileUrl = fileUrl;
	}
}
