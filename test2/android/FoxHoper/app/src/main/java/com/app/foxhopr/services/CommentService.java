package com.app.foxhopr.services;

import android.app.AlarmManager;
import android.app.PendingIntent;
import android.app.Service;
import android.content.Intent;
import android.os.Binder;
import android.os.Build;
import android.os.Handler;
import android.os.IBinder;
import android.util.Log;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.webservice.models.NotificationListModel;
import com.app.foxhopr.webservice.models.NotificationResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.Calendar;
import java.util.Timer;
import java.util.TimerTask;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * Created by Chobey R. on 17/8/15.
 */
public class CommentService extends Service {

    private static final String TAG = "CommentService";

    private TimerTask formUpdateTask;
    private Handler handler;
    private Timer formUpdateTimer;
    private NotificationResponseModel getResponse;
    private SharedPreference sharedPreference;
    private IBinder mBinder = new MyBinder();
    @Override
    public IBinder onBind(Intent intent) {
        return mBinder;
    }

    @Override
    public void onCreate() {
        super.onCreate();
        handler = new Handler();

    }

    @Override
    public int onStartCommand(Intent intent, int flags, int startId) {
        Log.e(TAG, TAG);
        if (AppUtils.isOnline(CommentService.this)) {
            try {
                //callWebservice();
                Intent notifyScreen=new Intent(ApplicationConstants.NOTIFY_DATAUPDATE_ACTION);
                notifyScreen.putExtra(ApplicationConstants.NOTIFY_DATAUPDATE_KEY, ApplicationConstants.NOTIFY_DATAUPDATE_VALUE);
                getApplicationContext().sendBroadcast(notifyScreen);
            } catch (Exception e) {
                e.printStackTrace();
            }
        } else {
            Log.i(TAG, "NO INTERNET");
        }
        if(ApplicationConstants.COMMENT_STATUS) {
            Intent myIntent = new Intent(CommentService.this,
                    CommentService.class);
            PendingIntent pendingIntent = PendingIntent.getService(
                    CommentService.this, 0, myIntent, 0);

            AlarmManager alarmManager = (AlarmManager) getSystemService(ALARM_SERVICE);

            Calendar calendar = Calendar.getInstance();
            calendar.setTimeInMillis(System.currentTimeMillis());
            calendar.add(Calendar.SECOND, 5);//1 Hour//360// 21600//(6*60*60)
            if (Build.VERSION.SDK_INT < Build.VERSION_CODES.KITKAT) {
                alarmManager.set(AlarmManager.RTC_WAKEUP, calendar.getTimeInMillis(),
                        pendingIntent);
            } else {
                alarmManager.setExact(AlarmManager.RTC_WAKEUP, calendar.getTimeInMillis(),
                        pendingIntent);
            }

        }
        stopService(new Intent(CommentService.this,
                NotificationService.class));
        return START_STICKY;
    }

    /**
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.NOTIFICATION_ACTION_NAME, WebServiceConstants.NOTIFICATION_CONTROL_NAME, CommentService.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);


        requestApi.getNotificationRequest(new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response bodyNotificationService
                try {
                    if (responseModel != null) {
                        Log.i(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseReferralsListService(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        Log.e(TAG,getString(R.string.wrng_str_server_error));
                        //ErrorMsgDialog.showErrorAlert(CommentService.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();

                //mFooterProgressBar.setVisibility(View.GONE);
                //mProgressHUD.dismiss();
                //mWebserviceStatus=true;
                Log.e(TAG,getString(R.string.wrng_str_server_error));
                //ErrorMsgDialog.showErrorAlert(CommentService.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * parseReferralsListService
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseReferralsListService(String responseStr) throws Exception {
        sharedPreference = new SharedPreference();
        Gson gson = new Gson();
        NotificationResponseModel get_Response = gson.fromJson(responseStr, NotificationResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null) {
                    Gson gsonObj = new Gson();
                    NotificationListModel notficationModel = gsonObj.fromJson(get_Response.getResult().toString(), NotificationListModel.class);
                    Log.i(TAG, notficationModel.getReferralUnread() + "");
                    Log.i(TAG, notficationModel.getMessageUnread() + "");
                    Log.i(TAG, notficationModel.getTotal() + "");

                    sharedPreference.setReferralsUnreadCount(CommentService.this, notficationModel.getReferralUnread());
                    sharedPreference.setMessageUnreadCount(CommentService.this, notficationModel.getMessageUnread());
                    sharedPreference.setTotalUnreadCount(CommentService.this, notficationModel.getTotal());
                    //sending braodcast for updating dashboard  badge icon
                    Intent intent=new Intent(ApplicationConstants.LAST_DATAUPDATE_ACTION);
                    intent.putExtra(ApplicationConstants.LAST_DATAUPDATE_KEY, ApplicationConstants.LAST_DATAUPDATE_VALUE);
                    getApplicationContext().sendBroadcast(intent);
                }
            } else {
                //ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            // ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
        }
    }
    public class MyBinder extends Binder {
        public CommentService getService() {
            return CommentService.this;
        }
    }
}
