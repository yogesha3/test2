/**
 * Fragment using to change password
 */
package com.app.foxhopr.fragments.user.Settings;


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
import android.widget.LinearLayout;

import com.app.foxhopr.adapter.CurrentTeamListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.ChangePasswordRequestModel;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.app.foxhopr.utils.Validation;
import com.app.foxhopr.webservice.models.ReferralsResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * A simple {@link Fragment} subclass.
 */
public class ChangePasswordFragment extends Fragment  implements View.OnClickListener{
    private static String TAG = "ChangePasswordFragment";
    private CurrentTeamListAdapter mCurrentTeamListAdapter;
    private Context mContext;
    private HorizontalScrollView mLlTopTabs;
    private LinearLayout mLlBottomTabs;
    private Button mBtnDelete;
    private Button mBtnReadUnRead;
    private LinearLayout mLLBottomSelectTab;
    private LinearLayout mLLBottomMoreTab;
    private TabSelectedCallBack mTabSelectedCallBack;

    private EditText mEditTextCurrentPassword;
    private EditText mEditTextNewPassword;
    private EditText mEditTextConfirmPassword;

    private LinearLayout mLlSelectReset;
    private LinearLayout mLlSelectSave;

    private ProgressHUD mProgressHUD;
    private boolean mWebserviceStatus = true;

    private String mOldPassword;
    private String mNewPassword;
    private String mConfirmPassword;
    private View view;

    public ChangePasswordFragment() {
        // Required empty public constructor
    }
    public static ChangePasswordFragment newInstance(HorizontalScrollView mLlTopTabs, LinearLayout mLlBottomTabs, Button mBtnDelete,Button mBtnReadUnRead,
                                                  LinearLayout lLBottomSelectTab, LinearLayout lLBottomMoreTab, TabSelectedCallBack mTabSelectedCallBack) {
        ChangePasswordFragment mCurrentTeamFragment = new ChangePasswordFragment();
        mCurrentTeamFragment.mLlTopTabs = mLlTopTabs;
        mCurrentTeamFragment.mLlBottomTabs = mLlBottomTabs;
        mCurrentTeamFragment.mBtnDelete = mBtnDelete;
        mCurrentTeamFragment.mBtnReadUnRead = mBtnReadUnRead;
        mCurrentTeamFragment.mLLBottomSelectTab = lLBottomSelectTab;
        mCurrentTeamFragment.mLLBottomMoreTab = lLBottomMoreTab;
        mCurrentTeamFragment.mTabSelectedCallBack = mTabSelectedCallBack;

        return mCurrentTeamFragment;
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        mContext = getActivity();
        View view = inflater.inflate(R.layout.fragment_change_password, container, false);
        this.view=view;
        initView(view);
        return view;
    }

    private void initView(View view) {

        mEditTextCurrentPassword=(EditText)view.findViewById(R.id.editTextCurrentPassword);
        mEditTextNewPassword=(EditText)view.findViewById(R.id.editTextNewPassword);
        mEditTextConfirmPassword=(EditText)view.findViewById(R.id.editTextConfirmPassword);

        mEditTextCurrentPassword.setTypeface(AppUtils.getTypeFace(getActivity()));
        mEditTextNewPassword.setTypeface(AppUtils.getTypeFace(getActivity()));
        mEditTextConfirmPassword.setTypeface(AppUtils.getTypeFace(getActivity()));

        mLlSelectReset=(LinearLayout)view.findViewById(R.id.llSelectReset);
        mLlSelectSave=(LinearLayout)view.findViewById(R.id.llSelectSave);



        mBtnDelete.setOnClickListener(null);
        mBtnReadUnRead.setOnClickListener(null);
        mLLBottomSelectTab.setOnClickListener(null);
        mLLBottomMoreTab.setOnClickListener(null);

        mLlSelectReset.setOnClickListener(this);
        mLlSelectSave.setOnClickListener(this);
        applyValidationFilters();
    }

    private void setView(){
        mEditTextCurrentPassword.setText("");
        mEditTextNewPassword.setText("");
        mEditTextConfirmPassword.setText("");
    }

    @Override
    public void onClick(View view) {
        if(view==mLlSelectReset){
            mEditTextCurrentPassword.setText("");
            mEditTextNewPassword.setText("");
            mEditTextConfirmPassword.setText("");
        }else if(view==mLlSelectSave){
            mOldPassword=mEditTextCurrentPassword.getText().toString();
            mNewPassword=mEditTextNewPassword.getText().toString();
            mConfirmPassword=mEditTextConfirmPassword.getText().toString();
            if(checkValidation()){
                try {
                    if (AppUtils.isOnline(getActivity())) {
                        mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
                        mProgressHUD.setCancelable(false);
                        callWebservice();
                    } else {
                        ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

        }

    }

    /**
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.CHANGE_PASSWORD_ACTION_NAME, WebServiceConstants.GET_NOTIFICATION_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.changePassword(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mWebserviceStatus = true;

                mProgressHUD.dismiss();
                mTabSelectedCallBack.selectAction(false);
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        Gson gson = new Gson();
                        ReferralsResponseModel get_Response = gson.fromJson(WebServiceUtils.getResponseString(responseModel), ReferralsResponseModel.class);
                        if (null != get_Response && get_Response.getReponseCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                            ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage());
                            mEditTextCurrentPassword.setText("");
                            mEditTextNewPassword.setText("");
                            mEditTextConfirmPassword.setText("");
                        } else {
                            //{"code":"404","message":"Current and New password cannot be same"}
                            //{"code":"404","message":"Current password is incorrect"}

                            if(get_Response.getMessage().equals(getString(R.string.str_password_same))){
                                Validation.hasInputPassword(getActivity(), mEditTextCurrentPassword, mEditTextCurrentPassword);
                                Validation.hasInputPassword(getActivity(), mEditTextNewPassword, mEditTextNewPassword);
                            }else if(get_Response.getMessage().equals(getString(R.string.str_incorrect_password))){
                                Validation.hasInputPassword(getActivity(), mEditTextCurrentPassword, mEditTextCurrentPassword);
                            }
                            ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage());
                        }
                        //{"code":"200","message":"Your password has been changed successfully."}
                        //parseReferralsListService(WebServiceUtils.getResponseString(responseModel));
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
     * This method is use for get the request model for change password
     *
     * @return
     */
    private ChangePasswordRequestModel getRequestModel() throws Exception {
        ChangePasswordRequestModel mChangePasswordRequestModel = new ChangePasswordRequestModel();
        mChangePasswordRequestModel.setOld_password(mOldPassword);
        mChangePasswordRequestModel.setNew_password(mNewPassword);
        mChangePasswordRequestModel.setConfirm_password(mConfirmPassword);
        return mChangePasswordRequestModel;
    }

    private void applyValidationFilters() {
        mEditTextCurrentPassword.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputPassworodNew(getActivity(), mEditTextCurrentPassword, mEditTextCurrentPassword);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
        mEditTextNewPassword.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputPassworodNew(getActivity(), mEditTextNewPassword, mEditTextNewPassword);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
        mEditTextConfirmPassword.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputPassworodNew(getActivity(), mEditTextConfirmPassword, mEditTextConfirmPassword);
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
     * Method to check the validation on the page
     * @return
     */
    private boolean checkValidation() {
        /*private String mOldPassword;
        private String mNewPassword;
        private String mConfirmPassword;*/
        boolean isAllFieldCorrect = true;
        if (mOldPassword.length() == 0 && mNewPassword.length() == 0 && mConfirmPassword.length()==0) {
            isAllFieldCorrect = false;
            mEditTextNewPassword.requestFocus();
            Validation.hasInputPassword(getActivity(), mEditTextCurrentPassword, mEditTextCurrentPassword);
            Validation.hasInputPassword(getActivity(), mEditTextNewPassword, mEditTextNewPassword);
            Validation.hasInputPassword(getActivity(), mEditTextConfirmPassword, mEditTextConfirmPassword);
            ErrorMsgDialog.showErrorAlert(getActivity(), "",getString(R.string.wrng_str_empty_requried_field));

        }else if (mOldPassword.length() == 0 ) {
            isAllFieldCorrect = false;
            mEditTextCurrentPassword.requestFocus();
            Validation.hasInputPassword(getActivity(), mEditTextCurrentPassword, mEditTextCurrentPassword);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_empty_requried_field));
        }else if (mNewPassword.length() == 0) {
            isAllFieldCorrect = false;
            mEditTextNewPassword.requestFocus();
            Validation.hasInputPassword(getActivity(), mEditTextNewPassword, mEditTextNewPassword);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_empty_requried_field));
        }else if (mConfirmPassword.length() == 0) {
            isAllFieldCorrect = false;
            mEditTextConfirmPassword.requestFocus();
            Validation.hasInputPassword(getActivity(), mEditTextConfirmPassword, mEditTextConfirmPassword);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_empty_requried_field));
        }else if (mOldPassword.contains(" ")) {
            isAllFieldCorrect = false;
            mEditTextCurrentPassword.requestFocus();
            Validation.hasInputPassword(getActivity(), mEditTextCurrentPassword, mEditTextCurrentPassword);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_password_space_not_allowed));
        }else if (mNewPassword.contains(" ")) {
            isAllFieldCorrect = false;
            mEditTextNewPassword.requestFocus();
            Validation.hasInputPassword(getActivity(), mEditTextNewPassword, mEditTextNewPassword);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_password_space_not_allowed));
        }else if (mNewPassword.length() < 6 || mNewPassword.length() >20) {
            isAllFieldCorrect = false;
            mEditTextNewPassword.requestFocus();
            Validation.hasInputPassword(getActivity(), mEditTextNewPassword, mEditTextNewPassword);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_password_length));
        }else if (!mNewPassword.equals(mConfirmPassword)) {
            isAllFieldCorrect = false;
            mEditTextConfirmPassword.requestFocus();
            Validation.hasInputPassword(getActivity(), mEditTextConfirmPassword, mEditTextConfirmPassword);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_password_not_match));
        } else {
            isAllFieldCorrect = true;
        }
        return isAllFieldCorrect;
    }
}
