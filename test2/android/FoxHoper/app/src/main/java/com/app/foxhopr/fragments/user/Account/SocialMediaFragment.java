/**
 *SocialMediaFragment display UI for Twitter,Facebook and Linkedin to Grant Access or Revoke Access if already Access
 * is provided.
 */
package com.app.foxhopr.fragments.user.Account;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.HorizontalScrollView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.RevokeRequestModel;
import com.app.foxhopr.ui.Account.SocialDetailsActivity;
import com.app.foxhopr.ui.Account.WebViewActivity;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.app.foxhopr.webservice.models.SocialMediaResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class SocialMediaFragment extends Fragment implements View.OnClickListener{
    private static String TAG="SocialMediaFragment";

    private Context mContext;
    private HorizontalScrollView mLlTopTabs;
    private LinearLayout mLlBottomTabs;


    private Button mBtnDelete;
    private LinearLayout mLLBottomSelectTab;
    private LinearLayout mLLBottomMoreTab;
    private TabSelectedCallBack mTabSelectedCallBack;

    private LinearLayout llTwitter;
    private LinearLayout llTwitterAccess;
    private LinearLayout llTwitterSetting;
    private TextView mTextviewAllowTwitter;

    private LinearLayout llFacebook;
    private LinearLayout llFacebookAccess;
    private LinearLayout llFacebookSetting;
    private TextView mTextviewAllowFacebook;

    private LinearLayout llLinkedin;
    private LinearLayout llLinkedinAccess;
    private LinearLayout llLinkedinSetting;
    private TextView mTextviewAllowLinkedin;

    private ProgressHUD mProgressHUD;
    private boolean mWebserviceStatus = true;
    private SocialMediaResponseModel getResponse;
    private SharedPreference mSharedpreferences;

    public static SocialMediaFragment newInstance(HorizontalScrollView mLlTopTabs, LinearLayout mLlBottomTabs, Button mBtnDelete,
                                                        LinearLayout lLBottomSelectTab, LinearLayout lLBottomMoreTab, TabSelectedCallBack mTabSelectedCallBack) {
        SocialMediaFragment mReferralsReceivedFragment = new SocialMediaFragment();
        mReferralsReceivedFragment.mLlTopTabs = mLlTopTabs;
        mReferralsReceivedFragment.mLlBottomTabs = mLlBottomTabs;
        mReferralsReceivedFragment.mBtnDelete = mBtnDelete;
        mReferralsReceivedFragment.mLLBottomSelectTab = lLBottomSelectTab;
        mReferralsReceivedFragment.mLLBottomMoreTab = lLBottomMoreTab;
        mReferralsReceivedFragment.mTabSelectedCallBack = mTabSelectedCallBack;

        return mReferralsReceivedFragment;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        mContext = getActivity();
        View view = inflater.inflate(R.layout.fragment_social_media, container, false);
        initView(view);
        return view;
    }

    private void initView(View view){
        mSharedpreferences=new SharedPreference();
        llTwitter=(LinearLayout)view.findViewById(R.id.llTwitter);
        llTwitterAccess=(LinearLayout)llTwitter.findViewById(R.id.llTwitterAllowAccess);
        llTwitterSetting=(LinearLayout)llTwitter.findViewById(R.id.llTwitterDetails);
        mTextviewAllowTwitter=(TextView)llTwitter.findViewById(R.id.textviewAllowTwitter);

        llFacebook=(LinearLayout)view.findViewById(R.id.llFacebook);
        llFacebookAccess=(LinearLayout)llFacebook.findViewById(R.id.llFacebookAllowAccess);
        llFacebookSetting=(LinearLayout)llFacebook.findViewById(R.id.llFacebookDetails);
        mTextviewAllowFacebook=(TextView)llFacebook.findViewById(R.id.textviewAllowFacebook);

        llLinkedin=(LinearLayout)view.findViewById(R.id.llLinkedin);
        llLinkedinAccess=(LinearLayout)llLinkedin.findViewById(R.id.llLinkedinAllowAccess);
        llLinkedinSetting=(LinearLayout)llLinkedin.findViewById(R.id.llLinkedinDetails);
        mTextviewAllowLinkedin=(TextView)llLinkedin.findViewById(R.id.textviewAllowLinkedin);

        llTwitterAccess.setOnClickListener(this);
        llTwitterSetting.setOnClickListener(this);

        llFacebookAccess.setOnClickListener(this);
        llFacebookSetting.setOnClickListener(this);

        llLinkedinAccess.setOnClickListener(this);
        llLinkedinSetting.setOnClickListener(this);



    }

    @Override
    public void onClick(View view) {

        if (view == llTwitterAccess) {
            if(getResponse.getResult().getTwitter()){
                callWebServices(2,ApplicationConstants.TWITTER_TEXT);
            }else {
                Intent intent = new Intent(getActivity(), WebViewActivity.class);
                intent.putExtra(ApplicationConstants.FORCONFIGURATION, WebServiceConstants.TWITTER_LOGIN + mSharedpreferences.getUserId(mContext));
                intent.putExtra(ApplicationConstants.CANCEL_SOCIAL_MEDIA, ApplicationConstants.TWITTER_TEXT);
                //startActivityResult(intent,1001);
                startActivityForResult(intent,1001);
            }
            //Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(WebServiceConstants.TWITTER_LOGIN+mSharedpreferences.getUserId(mContext)));
            //mContext.startActivity(browserIntent);
        } else if (view == llTwitterSetting) {
            Intent intent=new Intent(getActivity(),SocialDetailsActivity.class);
            intent.putExtra(ApplicationConstants.FORCONFIGURATION,ApplicationConstants.TWITTER_VALUE);
            startActivity(intent);
        } else if (view == llFacebookAccess) {
            if(getResponse.getResult().getFacebook()){
                callWebServices(2,ApplicationConstants.FACEBOOK_TEXT);
            }else {
                Intent intent = new Intent(getActivity(), WebViewActivity.class);
                intent.putExtra(ApplicationConstants.FORCONFIGURATION, WebServiceConstants.FACEBOOK_LOGIN + mSharedpreferences.getUserId(mContext));
                intent.putExtra(ApplicationConstants.CANCEL_SOCIAL_MEDIA, ApplicationConstants.FACEBOOK_TEXT);
                //startActivity(intent);
                startActivityForResult(intent,1002);
            }
        } else if (view == llFacebookSetting) {
            Intent intent=new Intent(getActivity(),SocialDetailsActivity.class);
            intent.putExtra(ApplicationConstants.FORCONFIGURATION,ApplicationConstants.FACEBOOK_VALUE);
            startActivity(intent);
        } else if (view == llLinkedinAccess) {
            if(getResponse.getResult().getLinkedin()){
                callWebServices(2,ApplicationConstants.LINKEDIN_TEXT);
            }else {
                Intent intent = new Intent(getActivity(), WebViewActivity.class);
                intent.putExtra(ApplicationConstants.FORCONFIGURATION, WebServiceConstants.LINKEDIN_LOGIN + mSharedpreferences.getUserId(mContext));
                intent.putExtra(ApplicationConstants.CANCEL_SOCIAL_MEDIA, ApplicationConstants.LINKEDIN_TEXT);
                //startActivity(intent);
                startActivityForResult(intent,1003);
            }
        } else if (view == llLinkedinSetting) {
            Intent intent=new Intent(getActivity(),SocialDetailsActivity.class);
            intent.putExtra(ApplicationConstants.FORCONFIGURATION,ApplicationConstants.LINKED_IN_VALUE);
            startActivity(intent);
        }

    }

    /**
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_SOCIAL_MEDIA_ACTION_NAME, WebServiceConstants.GET_CC_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getPurchaseReceipt(new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mWebserviceStatus = true;

                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseSocialMediaResponse(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                mWebserviceStatus = true;
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void revokeWebservice(String revokeList) throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_SOCIAL_REVOKE_ACTION_NAME, WebServiceConstants.GET_CC_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.revokeAccess(getRequestModel(revokeList), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mWebserviceStatus = true;

                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseRevokeResponse(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                mWebserviceStatus = true;
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * parseSocialMedia
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseSocialMediaResponse(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        SocialMediaResponseModel get_Response = gson.fromJson(responseStr, SocialMediaResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;
            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null ) {
                    checkedUpdateUI(getResponse);
                }
            } else {
                ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * parseRevoke access
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseRevokeResponse(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        SocialMediaResponseModel get_Response = gson.fromJson(responseStr, SocialMediaResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;
            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage(), new AlertCallBack() {
                    @Override
                    public void alertAction(boolean select) {
                        callWebServices(1, "");
                    }
                });

            } else {
                ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
        }
    }


    /**
     * get revoke request model
     */

    public RevokeRequestModel getRequestModel(String revokeList){
        RevokeRequestModel mRevokeRequestModel=new RevokeRequestModel();
        mRevokeRequestModel.setRevokeList(revokeList);
        return  mRevokeRequestModel;
    }


    /**
     * updating ui based on server response
     * @param get_Response
     */
    private void checkedUpdateUI(SocialMediaResponseModel get_Response){
        /**
         * Here boolean value coming from service
         * to display grant or revoke and display setting value
         */

        if (getResponse.getResult().getTwitter()) {
            llTwitterSetting.setVisibility(View.VISIBLE);
            mTextviewAllowTwitter.setText(getString(R.string.str_social_twitter_revoke_access));
        }else{
            llTwitterSetting.setVisibility(View.INVISIBLE);
            mTextviewAllowTwitter.setText(getString(R.string.str_social_twitter_allow_access));
        }
        if (getResponse.getResult().getFacebook()) {
            llFacebookSetting.setVisibility(View.VISIBLE);
            mTextviewAllowFacebook.setText(getString(R.string.str_social_facebook_revoke_access));
        }else{
            llFacebookSetting.setVisibility(View.INVISIBLE);
            mTextviewAllowFacebook.setText(getString(R.string.str_social_facebook_allow_access));
        }
        if (getResponse.getResult().getLinkedin()) {
            llLinkedinSetting.setVisibility(View.VISIBLE);
            mTextviewAllowLinkedin.setText(getString(R.string.str_social_linkedin_revoke_access));
        }else{
            llLinkedinSetting.setVisibility(View.INVISIBLE);
            mTextviewAllowLinkedin.setText(getString(R.string.str_social_linkedin_allow_access));
        }

    }

    @Override
    public void onResume() {
        super.onResume();
        callWebServices(1,"");
    }

    public void callWebServices(int type,String listtype){
        if (AppUtils.isOnline(getActivity())) {
            mWebserviceStatus=false;
            mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
            mProgressHUD.setCancelable(false);
            try {
                if(type==1) {
                    callWebservice();
                }else if(type==2){
                    revokeWebservice(listtype);
                }
            }catch (Exception e){
                e.printStackTrace();
            }
        } else {
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
        }
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == 1001) {
            if (resultCode == getActivity().RESULT_OK && data.getStringExtra(ApplicationConstants.CANCEL_SOCIAL_MEDIA).equals(ApplicationConstants.TWITTER_TEXT))
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.str_alert_twitter_cancel));
         }else if (requestCode == 1002) {
            if (resultCode == getActivity().RESULT_OK && data.getStringExtra(ApplicationConstants.CANCEL_SOCIAL_MEDIA).equals(ApplicationConstants.FACEBOOK_TEXT))
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.str_alert_facebook_cancel));
        }else if (requestCode == 1003) {
            if (resultCode == getActivity().RESULT_OK && data.getStringExtra(ApplicationConstants.CANCEL_SOCIAL_MEDIA).equals(ApplicationConstants.LINKEDIN_TEXT))
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.str_alert_linkedin_cancel));
        }
    }
}
