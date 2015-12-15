package com.app.foxhopr.ui.activities;

import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.app.foxhopr.constants.IntentConstants;
import com.app.foxhopr.webservice.models.CurrentTeamInnerModel;
import com.app.foxhopr.webservice.models.TeamMebmersListInnerModel;
import com.foxhoper.app.R;

import java.util.ArrayList;

public class SendReferralsSelectMembersActivity extends FragmentActivity implements View.OnClickListener {
	private RelativeLayout rlBack;
	private TextView mTxtvSendReferrals;
	private LinearLayout mlLMemberList;
	private LinearLayout mlLNext;
	private TextView txtVreferralteamMember;

	private ArrayList<TeamMebmersListInnerModel> getSelectedMemberListData;
	private TextView mTxtvNextBtn;
	private ArrayList<CurrentTeamInnerModel> selectedMember = new ArrayList<CurrentTeamInnerModel>();

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_compose_referrals);
		initUi();
		try{
			selectedMember=(ArrayList<CurrentTeamInnerModel>) getIntent().getSerializableExtra("member_ids");
			setTeamMember();
		}catch (Exception e){
			e.printStackTrace();
		}
	}

	private void setTeamMember(){

		if(null!=selectedMember){
			getSelectedMemberListData=new ArrayList<TeamMebmersListInnerModel>();
		}

		for(CurrentTeamInnerModel cTMember:selectedMember ){
			TeamMebmersListInnerModel mTeamMebmersListInnerModel=new TeamMebmersListInnerModel();
			mTeamMebmersListInnerModel.setId(cTMember.getId());
			mTeamMebmersListInnerModel.setUser_id(cTMember.getUser_id());
			mTeamMebmersListInnerModel.setFname(cTMember.getFname());
			mTeamMebmersListInnerModel.setLname(cTMember.getLname());
			mTeamMebmersListInnerModel.setIsChecked(true);
			mTeamMebmersListInnerModel.setSelected(true);
			getSelectedMemberListData.add(mTeamMebmersListInnerModel);
		}

		if(getSelectedMemberListData != null){
			if(getSelectedMemberListData != null){
				if(getSelectedMemberListData.size() ==1){
					txtVreferralteamMember.setText(getSelectedMemberListData.get(0).getFname() +" "+getSelectedMemberListData.get(0).getLname());
				}else{
					txtVreferralteamMember.setText(getResources().getString(R.string.str_team_members)+"("+getSelectedMemberListData.size()+")");
				}
				mlLNext.getBackground().setAlpha(255);
				mlLNext.setOnClickListener(this);
				mTxtvNextBtn.setTextColor(Color.parseColor("#ffffff"));
			}

		}
	}

	private void initUi(){
		rlBack	=	(RelativeLayout)findViewById(R.id.rlBack);

		mTxtvSendReferrals	=	(TextView)findViewById(R.id.textViewHeaderTitle);
		mTxtvSendReferrals.setVisibility(View.VISIBLE);
		mTxtvSendReferrals.setText(getResources().getString(R.string.str_referrals));

		txtVreferralteamMember	=	(TextView)findViewById(R.id.txtVreferralteamMember);

		mlLMemberList	=	(LinearLayout)findViewById(R.id.llComposeMemberList);
		mlLNext	=	(LinearLayout)findViewById(R.id.llSendReferralsNextStepOne);

		mTxtvNextBtn	=	(TextView)findViewById(R.id.txtvNextReferrals);

		mlLNext.getBackground().setAlpha(200);
		mlLNext.setClickable(false);
		mTxtvNextBtn.setTextColor(Color.parseColor("#A0A0A0"));


		rlBack.setOnClickListener(this);
		mlLNext.setOnClickListener(null);
		mlLMemberList.setOnClickListener(this);
	}

	@Override
	public void onBackPressed() {
		finish();
		overridePendingTransition(0, 0);
	}

	@Override
	public void onClick(View v) {
		if(v == mlLNext){
			Intent intObj = new Intent(SendReferralsSelectMembersActivity.this, SendReferralsSelectContactsActivity.class);

			if(getSelectedMemberListData != null ){

				intObj.putExtra(IntentConstants.SELECTED_MEMBER_LIST, getSelectedMemberListData);
			}

			startActivity(intObj);
			overridePendingTransition(0,0);
		}else if(v == mlLMemberList){
			Intent intObj = new Intent(SendReferralsSelectMembersActivity.this, SelectMembersActivity.class);

			if(getSelectedMemberListData != null ){

				intObj.putExtra(IntentConstants.SELECTED_MEMBER_LIST, getSelectedMemberListData);
				Bundle bundle = new Bundle();
				bundle.putSerializable(IntentConstants.SELECTED_MEMBER_LIST, getSelectedMemberListData);
				intObj.putExtras(bundle);
			}

			startActivityForResult(intObj, 1);
			overridePendingTransition(0,0);

		}else if(v == rlBack){
			finish();
			overridePendingTransition(0, 0);
		}
	}


	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {

		if (requestCode == 1) {
			if(resultCode == RESULT_OK){
				String result=data.getStringExtra("result");
				if(data != null){
					Bundle bundle=data.getExtras();
					getSelectedMemberListData = 	(ArrayList<TeamMebmersListInnerModel>)bundle.getSerializable(IntentConstants.SELECTED_MEMBER_LIST);
					if(getSelectedMemberListData != null){
						if(getSelectedMemberListData.size() ==1){
							txtVreferralteamMember.setText(getSelectedMemberListData.get(0).getFname() +" "+getSelectedMemberListData.get(0).getLname());
						}else{
							txtVreferralteamMember.setText(getResources().getString(R.string.str_team_members)+"("+getSelectedMemberListData.size()+")");
						}
						mlLNext.getBackground().setAlpha(255);
						mlLNext.setOnClickListener(this);
						mTxtvNextBtn.setTextColor(Color.parseColor("#ffffff"));
					}
				}
			}
			if (resultCode == RESULT_CANCELED) {
				//Write your code if there's no result
			}
		}
	}



}
