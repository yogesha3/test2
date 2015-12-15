/**
 * Fragment display two types of billing local and global
 * user can cancel or upgrade or downgrade group selection
 */
package com.app.foxhopr.fragments.user.Account;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.widget.SwipeRefreshLayout;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.Animation;
import android.widget.Button;
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.adapter.ReferralsRecivedListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.swipemenulist.SwipeMenuListView;
import com.app.foxhopr.ui.Account.CreditCardActivity;
import com.app.foxhopr.ui.Account.PurchaseReceiptActivity;
import com.app.foxhopr.ui.GroupSelection.GroupSelectionActivity;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.app.foxhopr.webservice.models.BillingDetailsResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class BillingFragment extends Fragment implements View.OnClickListener {
    private static String TAG="BillingFragment";

    private Context mContext;
    private HorizontalScrollView mLlTopTabs;
    private LinearLayout mLlBottomTabs;
    BillingDetailsResponseModel getResponse;

    private ProgressHUD mProgressHUD;
    private SharedPreference mPreferences;

    private SwipeMenuListView mListViewReceived;
    private ReferralsRecivedListAdapter mReferralsRecivedListAdapter;
    private SwipeRefreshLayout swipeRefreshLayout;
    private Animation animShow, animHide;
    private Button mBtnDelete;
    private LinearLayout mLLBottomSelectTab;
    private LinearLayout mLLBottomMoreTab;
    private TabSelectedCallBack mTabSelectedCallBack;

    private LinearLayout llLocal;
    private TextView localamount;
    private LinearLayout localCancel;
    private LinearLayout localCreditCard;
    private LinearLayout localReceipt;

    private LinearLayout llGlobal;
    private TextView globalamount;
    private LinearLayout globalCancel;
    private LinearLayout globalCreditCard;
    private LinearLayout globalReceipt;

    public static BillingFragment newInstance(HorizontalScrollView mLlTopTabs, LinearLayout mLlBottomTabs, Button mBtnDelete,
                                                        LinearLayout lLBottomSelectTab, LinearLayout lLBottomMoreTab, TabSelectedCallBack mTabSelectedCallBack) {
        BillingFragment mReferralsReceivedFragment = new BillingFragment();
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
        View view = inflater.inflate(R.layout.fragment_billing, container, false);
        initView(view);
        return view;
    }

    private void initView(View view) {
        mPreferences = new SharedPreference();

        llLocal=(LinearLayout)view.findViewById(R.id.local_layout);
        localamount=(TextView)llLocal.findViewById(R.id.billing_amountValue);
        localCancel=(LinearLayout)llLocal.findViewById(R.id.llcancel);
        localCreditCard=(LinearLayout)llLocal.findViewById(R.id.llcreditCard);
        localReceipt=(LinearLayout)llLocal.findViewById(R.id.llreceipt);

        localCancel.setOnClickListener(this);
        localCreditCard.setOnClickListener(this);
        localReceipt.setOnClickListener(this);


        llGlobal=(LinearLayout)view.findViewById(R.id.global_layout);

        globalamount=(TextView)llGlobal.findViewById(R.id.billing_amountValue);
        globalCancel=(LinearLayout)llGlobal.findViewById(R.id.llcancel);
        globalCreditCard=(LinearLayout)llGlobal.findViewById(R.id.llcreditCard);
        globalReceipt=(LinearLayout)llGlobal.findViewById(R.id.llreceipt);

        globalCancel.setOnClickListener(this);
        globalCreditCard.setOnClickListener(this);
        globalReceipt.setOnClickListener(this);


        //Call the webservices
        //new OkPressedResponse().alertAction(true);


    }

    @Override
    public void onResume() {
        super.onResume();
        new OkPressedResponse().alertAction(true);
    }

    /**
     * THis method is use to call the received selected group information
     * successful  response or  error from server
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_BILLING_ACTION_NAME, WebServiceConstants.GET_BILLING_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getBillingInfo(new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseBillingDetailsService(WebServiceUtils.getResponseString(responseModel));
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
     * parse cancel membership , getting from web services
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseCancelMembership(String responseStr) throws Exception {
        Log.e(TAG, responseStr);
        Gson gson = new Gson();
        BillingDetailsResponseModel get_Response = gson.fromJson(responseStr, BillingDetailsResponseModel.class);

        if (get_Response != null) {
            //getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                    ErrorMsgDialog.alertOkButtonCallBackNoChange(getActivity(), "", get_Response.getMessage(), new OkPressedResponse());
            } else {
                //ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            //ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
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

    /**
     * parse Billing details, getting from web services
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseBillingDetailsService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);
        Gson gson = new Gson();
        BillingDetailsResponseModel get_Response = gson.fromJson(responseStr, BillingDetailsResponseModel.class);

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
     * checking whether un subscription has been done or not
     * true not done/ false done
     * @param get_Response
     */
    private void checkSubscriptionStatus(BillingDetailsResponseModel get_Response){
        if(get_Response.getResult().getSubscription_status()){
            setValuesONUI(get_Response);
            if(get_Response.getResult().getGroup_type().equals(ApplicationConstants.GROUP_LOCAL)){
                localCancel.setContentDescription(getString(R.string.str_billing_cancel_text));
                globalCancel.setContentDescription(getString(R.string.str_billing_upgrade_text));
                globalCreditCard.setAlpha(.5f);
                globalReceipt.setAlpha(.5f);
                globalCreditCard.setContentDescription("");
                globalReceipt.setContentDescription("");
                localCreditCard.setContentDescription(getString(R.string.str_billing_credit_card_text));
                localReceipt.setContentDescription(getString(R.string.str_billing_receipts_text));
                if(!get_Response.getResult().getLast_updated()){
                    globalCancel.setContentDescription("");
                    globalCancel.setAlpha(.5f);
                }
            }else if(get_Response.getResult().getGroup_type().equals(ApplicationConstants.GROUP_GLOBAL)) {
                localCancel.setContentDescription(getString(R.string.str_billing_downgrade_text));
                globalCancel.setContentDescription(getString(R.string.str_billing_cancel_text));
                localCreditCard.setAlpha(.5f);
                localReceipt.setAlpha(.5f);
                localCreditCard.setContentDescription("");
                localReceipt.setContentDescription("");
                globalCreditCard.setContentDescription(getString(R.string.str_billing_credit_card_text));
                globalReceipt.setContentDescription(getString(R.string.str_billing_receipts_text));
                if(!get_Response.getResult().getLast_updated()){
                    localCancel.setContentDescription("");
                    localCancel.setAlpha(.5f);
                }
            }
        }else{
            if(get_Response.getResult().getGroup_type().equals(ApplicationConstants.GROUP_LOCAL)){
                localCancel.setAlpha(.5f);
                globalCreditCard.setAlpha(.5f);
                globalReceipt.setAlpha(.5f);
                globalCancel.setAlpha(.5f);
                globalCreditCard.setContentDescription("");
                globalReceipt.setContentDescription("");
                localCreditCard.setContentDescription(getString(R.string.str_billing_credit_card_text));
                localReceipt.setContentDescription(getString(R.string.str_billing_receipts_text));
                //localCancel.setContentDescription(getString(R.string.str_billing_cancel_text));
                //globalCancel.setContentDescription(getString(R.string.str_billing_upgrade_text));
            }else if(get_Response.getResult().getGroup_type().equals(ApplicationConstants.GROUP_GLOBAL)){
                globalCancel.setAlpha(.5f);
                localCreditCard.setAlpha(.5f);
                localReceipt.setAlpha(.5f);
                localCancel.setAlpha(.5f);
                localCreditCard.setContentDescription("");
                localReceipt.setContentDescription("");
                globalCreditCard.setContentDescription(getString(R.string.str_billing_credit_card_text));
                globalReceipt.setContentDescription(getString(R.string.str_billing_receipts_text));
            }

            localCancel.setContentDescription("");
            globalCancel.setContentDescription("");
            setValuesONUI(get_Response);
        }

    }

    private void setValuesONUI(BillingDetailsResponseModel get_Response){
        localamount.setText(get_Response.getResult().getAmount_local());
        globalamount.setText(get_Response.getResult().getAmount_global());
        if(get_Response.getResult().getGroup_type().equals(ApplicationConstants.GROUP_LOCAL)){
            /**
             * Setting cancel button active with exact text with
             */
            ((ImageView) localCancel.findViewById(R.id.cancel_button)).setImageResource(R.drawable.icon_close);
            ((TextView) localCancel.findViewById(R.id.cancel_text)).setText(getString(R.string.str_billing_cancel_text));
            //localCancel.setContentDescription(getString(R.string.str_billing_cancel_text));

            ((ImageView) globalCancel.findViewById(R.id.cancel_button)).setImageResource(R.drawable.icon_upgrade);
            ((TextView) globalCancel.findViewById(R.id.cancel_text)).setText(getString(R.string.str_billing_upgrade_text));
            //globalCancel.setContentDescription(getString(R.string.str_billing_upgrade_text));

        }else if(get_Response.getResult().getGroup_type().equals(ApplicationConstants.GROUP_GLOBAL)){

            ((ImageView) localCancel.findViewById(R.id.cancel_button)).setImageResource(R.drawable.icon_downgrade);
            ((TextView) localCancel.findViewById(R.id.cancel_text)).setText(getString(R.string.str_billing_downgrade_text));
            //localCancel.setContentDescription(getString(R.string.str_billing_downgrade_text));

            ((ImageView) globalCancel.findViewById(R.id.cancel_button)).setImageResource(R.drawable.icon_close);
            ((TextView) globalCancel.findViewById(R.id.cancel_text)).setText(getString(R.string.str_billing_cancel_text));
            //globalCancel.setContentDescription(getString(R.string.str_billing_cancel_text));
        }
    }

    /**
     * THis method is use to call the received selected group information
     * successful  response or  error from server
     */
    private void callCancelMemberShip() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_BILLING_CANCEL_ACTION_NAME, WebServiceConstants.GET_BILLING_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getBillingInfo(new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseCancelMembership(WebServiceUtils.getResponseString(responseModel));
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


    @Override
    public void onClick(View view) {
        if(view==localCancel){
            if(localCancel.getContentDescription().equals(getString(R.string.str_billing_cancel_text))){
                //cancelMembership();
                ErrorMsgDialog.alertOkCancelButtonCallBack(getActivity(), "",getResources().getString(R.string.str_alert_cancel_billing), new OnItemClick());
            }else if(localCancel.getContentDescription().equals(getString(R.string.str_billing_downgrade_text))){
                moveToGroupSelectionActivity(getString(R.string.str_billing_downgrade_text));
            }
        }else if(view==globalCancel){
            if(globalCancel.getContentDescription().equals(getString(R.string.str_billing_cancel_text))){
                ErrorMsgDialog.alertOkCancelButtonCallBack(getActivity(), "", getResources().getString(R.string.str_alert_cancel_billing), new OnItemClick());
                //cancelMembership();
            }else if(globalCancel.getContentDescription().equals(getString(R.string.str_billing_upgrade_text))){
                moveToGroupSelectionActivity(getString(R.string.str_billing_upgrade_text));
            }
        }else if(view==localCreditCard){
            if(localCreditCard.getContentDescription().equals(getString(R.string.str_billing_credit_card_text))){
                //Toast.makeText(getActivity(),getString(R.string.str_billing_credit_card_text),Toast.LENGTH_SHORT).show();
                Intent creditCard=new Intent(getActivity(), CreditCardActivity.class);
                creditCard.putExtra(ApplicationConstants.CREDITCARD,getResponse.getResult().getCredit_card_number());
                startActivity(creditCard);
            }

        }else if(view==localReceipt){
            if(localReceipt.getContentDescription().equals(getString(R.string.str_billing_receipts_text))){
                //Toast.makeText(getActivity(),getString(R.string.str_billing_receipts_text),Toast.LENGTH_SHORT).show();
                Intent purchaseReceipt=new Intent(getActivity(), PurchaseReceiptActivity.class);
                startActivity(purchaseReceipt);
            }
        }else if(view==globalCreditCard){
            if(globalCreditCard.getContentDescription().equals(getString(R.string.str_billing_credit_card_text))){
                //Toast.makeText(getActivity(),getString(R.string.str_billing_credit_card_text),Toast.LENGTH_SHORT).show();
                Intent creditCard=new Intent(getActivity(), CreditCardActivity.class);
                creditCard.putExtra(ApplicationConstants.CREDITCARD, getResponse.getResult().getCredit_card_number());
                startActivity(creditCard);
            }

        }else if(view==globalReceipt){
            if(globalReceipt.getContentDescription().equals(getString(R.string.str_billing_receipts_text))){
                //Toast.makeText(getActivity(),getString(R.string.str_billing_receipts_text),Toast.LENGTH_SHORT).show();
                Intent purchaseReceipt=new Intent(getActivity(), PurchaseReceiptActivity.class);
                startActivity(purchaseReceipt);
            }
        }

    }

    /**
     * CallBack class for cancel membership
     */
    class OnItemClick implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            // Call web service here
            //Call the webservices
            try{
                if (AppUtils.isOnline(getActivity())) {
                    mProgressHUD = ProgressHUD.show(getActivity(),"", true,true);
                    mProgressHUD.setCancelable(false);
                    callCancelMemberShip();
                }
                else{
                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                }
            }catch (Exception ex){
                ex.printStackTrace();
            }
        }
    }

    /**
     * Method use to navigate the Group selection screen
     */
    private void moveToGroupSelectionActivity(String status) {
        Intent intObj = new Intent(getActivity(), GroupSelectionActivity.class);
        intObj.putExtra(ApplicationConstants.MEMBER_STATUS,status);
        startActivity(intObj);
        getActivity().overridePendingTransition(R.anim.slide_in_left, R.anim.slide_out_right);
    }

}
