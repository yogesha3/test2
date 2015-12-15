package com.app.foxhopr.request.models;

public class ReceivedReferralsRequestModel {
	private String page_no;
	private String record_per_page;
	private String list_page;
	private String referral_name;
	private String sender_name;
	private String status;

	public String getPage_no() {
		return page_no;
	}

	public void setPage_no(String page_no) {
		this.page_no = page_no;
	}

	public String getRecord_per_page() {
		return record_per_page;
	}

	public void setRecord_per_page(String record_per_page) {
		this.record_per_page = record_per_page;
	}

	public String getList_page() {
		return list_page;
	}

	public void setList_page(String list_page) {
		this.list_page = list_page;
	}

	public String getReferral_name() {
		return referral_name;
	}

	public void setReferral_name(String referral_name) {
		this.referral_name = referral_name;
	}

	public String getSender_name() {
		return sender_name;
	}

	public void setSender_name(String sender_name) {
		this.sender_name = sender_name;
	}

	public String getStatus() {
		return status;
	}

	public void setStatus(String status) {
		this.status = status;
	}
}