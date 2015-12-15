package com.app.foxhopr.ui.activities;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.IntentConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.models.CreateContactRequestModel;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.MutiPartRequest;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.webservice.models.AttachmentsListBean;
import com.app.foxhopr.webservice.models.SendReferralsResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;

public class SendReferralsNewContactCreationFinalStepActivity extends FragmentActivity implements View.OnClickListener {
	private RelativeLayout rlBack;
	private TextView mTxtvSendReferrals;
	private LinearLayout mlLNext;
	private RelativeLayout mRlAttachFile;
	private LinearLayout mllAttachmentList;
	private EditText mEdtVMessage;

	private final int CHOOSE_FILE = 1;

	private ArrayList<AttachmentsListBean> attachmentListArr;
	private ProgressHUD mProgressHUD;

	private CreateContactRequestModel mPostData;

	private String strMessage =" ";
	private String strFname =" ";
	private String strLname =" ";
	private String strCompanyName =" ";

	private String strJobTitle =" ";
	private String strEmail =" ";
	private String strWebSite =" ";
	private String strAddress =" ";
	private String strCity =" ";
	private String strOfficePhoneNo =" ";
	private String strMobilePhoneNo =" ";

	private String strCountryName =" ";
	private String strCountryId =" ";

	private String strStateName =" ";
	private String strStatesId =" ";

	private String strMemberList =" ";
	private String strZipcode =" ";
	private String mcontactId;


	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_send_referrals_new_contacts_attachments);

		initUi();

		attachmentListArr = new ArrayList<AttachmentsListBean>();
	}

	private void initUi(){
		rlBack						=	(RelativeLayout)findViewById(R.id.rlBack);

		mTxtvSendReferrals			=	(TextView)findViewById(R.id.textViewHeaderTitle);
		mllAttachmentList			=	(LinearLayout)findViewById(R.id.llAttachmentList);
		mRlAttachFile				=	(RelativeLayout)findViewById(R.id.rlAttachFile);

		mEdtVMessage				=	(EditText)findViewById(R.id.editTextMessageCreateNewContact);

		mTxtvSendReferrals.setVisibility(View.VISIBLE);
		mTxtvSendReferrals.setText(getResources().getString(R.string.str_referrals));
		mlLNext	=	(LinearLayout)findViewById(R.id.llAttachFileNext);
		mlLNext.setOnClickListener(this);
		rlBack.setOnClickListener(this);
		mRlAttachFile.setOnClickListener(this);

		getDataFromIntent();
	}
	private void getDataFromIntent(){
		try{
			Intent intent=this.getIntent();

			if(intent.getStringExtra(IntentConstants.INTENT_FIRST_NAME) != null)
				strFname =intent.getStringExtra(IntentConstants.INTENT_FIRST_NAME);

			if(intent.getStringExtra(IntentConstants.INTENT_LAST_NAME) != null)
				strLname =intent.getStringExtra(IntentConstants.INTENT_LAST_NAME);

			if(intent.getStringExtra(IntentConstants.INTENT_COMPANY_NAME) != null)
				strCompanyName =intent.getStringExtra(IntentConstants.INTENT_COMPANY_NAME);

			if(intent.getStringExtra(IntentConstants.INTENT_JOB_TITLE) != null)
				strJobTitle =intent.getStringExtra(IntentConstants.INTENT_JOB_TITLE);

			if(intent.getStringExtra(IntentConstants.INTENT_WEBSITE) != null)
				strWebSite =intent.getStringExtra(IntentConstants.INTENT_WEBSITE);

			if(intent.getStringExtra(IntentConstants.INTENT_WEBSITE) != null)
				strWebSite =intent.getStringExtra(IntentConstants.INTENT_WEBSITE);

			if(intent.getStringExtra(IntentConstants.INTENT_COUNTRY_ID) != null)
				strCountryId =intent.getStringExtra(IntentConstants.INTENT_COUNTRY_ID);

			if(intent.getStringExtra(IntentConstants.INTENT_STATE_ID) != null)
				strStatesId =intent.getStringExtra(IntentConstants.INTENT_STATE_ID);

			if(intent.getStringExtra(IntentConstants.INTENT_CITY) != null)
				strCity =intent.getStringExtra(IntentConstants.INTENT_CITY);

			if(intent.getStringExtra(IntentConstants.INTENT_EMAIL) != null)
				strEmail =intent.getStringExtra(IntentConstants.INTENT_EMAIL);

			if(intent.getStringExtra(IntentConstants.INTENT_OFFICE_PHONE) != null)
				strOfficePhoneNo =intent.getStringExtra(IntentConstants.INTENT_OFFICE_PHONE);

			if(intent.getStringExtra(IntentConstants.INTENT_MOBILE) != null)
				strMobilePhoneNo =intent.getStringExtra(IntentConstants.INTENT_MOBILE);

			if(intent.getStringExtra(IntentConstants.INTENT_TEAM_MEAMBER) != null)
				strMemberList =intent.getStringExtra(IntentConstants.INTENT_TEAM_MEAMBER);

			if(intent.getStringExtra(IntentConstants.INTENT_ADDRESS) != null)
				strAddress =intent.getStringExtra(IntentConstants.INTENT_ADDRESS);

			if(intent.getStringExtra(IntentConstants.INTENT_ZIPCODE) != null)
				strZipcode =intent.getStringExtra(IntentConstants.INTENT_ZIPCODE);
			if(intent.getStringExtra(IntentConstants.INTENT_CONTACT_ID) != null)
				mcontactId =intent.getStringExtra(IntentConstants.INTENT_CONTACT_ID);

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
			strMessage = mEdtVMessage.getText().toString();

			if(checkValidation()){
				try{
					if (AppUtils.isOnline(SendReferralsNewContactCreationFinalStepActivity.this)) {
						new ImageUploadTask().execute();
					}
					else{
						ErrorMsgDialog.showErrorAlert(SendReferralsNewContactCreationFinalStepActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
					}
				}catch (Exception ex){
					ex.printStackTrace();
				}


			}

		}else if(v == rlBack){
			finish();
			overridePendingTransition(0, 0);
		}else if(v == mRlAttachFile){
			final Intent intent = new Intent(Intent.ACTION_GET_CONTENT);
			intent.setType("*/*");
			intent.addCategory(Intent.CATEGORY_OPENABLE);
			intent.setAction(Intent.ACTION_GET_CONTENT);
			startActivityForResult(Intent.createChooser(intent, "Select a File to Upload"), CHOOSE_FILE);
		}
	}
	/**
	 * Method to check the validation on the page
	 * @return
	 */
	private boolean checkValidation() {
		boolean isAllFieldCorrect = true;

		if(strMessage.length() >0 && strMessage.length() >500) {
			//mEdtVMessage.setBackgroundResource(R.drawable.bg_select_team);
			isAllFieldCorrect = false;
			mEdtVMessage.requestFocus();
			mEdtVMessage.setBackgroundResource(R.drawable.bg_select_team_error);
			ErrorMsgDialog.showErrorAlert(SendReferralsNewContactCreationFinalStepActivity.this, "", getString(R.string.wrng_str_message_max_limit));
		}

		return isAllFieldCorrect;
	}
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		switch (requestCode) {
			case CHOOSE_FILE :
				if (resultCode == RESULT_OK) {
					// Get the Uri of the selected file
					Uri uri = data.getData();
					// Get the path
					String path;
					try {
						if(uri != null){
							String urlStr = AppUtils.getPath(SendReferralsNewContactCreationFinalStepActivity.this, uri);
							if(urlStr != null){
								Log.e("File Path: ", "" + attachmentListArr.size() );
								Log.e("File Path: ", "" + urlStr);
								Log.e("Basename : ", "" + AppUtils.getFileNameFromUrl(urlStr));
								if(AppUtils.getFileNameFromUrl(urlStr) != null && AppUtils.getFileExt(AppUtils.getFileNameFromUrl(urlStr)) != null){
									if(AppUtils.checkFileExtension(AppUtils.getFileExt(AppUtils.getFileNameFromUrl(urlStr)))){
										Log.e("print the file size", ""+AppUtils.getFileSize(urlStr));

										if(AppUtils.getFileSize(urlStr) < 10.2){
											if(attachmentListArr.size() > 0){
												float totalSize =0;
												for(int i=0; i < attachmentListArr.size(); i++){
													totalSize +=totalSize+AppUtils.getFileSize(attachmentListArr.get(i).getFileUrl());
												}
												if((AppUtils.getFileSize(urlStr) +totalSize) < 10.2){
													addBeanObj(urlStr);
												}
												else{
													ErrorMsgDialog.showErrorAlert(SendReferralsNewContactCreationFinalStepActivity.this, "", getString(R.string.wrng_str_attachment_exceed_limit));
												}
											}else{
												addBeanObj(urlStr);
											}
										}else{
											ErrorMsgDialog.showErrorAlert(SendReferralsNewContactCreationFinalStepActivity.this, "", getString(R.string.wrng_str_file_too_large));
										}
									}else{
										ErrorMsgDialog.showErrorAlert(SendReferralsNewContactCreationFinalStepActivity.this, "", getString(R.string.wrng_str_file_format_check));
									}
								}
							}
						}
					} catch (Exception e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					}
				}
				break;
		}
		super.onActivityResult(requestCode, resultCode, data);
	}

	private void addBeanObj(String fileUrl){
		AttachmentsListBean mAttachmentsListBean = new AttachmentsListBean();
		mAttachmentsListBean.setFileUrl(fileUrl);
		mAttachmentsListBean.setName(AppUtils.getFileNameFromUrl(fileUrl));
		attachmentListArr.add(mAttachmentsListBean);
		createAttachmentList(attachmentListArr);
	}


	public void createAttachmentList(final ArrayList<AttachmentsListBean> dataArray){

		if(mllAttachmentList.getChildCount() >0)
			mllAttachmentList.removeAllViews();

		for(int i=0; i< dataArray.size(); i++){
			LayoutInflater inflater = (LayoutInflater)getBaseContext().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
			View listItemv = inflater.inflate(R.layout.item_list_attachments, null);

			TextView mTxtvFileNameName = (TextView) listItemv.findViewById(R.id.textvFileNameList);
			ImageView imgAttchmentDelete = (ImageView) listItemv.findViewById(R.id.imgAttchmentDelete);
			mTxtvFileNameName.setText(dataArray.get(i).getName());

			final int pos = i;
			imgAttchmentDelete.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					if (attachmentListArr != null && attachmentListArr.size() > 0) {
						attachmentListArr.remove(dataArray.get(pos));
						createAttachmentList(attachmentListArr);
					}
				}
			});

			if(dataArray.size()>=5){
				mRlAttachFile.setVisibility(View.GONE);
			}else{
				mRlAttachFile.setVisibility(View.VISIBLE);
			}

			mllAttachmentList.addView(listItemv);
		}
	}

	/**
	 * Method use to create the hash map that posted to server.
	 * @return
	 */

	private HashMap<String, String> getPostRequestParmes() {

		HashMap<String, String> postValues = new HashMap<String, String>();

		postValues.put("first_name", strFname);
		postValues.put("last_name", strLname);
		postValues.put("company", strCompanyName);
		postValues.put("job_title", strJobTitle);
		postValues.put("email", strEmail);
		postValues.put("address", strAddress);
		postValues.put("country_id", strCountryId);
		postValues.put("state_id", strStatesId);
		postValues.put("city", strCity);
		postValues.put("office_phone", strOfficePhoneNo);
		postValues.put("mobile", strMobilePhoneNo);
		postValues.put("website", strWebSite);
		postValues.put("teamMembers", strMemberList);

		postValues.put("message", strMessage);
		postValues.put("zip", strZipcode);
		postValues.put("contact_id", mcontactId);

		return postValues;

	}


	class ImageUploadTask extends AsyncTask<Void, Void, String> {

		private String responseStr = "";

		@Override
		protected void onPreExecute() {
			mProgressHUD = ProgressHUD.show(SendReferralsNewContactCreationFinalStepActivity.this,"", true,true);
			mProgressHUD.setCancelable(false);
		}

		@Override
		protected String doInBackground(Void... params) {
			try {
				ArrayList<File> files = new ArrayList<File>() ;

				for(int i =0; i< attachmentListArr.size(); i++){
					files.add(new File(attachmentListArr.get(i).getFileUrl()));
				}

				responseStr =MutiPartRequest.postImage(files, getPostRequestParmes(), SendReferralsNewContactCreationFinalStepActivity.this, WebServiceConstants.REFERRALS_CONTROL_NAME, WebServiceConstants.SENDREFERRAL_ACTION_NAME);

				Log.e("Response", responseStr);
			} catch (Exception e) {
				e.printStackTrace();
			}
			return null;
		}

		@Override
		protected void onPostExecute(String result) {
			mProgressHUD.dismiss();
			try {
				parseSendReferralsService(responseStr);

			} catch (Exception ex) {
				ex.printStackTrace();
				ErrorMsgDialog.showErrorAlert(SendReferralsNewContactCreationFinalStepActivity.this, "", getString(R.string.wrng_str_server_error));
			}

		}
	}

	/**
	 * parseLoginService
	 * @param responseStr
	 * @throws Exception
	 */

	private void parseSendReferralsService(String responseStr) throws Exception{
		Gson gson = new Gson();
		SendReferralsResponseModel get_Response = gson.fromJson(responseStr, SendReferralsResponseModel.class);

		if(get_Response != null){
			if (get_Response.getReponseCode().equals(ApplicationConstants.SUCCESS_CODE)) {
				ErrorMsgDialog.alertOkButtonCallBack(SendReferralsNewContactCreationFinalStepActivity.this, "", get_Response.getMessage(),new OkPressedResponse());

			}else{
				ErrorMsgDialog.showErrorAlert(SendReferralsNewContactCreationFinalStepActivity.this, "", get_Response.getMessage());
			}
		}else{
			ErrorMsgDialog.showErrorAlert(SendReferralsNewContactCreationFinalStepActivity.this, "", getString(R.string.wrng_str_server_error));
		}
	}

	class OkPressedResponse implements AlertCallBack {

		@Override
		public void alertAction(boolean select) {
			// Call web service here
			try {
				Intent intObj = new Intent(SendReferralsNewContactCreationFinalStepActivity.this, DashboardActivity.class);
				intObj.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
				startActivity(intObj);
				overridePendingTransition(0, 0);
				finish();
			} catch (Exception e) {
				e.printStackTrace();
			}
		}
	}
}
