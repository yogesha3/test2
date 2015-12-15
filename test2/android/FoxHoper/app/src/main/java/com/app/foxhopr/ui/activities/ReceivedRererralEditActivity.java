/**
 * Fragment activity displaying the existing details for editing referral details
 * The First Name, Last Name, Email, Country, City, State and ZIP code will not be updated of the referral.
 * Other fields can be changed.
 */
package com.app.foxhopr.ui.activities;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Resources;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.ReceivedReferralEditModel;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.Validation;
import com.app.foxhopr.webservice.models.ReferrelDetailsResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class ReceivedRererralEditActivity extends FragmentActivity implements View.OnClickListener{

    private static String TAG="ReceivedRererralEditActivity";
    private ImageView mBackButton;
    private ReferrelDetailsResponseModel mGetResponse;
    private String mStringResponse;

    private TextView mTxtvSendReferrals;
    private LinearLayout mlLNext;
    private LinearLayout llMonetoryValue;
    private EditText mEdtFirstName;
    private EditText mEdtLastName;
    private EditText mEdtCompanyName;

    private EditText mEdtJobTitle;
    private EditText mEdtEmail;
    private EditText mEdtWebsite;
    private EditText mEdtAddress;
    private EditText mEdtZip;
    private EditText mEdtCity;
    private EditText mEdtOfficePhoneNo;
    private EditText mEdtMobileNo;
    private EditText mEdtMonetaryValue;

    private TextView mBtnCountry;
    private TextView mBtnState;

    private TextView mBtnReferralStatus;



    private FrameLayout mFlCountry;
    private FrameLayout mFlState;
    private FrameLayout mFlStatus;

    private String strFname =" ";
    private String strLname =" ";
    private String strCompanyName =" ";

    private String strJobTitle =" ";
    private String strEmail =" ";
    private String strWebSite =" ";
    private String strAddress =" ";
    private String strCity =" ";
    private String strOfficePhoneNo =" ";
    private String strMobilePhoneNo =" ";

    private String strCountryName =" ";
    private String strCountryId =" ";
    private String strZip =" ";
    private String strStateName =" ";
    private String strStatesId =" ";

    private String strMemberList =" ";
    private String strReferralStatus =" ";
    private String strMonetoryValue =" ";

    private ProgressHUD mProgressHUD;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_received_rererral_edit);
        initView();

    }
    //initiating the view
    public void initView(){
        mBackButton=(ImageView)findViewById(R.id.imgvDrawer);
        mBackButton.setOnClickListener(this);

        mTxtvSendReferrals	=	(TextView)findViewById(R.id.textViewHeaderTitle);
        mTxtvSendReferrals.setVisibility(View.VISIBLE);
        mTxtvSendReferrals.setText(getResources().getString(R.string.str_edit_referrals));


        mEdtFirstName	=	(EditText)findViewById(R.id.editTextFNameCreateNewContact);
        mEdtLastName	=	(EditText)findViewById(R.id.editTextLNameCreateNewContact);
        mEdtCompanyName	=	(EditText)findViewById(R.id.editTextCompanyNameCreateNewContact);
        mEdtJobTitle	=	(EditText)findViewById(R.id.editTextJobTitleCreateNewContact);
        mEdtEmail	=	(EditText)findViewById(R.id.editTextEmailCreateNewContact);
        mEdtWebsite	=	(EditText)findViewById(R.id.editTextWebsiteCreateNewContact);
        mEdtAddress	=	(EditText)findViewById(R.id.editTextAddressCreateNewContact);
        mEdtZip			=	(EditText)findViewById(R.id.editTextZipCreateNewContact);
        mEdtCity			=	(EditText)findViewById(R.id.editTextCityCreateNewContact);
        mEdtOfficePhoneNo	=	(EditText)findViewById(R.id.editTextOfficePhoneCreateNewContact);
        mEdtMobileNo	=	(EditText)findViewById(R.id.editTextMobileCreateNewContact);


        mEdtMobileNo	=	(EditText)findViewById(R.id.editTextMobileCreateNewContact);

        mBtnCountry	=	(TextView)findViewById(R.id.buttonNewContactSelectCountry);
        mBtnState	=	(TextView)findViewById(R.id.buttonNewContactSelectState);

        mBtnReferralStatus= (TextView)findViewById(R.id.buttonreferralsStatus);

        mFlCountry	=	(FrameLayout)findViewById(R.id.flReferralsCountry);
        mFlState	=	(FrameLayout)findViewById(R.id.flReferralsState);
        mFlStatus   =(FrameLayout)findViewById(R.id.flReferralsStatus);

        mEdtMonetaryValue	=	(EditText)findViewById(R.id.editTextMonetaryValue);

        mlLNext	=	(LinearLayout)findViewById(R.id.llSendReferralsNewContactNextStepOne);
        llMonetoryValue=(LinearLayout)findViewById(R.id.llMonetoryValue);
        mFlStatus.setOnClickListener(this);
        mlLNext.setOnClickListener(this);


        getData();
        setDataOnUI();
        applyValidationFilters();
    }

    /**
     * getting data from intent
     */
    public void getData(){
        mStringResponse=getIntent().getStringExtra(ApplicationConstants.RECEIVED_REFERRAL_EDIT_ACTION);
        Gson gson = new Gson();
        mGetResponse = gson.fromJson(mStringResponse, ReferrelDetailsResponseModel.class);
    }

    //set data on UI
    public void setDataOnUI(){
        if(null!=mGetResponse) {
            mEdtFirstName.setText(mGetResponse.getResult().getFirst_name());
            mEdtLastName.setText(mGetResponse.getResult().getLast_name());
            mEdtCompanyName.setText(mGetResponse.getResult().getCompany());
            mEdtJobTitle.setText(mGetResponse.getResult().getJob_title());
            mEdtEmail.setText(mGetResponse.getResult().getEmail());
            mEdtWebsite.setText(mGetResponse.getResult().getWebsite());
            mEdtAddress.setText(mGetResponse.getResult().getAddress());
            mEdtZip.setText(mGetResponse.getResult().getZip());
            mEdtCity.setText(mGetResponse.getResult().getCity());
            mEdtOfficePhoneNo.setText(mGetResponse.getResult().getOffice_phone());
            mEdtMobileNo.setText(mGetResponse.getResult().getMobile());

            mBtnCountry.setText(mGetResponse.getResult().getCountry_name());
            mBtnState.setText(mGetResponse.getResult().getState_name());
            mBtnReferralStatus.setText(mGetResponse.getResult().getReferral_status().substring(0, 1).toUpperCase()+mGetResponse.getResult().getReferral_status().substring(1));
            mEdtMonetaryValue.setText(mGetResponse.getResult().getMonetary_value());
        }
    }

    /**
     * selection of Referral status
     */
    public void setReferralStatus(){
        Resources res = getResources();
        final String[] refferalStatus = res.getStringArray(R.array.string_referral_status);
        AlertDialog.Builder builder = new AlertDialog.Builder(ReceivedRererralEditActivity.this);
        builder.setTitle(res.getString(R.string.str_select_referral_status));
        builder.setItems(refferalStatus, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int item) {
                mBtnReferralStatus.setText(refferalStatus[item]);
                mFlStatus.setBackgroundResource(R.drawable.bg_select_team);
            }
        });

        AlertDialog alert = builder.create();
        alert.show();
    }

    //run time apply filter of fields
     public void applyValidationFilters(){
        mEdtOfficePhoneNo.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValid(ReceivedRererralEditActivity.this, mEdtOfficePhoneNo, mEdtOfficePhoneNo);
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
                 Validation.hasInputValid(ReceivedRererralEditActivity.this, mEdtMobileNo, mEdtMobileNo);
             }

             @Override
             public void beforeTextChanged(CharSequence s, int start, int count, int after) {
             }

             @Override
             public void onTextChanged(CharSequence s, int start, int before, int count) {
             }
         });

         mEdtMonetaryValue.addTextChangedListener(new TextWatcher() {
             @Override
             public void afterTextChanged(Editable s) {
                 Validation.hasInputValidMonetory(ReceivedRererralEditActivity.this, mEdtMonetaryValue, llMonetoryValue);
             }

             @Override
             public void beforeTextChanged(CharSequence s, int start, int count, int after) {
             }

             @Override
             public void onTextChanged(CharSequence s, int start, int before, int count) {
             }
         });

        /*mEdtMonetaryValue.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {

            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {

            }
        });*/
        /*InputFilter filter = new InputFilter() {
            @Override
            public CharSequence filter(CharSequence source, int start, int end, Spanned dest, int dstart, int dend) {
                if (dest.length()==0) {
                    return "$"+source;
                }else{
                    return source;
                }


            }
        };*/
        //mEdtMonetaryValue.setFilters(new InputFilter[]{filter});


    }

    /**
     * Method to check the validation on the page
     * @return
     */
    private boolean checkValidation() {
        boolean isAllFieldCorrect = true;


        if (strOfficePhoneNo.length() == 0  || strJobTitle.length() == 0) {
            isAllFieldCorrect = false;

            if (strOfficePhoneNo.length() == 0 ) {
                if (strFname.length() !=0 && strLname.length() !=0 ) {
                    mEdtOfficePhoneNo.requestFocus();
                }
                Validation.hasInputValid(ReceivedRererralEditActivity.this, mEdtOfficePhoneNo, mEdtOfficePhoneNo);
            }
            if (strJobTitle.trim().length() == 0 ) {
                mEdtJobTitle.requestFocus();
                Validation.hasInputValid(ReceivedRererralEditActivity.this, mEdtJobTitle, mEdtJobTitle);
            }

            ErrorMsgDialog.showErrorAlert(ReceivedRererralEditActivity.this, "", getString(R.string.wrng_str_empty_requried_field));

        }else if(strCompanyName.length() >0 && strCompanyName.length() >35){
            isAllFieldCorrect = false;
            mEdtCompanyName.requestFocus();
            mEdtCompanyName.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ReceivedRererralEditActivity.this, "", getString(R.string.wrng_str_companyname_max_limit));

        }else if (strCompanyName.length() >0  && !Validation.isValidAddress(strCompanyName))  {
            isAllFieldCorrect = false;
            mEdtCompanyName.requestFocus();
            mEdtCompanyName.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ReceivedRererralEditActivity.this, "", getString(R.string.wrng_str_companyname_invalid));
        }else if(strJobTitle.length() >0 && strJobTitle.length() >35) {
            mEdtCompanyName.setBackgroundResource(R.drawable.bg_select_team);
            isAllFieldCorrect = false;
            mEdtJobTitle.requestFocus();
            mEdtJobTitle.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ReceivedRererralEditActivity.this, "", getString(R.string.wrng_str_jobtitle_max_limit));
        }else if(strWebSite.length() >0 && !AppUtils.isValidUrl(strWebSite)){
            mEdtEmail.setBackgroundResource(R.drawable.bg_select_team);
            mEdtWebsite.requestFocus();
            isAllFieldCorrect = false;
            mEdtWebsite.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ReceivedRererralEditActivity.this, "", getString(R.string.wrng_str_invalid_url));
        }else if(strAddress.length() >0 && strAddress.length() >60){

            isAllFieldCorrect = false;
            mEdtAddress.requestFocus();
            mEdtAddress.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ReceivedRererralEditActivity.this, "", getString(R.string.wrng_str_address_max_limit));
        }else if (strAddress.length() >0 && !Validation.isValidAddressFormat(strAddress))  {

            isAllFieldCorrect = false;
            mEdtAddress.requestFocus();
            mEdtAddress.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ReceivedRererralEditActivity.this, "", getString(R.string.wrng_str_address_invalid));
        }else if(strOfficePhoneNo.length() >15){
            mEdtCity.setBackgroundResource(R.drawable.bg_select_team);
            mEdtOfficePhoneNo.requestFocus();
            isAllFieldCorrect = false;
            mEdtOfficePhoneNo.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ReceivedRererralEditActivity.this, "", getString(R.string.wrng_str_officePhone_max_limit));
        }else if(strMobilePhoneNo.length() >0 && strMobilePhoneNo.length() >15){
            mEdtOfficePhoneNo.setBackgroundResource(R.drawable.bg_select_team);
            mEdtMobileNo.requestFocus();
            isAllFieldCorrect = false;
            mEdtMobileNo.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ReceivedRererralEditActivity.this, "", getString(R.string.wrng_str_mobile_phone_max_limit));
        }else if(strMonetoryValue.length() >0 && strMonetoryValue.length() >11){
            mEdtOfficePhoneNo.setBackgroundResource(R.drawable.bg_select_team);
            mEdtMonetaryValue.requestFocus();
            isAllFieldCorrect = false;
            llMonetoryValue.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ReceivedRererralEditActivity.this, "", getString(R.string.wrng_str_monetary_value_max_limit));
        }
        return isAllFieldCorrect;
    }



    @Override
    public void onClick(View view) {
        if(view==mBackButton){
            finish();
            overridePendingTransition(0, 0);
        }if(view==mFlStatus){
            setReferralStatus();
        }if(view==mlLNext){
            strFname	=	mEdtFirstName.getText().toString().trim();
            strLname	=	mEdtLastName.getText().toString().trim();
            strCompanyName	=	mEdtCompanyName.getText().toString().trim();
            strJobTitle	=	mEdtJobTitle.getText().toString().trim();
            strWebSite	=	mEdtWebsite.getText().toString().trim();
            strEmail	=	mEdtEmail.getText().toString().trim();
            strAddress	=	mEdtAddress.getText().toString().trim();
            strCity	=	mEdtCity.getText().toString().trim();
            strOfficePhoneNo	=	mEdtOfficePhoneNo.getText().toString().trim();
            strMobilePhoneNo	=	mEdtMobileNo.getText().toString().trim();
            strZip	=	mEdtZip.getText().toString().trim();
            strReferralStatus=mBtnReferralStatus.getText().toString().toLowerCase();
            strMonetoryValue=mEdtMonetaryValue.getText().toString();


            if (checkValidation()) {

                mEdtFirstName.setBackgroundResource(R.drawable.bg_select_team);
                mEdtLastName.setBackgroundResource(R.drawable.bg_select_team);
                mEdtCompanyName.setBackgroundResource(R.drawable.bg_select_team);
                mEdtJobTitle.setBackgroundResource(R.drawable.bg_select_team);
                mEdtWebsite.setBackgroundResource(R.drawable.bg_select_team);
                mEdtAddress.setBackgroundResource(R.drawable.bg_select_team);
                mEdtCity.setBackgroundResource(R.drawable.bg_select_team);
                mEdtOfficePhoneNo.setBackgroundResource(R.drawable.bg_select_team);
                mEdtMobileNo.setBackgroundResource(R.drawable.bg_select_team);
                mEdtZip.setBackgroundResource(R.drawable.bg_select_team);
                mFlCountry.setBackgroundResource(R.drawable.bg_select_team);
                mFlState.setBackgroundResource(R.drawable.bg_select_team);

                //Call the web service

                try {

                    //Call the web service
                    try{
                        if (AppUtils.isOnline(ReceivedRererralEditActivity.this)) {
                            mProgressHUD = ProgressHUD.show(ReceivedRererralEditActivity.this,"", true,true);
                            mProgressHUD.setCancelable(false);
                            callEditWebservice();
                        }
                        else{
                            ErrorMsgDialog.showErrorAlert(ReceivedRererralEditActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                        }
                    }catch (Exception ex){
                        ex.printStackTrace();
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }
        }

    }

    /**
     * THis method is use to call the webservice and get
     * The response and error from server for edit referral
     */
    private void callEditWebservice() throws Exception{
        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.COUNTER_EDIT_REFERRAL_ACTION_NAME, WebServiceConstants.REFERRALS_CONTROL_NAME, ReceivedRererralEditActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.editReferral(getEditRequestModel(),new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.i(TAG,WebServiceUtils.getResponseString(responseModel));
                        //parseCountryService(WebServiceUtils.getResponseString(responseModel));
                        ErrorMsgDialog.showErrorAlert(ReceivedRererralEditActivity.this, "", getString(R.string.str_edit_success),new EditFinish());
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(ReceivedRererralEditActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
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
     * This method is use for get the request model for edit
     *
     * @return
     */
    private ReceivedReferralEditModel getEditRequestModel() throws Exception {
        ReceivedReferralEditModel mReceivedReferralEditModel = new ReceivedReferralEditModel();
        mReceivedReferralEditModel.setReferralId(mGetResponse.getResult().getId());
        mReceivedReferralEditModel.setFirst_name(mGetResponse.getResult().getFirst_name());

        mReceivedReferralEditModel.setCompany(strCompanyName);
        mReceivedReferralEditModel.setJob_title(strJobTitle);
        mReceivedReferralEditModel.setAddress(strAddress);
        mReceivedReferralEditModel.setOffice_phone(strOfficePhoneNo);
        mReceivedReferralEditModel.setMobile(strMobilePhoneNo);
        mReceivedReferralEditModel.setWebsite(strWebSite);
        mReceivedReferralEditModel.setReferral_status(strReferralStatus);
        mReceivedReferralEditModel.setMonetary_value(strMonetoryValue);

        return mReceivedReferralEditModel;
    }

}
