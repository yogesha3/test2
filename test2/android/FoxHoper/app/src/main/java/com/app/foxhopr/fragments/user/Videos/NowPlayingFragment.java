/**
 * Show the details for current playing video
 * user can navigate to upnext video list
 */
package com.app.foxhopr.fragments.user.Videos;

import android.app.Fragment;
import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.ServiceConnection;
import android.os.Bundle;
import android.os.IBinder;
import android.text.Html;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.HorizontalScrollView;
import android.widget.ScrollView;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.services.CommentService;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.WebCastSelectedCallBack;
import com.app.foxhopr.webservice.models.ReferralsListInnerModel;
import com.app.foxhopr.webservice.models.WebCastListInnerModel;
import com.foxhoper.app.R;

import java.util.ArrayList;

public class NowPlayingFragment extends Fragment implements View.OnClickListener {
    private static String TAG="NowPlayingFragment";

    private Context mContext;
    private HorizontalScrollView mLlTopTabs;

    private ArrayList<ReferralsListInnerModel> listDataArray;
    private boolean isAllSelected = false;

    private WebCastSelectedCallBack mTabSelectedCallBack;
    private WebCastListInnerModel mWebCastListInnerModel;


    private BroadcastReceiver mNotificationBroadcast;
    private SharedPreference mPreferences;

    private ScrollView mScrollViewTop;
    //service variable

    CommentService mBoundService;
    boolean mServiceBound = false;
    Intent intentService;

    private TextView mTextViewItemTitleName;
    private TextView mTextViewItemDate;
    private TextView mTextViewDescription;

    public static NowPlayingFragment newInstance(HorizontalScrollView mLlTopTabs,WebCastListInnerModel mWebCastListInnerModel, WebCastSelectedCallBack mTabSelectedCallBack) {
        NowPlayingFragment mReferralsReceivedFragment = new NowPlayingFragment();
        mReferralsReceivedFragment.mLlTopTabs = mLlTopTabs;
        mReferralsReceivedFragment.mTabSelectedCallBack = mTabSelectedCallBack;
        mReferralsReceivedFragment.mWebCastListInnerModel=mWebCastListInnerModel;
        return mReferralsReceivedFragment;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        mContext = getActivity();
        View view = inflater.inflate(R.layout.fragment_nowplaying, container, false);
        //setting common bottom bar view gone as only edit icon will be here
        initView(view);
        return view;
    }

    private void initView(View view) {
        mPreferences = new SharedPreference();
        mTextViewItemTitleName=(TextView)view.findViewById(R.id.textViewItemTitleName);
        mTextViewItemDate=(TextView)view.findViewById(R.id.datevalue);
        mTextViewDescription=(TextView)view.findViewById(R.id.textViewDescription);
        if(null!=mWebCastListInnerModel){
            setValueOnUI();
        }
    }

    private void setValueOnUI(){
        setValueWithoutColor(mTextViewItemTitleName, mWebCastListInnerModel.getTitle());

        if(mWebCastListInnerModel.getDescription().trim().length()>0) {
            mTextViewDescription.setText(mWebCastListInnerModel.getDescription());
        }else{
            setValueWithColor(mTextViewDescription, getResources().getString(R.string.not_message_sender), getResources().getColor(R.color.light_gray_color));
        }

        String commentText= "<font color=#d3d3d3>"+AppUtils.parseDateToddMMyyyy(mWebCastListInnerModel.getCreated())+  "</font>" + "<font color=#F15A2B>"+" @ " +"</font> <font color=#d3d3d3>" + AppUtils.parseDateToddMMyyyyTime(mWebCastListInnerModel.getCreated())+"</font>";
        //setValueWithoutColor(mDatevalue, commentText);
        mTextViewItemDate.setText(Html.fromHtml(commentText));
    }

    @Override
    public void onClick(View view) {

    }

    //Setting value on UI with color specific
    public void setValue(TextView View,String Value){

        if(null!=Value && !Value.equals("") ){
            View.setTextColor(getResources().getColor(R.color.refrrels_list_small_text_color));
            View.setText(Value);
        }else{
            View.setTextColor(getResources().getColor(R.color.light_gray_color));
            View.setText(getResources().getString(R.string.not_applicable));
        }

    }
    //Setting value on UI with color specific
    public void setValueGrey(TextView View,String Value){
        if (null != Value && !Value.equals("")) {
            View.setTextColor(getResources().getColor(R.color.light_gray_color));
            View.setText(Value.trim());
        } else {
            View.setTextColor(getResources().getColor(R.color.light_gray_color));
            View.setText(getResources().getString(R.string.not_applicable));
        }

    }

    //Setting value on UI with color specific
    public void setValueWithoutColor(TextView View,String Value){

        if(null!= Value && !Value.equals("") ){
            View.setText(Value);
        }
    }

    //Setting value on UI with color specific
    public void setValueWithColor(TextView View,String Value,int colorid){

        if(null!=Value && !Value.equals("")) {
            View.setTextColor(colorid);
            View.setText(Value);
        }
    }

    /**
     * Initializing broadcast for getting notifications and
     * update it to same screen
     */
    public void initBroadCast() {
        mNotificationBroadcast = new BroadcastReceiver() {

            @Override
            public void onReceive(Context context, Intent intent) {
                String versionValue = intent.getStringExtra(ApplicationConstants.NOTIFY_DATAUPDATE_KEY);
                if (versionValue.equals(ApplicationConstants.NOTIFY_DATAUPDATE_VALUE)) {
                    try{
                        //callCommentWebservice();
                        if (AppUtils.isOnline(getActivity())) {
                            // mFooterProgressBar.setVisibility(View.VISIBLE);
                            //mProgressHUD = ProgressHUD.show(ReferrelDetailsActivity.this, "", true, true);
                            //mProgressHUD.setCancelable(false);
                            try {
                                //callWebserviceSecond();
                            }catch (Exception e){
                                e.printStackTrace();
                            }
                        } else {
                            // ErrorMsgDialog.showErrorAlert(SentReferrelDetailsActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                        }
                    }catch (Exception e){
                        e.printStackTrace();
                    }
                }
            }
        };
    }

    @Override
    public void onStart() {
        super.onStart();
    }

    @Override
    public void onStop() {
        super.onStop();
    }

    @Override
    public void onPause() {
        super.onPause();
    }


    private ServiceConnection mServiceConnection = new ServiceConnection() {

        @Override
        public void onServiceDisconnected(ComponentName name) {
            mServiceBound = false;
        }

        @Override
        public void onServiceConnected(ComponentName name, IBinder service) {
            CommentService.MyBinder myBinder = (CommentService.MyBinder) service;
            mBoundService = myBinder.getService();
            mServiceBound = true;
        }
    };

    @Override
    public void onResume() {
        super.onResume();
        try{
            if (AppUtils.isOnline(getActivity())) {
                //mProgressHUD = ProgressHUD.show(getActivity(),"", true,true);
                //mProgressHUD.setCancelable(false);
                //callWebservice();
            }
            else{
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }
        }catch (Exception ex){
            ex.printStackTrace();
        }
    }
}
