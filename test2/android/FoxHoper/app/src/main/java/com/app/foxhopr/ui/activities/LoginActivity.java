package com.app.foxhopr.ui.activities;
/**
 * Name: LoginFragement
 * 
 * Description: this class use to login the user
 * 
 * @author a3logics
 *
 * Date: 11/05/2015
 */

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.LoginRequestModel;
import com.app.foxhopr.ui.GroupSelection.GroupSelectionActivity;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.Validation;
import com.app.foxhopr.webservice.models.LoginResponseModel;
import com.foxhoper.app.R;
import com.google.android.gcm.GCMRegistrar;
import com.google.gson.Gson;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

@SuppressLint("NewApi")
public class LoginActivity extends Activity implements OnClickListener {

	private final  static String TAG="LoginActivity";
	private EditText mEdtUserName;
	private EditText mEdtPassword;

	private LinearLayout mllUserName;
	private LinearLayout mllPassword;

	private Button mBtnLogin;

	private TextView mTxtvForgotPassword;

	private String strUserName = "";
	private String strPassword = "";

	private ProgressHUD mProgressHUD;
	private SharedPreference saveSharedPreference;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_login);
		saveSharedPreference = new SharedPreference();
		initUI();
		//setupPushNotification();
	}

	/**
	 * Initializing UIs
	 */
	private void initUI() {

		mEdtUserName = (EditText) findViewById(R.id.editTextLoginUserName);

		mEdtPassword = (EditText) findViewById(R.id.editTextLoginPassword);

		mllUserName = (LinearLayout) findViewById(R.id.llLoginUserName);

		mllPassword = (LinearLayout) findViewById(R.id.llLoginPassword);

		mBtnLogin = (Button) findViewById(R.id.buttonLoginSubmit);

		mTxtvForgotPassword = (TextView) findViewById(R.id.textViewLoginForgotPassword);
		mEdtUserName.setTypeface(AppUtils.getTypeFace(LoginActivity.this));
		mEdtPassword.setTypeface(AppUtils.getTypeFace(LoginActivity.this));

		mBtnLogin.setOnClickListener(this);
		mTxtvForgotPassword.setOnClickListener(this);
		applyValidationFilters();
	}

	private void applyValidationFilters() {
		mEdtPassword.addTextChangedListener(new TextWatcher() {
			@Override
			public void afterTextChanged(Editable s) {
				Validation.hasText(LoginActivity.this, mEdtPassword, mllPassword);
			}

			@Override
			public void beforeTextChanged(CharSequence s, int start, int count, int after) {
			}

			@Override
			public void onTextChanged(CharSequence s, int start, int before, int count) {
			}
		});

		// TextWatcher would let us check validation error on the fly
		mEdtUserName.addTextChangedListener(new TextWatcher() {
			@Override
			public void afterTextChanged(Editable s) {
				Validation.hasText(LoginActivity.this, mEdtUserName, mllUserName);
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
				Validation.isEmailAddress(LoginActivity.this, mEdtUserName, mllUserName, true);
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
		if (view == mBtnLogin) {

			strUserName = mEdtUserName.getText().toString().trim();
			strPassword = mEdtPassword.getText().toString().trim();
			if (checkValidation()) {
				//Call the web service
				try{
					if (AppUtils.isOnline(LoginActivity.this)) {
						setupPushNotification();
						mProgressHUD = ProgressHUD.show(LoginActivity.this,"", true,true);
						mProgressHUD.setCancelable(false);
						callWebservice();
					}
					else{
						ErrorMsgDialog.showErrorAlert(LoginActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
					}
				}catch (Exception ex){
					ex.printStackTrace();
				}
			}
		}else if(view == mTxtvForgotPassword){
			Intent intObj = new Intent(LoginActivity.this, ForgotPasswordActivity.class);
			startActivity(intObj);
			overridePendingTransition(0, 0);
		}
	}

	/**
	 * Method to check the validation on the page
	 * @return
	 */
	private boolean checkValidation() {
		boolean isAllFieldCorrect = true;
		if (strUserName.length() == 0 && strPassword.length() == 0) {
			isAllFieldCorrect = false;
			mEdtUserName.requestFocus();
			Validation.hasText(LoginActivity.this, mEdtUserName, mllUserName);
			Validation.hasText(LoginActivity.this, mEdtPassword, mllPassword);
			ErrorMsgDialog.showErrorAlert(LoginActivity.this, "",getString(R.string.wrng_str_empty_requried_field));

		}else if (strUserName.length() == 0 ) {
			isAllFieldCorrect = false;
			mEdtUserName.requestFocus();
			Validation.hasText(LoginActivity.this, mEdtUserName, mllUserName);
			ErrorMsgDialog.showErrorAlert(LoginActivity.this, "", getString(R.string.wrng_str_empty_requried_field));
		}
		else if (!Validation.isEmailAddress(LoginActivity.this, mEdtUserName, mllUserName, true)) {
			mEdtUserName.requestFocus();
			isAllFieldCorrect = false;
			Validation.isEmailAddress(LoginActivity.this, mEdtUserName, mllUserName, true);
			ErrorMsgDialog.showErrorAlert(LoginActivity.this, "", getString(R.string.wrng_str_invalid_email));
		} else if (strPassword.length() == 0) {
			isAllFieldCorrect = false;
			mEdtPassword.requestFocus();
			Validation.hasText(LoginActivity.this, mEdtPassword, mllPassword);
			ErrorMsgDialog.showErrorAlert(LoginActivity.this, "", getString(R.string.wrng_str_empty_requried_field));
		} else {
			isAllFieldCorrect = true;
		}
		return isAllFieldCorrect;
	}

	/**
	 * THis method is use to call the webservice and get
	 * The response and error from server
	 */
	private void callWebservice() throws Exception{
		RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.LOGIN_ACTION_NAME, WebServiceConstants.LOGIN_CONTROL_NAME, LoginActivity.this)).build();
		RequestApi requestApi = restAdapter.create(RequestApi.class);

		requestApi.getLogin(getRequestModel(), new Callback<Response>() {
			@Override
			public void success(Response responseModel, Response response) {
				// Try to get response body
				mProgressHUD.dismiss();
				try {
					if (responseModel != null) {
						parseLoginService(WebServiceUtils.getResponseString(responseModel));
					}
				} catch (Exception ex) {
					ex.printStackTrace();
				}
			}

			@Override
			public void failure(RetrofitError error) {
				error.printStackTrace();
				mProgressHUD.dismiss();
				ErrorMsgDialog.showErrorAlert(LoginActivity.this, "", getString(R.string.wrng_str_server_error));
			}
		});
	}

	/**
	 * This method is use for get the request model
	 * @return
	 */
	private LoginRequestModel getRequestModel() throws  Exception{
		LoginRequestModel mLoginRequestModel = new LoginRequestModel();
		mLoginRequestModel.setUser_email(strUserName);
		mLoginRequestModel.setPassword(strPassword);

		return mLoginRequestModel;
	}

	/**
	 * parseLoginService
	 * @param responseStr
	 * @throws Exception
	 */

	private void parseLoginService(String responseStr) throws Exception{
		Log.i("Login", responseStr);
		Gson gson = new Gson();
		LoginResponseModel get_Response = gson.fromJson(responseStr, LoginResponseModel.class);

		if(get_Response != null){
			if (get_Response.getReponseCode().equals(ApplicationConstants.SUCCESS_CODE)) {
				try{
					setDataToMemory(get_Response);
				}catch (Exception ex){
					ex.printStackTrace();
				}
				//startService(new Intent(LoginActivity.this,NotificationService.class));
				//NotificationAlart.stop(LoginActivity.this);
				//NotificationAlart.start(LoginActivity.this);
				saveSharedPreference.setIsLogin(LoginActivity.this, "");
				if(null!=saveSharedPreference.getGroupId(LoginActivity.this) && !saveSharedPreference.getGroupId(LoginActivity.this).equals("")){
					moveToDashboardScreen();
				}else{
					moveToGroupSelectionActivity();
				}


			}else{
				ErrorMsgDialog.showErrorAlert(LoginActivity.this, "", get_Response.getMessage());
			}
		}else{
			ErrorMsgDialog.showErrorAlert(LoginActivity.this, "", getString(R.string.wrng_str_server_error));
		}

	}

	/**
	 * This method is use for to save the data in memory
	 * @param mLoginResponseModel
	 * @throws Exception
	 */

	private void setDataToMemory(LoginResponseModel mLoginResponseModel) throws  Exception{
		if(mLoginResponseModel.getData != null){
			if( mLoginResponseModel.getData.getId() != null){
				saveSharedPreference.setUserId(LoginActivity.this, mLoginResponseModel.getData.getId());
			}
			if( mLoginResponseModel.getData.getUser_email() != null){
				saveSharedPreference.setUserEmail(LoginActivity.this, mLoginResponseModel.getData.getUser_email());
			}
			if( mLoginResponseModel.getData.getIs_active() != null){
				saveSharedPreference.setUserIsActive(LoginActivity.this, mLoginResponseModel.getData.getIs_active().toString());
			}
			if( mLoginResponseModel.getData.getIs_confirm() != null){
				saveSharedPreference.setUserIsConfirm(LoginActivity.this, mLoginResponseModel.getData.getIs_confirm().toString());
			}
			if( mLoginResponseModel.getData.getProfile_image() != null){
				saveSharedPreference.setUserImage(LoginActivity.this, mLoginResponseModel.getData.getProfile_image());
			}
			if( mLoginResponseModel.getData.getProfile_completion_status() != null){
				saveSharedPreference.setUserProfilePercentage(LoginActivity.this, mLoginResponseModel.getData.getProfile_completion_status());
			}
			if( mLoginResponseModel.getData.getGroup_type() != null){
				saveSharedPreference.setGroupType(LoginActivity.this, mLoginResponseModel.getData.getGroup_type());
			}
			if( mLoginResponseModel.getData.getRating() != null){
				saveSharedPreference.setUserRating(LoginActivity.this, mLoginResponseModel.getData.getRating());
			}
			if( mLoginResponseModel.getData.getNext_shuffle() != null){
				saveSharedPreference.setUserNextShuffle(LoginActivity.this, mLoginResponseModel.getData.getNext_shuffle());
			}
			if( mLoginResponseModel.getData.getFname() != null){
				saveSharedPreference.setUserFname(LoginActivity.this, mLoginResponseModel.getData.getFname());
			}
			if( mLoginResponseModel.getData.getLname() != null){
				saveSharedPreference.setUserLname(LoginActivity.this, mLoginResponseModel.getData.getLname());
			}if( mLoginResponseModel.getData.getGroup_id() != null){
				saveSharedPreference.setGroupId(LoginActivity.this, mLoginResponseModel.getData.getGroup_id());
			}if( mLoginResponseModel.getData.getCity() != null){
				saveSharedPreference.setCity(LoginActivity.this, mLoginResponseModel.getData.getCity());
			}if( mLoginResponseModel.getData.getZipcode() != null){
				saveSharedPreference.setZipCode(LoginActivity.this, mLoginResponseModel.getData.getZipcode());
			}
		}
	}

	/**
	 * Method use to navigate the Dashboard screen
	 */
	private void moveToDashboardScreen() {
		Intent intObj = new Intent(LoginActivity.this, DashboardActivity.class);
		startActivity(intObj);
		overridePendingTransition(R.anim.slide_in_left, R.anim.slide_out_right);
		finish();
	}
	/**
	 * Method use to navigate the Group selection screen
	 */
	private void moveToGroupSelectionActivity() {
		Intent intObj = new Intent(LoginActivity.this, GroupSelectionActivity.class);
		startActivity(intObj);
		overridePendingTransition(R.anim.slide_in_left, R.anim.slide_out_right);
		finish();
	}

	public void setupPushNotification(){

		// Make sure the device has the proper dependencies.
		GCMRegistrar.checkDevice(this);

		// Make sure the manifest was properly set - comment out this line
		// while developing the app, then uncomment it when it's ready.
		GCMRegistrar.checkManifest(this);


		//registerReceiver(mHandleMessageReceiver, new IntentFilter(DISPLAY_MESSAGE_ACTION));

		// Get GCM registration id
		String regId = GCMRegistrar.getRegistrationId(this);

		// Check if regid already presents
		if (regId.equals("")) {
			// Registration is not present, register now with GCM
			GCMRegistrar.register(this, ApplicationConstants.SENDER_ID);
		} else {
			// Device is already registered on GCM
			if (GCMRegistrar.isRegisteredOnServer(this)) {
				// Skips registration.
				Toast.makeText(getApplicationContext(), "Already registered with GCM", Toast.LENGTH_LONG).show();
			}
		}

	}
}
