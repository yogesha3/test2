/**
 * Dashboard Activity, based on navigation drawer
 *
 */
package com.app.foxhopr.ui.activities;

import android.annotation.SuppressLint;
import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.ServiceConnection;
import android.graphics.Color;
import android.os.Bundle;
import android.os.IBinder;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentActivity;
import android.support.v4.app.FragmentManager;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.util.Log;
import android.view.Gravity;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.fragments.user.AccountFragment;
import com.app.foxhopr.fragments.user.ContactsFragment;
import com.app.foxhopr.fragments.user.MessageFragment;
import com.app.foxhopr.fragments.user.ReferralsFragment;
import com.app.foxhopr.fragments.user.SettingFragment;
import com.app.foxhopr.fragments.user.SuggestionsFragment;
import com.app.foxhopr.fragments.user.TeamFragment;
import com.app.foxhopr.fragments.user.VideosFragment;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.services.NotificationService;
import com.app.foxhopr.ui.Account.ProfileEditActivity;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.webservice.models.UserProfileResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;
import com.squareup.picasso.Picasso;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

@SuppressLint("NewApi")
public class DashboardActivity extends FragmentActivity implements OnClickListener {
	private static String TAG="DashboardActivity";
	private ProgressHUD mProgressHUD;
	private DrawerLayout mDrawerLayout;
	private ImageView imgDrawer;
	private RelativeLayout rlDrawer;
	private FrameLayout flRingbel;
	private TextView mTxtVHeaderTitle;
	private ActionBarDrawerToggle actionBarDrawerToggle;

	private LinearLayout mLlSideMenuLogoutl;
	private RelativeLayout mLlSideMenuReferrals;
	private RelativeLayout mLlSideMenuTeam;
	private RelativeLayout mLlSideMenuMessages;
	private RelativeLayout mLlSideMenuEvents;
	private RelativeLayout mLlSideMenuContacts;
	private RelativeLayout mLlSideMenuReviews;
	private RelativeLayout mLlSideMenuSettings;
	private RelativeLayout mLlSideMenuAccount;
	private RelativeLayout mLlSideMenuSuggestion;
	private RelativeLayout mLlSideMenuVideo;

	//Side menu

	private TextView mTxtvLoginUserName;

	private ImageView profileImageView;
	private ImageView profileEditImageView;

	private TextView mTextViewMemberType;
	private TextView mTextViewShuffleDate;
	private TextView mTextViewProfilePercentage;
	private TextView mTextViewMemberRoal;
	private TextView mTextViewGroupName;
	private ProgressBar mProfileProgressBar;

	private LinearLayout mLlRating;


	private TextView mMessageCount;
	private TextView mReferralsCount;
	private TextView mBadge;
	private BroadcastReceiver mNotificationBroadcast;


	private SharedPreference saveSharedPreference;

	//service variable

	NotificationService mBoundService;
	boolean mServiceBound = false;
	Intent intentService;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_dashboard);
		saveSharedPreference = new SharedPreference();
		AppUtils.changeStatusBarColor(DashboardActivity.this);

		initUi();
		getSideMenuViews();
		initBroadCast();
		//Call the webservices
		try{
			if (AppUtils.isOnline(DashboardActivity.this)) {
				mProgressHUD = ProgressHUD.show(DashboardActivity.this,"", true,true);
				mProgressHUD.setCancelable(false);
				callWebservice();
			}
			else{
				ErrorMsgDialog.showErrorAlert(DashboardActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
			}
		}catch (Exception ex){
			ex.printStackTrace();
		}
		setReviewValue("3");
	}

	/**
	 *Initializing layout views
	 */
	private void initUi() {
		mDrawerLayout = (DrawerLayout) findViewById(R.id.drawer_layout);

		imgDrawer = (ImageView) findViewById(R.id.imgvDrawer);
		mTxtVHeaderTitle = (TextView) findViewById(R.id.textViewHeaderTitle);
		rlDrawer = (RelativeLayout) findViewById(R.id.rlDrawer);
		flRingbel = (FrameLayout) findViewById(R.id.rlHeaderRingbell);
		mBadge = (TextView) findViewById(R.id.txt_badge);


		mLlSideMenuReferrals= (RelativeLayout) findViewById(R.id.llSideMenuReferrals);
		mLlSideMenuTeam= (RelativeLayout) findViewById(R.id.llSideMenuTeam);
		mLlSideMenuMessages= (RelativeLayout) findViewById(R.id.llSideMenuMessages);
		mLlSideMenuEvents= (RelativeLayout) findViewById(R.id.llSideMenuEvents);
		mLlSideMenuContacts= (RelativeLayout) findViewById(R.id.llSideMenuContacts);
		mLlSideMenuReviews= (RelativeLayout) findViewById(R.id.llSideMenuReviews);
		mLlSideMenuSettings= (RelativeLayout) findViewById(R.id.llSideMenuSettings);
		mLlSideMenuAccount= (RelativeLayout) findViewById(R.id.llSideMenuAccount);
		mLlSideMenuSuggestion= (RelativeLayout) findViewById(R.id.llSideMenuSuggestion);
		mLlSideMenuVideo= (RelativeLayout) findViewById(R.id.llSideMenuVideo);

		mReferralsCount=(TextView)findViewById(R.id.referralsCount);
		mMessageCount=(TextView)findViewById(R.id.messagesCount);

		rlDrawer.setOnClickListener(this);
		flRingbel.setOnClickListener(this);

		try {
			setDrawer();
		} catch (Exception e) {
			e.printStackTrace();
		}

		//Call the defult fragment
		mTxtVHeaderTitle.setText(getResources().getString(R.string.str_referrals));
		mLlSideMenuReferrals.setBackgroundColor(Color.WHITE);
		ReferralsFragment mReferralsFragment = new ReferralsFragment();
		attachedFragement(mReferralsFragment);
	}

	/**
	 * Initalizing sidemenuviews
	 */
	private void getSideMenuViews(){

		profileImageView=(ImageView)findViewById(R.id.profileImageView);
		profileEditImageView=(ImageView)findViewById(R.id.imageViewSideMenuEdit);
		mTxtvLoginUserName = (TextView) findViewById(R.id.textViewSideMenuUserName);
		mTextViewMemberType = (TextView) findViewById(R.id.textViewSideMenuMemberShipType);
		mTextViewGroupName = (TextView) findViewById(R.id.textViewSideMenuGroupId);
		mTextViewMemberRoal = (TextView) findViewById(R.id.textViewSideMenuGroupRoal);
		mTextViewShuffleDate = (TextView) findViewById(R.id.textViewSideMenuNextShuffle);
		mProfileProgressBar = (ProgressBar) findViewById(R.id.profileProgressBar);
		mTextViewProfilePercentage = (TextView) findViewById(R.id.textViewSideMenuProfilePercentage);

		mLlSideMenuLogoutl = (LinearLayout) findViewById(R.id.llSideMenuLogout);

		mLlRating = (LinearLayout) findViewById(R.id.llreview);

		mLlSideMenuReferrals.setOnClickListener(this);
		mLlSideMenuTeam.setOnClickListener(this);
		mLlSideMenuMessages.setOnClickListener(this);
		mLlSideMenuContacts.setOnClickListener(this);
		mLlSideMenuSettings.setOnClickListener(this);
		mLlSideMenuAccount.setOnClickListener(this);
		mLlSideMenuSuggestion.setOnClickListener(this);
		mLlSideMenuVideo.setOnClickListener(this);
		mLlSideMenuLogoutl.setOnClickListener(this);
		profileImageView.setOnClickListener(this);
		profileEditImageView.setOnClickListener(this);
		mTxtvLoginUserName.setOnClickListener(this);

		try{
			setSiderMenuValue();
		}catch (Exception ex){
			ex.printStackTrace();
		}


	}

	/**
	 * setting values for sidemenu views
	 * @throws Exception
	 */
	private void setSiderMenuValue() throws Exception{


		String userNameStr ="";

		if(saveSharedPreference.getUserFname(DashboardActivity.this) != null && !saveSharedPreference.getUserFname(DashboardActivity.this).equalsIgnoreCase("")){
			userNameStr =saveSharedPreference.getUserFname(DashboardActivity.this);
			String s1 = userNameStr.substring(0, 1).toUpperCase();
			userNameStr = s1 + userNameStr.substring(1);
		}

		if(saveSharedPreference.getUserLname(DashboardActivity.this) != null && !saveSharedPreference.getUserLname(DashboardActivity.this).equalsIgnoreCase("")){
			userNameStr =  userNameStr+" "+ saveSharedPreference.getUserLname(DashboardActivity.this);
		}

		mTxtvLoginUserName.setText("" + userNameStr);
		Picasso.with(DashboardActivity.this)
				.load(saveSharedPreference.getUserImage(DashboardActivity.this))
				.resize(150, 150)
				.placeholder(R.drawable.icon_user) // optional
				.into(profileImageView);
		/*private TextView mTextViewMemberType;
		private TextView mTextViewShuffleDate;
		private TextView mTextViewProfilePercentage;
		private TextView mTextViewMemberRoal;
		private TextView mTextViewGroupName;
		private ProgressBar mProfileProgressBar;*/
		if(null!=saveSharedPreference.getUserRoal(DashboardActivity.this) && !saveSharedPreference.getUserRoal(DashboardActivity.this).equalsIgnoreCase("")){
			String userRole =saveSharedPreference.getUserRoal(DashboardActivity.this);
			String s1 = userRole.substring(0, 1).toUpperCase();
			userRole = s1 + userRole.substring(1);
			mTextViewMemberRoal.setText(userRole);
		}

		if(null!=saveSharedPreference.getUserNextShuffle(DashboardActivity.this) && !saveSharedPreference.getUserNextShuffle(DashboardActivity.this).equalsIgnoreCase("")){
			//mTextViewMemberType.setText(saveSharedPreference.getUserMemberShip(DashboardActivity.this));
			mTextViewMemberType.setText(String.format(getString(R.string.profile_next_suffle), AppUtils.parseDateToddMMyyyyNotime(saveSharedPreference.getUserNextShuffle(DashboardActivity.this))));
		}

		if(null!=saveSharedPreference.getUserMemberShip(DashboardActivity.this) && !saveSharedPreference.getUserMemberShip(DashboardActivity.this).equalsIgnoreCase("")){
			//mTextViewShuffleDate.setText(String.format(getString(R.string.profile_next_suffle), AppUtils.parseDateToddMMyyyyNotime(saveSharedPreference.getUserNextShuffle(DashboardActivity.this))));
			mTextViewShuffleDate.setText(saveSharedPreference.getUserMemberShip(DashboardActivity.this) + " " + "Member");
		}

		if(null!=saveSharedPreference.getUserRating(DashboardActivity.this) && !saveSharedPreference.getUserRating(DashboardActivity.this).equalsIgnoreCase("")){
			setReviewValue(saveSharedPreference.getUserRating(DashboardActivity.this));
		}

		if(null!=saveSharedPreference.getGroupId(DashboardActivity.this) && !saveSharedPreference.getGroupId(DashboardActivity.this).equalsIgnoreCase("")){
			mTextViewGroupName.setText("Group " + saveSharedPreference.getGroupId(DashboardActivity.this));
		}
		if(null!=saveSharedPreference.getUserIProfilePercentage(DashboardActivity.this) && !saveSharedPreference.getUserIProfilePercentage(DashboardActivity.this).equalsIgnoreCase("")){
			mTextViewProfilePercentage.setText(String.format(getString(R.string.profile_percentage),saveSharedPreference.getUserIProfilePercentage(DashboardActivity.this)+"%"));
			mProfileProgressBar.setProgress(Integer.parseInt(saveSharedPreference.getUserIProfilePercentage(DashboardActivity.this)));
		}

	}


	private void setDrawer() throws Exception {
		actionBarDrawerToggle = new ActionBarDrawerToggle(this, mDrawerLayout, R.drawable.ic_up, R.string.drawer_open) {
			@Override
			public void onDrawerClosed(View view) {
				super.onDrawerClosed(view);
				invalidateOptionsMenu();
				imgDrawer.setImageResource(R.drawable.ic_drawer);
				// syncState();
			}
			@Override
			public void onDrawerOpened(View view) {
				super.onDrawerOpened(view);
				imgDrawer.setImageResource(R.drawable.ic_drawer);
				invalidateOptionsMenu();
				// syncState();
				setCountValues();
				try{
					if (AppUtils.isOnline(DashboardActivity.this)) {
						mProgressHUD = ProgressHUD.show(DashboardActivity.this,"", true,true);
						mProgressHUD.setCancelable(false);
						callWebservice();
					}
					else{
						ErrorMsgDialog.showErrorAlert(DashboardActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
					}
				}catch (Exception ex){
					ex.printStackTrace();
				}
			}
		};
		mDrawerLayout.setDrawerListener(actionBarDrawerToggle);
		// actionBarDrawerToggle.syncState();
	}

	/**
	 *method to set values geeting from notification service
	 *If its find user has been logged out from other device
	 *automatically it will be logout
	 */
	public void setCountValues(){
		if(saveSharedPreference.getRefrralsUnreadCount(DashboardActivity.this)>0) {
			mReferralsCount.setText(saveSharedPreference.getRefrralsUnreadCount(DashboardActivity.this) + "");
		}else{
			mReferralsCount.setText("");
		}
		if(saveSharedPreference.getMessageUnreadCount(DashboardActivity.this)>0) {
			mMessageCount.setText(saveSharedPreference.getMessageUnreadCount(DashboardActivity.this) + "");
		}else{
			mMessageCount.setText("");
		}
		if(saveSharedPreference.getTotalUnreadCount(DashboardActivity.this)>0) {
			mBadge.setText(saveSharedPreference.getTotalUnreadCount(DashboardActivity.this) + "");
		}else{
			mBadge.setText("");
		}
		if(saveSharedPreference.getIsLogin(DashboardActivity.this).equals(getResources().getString(R.string.is_login_text))) {
			//mReferralsCount.setText(saveSharedPreference.getRefrralsUnreadCount(DashboardActivity.this) + "");
			//new OkPressedResponse().alertAction(true);
			processAfterLogout();
		}else{
			//mReferralsCount.setText("");
		}

	}

	@Override
	public void onClick(View view) {
		if (view == rlDrawer) {
			if(mDrawerLayout.isDrawerOpen(Gravity.LEFT)){
				mDrawerLayout.closeDrawer(Gravity.LEFT);
			}
			else
				mDrawerLayout.openDrawer(Gravity.LEFT);
		} else if (view == flRingbel) {
			if(saveSharedPreference.getTotalUnreadCount(DashboardActivity.this)>0){
				Intent notification=new Intent(DashboardActivity.this,NotificationListActivity.class);
				startActivity(notification);
			}

		}

		//SideMenu Click
		else if (view == mLlSideMenuReferrals) {

			if(mDrawerLayout.isDrawerOpen(Gravity.LEFT)){
				mDrawerLayout.closeDrawer(Gravity.LEFT);
			}
			mTxtVHeaderTitle.setText(getResources().getString(R.string.str_referrals));
			mLlSideMenuReferrals.setBackgroundColor(Color.WHITE);
			mLlSideMenuMessages.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuContacts.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuTeam.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSettings.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuAccount.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSuggestion.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuVideo.setBackgroundResource(R.drawable.listitem_background);
			//Call the referrals fragment
			ReferralsFragment mReferralsFragment = new ReferralsFragment();
			attachedFragement(mReferralsFragment);
		}else if (view == mLlSideMenuMessages) {

			if(mDrawerLayout.isDrawerOpen(Gravity.LEFT)){
				mDrawerLayout.closeDrawer(Gravity.LEFT);
			}
			mTxtVHeaderTitle.setText(getResources().getString(R.string.str_messages));
			mLlSideMenuMessages.setBackgroundColor(Color.WHITE);
			mLlSideMenuReferrals.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuContacts.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuTeam.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSettings.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuAccount.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSuggestion.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuVideo.setBackgroundResource(R.drawable.listitem_background);
			//Call the referrals fragment
			MessageFragment mMessageFragment = new MessageFragment();
			attachedFragement(mMessageFragment);
		}else if (view == mLlSideMenuContacts) {

			if(mDrawerLayout.isDrawerOpen(Gravity.LEFT)){
				mDrawerLayout.closeDrawer(Gravity.LEFT);
			}
			mTxtVHeaderTitle.setText(getResources().getString(R.string.str_contacts));
			mLlSideMenuContacts.setBackgroundColor(Color.WHITE);
			mLlSideMenuReferrals.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuMessages.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuTeam.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSettings.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuAccount.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSuggestion.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuVideo.setBackgroundResource(R.drawable.listitem_background);
			//Call the referrals fragment
			ContactsFragment mContactsFragment = new ContactsFragment();
			attachedFragement(mContactsFragment);
		}else if (view == mLlSideMenuTeam) {

			if(mDrawerLayout.isDrawerOpen(Gravity.LEFT)){
				mDrawerLayout.closeDrawer(Gravity.LEFT);
			}
			mTxtVHeaderTitle.setText(getResources().getString(R.string.str_contacts));
			mLlSideMenuTeam.setBackgroundColor(Color.WHITE);
			mLlSideMenuContacts.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuReferrals.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuMessages.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSettings.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuAccount.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSuggestion.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuVideo.setBackgroundResource(R.drawable.listitem_background);
			//Call the referrals fragment
			TeamFragment mTeamFragment = new TeamFragment();
			attachedFragement(mTeamFragment);
		}else if (view == mLlSideMenuSettings) {

			if(mDrawerLayout.isDrawerOpen(Gravity.LEFT)){
				mDrawerLayout.closeDrawer(Gravity.LEFT);
			}
			mTxtVHeaderTitle.setText(getResources().getString(R.string.str_change_password));
			mLlSideMenuSettings.setBackgroundColor(Color.WHITE);
			mLlSideMenuContacts.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuReferrals.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuMessages.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuTeam.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuAccount.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSuggestion.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuVideo.setBackgroundResource(R.drawable.listitem_background);
			//Call the settings fragment
			SettingFragment mSettingFragment = new SettingFragment();
			attachedFragement(mSettingFragment);
		}else if (view == mLlSideMenuAccount  || view==profileImageView || view==mTxtvLoginUserName) {

			if(mDrawerLayout.isDrawerOpen(Gravity.LEFT)){
				mDrawerLayout.closeDrawer(Gravity.LEFT);
			}
			mTxtVHeaderTitle.setText(getResources().getString(R.string.str_profile_account));
			mLlSideMenuAccount.setBackgroundColor(Color.WHITE);
			mLlSideMenuContacts.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuReferrals.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuMessages.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuTeam.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSettings.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSuggestion.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuVideo.setBackgroundResource(R.drawable.listitem_background);
			//Call the settings fragment
			AccountFragment mAccountFragment = new AccountFragment();
			attachedFragement(mAccountFragment);
		}else if (view == profileEditImageView ) {
			//ErrorMsgDialog.alertOkCancelButtonCallBack(DashboardActivity.this, "",getResources().getString(R.string.wrng_str_logout_app), new OkPressedResponse());
			if(mDrawerLayout.isDrawerOpen(Gravity.LEFT)){
				mDrawerLayout.closeDrawer(Gravity.LEFT);
			}
			mLlSideMenuAccount.setBackgroundColor(Color.WHITE);
			mLlSideMenuContacts.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuReferrals.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuMessages.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuTeam.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSettings.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSuggestion.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuVideo.setBackgroundResource(R.drawable.listitem_background);
			Intent editProfile=new Intent(DashboardActivity.this, ProfileEditActivity.class);
			startActivityForResult(editProfile, ApplicationConstants.EDIT_RESULT_CODE);

		}else if (view == mLlSideMenuSuggestion ) {
			//ErrorMsgDialog.alertOkCancelButtonCallBack(DashboardActivity.this, "",getResources().getString(R.string.wrng_str_logout_app), new OkPressedResponse());
			if(mDrawerLayout.isDrawerOpen(Gravity.LEFT)){
				mDrawerLayout.closeDrawer(Gravity.LEFT);
			}
			mLlSideMenuSuggestion.setBackgroundColor(Color.WHITE);
			mLlSideMenuAccount.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuContacts.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuReferrals.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuMessages.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuTeam.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSettings.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuVideo.setBackgroundResource(R.drawable.listitem_background);
			//Call the Suggestion fragment
			SuggestionsFragment mSuggestionsFragment = new SuggestionsFragment();
			attachedFragement(mSuggestionsFragment);

		}else if (view == mLlSideMenuVideo ) {
			//ErrorMsgDialog.alertOkCancelButtonCallBack(DashboardActivity.this, "",getResources().getString(R.string.wrng_str_logout_app), new OkPressedResponse());
			if(mDrawerLayout.isDrawerOpen(Gravity.LEFT)){
				mDrawerLayout.closeDrawer(Gravity.LEFT);
			}
			mLlSideMenuVideo.setBackgroundColor(Color.WHITE);
			mLlSideMenuSuggestion.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuAccount.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuContacts.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuReferrals.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuMessages.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuTeam.setBackgroundResource(R.drawable.listitem_background);
			mLlSideMenuSettings.setBackgroundResource(R.drawable.listitem_background);
			//Call the VideoFragment fragment
			VideosFragment mVideosFragment = new VideosFragment();
			attachedFragement(mVideosFragment);

		}else if (view == mLlSideMenuLogoutl) {
			ErrorMsgDialog.alertOkCancelButtonCallBack(DashboardActivity.this, "",getResources().getString(R.string.wrng_str_logout_app), new OkPressedResponse());
		}


	}

	public void attachedFragement(Fragment fragment){
		if (fragment != null) {
			FragmentManager fragmentManager = getSupportFragmentManager();
			fragmentManager.beginTransaction().replace(R.id.frame_container, fragment).commit();
		}
	}

	/**
	 * THis method is use to call the webservice for updated profile
	 * and get the response and error from server
	 */
	private void callWebservice() throws Exception {
		//setting timeout for reading data as well as for connecting with client

		RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.PROFILE_ACTION_NAME, WebServiceConstants.STATE_CONTROL_NAME, DashboardActivity.this)).build();
		RequestApi requestApi = restAdapter.create(RequestApi.class);

		requestApi.getUserProfileRequest( new Callback<Response>() {
			@Override
			public void success(Response responseModel, Response response) {
				// Try to get response body
				mProgressHUD.dismiss();
				try {
					if (responseModel != null) {
						Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
						parseReferralsListService(WebServiceUtils.getResponseString(responseModel));
					} else {
						ErrorMsgDialog.showErrorAlert(DashboardActivity.this, "", getString(R.string.wrng_str_server_error));
					}
				} catch (Exception ex) {
					ex.printStackTrace();
				}
			}

			@Override
			public void failure(RetrofitError error) {
				error.printStackTrace();
				mProgressHUD.dismiss();
				ErrorMsgDialog.showErrorAlert(DashboardActivity.this, "", getString(R.string.wrng_str_server_error));
			}
		});
	}

	/**
	 * parse Profile Response
	 *
	 * @param responseStr
	 * @throws Exception
	 */

	private void parseReferralsListService(String responseStr) throws Exception {
		Log.e("responseStr", responseStr);

		Gson gson = new Gson();
		UserProfileResponseModel get_Response = gson.fromJson(responseStr, UserProfileResponseModel.class);

		if (get_Response != null) {
			//getResponse = get_Response;

			if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
				if (get_Response.getResult() != null) {
					setValuesONUI(get_Response);
				}
			} else {
				//ErrorMsgDialog.showErrorAlert(DashboardActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
			}
		} else {
			ErrorMsgDialog.showErrorAlert(DashboardActivity.this, "", getString(R.string.wrng_str_server_error));
		}
	}

	/**
	 * setting value on UI like first_name, last_name and user profile image
	 * @param get_Response
	 */
	public void setValuesONUI(UserProfileResponseModel get_Response){

		saveSharedPreference.setUserFname(DashboardActivity.this, get_Response.getResult().getFname());
		saveSharedPreference.setUserLname(DashboardActivity.this, get_Response.getResult().getLname());
		saveSharedPreference.setUserImage(DashboardActivity.this, get_Response.getResult().getProfile_image());

		saveSharedPreference.setGroupId(DashboardActivity.this, get_Response.getResult().getGroup_id());
		saveSharedPreference.setUserMemberShip(DashboardActivity.this, get_Response.getResult().getMembership_type());
		saveSharedPreference.setUserNextShuffle(DashboardActivity.this, get_Response.getResult().getShuffling_date());
		saveSharedPreference.setUserProfilePercentage(DashboardActivity.this, get_Response.getResult().getProfile_completion_status());
		saveSharedPreference.setUserRating(DashboardActivity.this, get_Response.getResult().getRating());
		saveSharedPreference.setUserRoal(DashboardActivity.this, get_Response.getResult().getGroup_role());

		try {
			setSiderMenuValue();
		}catch (Exception e){
			e.printStackTrace();
		}
	}

	/**
	 * CallBack class for logout
	 */
	class OkPressedResponse implements AlertCallBack {

		@Override
		public void alertAction(boolean select) {
			// Call web service here
			try{
				if (AppUtils.isOnline(DashboardActivity.this)) {
					mProgressHUD = ProgressHUD.show(DashboardActivity.this,"", true,true);
					mProgressHUD.setCancelable(false);
					callWebserviceLogout();
				}
				else{
					ErrorMsgDialog.showErrorAlert(DashboardActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
				}
			}catch (Exception ex){
				ex.printStackTrace();
			}
		}
	}

	/**
	 * THis method is use to call the webservice for getting logout from application and get
	 * after getting success changing the screen, i.e. redirect to login screen
	 */
	private void callWebserviceLogout() throws Exception {
		//setting timeout for reading data as well as for connecting with client

		RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.LOGOUT_ACTION_NAME, WebServiceConstants.STATE_CONTROL_NAME, DashboardActivity.this)).build();
		RequestApi requestApi = restAdapter.create(RequestApi.class);

		requestApi.getUserProfileRequest( new Callback<Response>() {
			@Override
			public void success(Response responseModel, Response response) {
				// Try to get response body
				mProgressHUD.dismiss();
				try {
					if (responseModel != null) {
						Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
						parseLogOut(WebServiceUtils.getResponseString(responseModel));
					} else {
						ErrorMsgDialog.showErrorAlert(DashboardActivity.this, "", getString(R.string.wrng_str_server_error));
					}
				} catch (Exception ex) {
					ex.printStackTrace();
				}
			}

			@Override
			public void failure(RetrofitError error) {
				error.printStackTrace();
				mProgressHUD.dismiss();
				ErrorMsgDialog.showErrorAlert(DashboardActivity.this, "", getString(R.string.wrng_str_server_error));
			}
		});
	}

	/**
	 * parse response for logout web service
	 *
	 * @param responseStr
	 * @throws Exception
	 */

	private void parseLogOut(String responseStr) throws Exception {
		Log.e(TAG, responseStr);

		Gson gson = new Gson();
		UserProfileResponseModel get_Response = gson.fromJson(responseStr, UserProfileResponseModel.class);

		if (get_Response != null) {
			//getResponse = get_Response;

			if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
					processAfterLogout();
			} else {
				//ErrorMsgDialog.showErrorAlert(DashboardActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
			}
		} else {
			ErrorMsgDialog.showErrorAlert(DashboardActivity.this, "", getString(R.string.wrng_str_server_error));
		}
	}

	/**
	 *doing processing after successful response of logout web service
	 *@param
	 */
	public void processAfterLogout(){

		try {
			//NotificationAlart.stop(DashboardActivity.this);
			AppUtils.clearUserData(DashboardActivity.this);
			Intent intObj = new Intent(DashboardActivity.this, LoginActivity.class);
			intObj.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
			startActivity(intObj);
			overridePendingTransition(R.anim.slide_in_up, R.anim.slide_out_up);
			finish();

		} catch (Exception e) {
			e.printStackTrace();
		}

	}



	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		// TODO Auto-generated method stub
		if (event.getAction() == KeyEvent.ACTION_DOWN) {
			switch (keyCode) {
				case KeyEvent.KEYCODE_HOME:
					//The Code Want to Perform.
					Intent gotoHome= new Intent(Intent.ACTION_MAIN);
					gotoHome.addCategory(Intent.CATEGORY_HOME);
					gotoHome.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
					startActivity(gotoHome);
					return true;
			}
		}

		return super.onKeyDown(keyCode, event);
	}

	/**
	 * Initializing bradcast for getting notifications and
	 * update it to dashboard screen
	 */
	public void initBroadCast() {
		mNotificationBroadcast = new BroadcastReceiver() {

			@Override
			public void onReceive(Context context, Intent intent) {
				String versionValue = intent
						.getStringExtra(ApplicationConstants.LAST_DATAUPDATE_KEY);
				if (versionValue.equals(ApplicationConstants.LAST_DATAUPDATE_VALUE)) {
					setCountValues();
				}
			}
		};
	}

	@Override
	protected void onResume() {
		super.onResume();
		setCountValues();
		registerReceiver(mNotificationBroadcast, new IntentFilter(
				ApplicationConstants.LAST_DATAUPDATE_ACTION));
		//NotificationAlart.start(DashboardActivity.this);
	}

	@Override
	protected void onPause() {
		super.onPause();
		try {
			//NotificationAlart.stop(DashboardActivity.this);
			unregisterReceiver(mNotificationBroadcast);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	/**
	 * Control for search order selecting from each screen
	 * like received,sent,archive received and send archive referrals.
	 * @param view
	 */
	public void onCheckboxClicked(View view) {
		// Is the view now checked?
		AppUtils.checkBoxClicked(view, DashboardActivity.this);
	}


	@Override
	protected void onStart() {
		super.onStart();
			ApplicationConstants.NOTIFICATION_STATUS=true;
			intentService = new Intent(this, NotificationService.class);
		startService(intentService);
			bindService(intentService, mServiceConnection, Context.BIND_AUTO_CREATE);
	}

	@Override
	protected void onStop() {
		super.onStop();
		if (mServiceBound) {
			unbindService(mServiceConnection);
			mServiceBound = false;
			ApplicationConstants.NOTIFICATION_STATUS=false;
		}
	}

	private ServiceConnection mServiceConnection = new ServiceConnection() {

		@Override
		public void onServiceDisconnected(ComponentName name) {
			mServiceBound = false;
		}

		@Override
		public void onServiceConnected(ComponentName name, IBinder service) {
			NotificationService.MyBinder myBinder = (NotificationService.MyBinder) service;
			mBoundService = myBinder.getService();
			mServiceBound = true;
		}
	};

	@Override
	public void onBackPressed() {
		super.onBackPressed();
		MessageFragment.mStatusSearch=false;
		ReferralsFragment.mStatusSearch=false;
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		super.onActivityResult(requestCode, resultCode, data);
		if(requestCode==ApplicationConstants.EDIT_RESULT_CODE){
			AccountFragment mAccountFragment = new AccountFragment();
			attachedFragement(mAccountFragment);
		}
	}

	/**
	 * Setting review result on custom layout
	 * @param
	 */
	public void setReviewValue(String rating){
		mLlRating.removeAllViews();
		if(null!=rating){
			int ratingValue=Integer.parseInt(rating);
			for(int i=0;i<ratingValue;i++){
				LayoutInflater mInflater;
				mInflater = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
				LinearLayout view = (LinearLayout) mInflater.inflate(R.layout.row_review_layout, null);
				ImageView iv=(ImageView) view.findViewById(R.id.iconrating);
				iv.setBackgroundResource(R.drawable.icon_fill_star);
				mLlRating.addView(view);

			}
			int remainingReview=5-ratingValue;
			for(int i=0;i<remainingReview;i++){
				LayoutInflater mInflater;
				mInflater = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
				LinearLayout view = (LinearLayout) mInflater.inflate(R.layout.row_review_layout, null);
				ImageView iv=(ImageView) view.findViewById(R.id.iconrating);
				iv.setBackgroundResource(R.drawable.icon_no_review);
				mLlRating.addView(view);
			}
		}
	}
}
