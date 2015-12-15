/**
 *WebCastComment
 * Display comment for playing video
 * User will able to see the last five comments also
 */
package com.app.foxhopr.fragments.user.Videos;

import android.app.Fragment;
import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.ServiceConnection;
import android.os.Bundle;
import android.os.IBinder;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.HorizontalScrollView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.ScrollView;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.adapter.WebCastCommentListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.WebCastCommentModel;
import com.app.foxhopr.services.CommentService;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.Validation;
import com.app.foxhopr.utils.WebCastSelectedCallBack;
import com.app.foxhopr.webservice.models.ReferralsListInnerModel;
import com.app.foxhopr.webservice.models.ReferrelCommentResponseModel;
import com.app.foxhopr.webservice.models.WebCastCommentResponseModel;
import com.app.foxhopr.webservice.models.WebCastListInnerModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.ArrayList;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class WebCastCommentFragment extends Fragment implements View.OnClickListener {
    private static String TAG="NowPlayingFragment";

    private Context mContext;
    private HorizontalScrollView mLlTopTabs;

    private ArrayList<ReferralsListInnerModel> listDataArray;
    private boolean isAllSelected = false;

    private WebCastSelectedCallBack mTabSelectedCallBack;
    private WebCastListInnerModel mWebCastListInnerModel;


    private BroadcastReceiver mNotificationBroadcast;
    private SharedPreference mPreferences;

    private ScrollView mScrollViewTop;
    //service variable

    CommentService mBoundService;
    boolean mServiceBound = false;
    Intent intentService;
    private ListView mCommentListView;
    private WebCastCommentListAdapter commentlistAdapter;
    private EditText mEditTextMessageComment;
    private LinearLayout mPostComment;
    private TextView mNocommentfound;
    private ProgressHUD mProgressHUD;
    private String mContent;

    public static WebCastCommentFragment newInstance(HorizontalScrollView mLlTopTabs,WebCastListInnerModel mWebCastListInnerModel, WebCastSelectedCallBack mTabSelectedCallBack) {
        WebCastCommentFragment mReferralsReceivedFragment = new WebCastCommentFragment();
        mReferralsReceivedFragment.mLlTopTabs = mLlTopTabs;
        mReferralsReceivedFragment.mTabSelectedCallBack = mTabSelectedCallBack;
        mReferralsReceivedFragment.mWebCastListInnerModel=mWebCastListInnerModel;
        return mReferralsReceivedFragment;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        mContext = getActivity();
        View view = inflater.inflate(R.layout.fragment_webcast_comment, container, false);
        //setting common bottom bar view gone as only edit icon will be here
        initView(view);
        initBroadCast();
        if(null!=mScrollViewTop) {
            mScrollViewTop.postDelayed(new Runnable() {
                @Override
                public void run() {
                    mScrollViewTop.fullScroll(ScrollView.FOCUS_UP);
                }
            }, 600);
        }
        return view;
    }

    private void initView(View view) {
        mPreferences = new SharedPreference();
        mCommentListView=(ListView)view.findViewById(R.id.commentListView);
        mPostComment=(LinearLayout)view.findViewById(R.id.llPostComment);
        mNocommentfound=(TextView)view.findViewById(R.id.nocommentfound);
        mEditTextMessageComment=(EditText)view.findViewById(R.id.editTextMessageComment);
        mScrollViewTop=(ScrollView)view.findViewById(R.id.scrollViewTop);
        applyValidationFilters();

        /*View.OnLongClickListener mOnLongClickListener = new View.OnLongClickListener() {

            @Override
            public boolean onLongClick(View v) {
                // prevent context menu from being popped up, so that user
                // cannot copy/paste from/into any EditText fields.
                InputMethodManager imm = (InputMethodManager)getActivity().getSystemService(Context.INPUT_METHOD_SERVICE);
                imm.hideSoftInputFromWindow(mEditTextMessageComment.getWindowToken(), 0);
                return false;
            }
        };
        mEditTextMessageComment.setOnLongClickListener(mOnLongClickListener);*/
        //listener for posting comments
        mPostComment.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    mContent=mEditTextMessageComment.getText().toString();
                    if (checkValidation()) {
                        if (AppUtils.isOnline(getActivity())) {
                            //CommentAlart.stop(SentReferrelDetailsActivity.this);
                            ApplicationConstants.COMMENT_STATUS = false;
                            mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                            mProgressHUD.setCancelable(false);
                            callAddCommentWebservice();
                        } else {
                            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                        }

                    } /*else {
                        ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.not_comment_blanck));
                    }*/
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        });

        mCommentListView.setOnTouchListener(new View.OnTouchListener() {
            // Setting on Touch Listener for handling the touch inside ScrollView
            @Override
            public boolean onTouch(View v, MotionEvent event) {
                // Disallow the touch request for parent scroll on touch of child view
                v.getParent().requestDisallowInterceptTouchEvent(true);
                return false;
            }
        });

        //calling web service for getting all comment for playing video
        try{
            if (AppUtils.isOnline(getActivity())) {
                mProgressHUD = ProgressHUD.show(getActivity(),"", true,true);
                mProgressHUD.setCancelable(false);
                callCommentWebservice();
            }
            else{
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }
        }catch (Exception ex){
            ex.printStackTrace();
        }
    }

    /**
     * THis method is use to add comments
     */
    private void callAddCommentWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_WEB_CAST_ADD_COMMENT_ACTION_NAME, WebServiceConstants.GET_WEB_CAST_LIST_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.addWebCastComment(getAddCommentRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        //parseAddComment(WebServiceUtils.getResponseString(responseModel));
                        //mEditTextMessageComment.setText("");
                        //callCommentWebservice();
                        //{"code":"404","message":"Webcast video does not exist","webcastExist":false}
                        Gson gson = new Gson();
                        ReferrelCommentResponseModel comment_Response = gson.fromJson(WebServiceUtils.getResponseString(responseModel), ReferrelCommentResponseModel.class);

                        if (comment_Response != null) {
                            //comment_Response = comment_Response;

                            if (comment_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                                ErrorMsgDialog.showErrorAlert(getActivity(), "", comment_Response.getMessage().equals("") ? getString(R.string.wrng_str_no_record) : comment_Response.getMessage(), new OkPressed());
                                mEditTextMessageComment.setText("");
                                //callCommentWebservice();
                            } else if(!comment_Response.getWebcastExist()){
                                mCommentListView.setVisibility(View.GONE);
                                mNocommentfound.setVisibility(View.VISIBLE);
                                ErrorMsgDialog.showErrorAlert(getActivity(), "", comment_Response.getMessage().equals("") ? getString(R.string.wrng_str_no_record) : comment_Response.getMessage(),new WevCastNotExist());
                            }else {
                                mCommentListView.setVisibility(View.GONE);
                                mNocommentfound.setVisibility(View.VISIBLE);
                                ErrorMsgDialog.showErrorAlert(getActivity(), "", comment_Response.getMessage().equals("") ? getString(R.string.wrng_str_no_record) : comment_Response.getMessage());
                            }
                        } else {
                            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
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
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * THis method is use to call for getting all comment for selected sent referral id,
     * webservice and get successful  response or  error from server
     */
    private void callCommentWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_WEB_CAST_GET_COMMENT_ACTION_NAME, WebServiceConstants.GET_WEB_CAST_LIST_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getWebCastComment(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseCommentListService(WebServiceUtils.getResponseString(responseModel));
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
                mProgressHUD.dismiss();
                //ErrorMsgDialog.showErrorAlert(ReferrelDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * THis method is use to call for getting all comment for selected sent referral id,
     * webservice and get successful  response or  error from server
     */
    private void callCommentWebserviceSecond() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_WEB_CAST_GET_COMMENT_ACTION_NAME, WebServiceConstants.GET_WEB_CAST_LIST_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getWebCastComment(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                //mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseCommentListServiceSecond(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        //ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                //mProgressHUD.dismiss();
                //ErrorMsgDialog.showErrorAlert(ReferrelDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    public class OkPressed implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            try {
                if (AppUtils.isOnline(getActivity())) {
                    //CommentAlart.start(SentReferrelDetailsActivity.this);
                    ApplicationConstants.COMMENT_STATUS=true;
                    intentService = new Intent(getActivity(), CommentService.class);
                    getActivity().startService(intentService);
                    getActivity().bindService(intentService, mServiceConnection, Context.BIND_AUTO_CREATE);
                    callCommentWebservice();
                } else {
                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                }
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    public class WevCastNotExist implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            try {
                getActivity().finish();
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    /**
     * This method is use for get the request model for comment web service request
     * @return
     */
    private WebCastCommentModel getAddCommentRequestModel() throws Exception{
        WebCastCommentModel mWebCastCommentModel = new WebCastCommentModel();
        mWebCastCommentModel.setWebcastId(mWebCastListInnerModel.getId());
        mWebCastCommentModel.setComment(mEditTextMessageComment.getText().toString());

        return mWebCastCommentModel;
    }

    /**
     * This method is use for get the request model for sent referral details
     * @return
     */
    private WebCastCommentModel getRequestModel() throws Exception{
        WebCastCommentModel mWebCastCommentModel = new WebCastCommentModel();
        mWebCastCommentModel.setWebcastId(mWebCastListInnerModel.getId());
        return mWebCastCommentModel;
    }

    private void parseCommentListService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        WebCastCommentResponseModel comment_Response = gson.fromJson(responseStr, WebCastCommentResponseModel.class);

        if (comment_Response != null) {
            //comment_Response = comment_Response;

            if (comment_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (comment_Response.getResult() != null && comment_Response.getResult().size()>0) {
                    setCommentONUI(comment_Response);
                }
            } else {
                mCommentListView.setVisibility(View.GONE);
                mNocommentfound.setVisibility(View.VISIBLE);
                //ErrorMsgDialog.showErrorAlert(ReferrelDetailsActivity.this, "", comment_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): comment_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
        }
    }

    private void parseCommentListServiceSecond(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        WebCastCommentResponseModel comment_Response = gson.fromJson(responseStr, WebCastCommentResponseModel.class);

        if (comment_Response != null) {
            //comment_Response = comment_Response;

            if (comment_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (comment_Response.getResult() != null && comment_Response.getResult().size()>0) {
                    setCommentONUI(comment_Response);
                }
            } else {
                mCommentListView.setVisibility(View.GONE);
                mNocommentfound.setVisibility(View.VISIBLE);
                //ErrorMsgDialog.showErrorAlert(ReferrelDetailsActivity.this, "", comment_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): comment_Response.getMessage());
            }
        } else {
            //ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * Setting comment on UI, when getting it from background
     * @param comment_Response
     */
    public void setCommentONUI(WebCastCommentResponseModel comment_Response){
        //mCommentListView
        if(null!=commentlistAdapter /*&& comment_Response.getResult().size()>commentlistAdapter.getCount()*/){
            commentlistAdapter=new WebCastCommentListAdapter(getActivity(),comment_Response.getResult(),null);
            mCommentListView.setAdapter(commentlistAdapter);
            //commentlistAdapter.notifyDataSetChanged();
            if(comment_Response.getResult().size()>0) {
                mNocommentfound.setVisibility(View.GONE);
                mCommentListView.setVisibility(View.VISIBLE);
                //mCommentListView.setSelection(comment_Response.getResult().size() - 1);
            }
        }else if(null==commentlistAdapter){
            commentlistAdapter = new WebCastCommentListAdapter(getActivity(), comment_Response.getResult(), null);
            mCommentListView.setAdapter(commentlistAdapter);
            //commentlistAdapter.notifyDataSetChanged();
            if (comment_Response.getResult().size() > 0) {
                mNocommentfound.setVisibility(View.GONE);
                mCommentListView.setVisibility(View.VISIBLE);
                //mCommentListView.setSelection(comment_Response.getResult().size() - 1);
            }
        }

    }

    @Override
    public void onClick(View view) {

    }

    //Setting value on UI with color specific
    public void setValue(TextView View,String Value){

        if(null!=Value && !Value.equals("") ){
            View.setTextColor(getResources().getColor(R.color.refrrels_list_small_text_color));
            View.setText(Value);
        }else{
            View.setTextColor(getResources().getColor(R.color.light_gray_color));
            View.setText(getResources().getString(R.string.not_applicable));
        }

    }
    //Setting value on UI with color specific
    public void setValueGrey(TextView View,String Value){
        if (null != Value && !Value.equals("")) {
            View.setTextColor(getResources().getColor(R.color.light_gray_color));
            View.setText(Value.trim());
        } else {
            View.setTextColor(getResources().getColor(R.color.light_gray_color));
            View.setText(getResources().getString(R.string.not_applicable));
        }

    }

    //Setting value on UI with color specific
    public void setValueWithoutColor(TextView View,String Value){

        if(null!= Value && !Value.equals("") ){
            View.setText(Value);
        }
    }

    //Setting value on UI with color specific
    public void setValueWithColor(TextView View,String Value,int colorid){

        if(null!=Value && !Value.equals("")) {
            View.setTextColor(colorid);
            View.setText(Value);
        }
    }

    /**
     * Initializing bradcast for getting notifications and
     * update it to same screen
     */
    public void initBroadCast() {
        mNotificationBroadcast = new BroadcastReceiver() {

            @Override
            public void onReceive(Context context, Intent intent) {
                String versionValue = intent.getStringExtra(ApplicationConstants.NOTIFY_DATAUPDATE_KEY);
                if (versionValue.equals(ApplicationConstants.NOTIFY_DATAUPDATE_VALUE)) {
                    try{
                        //callCommentWebservice();
                        if (AppUtils.isOnline(getActivity())) {
                            // mFooterProgressBar.setVisibility(View.VISIBLE);
                            //mProgressHUD = ProgressHUD.show(ReferrelDetailsActivity.this, "", true, true);
                            //mProgressHUD.setCancelable(false);
                            try {
                                callCommentWebserviceSecond();
                            }catch (Exception e){
                                e.printStackTrace();
                            }
                        } else {
                            // ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                        }
                    }catch (Exception e){
                        e.printStackTrace();
                    }
                }
            }
        };
    }
   /* @Override
    protected void onResume() {
        super.onResume();
        registerReceiver(mNotificationBroadcast, new IntentFilter(
                ApplicationConstants.NOTIFY_DATAUPDATE_ACTION));
    }*/
   /* @Override
    protected void onPause() {
        super.onPause();
        try {
            unregisterReceiver(mNotificationBroadcast);
        } catch (Exception e) {
            e.printStackTrace();
        }
        //CommentAlart.stop(ReferrelDetailsActivity.this);
    }

    @Override
    protected void onStart() {
        super.onStart();
        ApplicationConstants.COMMENT_STATUS=true;
        intentService = new Intent(this, CommentService.class);
        startService(intentService);
        bindService(intentService, mServiceConnection, Context.BIND_AUTO_CREATE);
    }
    @Override
    protected void onStop() {
        super.onStop();
        if (mServiceBound) {
            unbindService(mServiceConnection);
            mServiceBound = false;
            ApplicationConstants.COMMENT_STATUS=false;
        }
    }*/

    @Override
    public void onStart() {
        super.onStart();
        ApplicationConstants.COMMENT_STATUS=true;
        intentService = new Intent(getActivity(), CommentService.class);
        getActivity().startService(intentService);
        getActivity().bindService(intentService, mServiceConnection, Context.BIND_AUTO_CREATE);
    }

    @Override
    public void onStop() {
        super.onStop();
        if (mServiceBound) {
            getActivity().unbindService(mServiceConnection);
            mServiceBound = false;
            ApplicationConstants.COMMENT_STATUS=false;
        }
    }

    @Override
    public void onPause() {
        super.onPause();
        try {
            getActivity().unregisterReceiver(mNotificationBroadcast);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }


    private ServiceConnection mServiceConnection = new ServiceConnection() {

        @Override
        public void onServiceDisconnected(ComponentName name) {
            mServiceBound = false;
        }

        @Override
        public void onServiceConnected(ComponentName name, IBinder service) {
            CommentService.MyBinder myBinder = (CommentService.MyBinder) service;
            mBoundService = myBinder.getService();
            mServiceBound = true;
        }
    };

    @Override
    public void onResume() {
        super.onResume();
        getActivity().registerReceiver(mNotificationBroadcast, new IntentFilter(
                ApplicationConstants.NOTIFY_DATAUPDATE_ACTION));
    }

    /**
     * to check all validation
     * here subject and body content is required
     *
     * @return boolean
     */
    private boolean checkValidation(){
        boolean isAllFieldCorrect = true;

        if (mContent.length() == 0) {
            isAllFieldCorrect = false;
            mEditTextMessageComment.requestFocus();
            mEditTextMessageComment.setBackgroundResource(R.drawable.bg_white_select_team_error);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.not_comment_blanck));

        } else if(mContent.length() >0 && mContent.length() >300){
            isAllFieldCorrect = false;
            mEditTextMessageComment.requestFocus();
            mEditTextMessageComment.setBackgroundResource(R.drawable.bg_white_select_team_error);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_comment_body_max_limit));
        }
        return isAllFieldCorrect;
    }

    private void applyValidationFilters() {

        mEditTextMessageComment.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValidComment(getActivity(), mEditTextMessageComment, mEditTextMessageComment);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
    }
}
