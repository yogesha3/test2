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
import com.app.foxhopr.webservice.models.ExistTeamMebmersListInnerModel;
import com.app.foxhopr.webservice.models.TeamMebmersListInnerModel;
import com.foxhoper.app.R;

import java.util.ArrayList;

public class SendReferralsSelectContactsActivity extends FragmentActivity implements View.OnClickListener {
	private RelativeLayout rlBack;
	private TextView mTxtvSendReferrals;
	private LinearLayout llNewContact;
	private LinearLayout llExistingContact;
	private LinearLayout mlLNext;
	private TextView txtvContactNext;
	private TextView txtvSelectAnExistingContact;


	private ArrayList<TeamMebmersListInnerModel> getSelectedMemberListData;
	private ArrayList<ExistTeamMebmersListInnerModel> getSelectedExistMemberListData;


	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_send_referrals_select_contacts);
		initUi();
	}

	private void initUi(){
		rlBack	=	(RelativeLayout)findViewById(R.id.rlBack);

		mTxtvSendReferrals	=	(TextView)findViewById(R.id.textViewHeaderTitle);
		mTxtvSendReferrals.setVisibility(View.VISIBLE);
		mTxtvSendReferrals.setText(getResources().getString(R.string.str_referrals));

		mlLNext	=	(LinearLayout)findViewById(R.id.llSendReferralsNextStepTwo);
		llNewContact	=	(LinearLayout)findViewById(R.id.llNewContact);
		llExistingContact	=	(LinearLayout)findViewById(R.id.llExistingContact);
		txtvSelectAnExistingContact	=	(TextView)findViewById(R.id.txtvSelectAnExistingContact);


		txtvContactNext	=	(TextView)findViewById(R.id.txtvContactNext);
		txtvContactNext.setTextColor(Color.parseColor("#A0A0A0"));
		rlBack.setOnClickListener(this);
		llNewContact.setOnClickListener(this);
		llExistingContact.setOnClickListener(this);
		getDataFromIntent();
	}


	private void getDataFromIntent(){
		try{
			Intent intent=this.getIntent();
			if((ArrayList<TeamMebmersListInnerModel>)intent.getSerializableExtra(IntentConstants.SELECTED_MEMBER_LIST) != null){
				getSelectedMemberListData =  (ArrayList<TeamMebmersListInnerModel>)intent.getSerializableExtra(IntentConstants.SELECTED_MEMBER_LIST);
			}
		}catch (Exception ex){
			ex.printStackTrace();
		}
	}


	@Override
	public void onBackPressed() {
		finish();
		overridePendingTransition(0, 0);
	}
	@Override
	public void onClick(View v) {
		if(v == mlLNext){
			Intent intObj = new Intent(SendReferralsSelectContactsActivity.this, SendReferralsExistingContactCreationActivity.class);
			if(getSelectedExistMemberListData != null ){
				intObj.putExtra(IntentConstants.SELECTED_EXISTING_CONTACT, getSelectedExistMemberListData);
				intObj.putExtra(IntentConstants.SELECTED_MEMBER_LIST, getSelectedMemberListData);
			}
			startActivity(intObj);
		}else if(v == rlBack){
			finish();
			overridePendingTransition(0, 0);
		}if(v == llNewContact){
			Intent intObj = new Intent(SendReferralsSelectContactsActivity.this, SendReferralsNewContactCreationActivity.class);
			if(getSelectedMemberListData != null ){
				intObj.putExtra(IntentConstants.SELECTED_MEMBER_LIST, getSelectedMemberListData);
			}
			startActivity(intObj);
			overridePendingTransition(0, 0);
		}else if(v == llExistingContact){
			Intent intObj = new Intent(SendReferralsSelectContactsActivity.this, SelectContactListActivity.class);
			if(getSelectedExistMemberListData != null ){
				intObj.putExtra(IntentConstants.SELECTED_EXISTING_CONTACT, getSelectedExistMemberListData);
			}
			startActivityForResult(intObj,1);
			overridePendingTransition(0, 0);
		}

	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		super.onActivityResult(requestCode, resultCode, data);
		if (requestCode == 1) {
			if(resultCode == RESULT_OK){
				String result=data.getStringExtra("result");
				if(data != null){
					Bundle bundle=data.getExtras();
					getSelectedExistMemberListData = 	(ArrayList<ExistTeamMebmersListInnerModel>)bundle.getSerializable(IntentConstants.SELECTED_EXISTING_CONTACT);
					//if(getSelectedExistMemberListData != null){
						if(null!= getSelectedExistMemberListData && getSelectedExistMemberListData.size() >0){
							String userName ="";
							if(getSelectedExistMemberListData.get(0).getFirst_name() != null && !getSelectedExistMemberListData.get(0).getFirst_name().equalsIgnoreCase("")){
								userName = getSelectedExistMemberListData.get(0).getFirst_name();
								String s1 = userName.substring(0, 1).toUpperCase();
								userName = s1 + userName.substring(1);
							}
							if(getSelectedExistMemberListData.get(0).getLast_name() != null && !getSelectedExistMemberListData.get(0).getLast_name().equalsIgnoreCase("")){
								String s1 = getSelectedExistMemberListData.get(0).getLast_name().substring(0, 1).toUpperCase();
								userName =  userName+" "+ s1 + getSelectedExistMemberListData.get(0).getLast_name().substring(1);
							}

							txtvSelectAnExistingContact.setText(userName);
							mlLNext.getBackground().setAlpha(255);
							mlLNext.setOnClickListener(this);
							txtvContactNext.setTextColor(Color.parseColor("#ffffff"));
							llNewContact.setOnClickListener(null);
						}else{
							llNewContact.setOnClickListener(this);
							txtvSelectAnExistingContact.setText(getString(R.string.str_select_an_existing_contact));
							mlLNext.getBackground().setAlpha(125);
							mlLNext.setOnClickListener(null);
							txtvContactNext.setTextColor(Color.parseColor("#A0A0A0"));
						}

					//}
				}
			}
			if (resultCode == RESULT_CANCELED) {
				//Write your code if there's no result
			}
		}
	}
}
