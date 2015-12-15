/**
 * To Add new contacts
 *
 */
package com.app.foxhopr.ui.contacts;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.ContactAddRequestModel;
import com.app.foxhopr.request.models.StateListRequestModel;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.Validation;
import com.app.foxhopr.webservice.models.CountryListInnerModel;
import com.app.foxhopr.webservice.models.CountryResponseModel;
import com.app.foxhopr.webservice.models.SendReferralsResponseModel;
import com.app.foxhopr.webservice.models.StateListInnerModel;
import com.app.foxhopr.webservice.models.StateListResponseModel;
import com.app.foxhopr.webservice.models.TeamMebmersListInnerModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.ArrayList;
import java.util.List;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class ContactAddActivity extends FragmentActivity implements View.OnClickListener {
    private static String TAG="ContactAddActivity";
    private RelativeLayout rlBack;
    private TextView mTxtvSendReferrals;
    private LinearLayout mlLAddContact;

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

    private TextView mBtnCountry;
    private TextView mBtnState;
    private FrameLayout mFlCountry;
    private FrameLayout mFlState;



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

    private ProgressHUD mProgressHUD;

    private ArrayList<StateListInnerModel> stateListArr;
    private List<String> stateList = new ArrayList<String>();


    private ArrayList<CountryListInnerModel> countryListArr;
    private List<String> countryList = new ArrayList<String>();

    private ArrayList<TeamMebmersListInnerModel> getSelectedMemberListData;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_contact_add);
        initView();
    }
    private void initView(){
        rlBack	=	(RelativeLayout)findViewById(R.id.rlBack);

        mTxtvSendReferrals	=	(TextView)findViewById(R.id.textViewHeaderTitle);
        mTxtvSendReferrals.setVisibility(View.VISIBLE);
        mTxtvSendReferrals.setText(getResources().getString(R.string.str_add_a_contact));


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
        mEdtMobileNo	=	(EditText)findViewById(R.id.editTextMobileCreateNewContact);

        mBtnCountry	=	(TextView)findViewById(R.id.buttonNewContactSelectCountry);
        mBtnState	=	(TextView)findViewById(R.id.buttonNewContactSelectState);

        mFlCountry	=	(FrameLayout)findViewById(R.id.flReferralsCountry);
        mFlState	=	(FrameLayout)findViewById(R.id.flReferralsState);


        mlLAddContact	=	(LinearLayout)findViewById(R.id.llSendReferralsNewContactNextStepOne);
        mlLAddContact.setOnClickListener(this);
        rlBack.setOnClickListener(this);
        mBtnCountry.setOnClickListener(this);
        mBtnState.setOnClickListener(this);

        //getDataFromIntent();

        applyValidationFilters();
        //Call the web service
        try{
            if (AppUtils.isOnline(ContactAddActivity.this)) {
                mProgressHUD = ProgressHUD.show(ContactAddActivity.this, "", true, true);
                mProgressHUD.setCancelable(false);
                callCountryListWebservice();
            }
            else{
                ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
            }
        }catch (Exception ex){
            ex.printStackTrace();
        }

    }

    private void applyValidationFilters() {
        mEdtFirstName.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValid(ContactAddActivity.this, mEdtFirstName, mEdtFirstName);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
        mEdtLastName.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValid(ContactAddActivity.this, mEdtLastName, mEdtLastName);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
        mEdtOfficePhoneNo.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValid(ContactAddActivity.this, mEdtOfficePhoneNo, mEdtOfficePhoneNo);
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
                Validation.hasInput(ContactAddActivity.this, mEdtMobileNo, mEdtMobileNo);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
        mEdtZip.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInput(ContactAddActivity.this, mEdtZip, mEdtZip);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });

        mEdtWebsite.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInput(ContactAddActivity.this, mEdtWebsite, mEdtWebsite);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });

        mEdtCompanyName.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInput(ContactAddActivity.this, mEdtCompanyName, mEdtCompanyName);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });

        // TextWatcher would let us check validation error on the fly
        mEdtEmail.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValid(ContactAddActivity.this, mEdtEmail, mEdtEmail);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });

        mEdtEmail.addTextChangedListener(new TextWatcher() {
            // after every change has been made to this editText, we would like to check validity
            @Override
            public void afterTextChanged(Editable s) {
                Validation.isEmailAddressCorrect(ContactAddActivity.this, mEdtEmail, mEdtEmail, true);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
    }
    /**
     * Method to check the validation on the page
     * @return
     */
    private boolean checkValidation() {
        boolean isAllFieldCorrect = true;


        if (strFname.length() == 0 || strLname.length() == 0 || strOfficePhoneNo.length() == 0 || strEmail.length()==0 || strJobTitle.length()==0) {
            isAllFieldCorrect = false;

            if (strFname.length() == 0 ) {
                mEdtFirstName.requestFocus();
                Validation.hasInputValid(ContactAddActivity.this, mEdtFirstName, mEdtFirstName);
            }

            if ( strLname.length() == 0 ) {
                if ( strFname.length() != 0 ) {
                    mEdtLastName.requestFocus();
                }
                Validation.hasInputValid(ContactAddActivity.this, mEdtLastName, mEdtLastName);
            }

            if (strCountryName.trim().length() == 0 ) {
                mFlCountry.setBackgroundResource(R.drawable.bg_select_team_error);
            }

            if (strStateName.trim().length() == 0 ) {
                mFlState.setBackgroundResource(R.drawable.bg_select_team_error);
            }

            if (strEmail.trim().length() == 0 ) {
                mEdtEmail.requestFocus();
                Validation.hasInputValid(ContactAddActivity.this, mEdtEmail, mEdtEmail);
            }

            if (strJobTitle.trim().length() == 0 ) {
                mEdtJobTitle.requestFocus();
                Validation.hasInputValid(ContactAddActivity.this, mEdtJobTitle, mEdtJobTitle);
            }

            if (strOfficePhoneNo.length() == 0 ) {
                if (strFname.length() !=0 && strLname.length() !=0 ) {
                    mEdtOfficePhoneNo.requestFocus();
                }

                Validation.hasInputValid(ContactAddActivity.this, mEdtOfficePhoneNo, mEdtOfficePhoneNo);
            }


            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_empty_requried_field));

        }
        else if (strFname.length() >20 ) {
            isAllFieldCorrect = false;
            mEdtFirstName.requestFocus();
            mEdtFirstName.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_fname_max_limit));
        }else if (!Validation.isValidString(strFname)) {
            isAllFieldCorrect = false;
            mEdtFirstName.requestFocus();
            mEdtFirstName.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_fname_invalid));
        }
        else if (strLname.length() >20 ) {
            isAllFieldCorrect = false;
            mEdtLastName.requestFocus();
            mEdtLastName.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_lname_max_limit));
        }else if (!Validation.isValidString(strLname)) {
            isAllFieldCorrect = false;
            mEdtLastName.requestFocus();
            mEdtLastName.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_lname_invalid));
        }else if(strCompanyName.length() >0 && strCompanyName.length() >35){
            isAllFieldCorrect = false;
            mEdtCompanyName.requestFocus();
            mEdtCompanyName.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_companyname_max_limit));

        }else if (strCompanyName.length() >0  && !Validation.isValidAddress(strCompanyName))  {
            isAllFieldCorrect = false;
            mEdtCompanyName.requestFocus();
            mEdtCompanyName.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_companyname_invalid));
        }
        else if(strJobTitle.length() >0 && strJobTitle.length() >35) {
            mEdtCompanyName.setBackgroundResource(R.drawable.bg_select_team);
            isAllFieldCorrect = false;
            mEdtJobTitle.requestFocus();
            mEdtJobTitle.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_jobtitle_max_limit));
        }
		/*else if (strJobTitle.length() >0 && !Validation.isValidJobTitle(strJobTitle))  {
			isAllFieldCorrect = false;
			mEdtJobTitle.requestFocus();
			mEdtJobTitle.setBackgroundResource(R.drawable.bg_select_team_error);
			ErrorMsgDialog.showErrorAlert(SendReferralsNewContactCreationActivity.this, "", getString(R.string.wrng_str_jobtitle_invalid));
		}*/
        else if(strEmail.length() >0 && !Validation.isValidEmail(strEmail)){
            mEdtJobTitle.setBackgroundResource(R.drawable.bg_select_team);
            mEdtEmail.requestFocus();
            isAllFieldCorrect = false;
            mEdtEmail.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_invalid_email));
        }else if(strWebSite.length() >0 && !AppUtils.isValidUrl(strWebSite)){
            mEdtEmail.setBackgroundResource(R.drawable.bg_select_team);
            mEdtWebsite.requestFocus();
            isAllFieldCorrect = false;
            mEdtWebsite.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_invalid_url));
        }else if(strAddress.length() >0 && strAddress.length() >60){

            isAllFieldCorrect = false;
            mEdtAddress.requestFocus();
            mEdtAddress.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_address_max_limit));
        }else if (strAddress.length() >0 && !Validation.isValidAddressFormat(strAddress))  {

            isAllFieldCorrect = false;
            mEdtAddress.requestFocus();
            mEdtAddress.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_address_invalid));
        }else if(strCity.length() >0 && strCity.length() >35) {
            mEdtAddress.setBackgroundResource(R.drawable.bg_select_team);
            isAllFieldCorrect = false;
            mEdtCity.requestFocus();
            mEdtCity.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_city_max_limit));
        }else if(strCity.length() >0 &&  !Validation.isValidString(strCity)) {
            mEdtAddress.setBackgroundResource(R.drawable.bg_select_team);
            isAllFieldCorrect = false;
            mEdtCity.requestFocus();
            mEdtCity.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_city_invalid));
        }
        else if(strZip.length() >0 && strZip.length() <3) {
            mEdtCity.setBackgroundResource(R.drawable.bg_select_team);
            isAllFieldCorrect = false;
            mEdtZip.requestFocus();
            mEdtZip.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_zipcode_max_limit));
        }else if(strZip.length() >0 && strZip.length() > 12) {
            mEdtCity.setBackgroundResource(R.drawable.bg_select_team);
            isAllFieldCorrect = false;
            mEdtZip.requestFocus();
            mEdtZip.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_zipcode_max_limit));
        }else if (strZip.length() >0 && !Validation.isValidZip(strZip)) {
            mEdtCity.setBackgroundResource(R.drawable.bg_select_team);
            isAllFieldCorrect = false;
            mEdtZip.requestFocus();
            mEdtZip.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_zipcode_invalid));
        }else if(strOfficePhoneNo.length() >15){
            mEdtCity.setBackgroundResource(R.drawable.bg_select_team);
            mEdtOfficePhoneNo.requestFocus();
            isAllFieldCorrect = false;
            mEdtOfficePhoneNo.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_officePhone_max_limit));
        }else if(strMobilePhoneNo.length() >0 && strMobilePhoneNo.length() >15){
            mEdtOfficePhoneNo.setBackgroundResource(R.drawable.bg_select_team);
            mEdtMobileNo.requestFocus();
            isAllFieldCorrect = false;
            mEdtMobileNo.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_mobile_phone_max_limit));
        }
        return isAllFieldCorrect;
    }

    @Override
    public void onBackPressed() {
        finish();
        overridePendingTransition(0, 0);
    }

    @Override
    public void onClick(View v) {
        if(v == mlLAddContact){

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
                    //TODO code for calling web service
                    try{
                        if (AppUtils.isOnline(ContactAddActivity.this)) {
                            mProgressHUD = ProgressHUD.show(ContactAddActivity.this, "", true, true);
                            mProgressHUD.setCancelable(false);
                            callAddContactWebServices();
                        }
                        else{
                            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                        }
                    }catch (Exception ex){
                        ex.printStackTrace();
                    }


                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }
        }else if(v == rlBack){
            finish();
            overridePendingTransition(0, 0);
        }else if(v == mBtnCountry){
            try{
                if(countryListArr != null && countryListArr.size() >0){
                    showCountryList(countryListArr);
                }
            }catch (Exception ex){
                ex.printStackTrace();
            }
        }else if(v == mBtnState){
            try{
                showStateList();
            }catch (Exception ex){
                ex.printStackTrace();
            }
        }
    }

    /*************************COUNTRY LIST************************/

    /**
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callCountryListWebservice() throws Exception{
        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.COUNTRY_ACTION_NAME, WebServiceConstants.COUNTRY_CONTROL_NAME, ContactAddActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getCountryListRequest(new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        parseCountryService(WebServiceUtils.getResponseString(responseModel));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * parseLoginService
     * @param responseStr
     * @throws Exception
     */
    private void parseCountryService(String responseStr) throws Exception{
        Log.e(TAG, responseStr);
        Gson gson = new Gson();
        CountryResponseModel get_Response = gson.fromJson(responseStr, CountryResponseModel.class);

        if(get_Response != null){
            if (get_Response.getReponseCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                countryListArr = get_Response.getCountryList();
                for(int i=0; i< countryListArr.size(); i++){
                    countryList.add(countryListArr.get(i).getCountry_name());
                }
            }else{
                ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", get_Response.getMessage());
            }
        }else{
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_server_error));
        }

    }

    private void showCountryList(final ArrayList<CountryListInnerModel> countryListArr) throws Exception {
        if (countryListArr.size() > 0) {

            if(countryList.size() >0){
                final String[] items = countryList.toArray(new String[0]);
                AlertDialog.Builder builder = new AlertDialog.Builder(ContactAddActivity.this);
                builder.setTitle(ContactAddActivity.this.getResources().getString(R.string.str_select_country));
                builder.setItems(items, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int item) {
                        if (countryListArr.get(item).getCountry_name() != null && !countryListArr.get(item).getCountry_name().equalsIgnoreCase("")) {
                            strCountryName = "" + countryListArr.get(item).getCountry_name();
                            strCountryId = "" + countryListArr.get(item).getCountry_iso_code_2();

                            mBtnCountry.setText(items[item]);
                            mFlCountry.setBackgroundResource(R.drawable.bg_select_team);

                            //Call the web service
                            try{
                                if (AppUtils.isOnline(ContactAddActivity.this)) {
                                    mBtnState.setText(getResources().getString(R.string.str_select_state));
                                    strStateName = "";
                                    mProgressHUD = ProgressHUD.show(ContactAddActivity.this,"", true,true);
                                    mProgressHUD.setCancelable(false);
                                    callStateListWebservice();
                                }
                                else{
                                    ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                                }
                            }catch (Exception ex){
                                ex.printStackTrace();
                            }
                        }
                    }
                });

                AlertDialog alert = builder.create();
                alert.show();
            }
        }
    }

    /******************STATE LIST******************************/
    /**
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callStateListWebservice() throws Exception{
        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.STATE_ACTION_NAME, WebServiceConstants.STATE_CONTROL_NAME, ContactAddActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getStateListRequest(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        parseStateService(WebServiceUtils.getResponseString(responseModel));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * This method is use for get the request model
     * @return
     */
    private StateListRequestModel getRequestModel() throws  Exception{
        StateListRequestModel mStateListRequestModel = new StateListRequestModel();
        mStateListRequestModel.setCountryCode(strCountryId);
        return mStateListRequestModel;
    }


    /**
     * parseLoginService
     * @param responseStr
     * @throws Exception
     */

    private void parseStateService(String responseStr) throws Exception{
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        StateListResponseModel get_Response = gson.fromJson(responseStr, StateListResponseModel.class);

        if(get_Response != null){
            if (get_Response.getReponseCode().equals(ApplicationConstants.SUCCESS_CODE)) {

                if(stateList.size()>0)
                    stateList.clear();

                stateListArr = get_Response.getStateList();

                for(int i=0; i< stateListArr.size(); i++){
                    stateList.add(stateListArr.get(i).getState_subdivision_name());
                }
            }else{
                ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", get_Response.getMessage());
            }
        }else{
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    private void showStateList() throws Exception {
        if (stateListArr != null && stateListArr.size() > 0) {

            if(stateList.size() >0){
                final String[] items = stateList.toArray(new String[0]);
                AlertDialog.Builder builder = new AlertDialog.Builder(ContactAddActivity.this);
                builder.setTitle(ContactAddActivity.this.getResources().getString(R.string.str_select_state));
                builder.setItems(items, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int item) {
                        if (stateListArr.get(item).getState_subdivision_name() != null && !stateListArr.get(item).getState_subdivision_name().equalsIgnoreCase("")) {
                            strStateName = "" + stateListArr.get(item).getState_subdivision_name();
                            strStatesId = "" + stateListArr.get(item).getState_subdivision_id();
                            mFlState.setBackgroundResource(R.drawable.bg_select_team);
                            mBtnState.setText(items[item]);
                        }
                    }
                });

                AlertDialog alert = builder.create();
                alert.show();
            }
        }
    }

    private void callAddContactWebServices() throws Exception{
        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.ADD_CONTACT_ACTION_NAME, WebServiceConstants.ADD_CONTACT_CONTROL_NAME, ContactAddActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.addNewContact(getContactModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseAddContactServiceService(WebServiceUtils.getResponseString(responseModel));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    private void parseAddContactServiceService(String responseStr) throws Exception{
        Gson gson = new Gson();
        SendReferralsResponseModel get_Response = gson.fromJson(responseStr, SendReferralsResponseModel.class);

        if(get_Response != null){
            if (get_Response.getReponseCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                ErrorMsgDialog.alertOkButtonCallBack(ContactAddActivity.this, "", get_Response.getMessage(),new OkPressedResponse());

            }else{
                ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", get_Response.getMessage());
            }
        }else{
            ErrorMsgDialog.showErrorAlert(ContactAddActivity.this, "", getString(R.string.wrng_str_server_error));
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
     * This method is use for get the request model
     * @return
     */
    private ContactAddRequestModel getContactModel() throws  Exception{
        ContactAddRequestModel mContactAddRequestModel = new ContactAddRequestModel();
        SharedPreference saveSharedPreference=saveSharedPreference = new SharedPreference();
        mContactAddRequestModel.setFirst_name(strFname);
        mContactAddRequestModel.setLast_name(strLname);
        mContactAddRequestModel.setCompany(strCompanyName);
        mContactAddRequestModel.setJob_title(strJobTitle);
        mContactAddRequestModel.setWebsite(strWebSite);
        mContactAddRequestModel.setEmail(strEmail);
        mContactAddRequestModel.setAddress(strAddress);
        mContactAddRequestModel.setCity(strCity);
        mContactAddRequestModel.setOffice_phone(strOfficePhoneNo);
        mContactAddRequestModel.setMobile(strMobilePhoneNo);
        mContactAddRequestModel.setZip(strZip);
        mContactAddRequestModel.setCountry_id(strCountryId);
        mContactAddRequestModel.setState_id(strStatesId);
        mContactAddRequestModel.setUser_groupid(saveSharedPreference.getGroupId(ContactAddActivity.this));
        return mContactAddRequestModel;
    }


}
