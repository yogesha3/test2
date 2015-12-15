package com.app.foxhopr.ui.Account;

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
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.adapter.PurchaseReceiptAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.CreditCardRequestModel;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.Validation;
import com.app.foxhopr.webservice.models.CreditCardResponseModel;
import com.app.foxhopr.webservice.models.ReceiptDetailModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.ArrayList;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class CreditCardActivity extends FragmentActivity implements View.OnClickListener {

    private static String TAG="CreditCardActivity";
    private PurchaseReceiptAdapter mPurchaseReceiptAdapter;
    private SharedPreference mPreference;
    private ImageView mBackButton;
    private TextView mTxtvSendReferrals;

    private ArrayList<ReceiptDetailModel> listDataArray;
    private boolean isAllSelected = false;

    private ProgressHUD mProgressHUD;

    //Page data
    private CreditCardResponseModel getResponse;
    //for search order
    private SharedPreference mPreferences;
    //variable storing state of search or simple by default referal
    private boolean mStatus = false;
    private boolean mWebserviceStatus = true;
    private TextView mcreditcard_info;
    private EditText mEditTextCardNumber;
    private EditText mEditTextCVC;
    private FrameLayout flReferralsMonth;
    private FrameLayout flReferralsYear;
    private TextView buttonNewContactSelectMonth;
    private TextView buttonNewContactSelectYear;
    private EditText editTextCreditCardName;
    private LinearLayout llSaveButton;

    private  String mCreditCardText;
    private String mCreditCardNumber;
    private String mCreditCardCVC;
    private String mCreditCardExpiryMonth;
    private String mCreditCardExpiryYear;
    private String mCreditCardName;



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_creditcard);
        initView();


    }
    private void initView(){
        mCreditCardText=getIntent().getStringExtra(ApplicationConstants.CREDITCARD);
        mPreference=new SharedPreference();
        listDataArray = new ArrayList<ReceiptDetailModel>();
        mBackButton=(ImageView)findViewById(R.id.imgvDrawer);
        mBackButton.setOnClickListener(this);

        mTxtvSendReferrals	=	(TextView)findViewById(R.id.textViewHeaderTitle);
        mTxtvSendReferrals.setVisibility(View.VISIBLE);
        mTxtvSendReferrals.setText(getResources().getString(R.string.str_credit_card_title));

        mcreditcard_info = (TextView)findViewById(R.id.creditcard_info);
        mEditTextCardNumber=(EditText)findViewById(R.id.editTextCardNumber);
        mEditTextCVC=(EditText)findViewById(R.id.editTextCVC);
        flReferralsMonth=(FrameLayout)findViewById(R.id.flMonth);
        flReferralsYear=(FrameLayout)findViewById(R.id.flYear);
        buttonNewContactSelectMonth=(TextView)findViewById(R.id.buttonNewContactSelectMonth);
        buttonNewContactSelectYear=(TextView)findViewById(R.id.buttonNewContactSelectYear);
        editTextCreditCardName=(EditText)findViewById(R.id.editTextCreditCardName);
        llSaveButton=(LinearLayout)findViewById(R.id.llSave);
        llSaveButton.setOnClickListener(this);

        buttonNewContactSelectMonth.setOnClickListener(this);
        buttonNewContactSelectYear.setOnClickListener(this);

        if (AppUtils.isOnline(CreditCardActivity.this)) {
            // mFooterProgressBar.setVisibility(View.VISIBLE);
            mWebserviceStatus=false;
            mProgressHUD = ProgressHUD.show(CreditCardActivity.this, "", true, true);
            mProgressHUD.setCancelable(false);
            try {
                //callWebservice();
                mProgressHUD.dismiss();
            }catch (Exception e){
                e.printStackTrace();
            }
        } else {
            ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
        }
        applyValidationFilters();
        //setting credit card value;
        setCreditCardValue(mCreditCardText);
    }
    @Override
    public void onClick(View view) {
        if(view==mBackButton){
            finish();
        }else if(view ==llSaveButton){
            mCreditCardNumber=mEditTextCardNumber.getText().toString();
            mCreditCardCVC=mEditTextCVC.getText().toString();
            mCreditCardExpiryMonth=buttonNewContactSelectMonth.getContentDescription().toString();
            mCreditCardExpiryYear=buttonNewContactSelectYear.getContentDescription().toString();
            mCreditCardName=editTextCreditCardName.getText().toString();
            if(checkValidation()){
                try{
                    if (AppUtils.isOnline(CreditCardActivity.this)) {
                        mProgressHUD = ProgressHUD.show(CreditCardActivity.this,"", true,true);
                        mProgressHUD.setCancelable(false);
                        updateCreditCardWebservice();
                    }
                    else{
                        ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                    }
                }catch (Exception ex){
                    ex.printStackTrace();
                }
            }
        }else if(view==buttonNewContactSelectMonth){
            try {
                showMonthList();
            }catch (Exception e){
                e.printStackTrace();
            }
        }else if(view==buttonNewContactSelectYear){
            try {
                showYearList();
            }catch (Exception e){
                e.printStackTrace();
            }
        }

    }

    private void applyValidationFilters() {
        mEditTextCardNumber.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValidMonetory(CreditCardActivity.this, mEditTextCardNumber, mEditTextCardNumber);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
        mEditTextCVC.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValidMonetory(CreditCardActivity.this, mEditTextCVC, mEditTextCVC);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
        editTextCreditCardName.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValidMonetory(CreditCardActivity.this, editTextCreditCardName, editTextCreditCardName);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
    }
    /*mCreditCardNumber=mEditTextCardNumber.getText().toString();
    mCreditCardCVC=mEditTextCVC.getText().toString();
    mCreditCardExpiryMonth=buttonNewContactSelectMonth.getText().toString();
    mCreditCardExpiryYear=buttonNewContactSelectYear.getText().toString();
    mCreditCardName=editTextCreditCardName.getText().toString();*/
    /**
     * Checking validation for multiple required fields
     * @return
     */
    private boolean checkValidation() {
        boolean isAllFieldCorrect = true;
        if (mCreditCardNumber.length() == 0 || mCreditCardCVC.length() == 0 || mCreditCardExpiryMonth.length() == 0 || mCreditCardExpiryYear.length() == 0 || mCreditCardName.length() == 0) {
            isAllFieldCorrect = false;

            if (mCreditCardNumber.length() == 0 ) {
                mEditTextCardNumber.requestFocus();
                Validation.hasInputValid(CreditCardActivity.this, mEditTextCardNumber, mEditTextCardNumber);
            }

            if ( mCreditCardCVC.length() == 0 ) {
                if ( mCreditCardNumber.length() != 0 ) {
                    mEditTextCVC.requestFocus();
                }
                Validation.hasInputValid(CreditCardActivity.this, mEditTextCVC, mEditTextCVC);
            }

            if ( mCreditCardExpiryMonth.length() == 0 ) {
                if ( mCreditCardCVC.length() != 0 ) {
                    flReferralsMonth.requestFocus();
                }
                flReferralsMonth.setBackgroundResource(R.drawable.bg_select_team_error);
            }

            if ( mCreditCardExpiryYear.length() == 0 ) {
                if ( mCreditCardExpiryMonth.length() != 0 ) {
                    flReferralsYear.requestFocus();
                }
                flReferralsYear.setBackgroundResource(R.drawable.bg_select_team_error);
            }

            if ( mCreditCardName.length() == 0 ) {
                if ( mCreditCardExpiryYear.length() != 0 ) {
                    editTextCreditCardName.requestFocus();
                }
                Validation.hasInputValid(CreditCardActivity.this, editTextCreditCardName, editTextCreditCardName);
            }

            ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", getString(R.string.wrng_str_empty_requried_field));

        }else if (null!=mCreditCardNumber && mCreditCardNumber.length() ==0 ) {
            isAllFieldCorrect = false;
            mEditTextCardNumber.requestFocus();
            mEditTextCardNumber.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", getString(R.string.wrng_str_empty_requried_field));
        }else if (null!=mCreditCardNumber && mCreditCardNumber.length() <13 || mCreditCardNumber.length() >16 ) {
            isAllFieldCorrect = false;
            mEditTextCardNumber.requestFocus();
            mEditTextCardNumber.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", getString(R.string.str_alert_credit_card_number));
        }else if (null!=mCreditCardCVC && mCreditCardCVC.length() <3 || mCreditCardCVC.length() >3 ) {
            isAllFieldCorrect = false;
            mEditTextCVC.requestFocus();
            mEditTextCVC.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", getString(R.string.str_alert_credit_card_cvc));
        }else if (null!=mCreditCardCVC && mCreditCardCVC.length() ==0 ) {
            isAllFieldCorrect = false;
            mEditTextCVC.requestFocus();
            mEditTextCVC.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", getString(R.string.wrng_str_empty_requried_field));
        }else if (null!=mCreditCardExpiryMonth && mCreditCardExpiryMonth.length()==0) {
            isAllFieldCorrect = false;
            flReferralsMonth.requestFocus();
            flReferralsMonth.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", getString(R.string.wrng_str_empty_requried_field));
        }else if (null!=mCreditCardExpiryYear && mCreditCardExpiryYear.length()==0) {
            isAllFieldCorrect = false;
            flReferralsYear.requestFocus();
            flReferralsYear.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", getString(R.string.wrng_str_empty_requried_field));
        }else if (null!=mCreditCardName && mCreditCardName.length()==0) {
            isAllFieldCorrect = false;
            editTextCreditCardName.requestFocus();
            editTextCreditCardName.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", getString(R.string.wrng_str_empty_requried_field));
        }else if (null!=mCreditCardName && !Validation.isValidString(mCreditCardName)) {
            isAllFieldCorrect = false;
            editTextCreditCardName.requestFocus();
            editTextCreditCardName.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", getString(R.string.wrng_str_credit_card_name));
        }
        return isAllFieldCorrect;
    }

    private void showMonthList() throws Exception {

                final String[] items = getResources().getStringArray(R.array.string_month_list);
                AlertDialog.Builder builder = new AlertDialog.Builder(CreditCardActivity.this);
                builder.setTitle(CreditCardActivity.this.getResources().getString(R.string.str_select_month));
                builder.setItems(items, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int item) {
                        buttonNewContactSelectMonth.setText(items[item]);
                        buttonNewContactSelectMonth.setContentDescription(item + "");
                        flReferralsMonth.setBackgroundResource(R.drawable.bg_select_team);
                    }
                });

                AlertDialog alert = builder.create();
                alert.show();
    }

    private void showYearList() throws Exception {
        int year=AppUtils.getYears();

        //final String[] items = getResources().getStringArray(R.array.string_month_list);
        final String[] items =new String[16] ;
        for(int i=0;i<=15;i++){
            items[i]=year++ +"";
        }

        AlertDialog.Builder builder = new AlertDialog.Builder(CreditCardActivity.this);
        builder.setTitle(CreditCardActivity.this.getResources().getString(R.string.str_select_month));
        builder.setItems(items, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int item) {
                buttonNewContactSelectYear.setText(items[item]);
                buttonNewContactSelectYear.setContentDescription(items[item]);
                flReferralsYear.setBackgroundResource(R.drawable.bg_select_team);

            }
        });

        AlertDialog alert = builder.create();
        alert.show();
    }

/**
 * Update Goal
 */
    /**
     * THis method is use to call the received selected creditcard information
     * successful  response or  error from server
     */
    private void updateCreditCardWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_CC_ACTION_NAME, WebServiceConstants.GET_CC_CONTROL_NAME, CreditCardActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.updateCreditCardInfo(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseUpdateCreditCardDetailsService(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    private CreditCardRequestModel getRequestModel(){
        CreditCardRequestModel mCreditCardRequestModel=new CreditCardRequestModel();
        mCreditCardRequestModel.setCC_Number(mCreditCardNumber);
        mCreditCardRequestModel.setCC_cvv(mCreditCardCVC);
        mCreditCardRequestModel.setCC_month(mCreditCardExpiryMonth);
        mCreditCardRequestModel.setCC_year(mCreditCardExpiryYear);
        mCreditCardRequestModel.setCC_Name(mCreditCardName);
        return mCreditCardRequestModel;
    }

    /**
     * parse Billing details, getting from web services
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseUpdateCreditCardDetailsService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);
        Gson gson = new Gson();
        CreditCardResponseModel get_Response = gson.fromJson(responseStr, CreditCardResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", get_Response.getMessage());
                if (get_Response.getResult() != null) {
                    setCreditCardValue(get_Response.getResult().getCredit_card_number());
                    mEditTextCardNumber.setText("");
                    mEditTextCVC.setText("");
                    buttonNewContactSelectMonth.setText(getString(R.string.str_credit_card_expiration_month_text));
                    buttonNewContactSelectYear.setText(getString(R.string.str_credit_card_expiration_year_text));
                    editTextCreditCardName.setText("");
                }
            } else {
                ErrorMsgDialog.showErrorAlert(CreditCardActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            //ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    private void setCreditCardValue(String mCreditCardText){
        mcreditcard_info.setText(getString(R.string.str_associated_credit_card_text, mCreditCardText));
    }

}
