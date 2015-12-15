/**
 * MessageInboxArchiveFragment display list of Archive Inbox messages
 * user can filter,delete record(s) permanent
 * Implemented paging per page 20 records displaying
 */
package com.app.foxhopr.fragments.user.Messages;


import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
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
import com.app.foxhopr.adapter.MessageInboxArchiveListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.fragments.user.MessageFragment;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.MessageInboxRequestModel;
import com.app.foxhopr.request.models.MessageReadUnreadRequestModel;
import com.app.foxhopr.request.models.ReceivedReferralDeleteRequestModel;
import com.app.foxhopr.swipemenulist.SwipeMenu;
import com.app.foxhopr.swipemenulist.SwipeMenuCreator;
import com.app.foxhopr.swipemenulist.SwipeMenuItem;
import com.app.foxhopr.swipemenulist.SwipeMenuListView;
import com.app.foxhopr.ui.messages.MessageInboxDetailsActivity;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.MessageListClickCallBack;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.app.foxhopr.webservice.models.InboxListInnerModel;
import com.app.foxhopr.webservice.models.MessagesResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.ArrayList;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * Created by Chobey R. on 18/9/15.
 */

public class MessageInboxArchiveFragment extends Fragment implements View.OnClickListener {
    private static String TAG="MessageInboxArchiveFragment";

    private MessageInboxArchiveListAdapter mMessageInboxArchiveListAdapter;

    private Context mContext;
    private HorizontalScrollView mLlTopTabs;
    private LinearLayout mLlBottomTabs;
    private Button mBtnDelete;
    private Button mBtnReadUnRead;
    private LinearLayout mLLBottomSelectTab;
    private LinearLayout mLLBottomMoreTab;
    private TabSelectedCallBack mTabSelectedCallBack;

    private SwipeMenuListView mListViewMessageInboxArchive;
    private ProgressHUD mProgressHUD;

    //Footer View
    private View footerViewInProgress;
    private ProgressBar mFooterProgressBar;
    private ArrayList<InboxListInnerModel> listDataArray;
    private boolean isAllSelected = false;

    //Page data
    private MessagesResponseModel getResponse;
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
    private ArrayList<InboxListInnerModel> mDeletedId=new ArrayList<InboxListInnerModel>();
    private InboxListInnerModel mReadUnRead=new InboxListInnerModel();

    //variable storing state of search or simple by default referal
    private boolean mStatus = false;
    private TextView mNoRecordFound;
    private boolean mWebserviceStatus = true;

    public MessageInboxArchiveFragment() {
        // Required empty public constructor
    }

    public static MessageInboxArchiveFragment newInstance(HorizontalScrollView mLlTopTabs, LinearLayout mLlBottomTabs, Button mBtnDelete,Button mBtnReadUnRead,
                                                  LinearLayout lLBottomSelectTab, LinearLayout lLBottomMoreTab, TabSelectedCallBack mTabSelectedCallBack) {
        MessageInboxArchiveFragment mReferralsReceivedFragment = new MessageInboxArchiveFragment();
        mReferralsReceivedFragment.mLlTopTabs = mLlTopTabs;
        mReferralsReceivedFragment.mLlBottomTabs = mLlBottomTabs;
        mReferralsReceivedFragment.mBtnDelete = mBtnDelete;
        mReferralsReceivedFragment.mBtnReadUnRead = mBtnReadUnRead;
        mReferralsReceivedFragment.mLLBottomSelectTab = lLBottomSelectTab;
        mReferralsReceivedFragment.mLLBottomMoreTab = lLBottomMoreTab;
        mReferralsReceivedFragment.mTabSelectedCallBack = mTabSelectedCallBack;

        return mReferralsReceivedFragment;
    }
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        mContext = getActivity();
        View view = inflater.inflate(R.layout.fragment_message_inbox_archive, container, false);
        initView(view);
        return view;
    }

    private void initView(View view){
        mPreferences = new SharedPreference();
        /**
         * setting search clear
         */
        mPreferences.setSearchText(getActivity(), "");
        mPreferences.setSearrchOrder(getActivity(), R.id.dateNewest);
        listDataArray = new ArrayList<InboxListInnerModel>();
        mListViewMessageInboxArchive = (SwipeMenuListView) view.findViewById(R.id.listViewMessageInboxArchive);
        mListViewMessageInboxArchive.setOnItemClickListener(OnitemClick);

        mNoRecordFound=(TextView)view.findViewById(R.id.norecordfound);

        mListViewMessageInboxArchive.setOnScrollListener(onScrollListener);
        mListViewMessageInboxArchive.setOnMenuItemClickListener(onMenuItemClickListener);

        // add the footer before adding the adapter, else the footer will not load!
        footerViewInProgress = ((LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE)).inflate(R.layout.layout_list_footer_view, null, false);
        mFooterProgressBar = (ProgressBar) footerViewInProgress.findViewById(R.id.progressBarFooterList);
        mFooterProgressBar.setVisibility(View.GONE);
        mListViewMessageInboxArchive.addFooterView(footerViewInProgress);

        mBtnDelete.setOnClickListener(this);
        mBtnReadUnRead.setOnClickListener(this);
        mLLBottomSelectTab.setOnClickListener(this);
        mLLBottomMoreTab.setOnClickListener(this);

        //Call the webservices
        /*try {
            if (AppUtils.isOnline(getActivity())) {
                mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                mProgressHUD.setCancelable(false);
                callInboxArchiveWebservice();
            } else {
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }*/
    }

    AdapterView.OnItemClickListener OnitemClick= new AdapterView.OnItemClickListener() {
        @Override
        public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
            if (AppUtils.isOnline(getActivity())) {
                Intent detailsReferrels = new Intent(getActivity(), MessageInboxDetailsActivity.class);
                detailsReferrels.putExtra("message_id", listDataArray.get(position).getId());
                detailsReferrels.putExtra("details_page", WebServiceConstants.INBOX_ARCHIVE_LIST_PAGE);
                startActivity(detailsReferrels);
            }
            else{
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }
        }
    };
    SwipeMenuListView.OnMenuItemClickListener onMenuItemClickListener=new SwipeMenuListView.OnMenuItemClickListener() {
        @Override
        public boolean onMenuItemClick(int position, SwipeMenu menu, int index) {

            switch (index){
                case 0:{
                    ErrorMsgDialog.alertOkCancelButtonCallBack(getActivity(), "", getResources().getString(R.string.alert_str_archive_delete_messages), new OkPressedResponse(position));
                }
            }

            return false;
        }
    };

    /**
     * CallBack class for delete
     */
    class OkPressedResponse implements AlertCallBack {
        private  int position;

        public OkPressedResponse(int position){
            this.position=position;
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

            }catch (Exception e){
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
            int count = mListViewMessageInboxArchive.getCount() - 1;

			/*if (scrollState == OnScrollListener.SCROLL_STATE_TOUCH_SCROLL) {
                mLlBottomTabs.setVisibility(View.GONE);
				mLlBottomTabs.startAnimation(animHide);
			}else{
				mLlBottomTabs.setVisibility(View.VISIBLE);
				mLlBottomTabs.startAnimation( animShow );
			}*/
            if (getResponse.getTotalReferrals() != null && !getResponse.getTotalReferrals().equalsIgnoreCase("") && count < Integer.parseInt(getResponse.getTotalReferrals())) {

                if (scrollState == SCROLL_STATE_IDLE && mWebserviceStatus) {

                    if (mListViewMessageInboxArchive.getLastVisiblePosition() >= count - threshold) {
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
                                    mWebserviceStatus=false;
                                    mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                                    mProgressHUD.setCancelable(false);
                                    callSearchWebservice(mSearchEditText.getText().toString());

                                } else {
                                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                                }

                            } else {
                                if (AppUtils.isOnline(getActivity())) {
                                    mFooterProgressBar.setVisibility(View.VISIBLE);
                                    mWebserviceStatus=false;
                                    mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                                    mProgressHUD.setCancelable(false);
                                    callInboxArchiveWebservice();
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

    class OnCheckBoxSeletedResponse implements MessageListClickCallBack {

        @Override
        public void itemClickAction(ArrayList<InboxListInnerModel> mList) {
            try {
                Log.i("List size", "" + mList.size());

                if (mList.size() > 0) {
                    mBtnDelete.setVisibility(View.VISIBLE);
                    mBtnReadUnRead.setVisibility(View.VISIBLE);
                    mReadUnRead=mList.get(0);
                    if(mReadUnRead.is_read()){
                        mBtnReadUnRead.setBackgroundResource(R.drawable.icon_unread);
                    }else{
                        mBtnReadUnRead.setBackgroundResource(R.drawable.icon_read);
                    }
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
                    mMessageInboxArchiveListAdapter.notifyDataSetChanged();
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
                    mMessageInboxArchiveListAdapter.notifyDataSetChanged();
                    mBtnDelete.setVisibility(View.VISIBLE);
                    mBtnReadUnRead.setVisibility(View.VISIBLE);
                    mReadUnRead=listDataArray.get(0);
                    if(mReadUnRead.is_read()){
                        mBtnReadUnRead.setBackgroundResource(R.drawable.icon_unread);
                    }else{
                        mBtnReadUnRead.setBackgroundResource(R.drawable.icon_read);
                    }
                    mTabSelectedCallBack.selectAction(true);
                }

            }
        }else if (view == mLLBottomMoreTab) {
            mListViewMessageInboxArchive.setOnItemClickListener(null);
            LayoutInflater mInflater = (LayoutInflater) getActivity().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            final View innerView = mInflater.inflate(R.layout.layout_search_message_inbox, null, false);
            final FrameLayout searchFrameLayout = (FrameLayout) getActivity().findViewById(R.id.messagesSearchViewPager);
            searchFrameLayout.addView(innerView);
            getActivity().overridePendingTransition(0, 0);
            MessageFragment.mStatusSearch=true;
            //ListView searchListView=(ListView)innerView.findViewById(R.id.listSearchSortView);
            LinearLayout llCancelSearch = (LinearLayout) innerView.findViewById(R.id.llCancelSearch);
            LinearLayout llSelectSearch = (LinearLayout) innerView.findViewById(R.id.llSelectSearch);
            mSearchEditText = (EditText) innerView.findViewById(R.id.edit_search_text);
            final ImageView imageClearSearch = (ImageView) innerView.findViewById(R.id.imageClearSearch);
            //mSearchRadioButton = (RadioGroup) innerView.findViewById(R.id.radiogroupSort);
            final int lastSelected=mPreferences.getSearchOrder(getActivity());
            CheckBox defaultCheckBox=(CheckBox)innerView.findViewById(mPreferences.getSearchOrder(getActivity()));

            if(mPreferences.getSearchText(getActivity()).length()>0){
                imageClearSearch.setVisibility(View.VISIBLE);
            }else{
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
                    if(mSearchEditText.getText().toString().length()>0){
                        imageClearSearch.setVisibility(View.VISIBLE);
                    }else{
                        imageClearSearch.setVisibility(View.GONE);
                    }
                }
            });

            //SearchListAdapter sAdapter=new SearchListAdapter(getActivity());
            //searchListView.setAdapter(sAdapter);



            llCancelSearch.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    mListViewMessageInboxArchive.setOnItemClickListener(OnitemClick);
                    mPreferences.setSearrchOrder(getActivity(), lastSelected);
                    MessageFragment.mStatusSearch=false;
                    searchFrameLayout.removeAllViews();
                }
            });

            llSelectSearch.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    mListViewMessageInboxArchive.setOnItemClickListener(OnitemClick);
                    MessageFragment.mStatusSearch=false;
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
        }
        else if(view == mBtnDelete){
            ErrorMsgDialog.alertOkCancelButtonCallBack(getActivity(), "",getResources().getString(R.string.alert_str_archive_delete_messages), new OkPressedResponseAll());
        }else if (view == mBtnReadUnRead) {
            //ErrorMsgDialog.alertOkCancelButtonCallBack(getActivity(), "", getResources().getString(R.string.alert_str_delete_messages), new OkPressedResponseAll());
            new MakeReadUnreadAll().alertAction(true);
        }
    }


    /**
     * CallBack class for ReadUnread messages
     */
    class MakeReadUnreadAll implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            mDeletedId.clear();
            for (InboxListInnerModel checkedRow: listDataArray){
                if(checkedRow.isSelected()){
                    mDeletedId.add(checkedRow);
                }
            }
            if (AppUtils.isOnline(getActivity())) {
                try {
                    if (AppUtils.isOnline(getActivity())) {
                        mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                        mProgressHUD.setCancelable(false);
                        callReadUnreadWebservice(mDeletedId);
                    } else {
                        ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                    }
                }catch (Exception e){
                    e.printStackTrace();
                }

            } else {
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }

        }
    }
    /**
     * CallBack class for delete
     */
    class OkPressedResponseAll implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            mDeletedId.clear();
            for (InboxListInnerModel checkedRow: listDataArray){
                if(checkedRow.isSelected()){
                    mDeletedId.add(checkedRow);
                }
            }
            if (AppUtils.isOnline(getActivity())) {
                try {
                    if (AppUtils.isOnline(getActivity())) {
                        mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                        mProgressHUD.setCancelable(false);
                        callDeleteWebservice(mDeletedId);
                    } else {
                        ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                    }
                }catch (Exception e){
                    e.printStackTrace();
                }

            } else {
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }

        }
    }

    /**
     * THis method is use to call the webservice and get inbox messages
     * The response and error from server
     */
    private void callInboxArchiveWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.MESSAGE_GET_ACTION_NAME, WebServiceConstants.MESSAGE_COMPOSE_CONTROL, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getMessageInboxListRequest(getInboxArchiveRequestModel(), new Callback<Response>() {
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
                        parseInboxArchiveListService(WebServiceUtils.getResponseString(responseModel));
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
        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.MESSAGE_GET_ACTION_NAME, WebServiceConstants.MESSAGE_COMPOSE_CONTROL, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getMessageInboxListRequest(getSearchRequestModel(searchText), new Callback<Response>() {
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
                        parseInboxArchiveListService(WebServiceUtils.getResponseString(responseModel));
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
    private void callReadUnreadWebservice(final ArrayList<InboxListInnerModel> mDeletedIds) throws Exception {
        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.MESSAGE_READ_UNREAD_ACTION_NAME, WebServiceConstants.MESSAGE_COMPOSE_CONTROL, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.MessageReadUnreadList(getReadUnReadRequestModel(mDeletedId), new Callback<Response>() {
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
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        if (mReadUnRead.is_read()) {
                            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.str_sccess_unread_inbox_message), new DeleteOkPressed());
                        } else {
                            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.str_sccess_read_inbox_message), new DeleteOkPressed());
                        }
                        //deleteReferralsListService(mDeletedIds, WebServiceUtils.getResponseString(responseModel));
                        mMessageInboxArchiveListAdapter.cleanSelectedArray();
                        mReadUnRead = new InboxListInnerModel();
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

    /**
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callDeleteWebservice(final ArrayList<InboxListInnerModel> mDeletedIds) throws Exception {
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
                        ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.str_sccess_archive_delete_messages), new DeleteOkPressed());
                        //deleteReferralsListService(mDeletedIds, WebServiceUtils.getResponseString(responseModel));
                        mMessageInboxArchiveListAdapter.cleanSelectedArray();
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
                        callInboxArchiveWebservice();
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
    private MessageReadUnreadRequestModel getReadUnReadRequestModel(ArrayList<InboxListInnerModel> mDeletedIds) throws Exception {
        MessageReadUnreadRequestModel mMessageReadUnreadRequestModel = new MessageReadUnreadRequestModel();
        mMessageReadUnreadRequestModel.setListPage(WebServiceConstants.INBOX_ARCHIVE_LIST_PAGE);
        String ids="";
        for(InboxListInnerModel s:mDeletedIds){
            if(ids.length()==0){
                ids=s.getId();
            }else{
                ids=ids + "," +s.getId();
            }

        }
        Log.i("ids", ids);
        mMessageReadUnreadRequestModel.setMessageId(ids);
        if(mReadUnRead.is_read()){
            mMessageReadUnreadRequestModel.setMessageStatus(WebServiceConstants.MESSAGE_UNREAD);
        }else{
            mMessageReadUnreadRequestModel.setMessageStatus(WebServiceConstants.MESSAGE_READ);
        }

        return mMessageReadUnreadRequestModel;
    }

    /**
     * This method is use for get the request model
     *
     * @return
     */
    private ReceivedReferralDeleteRequestModel getDeleteRequestModel(ArrayList<InboxListInnerModel> mDeletedIds) throws Exception {
        ReceivedReferralDeleteRequestModel mReceivedReferralsDeleteRequestModel = new ReceivedReferralDeleteRequestModel();
        mReceivedReferralsDeleteRequestModel.setListPage(WebServiceConstants.INBOX_ARCHIVE_LIST_PAGE);
        String ids="";
        for(InboxListInnerModel s:mDeletedIds){
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
    private MessageInboxRequestModel getSearchRequestModel(String searchText) throws Exception {
        MessageInboxRequestModel mMessageInboxRequestModel = new MessageInboxRequestModel();
        mMessageInboxRequestModel.setPage_no("" + pageNumber);
        mMessageInboxRequestModel.setRecord_per_page("" + recordPerPage);
        mMessageInboxRequestModel.setList_page(WebServiceConstants.INBOX_ARCHIVE_LIST_PAGE);
        mMessageInboxRequestModel.setSearch_filter(searchText);
        Log.i("Referrals", AppUtils.getRadioData(getActivity()));
        Log.i("Referrals", AppUtils.getRadioDataASCOrDESC(getActivity()));
        mMessageInboxRequestModel.setSort_data(AppUtils.getRadioData(getActivity()));
        mMessageInboxRequestModel.setSort_direction(AppUtils.getRadioDataASCOrDESC(getActivity()));


        return mMessageInboxRequestModel;
    }

    /**
     * This method is use for get the request model
     *
     * @return
     */
    private MessageInboxRequestModel getInboxArchiveRequestModel() throws Exception {
        MessageInboxRequestModel mMessageInboxRequestModel = new MessageInboxRequestModel();
        mMessageInboxRequestModel.setPage_no("" + pageNumber);
        mMessageInboxRequestModel.setRecord_per_page("" + recordPerPage);
        mMessageInboxRequestModel.setList_page(WebServiceConstants.INBOX_ARCHIVE_LIST_PAGE);

        mMessageInboxRequestModel.setSearch_filter("");
        mMessageInboxRequestModel.setSort_data("");
        mMessageInboxRequestModel.setSort_direction("");

        return mMessageInboxRequestModel;
    }

    private void parseInboxArchiveListService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        MessagesResponseModel get_Response = gson.fromJson(responseStr, MessagesResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getReponseCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getListData != null && get_Response.getListData.size() > 0) {
                    mListViewMessageInboxArchive.setVisibility(View.VISIBLE);
                    mNoRecordFound.setVisibility(View.GONE);
                    listDataArray.addAll(get_Response.getListData);
                    if (mMessageInboxArchiveListAdapter != null) {
                        mMessageInboxArchiveListAdapter.setData(listDataArray);
                        mMessageInboxArchiveListAdapter.notifyDataSetChanged();
                    } else {
                        setListAdapter();
                    }
                }
            } else {
                //ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
                listDataArray.clear();
                mListViewMessageInboxArchive.setVisibility(View.GONE);
                mNoRecordFound.setVisibility(View.VISIBLE);
                if(mStatus/*mPreferences.getSearchText(getActivity()).length()>0 && mPreferences.getSearchOrder(getActivity())==R.id.dateNewest*/){
                    mNoRecordFound.setText(getActivity().getResources().getString(R.string.wrng_str_no_result));
                }else{
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
        mMessageInboxArchiveListAdapter = new MessageInboxArchiveListAdapter(getActivity(), listDataArray, new OnCheckBoxSeletedResponse());
        // set our adapter and pass our recived list content
        mListViewMessageInboxArchive.setAdapter(mMessageInboxArchiveListAdapter);

        SwipeMenuCreator creator = new SwipeMenuCreator() {

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
        mListViewMessageInboxArchive.setMenuCreator(creator);

        mListViewMessageInboxArchive.setOnSwipeListener(new SwipeMenuListView.OnSwipeListener() {

            @Override
            public void onSwipeStart(int position) {
                // swipe start
                //Log.i("Clicked", position + "");
            }

            @Override
            public void onSwipeEnd(int position) {
                // swipe end
                //Log.i("Clicked", position + "");
            }
        });
        //mListView.setSwipeDirection(SwipeMenuListView.DIRECTION_LEFT);
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
                    callInboxArchiveWebservice();
                }catch (Exception e){
                    e.printStackTrace();
                }
            } else {
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }

        }
    }
}
