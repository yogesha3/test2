package com.app.foxhopr.fragments.user.Team;


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
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.adapter.CurrentTeamListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.GoalsRequestModel;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.app.foxhopr.utils.Validation;
import com.app.foxhopr.webservice.models.GoalDetailsResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * A simple {@link Fragment} subclass.
 */
public class GoalFragment extends Fragment implements View.OnClickListener{

    private static String TAG = "GoalFragment";
    private CurrentTeamListAdapter mCurrentTeamListAdapter;
    private Context mContext;
    private HorizontalScrollView mLlTopTabs;
    private LinearLayout mLlBottomTabs;
    private Button mBtnDelete;
    private Button mBtnReadUnRead;
    private LinearLayout mLLBottomSelectTab;
    private LinearLayout mLLBottomMoreTab;
    private TabSelectedCallBack mTabSelectedCallBack;

    private EditText mEditTextGroupGoalTarget;
    private EditText mEditTextGroupGoalActual;
    private EditText mEditTextMemberGoalTarget;
    private EditText mEditTextMemberGoalActual;
    private EditText mEditTextIndividualGoalTarget;
    private EditText mEditTextIndividualGoalActual;

    private String mGroupGoalTarget;
    private String mMemberGoalTarget;
    private String mIndividualGoalTarget;

    private LinearLayout mLlUpdateGoal;
    private ProgressHUD mProgressHUD;
    private SharedPreference mPreferences;
    private GoalDetailsResponseModel getResponse;


    public GoalFragment() {
        // Required empty public constructor
    }

    public static GoalFragment newInstance(HorizontalScrollView mLlTopTabs, LinearLayout mLlBottomTabs, Button mBtnDelete,Button mBtnReadUnRead,
                                                  LinearLayout lLBottomSelectTab, LinearLayout lLBottomMoreTab, TabSelectedCallBack mTabSelectedCallBack) {
        GoalFragment mGoalFragment = new GoalFragment();
        mGoalFragment.mLlTopTabs = mLlTopTabs;
        mGoalFragment.mLlBottomTabs = mLlBottomTabs;
        mGoalFragment.mBtnDelete = mBtnDelete;
        mGoalFragment.mBtnReadUnRead = mBtnReadUnRead;
        mGoalFragment.mLLBottomSelectTab = lLBottomSelectTab;
        mGoalFragment.mLLBottomMoreTab = lLBottomMoreTab;
        mGoalFragment.mTabSelectedCallBack = mTabSelectedCallBack;

        return mGoalFragment;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        mContext = getActivity();
        View view = inflater.inflate(R.layout.fragment_goal, container, false);
        initView(view);
        return view;
    }

    private void initView(View view){
        mPreferences = new SharedPreference();
        mBtnDelete.setOnClickListener(null);
        mLLBottomSelectTab.setOnClickListener(null);
        mLLBottomMoreTab.setOnClickListener(null);

        mEditTextGroupGoalTarget=(EditText)view.findViewById(R.id.group_goal_target);
        mEditTextGroupGoalActual=(EditText)view.findViewById(R.id.group_goal_actual);
        mEditTextMemberGoalTarget=(EditText)view.findViewById(R.id.member_goal_target);

        mEditTextMemberGoalActual=(EditText)view.findViewById(R.id.member_goal_actual);
        mEditTextIndividualGoalTarget=(EditText)view.findViewById(R.id.individual_goal_target);
        mEditTextIndividualGoalActual=(EditText)view.findViewById(R.id.individual_goal_actual);
        mLlUpdateGoal=(LinearLayout)view.findViewById(R.id.llUpdateGoal);
        mLlUpdateGoal.setOnClickListener(this);

        mLLBottomMoreTab.setAlpha(0.5f);
        mLLBottomSelectTab.setAlpha(0.5f);

        applyValidationFilters();
        //Call the webservices
        new OkPressedResponse().alertAction(true);

    }

    /**
     * CallBack class for delete
     */
    class OkPressedResponse implements AlertCallBack {
        private  int position;

        public OkPressedResponse(){
            this.position=position;
        }

        @Override
        public void alertAction(boolean select) {
            try{
                if (AppUtils.isOnline(getActivity())) {
                    mProgressHUD = ProgressHUD.show(getActivity(),"", true,true);
                    mProgressHUD.setCancelable(false);
                    callWebservice();
                }
                else{
                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                }
            }catch (Exception ex){
                ex.printStackTrace();
            }

        }
    }

    @Override
    public void onClick(View view) {
        if(view==mLlUpdateGoal){
            mGroupGoalTarget=mEditTextGroupGoalTarget.getText().toString();
            mMemberGoalTarget=mEditTextMemberGoalTarget.getText().toString();
            mIndividualGoalTarget=mEditTextIndividualGoalTarget.getText().toString();
            if(checkValidation()){
                try{
                    if (AppUtils.isOnline(getActivity())) {
                        mProgressHUD = ProgressHUD.show(getActivity(),"", true,true);
                        mProgressHUD.setCancelable(false);
                        updateGoalWebservice();
                    }
                    else{
                        ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                    }
                }catch (Exception ex){
                    ex.printStackTrace();
                }
            }
        }

    }

    private void applyValidationFilters() {
        mEditTextGroupGoalTarget.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValidMonetory(getActivity(), mEditTextGroupGoalTarget, mEditTextGroupGoalTarget);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
        mEditTextMemberGoalTarget.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValidMonetory(getActivity(), mEditTextMemberGoalTarget, mEditTextMemberGoalTarget);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
        mEditTextIndividualGoalTarget.addTextChangedListener(new TextWatcher() {
            @Override
            public void afterTextChanged(Editable s) {
                Validation.hasInputValidMonetory(getActivity(), mEditTextIndividualGoalTarget, mEditTextIndividualGoalTarget);
            }

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
    }
    private boolean checkValidation() {
        boolean isAllFieldCorrect = true;
        if (mGroupGoalTarget.length() == 0 || mMemberGoalTarget.length() == 0 || mIndividualGoalTarget.length() == 0 ) {
            isAllFieldCorrect = false;

            if (mGroupGoalTarget.length() == 0 ) {
                mEditTextGroupGoalTarget.requestFocus();
                Validation.hasInputValid(getActivity(), mEditTextGroupGoalTarget, mEditTextGroupGoalTarget);
            }

            if ( mMemberGoalTarget.length() == 0 ) {
                if ( mGroupGoalTarget.length() != 0 ) {
                    mEditTextMemberGoalTarget.requestFocus();
                }
                Validation.hasInputValid(getActivity(), mEditTextMemberGoalTarget, mEditTextMemberGoalTarget);
            }
            if (mIndividualGoalTarget.length() == 0) {
                if (mMemberGoalTarget.length() != 0) {
                    mEditTextIndividualGoalTarget.requestFocus();
                }
                Validation.hasInputValid(getActivity(), mEditTextIndividualGoalTarget, mEditTextIndividualGoalTarget);
            }
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_empty_requried_field));

        }else if (null!=mGroupGoalTarget && mGroupGoalTarget.length() >5 ) {
            isAllFieldCorrect = false;
            mEditTextGroupGoalTarget.requestFocus();
            mEditTextGroupGoalTarget.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.str_alert_morethan_five));
        }else if(null!=mGroupGoalTarget && mGroupGoalTarget.length() ==0 ) {
            isAllFieldCorrect = false;
            mEditTextGroupGoalTarget.requestFocus();
            mEditTextGroupGoalTarget.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_empty_requried_field));
        }else if (null!=mMemberGoalTarget && mMemberGoalTarget.length() >5 ) {
            isAllFieldCorrect = false;
            mEditTextMemberGoalTarget.requestFocus();
            mEditTextMemberGoalTarget.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.str_alert_morethan_five));
        }else if (null!=mMemberGoalTarget && mMemberGoalTarget.length() ==0 ) {
            isAllFieldCorrect = false;
            mEditTextMemberGoalTarget.requestFocus();
            mEditTextMemberGoalTarget.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_empty_requried_field));
        }else if (null!=mIndividualGoalTarget && mIndividualGoalTarget.length() >5 ) {
            isAllFieldCorrect = false;
            mEditTextIndividualGoalTarget.requestFocus();
            mEditTextIndividualGoalTarget.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.str_alert_morethan_five));
        }else if (null!=mIndividualGoalTarget && mIndividualGoalTarget.length() ==0 ) {
            isAllFieldCorrect = false;
            mEditTextIndividualGoalTarget.requestFocus();
            mEditTextIndividualGoalTarget.setBackgroundResource(R.drawable.bg_select_team_error);
            ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_empty_requried_field));
        }
        return isAllFieldCorrect;
    }

    /**
     * THis method is use to call the received selected group information
     * successful  response or  error from server
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_GOALS_ACTION_NAME, WebServiceConstants.GET_GOALS_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getGoalInfo(new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseGoalDetailsService(WebServiceUtils.getResponseString(responseModel));
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
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * parse Billing details, getting from web services
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseGoalDetailsService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);
        Gson gson = new Gson();
        GoalDetailsResponseModel get_Response = gson.fromJson(responseStr, GoalDetailsResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null) {
                    checkSubscriptionStatus(get_Response);
                }
            } else {
                //ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            //ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * parse Goal details, getting from web services
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseUpdateGoalDetailsService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);
        Gson gson = new Gson();
        GoalDetailsResponseModel get_Response = gson.fromJson(responseStr, GoalDetailsResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage() );
                if (get_Response.getResult() != null) {
                    checkSubscriptionStatus(get_Response);
                }
            } else {
                //ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            //ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    private void checkSubscriptionStatus(GoalDetailsResponseModel get_Response){
        /*mEditTextGroupGoalTarget=(EditText)view.findViewById(R.id.group_goal_target);
        mEditTextGroupGoalActual=(EditText)view.findViewById(R.id.group_goal_actual);
        mEditTextMemberGoalTarget=(EditText)view.findViewById(R.id.member_goal_target);

        mEditTextMemberGoalActual=(EditText)view.findViewById(R.id.member_goal_actual);
        mEditTextIndividualGoalTarget=(EditText)view.findViewById(R.id.individual_goal_target);
        mEditTextIndividualGoalActual=(EditText)view.findViewById(R.id.individual_goal_actual);*/
        mEditTextGroupGoalTarget.setText(get_Response.getResult().getGroup_goals().equals("") ? "0" : get_Response.getResult().getGroup_goals());
        mEditTextGroupGoalActual.setText(get_Response.getResult().getActual_group_goals().equals("") ? "0" : get_Response.getResult().getActual_group_goals() );

        mEditTextMemberGoalTarget.setText(get_Response.getResult().getGroup_member_goals().equals("") ? "0" : get_Response.getResult().getGroup_member_goals() );
        mEditTextMemberGoalActual.setText(get_Response.getResult().getActual_individual_goals().equals("") ? "0" :get_Response.getResult().getActual_individual_goals());

        mEditTextIndividualGoalTarget.setText(get_Response.getResult().getIndividual_goals().equals("") ? "0" : get_Response.getResult().getIndividual_goals());
        mEditTextIndividualGoalActual.setText(get_Response.getResult().getActual_individual_goals().equals("") ? "0" : get_Response.getResult().getActual_individual_goals());

        if(get_Response.getResult().getMember_type().equals(ApplicationConstants.MEMBER_TYPE)){
            mEditTextGroupGoalTarget.setEnabled(false);
            mEditTextMemberGoalTarget.setEnabled(false);
            mEditTextIndividualGoalTarget.setEnabled(true);
        }else{
            mEditTextGroupGoalTarget.setEnabled(true);
            mEditTextMemberGoalTarget.setEnabled(true);
            mEditTextIndividualGoalTarget.setEnabled(true);
        }

        if(null!=getResponse.getResult() && get_Response.getResult().getSet_edit_key().equals(getActivity().getString(R.string.str_set_edit_key_update))){
            ((TextView)mLlUpdateGoal.findViewById(R.id.updateText)).setText(getActivity().getString(R.string.str_update_group_goal));
        }else if(null!=getResponse.getResult() && get_Response.getResult().getSet_edit_key().equals(getActivity().getString(R.string.str_set_edit_key_set))){
            ((TextView)mLlUpdateGoal.findViewById(R.id.updateText)).setText(getActivity().getString(R.string.str_set_group_goal));
        }


    }

    /**
     * Update Goal
     */
    /**
     * THis method is use to call the received selected group information
     * successful  response or  error from server
     */
    private void updateGoalWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_GOALS_ACTION_NAME, WebServiceConstants.GET_GOALS_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.updateGoalInfo(getRequestModel(),new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseUpdateGoalDetailsService(WebServiceUtils.getResponseString(responseModel));
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
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    private GoalsRequestModel getRequestModel(){
        GoalsRequestModel mGoalsRequestModel=new GoalsRequestModel();
        mGoalsRequestModel.setMode(WebServiceConstants.EDIT_GOALS_ACTION_NAME);
        mGoalsRequestModel.setGroup_goals(null != mGroupGoalTarget && mGroupGoalTarget.length()>0 ?Integer.parseInt(mGroupGoalTarget) : 0  );
        mGoalsRequestModel.setGroup_member_goals(null != mMemberGoalTarget && mMemberGoalTarget.length()>0 ?  Integer.parseInt(mMemberGoalTarget) : 0 );
        mGoalsRequestModel.setIndividual_goals(null!=mIndividualGoalTarget && mIndividualGoalTarget.length()>0 ?Integer.parseInt(mIndividualGoalTarget) : 0);
        return mGoalsRequestModel;
    }


}
