/**
 * Activity using to compose message
 * As a registered user can  compose a message to send it to the group/team members
 * @author Rajeev
 */
package com.app.foxhopr.ui.messages;

import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.IntentConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.ui.activities.SelectMembersActivity;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.MutiPartRequest;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.Validation;
import com.app.foxhopr.webservice.models.AttachmentsListBean;
import com.app.foxhopr.webservice.models.CurrentTeamInnerModel;
import com.app.foxhopr.webservice.models.SendReferralsResponseModel;
import com.app.foxhopr.webservice.models.TeamMebmersListInnerModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;

public class MessageComposeActivity extends FragmentActivity implements View.OnClickListener {
    private static String TAG="MessageComposeActivity";
    private ArrayList<TeamMebmersListInnerModel> getSelectedMemberListData;
    private FrameLayout mFlMessagerecipient;
    private TextView mButtonSelectRecipient;
    private EditText mEditTextMessageSubject;
    private EditText mEditTextMessageBody;
    private RelativeLayout mRlAttachFile;
    private LinearLayout mLlAttachmentList;
    private LinearLayout mLlSend;

    private ArrayList<AttachmentsListBean> attachmentListArr;
    private ProgressHUD mProgressHUD;

    private String mSubject="";
    private String mContent="";
    private String strMemberList ="";
    private RelativeLayout rlBack;
    private TextView mTextComposeHeader;
    private TextView mSendtext;
    private ArrayList<CurrentTeamInnerModel> selectedMember = new ArrayList<CurrentTeamInnerModel>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_new_message_compose);
        initView();
        attachmentListArr = new ArrayList<AttachmentsListBean>();
        try{
            selectedMember=(ArrayList<CurrentTeamInnerModel>) getIntent().getSerializableExtra("member_ids");
            setTeamMember();
        }catch (Exception e){
            e.printStackTrace();
        }

    }

    private void setTeamMember(){

        if(null!=selectedMember){
            getSelectedMemberListData=new ArrayList<TeamMebmersListInnerModel>();
        }

        for(CurrentTeamInnerModel cTMember:selectedMember ){
            TeamMebmersListInnerModel mTeamMebmersListInnerModel=new TeamMebmersListInnerModel();
            mTeamMebmersListInnerModel.setId(cTMember.getId());
            mTeamMebmersListInnerModel.setUser_id(cTMember.getUser_id());
            mTeamMebmersListInnerModel.setFname(cTMember.getFname());
            mTeamMebmersListInnerModel.setLname(cTMember.getLname());
            mTeamMebmersListInnerModel.setIsChecked(true);
            mTeamMebmersListInnerModel.setSelected(true);
            getSelectedMemberListData.add(mTeamMebmersListInnerModel);
        }

        if(getSelectedMemberListData != null){
            if(getSelectedMemberListData.size() ==1){
                mButtonSelectRecipient.setText(getSelectedMemberListData.get(0).getFname() +" "+getSelectedMemberListData.get(0).getLname());
            }else{
                mButtonSelectRecipient.setText(getResources().getString(R.string.str_team_members)+"("+getSelectedMemberListData.size()+")");
            }
            mLlSend.setOnClickListener(this);
            mLlSend.getBackground().setAlpha(250);
            mSendtext.setTextColor(Color.parseColor("#ffffff"));

        }
    }

    /**
     * Initializing the layout views
     */
    private void initView(){

        rlBack	=	(RelativeLayout)findViewById(R.id.rlBack);

        mTextComposeHeader	=	(TextView)findViewById(R.id.textViewHeaderTitle);
        mTextComposeHeader.setVisibility(View.VISIBLE);
        mTextComposeHeader.setText(getResources().getString(R.string.str_compose_message));


        mFlMessagerecipient=(FrameLayout)findViewById(R.id.flMessagerecipient);
        mButtonSelectRecipient=(TextView)findViewById(R.id.buttonSelectRecipient);
        mEditTextMessageSubject=(EditText)findViewById(R.id.editTextMessageSubject);
        mEditTextMessageBody=(EditText)findViewById(R.id.editTextMessageBody);
        mRlAttachFile=(RelativeLayout)findViewById(R.id.rlAttachFile);
        mLlAttachmentList=(LinearLayout)findViewById(R.id.llAttachmentList);
        mLlSend=(LinearLayout)findViewById(R.id.llSend);

        mSendtext=(TextView)findViewById(R.id.sendtext);

        mFlMessagerecipient.setOnClickListener(this);
        mRlAttachFile.setOnClickListener(this);

        mLlSend.getBackground().setAlpha(200);
        mSendtext.setTextColor(Color.parseColor("#A0A0A0"));
        mLlSend.setOnClickListener(null);

        rlBack.setOnClickListener(this);
        applyValidationFilters();
    }

    /**
     * multiple view click event
     * @param view
     */
    @Override
    public void onClick(View view) {
        if(view==mFlMessagerecipient){
            Intent intObj = new Intent(MessageComposeActivity.this, SelectMembersActivity.class);
            if(getSelectedMemberListData != null ){

                intObj.putExtra(IntentConstants.SELECTED_MEMBER_LIST, getSelectedMemberListData);
                Bundle bundle = new Bundle();
                bundle.putSerializable(IntentConstants.SELECTED_MEMBER_LIST, getSelectedMemberListData);
                intObj.putExtras(bundle);
            }
            intObj.putExtra("from", getResources().getString(R.string.str_messages));
            startActivityForResult(intObj, ApplicationConstants.MESSAGE_CODE);
            overridePendingTransition(0,0);
        }else if(view==mRlAttachFile){

            final Intent intent = new Intent(Intent.ACTION_GET_CONTENT);
            intent.setType("*/*");
            intent.addCategory(Intent.CATEGORY_OPENABLE);
            intent.setAction(Intent.ACTION_GET_CONTENT);
            startActivityForResult(Intent.createChooser(intent, "Select a File to Upload"), ApplicationConstants.MESSAGE_FILE_UPLOAD_CODE);
        }else if(view==mLlSend){
            mSubject=mEditTextMessageSubject.getText().toString().trim();
            mContent=mEditTextMessageBody.getText().toString().trim();
            strMemberList="";
            for(int i =0; i <getSelectedMemberListData.size(); i++){
                if(strMemberList.length()==0){
                    strMemberList += getSelectedMemberListData.get(i).getUser_id();
                }else{
                    strMemberList += ","+getSelectedMemberListData.get(i).getUser_id();
                }
            }

            if(checkValidation()){
                try{
                    if (AppUtils.isOnline(MessageComposeActivity.this)) {
                        new ImageUploadTask().execute();
                    }
                    else{
                        ErrorMsgDialog.showErrorAlert(MessageComposeActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
                    }
                }catch (Exception ex){
                    ex.printStackTrace();
                }
            }
        }else if(view==rlBack){
            finish();
            overridePendingTransition(0,0);
        }

    }

    /**
     * Activity result for member list and files attaching for compose message
     * @param requestCode
     * @param resultCode
     * @param data
     */
    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {

        if (requestCode == ApplicationConstants.MESSAGE_CODE) {
            if(resultCode == RESULT_OK){
                String result=data.getStringExtra("result");
                if(data != null){
                    Bundle bundle=data.getExtras();
                    getSelectedMemberListData = 	(ArrayList<TeamMebmersListInnerModel>)bundle.getSerializable(IntentConstants.SELECTED_MEMBER_LIST);
                    if(getSelectedMemberListData != null){
                        if(getSelectedMemberListData.size() ==1){
                            mButtonSelectRecipient.setText(getSelectedMemberListData.get(0).getFname() +" "+getSelectedMemberListData.get(0).getLname());
                        }else{
                            mButtonSelectRecipient.setText(getResources().getString(R.string.str_team_members)+"("+getSelectedMemberListData.size()+")");
                        }
                        mLlSend.setOnClickListener(this);
                        mLlSend.getBackground().setAlpha(250);
                        mSendtext.setTextColor(Color.parseColor("#ffffff"));

                    }
                }
            }
            if (resultCode == RESULT_CANCELED) {
                //Write your code if there's no result
            }
        }else if(requestCode == ApplicationConstants.MESSAGE_FILE_UPLOAD_CODE){
            if (resultCode == RESULT_OK) {
                // Get the Uri of the selected file
                Uri uri = data.getData();
                // Get the path
                String path;
                try {
                    if(uri != null){
                        String urlStr = AppUtils.getPath(MessageComposeActivity.this, uri);
                        if(urlStr != null){
                            Log.e("File Path: ", "" + attachmentListArr.size());
                            Log.e("File Path: ", "" + urlStr);
                            Log.e("Basename : ", "" + AppUtils.getFileNameFromUrl(urlStr));
                            if(AppUtils.getFileNameFromUrl(urlStr) != null && AppUtils.getFileExt(AppUtils.getFileNameFromUrl(urlStr)) != null){
                                if(AppUtils.checkFileExtension(AppUtils.getFileExt(AppUtils.getFileNameFromUrl(urlStr)))){
                                    Log.e("print the file size", ""+AppUtils.getFileSize(urlStr));

                                    if(AppUtils.getFileSize(urlStr) < 10.2){
                                        if(attachmentListArr.size() > 0){
                                            float totalSize =0;
                                            for(int i=0; i < attachmentListArr.size(); i++){
                                                totalSize +=totalSize+AppUtils.getFileSize(attachmentListArr.get(i).getFileUrl());
                                            }
                                            if((AppUtils.getFileSize(urlStr) +totalSize) < 10.2){
                                                addBeanObj(urlStr);
                                            }
                                            else{
                                                ErrorMsgDialog.showErrorAlert(MessageComposeActivity.this, "", getString(R.string.wrng_str_attachment_exceed_limit));
                                            }
                                        }else{
                                            addBeanObj(urlStr);
                                        }
                                    }else{
                                        ErrorMsgDialog.showErrorAlert(MessageComposeActivity.this, "", getString(R.string.wrng_str_file_too_large));
                                    }
                                }else{
                                    ErrorMsgDialog.showErrorAlert(MessageComposeActivity.this, "", getString(R.string.wrng_str_file_format_check));
                                }
                            }
                        }
                    }
                } catch (Exception e) {
                    // TODO Auto-generated catch block
                    e.printStackTrace();
                }
            }
        }
    }

    private void addBeanObj(String fileUrl){
        AttachmentsListBean mAttachmentsListBean = new AttachmentsListBean();
        mAttachmentsListBean.setFileUrl(fileUrl);
        mAttachmentsListBean.setName(AppUtils.getFileNameFromUrl(fileUrl));
        attachmentListArr.add(mAttachmentsListBean);
        createAttachmentList(attachmentListArr);
    }

    //Dynamically attaching multiple files in layout
    public void createAttachmentList(final ArrayList<AttachmentsListBean> dataArray){

        if(mLlAttachmentList.getChildCount() >0)
            mLlAttachmentList.removeAllViews();

        for(int i=0; i< dataArray.size(); i++){
            LayoutInflater inflater = (LayoutInflater)getBaseContext().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            View listItemv = inflater.inflate(R.layout.item_list_attachments, null);

            TextView mTxtvFileNameName = (TextView) listItemv.findViewById(R.id.textvFileNameList);
            ImageView imgAttchmentDelete = (ImageView) listItemv.findViewById(R.id.imgAttchmentDelete);
            mTxtvFileNameName.setText(dataArray.get(i).getName());

            final int pos = i;
            imgAttchmentDelete.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if (attachmentListArr != null && attachmentListArr.size() > 0) {
                        attachmentListArr.remove(dataArray.get(pos));
                        createAttachmentList(attachmentListArr);
                    }
                }
            });

            if(dataArray.size()>=5){
                mRlAttachFile.setVisibility(View.GONE);
            }else{
                mRlAttachFile.setVisibility(View.VISIBLE);
            }

            mLlAttachmentList.addView(listItemv);
        }
    }

    private void applyValidationFilters() {
        mEditTextMessageSubject.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValid(MessageComposeActivity.this, mEditTextMessageSubject, mEditTextMessageSubject);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });

        mEditTextMessageBody.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValid(MessageComposeActivity.this, mEditTextMessageBody, mEditTextMessageBody);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
    }

    /**
     * to check all validation
     * here subject and body content is required
     *
     * @return boolean
     */
    private boolean checkValidation(){
        boolean isAllFieldCorrect = true;

        if (mSubject.length() == 0 || mContent.length() == 0) {
            isAllFieldCorrect = false;

            if (mSubject.length() == 0 ) {
                mEditTextMessageSubject.requestFocus();
                Validation.hasInputValid(MessageComposeActivity.this, mEditTextMessageSubject, mEditTextMessageSubject);
            }

            if ( mContent.length() == 0 ) {
                if ( mSubject.length() != 0 ) {
                    mEditTextMessageBody.requestFocus();
                }
                Validation.hasInputValid(MessageComposeActivity.this, mEditTextMessageBody, mEditTextMessageBody);
            }
            ErrorMsgDialog.showErrorAlert(MessageComposeActivity.this, "", getString(R.string.wrng_str_empty_requried_field));

        }else if(mSubject.length() >0 && mSubject.length() >65){

            isAllFieldCorrect = false;
            mEditTextMessageSubject.requestFocus();
            mEditTextMessageSubject.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(MessageComposeActivity.this, "", getString(R.string.wrng_str_subject_max_limit));
        }else if (mSubject.length() >0 && !Validation.isValidSubjectFormat(mSubject))  {

            isAllFieldCorrect = false;
            mEditTextMessageSubject.requestFocus();
            mEditTextMessageSubject.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(MessageComposeActivity.this, "", getString(R.string.wrng_str_subject_invalid));
        }else if(mContent.length() >0 && mContent.length() >5000){

            isAllFieldCorrect = false;
            mEditTextMessageBody.requestFocus();
            mEditTextMessageBody.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(MessageComposeActivity.this, "", getString(R.string.wrng_str_message_body_max_limit));
        }
        return isAllFieldCorrect;
    }

    /**
     * Asynctask uploading files as well as data of composing message
     */
    class ImageUploadTask extends AsyncTask<Void, Void, String> {

        private String responseStr = "";

        @Override
        protected void onPreExecute() {
            mProgressHUD = ProgressHUD.show(MessageComposeActivity.this,"", true,true);
            mProgressHUD.setCancelable(false);
        }

        @Override
        protected String doInBackground(Void... params) {
            try {
                ArrayList<File> files = new ArrayList<File>() ;

                for(int i =0; i< attachmentListArr.size(); i++){
                    files.add(new File(attachmentListArr.get(i).getFileUrl()));
                }

                responseStr = MutiPartRequest.postImage(files, getPostRequestParmes(), MessageComposeActivity.this, WebServiceConstants.MESSAGE_COMPOSE_CONTROL, WebServiceConstants.MESSAGE_COMPOSE_ACTION_NAME);

                Log.e(TAG, responseStr);
            } catch (Exception e) {
                e.printStackTrace();
            }
            return null;
        }

        @Override
        protected void onPostExecute(String result) {
            mProgressHUD.dismiss();
            try {
                parseSendReferralsService(responseStr);

            } catch (Exception ex) {
                ex.printStackTrace();
                ErrorMsgDialog.showErrorAlert(MessageComposeActivity.this, "", getString(R.string.wrng_str_server_error));
            }

        }
    }

    /**
     * parsing compose message response
     * @param responseStr
     * @throws Exception
     */

    private void parseSendReferralsService(String responseStr) throws Exception{
        Gson gson = new Gson();
        SendReferralsResponseModel get_Response = gson.fromJson(responseStr, SendReferralsResponseModel.class);

        if(get_Response != null){
            if (get_Response.getReponseCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                ErrorMsgDialog.alertOkButtonCallBack(MessageComposeActivity.this, "", get_Response.getMessage(),new OkPressedResponse());

            }else{
                ErrorMsgDialog.showErrorAlert(MessageComposeActivity.this, "", get_Response.getMessage());
            }
        }else{
            ErrorMsgDialog.showErrorAlert(MessageComposeActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }
    //callback method after successful response
    class OkPressedResponse implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            // Call web service here
            try {
                /*Intent intObj = new Intent(MessageComposeActivity.this, DashboardActivity.class);
                intObj.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(intObj);
                overridePendingTransition(0, 0);*/
                finish();
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    /**
     * Method use to create the hash map that posted to server.
     * @return
     */

    private HashMap<String, String> getPostRequestParmes() {

        HashMap<String, String> postValues = new HashMap<String, String>();

        postValues.put("subject", mSubject);
        postValues.put("content", mContent);
        postValues.put("teamMembers", strMemberList);
        postValues.put("messageType", "message");
        return postValues;

    }
}
