package com.app.foxhopr.fragments.user.Settings;


import android.content.Context;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.HorizontalScrollView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.adapter.PushNotificationListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.NotificationRequestModel;
import com.app.foxhopr.swipemenulist.SwipeMenuListView;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.CurrentTeamListClickCallBack;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.app.foxhopr.webservice.models.CurrentTeamInnerModel;
import com.app.foxhopr.webservice.models.PushNotificationResponseModel;
import com.app.foxhopr.webservice.models.ReferralsResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.ArrayList;
import java.util.Arrays;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * A simple {@link Fragment} subclass.
 */
public class NotificationsFragment extends Fragment implements View.OnClickListener{
    private static String TAG = "NotificationsFragment";
    private PushNotificationListAdapter mPushNotificationListAdapter;
    private Context mContext;
    private HorizontalScrollView mLlTopTabs;
    private LinearLayout mLlBottomTabs;
    private Button mBtnDelete;
    private Button mBtnReadUnRead;
    private LinearLayout mLLBottomSelectTab;
    private LinearLayout mLLBottomMoreTab;
    private TabSelectedCallBack mTabSelectedCallBack;

    private SwipeMenuListView mListViewTeam;
    private LinearLayout mLlSave;
    private ProgressHUD mProgressHUD;
    //Footer View
    private View footerViewInProgress;
    private ProgressBar mFooterProgressBar;
    private ArrayList<CurrentTeamInnerModel> listDataArray;
    private boolean isAllSelected = false;

    //Page data
    private PushNotificationResponseModel getResponse;
    //Paging
    private int recordPerPage = ApplicationConstants.RECORD_PER_PAGE;
    private int pageNumber = 1;

    //for search order
    private SharedPreference mPreferences;
    //private RadioGroup mSearchRadioButton;

    private EditText mSearchEditText;

    //variable storing state of search or simple by default referal
    private boolean mStatus = false;
    private TextView mNoRecordFound;
    private boolean mWebserviceStatus = true;

    public NotificationsFragment() {
        // Required empty public constructor
    }

    public static NotificationsFragment newInstance(HorizontalScrollView mLlTopTabs, LinearLayout mLlBottomTabs, Button mBtnDelete,Button mBtnReadUnRead,
                                                  LinearLayout lLBottomSelectTab, LinearLayout lLBottomMoreTab, TabSelectedCallBack mTabSelectedCallBack) {
        NotificationsFragment mPreviousTeamFragment = new NotificationsFragment();
        mPreviousTeamFragment.mLlTopTabs = mLlTopTabs;
        mPreviousTeamFragment.mLlBottomTabs = mLlBottomTabs;
        mPreviousTeamFragment.mBtnDelete = mBtnDelete;
        mPreviousTeamFragment.mBtnReadUnRead = mBtnReadUnRead;
        mPreviousTeamFragment.mLLBottomSelectTab = lLBottomSelectTab;
        mPreviousTeamFragment.mLLBottomMoreTab = lLBottomMoreTab;
        mPreviousTeamFragment.mTabSelectedCallBack = mTabSelectedCallBack;

        return mPreviousTeamFragment;
    }
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        mContext = getActivity();
        View view = inflater.inflate(R.layout.fragment_notification_layout, container, false);
        initView(view);
        return view;
    }

    private void initView(View view) {
        mPreferences = new SharedPreference();
        /**
         * setting search clear
         */
        mPreferences.setSearchText(getActivity(), "");
        mPreferences.setSearrchOrder(getActivity(), R.id.dateNewest);
        listDataArray = new ArrayList<CurrentTeamInnerModel>();
        mListViewTeam = (SwipeMenuListView) view.findViewById(R.id.listViewTeam);
        mLlSave = (LinearLayout) view.findViewById(R.id.llSave);
        //mListViewTeam.setOnItemClickListener(OnitemClick);

        mNoRecordFound = (TextView) view.findViewById(R.id.norecordfound);

        // add the footer before adding the adapter, else the footer will not load!
        footerViewInProgress = ((LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE)).inflate(R.layout.layout_list_footer_view, null, false);
        mFooterProgressBar = (ProgressBar) footerViewInProgress.findViewById(R.id.progressBarFooterList);
        mFooterProgressBar.setVisibility(View.GONE);
        mListViewTeam.addFooterView(footerViewInProgress);

        mBtnDelete.setOnClickListener(this);
        mBtnReadUnRead.setOnClickListener(this);
        mLLBottomSelectTab.setOnClickListener(this);
        mLLBottomMoreTab.setOnClickListener(this);
        mLlSave.setOnClickListener(this);

        if (AppUtils.isOnline(getActivity())) {
            // mFooterProgressBar.setVisibility(View.VISIBLE);
            mWebserviceStatus=false;
            mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
            mProgressHUD.setCancelable(false);
            try {
                callWebservice();
            }catch (Exception e){
                e.printStackTrace();
            }
        } else {
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
        }

        getDataForList();
    }

    class OnCheckBoxSeletedResponse implements CurrentTeamListClickCallBack {

        @Override
        public void itemClickAction(ArrayList<CurrentTeamInnerModel> mList) {
            try {
                Log.i("List size", "" + mList.size());
                if(null!=mList && mList.size()==listDataArray.size()){
                    mTabSelectedCallBack.selectAction(true);
                    isAllSelected = true;
                }else{
                    mTabSelectedCallBack.selectAction(false);
                    isAllSelected = false;
                }

            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    @Override
    public void onClick(View view) {
        if (view == mLLBottomSelectTab) {
            if (isAllSelected) {
                isAllSelected = false;
                if (listDataArray != null) {
                    for (int i = 0; i < listDataArray.size(); i++) {
                        listDataArray.get(i).setSelected(false);
                    }
                    mPushNotificationListAdapter.notifyDataSetChanged();
                    mTabSelectedCallBack.selectAction(false);
                }

            } else {
                if (listDataArray != null && listDataArray.size()>0) {
                    isAllSelected = true;
                    for (int i = 0; i < listDataArray.size(); i++) {
                        listDataArray.get(i).setSelected(true);
                    }
                    mPushNotificationListAdapter.notifyDataSetChanged();
                    mTabSelectedCallBack.selectAction(true);
                }

            }
        }else if(view==mLlSave){
            if (AppUtils.isOnline(getActivity())) {
                // mFooterProgressBar.setVisibility(View.VISIBLE);
                mWebserviceStatus=false;
                mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                mProgressHUD.setCancelable(false);
                try {
                    callEditNotiWebservice();
                }catch (Exception e){
                    e.printStackTrace();
                }
            } else {
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }

        }
    }

    private void getDataForList() {
        listDataArray.clear();
        final String [] Title= getResources().getStringArray(R.array.string_notification_title);
        final String [] Description= getResources().getStringArray(R.array.string_notification_description);
        ArrayList<String> myList = new ArrayList<String>(Arrays.asList(Title));
        ArrayList<String> myExist = new ArrayList<String>();

        for(int i=0;i<myList.size();i++){
            CurrentTeamInnerModel mdm=new CurrentTeamInnerModel();
            mdm.setFname(myList.get(i));
            mdm.setLname(Description[i]);
            if(null!=getResponse)
            switch(i){
                case 0:
                    if(getResponse.getGetResult().getWeeklySummery().equals("1")){
                        myExist.add("1");
                        mdm.setSelected(true);
                        mdm.setIsChecked(true);
                    }else{
                        mdm.setSelected(false);
                        mdm.setIsChecked(false);
                    }
                    break;
                 case 1:
                     if(getResponse.getGetResult().getReceiveReferral().equals("1")){
                         myExist.add("1");
                         mdm.setSelected(true);
                         mdm.setIsChecked(true);
                     }else{
                         mdm.setSelected(false);
                         mdm.setIsChecked(false);
                     }
                    break;
                 case 2:
                     if(getResponse.getGetResult().getCommentMadeOnReferral().equals("1")){
                         myExist.add("1");
                         mdm.setSelected(true);
                         mdm.setIsChecked(true);
                     }else{
                         mdm.setSelected(false);
                         mdm.setIsChecked(false);
                     }
                     break;
                 case 3:
                     if(getResponse.getGetResult().getReceiveMessage().equals("1")){
                         myExist.add("1");
                         mdm.setSelected(true);
                         mdm.setIsChecked(true);
                     }else{
                         mdm.setSelected(false);
                         mdm.setIsChecked(false);
                     }
                     break;
                 case 4:
                     if(getResponse.getGetResult().getCommentMadeOnMessage().equals("1")){
                         myExist.add("1");
                         mdm.setSelected(true);
                         mdm.setIsChecked(true);
                     }else{
                         mdm.setSelected(false);
                         mdm.setIsChecked(false);
                     }
                    break;
                 case 5:
                     if(getResponse.getGetResult().getReceiveEventInvitation().equals("1")){
                         myExist.add("1");
                         mdm.setSelected(true);
                         mdm.setIsChecked(true);
                     }else{
                         mdm.setSelected(false);
                         mdm.setIsChecked(false);
                     }
                    break;
                 case 6:
                     if(getResponse.getGetResult().getCommentMadeOnEvent().equals("1")){
                         myExist.add("1");
                         mdm.setSelected(true);
                         mdm.setIsChecked(true);
                     }else{
                         mdm.setSelected(false);
                         mdm.setIsChecked(false);
                     }
                    break;

            }
            listDataArray.add(mdm);
        }

        try{
            setListAdapter();
        }catch (Exception e){

        }
        if(null!=myExist && myExist.size()==listDataArray.size()){
            mTabSelectedCallBack.selectAction(true);
            isAllSelected = true;
        }else{
            mTabSelectedCallBack.selectAction(false);
            isAllSelected = false;
        }


    }



    /**
     * Method use for set the data on List
     * @throws Exception
     */

    private void setListAdapter() throws Exception {
        mPushNotificationListAdapter = new PushNotificationListAdapter(getActivity(), listDataArray, new OnCheckBoxSeletedResponse());
        // set our adapter and pass our recived list content
        mListViewTeam.setAdapter(mPushNotificationListAdapter);


    }

    /**
     * THis method is use to call the webservice and get notification details
     * The response and error from server
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_NOTIFICATION_ACTION_NAME, WebServiceConstants.GET_NOTIFICATION_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getNotification(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mWebserviceStatus = true;

                mProgressHUD.dismiss();
                mFooterProgressBar.setVisibility(View.GONE);
                mBtnDelete.setVisibility(View.GONE);
                mTabSelectedCallBack.selectAction(false);
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseNotificaitonListService(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
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


                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
            }
        });
    }
    /**
     * This method is use for get the request model for notification
     *
     * @return
     */
    private NotificationRequestModel getRequestModel() throws Exception {
        NotificationRequestModel mNotificationRequestModel = new NotificationRequestModel();
        mNotificationRequestModel.setNotifPage(WebServiceConstants.GET_NOTIFICATION_LIST_PAGE);
        return mNotificationRequestModel;
    }

    /**
     * parseNotificaitonListService
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseNotificaitonListService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        PushNotificationResponseModel get_Response = gson.fromJson(responseStr, PushNotificationResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getGetResult() != null) {
                    mListViewTeam.setVisibility(View.VISIBLE);
                    mNoRecordFound.setVisibility(View.GONE);
                    getDataForList();
                }
            } else {
                ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
        }
    }

    //edit notifications
    /**
     * THis method is use to call the webservice and get notification details
     * The response and error from server
     */
    private void callEditNotiWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_NOTIFICATION_ACTION_NAME, WebServiceConstants.GET_NOTIFICATION_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getNotification(getEditRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mWebserviceStatus = true;

                mProgressHUD.dismiss();
                mFooterProgressBar.setVisibility(View.GONE);
                mBtnDelete.setVisibility(View.GONE);
                mTabSelectedCallBack.selectAction(false);
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        Gson gson = new Gson();
                        ReferralsResponseModel get_Response = gson.fromJson(WebServiceUtils.getResponseString(responseModel), ReferralsResponseModel.class);
                        if (null != get_Response && get_Response.getReponseCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                            ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage());
                        }else{
                            ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage());
                        }

                    } else {
                        ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
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


                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
            }
        });
    }
    /**
     * This method is use for get the request model for notification
     *
     * @return
     */
    private NotificationRequestModel getEditRequestModel() throws Exception {
        NotificationRequestModel mNotificationRequestModel = new NotificationRequestModel();
        mNotificationRequestModel.setNotifPage(WebServiceConstants.GET_NOTIFICATION_EDIT_LIST_PAGE);
        ArrayList<String> name=new ArrayList<String>();
        /*for (CurrentTeamInnerModel checkedRow: listDataArray){
            if(checkedRow.isSelected()){
                //mDeletedId.add(checkedRow);
                name.add(checkedRow.get)
            }
        }*/
        //getResponse
        for(int i=0;i<listDataArray.size();i++){
            if(listDataArray.get(i).isSelected()){
                //mDeletedId.add(checkedRow);
                switch(i){
                    case 0:
                        name.add("weeklySummery");
                        break;
                    case 1:
                        name.add("receiveReferral");
                        break;
                    case 2:
                        name.add("commentMadeOnReferral");
                        break;
                    case 3:
                        name.add("receiveMessage");
                        break;
                    case 4:
                        name.add("commentMadeOnMessage");
                        break;
                    case 5:
                        name.add("receiveEventInvitation");
                        break;
                    case 6:
                        name.add("commentMadeOnEvent");
                        break;

                }
            }
        }
        //String [] newArray={"weeklySummery","receiveReferral","commentMadeOnReferral"};
        String [] newArray=name.toArray(new String[name.size()]);
        mNotificationRequestModel.setNotifArr(newArray);
        return mNotificationRequestModel;
    }

    /**
     * parseNotificaitonListService
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseEditNotificationListService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        PushNotificationResponseModel get_Response = gson.fromJson(responseStr, PushNotificationResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getGetResult() != null) {
                    mListViewTeam.setVisibility(View.VISIBLE);
                    mNoRecordFound.setVisibility(View.GONE);
                    getDataForList();
                }
            } else {
                ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
        }
    }


}
