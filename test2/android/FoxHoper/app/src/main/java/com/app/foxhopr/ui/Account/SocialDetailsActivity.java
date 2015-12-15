/**
 * Social media setting where user can accept three type of notification
 * during creating referral
 * sending message
 * and sending invite
 * respective can be share on facebook,twitter and linckedin
 */
package com.app.foxhopr.ui.Account;

import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.util.Log;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.adapter.SocialNotificaitonListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.SocialListRequestModel;
import com.app.foxhopr.swipemenulist.SwipeMenuListView;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.CurrentTeamListClickCallBack;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.webservice.models.CurrentTeamInnerModel;
import com.app.foxhopr.webservice.models.SocialMediaResponseFacebook;
import com.app.foxhopr.webservice.models.SocialMediaResponseLinkedin;
import com.app.foxhopr.webservice.models.SocialMediaResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.ArrayList;
import java.util.Arrays;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class SocialDetailsActivity extends FragmentActivity implements View.OnClickListener {

    private static String TAG="SocialDetailsActivity";
    private SocialNotificaitonListAdapter mPurchaseReceiptAdapter;
    private SharedPreference mPreference;
    private ImageView mBackButton;
    private TextView mTxtvSendReferrals;
    private SwipeMenuListView mListViewReceived;
    private ImageView mLogo;

    private ArrayList<CurrentTeamInnerModel> listDataArray;
    private boolean isAllSelected = false;

    private ProgressHUD mProgressHUD;

    //Page data
    private SocialMediaResponseModel getResponse;


    //variable storing state of search or simple by default referal
    private boolean mWebserviceStatus = true;
    private LinearLayout llSave;
    private String mEditSocialMedia;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_twitter_details);
        initView();
    }

    private void initView(){
        mPreference=new SharedPreference();
        listDataArray = new ArrayList<CurrentTeamInnerModel>();
        mBackButton=(ImageView)findViewById(R.id.imgvDrawer);
        mBackButton.setOnClickListener(this);

        mTxtvSendReferrals	=	(TextView)findViewById(R.id.textViewHeaderTitle);
        mTxtvSendReferrals.setVisibility(View.VISIBLE);
        mTxtvSendReferrals.setText(getResources().getString(R.string.str_configure_post));
        mListViewReceived = (SwipeMenuListView) findViewById(R.id.listViewNotification);
        mLogo=(ImageView)findViewById(R.id.logo);
        llSave=(LinearLayout)findViewById(R.id.llSave);
        llSave.setOnClickListener(this);
        /*if (AppUtils.isOnline(SocialDetailsActivity.this)) {
            // mFooterProgressBar.setVisibility(View.VISIBLE);
            mWebserviceStatus=false;
            mProgressHUD = ProgressHUD.show(SocialDetailsActivity.this, "", true, true);
            mProgressHUD.setCancelable(false);
            try {
                callWebservice();
            }catch (Exception e){
                e.printStackTrace();
            }
        } else {
            ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
        }*/
        getDataForList();
    }

    class OnCheckBoxSeletedResponse implements CurrentTeamListClickCallBack {

        @Override
        public void itemClickAction(ArrayList<CurrentTeamInnerModel> mList) {
            try {
                Log.i("List size", "" + mList.size());
                if(null!=mList && mList.size()==listDataArray.size()){
                    //mTabSelectedCallBack.selectAction(true);
                    isAllSelected = true;
                }else{
                    //mTabSelectedCallBack.selectAction(false);
                    isAllSelected = false;
                }

            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    private void getDataForList() {

       /* Intent intent = getIntent();
        String action = intent.getAction();
        Uri data = intent.getData();
        Log.e(TAG,action);
        Log.e(TAG,data.toString());*/

        listDataArray.clear();
        String [] Title=null;
        String [] Description=null;
        if (getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION).equals(ApplicationConstants.TWITTER_VALUE)) {
            if(null !=getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION_ALERT) && getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION_ALERT).equals(ApplicationConstants.TWITTER_TEXT)){
                ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.str_alert_twitter));
            }else if(null !=getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION_ALERT)  && getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION_ALERT).equals(ApplicationConstants.FORCONFIGURATION_ALERT)){
                ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.str_alert_twitter_already_linked));
            }
            mEditSocialMedia=ApplicationConstants.TWITTER_TEXT;
            mLogo.setImageResource(R.drawable.icon_twitter_big);
            Title = getResources().getStringArray(R.array.string_configure_twitter_title);
            Description = getResources().getStringArray(R.array.string_configure_twitter_details);
            callWebServices(1,ApplicationConstants.TWITTER_TEXT);
        } else if (getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION).equals(ApplicationConstants.FACEBOOK_VALUE)) {
            if(null !=getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION_ALERT)  && getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION_ALERT).equals(ApplicationConstants.FACEBOOK_TEXT)){
                ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.str_alert_facebook));
            }else if(null !=getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION_ALERT)  && getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION_ALERT).equals(ApplicationConstants.FORCONFIGURATION_ALERT)){
                ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.str_alert_facebook_already_linked));
            }
            mEditSocialMedia=ApplicationConstants.FACEBOOK_TEXT;
            mLogo.setImageResource(R.drawable.icon_facebook_big);
            Title = getResources().getStringArray(R.array.string_configure_twitter_title);
            Description = getResources().getStringArray(R.array.string_configure_fb_linkedin_details);
            callWebServices(1,ApplicationConstants.FACEBOOK_TEXT);
        } else if (getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION).equals(ApplicationConstants.LINKED_IN_VALUE)) {
            if(null !=getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION_ALERT) && getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION_ALERT).equals(ApplicationConstants.LINKEDIN_TEXT)){
                ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.str_alert_linkedin));
            }else if(null !=getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION_ALERT)  && getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION_ALERT).equals(ApplicationConstants.FORCONFIGURATION_ALERT)){
                ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.str_alert_linkedin_already_linked));
            }
            mEditSocialMedia=ApplicationConstants.LINKEDIN_TEXT;
            mLogo.setImageResource(R.drawable.icon_linkedin_big);
            Title = getResources().getStringArray(R.array.string_configure_twitter_title);
            Description = getResources().getStringArray(R.array.string_configure_fb_linkedin_details);
            callWebServices(1,ApplicationConstants.LINKEDIN_TEXT);
        }
        ArrayList<String> myList = new ArrayList<String>(Arrays.asList(Title));
        ArrayList<String> myExist = new ArrayList<String>();

        for(int i=0;i<myList.size();i++){
            CurrentTeamInnerModel mdm=new CurrentTeamInnerModel();
            mdm.setFname(myList.get(i));
            mdm.setLname(Description[i]);
            mdm.setSelected(false);
            mdm.setIsChecked(false);
            listDataArray.add(mdm);
        }

        try{
            setListAdapter();
        }catch (Exception e){

        }
        if(null!=myExist && myExist.size()==listDataArray.size()){
            //mTabSelectedCallBack.selectAction(true);
            isAllSelected = true;
        }else{
            //mTabSelectedCallBack.selectAction(false);
            isAllSelected = false;
        }


    }
    /**
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callListWebservice(final String listType) throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_SOCIAL_MEDIA_ACTION_NAME, WebServiceConstants.GET_CC_CONTROL_NAME, SocialDetailsActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.SocialMediaList(getRequestModelList(listType),new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mWebserviceStatus = true;

                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        if(listType.equals(ApplicationConstants.TWITTER_TEXT)) {
                            parseSocialMediaTwitter(WebServiceUtils.getResponseString(responseModel));
                        }else if(listType.equals(ApplicationConstants.FACEBOOK_TEXT)){
                            parseSocialMediaFacebook(WebServiceUtils.getResponseString(responseModel));
                        }else if(listType.equals(ApplicationConstants.LINKEDIN_TEXT)){
                            parseSocialMediaLinkedin(WebServiceUtils.getResponseString(responseModel));
                        }
                    } else {
                        ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
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
                ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * parseSocialMediaTwitter
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseSocialMediaTwitter(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        SocialMediaResponseModel get_Response = gson.fromJson(responseStr, SocialMediaResponseModel.class);

        if (get_Response != null) {
            //getResponse = get_Response;
            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null ) {

                    checkedUpdateUITwitter(get_Response);
                }
            } else {
                ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }
    /**
     * parseSocialMediaFacebook
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseSocialMediaFacebook(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        SocialMediaResponseFacebook get_Response = gson.fromJson(responseStr, SocialMediaResponseFacebook.class);

        if (get_Response != null) {
            //getResponse = get_Response;
            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (null!=get_Response.getResult() ) {
                    checkedUpdateUIFacebook(get_Response);
                }
            } else {
                ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * parseSocialMediaLinkedin
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseSocialMediaLinkedin(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        SocialMediaResponseLinkedin get_Response = gson.fromJson(responseStr, SocialMediaResponseLinkedin.class);

        if (get_Response != null) {
            //getResponse = get_Response;
            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null ) {

                    checkedUpdateUILinkedin(get_Response);
                }
            } else {
                ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }


    /**
     * updating ui with twitter response
     * @param get_Response
     */
    private void checkedUpdateUITwitter(SocialMediaResponseModel get_Response){
        for(int i=0;i<listDataArray.size();i++){
            CurrentTeamInnerModel mdm=new CurrentTeamInnerModel();
            if(null!=get_Response)
                switch(i){
                    case 0:
                        if(get_Response.getResult().isTweetReferralSend()){
                            listDataArray.get(i).setIsChecked(true);
                            listDataArray.get(i).setSelected(true);
                        }else{
                            listDataArray.get(i).setIsChecked(false);
                            listDataArray.get(i).setSelected(false);
                        }
                        break;
                    case 1:
                        if(get_Response.getResult().isTweetMessageSend()){
                            listDataArray.get(i).setIsChecked(true);
                            listDataArray.get(i).setSelected(true);
                        }else{
                            listDataArray.get(i).setIsChecked(false);
                            listDataArray.get(i).setSelected(false);
                        }
                        break;
                    case 2:
                        if(get_Response.getResult().isTweetInviteSend()){
                            listDataArray.get(i).setIsChecked(true);
                            listDataArray.get(i).setSelected(true);
                        }else{
                            listDataArray.get(i).setIsChecked(false);
                            listDataArray.get(i).setSelected(false);
                        }
                        break;
                }
        }
        mPurchaseReceiptAdapter.notifyDataSetChanged();
    }

    /**
     * updating ui with facebook response
     * @param get_Response
     */
    private void checkedUpdateUIFacebook(SocialMediaResponseFacebook get_Response){
        for(int i=0;i<listDataArray.size();i++){
            CurrentTeamInnerModel mdm=new CurrentTeamInnerModel();
            if(null!=get_Response)
                switch(i){
                    case 0:
                        if(get_Response.getResult().isFbReferralSend()){
                            listDataArray.get(i).setIsChecked(true);
                            listDataArray.get(i).setSelected(true);
                        }else{
                            listDataArray.get(i).setIsChecked(false);
                            listDataArray.get(i).setSelected(false);
                        }
                        break;
                    case 1:
                        if(get_Response.getResult().isFbMessageSend()){
                            listDataArray.get(i).setIsChecked(true);
                            listDataArray.get(i).setSelected(true);
                        }else{
                            listDataArray.get(i).setIsChecked(false);
                            listDataArray.get(i).setSelected(false);
                        }
                        break;
                    case 2:
                        if(get_Response.getResult().isFbInviteSend()){
                            listDataArray.get(i).setIsChecked(true);
                            listDataArray.get(i).setSelected(true);
                        }else{
                            listDataArray.get(i).setIsChecked(false);
                            listDataArray.get(i).setSelected(false);
                        }
                        break;
                }
        }
        mPurchaseReceiptAdapter.notifyDataSetChanged();
    }

    /**
     * Updating ui for linkedin response
     * @param get_Response
     */
    private void checkedUpdateUILinkedin(SocialMediaResponseLinkedin get_Response){
        for(int i=0;i<listDataArray.size();i++){
            CurrentTeamInnerModel mdm=new CurrentTeamInnerModel();
            if(null!=get_Response)
                switch(i){
                    case 0:
                        if(get_Response.getResult().isLinkedinReferralSend()){
                            listDataArray.get(i).setIsChecked(true);
                            listDataArray.get(i).setSelected(true);
                        }else{
                            listDataArray.get(i).setIsChecked(false);
                            listDataArray.get(i).setSelected(false);
                        }
                        break;
                    case 1:
                        if(get_Response.getResult().isLinkedinMessageSend()){
                            listDataArray.get(i).setIsChecked(true);
                            listDataArray.get(i).setSelected(true);
                        }else{
                            listDataArray.get(i).setIsChecked(false);
                            listDataArray.get(i).setSelected(false);
                        }
                        break;
                    case 2:
                        if(get_Response.getResult().isLinkedinInviteSend()){
                            listDataArray.get(i).setIsChecked(true);
                            listDataArray.get(i).setSelected(true);
                        }else{
                            listDataArray.get(i).setIsChecked(false);
                            listDataArray.get(i).setSelected(false);
                        }
                        break;
                }
        }
        mPurchaseReceiptAdapter.notifyDataSetChanged();
    }


    /**
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callEditWebservice(final String listType) throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_SOCIAL_MEDIA_ACTION_NAME, WebServiceConstants.GET_CC_CONTROL_NAME, SocialDetailsActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.SocialMediaList(getRequestModelEdit(listType), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mWebserviceStatus = true;

                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseEditResponse(WebServiceUtils.getResponseString(responseModel), listType);
                    } else {
                        ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
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
                ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }


    /**
     * parsing resopnse after edit
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseEditResponse(String responseStr,String listType) throws Exception {
        Log.e(TAG, responseStr);
        Object get_Response=null;
        Gson gson = new Gson();
        if(listType.equals(ApplicationConstants.TWITTER_TEXT)){
             get_Response =gson.fromJson(responseStr, SocialMediaResponseModel.class);
        }else if(listType.equals(ApplicationConstants.FACEBOOK_TEXT)){
             get_Response = gson.fromJson(responseStr, SocialMediaResponseFacebook.class);
        }else if (listType.equals(ApplicationConstants.LINKEDIN_TEXT)){
             get_Response =  gson.fromJson(responseStr, SocialMediaResponseLinkedin.class);
        }
        if (get_Response != null) {
            //getResponse = get_Response;
            if (get_Response instanceof SocialMediaResponseModel) {
                if (((SocialMediaResponseModel) get_Response).getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                    ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", ((SocialMediaResponseModel) get_Response).getMessage(), new AlertCallBack() {
                        @Override
                        public void alertAction(boolean select) {
                            finish();
                        }
                    });
                }
            } else if (get_Response instanceof SocialMediaResponseFacebook) {
                if (((SocialMediaResponseFacebook) get_Response).getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                    ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", ((SocialMediaResponseFacebook) get_Response).getMessage(), new AlertCallBack() {
                        @Override
                        public void alertAction(boolean select) {
                            finish();
                        }
                    });
                }
            } else if (get_Response instanceof SocialMediaResponseLinkedin) {
                if (((SocialMediaResponseLinkedin) get_Response).getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                    ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", ((SocialMediaResponseLinkedin) get_Response).getMessage(), new AlertCallBack() {
                        @Override
                        public void alertAction(boolean select) {
                            finish();
                        }
                    });
                }
            }
        } else {
            ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    private void displayMessage(){

    }

    /**
     * set request model
     */

    private SocialListRequestModel getRequestModelList(String listType){
        SocialListRequestModel mSocialListRequestModel=new SocialListRequestModel();
        mSocialListRequestModel.setMode("list");
        mSocialListRequestModel.setList(listType);

        return mSocialListRequestModel;
    }

    /**
     * gettting request model for twitter edit
     * @param listtype
     * @return
     */
    private SocialListRequestModel getRequestModelEdit(String listtype){
        SocialListRequestModel mSocialListRequestModel=new SocialListRequestModel();
        mSocialListRequestModel.setMode("edit");
        mSocialListRequestModel.setList(listtype);
        mSocialListRequestModel.setUpdateSocial(listtype);

        ArrayList<String> name=new ArrayList<String>();
        //getResponse
        if(listtype.equals(ApplicationConstants.TWITTER_TEXT)){
            for(int i=0;i<listDataArray.size();i++){
                if(listDataArray.get(i).isSelected()){
                    //'tweetReferralSend','tweetMessageSend','tweetInviteSend'
                    switch(i){
                        case 0:
                            name.add("tweetReferralSend");
                            break;
                        case 1:
                            name.add("tweetMessageSend");
                            break;
                        case 2:
                            name.add("tweetInviteSend");
                            break;
                    }
                }
            }
        }else if(listtype.equals(ApplicationConstants.FACEBOOK_TEXT)){
            for(int i=0;i<listDataArray.size();i++){
                if(listDataArray.get(i).isSelected()){
                    // 'fbReferralSend', 'fbMessageSend', 'fbInviteSend'
                    switch(i){
                        case 0:
                            name.add("fbReferralSend");
                            break;
                        case 1:
                            name.add("fbMessageSend");
                            break;
                        case 2:
                            name.add("fbInviteSend");
                            break;
                    }
                }
            }
        }else if(listtype.equals(ApplicationConstants.LINKEDIN_TEXT)){
            for(int i=0;i<listDataArray.size();i++){
                if(listDataArray.get(i).isSelected()){
                    //'linkedinReferralSend','linkedinMessageSend','linkedinInviteSend'
                    switch(i){
                        case 0:
                            name.add("linkedinReferralSend");
                            break;
                        case 1:
                            name.add("linkedinMessageSend");
                            break;
                        case 2:
                            name.add("linkedinInviteSend");
                            break;
                    }
                }
            }
        }

        String [] newArray=name.toArray(new String[name.size()]);
        mSocialListRequestModel.setUpdateFields(newArray);
        return mSocialListRequestModel;
    }

    /**
     * Method use for set the data on List
     *
     * @throws Exception
     */

    private void setListAdapter() throws Exception {
        mPurchaseReceiptAdapter = new SocialNotificaitonListAdapter(SocialDetailsActivity.this, listDataArray,new OnCheckBoxSeletedResponse());
        // set our adapter and pass our recived list content
        mListViewReceived.setAdapter(mPurchaseReceiptAdapter);
    }

    @Override
    public void onClick(View view) {
        if(view==mBackButton){
            finish();
        }else if(view==llSave){
            callWebServices(2,mEditSocialMedia);
        }

    }


    public void callWebServices(int type,String listtype){
        if (AppUtils.isOnline(SocialDetailsActivity.this)) {
            mWebserviceStatus=false;
            mProgressHUD = ProgressHUD.show(SocialDetailsActivity.this, "", true, true);
            mProgressHUD.setCancelable(false);
            try {
                if(type==1) {//for list
                    callListWebservice(listtype);
                }else if(type==2){//for edit
                    callEditWebservice(listtype);
                }
            }catch (Exception e){
                e.printStackTrace();
            }
        } else {
            ErrorMsgDialog.showErrorAlert(SocialDetailsActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
        }
    }

}
