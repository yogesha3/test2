package com.app.foxhopr.ui.activities;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.app.foxhopr.adapter.ExistMemberListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.IntentConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.MemberListRequestModel;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ExistContactClickCallBack;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.webservice.models.ExistTeamMebmersListInnerModel;
import com.app.foxhopr.webservice.models.ExistTeamMembersResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.ArrayList;
import java.util.Collections;
import java.util.Locale;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class SelectContactListActivity extends FragmentActivity implements View.OnClickListener {

	private static String TAG="SelectContactListActivity";
	private RelativeLayout rlBack;
	private TextView mTxtvSendReferrals;
	private Button mBtnDone;
	private ListView mListView;
	private EditText mEditsearch;
	private TextView txtvSelectAllName;
	private CheckBox mChckBoxSelectAllMember;
	private TextView mTxtvNoReult;
	private View headerView;

	private ProgressHUD mProgressHUD;

	private ExistMemberListAdapter mMemberListAdapter;
	private ArrayList<ExistTeamMebmersListInnerModel> getListData=new ArrayList<ExistTeamMebmersListInnerModel>();

	private ArrayList<ExistTeamMebmersListInnerModel> getSelectedListData;

	public static boolean isAllSelected = false;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_select_members);

		isAllSelected = false;

		initUi();
	}

	private void initUi(){
		rlBack				=	(RelativeLayout)findViewById(R.id.rlBack);

		mTxtvSendReferrals	=	(TextView)findViewById(R.id.textViewHeaderTitle);
		mBtnDone			=	(Button)findViewById(R.id.btnDoneHeader);

		// Locate the EditText in listview_main.xml
		mEditsearch = (EditText) findViewById(R.id.edtSearchMemberList);
		mTxtvNoReult = (TextView) findViewById(R.id.textViewNoResult);

		mListView			=	(ListView)findViewById(R.id.listViewMembers);

		// add the footer before adding the adapter, else the footer will not load!
		headerView = ((LayoutInflater) SelectContactListActivity.this.getSystemService(Context.LAYOUT_INFLATER_SERVICE)).inflate(R.layout.item_list, null, false);
		mListView.addHeaderView(headerView);

		// Locate the TextViews in listview_item.xml
		txtvSelectAllName = (TextView) headerView.findViewById(R.id.textvNameList);
		mChckBoxSelectAllMember = (CheckBox) headerView.findViewById(R.id.checkBoxItemMemberSelect);

		txtvSelectAllName.setText(getResources().getString(R.string.str_clear_all));

		mBtnDone.setVisibility(View.VISIBLE);
		mTxtvSendReferrals.setVisibility(View.VISIBLE);
		String title=getIntent().getStringExtra("from");
		if(null!=title && title.equals(getResources().getString(R.string.str_messages))){
			mTxtvSendReferrals.setText(getResources().getString(R.string.str_messages));
		}else{
			mTxtvSendReferrals.setText(getResources().getString(R.string.str_referrals));
		}

		rlBack.setOnClickListener(this);
		mChckBoxSelectAllMember.setOnClickListener(this);
		mBtnDone.setOnClickListener(this);
		headerView.setOnClickListener(this);
		//Get the data from intents
		getDataFromIntent();

		//Call the web service
		callWebServiceMethodCalling();


		// Capture Text in EditText
		mEditsearch.addTextChangedListener(new TextWatcher() {

			@Override
			public void afterTextChanged(Editable arg0) {
				String text = mEditsearch.getText().toString().toLowerCase(Locale.getDefault());
				if(mMemberListAdapter != null){
					mMemberListAdapter.filter(text);
				}
			}

			@Override
			public void beforeTextChanged(CharSequence arg0, int arg1,
										  int arg2, int arg3) {
			}

			@Override
			public void onTextChanged(CharSequence arg0, int arg1, int arg2,
									  int arg3) {
			}
		});
	}

	private void getDataFromIntent(){
		Intent intent=this.getIntent();
		if((ArrayList<ExistTeamMebmersListInnerModel>)intent.getSerializableExtra(IntentConstants.SELECTED_EXISTING_CONTACT) != null){
			getSelectedListData =  (ArrayList<ExistTeamMebmersListInnerModel>)intent.getSerializableExtra(IntentConstants.SELECTED_EXISTING_CONTACT);
		}
	}

	private void callWebServiceMethodCalling(){
		try{
			if (AppUtils.isOnline(SelectContactListActivity.this)) {
				mProgressHUD = ProgressHUD.show(SelectContactListActivity.this,"", true,true);
				mProgressHUD.setCancelable(false);
				callWebservice();
			}
			else{
				mListView.setVisibility(View.GONE);
				mTxtvNoReult.setVisibility(View.VISIBLE);
				ErrorMsgDialog.showErrorAlert(SelectContactListActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
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
		if(v == rlBack){
			finish();
			overridePendingTransition(0, 0);
		}else if(v == mChckBoxSelectAllMember){
			if(isAllSelected){
				isAllSelected = false;
				for(int i =0; i< getListData.size(); i++){
					getListData.get(i).setSelected(false);
				}
				mMemberListAdapter.notifyDataSetChanged();
			}else{
				isAllSelected = true;
				for(int i =0; i< getListData.size(); i++){
					getListData.get(i).setSelected(false);
				}
				mMemberListAdapter.notifyDataSetChanged();
			}
		}else if(v == mBtnDone){
			try{

				if (AppUtils.isOnline(SelectContactListActivity.this)) {

					getSelectedListData = new ArrayList<ExistTeamMebmersListInnerModel>();

					if(getListData != null){
						for(int i =0; i< getListData.size(); i++){
							if(getListData.get(i).isSelected()){
								getSelectedListData.add(getListData.get(i));
							}
						}

						//Send the selected list back

						if(getSelectedListData != null){
							Bundle bundle = new Bundle();
							bundle.putSerializable(IntentConstants.SELECTED_EXISTING_CONTACT, getSelectedListData);
							Intent returnIntent = new Intent();
							returnIntent.putExtras(bundle);
							setResult(RESULT_OK,returnIntent);
							finish();
						}
					}
				}else{

					ErrorMsgDialog.showErrorAlert(SelectContactListActivity.this, "", getString(R.string.wrng_str_no_record));
				}


			}catch (Exception ex){
				ex.printStackTrace();
			}



		}else if(v == headerView){
			if(isAllSelected){
				isAllSelected = false;
				for(int i =0; i< getListData.size(); i++){
					getListData.get(i).setSelected(false);
				}
				mMemberListAdapter.notifyDataSetChanged();
			}else{
				isAllSelected = true;
				for(int i =0; i< getListData.size(); i++){
					getListData.get(i).setSelected(false);
				}
				mMemberListAdapter.notifyDataSetChanged();
			}
		}
	}


	/**
	 * THis method is use to call the webservice for current user listing and get
	 * The response and error from server
	 */
	private void callWebservice() throws Exception{
		RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.CONTACT_MEMBER_ACTION_NAME, WebServiceConstants.TEAMMEMBER_CONTROL_NAME, SelectContactListActivity.this)).build();
		RequestApi requestApi = restAdapter.create(RequestApi.class);

		requestApi.getMembersListRequest(getRequestModel(), new Callback<Response>() {
			@Override
			public void success(Response responseModel, Response response) {
				// Try to get response body
				mProgressHUD.dismiss();
				try {
					if (responseModel != null) {
						Log.e(TAG,WebServiceUtils.getResponseString(responseModel));
						parseWebserviceResponse(WebServiceUtils.getResponseString(responseModel));
					}
				} catch (Exception ex) {
					ex.printStackTrace();
				}
			}

			@Override
			public void failure(RetrofitError error) {
				error.printStackTrace();
				mProgressHUD.dismiss();
				ErrorMsgDialog.showErrorAlert(SelectContactListActivity.this, "", getString(R.string.wrng_str_server_error));
			}
		});
	}

	/**
	 * This method is use for get the request model
	 * @return
	 */
	private MemberListRequestModel getRequestModel() throws  Exception{
		MemberListRequestModel mLoginRequestModel = new MemberListRequestModel();
		mLoginRequestModel.setUser_email("");
		return mLoginRequestModel;
	}

	/**
	 * parseLoginService
	 * @param responseStr
	 * @throws Exception
	 */

	private void parseWebserviceResponse(String responseStr) throws Exception{
		Log.e("id",responseStr);
		Gson gson = new Gson();
		ExistTeamMembersResponseModel get_Response = gson.fromJson(responseStr, ExistTeamMembersResponseModel.class);

		if(get_Response != null){
			if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
				if(get_Response.getGetResult() != null && get_Response.getGetResult().size() >0){
					getListData =	get_Response.getGetResult();
					checkIntentDataExistOrNot();
					setListAdapter();
					//callPreviousMemberWebservice();
				}
			}else{
				if(getListData.size()==0) {
					//ErrorMsgDialog.showErrorAlert(SelectMembersActivity.this, "", get_Response.getMessage());
					mTxtvNoReult.setVisibility(View.VISIBLE);
					mTxtvNoReult.setText(getResources().getString(R.string.wrng_str_no_record));
					mListView.setVisibility(View.GONE);
				}
				//ErrorMsgDialog.showErrorAlert(SelectContactListActivity.this, "", get_Response.getMessage());
				//callPreviousMemberWebservice();
			}
		}else{
			ErrorMsgDialog.showErrorAlert(SelectContactListActivity.this, "", getString(R.string.wrng_str_server_error));
		}
	}

	class UnCheckedAll implements ExistContactClickCallBack {

		@Override
		public void itemClickAction(ArrayList<ExistTeamMebmersListInnerModel> mList,CheckBox chbox,int position) {
				for(int i =0; i< getListData.size(); i++){
					getListData.get(i).setSelected(false);
				}
				getListData.get(position).setSelected(true);
				//getListData.get(position).isSelected();
				mMemberListAdapter.notifyDataSetChanged();

		}
	}


	private void setListAdapter(){
		// Pass results to ListViewAdapter Class
		Collections.sort(getListData);
		mMemberListAdapter = new ExistMemberListAdapter(this, getListData, mTxtvNoReult, mListView, mChckBoxSelectAllMember,new UnCheckedAll());

		// Binds the Adapter to the ListView
		mListView.setAdapter(mMemberListAdapter);
	}

	private void checkIntentDataExistOrNot(){
		try{
			if(getSelectedListData != null && getSelectedListData.size() > 0){
				for(int counter = 0; counter < getListData.size(); counter++) {
					for (int j=0; j < getSelectedListData.size(); j++){
						if(getSelectedListData.get(j).getId().equalsIgnoreCase(getListData.get(counter).getId())) {
							getListData.get(counter).setSelected(true);
						}
					}
				}

				if(getListData.size() == getSelectedListData.size()){
					mChckBoxSelectAllMember.setChecked(true);
				}
			}
		}catch (Exception ex){
			ex.printStackTrace();
		}
	}
}
