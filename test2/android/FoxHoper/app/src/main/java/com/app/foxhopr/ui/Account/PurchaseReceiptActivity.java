package com.app.foxhopr.ui.Account;

import android.content.Context;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.adapter.PurchaseReceiptAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.swipemenulist.SwipeMenuListView;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.webservice.models.PurchaseReceiptResponseModel;
import com.app.foxhopr.webservice.models.ReceiptDetailModel;
import com.app.foxhopr.webservice.models.ReferralsListInnerModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.ArrayList;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class PurchaseReceiptActivity extends FragmentActivity implements View.OnClickListener {

    private static String TAG="PurchaseReceiptActivity";
    private PurchaseReceiptAdapter mPurchaseReceiptAdapter;
    private SharedPreference mPreference;
    private ImageView mBackButton;
    private TextView mTxtvSendReferrals;
    private SwipeMenuListView mListViewReceived;

    //Footer View
    private View footerViewInProgress;
    private ProgressBar mFooterProgressBar;


    private ArrayList<ReceiptDetailModel> listDataArray;
    private boolean isAllSelected = false;

    private ProgressHUD mProgressHUD;

    //Page data
    private PurchaseReceiptResponseModel getResponse;

    //Paging
    private String pageNo = "";
    private int recordPerPage = ApplicationConstants.RECORD_PER_PAGE;
    private String senderNameStr = "";
    private String statusStr = "";
    private String referralNameStr = "";

    private int pageNumber = 1;

    //for search order
    private SharedPreference mPreferences;
    //private RadioGroup mSearchRadioButton;

    private EditText mSearchEditText;
    private ArrayList<ReferralsListInnerModel> mDeletedId=new ArrayList<ReferralsListInnerModel>();

    //variable storing state of search or simple by default referal
    private boolean mStatus = false;
    private TextView mNoRecordFound;
    private boolean mWebserviceStatus = true;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_purchase_receipt);
        initView();
    }

    private void initView(){
        mPreference=new SharedPreference();
        listDataArray = new ArrayList<ReceiptDetailModel>();
        mBackButton=(ImageView)findViewById(R.id.imgvDrawer);
        mBackButton.setOnClickListener(this);

        mTxtvSendReferrals	=	(TextView)findViewById(R.id.textViewHeaderTitle);
        mTxtvSendReferrals.setVisibility(View.VISIBLE);
        mTxtvSendReferrals.setText(getResources().getString(R.string.str_purchase_receipt_title));
        mListViewReceived = (SwipeMenuListView) findViewById(R.id.listViewReceipt);

        mNoRecordFound=(TextView)findViewById(R.id.norecordfound);



        // add the footer before adding the adapter, else the footer will not load!
        footerViewInProgress = ((LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE)).inflate(R.layout.layout_list_footer_view, null, false);
        mFooterProgressBar = (ProgressBar) footerViewInProgress.findViewById(R.id.progressBarFooterList);
        mFooterProgressBar.setVisibility(View.GONE);
        mListViewReceived.addFooterView(footerViewInProgress);

        /*mListViewReceived.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(listDataArray.get(position).getPdfUrl()));
                startActivity(browserIntent);
            }
        });
*/
        if (AppUtils.isOnline(PurchaseReceiptActivity.this)) {
            // mFooterProgressBar.setVisibility(View.VISIBLE);
            mWebserviceStatus=false;
            mProgressHUD = ProgressHUD.show(PurchaseReceiptActivity.this, "", true, true);
            mProgressHUD.setCancelable(false);
            try {
                callWebservice();
            }catch (Exception e){
                e.printStackTrace();
            }
        } else {
            ErrorMsgDialog.showErrorAlert(PurchaseReceiptActivity.this, "", getString(R.string.wrng_str_lost_internet_error));
        }
    }
    /**
     * THis method is use to call the webservice and get
     * The response and error from server
     */
    private void callWebservice() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_RECEIPTS_ACTION_NAME, WebServiceConstants.GET_RECEIPTS_CONTROL_NAME, PurchaseReceiptActivity.this)).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getPurchaseReceipt(new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mWebserviceStatus = true;

                mProgressHUD.dismiss();
                mFooterProgressBar.setVisibility(View.GONE);
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseReferralsListService(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(PurchaseReceiptActivity.this, "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();

                mFooterProgressBar.setVisibility(View.GONE);
                mProgressHUD.dismiss();
                mWebserviceStatus = true;
                ErrorMsgDialog.showErrorAlert(PurchaseReceiptActivity.this, "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * parseReferralsListService
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseReferralsListService(String responseStr) throws Exception {
        Log.e(TAG, responseStr);

        Gson gson = new Gson();
        PurchaseReceiptResponseModel get_Response = gson.fromJson(responseStr, PurchaseReceiptResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null && get_Response.getResult().size() > 0) {
                    mListViewReceived.setVisibility(View.VISIBLE);
                    mNoRecordFound.setVisibility(View.GONE);
                    listDataArray.addAll(get_Response.getResult());
                    if (mPurchaseReceiptAdapter != null) {
                        mPurchaseReceiptAdapter.setData(listDataArray);
                        mPurchaseReceiptAdapter.notifyDataSetChanged();
                    } else {
                        setListAdapter();
                    }
                }
            } else {
                //ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
                listDataArray.clear();
                mListViewReceived.setVisibility(View.GONE);
                mNoRecordFound.setVisibility(View.VISIBLE);
                if(mStatus/*mPreferences.getSearchText(getActivity()).length()>0 && mPreferences.getSearchOrder(getActivity())==R.id.dateNewest*/){
                    mNoRecordFound.setText(getResources().getString(R.string.wrng_str_no_result));
                }else{
                    mNoRecordFound.setText(getResources().getString(R.string.wrng_str_no_record));
                }
            }
        } else {
            ErrorMsgDialog.showErrorAlert(PurchaseReceiptActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * Method use for set the data on List
     *
     * @throws Exception
     */

    private void setListAdapter() throws Exception {
        mPurchaseReceiptAdapter = new PurchaseReceiptAdapter(PurchaseReceiptActivity.this, listDataArray);
        // set our adapter and pass our recived list content
        mListViewReceived.setAdapter(mPurchaseReceiptAdapter);
    }

    @Override
    public void onClick(View view) {
        if(view==mBackButton){
            finish();
        }

    }




}
