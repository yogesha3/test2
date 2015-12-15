package com.app.foxhopr.ui.Account;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.support.v4.app.FragmentActivity;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.TeamDetailsRequestModel;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.MutiPartRequest;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.Validation;
import com.app.foxhopr.webservice.models.AttachmentsListBean;
import com.app.foxhopr.webservice.models.CountryListInnerModel;
import com.app.foxhopr.webservice.models.SendReferralsResponseModel;
import com.app.foxhopr.webservice.models.StateListInnerModel;
import com.app.foxhopr.webservice.models.TeamDetailModel;
import com.app.foxhopr.webservice.models.TeamDetailsResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;
import com.squareup.picasso.Picasso;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class ProfileEditActivity extends FragmentActivity implements View.OnClickListener{
    private static String TAG="ProfileEditActivity";
    private ImageView mBackButton;
    private String mStringResponse;

    private TextView mTxtvSendReferrals;
    private LinearLayout mLlProfileImage;
    private TextView mTextFirstName;
    private TextView mTextCompany;

    private TextView mEmail;
    private TextView mAddress;
    private TextView mLocation;
    private ImageView mProfileImage;
    private SharedPreference mPreference;
    private TeamDetailsResponseModel mGetResponse;

    //previous
    private LinearLayout mlLNext;
    private EditText mEdtWebsite1;
    private EditText mEdtWebsite2;
    private EditText mEdtAddress;
    private EditText mEdtCity;
    private EditText mEdtOfficePhoneNo;
    private EditText mEdtMobileNo;

    private EditText mEdtTwitterId;
    private EditText mEdtFacebookId;
    private EditText mEdtLinkedinId;
    private EditText mEdtSkypeId;
    private EditText mEdtAboutMe;
    private EditText mEdtCompanyDesc;
    private EditText mEdtServices;

    private String strWebSite1 ="";
    private String strWebSite2 ="";
    private String strAddress ="";
    private String strCity ="";
    private String strOfficePhoneNo ="";
    private String strMobilePhoneNo ="";
    private String strTwitterId ="";
    private String strFacebookId ="";
    private String strLinkedinId ="";
    private String strSkypeId ="";
    private String strAboutUs ="";
    private String strCompDesc ="";
    private String strServices ="";
    private ArrayList<AttachmentsListBean> attachmentListArr;
    private ProgressHUD mProgressHUD;
    private Uri mImageCaptureUri;

    private ScrollView scrollView;

    private ArrayList<StateListInnerModel> stateListArr;
    private List<String> stateList = new ArrayList<String>();


    private ArrayList<CountryListInnerModel> countryListArr;
    private List<String> countryList = new ArrayList<String>();
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profile_edit);
        initView();
        attachmentListArr = new ArrayList<AttachmentsListBean>();
    }
    //initiating the view
    public void initView(){
        mPreference=new SharedPreference();
        mBackButton=(ImageView)findViewById(R.id.imgvDrawer);
        mBackButton.setOnClickListener(this);

        mTxtvSendReferrals	=	(TextView)findViewById(R.id.textViewHeaderTitle);
        mTxtvSendReferrals.setVisibility(View.VISIBLE);
        mTxtvSendReferrals.setText(getResources().getString(R.string.str_edit_profile));

        mTextFirstName =(TextView)findViewById(R.id.textViewItemRecivedName);
        mTextCompany =(TextView)findViewById(R.id.textViewJobTitle);

        mLlProfileImage =(LinearLayout)findViewById(R.id.llProfileImage);

        mEmail=(TextView)findViewById(R.id.textViewEmail);
        mAddress=(TextView)findViewById(R.id.textViewLocation);
        mLocation=(TextView)findViewById(R.id.textViewTimeZone);
        mProfileImage=(ImageView)findViewById(R.id.profileImageView);


        mEdtCity			=	(EditText)findViewById(R.id.editTextCityCreateNewContact);
        mEdtAddress	=	(EditText)findViewById(R.id.editTextAddressCreateNewContact);
        mEdtMobileNo	=	(EditText)findViewById(R.id.editTextMobileCreateNewContact);
        mEdtOfficePhoneNo	=	(EditText)findViewById(R.id.editTextOfficePhoneCreateNewContact);
        mEdtWebsite1	=	(EditText)findViewById(R.id.editTextWebsite1);
        mEdtWebsite2	=	(EditText)findViewById(R.id.editTextWebsite2);


        mEdtTwitterId	=	(EditText)findViewById(R.id.editTexxTwitterId);
        mEdtFacebookId	=	(EditText)findViewById(R.id.editTexxFacebookId);
        mEdtLinkedinId	=	(EditText)findViewById(R.id.editTexxLinkedinId);
        mEdtSkypeId	=	(EditText)findViewById(R.id.editTexxSkypeId);
        mEdtAboutMe	=	(EditText)findViewById(R.id.editTextAboutMe);
        mEdtCompanyDesc	=	(EditText)findViewById(R.id.editTextCompanyDescription);
        mEdtServices	=	(EditText)findViewById(R.id.editTextServices);
        scrollView=(ScrollView)findViewById(R.id.scrollView);

        mlLNext	=	(LinearLayout)findViewById(R.id.llUpdate);
        mlLNext.setOnClickListener(this);
        mLlProfileImage.setOnClickListener(this);

        try{
            if (AppUtils.isOnline(ProfileEditActivity.this)) {
                mProgressHUD = ProgressHUD.show(ProfileEditActivity.this,"", true,true);
                mProgressHUD.setCancelable(false);
                callWebservice();
            }
            else{
                ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
            }
        }catch (Exception ex){
            ex.printStackTrace();
        }
    }
    //set data on UI
    public void setDataOnUI(){
        if(null!=mGetResponse) {
            //setting vaule on edit text
            mEdtCity.setText(mGetResponse.getResult().getCity());
            mEdtAddress.setText(mGetResponse.getResult().getAddress());
            mEdtMobileNo.setText(mGetResponse.getResult().getMobile());
            mEdtOfficePhoneNo.setText(mGetResponse.getResult().getOffice_phone());
            mEdtWebsite1.setText(mGetResponse.getResult().getWebsite());
            mEdtWebsite2.setText(mGetResponse.getResult().getWebsite1());
            mEdtWebsite2.setText(mGetResponse.getResult().getWebsite1());
            mEdtTwitterId.setText(mGetResponse.getResult().getTwitter_profile_id());
            mEdtFacebookId.setText(mGetResponse.getResult().getFacebook_profile_id());
            mEdtLinkedinId.setText(mGetResponse.getResult().getLinkedin_profile_id());
            mEdtSkypeId.setText(mGetResponse.getResult().getSkype_id());
            mEdtAboutMe.setText(mGetResponse.getResult().getAboutme());
            mEdtCompanyDesc.setText(mGetResponse.getResult().getBusiness_description());
            mEdtServices.setText(mGetResponse.getResult().getServices());

            String toNameStr ="";

            if(mGetResponse.getResult().getFname()!= null){

                toNameStr =mGetResponse.getResult().getFname();
                String s1 = toNameStr.substring(0, 1).toUpperCase();
                toNameStr = s1 + toNameStr.substring(1);

            }

            if(mGetResponse.getResult().getLname() != null ){
                String s1 = mGetResponse.getResult().getLname().substring(0, 1).toUpperCase();
                toNameStr =  toNameStr+" "+ s1 + mGetResponse.getResult().getLname().substring(1);
            }

            setValueWithoutColor(mTextFirstName, toNameStr);

            if(null!=mGetResponse.getResult().getProfession_name() && mGetResponse.getResult().getProfession_name().trim().length()>0) {
                setValueWithoutColor(mTextCompany, mGetResponse.getResult().getProfession_name() + ", " + mGetResponse.getResult().getCompany());
            }else if(null!=mGetResponse.getResult().getCompany() && mGetResponse.getResult().getCompany().trim().length()>0){
                setValueWithoutColor(mTextCompany, mGetResponse.getResult().getCompany());
            }
            //Adding after discusstion wroking in ios
            mEmail.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if (null != mGetResponse.getResult().getEmail() && mGetResponse.getResult().getEmail().trim().length() > 0) {
                        final Intent emailIntent = new Intent(Intent.ACTION_SEND);
                        emailIntent.setType("text/plain");
                        emailIntent.putExtra(Intent.EXTRA_EMAIL, new String[]{mGetResponse.getResult().getEmail()});
                        startActivity(Intent.createChooser(emailIntent, ""));
                    }
                }
            });

            setValue(mEmail, mGetResponse.getResult().getEmail());
            setValueGrey(mLocation, mGetResponse.getResult().getTimezone_id());
            setValueGrey(mAddress, calculateAddress(mGetResponse.getResult()));

            if(null!=mGetResponse.getResult().getMember_profile_image()){
                Picasso.with(ProfileEditActivity.this)
                        .load(mGetResponse.getResult().getMember_profile_image())
                        .resize(100, 100)
                        .placeholder(R.drawable.icon_user) // optional
                        .into(mProfileImage);
            }


        }
        applyValidationFilters();
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

    /**
     * Calculating address, as per business logic
     * @param result
     * @return
     */
    public String calculateAddress(TeamDetailModel result){
        String toalAddress,state,zip,country="";

            state=null!=result.getState_name() && result.getState_name().trim().length()>0? " "+ result.getState_name().trim() +"," : "";

            zip=null!= result.getZipcode() && result.getZipcode().trim().length()>0? " "+ result.getZipcode().trim() + "," : "";

            country=null!=result.getCountry_name() && result.getCountry_name().trim().length()>0?  " "+result.getCountry_name().trim()  : "";
            toalAddress=zip+state+country;


        return toalAddress;
    }

    //run time apply filter of fields
    public void applyValidationFilters(){
        mEdtOfficePhoneNo.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValid(ProfileEditActivity.this, mEdtOfficePhoneNo, mEdtOfficePhoneNo);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
        mEdtMobileNo.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInput(ProfileEditActivity.this, mEdtMobileNo, mEdtMobileNo);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });

        mEdtWebsite1.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInput(ProfileEditActivity.this, mEdtWebsite1, mEdtWebsite1);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });

        mEdtWebsite2.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInput(ProfileEditActivity.this, mEdtWebsite2, mEdtWebsite2);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
    }

    @Override
    public void onClick(View view) {
        if(view==mBackButton){
            finish();
            overridePendingTransition(0, 0);
        }else if (view== mLlProfileImage){
            LayoutInflater mInflater = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            final View innerView = mInflater.inflate(R.layout.layout_take_image_option, null, false);
            final FrameLayout searchFrameLayout = (FrameLayout)findViewById(R.id.referralsSearchViewPager);
            searchFrameLayout.addView(innerView);
            overridePendingTransition(0, 0);
            LinearLayout llLocal = (LinearLayout) innerView.findViewById(R.id.llLocal);
            LinearLayout llGlobal = (LinearLayout) innerView.findViewById(R.id.llGlobal);
            LinearLayout llCancel = (LinearLayout) innerView.findViewById(R.id.llCancel);

            llCancel.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    searchFrameLayout.removeAllViews();
                }
            });
            llLocal.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Intent actionIntent = new Intent(Intent.ACTION_PICK, android.provider.MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
                    startActivityForResult(actionIntent, ApplicationConstants.GALLERY_PIC);
                    searchFrameLayout.removeAllViews();
                }
            });
            llGlobal.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    AppUtils.fixOrientation(ProfileEditActivity.this);
                    Intent takePictureIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
                    String APPLICATION_ROOT_PATH = Environment.getExternalStorageDirectory() + "/Foxhopr_Mobile/";
                    File mfile = new File(APPLICATION_ROOT_PATH);
                    if(mfile.exists()){
                        try {
                            File dir = new File(APPLICATION_ROOT_PATH);
                            if (!dir.exists())
                                dir.mkdir();
                        } catch (Exception e) {
                            // TODO: handle exception
                            e.printStackTrace();
                        }

                    }
                    if (takePictureIntent.resolveActivity(getPackageManager()) != null) {
                        mImageCaptureUri = Uri.fromFile(new File(APPLICATION_ROOT_PATH +"/"+ "obs_image_temp.jpg"));
                        takePictureIntent.putExtra(MediaStore.EXTRA_OUTPUT, mImageCaptureUri);
                        startActivityForResult(takePictureIntent, ApplicationConstants.CAMERA_PIC);
                    }
                    searchFrameLayout.removeAllViews();
                }
            });


        }else if(view==mlLNext){
            strWebSite1	=	mEdtWebsite1.getText().toString().trim();
            strWebSite2	=	mEdtWebsite2.getText().toString().trim();
            strAddress	=	mEdtAddress.getText().toString().trim();
            strCity	=	mEdtCity.getText().toString().trim();
            strOfficePhoneNo	=	mEdtOfficePhoneNo.getText().toString().trim();
            strMobilePhoneNo	=	mEdtMobileNo.getText().toString().trim();
            strTwitterId	=	mEdtTwitterId.getText().toString().trim();
            strFacebookId	=	mEdtFacebookId.getText().toString().trim();
            strLinkedinId	=	mEdtLinkedinId.getText().toString().trim();
            strSkypeId	=	mEdtSkypeId.getText().toString().trim();
            strAboutUs	=	mEdtAboutMe.getText().toString().trim();
            strCompDesc	=	mEdtCompanyDesc.getText().toString().trim();
            strServices	=	mEdtServices.getText().toString().trim();

            if (checkValidation()) {

                mEdtWebsite1.setBackgroundResource(R.drawable.bg_select_team);
                mEdtWebsite2.setBackgroundResource(R.drawable.bg_select_team);
                mEdtAddress.setBackgroundResource(R.drawable.bg_select_team);
                mEdtCity.setBackgroundResource(R.drawable.bg_select_team);
                mEdtOfficePhoneNo.setBackgroundResource(R.drawable.bg_select_team);
                mEdtMobileNo.setBackgroundResource(R.drawable.bg_select_team);
                mEdtTwitterId.setBackgroundResource(R.drawable.bg_select_team);
                mEdtFacebookId.setBackgroundResource(R.drawable.bg_select_team);
                mEdtSkypeId.setBackgroundResource(R.drawable.bg_select_team);
                mEdtLinkedinId.setBackgroundResource(R.drawable.bg_select_team);
                mEdtAboutMe.setBackgroundResource(R.drawable.bg_select_team);
                mEdtCompanyDesc.setBackgroundResource(R.drawable.bg_select_team);
                mEdtServices.setBackgroundResource(R.drawable.bg_select_team);
                //Call the web service

                try{
                    if (AppUtils.isOnline(ProfileEditActivity.this)) {
                        new ImageUploadTask().execute();
                    }
                    else{
                        ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                    }
                }catch (Exception ex){
                    ex.printStackTrace();
                }
            }
        }


    }

    /**
     * Method to check the validation on the page
     * @return
     */
    private boolean checkValidation() {
        boolean isAllFieldCorrect = true;


        if (strOfficePhoneNo.length() == 0) {
            isAllFieldCorrect = false;

            if (strOfficePhoneNo.length() == 0 ) {
                mEdtOfficePhoneNo.requestFocus();
                Validation.hasInputValid(ProfileEditActivity.this, mEdtOfficePhoneNo, mEdtOfficePhoneNo);
            }
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_empty_requried_field));

        }else if(strCity.length() >0 && strCity.length() >35) {
            mEdtAddress.setBackgroundResource(R.drawable.bg_select_team);
            isAllFieldCorrect = false;
            mEdtCity.requestFocus();
            mEdtCity.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_city_max_limit));
        }else if(strCity.length() >0 &&  !Validation.isValidString(strCity)) {
            mEdtAddress.setBackgroundResource(R.drawable.bg_select_team);
            isAllFieldCorrect = false;
            mEdtCity.requestFocus();
            mEdtCity.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_city_invalid));
        }else if(strAddress.length() >0 && strAddress.length() >60){
            isAllFieldCorrect = false;
            mEdtAddress.requestFocus();
            mEdtAddress.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_address_max_limit));
        }else if (strAddress.length() >0 && !Validation.isValidAddressFormat(strAddress))  {

            isAllFieldCorrect = false;
            mEdtAddress.requestFocus();
            mEdtAddress.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_address_invalid));
        }else if(strOfficePhoneNo.length() >15){
            mEdtCity.setBackgroundResource(R.drawable.bg_select_team);
            mEdtOfficePhoneNo.requestFocus();
            isAllFieldCorrect = false;
            mEdtOfficePhoneNo.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_officePhone_max_limit));
        }else if(strMobilePhoneNo.length() >0 && strMobilePhoneNo.length() >15){
            mEdtOfficePhoneNo.setBackgroundResource(R.drawable.bg_select_team);
            mEdtMobileNo.requestFocus();
            isAllFieldCorrect = false;
            mEdtMobileNo.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_mobile_phone_max_limit));
        }else if(strWebSite1.length() >0 && !AppUtils.isValidUrl(strWebSite1)){
            mEdtWebsite1.requestFocus();
            isAllFieldCorrect = false;
            mEdtWebsite1.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_invalid_url));
        }else if(strWebSite2.length() >0 && !AppUtils.isValidUrl(strWebSite2)){
            mEdtWebsite2.requestFocus();
            isAllFieldCorrect = false;
            mEdtWebsite2.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_invalid_url));
        }else if(strTwitterId.length() >0 && !AppUtils.isValidUrl(strTwitterId)){
            mEdtTwitterId.requestFocus();
            isAllFieldCorrect = false;
            mEdtTwitterId.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_invalid_url));
        }else if(strFacebookId.length() >0 && !AppUtils.isValidUrl(strFacebookId)){
            mEdtFacebookId.requestFocus();
            isAllFieldCorrect = false;
            mEdtFacebookId.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_invalid_url));
        }else if(strLinkedinId.length() >0 && !AppUtils.isValidUrl(strLinkedinId)){
            mEdtLinkedinId.requestFocus();
            isAllFieldCorrect = false;
            mEdtLinkedinId.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_invalid_url));
        }else if(strAboutUs.length() >0 && strAboutUs.length() >500){
            isAllFieldCorrect = false;
            mEdtAboutMe.requestFocus();
            mEdtAboutMe.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_about_me_max_limit));
        }else if(strCompDesc.length() >0 && strCompDesc.length() >500){
            isAllFieldCorrect = false;
            mEdtCompanyDesc.requestFocus();
            mEdtCompanyDesc.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_company_description_max_limit));
        }else if(strServices.length() >0 && strServices.length() >500){
            isAllFieldCorrect = false;
            mEdtServices.requestFocus();
            mEdtServices.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_services_max_limit));
        }
        return isAllFieldCorrect;
    }


    //on press ok button user will redirect to referrallist
    public class EditFinish implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            try {
                Intent resultIntent = new Intent();
                setResult(RESULT_OK, resultIntent);
                finish();
                overridePendingTransition(0,0);
            } catch (Exception ex) {
                ex.printStackTrace();
            }
        }
    }


    /**
     * THis method is use to call the received referral details webservice and get
     * successful  response or  error from server
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.TEAM_DETAILS_ACTION_NAME, WebServiceConstants.GET_TEAM_CONTROL_NAME, ProfileEditActivity.this)).build();
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
                        ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * This method is use for get the request model for received referral details
     * @return
     */
    private TeamDetailsRequestModel getRequestModel() throws Exception{
        TeamDetailsRequestModel mTeamDetailsRequestModel = new TeamDetailsRequestModel();
        mTeamDetailsRequestModel.setMemberId(mPreference.getUserId(ProfileEditActivity.this));
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
            mGetResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null) {
                    setDataOnUI();
                }
            } else {
                ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        switch (requestCode) {
            case ApplicationConstants.GALLERY_PIC :
                if (resultCode == RESULT_OK) {
                    // Get the Uri of the selected file
                    Uri uri = data.getData();
                    // Get the path
                    String path;
                    try {
                        if(uri != null){
                            String urlStr = AppUtils.getPath(ProfileEditActivity.this, uri);
                            if(urlStr != null){
                                Log.e("File Path: ", "" + urlStr);
                                if(AppUtils.getFileNameFromUrl(urlStr) != null && AppUtils.getFileExt(AppUtils.getFileNameFromUrl(urlStr)) != null){
                                    if(AppUtils.checkFileExtension(AppUtils.getFileExt(AppUtils.getFileNameFromUrl(urlStr)))){
                                        Log.e("print the file size", ""+AppUtils.getFileSize(urlStr));

                                        if(AppUtils.getFileSize(urlStr) < 2){
                                            addBeanObj(urlStr);
                                            File imgFile = new  File(urlStr);
                                            Bitmap myBitmap = BitmapFactory.decodeFile(imgFile.getAbsolutePath());
                                            mProfileImage.setImageBitmap(myBitmap);
                                        }else{
                                            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_file_validation_large));
                                        }
                                    }else{
                                        ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_file_format_edit_profile));
                                    }
                                }
                            }
                        }
                    } catch (Exception e) {
                        // TODO Auto-generated catch block
                        e.printStackTrace();
                    }
                }
                break;
            case ApplicationConstants.CAMERA_PIC:
                if (resultCode == RESULT_OK) {
                    // Get the Uri of the selected file
                    //Uri uri = data.getData();
                    // Get the path
                    String path;
                    try {
                        if(mImageCaptureUri != null){
                            String urlStr = AppUtils.getPath(ProfileEditActivity.this, mImageCaptureUri);
                            if(urlStr != null){
                                Log.e("File Path: ", "" + urlStr);
                                if(AppUtils.getFileNameFromUrl(urlStr) != null && AppUtils.getFileExt(AppUtils.getFileNameFromUrl(urlStr)) != null){
                                    if(AppUtils.checkFileExtension(AppUtils.getFileExt(AppUtils.getFileNameFromUrl(urlStr)))){
                                        Log.e("print the file size", ""+AppUtils.getFileSize(urlStr));
                                        if(AppUtils.getFileSize(urlStr) < 2){
                                            addBeanObj(urlStr);
                                            File imgFile = new  File(urlStr);
                                            Bitmap myBitmap = BitmapFactory.decodeFile(imgFile.getAbsolutePath());
                                            mProfileImage.setImageBitmap(myBitmap);
                                        }else{
                                            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_file_validation_large));
                                        }
                                    }else{
                                        ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_file_format_edit_profile));
                                    }
                                }
                            }
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                }
                break;
        }
        super.onActivityResult(requestCode, resultCode, data);
    }
    private void addBeanObj(String fileUrl){
        AttachmentsListBean mAttachmentsListBean = new AttachmentsListBean();
        mAttachmentsListBean.setFileUrl(fileUrl);
        mAttachmentsListBean.setName(AppUtils.getFileNameFromUrl(fileUrl));
        attachmentListArr.add(mAttachmentsListBean);
    }

    /**
     * Asynctask uploading files as well as data of composing message
     */
    class ImageUploadTask extends AsyncTask<Void, Void, String> {

        private String responseStr = "";

        @Override
        protected void onPreExecute() {
            mProgressHUD = ProgressHUD.show(ProfileEditActivity.this,"", true,true);
            mProgressHUD.setCancelable(false);
        }

        @Override
        protected String doInBackground(Void... params) {
            try {
                ArrayList<File> files = new ArrayList<File>() ;

                for(int i =0; i< attachmentListArr.size(); i++){
                    files.add(new File(attachmentListArr.get(i).getFileUrl()));
                }

                responseStr = MutiPartRequest.postImageProfile(files, getPostRequestParmes(), ProfileEditActivity.this, WebServiceConstants.GET_NOTIFICATION_CONTROL_NAME, WebServiceConstants.EDIT_PROFILE_ACTION_NAME);

                Log.e(TAG, responseStr);
            } catch (Exception e) {
                e.printStackTrace();
            }
            return null;
        }

        @Override
        protected void onPostExecute(String result) {
            mProgressHUD.dismiss();
            try {
                parseSendReferralsService(responseStr);

            } catch (Exception ex) {
                ex.printStackTrace();
                ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_server_error));
            }

        }
    }

    /**
     * parsing compose message response
     * @param responseStr
     * @throws Exception
     */

    private void parseSendReferralsService(String responseStr) throws Exception{
        Gson gson = new Gson();
        SendReferralsResponseModel get_Response = gson.fromJson(responseStr, SendReferralsResponseModel.class);

        if(get_Response != null){
            if (get_Response.getReponseCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                ErrorMsgDialog.alertOkButtonCallBack(ProfileEditActivity.this, "", get_Response.getMessage(),new OkPressedResponse());

            }else{
                ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", get_Response.getMessage());
            }
        }else{
            ErrorMsgDialog.showErrorAlert(ProfileEditActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }
    //callback method after successful response
    class OkPressedResponse implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            // Call web service here
            try {
                finish();
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    /**
     * Method use to create the hash map that posted to server.
     * @return
     */

    private HashMap<String, String> getPostRequestParmes() {

        HashMap<String, String> postValues = new HashMap<String, String>();

        postValues.put("city", strCity);
        postValues.put("mobile", strMobilePhoneNo);
        postValues.put("office_phone", strOfficePhoneNo);
        postValues.put("website", strWebSite1);
        postValues.put("website1", strWebSite2);
        postValues.put("twitter_profile_id", strTwitterId);
        postValues.put("facebook_profile_id", strFacebookId);
        postValues.put("linkedin_profile_id", strLinkedinId);
        postValues.put("skype_id",strSkypeId);
        postValues.put("aboutme", strAboutUs);
        postValues.put("business_description", strCompDesc);
        postValues.put("services", strServices);
        postValues.put("address", strAddress);
        return postValues;

    }
}
