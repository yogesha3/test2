package com.app.foxhopr.ui.activities;
/**
 * Name: SplashActivity
 * 
 * Description: Activity Come when application start this screen appear for few second and move to next screen.
 * 
 * @author a3logics
 *
 * Date: 11/05/2015
 */

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.widget.Toast;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.ui.GroupSelection.GroupSelectionActivity;
import com.foxhoper.app.R;
import com.google.android.gcm.GCMRegistrar;
import com.splunk.mint.Mint;

@SuppressLint("NewApi")
public class SplashActivity extends Activity {

	private CountDownTimer splashTimer;
	private final long startTime = 3 * 1000;
	private final long interval = 1 * 1000;
	private SharedPreference saveSharedPreference;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		Mint.initAndStartSession(SplashActivity.this, "51391a87");
		Mint.enableDebug();
		setContentView(R.layout.activity_splash);
		setupPushNotification();
		saveSharedPreference = new SharedPreference();
		// Start the timer
		splashTimer = new SplashCountDownTimer(startTime, interval);
		splashTimer.start();

	}



	/**
	 * Timer user to show this screen only 3 second
	 * @author a3logics
	 *
	 */
	public class SplashCountDownTimer extends CountDownTimer {
		public SplashCountDownTimer(long startTime, long interval) {
			super(startTime, interval);
		}

		@Override
		public void onFinish() {
			moveTonextScreen();
		}

		@Override
		public void onTick(long millisUntilFinished) {
		}
	}

	@Override
	protected void onDestroy() {
		super.onDestroy();
		splashTimer.cancel();
	}

	/**
	 * Method use to navigate the screen
	 */
	private void moveTonextScreen() {

		Intent intObj;
		if(saveSharedPreference.getUserId(SplashActivity.this).equalsIgnoreCase("")){
			intObj= new Intent(SplashActivity.this, LoginActivity.class);
		}else if(null!=saveSharedPreference.getGroupId(SplashActivity.this) && !saveSharedPreference.getGroupId(SplashActivity.this).equals("")){
			intObj= new Intent(SplashActivity.this, DashboardActivity.class);
		}else{
			intObj= new Intent(SplashActivity.this, GroupSelectionActivity.class);
		}
		startActivity(intObj);
		overridePendingTransition(0, 0);
		finish();
	}

	public void setupPushNotification(){

		// Make sure the device has the proper dependencies.
		GCMRegistrar.checkDevice(this);

		// Make sure the manifest was properly set - comment out this line
		// while developing the app, then uncomment it when it's ready.
		GCMRegistrar.checkManifest(this);


		//registerReceiver(mHandleMessageReceiver, new IntentFilter(DISPLAY_MESSAGE_ACTION));
		GCMRegistrar.register(this, ApplicationConstants.SENDER_ID);
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
