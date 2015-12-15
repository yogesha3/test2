/**
 * Fragment using to send suggestions
 * maximum 500 char allowed
 *
 */
package com.app.foxhopr.fragments.user.Suggestions;


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
import com.app.foxhopr.request.models.SuggestionsRequestModel;
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
public class SuggestionsFragmentTab extends Fragment  implements View.OnClickListener{
    private static String TAG = "SuggestionsFragmentTab";
    private CurrentTeamListAdapter mCurrentTeamListAdapter;
    private Context mContext;
    private HorizontalScrollView mLlTopTabs;
    private LinearLayout mLlBottomTabs;
    private Button mBtnDelete;
    private Button mBtnReadUnRead;
    private LinearLayout mLLBottomSelectTab;
    private LinearLayout mLLBottomMoreTab;
    private TabSelectedCallBack mTabSelectedCallBack;

    private ProgressHUD mProgressHUD;
    private boolean mWebserviceStatus = true;

    private LinearLayout mLLSubmit;
    private EditText mEditTextSuggestions;
    private String mSuggestions;

    private View view;

    public SuggestionsFragmentTab() {
        // Required empty public constructor
    }
    public static SuggestionsFragmentTab newInstance(HorizontalScrollView mLlTopTabs, LinearLayout mLlBottomTabs, Button mBtnDelete,Button mBtnReadUnRead,
                                                  LinearLayout lLBottomSelectTab, LinearLayout lLBottomMoreTab, TabSelectedCallBack mTabSelectedCallBack) {
        SuggestionsFragmentTab mCurrentTeamFragment = new SuggestionsFragmentTab();
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
        View view = inflater.inflate(R.layout.fragment_suggestions_tab, container, false);
        this.view=view;
        initView(view);
        return view;
    }

    private void initView(View view) {

        mLLSubmit=(LinearLayout)view.findViewById(R.id.llSubmitSuggestion);
        mEditTextSuggestions=(EditText)view.findViewById(R.id.editTextSuggestionBody);

        mBtnDelete.setOnClickListener(null);
        mBtnReadUnRead.setOnClickListener(null);
        mLLBottomSelectTab.setOnClickListener(null);
        mLLBottomMoreTab.setOnClickListener(null);
        mLLSubmit.setOnClickListener(this);
        applyValidationFilters();
    }

    @Override
    public void onClick(View view) {
        if(view==mLLSubmit){
            mSuggestions=mEditTextSuggestions.getText().toString();
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

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_SUGGESTION_ACTION_NAME, WebServiceConstants.GET_SUGGESTION_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.postSuggestions(getRequestModel(), new Callback<Response>() {
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
                            mEditTextSuggestions.setText("");
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
    private SuggestionsRequestModel getRequestModel() throws Exception {
        SuggestionsRequestModel mSuggestionsRequestModel = new SuggestionsRequestModel();
        mSuggestionsRequestModel.setSuggestion(mSuggestions);
        return mSuggestionsRequestModel;
    }

    private void applyValidationFilters() {
        mEditTextSuggestions.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputPassworodNew(getActivity(), mEditTextSuggestions, mEditTextSuggestions);
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
        if (mSuggestions.length() == 0) {
            isAllFieldCorrect = false;
            mEditTextSuggestions.requestFocus();
            Validation.hasInputPassword(getActivity(), mEditTextSuggestions, mEditTextSuggestions);
            ErrorMsgDialog.showErrorAlert(getActivity(), "",getString(R.string.wrng_str_empty_requried_field));

        }else if(mSuggestions.length() >0 && mSuggestions.length() >500){

            isAllFieldCorrect = false;
            mEditTextSuggestions.requestFocus();
            mEditTextSuggestions.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_suggestions_body_max_limit));
        }else {
            isAllFieldCorrect = true;
        }
        return isAllFieldCorrect;
    }
}
