/**
 * Sent Referrel Details activity
 * Showing all the details for selected rererrel
 * comment will be updated on same screen
 *
 */
package com.app.foxhopr.ui.activities;

import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.ServiceConnection;
import android.graphics.Typeface;
import android.net.Uri;
import android.os.Bundle;
import android.os.IBinder;
import android.support.v4.app.FragmentActivity;
import android.text.Html;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.MotionEvent;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.ScrollView;
import android.widget.TextView;

import com.app.foxhopr.adapter.CommentListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.ReceivedReferralCommentModel;
import com.app.foxhopr.request.models.ReferralsDetailsRequestModel;
import com.app.foxhopr.services.CommentService;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.webservice.models.FileModel;
import com.app.foxhopr.webservice.models.ReceivedDetailsModel;
import com.app.foxhopr.webservice.models.ReferrelCommentResponseModel;
import com.app.foxhopr.webservice.models.ReferrelDetailsResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.text.NumberFormat;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class SentReferrelDetailsActivity extends FragmentActivity implements View.OnClickListener {

    private final String TAG="SentReferrelDetails";
    private BroadcastReceiver mNotificationBroadcast;
    private ProgressHUD mProgressHUD;

    private ImageView mBackButton;
    private ImageView mEditButton;
    private String mRefferalId;

    private TextView mTextViewHeaderTitle;
    CommentListAdapter commentlistAdapter;

    //details data
    ReferrelDetailsResponseModel getResponse;

    private TextView mTextFirstName;
    private TextView mTextCompany;

    private TextView mEmail;
    private TextView mWebsite;
    private TextView mMobile;
    private TextView mTelephone;
    private TextView mAddress;
    private TextView mReferralsStatus;
    private TextView mReferralsValue;
    private TextView mReferralsWant;
    private TextView mReferralTextMessage;
    private ListView mCommentListView;
    private LinearLayout mllPostComment;
    private TextView mDatevalue;

    private EditText mEditTextMessageComment;
    private LinearLayout mPostComment;
    private LinearLayout mLlFileLayout;
    private TextView mNocommentfound;

    private ScrollView mScrollViewTop;
    Intent mCommentIntent;

    //service variable

    CommentService mBoundService;
    boolean mServiceBound = false;
    Intent intentService;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sent_referrel_details);
        mRefferalId=getIntent().getStringExtra("referral_id");
        //CommentAlart.stop(SentReferrelDetailsActivity.this);
        //CommentAlart.start(SentReferrelDetailsActivity.this);
        //mCommentIntent=new Intent(SentReferrelDetailsActivity.this, CommentService.class);
        //startService(mCommentIntent);
        initView();
        initBroadCast();
        if(null!=mScrollViewTop) {
            mScrollViewTop.postDelayed(new Runnable() {
                @Override
                public void run() {
                    mScrollViewTop.fullScroll(ScrollView.FOCUS_UP);
                }
            }, 600);
        }
    }

    public void initView(){
        mBackButton=(ImageView)findViewById(R.id.imgvDrawer);
        mBackButton.setOnClickListener(this);
        mEditButton=(ImageView)findViewById(R.id.editView);
        mEditButton.setVisibility(View.GONE);
        mTextViewHeaderTitle=(TextView)findViewById(R.id.textViewHeaderTitle);
        mTextViewHeaderTitle.setVisibility(View.VISIBLE);
        mTextViewHeaderTitle.setText(getResources().getString(R.string.str_referrals));

        mTextFirstName =(TextView)findViewById(R.id.textViewItemRecivedName);
        mTextCompany =(TextView)findViewById(R.id.textViewItemSecivedName);

        mEmail=(TextView)findViewById(R.id.emailValue);
        mWebsite=(TextView)findViewById(R.id.websiteValue);
        mMobile=(TextView)findViewById(R.id.mobileNoValue);
        mTelephone=(TextView)findViewById(R.id.telephoneValue);
        mAddress=(TextView)findViewById(R.id.locationValue);
        mReferralsStatus=(TextView)findViewById(R.id.textViewReferralsStatus);
        mReferralsValue=(TextView)findViewById(R.id.textViewReferralsValues);
        mReferralsWant=(TextView)findViewById(R.id.textViewReferralWant);
        mReferralTextMessage=(TextView)findViewById(R.id.textmessage);
        mCommentListView=(ListView)findViewById(R.id.commentListView);
        mllPostComment=(LinearLayout)findViewById(R.id.llPostComment);
        mDatevalue=(TextView)findViewById(R.id.datevalue);
        mNocommentfound=(TextView)findViewById(R.id.nocommentfound);

        mCommentListView=(ListView)findViewById(R.id.commentListView);
        mPostComment=(LinearLayout)findViewById(R.id.llPostComment);
        mEditTextMessageComment=(EditText)findViewById(R.id.editTextMessageComment);

        mLlFileLayout=(LinearLayout)findViewById(R.id.llFileLayout);
        mScrollViewTop=(ScrollView)findViewById(R.id.scrollViewTop);

        //Call the webservices
        try{
            if (AppUtils.isOnline(SentReferrelDetailsActivity.this)) {
                mProgressHUD = ProgressHUD.show(SentReferrelDetailsActivity.this,"", true,true);
                mProgressHUD.setCancelable(false);
                callWebservice();
            }
            else{
                ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
            }
        }catch (Exception ex){
            ex.printStackTrace();
        }
        //listener for posting comments
        mPostComment.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    if(mEditTextMessageComment.getText().toString().length()>0) {
                        if (AppUtils.isOnline(SentReferrelDetailsActivity.this)) {
                            //CommentAlart.stop(SentReferrelDetailsActivity.this);
                            ApplicationConstants.COMMENT_STATUS=false;
                            mProgressHUD = ProgressHUD.show(SentReferrelDetailsActivity.this,"", true,true);
                            mProgressHUD.setCancelable(false);
                            callAddCommentWebservice();
                        }else{
                            ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                        }

                    }else{
                        ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.not_comment_blanck));
                    }
                }catch (Exception e){
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
    }

    /**
     * THis method is use to call the sent referral details webservice and get
     * successful  response or  error from server
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.RECEIVED_REFERREL_DETAILSL_ACTION_NAME, WebServiceConstants.REFERRALS_CONTROL_NAME, SentReferrelDetailsActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getReferralsReceivedDetailsRequest(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        callCommentWebservice();
                        parseReferralsListService(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * THis method is use to call the sent referral details webservice and get
     * successful  response or  error from server
     * method is calling from background second time
     */
    private void callWebserviceSecond() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.RECEIVED_REFERREL_DETAILSL_ACTION_NAME, WebServiceConstants.REFERRALS_CONTROL_NAME, SentReferrelDetailsActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getReferralsReceivedDetailsRequest(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        callCommentWebservice();
                        parseReferralsListServiceSecond(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        //ErrorMsgDialog.showErrorAlert(ReferrelDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
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
    private void callCommentWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.RECEIVED_REFERREL_COMMENT_ACTION_NAME, WebServiceConstants.REFERRALS_CONTROL_NAME, SentReferrelDetailsActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getReferralsReceivedDetailsRequest(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseCommentListService(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
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
     * THis method is use to add comments
     */
    private void callAddCommentWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.RECEIVED_REFERREL_ADD_COMMENT_ACTION_NAME, WebServiceConstants.REFERRALS_CONTROL_NAME, SentReferrelDetailsActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.ReferralsAddCommentRequest(getAddCommentRequestModel(), new Callback<Response>() {
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
                        Gson gson = new Gson();
                        ReferrelCommentResponseModel comment_Response = gson.fromJson(WebServiceUtils.getResponseString(responseModel), ReferrelCommentResponseModel.class);

                        if (comment_Response != null) {
                            //comment_Response = comment_Response;

                            if (comment_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                                ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", comment_Response.getMessage().equals("") ? getString(R.string.wrng_str_no_record) : comment_Response.getMessage(), new OkPressed());
                                mEditTextMessageComment.setText("");
                                //callCommentWebservice();
                            } else {
                                mCommentListView.setVisibility(View.GONE);
                                mNocommentfound.setVisibility(View.VISIBLE);
                                ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", comment_Response.getMessage().equals("") ? getString(R.string.wrng_str_no_record) : comment_Response.getMessage());
                            }
                        } else {
                            ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
                        }
                    } else {
                        ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    public class OkPressed implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            try {
                if (AppUtils.isOnline(SentReferrelDetailsActivity.this)) {
                    //CommentAlart.start(SentReferrelDetailsActivity.this);
                    ApplicationConstants.COMMENT_STATUS=true;
                    intentService = new Intent(SentReferrelDetailsActivity.this, CommentService.class);
                    startService(intentService);
                    bindService(intentService, mServiceConnection, Context.BIND_AUTO_CREATE);
                    callCommentWebservice();
                } else {
                    ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                }
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    /**
     * parse ReferralsDetails, getting from web services
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseReferralsListService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        ReferrelDetailsResponseModel get_Response = gson.fromJson(responseStr, ReferrelDetailsResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null) {
                    setValuesONUI(get_Response);
                }
            } else {
                ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * parse ReferralsDetails, getting from web services
     *
     * @param responseStr
     * @throws Exception
     */
    private void parseReferralsListServiceSecond(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        ReferrelDetailsResponseModel get_Response = gson.fromJson(responseStr, ReferrelDetailsResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null) {
                    setValuesONUI(get_Response);
                }
            } else {
                //ErrorMsgDialog.showErrorAlert(ReferrelDetailsActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            //ErrorMsgDialog.showErrorAlert(ReferrelDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * parse Comments list getting from comment web service
     *
     * @param responseStr
     * @throws Exception
     */


    private void parseCommentListService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        ReferrelCommentResponseModel comment_Response = gson.fromJson(responseStr, ReferrelCommentResponseModel.class);

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
            ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    private void parseAddComment(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        ReferrelCommentResponseModel comment_Response = gson.fromJson(responseStr, ReferrelCommentResponseModel.class);

        if (comment_Response != null) {
            //comment_Response = comment_Response;

            if (comment_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", comment_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): comment_Response.getMessage());
                mEditTextMessageComment.setText("");
                callCommentWebservice();
            } else {
                mCommentListView.setVisibility(View.GONE);
                mNocommentfound.setVisibility(View.VISIBLE);
                ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", comment_Response.getMessage().equals("") ? getString(R.string.wrng_str_no_record) : comment_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * Setting comment on UI, when getting it from background
     * @param comment_Response
     */
    public void setCommentONUI(ReferrelCommentResponseModel comment_Response){
        //mCommentListView
        if(null!=commentlistAdapter && comment_Response.getResult().size()>commentlistAdapter.getCount()){
            commentlistAdapter=new CommentListAdapter(SentReferrelDetailsActivity.this,comment_Response.getResult(),null);
            mCommentListView.setAdapter(commentlistAdapter);
            //commentlistAdapter.notifyDataSetChanged();
            if(comment_Response.getResult().size()>0) {
                mNocommentfound.setVisibility(View.GONE);
                mCommentListView.setVisibility(View.VISIBLE);
                //mCommentListView.setSelection(comment_Response.getResult().size() - 1);
            }
        }else if(null==commentlistAdapter){
            commentlistAdapter = new CommentListAdapter(SentReferrelDetailsActivity.this, comment_Response.getResult(), null);
            mCommentListView.setAdapter(commentlistAdapter);
            //commentlistAdapter.notifyDataSetChanged();
            if (comment_Response.getResult().size() > 0) {
                mNocommentfound.setVisibility(View.GONE);
                mCommentListView.setVisibility(View.VISIBLE);
                //mCommentListView.setSelection(comment_Response.getResult().size() - 1);
            }
        }

    }

    /**
     * Setting all values on UI after parsing
     * @param get_Response
     */

    public void setValuesONUI(final ReferrelDetailsResponseModel get_Response){

        setValueWithoutColor(mTextFirstName, get_Response.getResult().getFirst_name() + " " + get_Response.getResult().getLast_name());

        if(null!=get_Response.getResult().getJob_title() && get_Response.getResult().getJob_title().trim().length()>0) {
            setValueWithoutColor(mTextCompany, get_Response.getResult().getJob_title() + ", " + get_Response.getResult().getCompany());
        }else if(null!=get_Response.getResult().getCompany() && get_Response.getResult().getCompany().trim().length()>0){
            setValueWithoutColor(mTextCompany, get_Response.getResult().getCompany());
        }

        setValue(mEmail, get_Response.getResult().getEmail());
        setValue(mWebsite, get_Response.getResult().getWebsite());

        mEmail.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(null!=get_Response.getResult().getEmail() && get_Response.getResult().getEmail().trim().length()>0) {
                    final Intent emailIntent = new Intent(Intent.ACTION_SEND);
                    emailIntent.setType("text/plain");
                    emailIntent.putExtra(Intent.EXTRA_EMAIL, new String[]{get_Response.getResult().getEmail()});
                    startActivity(Intent.createChooser(emailIntent, ""));
                }
            }
        });

        mWebsite.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String url="";
                if(null!=get_Response.getResult().getWebsite() && get_Response.getResult().getWebsite().trim().length()>0) {
                    if (get_Response.getResult().getWebsite().contains("HTTP://") || get_Response.getResult().getWebsite().contains("http://")) {
                        url = get_Response.getResult().getWebsite().trim();
                    } else {
                        url = "http://" + get_Response.getResult().getWebsite().trim();
                    }
                    Log.i("tag", get_Response.getResult().getWebsite());
                    Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                    startActivity(browserIntent);
                }
            }
        });


        setValueGrey(mMobile, get_Response.getResult().getMobile());
        setValueGrey(mTelephone, get_Response.getResult().getOffice_phone());


        setValueGrey(mAddress,calculateAddress(get_Response.getResult()));

        if(null!=get_Response.getResult().getReferral_status() && get_Response.getResult().getReferral_status().trim().length()>0) {
            mReferralsStatus.setText(get_Response.getResult().getReferral_status().substring(0, 1).toUpperCase() +get_Response.getResult().getReferral_status().substring(1));
        }

        if(null!=get_Response.getResult().getMonetary_value() && get_Response.getResult().getMonetary_value().trim().length()>0) {
            setValue(mReferralsValue, "$"+ NumberFormat.getInstance().format(Long.parseLong(get_Response.getResult().getMonetary_value())));
        }else{
            setValue(mReferralsValue, "$0");
        }


        if(get_Response.getResult().getMessage().trim().length()>0) {
            mReferralTextMessage.setText(get_Response.getResult().getMessage());
        }else{
            setValueWithColor(mReferralTextMessage, getResources().getString(R.string.not_message_sender), getResources().getColor(R.color.light_gray_color));
        }


        //setValueWithoutColor(mDatevalue, AppUtils.parseDateToddMMyyyy(get_Response.getResult().getCreated()) + " @ " + AppUtils.parseDateToddMMyyyyTime(get_Response.getResult().getCreated()));
        String commentText= "<font color=#d3d3d3>"+AppUtils.parseDateToddMMyyyy(get_Response.getResult().getCreated())+  "</font>" + "<font color=#F15A2B>"+" @ " +"</font> <font color=#d3d3d3>" + AppUtils.parseDateToddMMyyyyTime(get_Response.getResult().getCreated())+"</font>";
        //setValueWithoutColor(mDatevalue, commentText);
        mDatevalue.setText(Html.fromHtml(commentText));

        setFileValue(get_Response.getResult());

    }
    /**
     * Setting files result on custom layout
     * @param result
     */
    public void setFileValue(ReceivedDetailsModel result){
        mLlFileLayout.removeAllViews();
        if(null!=result.getFiles() && result.getFiles().size()>0){
            for(final FileModel model:result.getFiles()){
                LayoutInflater mInflater;
                mInflater = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
                LinearLayout view = (LinearLayout) mInflater.inflate(R.layout.row_file_layout, null);
                ImageView iv=(ImageView) view.findViewById(R.id.iconTypeFile);

                iv.setBackgroundResource(AppUtils.getAttachmentType(model.getName()));
                TextView tv=(TextView) view.findViewById(R.id.filename_value);
                tv.setText(model.getName());

                view.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(model.getUrl()));
                        startActivity(browserIntent);
                    }
                });

                mLlFileLayout.addView(view);

            }
        }else{
            LayoutInflater mInflater;
            mInflater = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            LinearLayout view = (LinearLayout) mInflater.inflate(R.layout.row_file_layout, null);
            ImageView iv=(ImageView) view.findViewById(R.id.iconTypeFile);
            iv.setVisibility(View.GONE);
            TextView tv=(TextView) view.findViewById(R.id.filename_value);
            tv.setTextColor(getResources().getColor(R.color.gray_color));
            tv.setTextSize(16);
            tv.setTypeface(Typeface.DEFAULT_BOLD);
            tv.setText(getResources().getString(R.string.not_attachment));
            mLlFileLayout.addView(view);
        }



    }


    /**
     * Calculating address, as per business logic
     * @param result
     * @return
     */
    public String calculateAddress(ReceivedDetailsModel result){
        String toalAddress,address,city,state,zip,country="";
        if(null!=result.getAddress() && result.getAddress().trim().length()>0){
             address=result.getAddress() +"\n";

             city=null!=result.getCity() && result.getCity().trim().length()>0? result.getCity().trim() +"," : "";

             state=null!=result.getState_name() && result.getState_name().trim().length()>0? " "+ result.getState_name().trim() : "";

             zip=null!= result.getZip() && result.getZip().trim().length()>0? " "+ result.getZip().trim() + "," : "";

             country=null!=result.getCountry_name() && result.getCountry_name().trim().length()>0?  " "+result.getCountry_name().trim()  : "";

            toalAddress=address+city+state+zip+country;
        }else{
            city=null!=result.getCity() && result.getCity().trim().length()>0? result.getCity().trim() +"," : "";

            state=null!=result.getState_name() && result.getState_name().trim().length()>0? " "+ result.getState_name().trim() : "";

            zip=null!= result.getZip() && result.getZip().trim().length()>0? " "+ result.getZip().trim() + "," : "";

            country=null!=result.getCountry_name() && result.getCountry_name().trim().length()>0?  " "+result.getCountry_name().trim()  : "";
            toalAddress=city+state+zip+country;
        }


        return toalAddress;
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
        Value=Value.trim();
        if(null!=Value && !Value.equals("") ){
            View.setTextColor(getResources().getColor(R.color.light_gray_color));
            View.setText(Value);
        }else{
            View.setTextColor(getResources().getColor(R.color.light_gray_color));
            View.setText(getResources().getString(R.string.not_applicable));
        }

    }

    //Setting value on UI with color specific
    public void setValueWithoutColor(TextView View,String Value){

        if(null!=Value && !Value.equals("") ){
            View.setText(Value);
        }
    }

    //Setting value on UI with color specific
    public void setValueWithColor(TextView View,String Value,int colorid){

        if(null!=Value && !Value.equals("") ){
            View.setTextColor(colorid);
            View.setText(Value);
        }
    }

    /**
     * This method is use for get the request model for comment web service request
     * @return
     */
    private ReceivedReferralCommentModel getAddCommentRequestModel() throws Exception{
        ReceivedReferralCommentModel mReceivedReferralCommentModel = new ReceivedReferralCommentModel();
        mReceivedReferralCommentModel.setReferralId(mRefferalId);
        mReceivedReferralCommentModel.setDetailPage(WebServiceConstants.SENT_LIST_PAGE);
        mReceivedReferralCommentModel.setType(WebServiceConstants.SENT_LIST_PAGE);
        mReceivedReferralCommentModel.setComment(mEditTextMessageComment.getText().toString());
        return mReceivedReferralCommentModel;
    }

    /**
     * This method is use for get the request model for sent referral details
     * @return
     */
    private ReferralsDetailsRequestModel getRequestModel() throws Exception{
        ReferralsDetailsRequestModel mReferralsDetailsRequestModel = new ReferralsDetailsRequestModel();
        mReferralsDetailsRequestModel.setReferralId(mRefferalId);
        mReferralsDetailsRequestModel.setDetailPage(WebServiceConstants.SENT_LIST_PAGE);
        return mReferralsDetailsRequestModel;
    }




    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_referrel_details, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onClick(View view) {
        if(view == mBackButton){
            finish();
            overridePendingTransition(0, 0);
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
                        if (AppUtils.isOnline(SentReferrelDetailsActivity.this)) {
                            // mFooterProgressBar.setVisibility(View.VISIBLE);
                            //mProgressHUD = ProgressHUD.show(SentReferrelDetailsActivity.this, "", true, true);
                            //mProgressHUD.setCancelable(false);
                            try {
                                callWebserviceSecond();
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

    @Override
    protected void onResume() {
        super.onResume();
        registerReceiver(mNotificationBroadcast, new IntentFilter(
                ApplicationConstants.NOTIFY_DATAUPDATE_ACTION));
    }

    @Override
    protected void onPause() {
        super.onPause();
        try {
            unregisterReceiver(mNotificationBroadcast);
        } catch (Exception e) {
            e.printStackTrace();
        }
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
}
