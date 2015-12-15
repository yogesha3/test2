package com.app.foxhopr.SharedPrefrances;

import android.content.Context;
import android.content.SharedPreferences;

import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.webservice.models.MeetingDayModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.ArrayList;

public class SharedPreference {
	/**
	 * Shared Preferences Class to save/hold the values
	 */
	private final String APPLICATION_SHARED_PREFERENCE = "FOXHOPER_PREFERENCE";

	String USER_ID 										= "user_id";
	String USER_EMAIL 									= "user_email";
	String USER_IS_ACTIVE 								= "is_active";
	String USER_IS_CONFIRM 								= "is_confirm";
	String USER_IMAGE 									= "profile_image";
	String USER_PROFILE_PERCENTAGE 						= "profile_completaion_percentage";
	String USER_RATING 									= "rating";
	String USER_ROAL 									= "user_roal";
	String USER_MEMBERSHIPTYPE 							= "membership_type";
	String USER_NEXT_SHUFFLE 							= "next_shuffle";
	String GCM_REGISTRATION_ID 							= "gcm_reg_id";
	String USER_FNAME 									= "fname";
	String USER_LNAME 									= "lname";
	String GROUP_TYPE 									= "group_type";
	String GROUP_ID 									= "group_id";
	String CITY 										= "city";
	String ZIPCODE 										= "zipcode";
	String SEARCH_TEXT 									= "search_text";
	String SEARCH_ORDER 								= "search_order";
	String RERERRAL_UNREAD_COUNT 						= "RERERRAL_UNREAD_COUNT";
	String MESSAGE_UNREAD_COUNT 						= "MESSAGE_UNREAD_COUNT";

	String TOTAL_RERERRAL_UNREAD_COUNT 					= "TOTAL_RERERRAL_UNREAD_COUNT";
	String TOTAL_MESSAGE_UNREAD_COUNT 					= "TOTAL_MESSAGE_UNREAD_COUNT";
	String TOTAL_UNREAD_COUNT							= "TOTAL_UNREAD_COUNT";

	String SENT_SEARCH_TEXT 							= "SENT_SEARCH_TEXT";
	String SENT_SEARCH_ORDER 							= "SENT_SEARCH_ORDER";
	String IS_LOGIN 									= "IS_LOGIN";
	String MEETING_DAYS_LOCAL 							= "MEETING_DAYS_LOCAL";
	String MEETING_TIMES_LOCAL 							= "MEETING_TIMES_LOCAL";
	String MEETING_DAYS_GLOBAL 							= "MEETING_DAYS_GLOBAL";
	String MEETING_TIMES_GLOBAL 						= "MEETING_TIMES_GLOBAL";
	String SELECTED_GROUP 								= "SELECTED_GROUP";
	String SORT_LOCAL 									= "SORT_LOCAL";
	String SORT_GLOBAL 									= "SORT_GLOBAL";
	String MILES_LOCAL 									= "MILES_LOCAL";
	String LOCATION_GLOBAL 								= "LOCATION_GLOBAL";

	/**
	 * setUserId
	 * @param context
	 * @param userId
	 */

	public void setUserId(Context context, String userId) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(USER_ID, userId);
		editor.commit();
	}
	/**
	 * 
	 * @param context
	 * @return String
	 */
	public String getUserId(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(USER_ID, "");
	}


	/**
	 * setUserEmail
	 * @param context
	 * @param email
	 */

	public void setUserEmail(Context context, String email) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(USER_EMAIL, email);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getUserEmail(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(USER_EMAIL, "");
	}


	/**
	 * setUserEmail
	 * @param context
	 * @param userActive
	 */

	public void setUserIsActive(Context context, String userActive) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(USER_IS_ACTIVE, userActive);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getUserIsActive(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(USER_IS_ACTIVE, "");
	}


	/**
	 * setUserIsConfirm
	 * @param context
	 * @param userIsConfirm
	 */

	public void setUserIsConfirm(Context context, String userIsConfirm) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(USER_IS_CONFIRM, userIsConfirm);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getUserIsConfirm(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(USER_IS_CONFIRM, "");
	}


	/**
	 * setUserImage
	 * @param context
	 * @param userIMage
	 */

	public void setUserImage(Context context, String userIMage) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(USER_IMAGE, userIMage);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getUserImage(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(USER_IMAGE, "");
	}

	/**
	 * setUserProfilePercentage
	 * @param context
	 * @param userProfilePercentage
	 */

	public void setUserProfilePercentage(Context context, String userProfilePercentage) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(USER_PROFILE_PERCENTAGE, userProfilePercentage);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getUserIProfilePercentage(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(USER_PROFILE_PERCENTAGE, "");
	}

	/**
	 * setUserRating
	 * @param context
	 * @param userRating
	 */

	public void setUserRating(Context context, String userRating) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(USER_RATING, userRating);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getUserRating(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(USER_RATING, "");
	}

	/**
	 * setUserRoal
	 * @param context
	 * @param userRoal
	 */

	public void setUserRoal(Context context, String userRoal) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(USER_ROAL, userRoal);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getUserRoal(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(USER_ROAL, "");
	}

	/**
	 * setUserMemberShip
	 * @param context
	 * @param userMemberShip
	 */

	public void setUserMemberShip(Context context, String userMemberShip) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(USER_MEMBERSHIPTYPE, userMemberShip);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getUserMemberShip(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(USER_MEMBERSHIPTYPE, "");
	}

	/**
	 * setUserNextShuffle
	 * @param context
	 * @param userNextShuffle
	 */

	public void setUserNextShuffle(Context context, String userNextShuffle) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(USER_NEXT_SHUFFLE, userNextShuffle);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getUserNextShuffle(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(USER_NEXT_SHUFFLE, "");
	}

	/**
	 * setUserFname
	 * @param context
	 * @param userFname
	 */

	public void setUserFname(Context context, String userFname) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(USER_FNAME, userFname);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getUserFname(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(USER_FNAME, "");
	}

	/**
	 * setUserLname
	 * @param context
	 * @param userLname
	 */

	public void setUserLname(Context context, String userLname) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(USER_LNAME, userLname);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getUserLname(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(USER_LNAME, "");
	}

	/**
	 * setUserLname
	 * @param context
	 * @param userLname
	 */

	public void setGroupId(Context context, String userLname) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(GROUP_ID, userLname);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getGroupId(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(GROUP_ID, "");
	}

	/**
	 * setGroupType
	 * @param context
	 * @param groupType
	 */

	public void setGroupType(Context context, String groupType) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(GROUP_ID, groupType);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getGroupType(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(GROUP_TYPE, "");
	}

	/**
	 * setting searchtext value
	 * @param context
	 * @param groupType
	 */
	public void setSearchText(Context context, String groupType) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(SEARCH_TEXT, groupType);
		editor.commit();
	}
	/**
	 * getting last saved search text value
	 * @param context
	 * @return String
	 */
	public String getSearchText(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(SEARCH_TEXT, "");
	}


	/**
	 * setting search order value
	 * @param context
	 * @param groupType
	 */
	public void setSearrchOrder(Context context, int groupType) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putInt(SEARCH_ORDER, groupType);
		editor.commit();
	}
	/**
	 * getting last saved searchorder  value
	 * @param context
	 * @return String
	 */
	public int getSearchOrder(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getInt(SEARCH_ORDER, R.id.dateNewest);
	}

	/**
	 * setting sent searchtext value
	 * @param context
	 * @param groupType
	 */
	public void setSentSearchText(Context context, String groupType) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(SENT_SEARCH_TEXT, groupType);
		editor.commit();
	}
	/**
	 * getting last saved sent search text value
	 * @param context
	 * @return String
	 */
	public String getSentSearchText(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(SENT_SEARCH_TEXT, "");
	}


	/**
	 * setting sent search order value
	 * @param context
	 * @param groupType
	 */
	public void setSentSearrchOrder(Context context, int groupType) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putInt(SENT_SEARCH_ORDER, groupType);
		editor.commit();
	}
	/**
	 * getting last saved  sent searchorder  value
	 * @param context
	 * @return String
	 */
	public int getSentSearchOrder(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getInt(SENT_SEARCH_ORDER, R.id.dateNewest);
	}

	/**
	 * setting referral unread count  value
	 * @param context
	 * @param groupType
	 */
	public void setReferralsUnreadCount(Context context, int groupType) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putInt(RERERRAL_UNREAD_COUNT, groupType);
		editor.commit();
	}
	/**
	 * getting  referral unread count  value
	 * @param context
	 * @return String
	 */
	public int getRefrralsUnreadCount(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getInt(RERERRAL_UNREAD_COUNT, 0);
	}

	/**
	 * setting referral unread count  value
	 * @param context
	 * @param groupType
	 */
	public void setTotalReferralsUnreadCount(Context context, int groupType) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putInt(TOTAL_RERERRAL_UNREAD_COUNT, groupType);
		editor.commit();
	}
	/**
	 * getting  referral unread count  value
	 * @param context
	 * @return String
	 */
	public int getTotalRefrralsUnreadCount(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getInt(TOTAL_RERERRAL_UNREAD_COUNT, 0);
	}

	/**
	 *setting message unread count  value
	 * @param context
	 * @param groupType
	 */
	public void setMessageUnreadCount(Context context, int groupType) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putInt(MESSAGE_UNREAD_COUNT, groupType);
		editor.commit();
	}
	/**
	 * getting referral unread count  value
	 * @param context
	 * @return String
	 */
	public int getMessageUnreadCount(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getInt(MESSAGE_UNREAD_COUNT, 0);
	}

	/**
	 *setting message unread count  value
	 * @param context
	 * @param groupType
	 */
	public void setTotalMessageUnreadCount(Context context, int groupType) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putInt(TOTAL_MESSAGE_UNREAD_COUNT, groupType);
		editor.commit();
	}
	/**
	 * getting referral unread count  value
	 * @param context
	 * @return String
	 */
	public int getTotalMessageUnreadCount(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getInt(TOTAL_MESSAGE_UNREAD_COUNT, 0);
	}

	/**
	 *setting total unread count  value
	 * @param context
	 * @param groupType
	 */
	public void setTotalUnreadCount(Context context, int groupType) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putInt(TOTAL_UNREAD_COUNT, groupType);
		editor.commit();
	}
	/**
	 * getting referral unread count  value
	 * @param context
	 * @return String
	 */
	public int getTotalUnreadCount(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getInt(TOTAL_UNREAD_COUNT, 0);
	}

	/**
	 *setting total unread count  value
	 * @param context
	 * @param groupType
	 */
	public void setIsLogin(Context context, String groupType) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(IS_LOGIN, groupType);
		editor.commit();
	}
	/**
	 * getting referral unread count  value
	 * @param context
	 * @return String
	 */
	public String getIsLogin(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(IS_LOGIN, "");
	}




	/**
	 * setUserLname
	 * @param context
	 * @param city
	 */

	public void setCity(Context context, String city) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(CITY, city);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getCity(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(CITY, "");
	}

	/**
	 * setUserLname
	 * @param context
	 * @param zipcode
	 */

	public void setZipCode(Context context, String zipcode) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(ZIPCODE, zipcode);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getZipCode(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(ZIPCODE, "");
	}

	//Meeting days

	/**
	 *setting total unread count  value
	 * @param context
	 * @param meetingDays
	 */
	public void setMeetingDays(Context context, ArrayList<MeetingDayModel> meetingDays) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		//editor.putString(MEETING_DAYS, groupType);
		try {
			if(null!=meetingDays) {
				Gson gson = new Gson();
				String jsonFavorites = gson.toJson(meetingDays);
				editor.putString(MEETING_DAYS_LOCAL, jsonFavorites);
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		editor.commit();
	}
	/**
	 * getting referral unread count  value
	 * @param context
	 * @return String
	 */
	public String getMeetingDays(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(MEETING_DAYS_LOCAL, "");
	}

	/**
	 *setting total unread count  value
	 * @param context
	 * @param meetingDays
	 */
	public void setMeetingTimes(Context context, ArrayList<MeetingDayModel> meetingDays) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		try {
			if(null!=meetingDays) {
				Gson gson = new Gson();
				String jsonFavorites = gson.toJson(meetingDays);
				editor.putString(MEETING_TIMES_LOCAL, jsonFavorites);
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		editor.commit();
	}
	/**
	 * getting referral unread count  value
	 * @param context
	 * @return String
	 */
	public String getMeetingTimes(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(MEETING_TIMES_LOCAL, "");
	}

	//Meeting days

	/**
	 *setting total unread count  value
	 * @param context
	 * @param meetingDays
	 */
	public void setMeetingDaysGlobal(Context context, ArrayList<MeetingDayModel> meetingDays) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		//editor.putString(MEETING_DAYS, groupType);
		try {
			if(null!=meetingDays) {
				Gson gson = new Gson();
				String jsonFavorites = gson.toJson(meetingDays);
				editor.putString(MEETING_DAYS_GLOBAL, jsonFavorites);
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		editor.commit();
	}
	/**
	 * getting referral unread count  value
	 * @param context
	 * @return String
	 */
	public String getMeetingDaysGlobal(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(MEETING_DAYS_GLOBAL, "");
	}

	/**
	 *setting total unread count  value
	 * @param context
	 * @param meetingDays
	 */
	public void setMeetingTimesGlobal(Context context, ArrayList<MeetingDayModel> meetingDays) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		try {
			if(null!=meetingDays) {
				Gson gson = new Gson();
				String jsonFavorites = gson.toJson(meetingDays);
				editor.putString(MEETING_TIMES_GLOBAL, jsonFavorites);
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		editor.commit();
	}
	/**
	 * getting referral unread count  value
	 * @param context
	 * @return String
	 */
	public String getMeetingTimesGlobal(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(MEETING_TIMES_GLOBAL, "");
	}

	/**
	 * setUserLname
	 * @param context
	 * @param city
	 */

	public void setSelectedGroupType(Context context, String city) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(SELECTED_GROUP, city);
		editor.commit();
	}
	/**
	 *
	 * @param context
	 * @return String
	 */
	public String getSelectedGroupType(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(SELECTED_GROUP, ApplicationConstants.GROUP_LOCAL);
	}

	/**
	 * setSortyByLocal
	 * @param context
	 * @param city
	 */

	public void setSortyByLocal(Context context, String city) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(SORT_LOCAL, city);
		editor.commit();
	}
	/**
	 *getSortByLocal
	 * @param context
	 * @return String
	 */
	public String getSortByLocal(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(SORT_LOCAL, "");
	}

	/**
	 * setSortyByGlobal
	 * @param context
	 * @param city
	 */

	public void setSortyByGlobal(Context context, String city) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(SORT_GLOBAL, city);
		editor.commit();
	}
	/**
	 *getSortByGlobal
	 * @param context
	 * @return String
	 */
	public String getSortByGlobal(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(SORT_GLOBAL, "");
	}

	/**
	 *setMiles
	 * @param context
	 * @return String
	 */
	public void setMiles(Context context, String city) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(MILES_LOCAL, city);
		editor.commit();
	}
	/**
	 *getMiles
	 * @param context
	 * @return String
	 */
	public String getMiles(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(MILES_LOCAL, "5");
	}


	/**
	 *setLocationGlobal
	 * @param context
	 * @return String
	 */
	public void setLocationGlobal(Context context, String city) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		SharedPreferences.Editor editor = sp.edit();
		editor.putString(LOCATION_GLOBAL, city);
		editor.commit();
	}
	/**
	 *getLocationGlobal
	 * @param context
	 * @return String
	 */
	public String getLocationGlobal(Context context) {
		SharedPreferences sp = context.getSharedPreferences(APPLICATION_SHARED_PREFERENCE, Context.MODE_PRIVATE);
		return sp.getString(LOCATION_GLOBAL, "");
	}

}