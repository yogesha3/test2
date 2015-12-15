package com.app.foxhopr.ui.activities;
/**
 * Name: ForgotPasswordActivity
 * 
 * Description: this class use to when user want to reset the password.
 * 
 * @author a3logics
 *
 * Date: 11/05/2015
 *
 * Git Branch FOX-11
 */

import android.annotation.SuppressLint;
import android.app.Activity;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;

import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.ForgotPasswordRequestModel;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.Validation;
import com.app.foxhopr.webservice.models.ForgotPasswordResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

@SuppressLint("NewApi")
public class ForgotPasswordActivity extends Activity implements OnClickListener {

	private EditText mEdtUserName;

	private LinearLayout mllUserName;

	private Button mBtnLogin;

	private String strUserName = "";

	private ProgressHUD mProgressHUD;

	private RelativeLayout mRlBack;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_forgot);
		initUI();
	}

	private void initUI() {
		mRlBack = (RelativeLayout) findViewById(R.id.rlBack);

		mEdtUserName = (EditText) findViewById(R.id.editTextForgotPasswordUserName);

		mllUserName = (LinearLayout) findViewById(R.id.llForgotUserName);

		mBtnLogin = (Button) findViewById(R.id.buttonForgotPasswordSubmit);

		mBtnLogin.setOnClickListener(this);
		mRlBack.setOnClickListener(this);
		applyValidationFilters();
	}

	private void applyValidationFilters() {

		// TextWatcher would let us check validation error on the fly
		mEdtUserName.addTextChangedListener(new TextWatcher() {
			@Override
			public void afterTextChanged(Editable s) {
				Validation.hasText(ForgotPasswordActivity.this, mEdtUserName, mllUserName);
			}

			@Override
			public void beforeTextChanged(CharSequence s, int start, int count, int after) {
			}

			@Override
			public void onTextChanged(CharSequence s, int start, int before, int count) {
			}
		});

		mEdtUserName.addTextChangedListener(new TextWatcher() {
			// after every change has been made to this editText, we would like to check validity
			@Override
			public void afterTextChanged(Editable s) {
				Validation.isEmailAddress(ForgotPasswordActivity.this, mEdtUserName, mllUserName, true);
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
		// TODO Auto-generated method stub
		if (view == mBtnLogin) {
			strUserName = mEdtUserName.getText().toString().trim();
			if (checkValidation()) {
				if (AppUtils.isOnline(ForgotPasswordActivity.this)) {
					mProgressHUD = ProgressHUD.show(ForgotPasswordActivity.this,"", true,true);
					callWebservice();
				}
				else{
					ErrorMsgDialog.showErrorAlert(ForgotPasswordActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
				}
			}
		}else if(view == mRlBack){
			finish();
			overridePendingTransition(0,0);
		}
	}

	/**
	 * Method to check the validation on the page
	 * @return
	 */
	private boolean checkValidation() {
		boolean isAllFieldCorrect = true;
	   if (strUserName.length() == 0 ) {
			isAllFieldCorrect = false;
			mEdtUserName.requestFocus();
			Validation.hasText(ForgotPasswordActivity.this, mEdtUserName, mllUserName);
			ErrorMsgDialog.showErrorAlert(ForgotPasswordActivity.this, "", getString(R.string.wrng_str_empty_requried_field));
		}
		else if (!Validation.isEmailAddress(ForgotPasswordActivity.this, mEdtUserName, mllUserName, true)) {
			mEdtUserName.requestFocus();
			isAllFieldCorrect = false;
			Validation.isEmailAddress(ForgotPasswordActivity.this, mEdtUserName, mllUserName, true);
			ErrorMsgDialog.showErrorAlert(ForgotPasswordActivity.this, "", getString(R.string.wrng_str_invalid_email));
		} else {
			isAllFieldCorrect = true;
		}
		return isAllFieldCorrect;
	}

	/**
	 * THis method is use to call the webservice and get
	 * The response and error from server
	 */
	private void callWebservice() {
		RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.FORGOT_PASSWORD_ACTION_NAME, WebServiceConstants.FORGOT_PASSWORD_CONTROL_NAME, ForgotPasswordActivity.this)).build();
		RequestApi requestApi = restAdapter.create(RequestApi.class);

		requestApi.getForgotpasswordRequest(getRequestModel(), new Callback<Response>() {
			@Override
			public void success(Response responseModel, Response response) {
				// Try to get response body
				mProgressHUD.dismiss();
				try {
					parseForgotPasswordService(WebServiceUtils.getResponseString(responseModel));
				} catch (Exception ex) {
					ex.printStackTrace();
				}
			}

			@Override
			public void failure(RetrofitError error) {
				mProgressHUD.dismiss();
				error.printStackTrace();
				ErrorMsgDialog.showErrorAlert(ForgotPasswordActivity.this, "", getString(R.string.wrng_str_server_error));
			}
		});
	}

	/**
	 * This method is use for get the request model
	 * @return
	 */
	private ForgotPasswordRequestModel getRequestModel(){
		ForgotPasswordRequestModel mLoginRequestModel = new ForgotPasswordRequestModel();
		mLoginRequestModel.setUser_email(strUserName);
		return mLoginRequestModel;
	}

	/**
	 * parseLoginService 
	 * @param responseStr
	 * @throws Exception
	 */

	private void parseForgotPasswordService(String responseStr) throws Exception{
		Gson gson = new Gson();
		ForgotPasswordResponseModel get_Response = gson.fromJson(responseStr, ForgotPasswordResponseModel.class);

		if(get_Response != null){
			if (get_Response.getReponseCode().equals(ApplicationConstants.SUCCESS_CODE)) {
				ErrorMsgDialog.alertOkButtonCallBack(ForgotPasswordActivity.this, "", get_Response.getMessage(),new OkPressedResponse());
			}else{
				ErrorMsgDialog.showErrorAlert(ForgotPasswordActivity.this, "", get_Response.getMessage());
			}
		}else{
			ErrorMsgDialog.showErrorAlert(ForgotPasswordActivity.this, "", getString(R.string.wrng_str_server_error));
		}

	}


	class OkPressedResponse implements AlertCallBack {

		@Override
		public void alertAction(boolean select) {
			// Call web service here
			try {
				finish();
				overridePendingTransition(0,0);
			} catch (Exception e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
	}

	@Override
	public void onBackPressed() {
		finish();
		overridePendingTransition(0,0);
	}
}
