/**
 * Display list of partners
 */
package com.app.foxhopr.fragments.user.Contacts;


import android.content.Context;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView;
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
import com.app.foxhopr.adapter.PartnerListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.fragments.user.ContactsFragment;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.MessageInboxRequestModel;
import com.app.foxhopr.swipemenulist.SwipeMenuListView;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.app.foxhopr.webservice.models.ContactListInnerModel;
import com.app.foxhopr.webservice.models.PartnerListInnerModel;
import com.app.foxhopr.webservice.models.PartnerResponseModel;
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
public class PartnerFragment extends Fragment implements View.OnClickListener{
    private static String TAG = "PartnerFragment";
    private PartnerListAdapter mPartnerListAdapter;
    private Context mContext;
    private HorizontalScrollView mLlTopTabs;
    private LinearLayout mLlBottomTabs;
    private Button mBtnDelete;
    private Button mBtnReadUnRead;
    private LinearLayout mLLBottomSelectTab;
    private LinearLayout mLLBottomMoreTab;
    private TabSelectedCallBack mTabSelectedCallBack;

    private SwipeMenuListView mListViewContact;
    private ProgressHUD mProgressHUD;

    //Footer View
    private View footerViewInProgress;
    private ProgressBar mFooterProgressBar;
    private ArrayList<PartnerListInnerModel> listDataArray;
    private boolean isAllSelected = false;

    //Page data
    private PartnerResponseModel getResponse;
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
    private ArrayList<ContactListInnerModel> mDeletedId = new ArrayList<ContactListInnerModel>();

    //variable storing state of search or simple by default referal
    private boolean mStatus = false;
    private TextView mNoRecordFound;
    private boolean mWebserviceStatus = true;

    public PartnerFragment() {
        // Required empty public constructor
    }
    public static PartnerFragment newInstance(HorizontalScrollView mLlTopTabs, LinearLayout mLlBottomTabs, Button mBtnDelete,Button mBtnReadUnRead,
                                                  LinearLayout lLBottomSelectTab, LinearLayout lLBottomMoreTab, TabSelectedCallBack mTabSelectedCallBack) {
        PartnerFragment mPartnerFragment = new PartnerFragment();
        mPartnerFragment.mLlTopTabs = mLlTopTabs;
        mPartnerFragment.mLlBottomTabs = mLlBottomTabs;
        mPartnerFragment.mBtnDelete = mBtnDelete;
        mPartnerFragment.mBtnReadUnRead = mBtnReadUnRead;
        mPartnerFragment.mLLBottomSelectTab = lLBottomSelectTab;
        mPartnerFragment.mLLBottomMoreTab = lLBottomMoreTab;
        mPartnerFragment.mTabSelectedCallBack = mTabSelectedCallBack;

        return mPartnerFragment;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        mContext = getActivity();
        View view = inflater.inflate(R.layout.fragment_partner, container, false);
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
        listDataArray = new ArrayList<PartnerListInnerModel>();
        mListViewContact = (SwipeMenuListView) view.findViewById(R.id.listViewContact);
        //mListViewContact.setOnItemClickListener(OnitemClick);

        mNoRecordFound = (TextView) view.findViewById(R.id.norecordfound);

        mListViewContact.setOnScrollListener(onScrollListener);
        //mListViewContact.setOnMenuItemClickListener(onMenuItemClickListener);

        // add the footer before adding the adapter, else the footer will not load!
        footerViewInProgress = ((LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE)).inflate(R.layout.layout_list_footer_view, null, false);
        mFooterProgressBar = (ProgressBar) footerViewInProgress.findViewById(R.id.progressBarFooterList);
        mFooterProgressBar.setVisibility(View.GONE);
        mListViewContact.addFooterView(footerViewInProgress);
        mBtnDelete.setOnClickListener(null);
        mLLBottomSelectTab.setOnClickListener(null);
        mLLBottomMoreTab.setOnClickListener(this);
        mLLBottomSelectTab.findViewById(R.id.imgvBottomTabSelect).setAlpha(0.5f);
        mLLBottomMoreTab.findViewById(R.id.imgvBottomTabSearch).setAlpha(1);

    }
    /**
     * On scroll listener use to load more data at end of list.
     */
    AbsListView.OnScrollListener onScrollListener = new AbsListView.OnScrollListener() {

        @Override
        public void onScrollStateChanged(AbsListView view, int scrollState) {
            int threshold = 1;
            int count = mListViewContact.getCount() - 1;

			/*if (scrollState == OnScrollListener.SCROLL_STATE_TOUCH_SCROLL) {
                mLlBottomTabs.setVisibility(View.GONE);
				mLlBottomTabs.startAnimation(animHide);
			}else{
				mLlBottomTabs.setVisibility(View.VISIBLE);
				mLlBottomTabs.startAnimation( animShow );
			}*/
            if (getResponse.getTotalPartners() != null && !getResponse.getTotalPartners().equalsIgnoreCase("") && count < Integer.parseInt(getResponse.getTotalPartners())) {

                if (scrollState == SCROLL_STATE_IDLE && mWebserviceStatus) {

                    if (mListViewContact.getLastVisiblePosition() >= count - threshold) {
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
                                    callContactWebservice();
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

    /**
     * THis method is use to call the webservice and get inbox messages
     * The response and error from server
     */
    private void callContactWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_PARTNER_LIST_ACTION_NAME, WebServiceConstants.ADD_CONTACT_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getContactListRequest(getInboxRequestModel(), new Callback<Response>() {
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
                        parseContactListService(WebServiceUtils.getResponseString(responseModel));
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
        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_PARTNER_LIST_ACTION_NAME, WebServiceConstants.ADD_CONTACT_CONTROL_NAME, getActivity())).build();
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
                        parseContactListService(WebServiceUtils.getResponseString(responseModel));
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
     * This method is use for get the request model
     *
     * @return
     */
    private MessageInboxRequestModel getSearchRequestModel(String searchText) throws Exception {
        MessageInboxRequestModel mMessageInboxRequestModel = new MessageInboxRequestModel();
        mMessageInboxRequestModel.setPage_no("" + pageNumber);
        mMessageInboxRequestModel.setRecord_per_page("" + recordPerPage);
        mMessageInboxRequestModel.setSearch_filter(searchText);
        Log.i(TAG, AppUtils.getRadioData(getActivity()));
        Log.i(TAG, AppUtils.getRadioDataASCOrDESC(getActivity()));
        mMessageInboxRequestModel.setSort_data(AppUtils.getRadioData(getActivity()));
        mMessageInboxRequestModel.setSort_direction(AppUtils.getRadioDataASCOrDESC(getActivity()));

        return mMessageInboxRequestModel;
    }

    /**
     * This method is use for get the request model
     *
     * @return
     */
    private MessageInboxRequestModel getInboxRequestModel() throws Exception {
        MessageInboxRequestModel mMessageInboxRequestModel = new MessageInboxRequestModel();
        mMessageInboxRequestModel.setPage_no("" + pageNumber);
        mMessageInboxRequestModel.setRecord_per_page("" + recordPerPage);

        mMessageInboxRequestModel.setSearch_filter("");
        mMessageInboxRequestModel.setSort_data("");
        mMessageInboxRequestModel.setSort_direction("");

        return mMessageInboxRequestModel;
    }

    private void parseContactListService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        PartnerResponseModel get_Response = gson.fromJson(responseStr, PartnerResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getListData != null && get_Response.getListData.size() > 0) {
                    mListViewContact.setVisibility(View.VISIBLE);
                    mNoRecordFound.setVisibility(View.GONE);
                    listDataArray.addAll(get_Response.getListData);
                    if (mPartnerListAdapter != null) {
                        mPartnerListAdapter.setData(listDataArray);
                        mPartnerListAdapter.notifyDataSetChanged();
                    } else {
                        setListAdapter();
                    }
                }
            } else {
                //ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
                listDataArray.clear();
                mListViewContact.setVisibility(View.GONE);
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
        mPartnerListAdapter = new PartnerListAdapter(getActivity(), listDataArray);
        // set our adapter and pass our recived list content
        mListViewContact.setAdapter(mPartnerListAdapter);
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
                    callContactWebservice();
                }catch (Exception e){
                    e.printStackTrace();
                }
            } else {
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }

        }
    }
    @Override
    public void onClick(View view) {
        if (view == mLLBottomMoreTab) {
            mListViewContact.setOnItemClickListener(null);
            mListViewContact.setEnabled(false);
            LayoutInflater mInflater = (LayoutInflater) getActivity().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            final View innerView = mInflater.inflate(R.layout.layout_search_partner, null, false);
            final FrameLayout searchFrameLayout = (FrameLayout) getActivity().findViewById(R.id.messagesSearchViewPager);
            searchFrameLayout.addView(innerView);
            getActivity().overridePendingTransition(0, 0);
            ContactsFragment.mStatusSearch = true;
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
                    mListViewContact.setEnabled(true);
                    //mListViewContact.setOnItemClickListener(OnitemClick);
                    mPreferences.setSearrchOrder(getActivity(), lastSelected);
                    ContactsFragment.mStatusSearch = false;
                    searchFrameLayout.removeAllViews();
                }
            });

            llSelectSearch.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    //mListViewContact.setOnItemClickListener(OnitemClick);
                    mListViewContact.setEnabled(true);
                    ContactsFragment.mStatusSearch = false;
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
    }
}
