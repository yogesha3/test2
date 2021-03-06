package com.app.foxhopr.ui.GroupSelection;

import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.ListView;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.adapter.SelectMeetingTimeAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.IntentConstants;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.SelectDayClickCallBack;
import com.app.foxhopr.webservice.models.MeetingDayModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

public class SelectMeetingTimeActivity extends FragmentActivity implements View.OnClickListener {

	private static String TAG="SelectMeetingTimeActivity";
	private TextView mTxtvSendReferrals;
	private Button mBtnCancel;
	private Button mBtnApply;
	private ListView mListView;
	private TextView txtvSelectAllName;
	private CheckBox mChckBoxSelectAllMember;
	private TextView mTxtvNoReult;
	//private View headerView;

	private ProgressHUD mProgressHUD;
	private SharedPreference sharedPreference;

	private SelectMeetingTimeAdapter mMemberListAdapter;
	private ArrayList<MeetingDayModel> getListData=new ArrayList<MeetingDayModel>();

	private ArrayList<MeetingDayModel> getSelectedListData;

	public static boolean isAllSelected = false;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_select_meeting);

		isAllSelected = false;

		initUi();
	}

	private void initUi() {
		sharedPreference = new SharedPreference();

		mTxtvSendReferrals = (TextView) findViewById(R.id.textViewHeaderTitle);
		mBtnCancel = (Button) findViewById(R.id.btncancelHeader);
		mBtnApply = (Button) findViewById(R.id.btnapplyHeader);

		mTxtvSendReferrals.setText(getString(R.string.str_by_time));
		// Locate the EditText in listview_main.xml
		mTxtvNoReult = (TextView) findViewById(R.id.textViewNoResult);

		mListView = (ListView) findViewById(R.id.listViewMembers);

		// add the footer before adding the adapter, else the footer will not load!
		//headerView = ((LayoutInflater) SelectMeetingTimeActivity.this.getSystemService(Context.LAYOUT_INFLATER_SERVICE)).inflate(R.layout.item_list, null, false);
		//mListView.addHeaderView(headerView);

		// Locate the TextViews in listview_item.xml
		//txtvSelectAllName = (TextView) headerView.findViewById(R.id.textvNameList);
		//mChckBoxSelectAllMember = (CheckBox) headerView.findViewById(R.id.checkBoxItemMemberSelect);

		//txtvSelectAllName.setText(getResources().getString(R.string.str_select_all));

		mTxtvSendReferrals.setVisibility(View.VISIBLE);
		/*String title=getIntent().getStringExtra("from");
		if(null!=title && title.equals(getResources().getString(R.string.str_messages))){
			mTxtvSendReferrals.setText(getResources().getString(R.string.str_messages));
		}else{
			mTxtvSendReferrals.setText(getResources().getString(R.string.str_referrals));
		}*/

		mBtnCancel.setOnClickListener(this);
		//mChckBoxSelectAllMember.setOnClickListener(this);
		mBtnApply.setOnClickListener(this);
		//headerView.setOnClickListener(this);
		//Get the data from intents
		getDataFromInt();
		getDataForList();
		//Call the web service
	}
	private void getDataFromIntent(){
		List<MeetingDayModel> get_Response;
		String savedMeetingDays;
		if(sharedPreference.getSelectedGroupType(SelectMeetingTimeActivity.this).equals(ApplicationConstants.GROUP_LOCAL)){
			savedMeetingDays=sharedPreference.getMeetingTimes(SelectMeetingTimeActivity.this);
		}else{
			 savedMeetingDays=sharedPreference.getMeetingTimesGlobal(SelectMeetingTimeActivity.this);
		}


		if(null!=savedMeetingDays && !savedMeetingDays.equals("")) {
			Gson gson = new Gson();
			MeetingDayModel[] favoriteItems = gson.fromJson(savedMeetingDays, MeetingDayModel[].class);
			get_Response = Arrays.asList(favoriteItems);
			get_Response = new ArrayList(get_Response);

			if (null != get_Response && get_Response.size() > 0) {
				getSelectedListData = new ArrayList(get_Response);
			}
		}
	}

	private void getDataFromInt(){
		try{
			Intent intent=this.getIntent();
			if((ArrayList<MeetingDayModel>)intent.getSerializableExtra(IntentConstants.SELECTED_TIME_LIST) != null){
				getSelectedListData =  (ArrayList<MeetingDayModel>)intent.getSerializableExtra(IntentConstants.SELECTED_TIME_LIST);
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
		if(v == mBtnCancel){
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
					getListData.get(i).setSelected(true);
				}
				mMemberListAdapter.notifyDataSetChanged();
			}
		}else if(v == mBtnApply){
			try{

				if (AppUtils.isOnline(SelectMeetingTimeActivity.this)) {

					getSelectedListData = new ArrayList<MeetingDayModel>();

					if(getListData != null){
						for(int i =0; i< getListData.size(); i++){
							if(getListData.get(i).isSelected()){
								getSelectedListData.add(getListData.get(i));
							}
						}
						if(getSelectedListData != null){
							//sharedPreference.setMeetingTimes(SelectMeetingTimeActivity.this, getSelectedListData);
							Bundle bundle = new Bundle();
							bundle.putSerializable(IntentConstants.SELECTED_MEETING_TIMES, getSelectedListData);
							Intent returnIntent = new Intent();
							returnIntent.putExtras(bundle);
							setResult(RESULT_OK,returnIntent);
							finish();
						}
						//Send the selected list back
						/*if(getSelectedListData.size() ==0){
							ErrorMsgDialog.showErrorAlert(SelectMeetingTimeActivity.this, "", getString(R.string.wrng_str_no_record_select));
						}else{
							if(getSelectedListData != null)
							{
								//sharedPreference.setMeetingTimes(SelectMeetingTimeActivity.this, getSelectedListData);
								Bundle bundle = new Bundle();
								bundle.putSerializable(IntentConstants.SELECTED_MEETING_TIMES, getSelectedListData);
								Intent returnIntent = new Intent();
								returnIntent.putExtras(bundle);
								setResult(RESULT_OK,returnIntent);
								finish();
							}
						}*/
					}
				}else{

					ErrorMsgDialog.showErrorAlert(SelectMeetingTimeActivity.this, "", getString(R.string.wrng_str_no_record));
				}


			}catch (Exception ex){
				ex.printStackTrace();
			}



		}/*else if(v == headerView){
			if(isAllSelected){
				isAllSelected = false;
				for(int i =0; i< getListData.size(); i++){
					getListData.get(i).setSelected(false);
				}
				mMemberListAdapter.notifyDataSetChanged();
			}else{
				isAllSelected = true;
				for(int i =0; i< getListData.size(); i++){
					getListData.get(i).setSelected(true);
				}
				mMemberListAdapter.notifyDataSetChanged();
			}
		}*/
	}



	/**
	 * Calculating data
	 */

	private void getDataForList() {
		final String [] countryListArr= getResources().getStringArray(R.array.string_meeting_time);
		final String [] ExactValue= getResources().getStringArray(R.array.string_meeting_time_exact);
		ArrayList<String> myList = new ArrayList<String>(Arrays.asList(countryListArr));

		for(int i=0;i<myList.size();i++){
			MeetingDayModel mdm=new MeetingDayModel();
			mdm.setMeetingDay(myList.get(i));
			mdm.setMeetingValue(myList.get(i));
			mdm.setMeetingTimeExactValue(ExactValue[i]);
			getListData.add(mdm);
		}
		checkIntentDataExistOrNot();
		setListAdapter();

	}

	class UnCheckedAll implements SelectDayClickCallBack {

		@Override
		public void itemClickAction(ArrayList<MeetingDayModel> mList,CheckBox chbox,int position) {
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
		mMemberListAdapter = new SelectMeetingTimeAdapter(this, getListData, mTxtvNoReult, mListView, mChckBoxSelectAllMember,new UnCheckedAll());

		// Binds the Adapter to the ListView
		mListView.setAdapter(mMemberListAdapter);
	}

	private void checkIntentDataExistOrNot(){
		try{
			if(getSelectedListData != null && getSelectedListData.size() > 0){
				for(int counter = 0; counter < getListData.size(); counter++) {
					for (int j=0; j < getSelectedListData.size(); j++){
						if(getSelectedListData.get(j).getMeetingValue().equalsIgnoreCase(getListData.get(counter).getMeetingValue())) {
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
