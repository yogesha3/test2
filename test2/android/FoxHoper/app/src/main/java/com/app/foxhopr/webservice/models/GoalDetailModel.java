package com.app.foxhopr.webservice.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by Chobey R. on 23/11/15.
 */
public class GoalDetailModel {

    @SerializedName("group_goals")
    private String group_goals;

    @SerializedName("group_member_goals")
    private String group_member_goals;

    @SerializedName("individual_goals")
    private String individual_goals;

    @SerializedName("actual_group_goals")
    private String actual_group_goals;


    @SerializedName("actual_individual_goals")
    private String actual_individual_goals;

    @SerializedName("member_type")
    private String member_type;

    public String getSet_edit_key() {
        return set_edit_key;
    }

    public void setSet_edit_key(String set_edit_key) {
        this.set_edit_key = set_edit_key;
    }

    @SerializedName("set_edit_key")
    private String set_edit_key;


    public String getGroup_goals() {
        return group_goals;
    }

    public void setGroup_goals(String group_goals) {
        this.group_goals = group_goals;
    }

    public String getGroup_member_goals() {
        return group_member_goals;
    }

    public void setGroup_member_goals(String group_member_goals) {
        this.group_member_goals = group_member_goals;
    }

    public String getIndividual_goals() {
        return individual_goals;
    }

    public void setIndividual_goals(String individual_goals) {
        this.individual_goals = individual_goals;
    }

    public String getActual_group_goals() {
        return actual_group_goals;
    }

    public void setActual_group_goals(String actual_group_goals) {
        this.actual_group_goals = actual_group_goals;
    }

    public String getMember_type() {
        return member_type;
    }

    public void setMember_type(String member_type) {
        this.member_type = member_type;
    }

    public String getActual_individual_goals() {
        return actual_individual_goals;
    }

    public void setActual_individual_goals(String actual_individual_goals) {
        this.actual_individual_goals = actual_individual_goals;
    }


}
