/**
 * Displaying details of a contacts.
 * show a edit icon to edit the details
 */
package com.app.foxhopr.ui.contacts;

import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.ServiceConnection;
import android.net.Uri;
import android.os.Bundle;
import android.os.IBinder;
import android.support.v4.app.FragmentActivity;
import android.util.Log;
import android.view.View;
import android.widget.ImageView;
import android.widget.ScrollView;
import android.widget.TextView;

import com.app.foxhopr.adapter.CommentListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.ContactDetailsRequestModel;
import com.app.foxhopr.services.CommentService;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.webservice.models.ContactDetailModel;
import com.app.foxhopr.webservice.models.ContactDetailsResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class ContactDetilsActivity extends FragmentActivity implements View.OnClickListener{
    private final String TAG="ContactDetilsActivity";
    private BroadcastReceiver mNotificationBroadcast;
    private ProgressHUD mProgressHUD;

    private ImageView mBackButton;
    private ImageView mEditButton;
    private String mContactID;

    private TextView mTextViewHeaderTitle;
    CommentListAdapter commentlistAdapter;

    //details data
    ContactDetailsResponseModel getResponse;

    private TextView mTextFirstName;
    private TextView mTextCompany;

    private TextView mEmail;
    private TextView mWebsite;
    private TextView mMobile;
    private TextView mTelephone;
    private TextView mAddress;

    //Edit contact
    private String responseStr;
    private ScrollView mScrollViewTop;

    //service variable

    CommentService mBoundService;
    boolean mServiceBound = false;
    Intent intentService;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_contact_detils);

        mContactID=getIntent().getStringExtra("contact_id");
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
        mEditButton=(ImageView)findViewById(R.id.editView);
        mBackButton.setOnClickListener(this);
        mEditButton.setOnClickListener(this);

        mTextViewHeaderTitle=(TextView)findViewById(R.id.textViewHeaderTitle);
        mTextViewHeaderTitle.setVisibility(View.VISIBLE);
        mTextViewHeaderTitle.setText(getResources().getString(R.string.str_details_a_contact));

        mTextFirstName =(TextView)findViewById(R.id.textViewItemRecivedName);
        mTextCompany =(TextView)findViewById(R.id.textViewItemSecivedName);

        mEmail=(TextView)findViewById(R.id.emailValue);
        mWebsite=(TextView)findViewById(R.id.websiteValue);
        mMobile=(TextView)findViewById(R.id.mobileNoValue);
        mTelephone=(TextView)findViewById(R.id.telephoneValue);
        mAddress=(TextView)findViewById(R.id.locationValue);

        mScrollViewTop=(ScrollView)findViewById(R.id.scrollViewTop);

        //Call the webservices
        try{
            if (AppUtils.isOnline(ContactDetilsActivity.this)) {
                mProgressHUD = ProgressHUD.show(ContactDetilsActivity.this,"", true,true);
                mProgressHUD.setCancelable(false);
                callWebservice();
            }
            else{
                ErrorMsgDialog.showErrorAlert(ContactDetilsActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
            }
        }catch (Exception ex){
            ex.printStackTrace();
        }

    }

    /**
     * THis method is use to call the contact details webservice and get
     * successful  response or  error from server
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.CONTACT_DETAILS_ACTION_NAME, WebServiceConstants.ADD_CONTACT_CONTROL_NAME, ContactDetilsActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getContactDetailsRequest(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseContactDetails(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(ContactDetilsActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(ContactDetilsActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * THis method is use to call the contact details webservice and get
     * successful  response or  error from server
     */
    private void callWebserviceSecond() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.CONTACT_DETAILS_ACTION_NAME, WebServiceConstants.ADD_CONTACT_CONTROL_NAME, ContactDetilsActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getContactDetailsRequest(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseContactDetailsSecond(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        //ErrorMsgDialog.showErrorAlert(ContactDetilsActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                //mProgressHUD.dismiss();
                //ErrorMsgDialog.showErrorAlert(ContactDetilsActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }
    /**
     * This method is use for get the request model for contact details
     * @return
     */
    private ContactDetailsRequestModel getRequestModel() throws Exception{
        ContactDetailsRequestModel mContactDetailsRequestModel = new ContactDetailsRequestModel();
        mContactDetailsRequestModel.setContactId(mContactID);
        return mContactDetailsRequestModel;
    }

    /**
     * parse contactDetails, getting from web services
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseContactDetails(String responseStr) throws Exception {
        Log.e(TAG, responseStr);
        this.responseStr=responseStr;
        Gson gson = new Gson();
        ContactDetailsResponseModel get_Response = gson.fromJson(responseStr, ContactDetailsResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null) {
                    setValuesONUI(get_Response);
                }
            } else {
                ErrorMsgDialog.showErrorAlert(ContactDetilsActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(ContactDetilsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * parse contactDetails, getting from web services
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseContactDetailsSecond(String responseStr) throws Exception {
        Log.e(TAG, responseStr);
        this.responseStr=responseStr;
        Gson gson = new Gson();
        ContactDetailsResponseModel get_Response = gson.fromJson(responseStr, ContactDetailsResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null) {
                    setValuesONUI(get_Response);
                }
            } else {
                finish();
                //ErrorMsgDialog.showErrorAlert(ContactDetilsActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            //ErrorMsgDialog.showErrorAlert(ContactDetilsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * Setting all values on UI after parsing
     * @param get_Response
     */
    public void setValuesONUI(final ContactDetailsResponseModel get_Response){

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
                String url = "";
                if (null != get_Response.getResult().getWebsite() && get_Response.getResult().getWebsite().trim().length() > 0) {
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
        setValueGrey(mAddress, calculateAddress(get_Response.getResult()));
    }
    /**
     * Calculating address, as per business logic
     * @param result
     * @return
     */
    public String calculateAddress(ContactDetailModel result){
        String toalAddress,address,city,state,zip,country="";
        if(null!=result.getAddress() && result.getAddress().trim().length()>0){
            address=result.getAddress() +"\n";

            city=null!=result.getCity() && result.getCity().trim().length()>0? result.getCity().trim() +"," : "";

            state=null!=result.getState_name() && result.getState_name().trim().length()>0? " "+ result.getState_name().trim() +",": "";

            zip=null!= result.getZip() && result.getZip().trim().length()>0? " "+ result.getZip().trim() + "," : "";

            country=null!=result.getCountry_name() && result.getCountry_name().trim().length()>0?  " "+result.getCountry_name().trim()  : "";
            //City, ZIP Code, State, Country
            //toalAddress=address+city+state+zip+country;
            toalAddress=address+city+zip+state+country;
        }else{
            city=null!=result.getCity() && result.getCity().trim().length()>0? result.getCity().trim() +"," : "";

            state=null!=result.getState_name() && result.getState_name().trim().length()>0? " "+ result.getState_name().trim() +",": "";

            zip=null!= result.getZip() && result.getZip().trim().length()>0? " "+ result.getZip().trim() + "," : "";

            country=null!=result.getCountry_name() && result.getCountry_name().trim().length()>0?  " "+result.getCountry_name().trim()  : "";
            //toalAddress=city+state+zip+country;
            toalAddress=city+zip+state+country;
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

    @Override
    public void onClick(View view) {
        if(view == mBackButton){
            finish();
            overridePendingTransition(0, 0);
        }if(view == mEditButton){
            //finish();
            //overridePendingTransition(0, 0);
            if(null!=getResponse && getResponse.getCode().equals("200")) {
                Intent editReferral = new Intent(ContactDetilsActivity.this, ContactEditActivity.class);
                editReferral.putExtra(ApplicationConstants.RECEIVED_REFERRAL_EDIT_ACTION, responseStr);
                startActivityForResult(editReferral, ApplicationConstants.RESULT_CODE);
            }

        }
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == ApplicationConstants.RESULT_CODE) { // Please, use a final int instead of hardcoded
            // int value
            if (resultCode == RESULT_OK) {
                finish();
            }

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
                        if (AppUtils.isOnline(ContactDetilsActivity.this)) {
                            // mFooterProgressBar.setVisibility(View.VISIBLE);
                            //mProgressHUD = ProgressHUD.show(ReferrelDetailsActivity.this, "", true, true);
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
