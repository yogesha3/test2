package com.app.foxhopr.fragments.user.Team;


import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.adapter.CurrentTeamListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.fragments.user.TeamFragment;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.CurrentTeamRequestModel;
import com.app.foxhopr.request.models.ReceivedReferralDeleteRequestModel;
import com.app.foxhopr.swipemenulist.SwipeMenu;
import com.app.foxhopr.swipemenulist.SwipeMenuListView;
import com.app.foxhopr.ui.activities.SendReferralsSelectMembersActivity;
import com.app.foxhopr.ui.messages.MessageComposeActivity;
import com.app.foxhopr.ui.team.TeamDetailsActivity;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.CurrentTeamListClickCallBack;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.app.foxhopr.webservice.models.CurrentTeamInnerModel;
import com.app.foxhopr.webservice.models.CurrentTeamMembersResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.ArrayList;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * A simple {@link Fragment} subclass.
 */
public class CurrentTeamFragment extends Fragment  implements View.OnClickListener{
    private static String TAG = "CurrentTeamFragment";
    private CurrentTeamListAdapter mCurrentTeamListAdapter;
    private Context mContext;
    private HorizontalScrollView mLlTopTabs;
    private LinearLayout mLlBottomTabs;
    private Button mBtnDelete;
    private Button mBtnReadUnRead;
    private LinearLayout mLLBottomSelectTab;
    private LinearLayout mLLBottomMoreTab;
    private TabSelectedCallBack mTabSelectedCallBack;

    private SwipeMenuListView mListViewTeam;
    private ProgressHUD mProgressHUD;
    //Footer View
    private View footerViewInProgress;
    private ProgressBar mFooterProgressBar;
    private ArrayList<CurrentTeamInnerModel> listDataArray;
    private boolean isAllSelected = false;

    //Page data
    private CurrentTeamMembersResponseModel getResponse;
    //Paging
    private String pageNo = "";
    private int recordPerPage = ApplicationConstants.RECORD_PER_PAGE;
    private String senderNameStr = "";
    private String statusStr = "";
    private String referralNameStr = "";

    private int pageNumber = 1;

    //for search order
    private SharedPreference mPreferences;
    //private RadioGroup mSearchRadioButton;

    private EditText mSearchEditText;
    private ArrayList<CurrentTeamInnerModel> mDeletedId = new ArrayList<CurrentTeamInnerModel>();

    //variable storing state of search or simple by default referal
    private boolean mStatus = false;
    private TextView mNoRecordFound;
    private boolean mWebserviceStatus = true;
    public CurrentTeamFragment() {
        // Required empty public constructor
    }
    public static CurrentTeamFragment newInstance(HorizontalScrollView mLlTopTabs, LinearLayout mLlBottomTabs, Button mBtnDelete,Button mBtnReadUnRead,
                                                  LinearLayout lLBottomSelectTab, LinearLayout lLBottomMoreTab, TabSelectedCallBack mTabSelectedCallBack) {
        CurrentTeamFragment mCurrentTeamFragment = new CurrentTeamFragment();
        mCurrentTeamFragment.mLlTopTabs = mLlTopTabs;
        mCurrentTeamFragment.mLlBottomTabs = mLlBottomTabs;
        mCurrentTeamFragment.mBtnDelete = mBtnDelete;
        mCurrentTeamFragment.mBtnReadUnRead = mBtnReadUnRead;
        mCurrentTeamFragment.mLLBottomSelectTab = lLBottomSelectTab;
        mCurrentTeamFragment.mLLBottomMoreTab = lLBottomMoreTab;
        mCurrentTeamFragment.mTabSelectedCallBack = mTabSelectedCallBack;

        return mCurrentTeamFragment;
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        mContext = getActivity();
        View view = inflater.inflate(R.layout.fragment_current_team, container, false);
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
        mListViewTeam.setOnItemClickListener(OnitemClick);

        mNoRecordFound = (TextView) view.findViewById(R.id.norecordfound);

        mListViewTeam.setOnScrollListener(onScrollListener);
        //mListViewTeam.setOnMenuItemClickListener(onMenuItemClickListener);

        // add the footer before adding the adapter, else the footer will not load!
        footerViewInProgress = ((LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE)).inflate(R.layout.layout_list_footer_view, null, false);
        mFooterProgressBar = (ProgressBar) footerViewInProgress.findViewById(R.id.progressBarFooterList);
        mFooterProgressBar.setVisibility(View.GONE);
        mListViewTeam.addFooterView(footerViewInProgress);

        mLLBottomMoreTab.setAlpha(1f);
        mLLBottomSelectTab.setAlpha(1f);
        mBtnDelete.setOnClickListener(this);
        mBtnReadUnRead.setOnClickListener(this);
        mLLBottomSelectTab.setOnClickListener(this);
        mLLBottomMoreTab.setOnClickListener(this);

    }

    AdapterView.OnItemClickListener OnitemClick= new AdapterView.OnItemClickListener() {
        @Override
        public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
            if (AppUtils.isOnline(getActivity())) {
                Intent detailsReferrels = new Intent(getActivity(), TeamDetailsActivity.class);
                detailsReferrels.putExtra("member_id", listDataArray.get(position).getMember_id());
                detailsReferrels.putExtra("details_page", WebServiceConstants.CURRENT_TEAM_LIST_PAGE);
                startActivity(detailsReferrels);
            }
            else{
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }
        }
    };
    SwipeMenuListView.OnMenuItemClickListener onMenuItemClickListener = new SwipeMenuListView.OnMenuItemClickListener() {
        @Override
        public boolean onMenuItemClick(int position, SwipeMenu menu, int index) {

            switch (index) {
                case 0: {
                    ErrorMsgDialog.alertOkCancelButtonCallBack(getActivity(), "", getResources().getString(R.string.alert_str_delete_contacts), new OkPressedResponse(position));
                }
            }

            return false;
        }
    };

    /**
     * CallBack class for delete
     */
    class OkPressedResponse implements AlertCallBack {
        private int position;

        public OkPressedResponse(int position) {
            this.position = position;
        }

        @Override
        public void alertAction(boolean select) {
            try {
                mDeletedId.clear();
                mDeletedId.add(listDataArray.get(position));
                if (AppUtils.isOnline(getActivity())) {
                    mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                    mProgressHUD.setCancelable(false);
                    callDeleteWebservice(mDeletedId);
                } else {
                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                }

            } catch (Exception e) {
                e.printStackTrace();
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
            int count = mListViewTeam.getCount() - 1;

			/*if (scrollState == OnScrollListener.SCROLL_STATE_TOUCH_SCROLL) {
                mLlBottomTabs.setVisibility(View.GONE);
				mLlBottomTabs.startAnimation(animHide);
			}else{
				mLlBottomTabs.setVisibility(View.VISIBLE);
				mLlBottomTabs.startAnimation( animShow );
			}*/
            if (getResponse.getTotalContacts() != null && !getResponse.getTotalContacts().equalsIgnoreCase("") && count < Integer.parseInt(getResponse.getTotalContacts())) {

                if (scrollState == SCROLL_STATE_IDLE && mWebserviceStatus) {

                    if (mListViewTeam.getLastVisiblePosition() >= count - threshold) {
                        pageNumber = pageNumber + 1;
                        try {
                            /*
							 *hecking which data is displaying (by default or search)
							 *for false by default is calling
							 *for true search is calling
							 */
                            if (mStatus) {
                                if (AppUtils.isOnline(getActivity())) {
                                    mFooterProgressBar.setVisibility(View.VISIBLE);
                                    mWebserviceStatus = false;
                                    mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                                    mProgressHUD.setCancelable(false);
                                    callSearchWebservice(mSearchEditText.getText().toString());

                                } else {
                                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                                }

                            } else {
                                if (AppUtils.isOnline(getActivity())) {
                                    mFooterProgressBar.setVisibility(View.VISIBLE);
                                    mWebserviceStatus = false;
                                    mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                                    mProgressHUD.setCancelable(false);
                                    callTeamWebservice();
                                } else {
                                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
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

    class OnCheckBoxSeletedResponse implements CurrentTeamListClickCallBack {

        @Override
        public void itemClickAction(ArrayList<CurrentTeamInnerModel> mList) {
            try {
                Log.i("List size", "" + mList.size());

                if (mList.size() > 0) {
                    mBtnDelete.setVisibility(View.VISIBLE);
                    mBtnReadUnRead.setVisibility(View.VISIBLE);
                } else {
                    mBtnDelete.setVisibility(View.GONE);
                    mBtnReadUnRead.setVisibility(View.GONE);
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
                    mDeletedId.clear();
                    mCurrentTeamListAdapter.notifyDataSetChanged();
                    mBtnDelete.setVisibility(View.GONE);
                    mBtnReadUnRead.setVisibility(View.GONE);
                    mTabSelectedCallBack.selectAction(false);
                }

            } else {
                if (listDataArray != null && listDataArray.size()>0) {
                    isAllSelected = true;
                    for (int i = 0; i < listDataArray.size(); i++) {
                        listDataArray.get(i).setSelected(true);
                        mDeletedId.add(listDataArray.get(i));
                    }
                    mCurrentTeamListAdapter.notifyDataSetChanged();
                    mBtnDelete.setVisibility(View.VISIBLE);
                    mBtnReadUnRead.setVisibility(View.VISIBLE);
                    mTabSelectedCallBack.selectAction(true);
                }

            }
        } else if (view == mLLBottomMoreTab) {
            mListViewTeam.setOnItemClickListener(null);
            LayoutInflater mInflater = (LayoutInflater) getActivity().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            final View innerView = mInflater.inflate(R.layout.layout_search_current_team, null, false);
            final FrameLayout searchFrameLayout = (FrameLayout) getActivity().findViewById(R.id.messagesSearchViewPager);
            searchFrameLayout.addView(innerView);
            getActivity().overridePendingTransition(0, 0);
            TeamFragment.mStatusSearch = true;
            //ListView searchListView=(ListView)innerView.findViewById(R.id.listSearchSortView);
            LinearLayout llCancelSearch = (LinearLayout) innerView.findViewById(R.id.llCancelSearch);
            LinearLayout llSelectSearch = (LinearLayout) innerView.findViewById(R.id.llSelectSearch);
            mSearchEditText = (EditText) innerView.findViewById(R.id.edit_search_text);
            final ImageView imageClearSearch = (ImageView) innerView.findViewById(R.id.imageClearSearch);
            //mSearchRadioButton = (RadioGroup) innerView.findViewById(R.id.radiogroupSort);
            final int lastSelected = mPreferences.getSearchOrder(getActivity());
            CheckBox defaultCheckBox = (CheckBox) innerView.findViewById(mPreferences.getSearchOrder(getActivity()));

            if (mPreferences.getSearchText(getActivity()).length() > 0) {
                imageClearSearch.setVisibility(View.VISIBLE);
            } else {
                imageClearSearch.setVisibility(View.GONE);
            }
            mSearchEditText.setText(mPreferences.getSearchText(getActivity()));
            defaultCheckBox.setChecked(true);


            mSearchEditText.addTextChangedListener(new TextWatcher() {

                public void beforeTextChanged(CharSequence s, int start, int count, int after) {
                }

                public void onTextChanged(CharSequence s, int start, int before, int count) {


                }

                @Override
                public void afterTextChanged(Editable s) {
                    if (mSearchEditText.getText().toString().length() > 0) {
                        imageClearSearch.setVisibility(View.VISIBLE);
                    } else {
                        imageClearSearch.setVisibility(View.GONE);
                    }
                }
            });

            //SearchListAdapter sAdapter=new SearchListAdapter(getActivity());
            //searchListView.setAdapter(sAdapter);


            llCancelSearch.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    mListViewTeam.setOnItemClickListener(OnitemClick);
                    mPreferences.setSearrchOrder(getActivity(), lastSelected);
                    TeamFragment.mStatusSearch = false;
                    searchFrameLayout.removeAllViews();
                }
            });

            llSelectSearch.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    mListViewTeam.setOnItemClickListener(OnitemClick);
                    TeamFragment.mStatusSearch = false;
                    mStatus = true;
                    listDataArray.clear();

                    pageNumber = 1;
                    String searchText = ((EditText) innerView.findViewById(R.id.edit_search_text)).getText().toString();
                    searchFrameLayout.removeAllViews();
                    mPreferences.setSearchText(getActivity(), mSearchEditText.getText().toString());
                    //mPreferences.setSearrchOrder(getActivity(), mSearchRadioButton.getCheckedRadioButtonId());

                    try {
                        setListAdapter();
                        if (AppUtils.isOnline(getActivity())) {
                            mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                            mProgressHUD.setCancelable(false);
                            callSearchWebservice(mSearchEditText.getText().toString());

                        } else {
                            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
            });

            imageClearSearch.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    mPreferences.setSearchText(getActivity(), mSearchEditText.getText().toString());
                    ((EditText) innerView.findViewById(R.id.edit_search_text)).setText("");
                    //((RadioGroup)innerView.findViewById(R.id.radiogroupSort)).clearCheck();
                }
            });
        } else if (view == mBtnDelete) {
            new OkPressedCompose().alertAction(true);
        }
        else if (view == mBtnReadUnRead) {
            new OkPressedSentReferral().alertAction(true);
        }
    }



    /**
     * CallBack class for compose messages
     */
    class OkPressedCompose implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            mDeletedId.clear();
            for (CurrentTeamInnerModel checkedRow: listDataArray){
                if(checkedRow.isSelected()){
                    mDeletedId.add(checkedRow);
                }
            }
           // MessageComposeActivity
            if (AppUtils.isOnline(getActivity())) {
                Intent detailsReferrels = new Intent(getActivity(), MessageComposeActivity.class);
                detailsReferrels.putExtra("member_ids", mDeletedId);
                startActivity(detailsReferrels);
            }
            else{
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }
        }
    }

    /**
     * CallBack class for sent referral messages
     */
    class OkPressedSentReferral implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            mDeletedId.clear();
            for (CurrentTeamInnerModel checkedRow: listDataArray){
                if(checkedRow.isSelected()){
                    mDeletedId.add(checkedRow);
                }
            }
            if (AppUtils.isOnline(getActivity())) {
                Intent detailsReferrels = new Intent(getActivity(), SendReferralsSelectMembersActivity.class);
                detailsReferrels.putExtra("member_ids", mDeletedId);
                startActivity(detailsReferrels);
            }
            else{
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }

        }
    }


    /**
     * THis method is use to call the webservice and get inbox messages
     * The response and error from server
     */
    private void callTeamWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_TEAM_ACTION_NAME, WebServiceConstants.GET_TEAM_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getTeamListRequest(getInboxRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mWebserviceStatus = true;

                mProgressHUD.dismiss();
                mFooterProgressBar.setVisibility(View.GONE);
                mBtnDelete.setVisibility(View.GONE);
                mBtnReadUnRead.setVisibility(View.GONE);
                mTabSelectedCallBack.selectAction(false);
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseTeamListService(WebServiceUtils.getResponseString(responseModel));
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
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callSearchWebservice(String searchText) throws Exception {
        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_TEAM_ACTION_NAME, WebServiceConstants.GET_TEAM_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getTeamListRequest(getSearchRequestModel(searchText), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mWebserviceStatus = true;
                mProgressHUD.dismiss();
                mFooterProgressBar.setVisibility(View.GONE);
                mBtnDelete.setVisibility(View.GONE);
                mBtnReadUnRead.setVisibility(View.GONE);
                mTabSelectedCallBack.selectAction(false);
                try {
                    if (responseModel != null) {
                        parseTeamListService(WebServiceUtils.getResponseString(responseModel));
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
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callDeleteWebservice(final ArrayList<CurrentTeamInnerModel> mDeletedIds) throws Exception {
        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.DELETE_INBOX_ACTION_NAME, WebServiceConstants.MESSAGE_COMPOSE_CONTROL, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.deleteReferralsList(getDeleteRequestModel(mDeletedId), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                mFooterProgressBar.setVisibility(View.GONE);
                mBtnDelete.setVisibility(View.GONE);
                mBtnReadUnRead.setVisibility(View.GONE);
                mTabSelectedCallBack.selectAction(false);
                try {
                    if (responseModel != null) {
                        ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.str_sccess_delete_inbox_message), new DeleteOkPressed());
                        //deleteReferralsListService(mDeletedIds, WebServiceUtils.getResponseString(responseModel));
                        mCurrentTeamListAdapter.cleanSelectedArray();
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
                mBtnDelete.setVisibility(View.GONE);
                mBtnReadUnRead.setVisibility(View.GONE);
                mFooterProgressBar.setVisibility(View.GONE);
                mProgressHUD.dismiss();

                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    public class DeleteOkPressed implements AlertCallBack{

        @Override
        public void alertAction(boolean select) {
            listDataArray.clear();
            pageNumber=1;
            try {
                if (AppUtils.isOnline(getActivity())) {
                    mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                    mProgressHUD.setCancelable(false);
                    if(mStatus) {
                        callSearchWebservice(mPreferences.getSearchText(getActivity()));
                    }else{
                        callTeamWebservice();
                    }
                } else {
                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                }
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    /**
     * This method is use for get the request model
     *
     * @return
     */
    private ReceivedReferralDeleteRequestModel getDeleteRequestModel(ArrayList<CurrentTeamInnerModel> mDeletedIds) throws Exception {
        ReceivedReferralDeleteRequestModel mReceivedReferralsDeleteRequestModel = new ReceivedReferralDeleteRequestModel();
        mReceivedReferralsDeleteRequestModel.setListPage(WebServiceConstants.INBOX_LIST_PAGE);
        String ids="";
        for(CurrentTeamInnerModel s:mDeletedIds){
            if(ids.length()==0){
                ids=s.getId();
            }else{
                ids=ids + "," +s.getId();
            }

        }
        Log.i("ids", ids);
        mReceivedReferralsDeleteRequestModel.setDeleteId(ids);
        return mReceivedReferralsDeleteRequestModel;
    }



    /**
     * This method is use for get the request model
     *
     * @return
     */
    private CurrentTeamRequestModel getSearchRequestModel(String searchText) throws Exception {
        CurrentTeamRequestModel mCurrentTeamRequestModel = new CurrentTeamRequestModel();
        mCurrentTeamRequestModel.setPage_no("" + pageNumber);
        mCurrentTeamRequestModel.setRecord_per_page("" + recordPerPage);
        mCurrentTeamRequestModel.setListPage(WebServiceConstants.CURRENT_TEAM_LIST_PAGE);
        mCurrentTeamRequestModel.setSearch_filter(searchText);
        Log.i(TAG, AppUtils.getRadioData(getActivity()));
        Log.i(TAG, AppUtils.getRadioDataASCOrDESC(getActivity()));
        mCurrentTeamRequestModel.setSort_data(AppUtils.getRadioData(getActivity()));
        mCurrentTeamRequestModel.setSort_direction(AppUtils.getRadioDataASCOrDESC(getActivity()));

        return mCurrentTeamRequestModel;
    }

    /**
     * This method is use for get the request model
     *
     * @return
     */
    private CurrentTeamRequestModel getInboxRequestModel() throws Exception {
        CurrentTeamRequestModel mCurrentTeamRequestModel = new CurrentTeamRequestModel();
        mCurrentTeamRequestModel.setPage_no("" + pageNumber);
        mCurrentTeamRequestModel.setRecord_per_page("" + recordPerPage);
        mCurrentTeamRequestModel.setListPage(WebServiceConstants.CURRENT_TEAM_LIST_PAGE);
        mCurrentTeamRequestModel.setSearch_filter("");
        mCurrentTeamRequestModel.setSort_data("");
        mCurrentTeamRequestModel.setSort_direction("");

        return mCurrentTeamRequestModel;
    }

    private void parseTeamListService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        CurrentTeamMembersResponseModel get_Response = gson.fromJson(responseStr, CurrentTeamMembersResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getListData != null && get_Response.getListData.size() > 0) {
                    mListViewTeam.setVisibility(View.VISIBLE);
                    mNoRecordFound.setVisibility(View.GONE);
                    listDataArray.addAll(get_Response.getListData);
                    if (mCurrentTeamListAdapter != null) {
                        mCurrentTeamListAdapter.setData(listDataArray);
                        mCurrentTeamListAdapter.notifyDataSetChanged();
                    } else {
                        setListAdapter();
                    }
                }
            } else {
                //ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
                listDataArray.clear();
                mListViewTeam.setVisibility(View.GONE);
                mNoRecordFound.setVisibility(View.VISIBLE);
                if (mStatus/*mPreferences.getSearchText(getActivity()).length()>0 && mPreferences.getSearchOrder(getActivity())==R.id.dateNewest*/) {
                    mNoRecordFound.setText(getActivity().getResources().getString(R.string.wrng_str_no_result));
                } else {
                    mNoRecordFound.setText(getActivity().getResources().getString(R.string.wrng_str_no_record));
                }
            }
        } else {
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
        }
    }


    /**
     * Method use for set the data on List
     *
     * @throws Exception
     */

    private void setListAdapter() throws Exception {
        mCurrentTeamListAdapter = new CurrentTeamListAdapter(getActivity(), listDataArray, new OnCheckBoxSeletedResponse());
        // set our adapter and pass our recived list content
        mListViewTeam.setAdapter(mCurrentTeamListAdapter);

        /*SwipeMenuCreator creator = new SwipeMenuCreator() {

            @Override
            public void create(SwipeMenu menu) {
                //create an action that will be showed on swiping an item in the list
                SwipeMenuItem item1 = new SwipeMenuItem(getActivity());
                //Add the list view header
                View viewHeader = LayoutInflater.from(getActivity()).inflate(R.layout.layout_swip_view, null);

                item1.setBackground(new ColorDrawable(Color.rgb(0xE4, 0x56,
                        0x2B)));
                // set width of an option (px)
                item1.setWidth(200);
                item1.setTitle(getActivity().getResources().getString(R.string.str_delete));
                item1.setTitleSize(14);
                item1.setIcon(R.drawable.ic_delete);
                item1.setTitleColor(Color.WHITE);
                menu.addMenuItem(item1);
            }
        };
        //set MenuCreator
        mListViewTeam.setMenuCreator(creator);

        mListViewTeam.setOnSwipeListener(new SwipeMenuListView.OnSwipeListener() {

            @Override
            public void onSwipeStart(int position) {
            }

            @Override
            public void onSwipeEnd(int position) {
            }
        });*/
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
            if (AppUtils.isOnline(getActivity())) {
                //mFooterProgressBar.setVisibility(View.VISIBLE);
                mWebserviceStatus=false;
                mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                mProgressHUD.setCancelable(false);
                try {
                    callSearchWebservice(mSearchEditText.getText().toString());
                }catch (Exception e){
                    e.printStackTrace();
                }
            } else {
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }

        } else {
            if (AppUtils.isOnline(getActivity())) {
                // mFooterProgressBar.setVisibility(View.VISIBLE);
                mWebserviceStatus=false;
                mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                mProgressHUD.setCancelable(false);
                try {
                    callTeamWebservice();
                }catch (Exception e){
                    e.printStackTrace();
                }
            } else {
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }

        }
    }


}
