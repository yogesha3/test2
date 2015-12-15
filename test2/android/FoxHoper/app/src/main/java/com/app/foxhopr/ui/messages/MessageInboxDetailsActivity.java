/**
 * Activity display details for messages inbox,sent , sent archive, inbox archive
 * we are showing from, to
 * subject,content
 * and respective comments for the messages from all four sections
 */
package com.app.foxhopr.ui.messages;

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
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
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
import com.app.foxhopr.request.models.InboxMessageCommentModel;
import com.app.foxhopr.request.models.MessagesDetailsRequestModel;
import com.app.foxhopr.services.CommentService;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.Validation;
import com.app.foxhopr.webservice.models.FileModel;
import com.app.foxhopr.webservice.models.MessagesDetailsModel;
import com.app.foxhopr.webservice.models.MessagesDetailsResponseModel;
import com.app.foxhopr.webservice.models.ReferrelCommentResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class MessageInboxDetailsActivity extends FragmentActivity implements View.OnClickListener {

    private final String TAG="InboxDetailsActivity";
    private BroadcastReceiver mNotificationBroadcast;
    private ProgressHUD mProgressHUD;

    private ImageView mBackButton;
    private String mMessageId;
    private String mDetailPage;

    private TextView mTextViewHeaderTitle;
    CommentListAdapter commentlistAdapter;

    private TextView mTextViewItemFromName;
    private TextView mTextViewItemDate1;
    private TextView mTextViewItemToName;
    private LinearLayout mLlExpand;
    private LinearLayout mLlAttachmentDisplay;

    private TextView mTextviewsubjectValue;
    private TextView mTextmessage;
    private ImageView mImageViewExpand;

    private ListView mCommentListView;
    private LinearLayout mllPostComment;
    private TextView mDatevalue;

    private EditText mEditTextMessageComment;
    private LinearLayout mPostComment;
    private LinearLayout mLlFileLayout;
    private TextView mNocommentfound;

    private ScrollView mScrollViewTop;
    private boolean expand=true;

    //details data
    MessagesDetailsResponseModel getResponse;

    //service variable

    CommentService mBoundService;
    boolean mServiceBound = false;
    Intent intentService;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_message_inbox_details);
        mMessageId=getIntent().getStringExtra("message_id");
        mDetailPage=getIntent().getStringExtra("details_page");
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

    private void initView(){

        mBackButton=(ImageView)findViewById(R.id.imgvDrawer);
        mBackButton.setOnClickListener(this);

        mTextViewHeaderTitle=(TextView)findViewById(R.id.textViewHeaderTitle);
        mTextViewHeaderTitle.setVisibility(View.VISIBLE);
        mTextViewHeaderTitle.setText(getResources().getString(R.string.str_messages));

        mTextViewItemFromName=(TextView)findViewById(R.id.textViewItemFromName);
        mTextViewItemDate1=(TextView)findViewById(R.id.textViewItemDate1);
        mTextViewItemToName=(TextView)findViewById(R.id.textViewItemToName);
        mTextviewsubjectValue=(TextView)findViewById(R.id.textviewsubjectValue);
        mTextmessage=(TextView)findViewById(R.id.textmessage);

        mLlExpand=(LinearLayout)findViewById(R.id.llExpand);
        mLlAttachmentDisplay=(LinearLayout)findViewById(R.id.llAttachmentDisplay);

        mCommentListView=(ListView)findViewById(R.id.commentListView);
        mllPostComment=(LinearLayout)findViewById(R.id.llPostComment);
        mDatevalue=(TextView)findViewById(R.id.datevalue);
        mNocommentfound=(TextView)findViewById(R.id.nocommentfound);

        mCommentListView=(ListView)findViewById(R.id.commentListView);
        mPostComment=(LinearLayout)findViewById(R.id.llPostComment);
        mEditTextMessageComment=(EditText)findViewById(R.id.editTextMessageComment);

        mLlFileLayout=(LinearLayout)findViewById(R.id.llFileLayout);
        mScrollViewTop=(ScrollView)findViewById(R.id.scrollViewTop);
        mImageViewExpand=(ImageView)findViewById(R.id.imageViewExpand);

        //Call the webservices
        try{
            if (AppUtils.isOnline(MessageInboxDetailsActivity.this)) {
                mProgressHUD = ProgressHUD.show(MessageInboxDetailsActivity.this,"", true,true);
                mProgressHUD.setCancelable(false);
                callWebservice();
            }
            else{
                ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
            }
        }catch (Exception ex){
            ex.printStackTrace();
        }

        mPostComment.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    if (mEditTextMessageComment.getText().toString().length() > 0) {
                        if(checkValidation()) {
                            if (AppUtils.isOnline(MessageInboxDetailsActivity.this)) {
                                //CommentAlart.stop(ReferrelDetailsActivity.this);
                                ApplicationConstants.COMMENT_STATUS = false;
                                mProgressHUD = ProgressHUD.show(MessageInboxDetailsActivity.this, "", true, true);
                                mProgressHUD.setCancelable(false);
                                callAddCommentWebservice();
                            } else {
                                ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                            }
                        }
                    } else {
                        ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.not_comment_blanck));
                    }
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

        mLlExpand.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(expand) {
                    mTextViewItemToName.setSingleLine(false);
                    mImageViewExpand.setBackgroundResource(R.drawable.icon_up);
                    expand=false;
                }else{
                    mTextViewItemToName.setSingleLine(true);
                    mImageViewExpand.setBackgroundResource(R.drawable.icon_down);
                    expand=true;
                }
            }
        });

        applyValidationFilters();

    }

    private void applyValidationFilters() {
        mEditTextMessageComment.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValidComment(MessageInboxDetailsActivity.this, mEditTextMessageComment, mEditTextMessageComment);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
    }

    public boolean checkValidation(){

        boolean isAllFieldCorrect = true;
        if(mEditTextMessageComment.getText().toString().length() >350){
            isAllFieldCorrect = false;
            mEditTextMessageComment.requestFocus();
            mEditTextMessageComment.setBackgroundResource(R.drawable.bg_white_select_team_error);
            ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_comment_max_limit));
        }
        return  isAllFieldCorrect;
    }

    /**
     * THis method is use to call the inbox messages details webservice and get
     * successful  response or  error from server
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.MESSAGES_DETAILS_ACTION_NAME, WebServiceConstants.MESSAGE_COMPOSE_CONTROL, MessageInboxDetailsActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getMessagessReceivedDetailsRequest(getRequestModel(), new Callback<Response>() {
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
                        ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * THis method is use to call for getting all comment for selected inbox messages referral,
     * webservice and get successful  response or  error from server
     */
    private void callCommentWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.MESSAGES_COMMENT_ACTION_NAME, WebServiceConstants.MESSAGE_COMPOSE_CONTROL, MessageInboxDetailsActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getMessagessReceivedDetailsRequest(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseCommentListService(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
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
     * THis method is use to call for getting all comment for selected inbox messages referral,
     * webservice and get successful  response or  error from server
     */
    private void callCommentWebserviceSecond() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.MESSAGES_COMMENT_ACTION_NAME, WebServiceConstants.MESSAGE_COMPOSE_CONTROL, MessageInboxDetailsActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getMessagessReceivedDetailsRequest(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                //mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseCommentListServiceSecond(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        //ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
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

    /**
     * THis method is use to add comments
     */
    private void callAddCommentWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.MESSAGES_ADD_COMMENT_ACTION_NAME, WebServiceConstants.MESSAGE_COMPOSE_CONTROL, MessageInboxDetailsActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.MessagesAddCommentRequest(getAddCommentRequestModel(), new Callback<Response>() {
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
                                ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", comment_Response.getMessage().equals("") ? getString(R.string.wrng_str_no_record) : comment_Response.getMessage(), new OkPressed());
                                mEditTextMessageComment.setText("");
                                //callCommentWebservice();
                            } else {
                                mCommentListView.setVisibility(View.GONE);
                                mNocommentfound.setVisibility(View.VISIBLE);
                                ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", comment_Response.getMessage().equals("") ? getString(R.string.wrng_str_no_record) : comment_Response.getMessage());
                            }
                        } else {
                            ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
                        }
                    } else {
                        ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }



    public class OkPressed implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            try {
                if (AppUtils.isOnline(MessageInboxDetailsActivity.this)) {
                    //CommentAlart.start(ReferrelDetailsActivity.this);
                    ApplicationConstants.COMMENT_STATUS=true;
                    intentService = new Intent(MessageInboxDetailsActivity.this, CommentService.class);
                    startService(intentService);
                    bindService(intentService, mServiceConnection, Context.BIND_AUTO_CREATE);
                    callCommentWebservice();
                } else {
                    ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                }
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }

    /**
     * This method is use for get the request model for comment web service request
     * @return
     */
    private InboxMessageCommentModel getAddCommentRequestModel() throws Exception{
        InboxMessageCommentModel mInboxMessageCommentModel = new InboxMessageCommentModel();
        mInboxMessageCommentModel.setMessageId(mMessageId);
        mInboxMessageCommentModel.setDetailPage(mDetailPage);
        mInboxMessageCommentModel.setSendMailTo(getResponse.getResult().getSendMailTo());
        mInboxMessageCommentModel.setComment(mEditTextMessageComment.getText().toString());
        return mInboxMessageCommentModel;
    }
    /**
     * This method is use for get the request model for inbox message details
     * @return
     */
    private MessagesDetailsRequestModel getRequestModel() throws Exception{
        MessagesDetailsRequestModel mMessagesDetailsRequestModel = new MessagesDetailsRequestModel();
        mMessagesDetailsRequestModel.setMessageId(mMessageId);
        mMessagesDetailsRequestModel.setDetailPage(mDetailPage);
        return mMessagesDetailsRequestModel;
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
        MessagesDetailsResponseModel get_Response = gson.fromJson(responseStr, MessagesDetailsResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null) {
                    setValuesONUI(get_Response);
                }
            } else {
                ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", get_Response.getMessage().equals("") ? getString(R.string.wrng_str_no_record) : get_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
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
            ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * parse Comments list getting from comment web service
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseCommentListServiceSecond(String responseStr) throws Exception {
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
            //ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    private void parseAddComment(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        ReferrelCommentResponseModel comment_Response = gson.fromJson(responseStr, ReferrelCommentResponseModel.class);

        if (comment_Response != null) {
            //comment_Response = comment_Response;

            if (comment_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", comment_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): comment_Response.getMessage());
                mEditTextMessageComment.setText("");
                callCommentWebservice();
            } else {
                mCommentListView.setVisibility(View.GONE);
                mNocommentfound.setVisibility(View.VISIBLE);
                ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", comment_Response.getMessage().equals("") ? getString(R.string.wrng_str_no_record) : comment_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(MessageInboxDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * Setting comment on UI, when getting it from background
     * @param comment_Response
     */
    public void setCommentONUI(ReferrelCommentResponseModel comment_Response){
        //mCommentListView
        if(null!=commentlistAdapter && comment_Response.getResult().size()>commentlistAdapter.getCount()){
            commentlistAdapter=new CommentListAdapter(MessageInboxDetailsActivity.this,comment_Response.getResult(),null);
            mCommentListView.setAdapter(commentlistAdapter);
            //commentlistAdapter.notifyDataSetChanged();
            if(comment_Response.getResult().size()>0) {
                mNocommentfound.setVisibility(View.GONE);
                mCommentListView.setVisibility(View.VISIBLE);
                //mCommentListView.setSelection(comment_Response.getResult().size() - 1);
            }
        }else if(null==commentlistAdapter){
            commentlistAdapter = new CommentListAdapter(MessageInboxDetailsActivity.this, comment_Response.getResult(), null);
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
    public void setValuesONUI(final MessagesDetailsResponseModel get_Response ){
        /*private TextView mTextViewItemFromName;
        private TextView mTextViewItemDate1;
        private TextView mTextViewItemToName;
        private LinearLayout mLlExpand;
        private LinearLayout mLlAttachmentDisplay;

        private TextView mTextviewsubjectValue;
        private TextView mTextmessage;*/

        String toNameStr ="";

        if(get_Response.getResult().getUser_name()!= null){

            toNameStr =get_Response.getResult().getUser_name();
            String s1 = toNameStr.substring(0, 1).toUpperCase();
            toNameStr = s1 + toNameStr.substring(1);

        }
        mTextViewItemFromName.setText(toNameStr);
        mTextViewItemDate1.setText(AppUtils.parseDateToddMMyyyy(get_Response.getResult().getCreated()));
        mTextViewItemToName.setText("To"+ " "+ get_Response.getResult().getReceiversName());
        mTextviewsubjectValue.setText(get_Response.getResult().getSubject());
        //mImageViewExpand
        if(null!=mTextViewItemToName && mTextViewItemToName.getText().toString().contains(",")){
            mImageViewExpand.setVisibility(View.VISIBLE);
        }else{
            mImageViewExpand.setVisibility(View.GONE);
        }

        if(null!=get_Response.getResult().getMessage_type() && (get_Response.getResult().getMessage_type().equals("message_comment") || get_Response.getResult().getMessage_type().equals("referral_comment")) ){
            String content=get_Response.getResult().getContent();
            content=content.replaceAll("&lt;br/&gt;","");
            content=content.replaceAll("\r\n        ","\n");

           // mTextmessage.setText(Html.fromHtml(content));
            mTextmessage.setText(content);
        } else {
            mTextmessage.setText(get_Response.getResult().getContent());
        }



        //setting files value on UI
        setFileValue(get_Response.getResult());
    }

    /**
     * Setting files result on custom layout
     * @param result
     */
    public void setFileValue(MessagesDetailsModel result){
        mLlFileLayout.removeAllViews();
        if(null!=result.getFiles() && result.getFiles().size()>0){
            mLlAttachmentDisplay.setVisibility(View.VISIBLE);
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
            mLlAttachmentDisplay.setVisibility(View.GONE);
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
                        if (AppUtils.isOnline(MessageInboxDetailsActivity.this)) {
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
