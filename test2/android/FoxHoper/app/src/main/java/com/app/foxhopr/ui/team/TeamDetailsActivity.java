package com.app.foxhopr.ui.team;

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
import android.text.Html;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TextView;

import com.app.foxhopr.adapter.CommentListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.TeamDetailsRequestModel;
import com.app.foxhopr.services.CommentService;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.webservice.models.TeamDetailModel;
import com.app.foxhopr.webservice.models.TeamDetailsResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;
import com.squareup.picasso.Picasso;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class TeamDetailsActivity extends FragmentActivity implements  View.OnClickListener{
    private final String TAG="TeamDetailsActivity";
    private BroadcastReceiver mNotificationBroadcast;
    private ProgressHUD mProgressHUD;

    private ImageView mBackButton;
    private ImageView mEditButton;
    private String mRefferalId;

    private TextView mTextViewHeaderTitle;
    CommentListAdapter commentlistAdapter;

    private String mMemberId;
    private String mDetailsPage;

    //details data
    TeamDetailsResponseModel getResponse;

    private TextView mTextFirstName;
    private TextView mTextCompany;

    private TextView mEmail;
    private TextView mWebsite;
    private TextView mWebsite1;
    private TextView mMobile;
    private TextView mTelephone;
    private TextView mAddress;
    private TextView mLocation;
    private TextView mSkype;
    private TextView textViewreview;
    private ImageView mProfileImage;

    private LinearLayout llaboutme;
    private LinearLayout llcompanyDescription;
    private LinearLayout llService;
    private LinearLayout sociallink;

    private LinearLayout llskype;
    private LinearLayout llreview;

    private ImageView facebookimageView;
    private ImageView twitterimageView;
    private ImageView linkedinimageView;

    private TextView abouttextmessage;
    private TextView company_description_textmessage;
    private TextView servicetextmessage;

    private ScrollView mScrollViewTop;
    //service variable

    CommentService mBoundService;
    boolean mServiceBound = false;
    Intent intentService;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_team_details);
        mMemberId=getIntent().getStringExtra("member_id");
        mDetailsPage=getIntent().getStringExtra("details_page");
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

        mTextViewHeaderTitle=(TextView)findViewById(R.id.textViewHeaderTitle);
        mTextViewHeaderTitle.setVisibility(View.VISIBLE);
        mTextViewHeaderTitle.setText(getResources().getString(R.string.str_team_member_details));

        mTextFirstName =(TextView)findViewById(R.id.textViewItemRecivedName);
        mTextCompany =(TextView)findViewById(R.id.textViewItemSecivedName);

        mEmail=(TextView)findViewById(R.id.emailValue);
        mWebsite=(TextView)findViewById(R.id.websiteValue);
        mWebsite1=(TextView)findViewById(R.id.websiteValue1);
        mMobile=(TextView)findViewById(R.id.mobileNoValue);
        mTelephone=(TextView)findViewById(R.id.telephoneValue);
        mSkype=(TextView)findViewById(R.id.skypeValue);
        mAddress=(TextView)findViewById(R.id.locationValue);
        mLocation=(TextView)findViewById(R.id.gmtValue);
        textViewreview=(TextView)findViewById(R.id.textViewreview);
        mProfileImage=(ImageView)findViewById(R.id.profile_image);

        mScrollViewTop=(ScrollView)findViewById(R.id.scrollViewTop);
        llaboutme=(LinearLayout)findViewById(R.id.llaboutme);
        llcompanyDescription=(LinearLayout)findViewById(R.id.llcompanydescription);
        llService=(LinearLayout)findViewById(R.id.llservice);
        sociallink=(LinearLayout)findViewById(R.id.sociallink);

        llskype=(LinearLayout)findViewById(R.id.llskype);
        llreview=(LinearLayout)findViewById(R.id.llreview);

        abouttextmessage=(TextView)findViewById(R.id.abouttextmessage);
        company_description_textmessage=(TextView)findViewById(R.id.company_description_textmessage);
        servicetextmessage=(TextView)findViewById(R.id.servicetextmessage);

        facebookimageView=(ImageView)findViewById(R.id.facebookimageView);
        twitterimageView=(ImageView)findViewById(R.id.twitterimageView);
        linkedinimageView=(ImageView)findViewById(R.id.linkedinimageView);

        //Call the webservices
        try{
            if (AppUtils.isOnline(TeamDetailsActivity.this)) {
                mProgressHUD = ProgressHUD.show(TeamDetailsActivity.this,"", true,true);
                mProgressHUD.setCancelable(false);
                callWebservice();
            }
            else{
                ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
            }
        }catch (Exception ex){
            ex.printStackTrace();
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
     * THis method is use to call the received referral details webservice and get
     * successful  response or  error from server
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.TEAM_DETAILS_ACTION_NAME, WebServiceConstants.GET_TEAM_CONTROL_NAME, TeamDetailsActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getTeamDetailsRequest(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseTeamDetailsService(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * THis method is use to call the received referral details webservice and get
     * successful  response or  error from server
     */
    private void callWebserviceSecond() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.TEAM_DETAILS_ACTION_NAME, WebServiceConstants.GET_TEAM_CONTROL_NAME, TeamDetailsActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getTeamDetailsRequest(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseTeamDetailsServiceSecond(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        //ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                //mProgressHUD.dismiss();
               // ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }


    /**
     * This method is use for get the request model for received referral details
     * @return
     */
    private TeamDetailsRequestModel getRequestModel() throws Exception{
        TeamDetailsRequestModel mTeamDetailsRequestModel = new TeamDetailsRequestModel();
        mTeamDetailsRequestModel.setMemberId(mMemberId);
        return mTeamDetailsRequestModel;
    }

    /**
     * parse TeamDetails, getting from web services
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseTeamDetailsService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);
        Gson gson = new Gson();
        TeamDetailsResponseModel get_Response = gson.fromJson(responseStr, TeamDetailsResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null) {
                    setValuesONUI(get_Response);
                }
            } else {
                ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * parse TeamDetails, getting from web services
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseTeamDetailsServiceSecond(String responseStr) throws Exception {
        Log.e(TAG, responseStr);
        Gson gson = new Gson();
        TeamDetailsResponseModel get_Response = gson.fromJson(responseStr, TeamDetailsResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null) {
                    setValuesONUI(get_Response);
                }
            } else {
                //ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            //ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * Setting all values on UI after parsing
     * @param get_Response
     */
    public void setValuesONUI(final TeamDetailsResponseModel get_Response){
        String toNameStr ="";

        if(get_Response.getResult().getFname()!= null){

            toNameStr =get_Response.getResult().getFname();
            String s1 = toNameStr.substring(0, 1).toUpperCase();
            toNameStr = s1 + toNameStr.substring(1);

        }

        if(get_Response.getResult().getLname() != null ){
            String s1 = get_Response.getResult().getLname().substring(0, 1).toUpperCase();
            toNameStr =  toNameStr+" "+ s1 + get_Response.getResult().getLname().substring(1);
        }

        setValueWithoutColor(mTextFirstName, toNameStr);

        if(null!=get_Response.getResult().getProfession_name() && get_Response.getResult().getProfession_name().trim().length()>0) {
            setValueWithoutColor(mTextCompany, get_Response.getResult().getProfession_name() + ", " + get_Response.getResult().getCompany());
        }else if(null!=get_Response.getResult().getCompany() && get_Response.getResult().getCompany().trim().length()>0){
            setValueWithoutColor(mTextCompany, get_Response.getResult().getCompany());
        }

        setValue(mEmail, get_Response.getResult().getEmail());
        setValue(mWebsite, get_Response.getResult().getWebsite());
        setValue(mWebsite1, get_Response.getResult().getWebsite1());

        mEmail.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (null != get_Response.getResult().getEmail() && get_Response.getResult().getEmail().trim().length() > 0) {
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

        mWebsite1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String url = "";
                if (null != get_Response.getResult().getWebsite1() && get_Response.getResult().getWebsite1().trim().length() > 0) {
                    if (get_Response.getResult().getWebsite1().contains("HTTP://") || get_Response.getResult().getWebsite1().contains("http://")) {
                        url = get_Response.getResult().getWebsite1().trim();
                    } else {
                        url = "http://" + get_Response.getResult().getWebsite1().trim();
                    }
                    Log.i("tag", get_Response.getResult().getWebsite1());
                    Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                    startActivity(browserIntent);
                }
            }
        });

        setValueGrey(mMobile, get_Response.getResult().getMobile());
        setValueGrey(mTelephone, get_Response.getResult().getOffice_phone());
        setValueGrey(mLocation, get_Response.getResult().getTimezone_id());
        setValueGrey(mAddress, calculateAddress(get_Response.getResult()));

        if(null!=get_Response.getResult().getMember_profile_image()){
            Picasso.with(TeamDetailsActivity.this)
                    .load(get_Response.getResult().getMember_profile_image())
                    .resize(100, 100)
                    .placeholder(R.drawable.icon_user) // optional
                    .into(mProfileImage);
        }

        if(null!=get_Response.getResult().getSkype_id() && !get_Response.getResult().getSkype_id().equals("")){
            llskype.setVisibility(View.VISIBLE);
            setValueGrey(mSkype, get_Response.getResult().getSkype_id());
        }else{
            llskype.setVisibility(View.GONE);
        }
        boolean aboutme=false;
        if(null!=get_Response.getResult().getAboutme() && !get_Response.getResult().getAboutme().equals("") ){
            aboutme=true;
            llaboutme.setVisibility(View.VISIBLE);
            abouttextmessage.setText(get_Response.getResult().getAboutme());
        }else{
            llaboutme.setVisibility(View.GONE);
        }
        boolean company=false;
        if(null!=get_Response.getResult().getBusiness_description() && !get_Response.getResult().getBusiness_description().equals("") ){
            llcompanyDescription.setVisibility(View.VISIBLE);
            if(aboutme) {
                llcompanyDescription.findViewById(R.id.company_separator).setVisibility(View.VISIBLE);
            }else{
                llcompanyDescription.findViewById(R.id.company_separator).setVisibility(View.GONE);
            }
            company_description_textmessage.setText(get_Response.getResult().getBusiness_description());
            company=true;
        }else{
            llcompanyDescription.setVisibility(View.GONE);
        }

        if(null!=get_Response.getResult().getServices() && !get_Response.getResult().getServices().equals("")){
            llService.setVisibility(View.VISIBLE);
            servicetextmessage.setText(get_Response.getResult().getServices());
            if(aboutme && company) {
                llService.findViewById(R.id.service_separator).setVisibility(View.VISIBLE);
            }else if(aboutme && !company){
                llService.findViewById(R.id.service_separator).setVisibility(View.VISIBLE);
            }else if(!aboutme && company){
                llService.findViewById(R.id.service_separator).setVisibility(View.VISIBLE);
            }else if(!aboutme && !company){
                llService.findViewById(R.id.service_separator).setVisibility(View.GONE);
            }
        }else{
            llService.setVisibility(View.GONE);
        }

        if(null!=get_Response.getResult().getFacebook_profile_id() && !get_Response.getResult().getFacebook_profile_id().equals("")){
            facebookimageView.setVisibility(View.VISIBLE);
        }else{
            //facebookimageView.setVisibility(View.GONE);
            facebookimageView.setAlpha(0.5f);
        }

        if(null!=get_Response.getResult().getLinkedin_profile_id() && !get_Response.getResult().getLinkedin_profile_id().equals("")){
            linkedinimageView.setVisibility(View.VISIBLE);
        }else{
            //linkedinimageView.setVisibility(View.GONE);
            linkedinimageView.setAlpha(0.5f);

        }

        if(null!=get_Response.getResult().getTwitter_profile_id() && !get_Response.getResult().getTwitter_profile_id().equals("")){
            twitterimageView.setVisibility(View.VISIBLE);
        }else{
            //twitterimageView.setVisibility(View.GONE);
            twitterimageView.setAlpha(0.5f);

        }

        facebookimageView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String url = "";
                if (null != get_Response.getResult().getFacebook_profile_id() && get_Response.getResult().getFacebook_profile_id().trim().length() > 0) {
                    if (get_Response.getResult().getFacebook_profile_id().contains("HTTP") || get_Response.getResult().getFacebook_profile_id().contains("http")) {
                        url = get_Response.getResult().getFacebook_profile_id().trim();
                    } else {
                        url = "http://" + get_Response.getResult().getFacebook_profile_id().trim();
                    }
                    Log.i("tag", get_Response.getResult().getFacebook_profile_id());
                    Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                    startActivity(browserIntent);
                }
            }
        });

        twitterimageView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String url = "";
                if (null != get_Response.getResult().getTwitter_profile_id() && get_Response.getResult().getTwitter_profile_id().trim().length() > 0) {
                    if (get_Response.getResult().getTwitter_profile_id().contains("HTTP") || get_Response.getResult().getTwitter_profile_id().contains("http")) {
                        url = get_Response.getResult().getTwitter_profile_id().trim();
                    } else {
                        url = "http://" + get_Response.getResult().getTwitter_profile_id().trim();
                    }
                    Log.i("tag", get_Response.getResult().getTwitter_profile_id());
                    Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                    startActivity(browserIntent);
                }
            }
        });

        linkedinimageView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String url = "";
                if (null != get_Response.getResult().getLinkedin_profile_id() && get_Response.getResult().getLinkedin_profile_id().trim().length() > 0) {
                    if (get_Response.getResult().getLinkedin_profile_id().contains("HTTP") || get_Response.getResult().getLinkedin_profile_id().contains("http")) {
                        url = get_Response.getResult().getLinkedin_profile_id().trim();
                    } else {
                        url = "http://" + get_Response.getResult().getLinkedin_profile_id().trim();
                    }
                    Log.i("tag", get_Response.getResult().getLinkedin_profile_id());
                    Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                    startActivity(browserIntent);
                }
            }
        });

        //setting review dynamically
        setReviewValue(get_Response);
    }

    /**
     * Setting review result on custom layout
     * @param result
     */
    public void setReviewValue(TeamDetailsResponseModel result){
        llreview.removeAllViews();
        if(null!=result.getResult().getRating()){
            int ratingValue=Integer.parseInt(result.getResult().getRating());
            for(int i=0;i<ratingValue;i++){
                LayoutInflater mInflater;
                mInflater = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
                LinearLayout view = (LinearLayout) mInflater.inflate(R.layout.row_review_layout, null);
                ImageView iv=(ImageView) view.findViewById(R.id.iconrating);
                iv.setBackgroundResource(R.drawable.icon_fill_star);
                llreview.addView(view);

            }
            int remainingReview=5-ratingValue;
            for(int i=0;i<remainingReview;i++){
                LayoutInflater mInflater;
                mInflater = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
                LinearLayout view = (LinearLayout) mInflater.inflate(R.layout.row_review_layout, null);
                ImageView iv=(ImageView) view.findViewById(R.id.iconrating);
                iv.setBackgroundResource(R.drawable.icon_no_review);
                llreview.addView(view);
            }
            if(null!=result.getResult().getTotalReview()){
                String commentText= "<font color=#F15A2B>"+result.getResult().getTotalReview()+  "</font>" + " <font color=#d3d3d3>" + getResources().getString(R.string.str_reviews_text)+"</font>";
                textViewreview.setText(Html.fromHtml(commentText));
            }


        }
    }
    /**
     * Calculating address, as per business logic
     * @param result
     * @return
     */
    public String calculateAddress(TeamDetailModel result){
        String toalAddress,address,city,state,zip,country="";
        if(null!=result.getAddress() && result.getAddress().trim().length()>0){
            address=result.getAddress() +"\n";

            city=null!=result.getCity() && result.getCity().trim().length()>0? result.getCity().trim() +"," : "";

            state=null!=result.getState_name() && result.getState_name().trim().length()>0? " "+ result.getState_name().trim()+"," : "";

            zip=null!= result.getZipcode() && result.getZipcode().trim().length()>0? " "+ result.getZipcode().trim() + "," : "";

            country=null!=result.getCountry_name() && result.getCountry_name().trim().length()>0?  " "+result.getCountry_name().trim()  : "";

            //toalAddress=address+city+state+zip+country;
            toalAddress=address+city+zip+state+country;
        }else{
            city=null!=result.getCity() && result.getCity().trim().length()>0? result.getCity().trim() +"," : "";

            state=null!=result.getState_name() && result.getState_name().trim().length()>0? " "+ result.getState_name().trim() +",": "";

            zip=null!= result.getZipcode() && result.getZipcode().trim().length()>0? " "+ result.getZipcode().trim() + "," : "";

            country=null!=result.getCountry_name() && result.getCountry_name().trim().length()>0?  " "+result.getCountry_name().trim()  : "";
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
                        if (AppUtils.isOnline(TeamDetailsActivity.this)) {
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
