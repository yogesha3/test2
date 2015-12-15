/**
 * Display the UI for invite friends
 * User can invide their friends with name,email and message
 * Here multiple (max 10) partner can be invited.
 */
package com.app.foxhopr.fragments.user.Contacts;


import android.content.Context;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.InvitePartnerRequestModel;
import com.app.foxhopr.request.models.PartnerBean;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.MoveToPartner;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.app.foxhopr.utils.Validation;
import com.app.foxhopr.webservice.models.PartnerListBean;
import com.app.foxhopr.webservice.models.SendReferralsResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.ArrayList;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * A simple {@link Fragment} subclass.
 */
public class InvitePartnerFragment extends Fragment implements View.OnClickListener {
    private static String TAG = "InvitePartnerFragment";

    private Context mContext;
    private HorizontalScrollView mLlTopTabs;
    private LinearLayout mLlBottomTabs;
    private Button mBtnDelete;
    private Button mBtnReadUnRead;
    private LinearLayout mLLBottomSelectTab;
    private LinearLayout mLLBottomMoreTab;
    private TabSelectedCallBack mTabSelectedCallBack;
    private MoveToPartner mMoveToPartner;

    //private LinearLayout mLladd_partner;
    private LinearLayout mLlPartnerList;
    private LinearLayout mLlSendInvitation;
    private EditText mEditTextMessageInvite;
    private EditText mEditTextPNameCreateNewContact;
    private EditText mEditTextPEmailCreateNewContact;

    private ImageView imageViewPlus;
    //private TextView mTextviewAdd;
    private SharedPreference mSharedPreference;
    private ProgressHUD mProgressHUD;


    private ArrayList<PartnerListBean> PartnerListArr=new ArrayList<PartnerListBean>();


    public InvitePartnerFragment() {
        // Required empty public constructor
    }
    public static InvitePartnerFragment newInstance(HorizontalScrollView mLlTopTabs, LinearLayout mLlBottomTabs, Button mBtnDelete,Button mBtnReadUnRead,
                                              LinearLayout lLBottomSelectTab, LinearLayout lLBottomMoreTab, TabSelectedCallBack mTabSelectedCallBack,MoveToPartner mMoveToPartner) {
        InvitePartnerFragment mInvitePartnerFragment = new InvitePartnerFragment();
        mInvitePartnerFragment.mLlTopTabs = mLlTopTabs;
        mInvitePartnerFragment.mLlBottomTabs = mLlBottomTabs;
        mInvitePartnerFragment.mBtnDelete = mBtnDelete;
        mInvitePartnerFragment.mBtnReadUnRead = mBtnReadUnRead;
        mInvitePartnerFragment.mLLBottomSelectTab = lLBottomSelectTab;
        mInvitePartnerFragment.mLLBottomMoreTab = lLBottomMoreTab;
        mInvitePartnerFragment.mTabSelectedCallBack = mTabSelectedCallBack;
        mInvitePartnerFragment.mMoveToPartner = mMoveToPartner;

        return mInvitePartnerFragment;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        //getActivity().getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_ADJUST_RESIZE);
        mContext = getActivity();
        View view = inflater.inflate(R.layout.fragment_invite_partner, container, false);
        initView(view);
        return view;
    }

    private void initView(View view){
        mSharedPreference=new SharedPreference();
        //mLladd_partner=(LinearLayout)view.findViewById(R.id.lladd_partner);
        mLlPartnerList=(LinearLayout)view.findViewById(R.id.llPartnerList);
        mLlSendInvitation=(LinearLayout)view.findViewById(R.id.llSendInvitation);

        mEditTextMessageInvite=(EditText)view.findViewById(R.id.editTextMessageInvite);
        mEditTextPNameCreateNewContact=(EditText)view.findViewById(R.id.editTextPNameCreateNewContact);
        mEditTextPEmailCreateNewContact=(EditText)view.findViewById(R.id.editTextPEmailCreateNewContact);

        imageViewPlus=(ImageView)view.findViewById(R.id.ImageViewPlus);
        //mTextviewAdd=(TextView)view.findViewById(R.id.textviewAdd);

        mBtnDelete.setOnClickListener(null);
        mLLBottomSelectTab.setOnClickListener(null);
        mLLBottomMoreTab.setOnClickListener(null);

        //mLladd_partner.setOnClickListener(this);
        mLlSendInvitation.setOnClickListener(this);
        imageViewPlus.setOnClickListener(this);
        //mTextviewAdd.setOnClickListener(this);
        mLLBottomSelectTab.findViewById(R.id.imgvBottomTabSelect).setAlpha(0.5f);
        mLLBottomMoreTab.findViewById(R.id.imgvBottomTabSearch).setAlpha(0.5f);
        addFirstPartner();
        setDefaultText();
    }


    private void addBeanObj(){
        PartnerListBean mPartnerListBean = new PartnerListBean();
        mPartnerListBean.setPartner_email("");
        mPartnerListBean.setPartner_name("");

        PartnerListArr.add(mPartnerListBean);
        createPartnerList(PartnerListArr);
    }


    public void createPartnerList(final ArrayList<PartnerListBean> PartnerListArr){

        if(mLlPartnerList.getChildCount() >0)
            mLlPartnerList.removeAllViews();

        for(int i=1; i< PartnerListArr.size(); i++) {

            LayoutInflater inflater = (LayoutInflater) getActivity().getBaseContext().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            final View listItemv = inflater.inflate(R.layout.item_list_partners, null);

            EditText mTxtvPartnerName = (EditText) listItemv.findViewById(R.id.editTextPNameCreateNewContact);
            EditText mTxtvPartnerEmail = (EditText) listItemv.findViewById(R.id.editTextPEmailCreateNewContact);
            mTxtvPartnerName.setText(PartnerListArr.get(i).getPartner_name());
            mTxtvPartnerEmail.setText(PartnerListArr.get(i).getPartner_email());
            applyValidation(mTxtvPartnerName, mTxtvPartnerEmail);
            ImageView imgAttchmentDelete = (ImageView) listItemv.findViewById(R.id.imgAttchmentDelete);
            final int pos = i;
            imgAttchmentDelete.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if (PartnerListArr != null && PartnerListArr.size() > 0) {
                        PartnerListArr.remove(PartnerListArr.get(pos));
                        createPartnerList(PartnerListArr);
                        //mLlPartnerList.removeView(listItemv);
                    }
                }
            });

            if (PartnerListArr.size() >= 10) {
                imageViewPlus.setVisibility(View.GONE);
            } else {
                imageViewPlus.setVisibility(View.VISIBLE);
            }

            mLlPartnerList.addView(listItemv);
        }
    }

    @Override
    public void onClick(View view) {
        if(view==imageViewPlus){
            imageViewPlus.setEnabled(false);
            for(int i=0;i<PartnerListArr.size();i++){
                if(i==0){
                    PartnerListArr.get(i).setPartner_name(mEditTextPNameCreateNewContact.getText().toString().trim());
                    PartnerListArr.get(i).setPartner_email(mEditTextPEmailCreateNewContact.getText().toString().trim());
                }else{
                    View listItemv=mLlPartnerList.getChildAt(i-1);
                    final EditText mTxtvPartnerName = (EditText) listItemv.findViewById(R.id.editTextPNameCreateNewContact);
                    final EditText mTxtvPartnerEmail = (EditText) listItemv.findViewById(R.id.editTextPEmailCreateNewContact);
                    PartnerListArr.get(i).setPartner_name(mTxtvPartnerName.getText().toString().trim());
                    PartnerListArr.get(i).setPartner_email(mTxtvPartnerEmail.getText().toString().trim());
                }
            }
            addBeanObj();
            imageViewPlus.setEnabled(true);
        }else if(view==mLlSendInvitation){
            for(int i=0;i<PartnerListArr.size();i++){
                if(i==0){
                    PartnerListArr.get(i).setPartner_name(mEditTextPNameCreateNewContact.getText().toString().trim());
                    PartnerListArr.get(i).setPartner_email(mEditTextPEmailCreateNewContact.getText().toString().trim());
                }else{
                    View listItemv=mLlPartnerList.getChildAt(i-1);
                    final EditText mTxtvPartnerName = (EditText) listItemv.findViewById(R.id.editTextPNameCreateNewContact);
                    final EditText mTxtvPartnerEmail = (EditText) listItemv.findViewById(R.id.editTextPEmailCreateNewContact);
                    PartnerListArr.get(i).setPartner_name(mTxtvPartnerName.getText().toString().trim());
                    PartnerListArr.get(i).setPartner_email(mTxtvPartnerEmail.getText().toString().trim());
                    //applyValidation(mTxtvPartnerName,mTxtvPartnerEmail);

                }
            }

            if(checkValidation()){
                //Call the web service

                try {
                    //TODO code for calling web service
                    try{
                        if (AppUtils.isOnline(getActivity())) {
                            mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                            mProgressHUD.setCancelable(false);
                            callInvitePartnerWebServices();
                        }
                        else{
                            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                        }
                    }catch (Exception ex){
                        ex.printStackTrace();
                    }


                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }
        }

    }

    /**
     * Method to check the validation on the page
     * Hear first checking required fields are contains values or not
     * then checking validation like email or partner name length or partner allowed char
     * @return boolean
     */
    private boolean checkValidation() {
        boolean isAllFieldCorrect = true;
        boolean isAllFieldValid = true;
        int validText = 0;
        //checking fields are field or not
        for(int i=0;i<PartnerListArr.size();i++) {
                EditText mTxtvPartnerName=null;
                EditText mTxtvPartnerEmail=null;
                if (i == 0) {
                    mTxtvPartnerName=mEditTextPNameCreateNewContact;
                    mTxtvPartnerEmail=mEditTextPEmailCreateNewContact;
                }else{
                    View listItemv = mLlPartnerList.getChildAt(i-1);
                    mTxtvPartnerName = (EditText) listItemv.findViewById(R.id.editTextPNameCreateNewContact);
                    mTxtvPartnerEmail = (EditText) listItemv.findViewById(R.id.editTextPEmailCreateNewContact);
                }

                if (PartnerListArr.get(i).getPartner_name().length() == 0  || PartnerListArr.get(i).getPartner_email().length() == 0 || mEditTextMessageInvite.getText().toString().length() == 0) {
                    isAllFieldCorrect = false;
                    if (PartnerListArr.get(i).getPartner_name().length() == 0) {
                        mTxtvPartnerName.requestFocus();
                        Validation.hasInputValid(getActivity(), mTxtvPartnerName, mTxtvPartnerName);
                    }
                    if (PartnerListArr.get(i).getPartner_email().length() == 0) {
                        if (PartnerListArr.get(i).getPartner_name().length() != 0) {
                            mTxtvPartnerEmail.requestFocus();
                        }
                        Validation.hasInputValid(getActivity(), mTxtvPartnerEmail, mTxtvPartnerEmail);
                    }
                    if (mEditTextMessageInvite.getText().toString().length() == 0) {
                        mEditTextMessageInvite.requestFocus();
                        Validation.hasInputValid(getActivity(), mEditTextMessageInvite, mEditTextMessageInvite);
                    }
                }
        }
        if(!isAllFieldCorrect){
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_empty_requried_field));
        }else {
            //checking for validations
            for(int i=0;i<PartnerListArr.size();i++) {
                EditText mTxtvPartnerName=null;
                EditText mTxtvPartnerEmail=null;
                if (i == 0) {
                    mTxtvPartnerName=mEditTextPNameCreateNewContact;
                    mTxtvPartnerEmail=mEditTextPEmailCreateNewContact;
                }else{
                    View listItemv = mLlPartnerList.getChildAt(i-1);
                    mTxtvPartnerName = (EditText) listItemv.findViewById(R.id.editTextPNameCreateNewContact);
                    mTxtvPartnerEmail = (EditText) listItemv.findViewById(R.id.editTextPEmailCreateNewContact);
                }

                 validText=checkexistingTextValidation(mTxtvPartnerName,mTxtvPartnerEmail);
                 if(validText==0){
                     isAllFieldValid=true;
                 }else{
                     isAllFieldValid=false;
                     break;
                 }
            }

            switch (validText) {
                case 0:
                    break;
                case 1:
                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_partner_name_max_limit));
                    break;
                case 2:
                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_partner_name_invalid));
                    break;
                case 3:
                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_invalid_email));
                    break;
                case 4:
                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_comment_max_limit));
                    break;
            }
        }
        return  isAllFieldCorrect && isAllFieldValid;
    }

    private int checkexistingTextValidation(EditText Name,EditText Email){
        int errortype=0;
        if (Name.getText().toString().length() >20 ) {
            Name.requestFocus();
            Name.setBackgroundResource(R.drawable.bg_select_team_error);
            errortype=1;
            //ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_fname_max_limit));
        }else if (!Validation.isValidString(Name.getText().toString())) {
            Name.requestFocus();
            Name.setBackgroundResource(R.drawable.bg_select_team_error);
            errortype=2;
            //ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_fname_invalid));
        }
        else if(Email.getText().toString().length() >0 && !Validation.isValidEmail(Email.getText().toString())){
            Email.requestFocus();
            Email.setBackgroundResource(R.drawable.bg_select_team_error);
            //ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_invalid_email));
            errortype=3;
        }else if(mEditTextMessageInvite.getText().toString().length() >0 && mEditTextMessageInvite.getText().toString().length() >350){
            mEditTextMessageInvite.requestFocus();
            mEditTextMessageInvite.setBackgroundResource(R.drawable.bg_select_team_error);
            //ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_comment_max_limit));
            errortype=4;
        }
        return errortype;
    }


    private void  addFirstPartner(){
        PartnerListBean mPartnerListBean = new PartnerListBean();
        mPartnerListBean.setPartner_email("");
        mPartnerListBean.setPartner_name("");
        PartnerListArr.add(mPartnerListBean);
        applyValidation(mEditTextPNameCreateNewContact, mEditTextPEmailCreateNewContact);

    }
    private void setDefaultText(){
        String text = getActivity().getResources().getString(R.string.str_invite_partner_message, mSharedPreference.getUserFname(getActivity())+ " " +mSharedPreference.getUserLname(getActivity()));
        mEditTextMessageInvite.setText(text);
    }

    private void callInvitePartnerWebServices() throws Exception{
        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.INVITE_PARTNER_ACTION_NAME, WebServiceConstants.ADD_CONTACT_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.invitePartner(getInviteModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseInvitePartner(WebServiceUtils.getResponseString(responseModel));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * This method is use for get the request model
     * @return
     */
    private InvitePartnerRequestModel getInviteModel() throws  Exception{
        InvitePartnerRequestModel mInvitePartnerRequestModel = new InvitePartnerRequestModel();
        ArrayList<PartnerBean> data=new ArrayList<PartnerBean>();
        for(int i=0;i<PartnerListArr.size();i++) {
            PartnerBean Partner=new PartnerBean();
            Partner.setName(PartnerListArr.get(i).getPartner_name());
            Partner.setEmail(PartnerListArr.get(i).getPartner_email());
            data.add(Partner);
        }
        mInvitePartnerRequestModel.setData(data);
        mInvitePartnerRequestModel.setMessage(mEditTextMessageInvite.getText().toString());

        return mInvitePartnerRequestModel;
    }

    private void parseInvitePartner(String responseStr) throws Exception{
        Gson gson = new Gson();
        SendReferralsResponseModel get_Response = gson.fromJson(responseStr, SendReferralsResponseModel.class);

        if(get_Response != null){
            if (get_Response.getReponseCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                ErrorMsgDialog.alertOkButtonCallBack(getActivity(), "", get_Response.getMessage(),new OkPressedResponse());

            }else{
                ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage());
            }
        }else{
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
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
                mMoveToPartner.toPartner(true);
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    private void applyValidation(final EditText mTxtvPartnerName,final EditText mTxtvPartnerEmail){
        mTxtvPartnerName.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValid(getActivity(), mTxtvPartnerName, mTxtvPartnerName);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
        // TextWatcher would let us check validation error on the fly
        mTxtvPartnerEmail.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValid(getActivity(), mTxtvPartnerEmail, mTxtvPartnerEmail);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });

        mTxtvPartnerEmail.addTextChangedListener(new TextWatcher() {
            // after every change has been made to this editText, we would like to check validity
            @Override
            public void afterTextChanged(Editable s) {
                Validation.isEmailAddressCorrect(getActivity(), mTxtvPartnerEmail, mTxtvPartnerEmail, true);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
    }
}
