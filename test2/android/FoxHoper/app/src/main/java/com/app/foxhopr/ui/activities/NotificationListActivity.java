package com.app.foxhopr.ui.activities;

import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.util.Log;
import android.view.View;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.adapter.NotificationListAdapter;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.foxhoper.app.R;

import java.util.ArrayList;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class NotificationListActivity extends FragmentActivity implements View.OnClickListener {
    private static String TAG="NotificationListActivity";
    private ArrayList<Row> mNotification;
    private SharedPreference mSharedpreferences;
    private ListView mNotificationListView;
    private RelativeLayout rlBack;
    private TextView mTextViewHeaderTitle;
    private ProgressHUD mProgressHUD;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_notification_list);
        mNotification = new ArrayList<Row>();
        mSharedpreferences = new SharedPreference();
        getData();
        initUi();

    }


    public void initUi() {
        mNotificationListView = (ListView) findViewById(R.id.notificaitonListView);
        mTextViewHeaderTitle = (TextView) findViewById(R.id.textViewHeaderTitle);
        mTextViewHeaderTitle.setVisibility(View.VISIBLE);
        mTextViewHeaderTitle.setText(getResources().getString(R.string.notification_title));
        rlBack = (RelativeLayout) findViewById(R.id.rlBack);
        NotificationListAdapter adapter = new NotificationListAdapter(NotificationListActivity.this, mNotification, null);
        mNotificationListView.setAdapter(adapter);
        rlBack.setOnClickListener(this);

        if (AppUtils.isOnline(NotificationListActivity.this)) {
            mProgressHUD = ProgressHUD.show(NotificationListActivity.this, "", true, true);
            mProgressHUD.setCancelable(false);
            try {
                callWebservice();
            }catch (Exception e){
                e.printStackTrace();
            }
        } else {
            ErrorMsgDialog.showErrorAlert(NotificationListActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
        }
    }

    public void getData() {
        if (mSharedpreferences.getTotalRefrralsUnreadCount(NotificationListActivity.this) > 0) {
            Row row = new Row();
            row.setType("referrel");
            row.setCount(mSharedpreferences.getTotalRefrralsUnreadCount(NotificationListActivity.this) + "");
            mNotification.add(row);
        }
        if (mSharedpreferences.getTotalMessageUnreadCount(NotificationListActivity.this) > 0) {
            Row row = new Row();
            row.setType("messages");
            row.setCount(mSharedpreferences.getTotalMessageUnreadCount(NotificationListActivity.this) + "");
            mNotification.add(row);
        }
    }


    @Override
    public void onClick(View v) {
        if (v == rlBack) {
            finish();
            overridePendingTransition(0, 0);
        }
    }

    public class Row {
        private String type;

        public String getCount() {
            return count;
        }

        public void setCount(String count) {
            this.count = count;
        }

        public String getType() {
            return type;
        }

        public void setType(String type) {
            this.type = type;
        }

        private String count;
    }

    /**
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.COUNTER_CHECKED_ACTION_NAME, WebServiceConstants.NOTIFICATION_CONTROL_NAME,NotificationListActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getCleanNotification(new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        //parseReferralsListService(WebServiceUtils.getResponseString(responseModel));
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(NotificationListActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(NotificationListActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }
}
