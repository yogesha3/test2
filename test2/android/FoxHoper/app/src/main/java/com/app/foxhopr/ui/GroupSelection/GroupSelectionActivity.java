package com.app.foxhopr.ui.GroupSelection;

import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.ServiceConnection;
import android.os.Bundle;
import android.os.IBinder;
import android.support.v4.app.FragmentActivity;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.AbsListView;
import android.widget.AdapterView;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.adapter.GroupSelectionAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.GroupSelectionRequestModel;
import com.app.foxhopr.request.models.SelectedGroupRequestModel;
import com.app.foxhopr.services.NotificationService;
import com.app.foxhopr.swipemenulist.SwipeMenuListView;
import com.app.foxhopr.ui.activities.DashboardActivity;
import com.app.foxhopr.ui.activities.LoginActivity;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.webservice.models.GroupListInnerModel;
import com.app.foxhopr.webservice.models.GroupResponseModel;
import com.app.foxhopr.webservice.models.UserProfileResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import org.json.JSONObject;

import java.util.ArrayList;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class GroupSelectionActivity extends FragmentActivity implements View.OnClickListener{
    private static String TAG="GroupSelectionActivity";
    private Context mContext;
    private LinearLayout mLLBottomMoreTab;
    private GroupSelectionAdapter mGroupSelectionAdapter;
    private RelativeLayout RlBack;
    private TextView mTextViewHeaderTitle;
    private ImageView mRefreshButton;


    //Footer View
    private View footerViewInProgress;
    private ProgressBar mFooterProgressBar;

    private SwipeMenuListView mListViewGroup;
    private ArrayList<GroupListInnerModel> listDataArray;


    private ProgressHUD mProgressHUD;

    //Page data
    private GroupResponseModel getResponse;

    //Paging
    private String pageNo = "";
    private int recordPerPage = ApplicationConstants.RECORD_PER_PAGE;
    private String senderNameStr = "";
    private String statusStr = "";
    private String referralNameStr = "";

    private int pageNumber = 1;

    //for search order
    private SharedPreference mPreferences;
    //variable storing state of search or simple by default referal
    private boolean mStatus = false;
    private TextView mNoRecordFound;
    private boolean mWebserviceStatus = true;
    private String mMemberStatus;

    //service variable

    NotificationService mBoundService;
    boolean mServiceBound = false;
    Intent intentService;
    private BroadcastReceiver mNotificationBroadcast;
    private int mSelectedPosition;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_group_selection);
        initView();
        initBroadCast();
    }

    private void initView(){
        mMemberStatus=getIntent().getStringExtra(ApplicationConstants.MEMBER_STATUS);
        mPreferences = new SharedPreference();
        RlBack=(RelativeLayout)findViewById(R.id.rlBack);
        ImageView iv=(ImageView) RlBack.findViewById(R.id.imgvDrawer);

        mTextViewHeaderTitle= (TextView)findViewById(R.id.textViewHeaderTitle);
        mRefreshButton= (ImageView)findViewById(R.id.refressicon);

        mTextViewHeaderTitle.setVisibility(View.VISIBLE);


        if(null!=mMemberStatus ){
            iv.setBackgroundResource(R.drawable.ic_back);
            if(mMemberStatus.equals(getString(R.string.str_billing_downgrade_text))){
                mTextViewHeaderTitle.setText(getString(R.string.str_group_downgrade_selection));
            }else if(mMemberStatus.equals(getString(R.string.str_billing_upgrade_text))){
                mTextViewHeaderTitle.setText(getString(R.string.str_group_upgrade_selection));
            }
        }else {
            iv.setBackgroundResource(R.drawable.icon_logout);
            mTextViewHeaderTitle.setText(getString(R.string.str_group_selection));
        }
        //RlBack.setVisibility(View.GONE);



        mListViewGroup = (SwipeMenuListView) findViewById(R.id.listViewGroupSelection);
        listDataArray = new ArrayList<GroupListInnerModel>();
        mListViewGroup.setOnItemClickListener(OnitemClick);
        mListViewGroup.setOnScrollListener(onScrollListener);
        mNoRecordFound=(TextView)findViewById(R.id.norecordfound);
        mLLBottomMoreTab=(LinearLayout)findViewById(R.id.llBottomSearchTab);

        footerViewInProgress = ((LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE)).inflate(R.layout.layout_list_footer_view, null, false);
        mFooterProgressBar = (ProgressBar) footerViewInProgress.findViewById(R.id.progressBarFooterList);
        mFooterProgressBar.setVisibility(View.GONE);
        mListViewGroup.addFooterView(footerViewInProgress);


        mLLBottomMoreTab.setOnClickListener(this);
        RlBack.setOnClickListener(this);
        mRefreshButton.setOnClickListener(this);
    }


    /**
     * Onmenuitem click listener
     */

    AdapterView.OnItemClickListener OnitemClick= new AdapterView.OnItemClickListener() {
        @Override
        public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
            mSelectedPosition=position;
            ErrorMsgDialog.alertOkCancelButtonCallBack(GroupSelectionActivity.this, "",getResources().getString(R.string.alert_str_group_confirmation), new OnItemClick());
        }
    };

    /**
     * CallBack class for logout
     */
    class OnItemClick implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            // Call web service here
             if (AppUtils.isOnline(GroupSelectionActivity.this)) {
                // mFooterProgressBar.setVisibility(View.VISIBLE);
                mWebserviceStatus=false;
                mProgressHUD = ProgressHUD.show(GroupSelectionActivity.this, "", true, true);
                mProgressHUD.setCancelable(false);
                try {
                    callWebserviceClick(listDataArray.get(mSelectedPosition).getId());
                }catch (Exception e){
                    e.printStackTrace();
                }
            } else {
                ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
            }
        }
    }



    /**
     * On scroll listener use to load more data at end of list.
     */
    AbsListView.OnScrollListener onScrollListener = new AbsListView.OnScrollListener() {

        @Override
        public void onScrollStateChanged(AbsListView view, int scrollState) {
            int threshold = 1;
            int count = mListViewGroup.getCount() - 1;

            if (getResponse.getTotalGroups() != null && !getResponse.getTotalGroups().equalsIgnoreCase("") && count < Integer.parseInt(getResponse.getTotalGroups())) {

                if (scrollState == SCROLL_STATE_IDLE && mWebserviceStatus) {

                    if (mListViewGroup.getLastVisiblePosition() >= count - threshold) {
                        pageNumber = pageNumber + 1;
                        try {
							/*
							 *hecking which data is displaying (by default or search)
							 *for false by default is calling
							 *for true search is calling
							 */
                            if (mStatus) {
                                if (AppUtils.isOnline(GroupSelectionActivity.this)) {
                                    mFooterProgressBar.setVisibility(View.VISIBLE);
                                    mWebserviceStatus=false;
                                    mProgressHUD = ProgressHUD.show(GroupSelectionActivity.this, "", true, true);
                                    mProgressHUD.setCancelable(false);
                                    callSearchWebservice();

                                } else {
                                    ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                                }

                            } else {
                                if (AppUtils.isOnline(GroupSelectionActivity.this)) {
                                    mFooterProgressBar.setVisibility(View.VISIBLE);
                                    mWebserviceStatus=false;
                                    mProgressHUD = ProgressHUD.show(GroupSelectionActivity.this, "", true, true);
                                    mProgressHUD.setCancelable(false);
                                    callWebservice();
                                } else {
                                    ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                                }

                            }
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }
            }

        }

        @Override
        public void onScroll(AbsListView view, int firstVisibleItem, int visibleItemCount, int totalItemCount) {

        }
    };


    @Override
    public void onClick(View view) {
        if (view == mLLBottomMoreTab) {
            mListViewGroup.setOnItemClickListener(null);
            LayoutInflater mInflater = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            final View innerView = mInflater.inflate(R.layout.layout_search_group_option, null, false);
            final FrameLayout searchFrameLayout = (FrameLayout)findViewById(R.id.referralsSearchViewPager);
            searchFrameLayout.addView(innerView);
            overridePendingTransition(0, 0);
            LinearLayout llLocal = (LinearLayout) innerView.findViewById(R.id.llLocal);
            LinearLayout llGlobal = (LinearLayout) innerView.findViewById(R.id.llGlobal);
            LinearLayout llCancel = (LinearLayout) innerView.findViewById(R.id.llCancel);
            if(null!=mMemberStatus && mMemberStatus.equals(getString(R.string.str_billing_downgrade_text))){
                llGlobal.setEnabled(false);
                llGlobal.setAlpha(0.5f);
            }else if(null!=mMemberStatus && mMemberStatus.equals(getString(R.string.str_billing_upgrade_text))){
                llLocal.setEnabled(false);
                llLocal.setAlpha(0.5f);
            }
            llCancel.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    mListViewGroup.setOnItemClickListener(OnitemClick);
                    searchFrameLayout.removeAllViews();
                }
            });
            llLocal.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    //mStatus=true;
                    mListViewGroup.setOnItemClickListener(OnitemClick);
                    //mPreferences.setSelectedGroupType(GroupSelectionActivity.this, ApplicationConstants.GROUP_LOCAL);
                    searchFrameLayout.removeAllViews();
                    Intent intObj= new Intent(GroupSelectionActivity.this, GroupSortActivity.class);
                    intObj.putExtra("GROUPSELECTED",ApplicationConstants.GROUP_LOCAL);
                    startActivityForResult(intObj, 1);
                    overridePendingTransition(0, 0);
                }
            });
            llGlobal.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    mListViewGroup.setOnItemClickListener(OnitemClick);
                    //mStatus=true;
                    //mPreferences.setSelectedGroupType(GroupSelectionActivity.this, ApplicationConstants.GROUP_GLOBAL);
                    searchFrameLayout.removeAllViews();
                    Intent intObj= new Intent(GroupSelectionActivity.this, GroupSortActivity.class);
                    intObj.putExtra("GROUPSELECTED",ApplicationConstants.GROUP_GLOBAL);
                    startActivityForResult(intObj, 1);
                    overridePendingTransition(0, 0);

                }
            });

        }else if (view == RlBack) {
            if(null!=mMemberStatus ){
                finish();
            }else {
                ErrorMsgDialog.alertOkCancelButtonCallBack(GroupSelectionActivity.this, "", getResources().getString(R.string.wrng_str_logout_app), new LogOutClick());
            }

        }else if (view == mRefreshButton) {
            onResume();
        }

    }

    /**
     * CallBack class for logout
     */
    class LogOutClick implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            // Call web service here
            try{
                if (AppUtils.isOnline(GroupSelectionActivity.this)) {
                    mProgressHUD = ProgressHUD.show(GroupSelectionActivity.this,"", true,true);
                    mProgressHUD.setCancelable(false);
                    callWebserviceLogout();
                }
                else{
                    ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                }
            }catch (Exception ex){
                ex.printStackTrace();
            }
        }
    }

    /**
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callWebserviceClick(String groupid) throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.SELECT_GROUP_CONTROL_NAME, WebServiceConstants.GET_GROUP_CONTROL_NAME, GroupSelectionActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getSelectedGroup(getItemRequestModel(groupid), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mWebserviceStatus = true;
                mProgressHUD.dismiss();
                mFooterProgressBar.setVisibility(View.GONE);
                try {//{"code":"200","result":{"group_id":"89"},"message":"Your Group has beed updated successfully"}
                    //{"code":"200","message":"You have already downgraded your group","result":{"group_id":"89"}}
                    if (responseModel != null) {
                        Log.i(TAG, WebServiceUtils.getResponseString(responseModel));
                        JSONObject json=new JSONObject( WebServiceUtils.getResponseString(responseModel));
                        if(json.getString("message").equals(getString(R.string.str_alert_thanks))) {
                            String group_id=json.getJSONObject("result").getString("group_id");
                            mPreferences.setGroupId(GroupSelectionActivity.this, group_id);
                            ErrorMsgDialog.alertOkButtonCallBack(GroupSelectionActivity.this, "", getResources().getString(R.string.alert_str_thanks_message), new OkPressedResponse());
                        }else if(json.getString("message").equals(getString(R.string.str_alert_already_joined))){
                            String group_id=json.getJSONObject("result").getString("group_id");
                            mPreferences.setGroupId(GroupSelectionActivity.this,group_id);
                            ErrorMsgDialog.alertOkButtonCallBack(GroupSelectionActivity.this, "", json.getString("message"), new OkPressedResponse());
                        }else if(json.getString("message").equals(getString(R.string.str_alert_group_has_updated))){
                            String group_id=json.getJSONObject("result").getString("group_id");
                            mPreferences.setGroupId(GroupSelectionActivity.this, group_id);
                            ErrorMsgDialog.alertOkButtonCallBack(GroupSelectionActivity.this, "", json.getString("message"), new OkPressedResponse());
                        }else if(json.getString("message").equals(getString(R.string.str_alert_already_downgraded))){
                            String group_id=json.getJSONObject("result").getString("group_id");
                            mPreferences.setGroupId(GroupSelectionActivity.this, group_id);
                            ErrorMsgDialog.alertOkButtonCallBack(GroupSelectionActivity.this, "", json.getString("message"), new OkPressedResponse());
                        }else if(json.getString("message").equals(getString(R.string.str_alert_already_upgraded))){
                            String group_id=json.getJSONObject("result").getString("group_id");
                            mPreferences.setGroupId(GroupSelectionActivity.this, group_id);
                            ErrorMsgDialog.alertOkButtonCallBack(GroupSelectionActivity.this, "", json.getString("message"), new OkPressedResponse());
                        }else if(json.getString("message").equals(getString(R.string.str_alert_group_no_longer_available))){
                            ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_group_fulled));
                            onResume();
                        }else{
                            onResume();
                        }
                    } else {
                        ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_server_error));
                        onResume();
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();

                mFooterProgressBar.setVisibility(View.GONE);
                mProgressHUD.dismiss();
                mWebserviceStatus = true;
                ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * CallBack class for delete
     */
    class OkPressedResponse implements AlertCallBack {
        private  int position;

        public OkPressedResponse(){
            this.position=position;
        }

        @Override
        public void alertAction(boolean select) {
            try {
                moveToDashboardScreen();
            }catch (Exception e){
                e.printStackTrace();
            }

        }
    }

    /**
     * Method use to navigate the Dashboard screen
     */
    private void moveToDashboardScreen() {
        Intent intObj = new Intent(GroupSelectionActivity.this, DashboardActivity.class);
        intObj.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intObj);
        overridePendingTransition(R.anim.slide_in_left, R.anim.slide_out_right);
        finish();
    }


    /**
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_GROUP_ACTION_NAME, WebServiceConstants.GET_GROUP_CONTROL_NAME, GroupSelectionActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getGroupSelectionListRequest(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mWebserviceStatus = true;
                mProgressHUD.dismiss();
                mFooterProgressBar.setVisibility(View.GONE);
                try {
                    if (responseModel != null) {
                        Log.i(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseGroupsListService(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();

                mFooterProgressBar.setVisibility(View.GONE);
                mProgressHUD.dismiss();
                mWebserviceStatus = true;


                ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callSearchWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_GROUP_ACTION_NAME, WebServiceConstants.GET_GROUP_CONTROL_NAME, GroupSelectionActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getGroupSelectionListRequest(getRequestModelSearch(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mWebserviceStatus = true;
                mProgressHUD.dismiss();
                mFooterProgressBar.setVisibility(View.GONE);
                try {
                    if (responseModel != null) {
                        Log.i(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseGroupsListService(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();

                mFooterProgressBar.setVisibility(View.GONE);
                mProgressHUD.dismiss();
                mWebserviceStatus = true;


                ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * This method is use for get the request model
     *
     * @return
     */
    private SelectedGroupRequestModel getItemRequestModel(String gropid) throws Exception {
        SelectedGroupRequestModel mSelectedGroupRequestModel = new SelectedGroupRequestModel();
        if(null!=mMemberStatus ){
            if(mMemberStatus.equals(getString(R.string.str_billing_downgrade_text))){
                //mSelectedGroupRequestModel.setListPage(ApplicationConstants.GROUP_LOCAL);
                mSelectedGroupRequestModel.setListPage(ApplicationConstants.GROUP_GLOBAL);
            }else if(mMemberStatus.equals(getString(R.string.str_billing_upgrade_text))){
                //mSelectedGroupRequestModel.setListPage(ApplicationConstants.GROUP_GLOBAL);
                mSelectedGroupRequestModel.setListPage(ApplicationConstants.GROUP_LOCAL);
            }
        }else {
            mSelectedGroupRequestModel.setListPage(WebServiceConstants.GROUP_LIST_PAGE);
        }
        mSelectedGroupRequestModel.setGroupId(gropid);
        return mSelectedGroupRequestModel;
    }

    /**
     * This method is use for get the request model
     *
     * @return
     */
    private GroupSelectionRequestModel getRequestModel() throws Exception {
        GroupSelectionRequestModel mGroupSelectionRequestModel = new GroupSelectionRequestModel();
        if(null!=mMemberStatus ){
            if(mMemberStatus.equals(getString(R.string.str_billing_downgrade_text))){
                mGroupSelectionRequestModel.setGroup(ApplicationConstants.GROUP_LOCAL);
            }else if(mMemberStatus.equals(getString(R.string.str_billing_upgrade_text))){
                mGroupSelectionRequestModel.setGroup(ApplicationConstants.GROUP_GLOBAL);
            }
        }else {
            mGroupSelectionRequestModel.setGroup(mPreferences.getSelectedGroupType(GroupSelectionActivity.this));
        }
        mGroupSelectionRequestModel.setRecord_per_page("" + recordPerPage);
        mGroupSelectionRequestModel.setPage_no("" + pageNumber);
        return mGroupSelectionRequestModel;
    }

    /**
     * This method is use for get the request model
     *
     * @return
     */
    private GroupSelectionRequestModel getRequestModelSearch() throws Exception {
        GroupSelectionRequestModel mGroupSelectionRequestModel = new GroupSelectionRequestModel();
        mGroupSelectionRequestModel.setGroup(mPreferences.getSelectedGroupType(GroupSelectionActivity.this));
        mGroupSelectionRequestModel.setRecord_per_page("" + recordPerPage);
        mGroupSelectionRequestModel.setPage_no("" + pageNumber);
        if(mPreferences.getSelectedGroupType(GroupSelectionActivity.this).equals(ApplicationConstants.GROUP_LOCAL)){
            String[] meetingDays=AppUtils.getMeeting(mPreferences.getMeetingDays(GroupSelectionActivity.this));
            String[] meetingTimes=AppUtils.getMeetingTime(mPreferences.getMeetingTimes(GroupSelectionActivity.this));
            mGroupSelectionRequestModel.setDay(meetingDays);
            mGroupSelectionRequestModel.setTime(meetingTimes);

            Log.e(TAG, mPreferences.getMiles(GroupSelectionActivity.this));
            Log.e(TAG, mPreferences.getSortByLocal(GroupSelectionActivity.this));
            mGroupSelectionRequestModel.setMilesfilter(mPreferences.getMiles(GroupSelectionActivity.this));
            if(mPreferences.getSortByLocal(GroupSelectionActivity.this).equals(getString(R.string.str_most_member))){
                mGroupSelectionRequestModel.setSorting(getString(R.string.str_total_value));
            }else if(mPreferences.getSortByLocal(GroupSelectionActivity.this).equals(getString(R.string.str_newest))) {
                mGroupSelectionRequestModel.setSorting(getString(R.string.str_date_asc_value));
            }
            mGroupSelectionRequestModel.setSearchbylocation("");

        } else {
            String[] meetingDays = AppUtils.getMeeting(mPreferences.getMeetingDaysGlobal(GroupSelectionActivity.this));
            String[] meetingTimes = AppUtils.getMeetingTime(mPreferences.getMeetingTimesGlobal(GroupSelectionActivity.this));
            mGroupSelectionRequestModel.setDay(meetingDays);
            mGroupSelectionRequestModel.setTime(meetingTimes);
            //Log.e(TAG, meetingDays.toString());
            //Log.e(TAG, meetingTimes.toString());
            Log.e(TAG, mPreferences.getLocationGlobal(GroupSelectionActivity.this));
            Log.e(TAG, mPreferences.getSortByGlobal(GroupSelectionActivity.this));

            mGroupSelectionRequestModel.setSearchbylocation(mPreferences.getLocationGlobal(GroupSelectionActivity.this));

            //mGroupSelectionRequestModel.setSorting(mPreferences.getSortByGlobal(GroupSelectionActivity.this));
            if(mPreferences.getSortByGlobal(GroupSelectionActivity.this).equals(getString(R.string.str_most_member))){
                mGroupSelectionRequestModel.setSorting(getString(R.string.str_total_value));
            }else if(mPreferences.getSortByGlobal(GroupSelectionActivity.this).equals(getString(R.string.str_newest))) {
                mGroupSelectionRequestModel.setSorting(getString(R.string.str_date_asc_value));
            }

            mGroupSelectionRequestModel.setMilesfilter("");
        }
        return mGroupSelectionRequestModel;
    }


    /**
     * parseReferralsListService
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseGroupsListService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        GroupResponseModel get_Response = gson.fromJson(responseStr, GroupResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getGroupList() != null && get_Response.getGroupList().size() > 0) {
                    mListViewGroup.setVisibility(View.VISIBLE);
                    mNoRecordFound.setVisibility(View.GONE);
                    listDataArray.addAll(get_Response.getGroupList());
                    if (mGroupSelectionAdapter != null) {
                        mGroupSelectionAdapter.setData(listDataArray);
                        mGroupSelectionAdapter.notifyDataSetChanged();
                    } else {
                        setListAdapter();
                    }
                }
            } else {
                //ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
                listDataArray.clear();
                mListViewGroup.setVisibility(View.GONE);
                mNoRecordFound.setVisibility(View.VISIBLE);
                if(mStatus/*mPreferences.getSearchText(getActivity()).length()>0 && mPreferences.getSearchOrder(getActivity())==R.id.dateNewest*/){
                    mNoRecordFound.setText(getResources().getString(R.string.wrng_str_no_result));
                }else{
                    mNoRecordFound.setText(getResources().getString(R.string.wrng_str_no_record));
                }
            }
        } else {
            ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * Method use for set the data on List
     *
     * @throws Exception
     */

    private void setListAdapter() throws Exception {
        mGroupSelectionAdapter = new GroupSelectionAdapter(GroupSelectionActivity.this, listDataArray);
        // set our adapter and pass our recived list content
        mListViewGroup.setAdapter(mGroupSelectionAdapter);
    }


    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if(requestCode ==1){//for meeting times
            if(resultCode == RESULT_OK){
                if(data != null){
                    Bundle bundle=data.getExtras();
                    String apply=bundle.getString("Apply");
                    if(apply.equals("Apply")){
                        mStatus=true;
                    }

                }
            }
        }
    }

    @Override
    public void onResume() {
        super.onResume();

        listDataArray.clear();
        try {
            setListAdapter();
        }catch (Exception e){
            e.printStackTrace();
        }
        pageNumber=1;
        if (mStatus) {
            if (AppUtils.isOnline(GroupSelectionActivity.this)) {
                //mFooterProgressBar.setVisibility(View.VISIBLE);
                mWebserviceStatus=false;
                mProgressHUD = ProgressHUD.show(GroupSelectionActivity.this, "", true, true);
                mProgressHUD.setCancelable(false);
                try {
                    callSearchWebservice();
                }catch (Exception e){
                    e.printStackTrace();
                }
            } else {
                ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
            }

        } else {
            if (AppUtils.isOnline(GroupSelectionActivity.this)) {
                // mFooterProgressBar.setVisibility(View.VISIBLE);
                mWebserviceStatus=false;
                mProgressHUD = ProgressHUD.show(GroupSelectionActivity.this, "", true, true);
                mProgressHUD.setCancelable(false);
                try {
                    callWebservice();
                }catch (Exception e){
                    e.printStackTrace();
                }
            } else {
                ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
            }

        }
    }

    /**
     * THis method is use to call the webservice for getting logout from application and get
     * after getting success changing the screen, i.e. redirect to login screen
     */
    private void callWebserviceLogout() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.LOGOUT_ACTION_NAME, WebServiceConstants.STATE_CONTROL_NAME, GroupSelectionActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getUserProfileRequest(new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseLogOut(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * parse response for logout web service
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseLogOut(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        UserProfileResponseModel get_Response = gson.fromJson(responseStr, UserProfileResponseModel.class);

        if (get_Response != null) {
            //getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                processAfterLogout();
            } else {
                //ErrorMsgDialog.showErrorAlert(DashboardActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(GroupSelectionActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     *doing processing after successful response of logout web service
     *@param
     */
    public void processAfterLogout(){

        try {
            //NotificationAlart.stop(DashboardActivity.this);
            AppUtils.clearUserData(GroupSelectionActivity.this);
            Intent intObj = new Intent(GroupSelectionActivity.this, LoginActivity.class);
            intObj.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intObj);
            overridePendingTransition(R.anim.slide_in_up, R.anim.slide_out_up);
            finish();

        } catch (Exception e) {
            e.printStackTrace();
        }

    }

    @Override
    protected void onStart() {
        super.onStart();
        ApplicationConstants.NOTIFICATION_STATUS=true;
        intentService = new Intent(this, NotificationService.class);
        startService(intentService);
        bindService(intentService, mServiceConnection, Context.BIND_AUTO_CREATE);
        registerReceiver(mNotificationBroadcast, new IntentFilter(
                ApplicationConstants.LAST_DATAUPDATE_ACTION));
    }

    @Override
    protected void onStop() {
        super.onStop();
        if (mServiceBound) {
            unbindService(mServiceConnection);
            mServiceBound = false;
            ApplicationConstants.NOTIFICATION_STATUS=false;
        }
    }

    private ServiceConnection mServiceConnection = new ServiceConnection() {

        @Override
        public void onServiceDisconnected(ComponentName name) {
            mServiceBound = false;
        }

        @Override
        public void onServiceConnected(ComponentName name, IBinder service) {
            NotificationService.MyBinder myBinder = (NotificationService.MyBinder) service;
            mBoundService = myBinder.getService();
            mServiceBound = true;
        }
    };
    /**
     * Initializing bradcast for getting notifications and
     * update it to dashboard screen
     */
    public void initBroadCast() {
        mNotificationBroadcast = new BroadcastReceiver() {

            @Override
            public void onReceive(Context context, Intent intent) {
                String versionValue = intent
                        .getStringExtra(ApplicationConstants.LAST_DATAUPDATE_KEY);
                if (versionValue.equals(ApplicationConstants.LAST_DATAUPDATE_VALUE)) {
                    setCountValues();
                }
            }
        };
    }

    /**
     *method to set values geeting from notification service
     *If its find user has been logged out from other device
     *automatically it will be logout
     */
    public void setCountValues(){
        if(mPreferences.getIsLogin(GroupSelectionActivity.this).equals(getResources().getString(R.string.is_login_text))) {
            processAfterLogout();
        }else{
            //mReferralsCount.setText("");
        }

    }
    @Override
    protected void onPause() {
        super.onPause();
        try {
            //NotificationAlart.stop(DashboardActivity.this);
            unregisterReceiver(mNotificationBroadcast);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

}
