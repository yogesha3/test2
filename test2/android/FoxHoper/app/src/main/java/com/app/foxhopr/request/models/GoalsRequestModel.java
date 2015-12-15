package com.app.foxhopr.request.models;

/**
 * Created by Chobey R. on 24/11/15.
 */
public class GoalsRequestModel {
    private String mode;
    private int group_goals;

    public int getGroup_member_goals() {
        return group_member_goals;
    }

    public void setGroup_member_goals(int group_member_goals) {
        this.group_member_goals = group_member_goals;
    }

    public int getGroup_goals() {
        return group_goals;
    }

    public void setGroup_goals(int group_goals) {
        this.group_goals = group_goals;
    }

    public int getIndividual_goals() {
        return individual_goals;
    }

    public void setIndividual_goals(int individual_goals) {
        this.individual_goals = individual_goals;
    }

    private int group_member_goals;
    private int individual_goals;



    public String getMode() {
        return mode;
    }

    public void setMode(String mode) {
        this.mode = mode;
    }



}
