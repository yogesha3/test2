/**
 * WebCastFragment display list of video uploading at admin end
 * after clicking on any row user will redirect for playing video, where details for selected video will display
 * User can play next video also, or can provide comment on playing video
 */
package com.app.foxhopr.fragments.user.Videos;


import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.HorizontalScrollView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.adapter.WebCastListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.WebcastListRequestModel;
import com.app.foxhopr.swipemenulist.SwipeMenuListView;
import com.app.foxhopr.ui.Video.WebCastPlayerActivity;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.app.foxhopr.utils.WebCastListClickCallBack;
import com.app.foxhopr.webservice.models.WebCastListInnerModel;
import com.app.foxhopr.webservice.models.WebcastListResponseModel;
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
public class WebCastFragment extends Fragment implements View.OnClickListener{
    private static String TAG = "WebCastFragment";
    private WebCastListAdapter mWebCastListAdapter;
    private Context mContext;
    private HorizontalScrollView mLlTopTabs;
    private LinearLayout mLlBottomTabs;
    private Button mBtnDelete;
    private Button mBtnReadUnRead;
    private LinearLayout mLLBottomSelectTab;
    private LinearLayout mLLBottomMoreTab;
    private TabSelectedCallBack mTabSelectedCallBack;

    private ProgressHUD mProgressHUD;
    //Footer View
    private View footerViewInProgress;
    private ProgressBar mFooterProgressBar;
    private ArrayList<WebCastListInnerModel> listDataArray;
    private boolean isAllSelected = false;

    //Page data
    private WebcastListResponseModel getResponse;
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

    private SwipeMenuListView mListViewVideo;

    public WebCastFragment() {
        // Required empty public constructor
    }

    public static WebCastFragment newInstance(HorizontalScrollView mLlTopTabs, LinearLayout mLlBottomTabs, Button mBtnDelete,Button mBtnReadUnRead,
                                                  LinearLayout lLBottomSelectTab, LinearLayout lLBottomMoreTab, TabSelectedCallBack mTabSelectedCallBack) {
        WebCastFragment mPreviousTeamFragment = new WebCastFragment();
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
        View view = inflater.inflate(R.layout.fragment_webcast, container, false);
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
        listDataArray = new ArrayList<WebCastListInnerModel>();
        mListViewVideo = (SwipeMenuListView) view.findViewById(R.id.listViewVideo);
        //mListViewTeam.setOnItemClickListener(OnitemClick);

        mNoRecordFound = (TextView) view.findViewById(R.id.norecordfound);

        // add the footer before adding the adapter, else the footer will not load!
        footerViewInProgress = ((LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE)).inflate(R.layout.layout_list_footer_view, null, false);
        mFooterProgressBar = (ProgressBar) footerViewInProgress.findViewById(R.id.progressBarFooterList);
        mFooterProgressBar.setVisibility(View.GONE);
        mListViewVideo.addFooterView(footerViewInProgress);

        mListViewVideo.setOnScrollListener(onScrollListener);
        //mBtnDelete.setOnClickListener(this);
        //mBtnReadUnRead.setOnClickListener(this);
        //mLLBottomSelectTab.setOnClickListener(this);
        //mLLBottomMoreTab.setOnClickListener(this);

        mListViewVideo.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                if (AppUtils.isOnline(getActivity())) {
                    Intent webCastDetails = new Intent(getActivity(), WebCastPlayerActivity.class);
                    //detailsReferrels.putStringExtra("video_id", listDataArray.get(position));
                    webCastDetails.putExtra("details",listDataArray.get(position));
                    startActivity(webCastDetails);
                } else {
                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                }
            }
        });

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

        //getDataForList();
    }

    /**
     * On scroll listener use to load more data at end of list.
     */
    AbsListView.OnScrollListener onScrollListener = new AbsListView.OnScrollListener() {

        @Override
        public void onScrollStateChanged(AbsListView view, int scrollState) {
            int threshold = 1;
            int count = mListViewVideo.getCount() - 1;

			/*if (scrollState == OnScrollListener.SCROLL_STATE_TOUCH_SCROLL) {
                mLlBottomTabs.setVisibility(View.GONE);
				mLlBottomTabs.startAnimation(animHide);
			}else{
				mLlBottomTabs.setVisibility(View.VISIBLE);
				mLlBottomTabs.startAnimation( animShow );
			}*/
            if (getResponse.getTotalWebcast() != null && !getResponse.getTotalWebcast().equalsIgnoreCase("") && count < Integer.parseInt(getResponse.getTotalWebcast())) {

                if (scrollState == SCROLL_STATE_IDLE && mWebserviceStatus) {

                    if (mListViewVideo.getLastVisiblePosition() >= count - threshold) {
                        pageNumber = pageNumber + 1;
                        try {
							/*
							 *hecking which data is displaying (by default or search)
							 *for false by default is calling
							 *for true search is calling
							 */
                                if (AppUtils.isOnline(getActivity())) {
                                    mFooterProgressBar.setVisibility(View.VISIBLE);
                                    mWebserviceStatus=false;
                                    mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                                    mProgressHUD.setCancelable(false);
                                    callWebservice();
                                } else {
                                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
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


    class OnCheckBoxSeletedResponse implements WebCastListClickCallBack {

        @Override
        public void itemClickAction(ArrayList<WebCastListInnerModel> mList) {
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
    }

    /**
     * Method use for set the data on List
     * @throws Exception
     */

    private void setListAdapter() throws Exception {
        mWebCastListAdapter = new WebCastListAdapter(getActivity(), listDataArray, new OnCheckBoxSeletedResponse());
        // set our adapter and pass our recived list content
        mListViewVideo.setAdapter(mWebCastListAdapter);


    }

    /**
     * THis method is use to call the webservice and get webcastlist details
     *
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_WEB_CAST_LIST_ACTION_NAME, WebServiceConstants.GET_WEB_CAST_LIST_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getWebCast(getRequestModel(), new Callback<Response>() {
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
                        parseWebCastList(WebServiceUtils.getResponseString(responseModel));
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
     * This method is use for get the request model for webcastlist
     *
     * @return
     */
    private WebcastListRequestModel getRequestModel() throws Exception {
        WebcastListRequestModel mWebcastListRequestModel = new WebcastListRequestModel();
        mWebcastListRequestModel.setPage_no("" + pageNumber);
        mWebcastListRequestModel.setRecord_per_page("" + recordPerPage);
        return mWebcastListRequestModel;
    }

    /**
     * parse webcastlist
     *
     * @param responseStr
     * @throws Exception
     */
    private void parseWebCastList(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        WebcastListResponseModel get_Response = gson.fromJson(responseStr, WebcastListResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null && get_Response.getResult().size() > 0) {
                    mListViewVideo.setVisibility(View.VISIBLE);
                    mNoRecordFound.setVisibility(View.GONE);
                    listDataArray.addAll(get_Response.getResult());
                    if (mWebCastListAdapter != null) {
                        mWebCastListAdapter.setData(listDataArray);
                        mWebCastListAdapter.notifyDataSetChanged();
                    } else {
                        setListAdapter();
                    }
                }
            } else {
                //ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
                listDataArray.clear();
                mListViewVideo.setVisibility(View.GONE);
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

}
