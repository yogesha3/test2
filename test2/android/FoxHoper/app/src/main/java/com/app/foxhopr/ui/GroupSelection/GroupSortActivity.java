package com.app.foxhopr.ui.GroupSelection;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.view.View;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.IntentConstants;
import com.app.foxhopr.webservice.models.MeetingDayModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

public class GroupSortActivity extends FragmentActivity implements View.OnClickListener, RadioGroup.OnCheckedChangeListener {

    private TextView mBtnMiles;
    private TextView mBtnMeetngDay;
    private TextView mBtnMeetingTime;
    private FrameLayout mFLMiles;
    private FrameLayout mFLMeetingDay;
    private FrameLayout mFLMeetingTime;
    private RadioGroup mRadioGroupSort;
    private RadioButton mRadioButtonMostMember;
    private RadioButton mRadioButtonNewest;
    private EditText mEditTextCity;
    private EditText editTextZip;
    private EditText mEditTextLocation;
    private SharedPreference sharedPreference;

    private LinearLayout mApply;
    private LinearLayout mCancel;

    private LinearLayout mLocalSort;
    private LinearLayout mGlobalSort;
    private RelativeLayout RlBack;

    private TextView mTextViewHeaderTitle;

    private String strMiles ="";
    private String strCity ="";
    private String strZip ="";

    private String strMeetingDay ="";
    private String strMeetingTime ="";
    private String strSortBy ="";
    private String groupSelected;

    private ArrayList<MeetingDayModel> getSelectedMeetingDaysData=null;
    private ArrayList<MeetingDayModel> getSelectedMeetingTimeData=null;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_group_sort);
        initView();
    }

    private void initView(){
        sharedPreference=new SharedPreference();
        groupSelected=getIntent().getStringExtra("GROUPSELECTED");
        RlBack=(RelativeLayout)findViewById(R.id.rlBack);
        RlBack.setVisibility(View.GONE);

        mTextViewHeaderTitle= (TextView)findViewById(R.id.textViewHeaderTitle);
        mTextViewHeaderTitle.setVisibility(View.VISIBLE);
        mTextViewHeaderTitle.setText(getString(R.string.str_group_selection));

        mBtnMiles=(TextView)findViewById(R.id.buttonMiles);
        mBtnMeetngDay=(TextView)findViewById(R.id.buttonSelectMeetingDay);
        mBtnMeetingTime=(TextView)findViewById(R.id.buttonSelectMeetingTime);

        mFLMiles=(FrameLayout)findViewById(R.id.flMiles);
        mFLMeetingDay=(FrameLayout)findViewById(R.id.flMeetingDay);
        mFLMeetingTime=(FrameLayout)findViewById(R.id.flMeetingTime);

        mRadioGroupSort=(RadioGroup)findViewById(R.id.radiogroupSort);
        mRadioButtonMostMember=(RadioButton)findViewById(R.id.radioMostMember);
        mRadioButtonNewest=(RadioButton)findViewById(R.id.radioNewest);

        mEditTextCity=(EditText)findViewById(R.id.editTextCityGroupSelection);
        editTextZip=(EditText)findViewById(R.id.editTextZipGroupSelection);
        mEditTextLocation=(EditText)findViewById(R.id.editTextLocation);

        mApply=(LinearLayout)findViewById(R.id.llSelectSearch);
        mCancel=(LinearLayout)findViewById(R.id.llCancelSearch);

        mLocalSort=(LinearLayout)findViewById(R.id.LLlocalGroup);
        mGlobalSort=(LinearLayout)findViewById(R.id.LLglobalGroup);
        /**
         * setting selected values in local variable depends on
         * global and local
         */
        getDaysFromSaved();
        getTimeFromSaved();

        if(groupSelected.equals(ApplicationConstants.GROUP_GLOBAL)){
            mLocalSort.setVisibility(View.GONE);
            mGlobalSort.setVisibility(View.VISIBLE);
            mEditTextLocation.setText(sharedPreference.getLocationGlobal(GroupSortActivity.this));

            //String [] meetingDays= AppUtils.getMeeting(sharedPreference.getMeetingDaysGlobal(GroupSortActivity.this));


            /**
             * Setting selected meeting Days values
             */

            if(null!=getSelectedMeetingDaysData && getSelectedMeetingDaysData.size() ==1){
                mBtnMeetngDay.setText(getSelectedMeetingDaysData.get(0).getMeetingDay());
            }else if(null!=getSelectedMeetingDaysData && getSelectedMeetingDaysData.size() >1) {
                mBtnMeetngDay.setText(getResources().getString(R.string.str_meeting_days)+"("+getSelectedMeetingDaysData.size() +")");
            }

           // String [] meetingTimes=AppUtils.getMeetingTime(sharedPreference.getMeetingTimesGlobal(GroupSortActivity.this));
            /**
             * Setting selected meeting times values
             */
            if(null!=getSelectedMeetingTimeData && getSelectedMeetingTimeData.size() ==1){
                mBtnMeetingTime.setText(getSelectedMeetingTimeData.get(0).getMeetingDay());
            }else if(null!=getSelectedMeetingTimeData && getSelectedMeetingTimeData.size() >1) {
                mBtnMeetingTime.setText(getResources().getString(R.string.str_meeting_times)+"("+getSelectedMeetingTimeData.size() +")");
            }

            strSortBy=sharedPreference.getSortByGlobal(GroupSortActivity.this);
            if(strSortBy.equals(ApplicationConstants.MOST_MEMBER)){
                mRadioButtonMostMember.setChecked(true);
            }else if(strSortBy.equals(ApplicationConstants.NEWEST)){
                mRadioButtonNewest.setChecked(true);
            }

        }else if(groupSelected.equals(ApplicationConstants.GROUP_LOCAL)){
            mLocalSort.setVisibility(View.VISIBLE);
            mGlobalSort.setVisibility(View.GONE);
            mEditTextCity.setText(sharedPreference.getCity(GroupSortActivity.this));
            editTextZip.setText(sharedPreference.getZipCode(GroupSortActivity.this));
            strMiles=sharedPreference.getMiles(GroupSortActivity.this);
            mBtnMiles.setText(strMiles + " Miles");

            /**
             * Setting selected meeting Days values
             */

            if(null!=getSelectedMeetingDaysData && getSelectedMeetingDaysData.size() ==1){
                mBtnMeetngDay.setText(getSelectedMeetingDaysData.get(0).getMeetingDay());
            }else if(null!=getSelectedMeetingDaysData && getSelectedMeetingDaysData.size() >1) {
                mBtnMeetngDay.setText(getResources().getString(R.string.str_meeting_days)+"("+getSelectedMeetingDaysData.size() +")");
            }

            /**
             * Setting selected meeting times values
             */
            if(null!=getSelectedMeetingTimeData && getSelectedMeetingTimeData.size() ==1){
                mBtnMeetingTime.setText(getSelectedMeetingTimeData.get(0).getMeetingDay());
            }else if(null!=getSelectedMeetingTimeData && getSelectedMeetingTimeData.size() >1) {
                mBtnMeetingTime.setText(getResources().getString(R.string.str_meeting_times)+"("+getSelectedMeetingTimeData.size() +")");
            }

            strSortBy=sharedPreference.getSortByLocal(GroupSortActivity.this);
            if(strSortBy.equals(ApplicationConstants.MOST_MEMBER)){
                mRadioButtonMostMember.setChecked(true);
            }else if(strSortBy.equals(ApplicationConstants.NEWEST)){
                mRadioButtonNewest.setChecked(true);
            }
        }

        mFLMiles.setOnClickListener(this);
        mFLMeetingDay.setOnClickListener(this);
        mFLMeetingTime.setOnClickListener(this);
        mApply.setOnClickListener(this);
        mCancel.setOnClickListener(this);

        mRadioGroupSort.setOnCheckedChangeListener(this);

    }

    private void showMilesList() throws Exception {
        final String [] countryListArr= getResources().getStringArray(R.array.string_miles);
        //ArrayList<String> myList = new ArrayList<String>(Arrays.asList(countryListArr));
        if ((countryListArr.length) > 0) {

            if(countryListArr.length >0){
                final String[] items = getResources().getStringArray(R.array.string_miles);
                AlertDialog.Builder builder = new AlertDialog.Builder(GroupSortActivity.this);
                builder.setTitle(GroupSortActivity.this.getResources().getString(R.string.str_select_miles));
                builder.setItems(items, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int item) {
                        if (null!=countryListArr[item]) {
                            strMiles = "" + countryListArr[item];
                            mBtnMiles.setText(strMiles + " Miles");
                            mFLMiles.setBackgroundResource(R.drawable.bg_select_team);
                        }
                    }
                });

                AlertDialog alert = builder.create();
                alert.show();
            }
        }
    }

    @Override
    public void onClick(View view) {
        if(view==mFLMiles){
            try {
                showMilesList();
            }catch (Exception e){
                e.printStackTrace();
            }
        }else if(view==mFLMeetingDay){
            Intent intObj= new Intent(GroupSortActivity.this, SelectMeetingDayActivity.class);
            intObj.putExtra(IntentConstants.SELECTED_DAY_LIST, getSelectedMeetingDaysData);
            startActivityForResult(intObj,1);
            overridePendingTransition(0, 0);
        }else if(view==mFLMeetingTime){
            Intent intObj= new Intent(GroupSortActivity.this, SelectMeetingTimeActivity.class);
            intObj.putExtra(IntentConstants.SELECTED_TIME_LIST, getSelectedMeetingTimeData);
            startActivityForResult(intObj,2);
            overridePendingTransition(0, 0);
        }else if(view==mApply){
            if(groupSelected.equals(ApplicationConstants.GROUP_GLOBAL)){
                sharedPreference.setSelectedGroupType(GroupSortActivity.this, ApplicationConstants.GROUP_GLOBAL);
                sharedPreference.setMeetingDaysGlobal(GroupSortActivity.this, getSelectedMeetingDaysData);
                sharedPreference.setMeetingTimesGlobal(GroupSortActivity.this, getSelectedMeetingTimeData);
                sharedPreference.setLocationGlobal(GroupSortActivity.this, mEditTextLocation.getText().toString());
                sharedPreference.setSortyByGlobal(GroupSortActivity.this, strSortBy);
            }else  if(groupSelected.equals(ApplicationConstants.GROUP_LOCAL)){
                sharedPreference.setSelectedGroupType(GroupSortActivity.this, ApplicationConstants.GROUP_LOCAL);
                sharedPreference.setMeetingDays(GroupSortActivity.this,getSelectedMeetingDaysData);
                sharedPreference.setMeetingTimes(GroupSortActivity.this, getSelectedMeetingTimeData);
                sharedPreference.setMiles(GroupSortActivity.this, strMiles);
                sharedPreference.setSortyByLocal(GroupSortActivity.this, strSortBy);
            }
            Bundle bundle = new Bundle();
            bundle.putString("Apply","Apply");
            Intent returnIntent = new Intent();
            returnIntent.putExtras(bundle);
            setResult(RESULT_OK, returnIntent);
            finish();
        }else if(view==mCancel){
            /*if(groupSelected.equals("GLOBAL")){
                sharedPreference.setSelectedGroupType(GroupSortActivity.this,ApplicationConstants.GROUP_LOCAL);
            }else{
                sharedPreference.setSelectedGroupType(GroupSortActivity.this,ApplicationConstants.GROUP_GLOBAL);
            }*/
            finish();
        }

    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {

        if (requestCode == 1) {//for meeting days
            if(resultCode == RESULT_OK){
                String result=data.getStringExtra("result");
                if(data != null){
                    Bundle bundle=data.getExtras();
                    getSelectedMeetingDaysData = 	(ArrayList<MeetingDayModel>)bundle.getSerializable(IntentConstants.SELECTED_MEETING_DAYS);
                    if(getSelectedMeetingDaysData != null){
                        if(getSelectedMeetingDaysData.size() ==1){
                            mBtnMeetngDay.setText(getSelectedMeetingDaysData.get(0).getMeetingValue());
                        }else if(getSelectedMeetingDaysData.size()>1){
                            mBtnMeetngDay.setText(getResources().getString(R.string.str_meeting_days)+"("+getSelectedMeetingDaysData.size()+")");
                        }else{
                            mBtnMeetngDay.setText(getResources().getString(R.string.str_by_meeting_day));
                        }
                    }
                }
            }
        }else if(requestCode ==2){//for meeting times
              if(resultCode == RESULT_OK){
                String result=data.getStringExtra("result");
                if(data != null){
                    Bundle bundle=data.getExtras();
                    getSelectedMeetingTimeData = 	(ArrayList<MeetingDayModel>)bundle.getSerializable(IntentConstants.SELECTED_MEETING_TIMES);
                    if(getSelectedMeetingTimeData != null){
                        if(getSelectedMeetingTimeData.size() ==1){
                            mBtnMeetingTime.setText(getSelectedMeetingTimeData.get(0).getMeetingValue());
                        }else if(getSelectedMeetingTimeData.size()>1){
                            mBtnMeetingTime.setText(getResources().getString(R.string.str_meeting_times)+"("+getSelectedMeetingTimeData.size()+")");
                        }else{
                            mBtnMeetingTime.setText(getResources().getString(R.string.str_by_meeting_time));
                        }
                    }
                }
            }
        }
    }

    @Override
    public void onCheckedChanged(RadioGroup group, int checkedId) {
        RadioButton checkedRadioButton = (RadioButton)group.findViewById(checkedId);
        // This puts the value (true/false) into the variable
        boolean isChecked = checkedRadioButton.isChecked();
        // If the radiobutton that has changed in check state is now checked...
        if (isChecked){
            strSortBy=checkedRadioButton.getText().toString();
        }
    }

    private void getDaysFromSaved(){
        List<MeetingDayModel> get_Response;
        String savedMeetingDays;
        if(groupSelected.equals(ApplicationConstants.GROUP_LOCAL)){
            savedMeetingDays=sharedPreference.getMeetingDays(GroupSortActivity.this);
        }else{
            savedMeetingDays=sharedPreference.getMeetingDaysGlobal(GroupSortActivity.this);
        }
        if(null!=savedMeetingDays && !savedMeetingDays.equals("")) {
            Gson gson = new Gson();
            MeetingDayModel[] favoriteItems = gson.fromJson(savedMeetingDays, MeetingDayModel[].class);
            get_Response = Arrays.asList(favoriteItems);
            get_Response = new ArrayList(get_Response);

            if (null != get_Response && get_Response.size() > 0) {
                getSelectedMeetingDaysData = new ArrayList(get_Response);
            }
        }
    }

    private void getTimeFromSaved(){
        List<MeetingDayModel> get_Response;
        String savedMeetingDays;
        if(groupSelected.equals(ApplicationConstants.GROUP_LOCAL)){
            savedMeetingDays=sharedPreference.getMeetingTimes(GroupSortActivity.this);
        }else{
            savedMeetingDays=sharedPreference.getMeetingTimesGlobal(GroupSortActivity.this);
        }


        if(null!=savedMeetingDays && !savedMeetingDays.equals("")) {
            Gson gson = new Gson();
            MeetingDayModel[] favoriteItems = gson.fromJson(savedMeetingDays, MeetingDayModel[].class);
            get_Response = Arrays.asList(favoriteItems);
            get_Response = new ArrayList(get_Response);

            if (null != get_Response && get_Response.size() > 0) {
                getSelectedMeetingTimeData = new ArrayList(get_Response);
            }
        }
    }
}
