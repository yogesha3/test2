package com.app.foxhopr.utils;

import android.annotation.SuppressLint;
import android.annotation.TargetApi;
import android.app.Activity;
import android.app.ActivityManager;
import android.content.ContentUris;
import android.content.Context;
import android.content.pm.ActivityInfo;
import android.content.res.Configuration;
import android.database.Cursor;
import android.graphics.Typeface;
import android.net.ConnectivityManager;
import android.net.Uri;
import android.os.Build;
import android.os.Environment;
import android.provider.DocumentsContract;
import android.provider.MediaStore;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.CheckBox;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.webservice.models.MeetingDayModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.io.File;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.TimeZone;
import java.util.regex.Matcher;
import java.util.regex.Pattern;


public class AppUtils {

    public static String TAG = "AppUtils";

    @SuppressLint("NewApi")
    public static void changeStatusBarColor(Activity mCtx) {

        Window window = mCtx.getWindow();
        window.addFlags(WindowManager.LayoutParams.FLAG_DRAWS_SYSTEM_BAR_BACKGROUNDS);
        window.clearFlags(WindowManager.LayoutParams.FLAG_TRANSLUCENT_STATUS);
        if (Build.VERSION.SDK_INT >= 21) {
            window.setStatusBarColor(mCtx.getResources().getColor(R.color.status_bar_color));
        }
    }


    /**
     * Check whether is Internet Connection is available or not.
     *
     * @param mCtx
     * @return
     */
    public static boolean isOnline(Context mCtx) {
        if (mCtx != null) {
            ConnectivityManager conMgr = (ConnectivityManager) mCtx.getSystemService(Context.CONNECTIVITY_SERVICE);
            // ARE WE CONNECTED TO THE NET
            if (conMgr.getActiveNetworkInfo() != null && conMgr.getActiveNetworkInfo().isAvailable() && conMgr.getActiveNetworkInfo().isConnected()) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public static String parseDateToddMMyyyyNotime(String time) {
        String inputPattern = "yyyy-MM-dd";
        String outputPattern = "MMM dd, yyyy";
        SimpleDateFormat inputFormat = new SimpleDateFormat(inputPattern);
        SimpleDateFormat outputFormat = new SimpleDateFormat(outputPattern);

        Date date = null;
        String str = null;
        try {
            date = inputFormat.parse(time);
            str = outputFormat.format(date);
        } catch (Exception e) {
            e.printStackTrace();
        }
        return str;
    }

    public static String parseDateToddMMyyyy(String time) {
        String inputPattern = "yyyy-MM-dd HH:mm:ss";
        String outputPattern = "MMM dd, yyyy";
        SimpleDateFormat inputFormat = new SimpleDateFormat(inputPattern);
        SimpleDateFormat outputFormat = new SimpleDateFormat(outputPattern);

        Date date = null;
        String str = null;
        try {
            date = inputFormat.parse(time);
            str = outputFormat.format(date);
        } catch (Exception e) {
            e.printStackTrace();
        }
        return str;
    }

    public static String parseDateToddMMyyyyTime(String time) {
        String inputPattern = "yyyy-MM-dd HH:mm:ss";
        String outputPattern = "HH:mm a";
        SimpleDateFormat inputFormat = new SimpleDateFormat(inputPattern);
        SimpleDateFormat outputFormat = new SimpleDateFormat(outputPattern);

        Date date = null;
        String str = null;
        try {
            date = inputFormat.parse(time);
            str = outputFormat.format(date);
        } catch (Exception e) {
            e.printStackTrace();
        }
        return str;
    }
    /*"meetingDate": "2015-10-23",
            "meetingTime": "17:00",*/

    public static String parseDay(String time) {
        String inputPattern = "yyyy-MM-dd HH:mm:ss";
        String outputPattern = "EEEE";
        SimpleDateFormat inputFormat = new SimpleDateFormat(inputPattern);
        SimpleDateFormat outputFormat = new SimpleDateFormat(outputPattern);

        Date date = null;
        String str = null;
        try {
            date = inputFormat.parse(time);
            str = outputFormat.format(date);
        } catch (Exception e) {
            e.printStackTrace();
        }
        return str;
    }

    public static String parseDaytime(String time) {
        String inputPattern = "yyyy-MM-dd HH:mm:ss";
        String outputPattern = "hh:mm a";
        SimpleDateFormat inputFormat = new SimpleDateFormat(inputPattern);
        SimpleDateFormat outputFormat = new SimpleDateFormat(outputPattern);

        Date date = null;
        String str = null;
        try {
            date = inputFormat.parse(time);
            str = outputFormat.format(date);
        } catch (Exception e) {
            e.printStackTrace();
        }
        return str;
    }

    public static String getDateString(String longValue) {
        Calendar cal = Calendar.getInstance();
        long millisecond = Long.parseLong(longValue);
        TimeZone tz = cal.getTimeZone();//get your local time zone.
        SimpleDateFormat sdf = new SimpleDateFormat("MMM dd, yyyy");
        sdf.setTimeZone(tz);//set time zone.
        String localTime = sdf.format(new Date(millisecond * 1000l));
        Date date = new Date();
        try {
            date = sdf.parse(localTime);//get local date
        } catch (ParseException e) {
            e.printStackTrace();
        }
        //return date.toString();
        return sdf.format(date);
    }

    /**
     * Get a file path from a Uri. This will get the the path for Storage Access Framework Documents, as well as the _data field for the MediaStore and other file-based ContentProviders.
     *
     * @param context The context.
     * @param uri     The Uri to query.
     * @author paulburke
     */
    @TargetApi(Build.VERSION_CODES.KITKAT)
    @SuppressLint("NewApi")
    public static String getPath(final Context context, final Uri uri) {

        final boolean isKitKat = Build.VERSION.SDK_INT >= Build.VERSION_CODES.KITKAT;

        // DocumentProvider
        if (isKitKat && DocumentsContract.isDocumentUri(context, uri)) {
            // ExternalStorageProvider
            if (isExternalStorageDocument(uri)) {
                final String docId = DocumentsContract.getDocumentId(uri);
                final String[] split = docId.split(":");
                final String type = split[0];

                if ("primary".equalsIgnoreCase(type)) {
                    return Environment.getExternalStorageDirectory() + "/" + split[1];
                }

            }
            // DownloadsProvider
            else if (isDownloadsDocument(uri)) {

                final String id = DocumentsContract.getDocumentId(uri);
                final Uri contentUri = ContentUris.withAppendedId(Uri.parse("content://downloads/public_downloads"), Long.valueOf(id));

                return getDataColumn(context, contentUri, null, null);
            }
            // MediaProvider
            else if (isMediaDocument(uri)) {
                final String docId = DocumentsContract.getDocumentId(uri);
                final String[] split = docId.split(":");
                final String type = split[0];

                Uri contentUri = null;
                if ("image".equals(type)) {
                    contentUri = MediaStore.Images.Media.EXTERNAL_CONTENT_URI;
                } else if ("video".equals(type)) {
                    contentUri = MediaStore.Video.Media.EXTERNAL_CONTENT_URI;
                } else if ("audio".equals(type)) {
                    contentUri = MediaStore.Audio.Media.EXTERNAL_CONTENT_URI;
                } else {
                    contentUri = MediaStore.Audio.Media.EXTERNAL_CONTENT_URI;
                }

                final String selection = "_id=?";
                final String[] selectionArgs = new String[]{split[1]};

                return getDataColumn(context, contentUri, selection, selectionArgs);
            }
        }
        // MediaStore (and general)
        else if ("content".equalsIgnoreCase(uri.getScheme())) {

            // Return the remote address
            if (isGooglePhotosUri(uri))
                return uri.getLastPathSegment();

            return getDataColumn(context, uri, null, null);
        }
        // File
        else if ("file".equalsIgnoreCase(uri.getScheme())) {
            return uri.getPath();
        }

        return null;
    }

    /**
     * Method for return file path of Gallery image
     *
     * @param context
     * @param uri
     * @return path of the selected image file from gallery
     */
    @SuppressLint("NewApi")
    public static String getPaths(final Context context, final Uri uri) {

        // check here to KITKAT or new version
        final boolean isKitKat = Build.VERSION.SDK_INT >= Build.VERSION_CODES.KITKAT;

        // DocumentProvider
        if (isKitKat && DocumentsContract.isDocumentUri(context, uri)) {

            // ExternalStorageProvider
            if (isExternalStorageDocument(uri)) {
                final String docId = DocumentsContract.getDocumentId(uri);
                final String[] split = docId.split(":");
                final String type = split[0];

                if ("primary".equalsIgnoreCase(type)) {
                    return Environment.getExternalStorageDirectory() + "/" + split[1];
                }
            }
            // DownloadsProvider
            else if (isDownloadsDocument(uri)) {

                final String id = DocumentsContract.getDocumentId(uri);
                final Uri contentUri = ContentUris.withAppendedId(Uri.parse("content://downloads/public_downloads"), Long.valueOf(id));

                return getDataColumn(context, contentUri, null, null);
            }

        }
        // MediaStore (and general)
        else if ("content".equalsIgnoreCase(uri.getScheme())) {

            // Return the remote address
            if (isGooglePhotosUri(uri))
                return uri.getLastPathSegment();

            return getDataColumn(context, uri, null, null);
        }
        // File
        else if ("file".equalsIgnoreCase(uri.getScheme())) {
            return uri.getPath();
        }

        return null;
    }

    /**
     * Get the value of the data column for this Uri. This is useful for MediaStore Uris, and other file-based ContentProviders.
     *
     * @param context       The context.
     * @param uri           The Uri to query.
     * @param selection     (Optional) Filter used in the query.
     * @param selectionArgs (Optional) Selection arguments used in the query.
     * @return The value of the _data column, which is typically a file path.
     */
    public static String getDataColumn(Context context, Uri uri, String selection, String[] selectionArgs) {

        Cursor cursor = null;
        final String column = "_data";
        final String[] projection = {column};

        try {
            cursor = context.getContentResolver().query(uri, projection, selection, selectionArgs, null);
            if (cursor != null && cursor.moveToFirst()) {
                final int index = cursor.getColumnIndexOrThrow(column);
                return cursor.getString(index);
            }
        } finally {
            if (cursor != null)
                cursor.close();
        }
        return null;
    }

    /**
     * @param uri The Uri to check.
     * @return Whether the Uri authority is ExternalStorageProvider.
     */
    public static boolean isExternalStorageDocument(Uri uri) {
        return "com.android.externalstorage.documents".equals(uri.getAuthority());
    }

    /**
     * @param uri The Uri to check.
     * @return Whether the Uri authority is DownloadsProvider.
     */
    public static boolean isDownloadsDocument(Uri uri) {
        return "com.android.providers.downloads.documents".equals(uri.getAuthority());
    }

    /**
     * @param uri The Uri to check.
     * @return Whether the Uri authority is MediaProvider.
     */
    public static boolean isMediaDocument(Uri uri) {
        return "com.android.providers.media.documents".equals(uri.getAuthority());
    }

    /**
     * @param uri The Uri to check.
     * @return Whether the Uri authority is Google Photos.
     */
    public static boolean isGooglePhotosUri(Uri uri) {
        return "com.google.android.apps.photos.content".equals(uri.getAuthority());
    }


    public static String getFileNameFromUrl(String urlString) {


        return urlString.substring(urlString.lastIndexOf('/') + 1).split("\\?")[0].split("#")[0];
    }

    public static String getFileExt(String fileName) {
        return fileName.substring((fileName.lastIndexOf(".") + 1), fileName.length());
    }

    public static boolean checkFileExtension(String strExtension) {
        String[] extensionArr = {"doc", "docx", "xls", "xlsx", "pdf", "jpg", "jpeg", "png"};
        return Arrays.asList(extensionArr).contains(strExtension);
    }


    public static float getFileSize(String filePath) throws Exception {
        File file = new File(filePath);
        long length = file.length();

        float newlength = length / 1024f;

        newlength = newlength / 1024;

		/*if (newlength < 10)
            return true;*/

        return newlength;
    }

    /**
     * getting value of selected radio button
     *
     * @return content description for radio
     */
    public static String getRadioDataMessageSent(Activity mContext) {
        SharedPreference sharedPreference = new SharedPreference();

        String mSortText = "";
        //if(null!=mSearchRadioButton && mSearchRadioButton.getCheckedRadioButtonId()!=-1) {
        switch (sharedPreference.getSearchOrder(mContext)) {

            case R.id.referrelAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_referrel_asc_value);
                break;
            case R.id.referrelZtoA:
                mSortText = mContext.getResources().getString(R.string.str_referrel_desc_value);
                break;
            case R.id.fromAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_recipient_users_asc_value);
                break;
            case R.id.fromZtoA:
                mSortText = mContext.getResources().getString(R.string.str_recipient_users_desc_value);
                break;
            case R.id.dateNewest:
                mSortText = mContext.getResources().getString(R.string.str_date_asc_value);
                break;
            case R.id.dateOldest:
                mSortText = mContext.getResources().getString(R.string.str_date_desc_value);
                break;
            case R.id.statusAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_status_asc_value);
                break;
            case R.id.statusZtoA:
                mSortText = mContext.getResources().getString(R.string.str_status_desc_value);
                break;
            case R.id.valueAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_value_asc_value);
                break;
            case R.id.valueZtoA:
                mSortText = mContext.getResources().getString(R.string.str_value_desc_value);
                break;
            case R.id.subjectAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_subject_asc_value);
                break;
            case R.id.subjectZtoA:
                mSortText = mContext.getResources().getString(R.string.str_subject_desc_value);
                break;

        }
        //}
        return mSortText;
    }

    /**
     * getting value of selected radio button
     *
     * @return content description for radio
     */
    public static String getRadioData(Activity mContext) {
        SharedPreference sharedPreference = new SharedPreference();

        String mSortText = "";
        //if(null!=mSearchRadioButton && mSearchRadioButton.getCheckedRadioButtonId()!=-1) {
        switch (sharedPreference.getSearchOrder(mContext)) {

            case R.id.referrelAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_referrel_asc_value);
                break;
            case R.id.referrelZtoA:
                mSortText = mContext.getResources().getString(R.string.str_referrel_desc_value);
                break;
            case R.id.fromAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_from_asc_value);
                break;
            case R.id.fromZtoA:
                mSortText = mContext.getResources().getString(R.string.str_from_desc_value);
                break;
            case R.id.dateNewest:
                mSortText = mContext.getResources().getString(R.string.str_date_asc_value);
                break;
            case R.id.dateOldest:
                mSortText = mContext.getResources().getString(R.string.str_date_desc_value);
                break;
            case R.id.statusAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_status_asc_value);
                break;
            case R.id.statusZtoA:
                mSortText = mContext.getResources().getString(R.string.str_status_desc_value);
                break;
            case R.id.valueAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_value_asc_value);
                break;
            case R.id.valueZtoA:
                mSortText = mContext.getResources().getString(R.string.str_value_desc_value);
                break;
            case R.id.subjectAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_subject_asc_value);
                break;
            case R.id.subjectZtoA:
                mSortText = mContext.getResources().getString(R.string.str_subject_desc_value);
                break;
            //New
            case R.id.contactAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_referrel_asc_value);
                break;
            case R.id.contactZtoA:
                mSortText = mContext.getResources().getString(R.string.str_referrel_desc_value);
                break;

            case R.id.professionAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_profession_asc_value);
                break;
            case R.id.professionZtoA:
                mSortText = mContext.getResources().getString(R.string.str_profession_desc_value);
                break;

            case R.id.emailAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_email_asc_value);
                break;
            case R.id.emailZtoA:
                mSortText = mContext.getResources().getString(R.string.str_email_asc_value);
                break;
            case R.id.partneremailAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_partner_email_asc_value);
                break;
            case R.id.partneremailZtoA:
                mSortText = mContext.getResources().getString(R.string.str_partner_email_desc_value);
                break;

            case R.id.partnernameAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_partner_name_asc_value);
                break;
            case R.id.partnernameZtoA:
                mSortText = mContext.getResources().getString(R.string.str_partner_name_desc_value);
                break;

            case R.id.referralamountAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_referral_amount_asc_value);
                break;
            case R.id.referralamountZtoA:
                mSortText = mContext.getResources().getString(R.string.str_referral_amount_desc_value);
                break;

            case R.id.statuspartnerAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_partner_status_asc_value);
                break;
            case R.id.statuspartnerZtoA:
                mSortText = mContext.getResources().getString(R.string.str_partner_status_desc_value);
                break;

            case R.id.companyAtoZ:
                mSortText = mContext.getResources().getString(R.string.str_company_asc_value);
                break;
            case R.id.companyZtoA:
                mSortText = mContext.getResources().getString(R.string.str_company_desc_value);
                break;

        }
        //}
        return mSortText;
    }

    /**
     * getting value of selected radio button
     *
     * @return content description for radio
     */
    public static String getRadioDataASCOrDESC(Activity mContext) {
        SharedPreference sharedPreference = new SharedPreference();
        String mSortText = "";
        switch (sharedPreference.getSearchOrder(mContext)) {

            case R.id.referrelAtoZ:
                mSortText = ApplicationConstants.ASC;
                break;
            case R.id.referrelZtoA:
                mSortText = ApplicationConstants.DESC;
                break;
            case R.id.fromAtoZ:
                mSortText = ApplicationConstants.ASC;
                break;
            case R.id.fromZtoA:
                mSortText = ApplicationConstants.DESC;
                break;
            case R.id.dateNewest:
                mSortText = ApplicationConstants.DESC;
                break;
            case R.id.dateOldest:
                mSortText = ApplicationConstants.ASC;
                break;
            case R.id.statusAtoZ:
                mSortText = ApplicationConstants.ASC;
                break;
            case R.id.statusZtoA:
                mSortText = ApplicationConstants.DESC;
                break;
            case R.id.valueAtoZ:
                mSortText = ApplicationConstants.DESC;
                break;
            case R.id.valueZtoA:
                mSortText = ApplicationConstants.ASC;
                break;
            case R.id.subjectAtoZ:
                mSortText = ApplicationConstants.ASC;
                break;
            case R.id.subjectZtoA:
                mSortText = ApplicationConstants.DESC;
                break;

            case R.id.contactAtoZ:
                mSortText = ApplicationConstants.ASC;
                break;
            case R.id.contactZtoA:
                mSortText = ApplicationConstants.DESC;
                break;
            case R.id.professionAtoZ:
                mSortText = ApplicationConstants.ASC;
                break;
            case R.id.professionZtoA:
                mSortText = ApplicationConstants.DESC;
                break;
            case R.id.emailAtoZ:
                mSortText = ApplicationConstants.ASC;
                break;
            case R.id.emailZtoA:
                mSortText = ApplicationConstants.DESC;
                break;
            case R.id.partneremailAtoZ:
                mSortText = ApplicationConstants.ASC;
                break;
            case R.id.partneremailZtoA:
                mSortText = ApplicationConstants.DESC;
                break;

            case R.id.partnernameAtoZ:
                mSortText = ApplicationConstants.ASC;
                break;
            case R.id.partnernameZtoA:
                mSortText = ApplicationConstants.DESC;
                break;

            case R.id.referralamountAtoZ:
                mSortText = ApplicationConstants.DESC;
                break;
            case R.id.referralamountZtoA:
                mSortText = ApplicationConstants.ASC;
                break;

            case R.id.companyAtoZ:
                mSortText = ApplicationConstants.ASC;
                break;

            case R.id.companyZtoA:
                mSortText = ApplicationConstants.DESC;
                break;

            case R.id.statuspartnerAtoZ:
                mSortText = ApplicationConstants.ASC;
                break;
            case R.id.statuspartnerZtoA:
                mSortText = ApplicationConstants.DESC;
                break;

            default:
                mSortText = ApplicationConstants.ASC;

        }
        return mSortText;
    }

	/*public static OkHttpClient getClient(){
		final OkHttpClient okHttpClient = new OkHttpClient();
		okHttpClient.setReadTimeout(60, TimeUnit.SECONDS);
		okHttpClient.setConnectTimeout(60, TimeUnit.SECONDS);
		return okHttpClient;
	}*/

    /**
     * checking whether service is running or not
     *
     * @param serviceClass
     * @param mContext
     * @return boolean
     */
    public static boolean isMyServiceRunning(Class<?> serviceClass, Context mContext) {
        ActivityManager manager = (ActivityManager) mContext.getSystemService(Context.ACTIVITY_SERVICE);
        for (ActivityManager.RunningServiceInfo service : manager.getRunningServices(Integer.MAX_VALUE)) {
            if (serviceClass.getName().equals(service.service.getClassName())) {
                return true;
            }
        }
        return false;
    }

    /**
     * Control for search order selecting from each screen
     * like received,sent,archive received and send archive referrals.
     *
     * @param view
     * @param mContext
     */
    public static void checkBoxClicked(View view, Activity mContext) {
        boolean checked = ((CheckBox) view).isChecked();
        switch (view.getId()) {
            case R.id.dateNewest:
                if (checked) {
                    unCheckedAll(R.id.dateNewest, mContext);
                    checkedSelected(R.id.dateNewest, mContext);
                    Log.i(TAG, "datenewest checked");
                } else {
                    ((CheckBox) view).setChecked(true);
                    Log.i(TAG, "datenewest ubchecked");
                }
                break;
            case R.id.dateOldest:
                if (checked) {
                    unCheckedAll(R.id.dateOldest, mContext);
                    checkedSelected(R.id.dateOldest, mContext);
                    Log.i(TAG, "olddate checked");
                } else {
                    unCheckedAll(R.id.dateNewest, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "olddate unchecked");
                }
                break;
            case R.id.referrelAtoZ:
                if (checked) {
                    unCheckedAll(R.id.referrelAtoZ, mContext);
                    checkedSelected(R.id.referrelAtoZ, mContext);
                    Log.i(TAG, "referrelAtoZ checked");
                } else {
                    unCheckedAll(R.id.referrelAtoZ, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "referrelAtoZ ubchecked");
                }
                break;
            case R.id.referrelZtoA:
                if (checked) {
                    unCheckedAll(R.id.referrelZtoA, mContext);
                    checkedSelected(R.id.referrelZtoA, mContext);
                    Log.i(TAG, "referrelZtoA checked");
                } else {
                    unCheckedAll(R.id.referrelZtoA, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "referrelZtoA ubchecked");
                }
                break;
            case R.id.fromAtoZ:
                if (checked) {
                    unCheckedAll(R.id.fromAtoZ, mContext);
                    checkedSelected(R.id.fromAtoZ, mContext);
                    Log.i(TAG, "fromAtoZ checked");
                } else {
                    unCheckedAll(R.id.fromAtoZ, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "fromAtoZ ubchecked");
                }
                break;
            case R.id.fromZtoA:
                if (checked) {
                    unCheckedAll(R.id.fromZtoA, mContext);
                    checkedSelected(R.id.fromZtoA, mContext);
                    Log.i(TAG, "fromZtoA checked");
                } else {
                    unCheckedAll(R.id.fromZtoA, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "fromZtoA ubchecked");
                }
                break;
            case R.id.statusAtoZ:
                if (checked) {
                    unCheckedAll(R.id.statusAtoZ, mContext);
                    checkedSelected(R.id.statusAtoZ, mContext);
                    Log.i(TAG, "statusAtoZ checked");
                } else {
                    unCheckedAll(R.id.statusAtoZ, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "statusAtoZ ubchecked");
                }
                break;
            case R.id.statusZtoA:
                if (checked) {
                    unCheckedAll(R.id.statusZtoA, mContext);
                    checkedSelected(R.id.statusZtoA, mContext);
                    Log.i(TAG, "statusAtoZ checked");
                } else {
                    unCheckedAll(R.id.statusZtoA, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "statusAtoZ ubchecked");
                }
                break;
            case R.id.valueAtoZ:
                if (checked) {
                    unCheckedAll(R.id.valueAtoZ, mContext);
                    checkedSelected(R.id.valueAtoZ, mContext);
                    Log.i(TAG, "valueAtoZ checked");
                } else {
                    unCheckedAll(R.id.valueAtoZ, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "valueAtoZ ubchecked");
                }
                break;
            case R.id.valueZtoA:
                if (checked) {
                    unCheckedAll(R.id.valueZtoA, mContext);
                    checkedSelected(R.id.valueZtoA, mContext);
                    Log.i(TAG, "valueZtoA checked");
                } else {
                    unCheckedAll(R.id.valueZtoA, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "valueZtoA ubchecked");
                }
                break;

            case R.id.subjectAtoZ:
                if (checked) {
                    unCheckedAll(R.id.subjectAtoZ, mContext);
                    checkedSelected(R.id.subjectAtoZ, mContext);
                    Log.i(TAG, "subjectAtoZ checked");
                } else {
                    unCheckedAll(R.id.subjectAtoZ, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "subjectAtoZ ubchecked");
                }
                break;
            case R.id.subjectZtoA:
                if (checked) {
                    unCheckedAll(R.id.subjectZtoA, mContext);
                    checkedSelected(R.id.subjectZtoA, mContext);
                    Log.i(TAG, "subjectZtoA checked");
                } else {
                    unCheckedAll(R.id.subjectZtoA, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "subjectZtoA ubchecked");
                }
                break;

            case R.id.contactAtoZ:
                if (checked) {
                    unCheckedAll(R.id.contactAtoZ, mContext);
                    checkedSelected(R.id.contactAtoZ, mContext);
                    Log.i(TAG, "contactAtoZ checked");
                } else {
                    unCheckedAll(R.id.contactAtoZ, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "contactAtoZ ubchecked");
                }
                break;
            case R.id.contactZtoA:
                if (checked) {
                    unCheckedAll(R.id.contactZtoA, mContext);
                    checkedSelected(R.id.contactZtoA, mContext);
                    Log.i(TAG, "contactZtoA checked");
                } else {
                    unCheckedAll(R.id.contactZtoA, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "contactZtoA ubchecked");
                }
                break;

            case R.id.professionAtoZ:
                if (checked) {
                    unCheckedAll(R.id.professionAtoZ, mContext);
                    checkedSelected(R.id.professionAtoZ, mContext);
                    Log.i(TAG, "professionAtoZ checked");
                } else {
                    unCheckedAll(R.id.professionAtoZ, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "professionAtoZ ubchecked");
                }
                break;
            case R.id.professionZtoA:
                if (checked) {
                    unCheckedAll(R.id.professionZtoA, mContext);
                    checkedSelected(R.id.professionZtoA, mContext);
                    Log.i(TAG, "professionZtoA checked");
                } else {
                    unCheckedAll(R.id.professionZtoA, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "professionZtoA ubchecked");
                }
                break;

            case R.id.emailAtoZ:
                if (checked) {
                    unCheckedAll(R.id.emailAtoZ, mContext);
                    checkedSelected(R.id.emailAtoZ, mContext);
                    Log.i(TAG, "emailAtoZ checked");
                } else {
                    unCheckedAll(R.id.emailAtoZ, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "emailAtoZ ubchecked");
                }
                break;
            case R.id.emailZtoA:
                if (checked) {
                    unCheckedAll(R.id.emailZtoA, mContext);
                    checkedSelected(R.id.emailZtoA, mContext);
                    Log.i(TAG, "emailZtoA checked");
                } else {
                    unCheckedAll(R.id.emailZtoA, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "emailZtoA ubchecked");
                }
                break;
            case R.id.partneremailAtoZ:
                if (checked) {
                    unCheckedAll(R.id.partneremailAtoZ, mContext);
                    checkedSelected(R.id.partneremailAtoZ, mContext);
                    Log.i(TAG, "partneremailAtoZ checked");
                } else {
                    unCheckedAll(R.id.partneremailAtoZ, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "partneremailAtoZ ubchecked");
                }
                break;
            case R.id.partneremailZtoA:
                if (checked) {
                    unCheckedAll(R.id.partneremailZtoA, mContext);
                    checkedSelected(R.id.partneremailZtoA, mContext);
                    Log.i(TAG, "partneremailZtoA checked");
                } else {
                    unCheckedAll(R.id.partneremailZtoA, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "partneremailZtoA ubchecked");
                }
                break;

            case R.id.partnernameAtoZ:
                if (checked) {
                    unCheckedAll(R.id.partnernameAtoZ, mContext);
                    checkedSelected(R.id.partnernameAtoZ, mContext);
                    Log.i(TAG, "partnernameAtoZ checked");
                } else {
                    unCheckedAll(R.id.partnernameAtoZ, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "partnernameAtoZ ubchecked");
                }
                break;
            case R.id.partnernameZtoA:
                if (checked) {
                    unCheckedAll(R.id.partnernameZtoA, mContext);
                    checkedSelected(R.id.partnernameZtoA, mContext);
                    Log.i(TAG, "partnernameZtoA checked");
                } else {
                    unCheckedAll(R.id.partnernameZtoA, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "partnernameZtoA ubchecked");
                }
                break;

            case R.id.referralamountAtoZ:
                if (checked) {
                    unCheckedAll(R.id.referralamountAtoZ, mContext);
                    checkedSelected(R.id.referralamountAtoZ, mContext);
                    Log.i(TAG, "referralamountAtoZ checked");
                } else {
                    unCheckedAll(R.id.referralamountAtoZ, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "referralamountAtoZ ubchecked");
                }
                break;
            case R.id.referralamountZtoA:
                if (checked) {
                    unCheckedAll(R.id.referralamountZtoA, mContext);
                    checkedSelected(R.id.referralamountZtoA, mContext);
                    Log.i(TAG, "referralamountZtoA checked");
                } else {
                    unCheckedAll(R.id.referralamountZtoA, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "referralamountZtoA ubchecked");
                }
                break;

            case R.id.statuspartnerAtoZ:
                if (checked) {
                    unCheckedAll(R.id.statuspartnerAtoZ, mContext);
                    checkedSelected(R.id.statuspartnerAtoZ, mContext);
                    Log.i(TAG, "statuspartnerAtoZ checked");
                } else {
                    unCheckedAll(R.id.statuspartnerAtoZ, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "statuspartnerAtoZ ubchecked");
                }
                break;
            case R.id.statuspartnerZtoA:
                if (checked) {
                    unCheckedAll(R.id.statuspartnerZtoA, mContext);
                    checkedSelected(R.id.statuspartnerZtoA, mContext);
                    Log.i(TAG, "emailZtoA checked");
                } else {
                    unCheckedAll(R.id.statuspartnerZtoA, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "statuspartnerZtoA ubchecked");
                }
                break;

            case R.id.companyAtoZ:
                if (checked) {
                    unCheckedAll(R.id.companyAtoZ, mContext);
                    checkedSelected(R.id.companyAtoZ, mContext);
                    Log.i(TAG, "companyAtoZ checked");
                } else {
                    unCheckedAll(R.id.companyAtoZ, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "companyAtoZ ubchecked");
                }
                break;

            case R.id.companyZtoA:
                if (checked) {
                    unCheckedAll(R.id.companyZtoA, mContext);
                    checkedSelected(R.id.companyZtoA, mContext);
                    Log.i(TAG, "companyZtoA checked");
                } else {
                    unCheckedAll(R.id.companyZtoA, mContext);
                    checkedDefault(mContext);
                    Log.i(TAG, "companyZtoA ubchecked");
                }
                break;

        }
    }

    /**
     * clearing all checkbox
     *
     * @param checkboxId
     * @param mCtx
     */
    public static void unCheckedAll(int checkboxId, Activity mCtx) {
        CheckBox dateNewest = (CheckBox) mCtx.findViewById(R.id.dateNewest);
        CheckBox dateOldest = (CheckBox) mCtx.findViewById(R.id.dateOldest);
        CheckBox referrelAtoZ = (CheckBox) mCtx.findViewById(R.id.referrelAtoZ);
        CheckBox referrelZtoA = (CheckBox) mCtx.findViewById(R.id.referrelZtoA);
        CheckBox fromAtoZ = (CheckBox) mCtx.findViewById(R.id.fromAtoZ);
        CheckBox fromZtoA = (CheckBox) mCtx.findViewById(R.id.fromZtoA);
        CheckBox statusAtoZ = (CheckBox) mCtx.findViewById(R.id.statusAtoZ);
        CheckBox statusZtoA = (CheckBox) mCtx.findViewById(R.id.statusZtoA);
        CheckBox valueAtoZ = (CheckBox) mCtx.findViewById(R.id.valueAtoZ);
        CheckBox valueZtoA = (CheckBox) mCtx.findViewById(R.id.valueZtoA);
        CheckBox subjectAtoZ = (CheckBox) mCtx.findViewById(R.id.subjectAtoZ);
        CheckBox subjectZtoA = (CheckBox) mCtx.findViewById(R.id.subjectZtoA);

        CheckBox contactAtoZ = (CheckBox) mCtx.findViewById(R.id.contactAtoZ);
        CheckBox contactZtoA = (CheckBox) mCtx.findViewById(R.id.contactZtoA);

        CheckBox professionAtoZ = (CheckBox) mCtx.findViewById(R.id.professionAtoZ);
        CheckBox professionZtoA = (CheckBox) mCtx.findViewById(R.id.professionZtoA);

        CheckBox emailAtoZ = (CheckBox) mCtx.findViewById(R.id.emailAtoZ);
        CheckBox emailZtoA = (CheckBox) mCtx.findViewById(R.id.emailZtoA);

        CheckBox companyAtoZ = (CheckBox) mCtx.findViewById(R.id.companyAtoZ);
        CheckBox companyZtoA = (CheckBox) mCtx.findViewById(R.id.companyZtoA);


        CheckBox partneremailAtoZ = (CheckBox) mCtx.findViewById(R.id.partneremailAtoZ);
        CheckBox partneremailZtoA = (CheckBox) mCtx.findViewById(R.id.partneremailZtoA);

        CheckBox partnernameAtoZ = (CheckBox) mCtx.findViewById(R.id.partnernameAtoZ);
        CheckBox partnernameZtoA = (CheckBox) mCtx.findViewById(R.id.partnernameZtoA);

        CheckBox referralamountAtoZ = (CheckBox) mCtx.findViewById(R.id.referralamountAtoZ);
        CheckBox referralamountZtoA = (CheckBox) mCtx.findViewById(R.id.referralamountZtoA);

        CheckBox statuspartnerAtoZ = (CheckBox) mCtx.findViewById(R.id.statuspartnerAtoZ);
        CheckBox statuspartnerZtoA = (CheckBox) mCtx.findViewById(R.id.statuspartnerZtoA);


        dateNewest.setChecked(false);
        dateOldest.setChecked(false);
        referrelAtoZ.setChecked(false);
        referrelZtoA.setChecked(false);
        fromAtoZ.setChecked(false);
        fromZtoA.setChecked(false);
        statusAtoZ.setChecked(false);
        statusZtoA.setChecked(false);
        valueAtoZ.setChecked(false);
        valueZtoA.setChecked(false);
        subjectAtoZ.setChecked(false);
        subjectZtoA.setChecked(false);

        contactAtoZ.setChecked(false);
        contactZtoA.setChecked(false);
        professionAtoZ.setChecked(false);
        professionZtoA.setChecked(false);

        emailAtoZ.setChecked(false);
        emailZtoA.setChecked(false);

        partneremailAtoZ.setChecked(false);
        partneremailZtoA.setChecked(false);
        partnernameAtoZ.setChecked(false);
        partnernameZtoA.setChecked(false);
        referralamountAtoZ.setChecked(false);
        referralamountZtoA.setChecked(false);
        statuspartnerAtoZ.setChecked(false);
        statuspartnerZtoA.setChecked(false);

        companyAtoZ.setChecked(false);
        companyZtoA.setChecked(false);

    }

    //check selected checkbox
    public static void checkedSelected(int checkboxId, Activity mCtx) {
        SharedPreference sharedPreference = new SharedPreference();
        sharedPreference.setSearrchOrder(mCtx, checkboxId);
        CheckBox dateNewest = (CheckBox) mCtx.findViewById(checkboxId);
        dateNewest.setChecked(true);

    }

    //select checkbox if no one selected
    public static void checkedDefault(Activity mCtx) {
        SharedPreference sharedPreference = new SharedPreference();
        sharedPreference.setSearrchOrder(mCtx, R.id.dateNewest);
        CheckBox dateNewest = (CheckBox) mCtx.findViewById(R.id.dateNewest);
        dateNewest.setChecked(true);

    }

    public static boolean isValidUrl(String url) {
        try {
            String[] webUrlArr = url.split("\\.");

            if (webUrlArr != null && webUrlArr.length > 0) {
                if (webUrlArr.length > 1) {
                    boolean isCorrect = false;

                    for (int i = 0; i < webUrlArr.length; i++) {
                        String strAfterDot = webUrlArr[1];
                        if (i == 0) {
                            if (strAfterDot.length() > 1) {
                                isCorrect = true;
                            }
                        }


                    }
                    return isCorrect;
                } else {
                    String strAfterDot = webUrlArr[0];

                    if (strAfterDot.length() == 1) {
                        return false;
                    }
                }

            } else {
                return false;
            }
            return false;

        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return true;
    }

    /**
     * Getting Files Attachment type logo
     *
     * @param filename
     * @return
     */
    public static int getAttachmentType(String filename) {
        int icon = 0;
        if (filename.contains(".pdf") || filename.contains(".PDF")) {
            icon = R.drawable.icon_pdf;
        } else if (filename.contains(".xls") || filename.contains(".XLS") || filename.contains(".xlsx") || filename.contains(".XLSX")) {
            icon = R.drawable.icon_xls;
        } else if (filename.contains(".doc") || filename.contains(".DOC") || filename.contains(".docx") || filename.contains(".DOCX")) {
            icon = R.drawable.icon_doc;
        } else if (filename.contains(".jpg") || filename.contains(".JPG") || filename.contains(".jpeg") || filename.contains(".JPEG") || filename.contains(".png") || filename.contains(".PNG")) {
            icon = R.drawable.icon_image;
        }
        return icon;
    }


    public static String[] getMeeting(String savedValue){
        List<MeetingDayModel> get_Response=new ArrayList<MeetingDayModel>();
        String []Days=null;
        if(null!=savedValue && !savedValue.equals("")) {
            Gson gson = new Gson();
            MeetingDayModel[] favoriteItems = gson.fromJson(savedValue, MeetingDayModel[].class);
            get_Response = Arrays.asList(favoriteItems);
            get_Response = new ArrayList(get_Response);

            Days=new String[get_Response.size()];
            for(int i=0;i<get_Response.size();i++){
                Days[i]=get_Response.get(i).getMeetingDay();
            }
        }
        return  Days;
    }

    public static String[] getMeetingTime(String savedValue){
        List<MeetingDayModel> get_Response=new ArrayList<MeetingDayModel>();
        String []Days=null;
        if(null!=savedValue && !savedValue.equals("")) {
            Gson gson = new Gson();
            MeetingDayModel[] favoriteItems = gson.fromJson(savedValue, MeetingDayModel[].class);
            get_Response = Arrays.asList(favoriteItems);
            get_Response = new ArrayList(get_Response);

            Days=new String[get_Response.size()];
            for(int i=0;i<get_Response.size();i++){
                Days[i]=get_Response.get(i).getMeetingTimeExactValue();
            }
        }
        return  Days;
    }

    public static List<String> getMeetingNEW(String savedValue){
        List<MeetingDayModel> get_Response=new ArrayList<MeetingDayModel>();
        List<String> Days=null;
        if(null!=savedValue && !savedValue.equals("")) {
            Gson gson = new Gson();
            MeetingDayModel[] favoriteItems = gson.fromJson(savedValue, MeetingDayModel[].class);
            get_Response = Arrays.asList(favoriteItems);
            get_Response = new ArrayList(get_Response);

            Days=new ArrayList<String>();
            for(int i=0;i<get_Response.size();i++){
                //Days[i]=get_Response.get(i).getMeetingDay();
                Days.add(get_Response.get(i).getMeetingDay());
            }
        }
        return  Days;
    }

    public static List<String> getMeetingTimeNew(String savedValue){
        List<MeetingDayModel> get_Response=new ArrayList<MeetingDayModel>();
        List<String> Days=null;
        if(null!=savedValue && !savedValue.equals("")) {
            Gson gson = new Gson();
            MeetingDayModel[] favoriteItems = gson.fromJson(savedValue, MeetingDayModel[].class);
            get_Response = Arrays.asList(favoriteItems);
            get_Response = new ArrayList(get_Response);

            Days=new ArrayList<String>();
            for(int i=0;i<get_Response.size();i++){
                Days.add(get_Response.get(i).getMeetingTimeExactValue());
            }
        }
        return  Days;
    }

    public static Typeface getTypeFace(Context ctx){
        Typeface tf = Typeface.createFromAsset(ctx.getAssets(), "fonts/graphik_regular_gdi.ttf");
        return tf;
    }

    public static void fixOrientation(Activity activity) {
        Configuration configuration = activity.getResources()
                .getConfiguration();

        int orientaionID = configuration.orientation;

        if (orientaionID == Configuration.ORIENTATION_LANDSCAPE) {
            activity.setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_LANDSCAPE);
        } else {
            activity.setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
        }
    }

    /**
     * clearing sharedpreference data before final logout
     * @throws Exception
     */
    public static  void clearUserData(Activity mContext) throws  Exception{
        SharedPreference saveSharedPreference = new SharedPreference();

        saveSharedPreference.setUserId(mContext, "");
        saveSharedPreference.setUserEmail(mContext, "");
        saveSharedPreference.setUserIsActive(mContext, "");
        saveSharedPreference.setUserIsConfirm(mContext, "");
        saveSharedPreference.setUserImage(mContext, "");
        saveSharedPreference.setUserProfilePercentage(mContext, "");
        saveSharedPreference.setGroupType(mContext, "");
        saveSharedPreference.setUserRating(mContext, "");
        saveSharedPreference.setUserNextShuffle(mContext, "");
        saveSharedPreference.setUserFname(mContext, "");
        saveSharedPreference.setUserLname(mContext, "");

        //clearing search text and order
        saveSharedPreference.setSearchText(mContext, "");
        saveSharedPreference.setSearrchOrder(mContext, R.id.dateNewest);
        //clearing  notification data
        saveSharedPreference.setReferralsUnreadCount(mContext, 0);
        saveSharedPreference.setMessageUnreadCount(mContext, 0);
        saveSharedPreference.setTotalUnreadCount(mContext, 0);
        saveSharedPreference.setIsLogin(mContext, "");

        saveSharedPreference.setSelectedGroupType(mContext, ApplicationConstants.GROUP_LOCAL);
        saveSharedPreference.setSortyByLocal(mContext, "");
        saveSharedPreference.setSortyByGlobal(mContext, "");
        saveSharedPreference.setMiles(mContext, "5");
        saveSharedPreference.setLocationGlobal(mContext, "");
        saveSharedPreference.setMeetingDays(mContext,new  ArrayList<MeetingDayModel>());
        saveSharedPreference.setMeetingTimes(mContext, new ArrayList<MeetingDayModel>());
        saveSharedPreference.setMeetingDaysGlobal(mContext, new ArrayList<MeetingDayModel>());
        saveSharedPreference.setMeetingTimesGlobal(mContext, new ArrayList<MeetingDayModel>());
    }

    public static int  getYears(){
        DateFormat df = new SimpleDateFormat("yyyy");
        String now = df.format(new Date());
        if(null!=now){
            return  Integer.parseInt(now);
        }else{
            return  2015;
        }

    }
//"^https?://.*(?:youtu.be/|v/|u/\\w/|embed/|watch?v=)([^#&?]*).*$"
    public static String getYouTubeId (String youTubeUrl) {
        String pattern = "(?<=youtu.be/|watch\\?v=|/videos/|embed\\/)[^#\\&\\?]*";
        Pattern compiledPattern = Pattern.compile(pattern);
        Matcher matcher = compiledPattern.matcher(youTubeUrl);
        if(matcher.find()){
            return matcher.group();
        } else {
            return "error";
        }
    }
}
