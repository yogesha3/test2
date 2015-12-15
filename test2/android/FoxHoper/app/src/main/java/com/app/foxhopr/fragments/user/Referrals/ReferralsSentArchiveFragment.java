package com.app.foxhopr.fragments.user.Referrals;

import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.widget.SwipeRefreshLayout;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.AbsListView;
import android.widget.AbsListView.OnScrollListener;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.adapter.ReferralsSentArchiveListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.fragments.user.ReferralsFragment;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.ReceivedReferralDeleteRequestModel;
import com.app.foxhopr.request.models.ReceivedReferralsRequestModel;
import com.app.foxhopr.request.models.ReceivedReferralsSearchRequestModel;
import com.app.foxhopr.services.NotificationAlart;
import com.app.foxhopr.services.NotificationService;
import com.app.foxhopr.swipemenulist.SwipeMenu;
import com.app.foxhopr.swipemenulist.SwipeMenuCreator;
import com.app.foxhopr.swipemenulist.SwipeMenuItem;
import com.app.foxhopr.swipemenulist.SwipeMenuListView;
import com.app.foxhopr.ui.activities.SentArchiveDetailsActivity;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ListClickCallBack;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.app.foxhopr.webservice.models.ReferralsListInnerModel;
import com.app.foxhopr.webservice.models.ReferralsResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.ArrayList;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

public class ReferralsSentArchiveFragment extends Fragment implements View.OnClickListener {
	private static String TAG="ReferralsSentArchiveFragment";

	private Context mContext;
	private HorizontalScrollView mLlTopTabs;
	private LinearLayout mLlBottomTabs;


	private SwipeMenuListView mListViewSentArchive;
	private ReferralsSentArchiveListAdapter mReferralsSentArchiveListAdapter;
	private SwipeRefreshLayout swipeRefreshLayout;
	private Animation animShow, animHide;
	private Button mBtnDelete;
	private LinearLayout mLLBottomSelectTab;
	private LinearLayout mLLBottomMoreTab;

	//Footer View
	private View footerViewInProgress;
	private ProgressBar mFooterProgressBar;


	private ArrayList<ReferralsListInnerModel> listDataArray;
	private boolean isAllSelected = false;

	private TabSelectedCallBack mTabSelectedCallBack;

	private ProgressHUD mProgressHUD;

	//Page data
	private ReferralsResponseModel getResponse;

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


	public static ReferralsSentArchiveFragment newInstance(HorizontalScrollView mLlTopTabs, LinearLayout mLlBottomTabs, Button mBtnDelete,
														LinearLayout lLBottomSelectTab, LinearLayout lLBottomMoreTab, TabSelectedCallBack mTabSelectedCallBack) {
		ReferralsSentArchiveFragment mReferralsSentArchiveFragment = new ReferralsSentArchiveFragment();
		mReferralsSentArchiveFragment.mLlTopTabs = mLlTopTabs;
		mReferralsSentArchiveFragment.mLlBottomTabs = mLlBottomTabs;
		mReferralsSentArchiveFragment.mBtnDelete = mBtnDelete;
		mReferralsSentArchiveFragment.mLLBottomSelectTab = lLBottomSelectTab;
		mReferralsSentArchiveFragment.mLLBottomMoreTab = lLBottomMoreTab;
		mReferralsSentArchiveFragment.mTabSelectedCallBack = mTabSelectedCallBack;

		return mReferralsSentArchiveFragment;
	}

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

		mContext = getActivity();
		View view = inflater.inflate(R.layout.fragment_referrals_sent_archive, container, false);
		initAnimation();
		initView(view);
		return view;
	}

	private void initView(View view) {
		mPreferences = new SharedPreference();
		//setting search clear
		mPreferences.setSentSearchText(getActivity(), "");
		mPreferences.setSearrchOrder(getActivity(), R.id.dateNewest);
		listDataArray = new ArrayList<ReferralsListInnerModel>();
		mListViewSentArchive = (SwipeMenuListView) view.findViewById(R.id.listViewReferrelsSentArchive);

		mListViewSentArchive.setOnItemClickListener(ItemClick);

		mNoRecordFound=(TextView)view.findViewById(R.id.norecordfound);

		mListViewSentArchive.setOnScrollListener(onScrollListener);
		mListViewSentArchive.setOnMenuItemClickListener(onMenuItemClickListener);


		// add the footer before adding the adapter, else the footer will not load!
		footerViewInProgress = ((LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE)).inflate(R.layout.layout_list_footer_view, null, false);
		mFooterProgressBar = (ProgressBar) footerViewInProgress.findViewById(R.id.progressBarFooterList);
		mFooterProgressBar.setVisibility(View.GONE);
		mListViewSentArchive.addFooterView(footerViewInProgress);

		mBtnDelete.setOnClickListener(this);
		mLLBottomSelectTab.setOnClickListener(this);
		mLLBottomMoreTab.setOnClickListener(this);
	}

	private void initAnimation() {
		animShow = AnimationUtils.loadAnimation(getActivity(), R.anim.view_show);
		animHide = AnimationUtils.loadAnimation(getActivity(), R.anim.view_hide);
	}

	AdapterView.OnItemClickListener ItemClick=new AdapterView.OnItemClickListener() {
		@Override
		public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
			if (AppUtils.isOnline(getActivity())) {
				Intent detailsReferrels = new Intent(mContext, SentArchiveDetailsActivity.class);
				detailsReferrels.putExtra("referral_id", listDataArray.get(position).getId());
				mContext.startActivity(detailsReferrels);
			}else{
				ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
			}
		}
	};
	/**
	 * Onmenuitem click listener
	 */

	SwipeMenuListView.OnMenuItemClickListener onMenuItemClickListener=new SwipeMenuListView.OnMenuItemClickListener() {
		@Override
		public boolean onMenuItemClick(int position, SwipeMenu menu, int index) {

			switch (index){
				case 0:{
					ErrorMsgDialog.alertOkCancelButtonCallBack(getActivity(), "",getResources().getString(R.string.alert_str_archive_delete_message), new OkPressedResponse(position));
				}
			}

			return false;
		}
	};




	/**
	 * CallBack class for delete
	 */
	class OkPressedResponse implements AlertCallBack {
		private  int position;

		public OkPressedResponse(int position){
			this.position=position;
		}

		@Override
		public void alertAction(boolean select) {
			try {
				mDeletedId.clear();
				mDeletedId.add(listDataArray.get(position));
				if (AppUtils.isOnline(getActivity())) {
					mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
					mProgressHUD.setCancelable(false);
					callDeleteWebservice(mDeletedId);
				} else {
					ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
				}

			}catch (Exception e){
				e.printStackTrace();
			}

		}
	}


	/**
	 * On scroll listener use to load more data at end of list.
	 */
	OnScrollListener onScrollListener = new OnScrollListener() {

		@Override
		public void onScrollStateChanged(AbsListView view, int scrollState) {
			int threshold = 1;
			int count = mListViewSentArchive.getCount() - 1;

			/*if (scrollState == OnScrollListener.SCROLL_STATE_TOUCH_SCROLL) {
                mLlBottomTabs.setVisibility(View.GONE);
				mLlBottomTabs.startAnimation(animHide);
			}else{
				mLlBottomTabs.setVisibility(View.VISIBLE);
				mLlBottomTabs.startAnimation( animShow );
			}*/
			if (getResponse.getTotalReferrals() != null && !getResponse.getTotalReferrals().equalsIgnoreCase("") && count < Integer.parseInt(getResponse.getTotalReferrals())) {

				if (scrollState == SCROLL_STATE_IDLE && mWebserviceStatus) {

					if (mListViewSentArchive.getLastVisiblePosition() >= count - threshold) {
						pageNumber = pageNumber + 1;
						try {
							/*
							 *hecking which data is displaying (by default or search)
							 *for false by default is calling
							 *for true search is calling
							 */
							if (mStatus) {
								if (AppUtils.isOnline(getActivity())) {
									mFooterProgressBar.setVisibility(View.VISIBLE);
									mWebserviceStatus=false;
									mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
									mProgressHUD.setCancelable(false);
									callSearchWebservice(mSearchEditText.getText().toString());

								} else {
									ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
								}

							} else {
								if (AppUtils.isOnline(getActivity())) {
									mFooterProgressBar.setVisibility(View.VISIBLE);
									mWebserviceStatus=false;
									mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
									mProgressHUD.setCancelable(false);
									callWebservice();
								} else {
									ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
								}

							}
						} catch (Exception e) {
							e.printStackTrace();
						}
					}
				}
			}

		}

		@Override
		public void onScroll(AbsListView view, int firstVisibleItem, int visibleItemCount, int totalItemCount) {

		}
	};



	class OnCheckBoxSeletedResponse implements ListClickCallBack {

		@Override
		public void itemClickAction(ArrayList<String> mList) {
			try {
				Log.i("List size", "" + mList.size());

				if (mList.size() > 0) {
					mBtnDelete.setVisibility(View.VISIBLE);
				} else {
					mBtnDelete.setVisibility(View.GONE);
				}
			} catch (Exception e) {
				e.printStackTrace();
			}
		}
	}

	@Override
	public void onClick(View view) {
		if (view == mLLBottomSelectTab) {
			if (isAllSelected) {
				isAllSelected = false;
				if (listDataArray != null) {
					for (int i = 0; i < listDataArray.size(); i++) {
						listDataArray.get(i).setSelected(false);
					}
					mDeletedId.clear();
					mReferralsSentArchiveListAdapter.notifyDataSetChanged();
					mBtnDelete.setVisibility(View.GONE);
					mTabSelectedCallBack.selectAction(false);
				}

			} else {
				if (listDataArray != null) {
					isAllSelected = true;
					for (int i = 0; i < listDataArray.size(); i++) {
						listDataArray.get(i).setSelected(true);
						mDeletedId.add(listDataArray.get(i));
					}
					mReferralsSentArchiveListAdapter.notifyDataSetChanged();
					mBtnDelete.setVisibility(View.VISIBLE);
					mTabSelectedCallBack.selectAction(true);
				}

			}
		} else if (view == mLLBottomMoreTab) {
			mListViewSentArchive.setOnItemClickListener(null);
			LayoutInflater mInflater = (LayoutInflater) getActivity().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
			final View innerView = mInflater.inflate(R.layout.layout_search_referrals_sent, null, false);
			final FrameLayout searchFrameLayout = (FrameLayout) getActivity().findViewById(R.id.referralsSearchViewPager);
			searchFrameLayout.addView(innerView);
			getActivity().overridePendingTransition(0, 0);
			ReferralsFragment.mStatusSearch=true;
			//ListView searchListView=(ListView)innerView.findViewById(R.id.listSearchSortView);
			LinearLayout llCancelSearch = (LinearLayout) innerView.findViewById(R.id.llCancelSearch);
			LinearLayout llSelectSearch = (LinearLayout) innerView.findViewById(R.id.llSelectSearch);
			mSearchEditText = (EditText) innerView.findViewById(R.id.edit_search_text);
			final ImageView imageClearSearch = (ImageView) innerView.findViewById(R.id.imageClearSearch);
			//mSearchRadioButton = (RadioGroup) innerView.findViewById(R.id.radiogroupSort);
			final int lastSelected=mPreferences.getSearchOrder(getActivity());
			CheckBox defaultCheckBox=(CheckBox)innerView.findViewById(mPreferences.getSearchOrder(getActivity()));

			if(mPreferences.getSentSearchText(getActivity()).length()>0){
				imageClearSearch.setVisibility(View.VISIBLE);
			}else{
				imageClearSearch.setVisibility(View.GONE);
			}
			mSearchEditText.setText(mPreferences.getSentSearchText(getActivity()));
			defaultCheckBox.setChecked(true);


			mSearchEditText.addTextChangedListener(new TextWatcher() {

				public void beforeTextChanged(CharSequence s, int start, int count, int after) {
				}

				public void onTextChanged(CharSequence s, int start, int before, int count) {


				}

				@Override
				public void afterTextChanged(Editable s) {
					if(mSearchEditText.getText().toString().length()>0){
						imageClearSearch.setVisibility(View.VISIBLE);
					}else{
						imageClearSearch.setVisibility(View.GONE);
					}
				}
			});

			//SearchListAdapter sAdapter=new SearchListAdapter(getActivity());
			//searchListView.setAdapter(sAdapter);



			llCancelSearch.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					mListViewSentArchive.setOnItemClickListener(ItemClick);
					mPreferences.setSearrchOrder(getActivity(), lastSelected);
					ReferralsFragment.mStatusSearch=false;
					searchFrameLayout.removeAllViews();
				}
			});

			llSelectSearch.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					mListViewSentArchive.setOnItemClickListener(ItemClick);
					ReferralsFragment.mStatusSearch=false;
					mStatus = true;
					listDataArray.clear();
					pageNumber = 1;
					String searchText = ((EditText) innerView.findViewById(R.id.edit_search_text)).getText().toString();
					searchFrameLayout.removeAllViews();
					mPreferences.setSentSearchText(getActivity(), mSearchEditText.getText().toString());
					//mPreferences.setSearrchOrder(getActivity(), mSearchRadioButton.getCheckedRadioButtonId());

					try {
						setListAdapter();
						if (AppUtils.isOnline(getActivity())) {
							mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
							mProgressHUD.setCancelable(false);
							callSearchWebservice(mSearchEditText.getText().toString());

						} else {
							ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
						}
					} catch (Exception e) {
						e.printStackTrace();
					}
				}
			});

			imageClearSearch.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					mPreferences.setSentSearchText(getActivity(), mSearchEditText.getText().toString());
					((EditText) innerView.findViewById(R.id.edit_search_text)).setText("");
					//((RadioGroup)innerView.findViewById(R.id.radiogroupSort)).clearCheck();
				}
			});
		}else if(view == mBtnDelete){
			ErrorMsgDialog.alertOkCancelButtonCallBack(getActivity(), "",getResources().getString(R.string.alert_str_archive_delete_message), new OkPressedResponseAll());
		}
	}

	/**
	 * CallBack class for delete
	 */
	class OkPressedResponseAll implements AlertCallBack {

		@Override
		public void alertAction(boolean select) {
			mDeletedId.clear();
			for (ReferralsListInnerModel checkedRow: listDataArray){
				if(checkedRow.isSelected()){
					mDeletedId.add(checkedRow);
				}
			}
			if (AppUtils.isOnline(getActivity())) {
				try {
					if (AppUtils.isOnline(getActivity())) {
						mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
						mProgressHUD.setCancelable(false);
						callDeleteWebservice(mDeletedId);
					} else {
						ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
					}
				}catch (Exception e){
					e.printStackTrace();
				}

			} else {
				ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
			}

		}
	}



	/**
	 * THis method is use to call the webservice and get
	 * The response and error from server
	 */
	private void callWebservice() throws Exception {
		//setting timeout for reading data as well as for connecting with client

		RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.REFERRALS_ACTION_NAME, WebServiceConstants.REFERRALS_CONTROL_NAME, getActivity())).build();
		RequestApi requestApi = restAdapter.create(RequestApi.class);

		requestApi.getReferralsListRequest(getRequestModel(), new Callback<Response>() {
			@Override
			public void success(Response responseModel, Response response) {
				// Try to get response body
				mWebserviceStatus = true;

				mProgressHUD.dismiss();
				mFooterProgressBar.setVisibility(View.GONE);
				mBtnDelete.setVisibility(View.GONE);
				mTabSelectedCallBack.selectAction(false);
				try {
					if (responseModel != null) {
						parseReferralsListService(WebServiceUtils.getResponseString(responseModel));
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

				mFooterProgressBar.setVisibility(View.GONE);
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
	private void callSearchWebservice(String searchText) throws Exception {
		RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.REFERRALS_ACTION_NAME, WebServiceConstants.REFERRALS_CONTROL_NAME, getActivity())).build();
		RequestApi requestApi = restAdapter.create(RequestApi.class);

		requestApi.getReferralsListSearchRequest(getSearchRequestModel(searchText), new Callback<Response>() {
			@Override
			public void success(Response responseModel, Response response) {
				// Try to get response body
				mWebserviceStatus = true;
				mProgressHUD.dismiss();
				mFooterProgressBar.setVisibility(View.GONE);
				mBtnDelete.setVisibility(View.GONE);
				mTabSelectedCallBack.selectAction(false);
				try {
					if (responseModel != null) {
						parseReferralsListService(WebServiceUtils.getResponseString(responseModel));
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

				mFooterProgressBar.setVisibility(View.GONE);
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
	private void callDeleteWebservice(final ArrayList<ReferralsListInnerModel> mDeletedIds) throws Exception {
		RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.DELETE_ACTION_NAME, WebServiceConstants.DELETE_CONTROL_NAME, getActivity())).build();
		RequestApi requestApi = restAdapter.create(RequestApi.class);

		requestApi.deleteReferralsList(getDeleteRequestModel(mDeletedId), new Callback<Response>() {
			@Override
			public void success(Response responseModel, Response response) {
				// Try to get response body
				mProgressHUD.dismiss();
				mFooterProgressBar.setVisibility(View.GONE);
				mBtnDelete.setVisibility(View.GONE);
				mTabSelectedCallBack.selectAction(false);
				try {
					if (responseModel != null) {
						ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.str_sccess_archive_delete_message), new DeleteOkPressed());
						//deleteReferralsListService(mDeletedIds, WebServiceUtils.getResponseString(responseModel));
						mReferralsSentArchiveListAdapter.cleanSelectedArray();
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
				mBtnDelete.setVisibility(View.GONE);
				mFooterProgressBar.setVisibility(View.GONE);
				mProgressHUD.dismiss();

				ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
			}
		});
	}

	public class DeleteOkPressed implements AlertCallBack{

		@Override
		public void alertAction(boolean select) {
			listDataArray.clear();
			pageNumber=1;
			try {
				if (AppUtils.isOnline(getActivity())) {
					mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
					mProgressHUD.setCancelable(false);
					if(mStatus) {
						callSearchWebservice(mPreferences.getSentSearchText(getActivity()));
					}else{
						callWebservice();
					}
				} else {
					ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
				}
			} catch (Exception ex) {
				ex.printStackTrace();
			}
		}
	}

	/**
	 * This method is use for get the request model
	 *
	 * @return
	 */
	private ReceivedReferralsRequestModel getRequestModel() throws Exception {
		ReceivedReferralsRequestModel mReceivedReferralsRequestModel = new ReceivedReferralsRequestModel();
		mReceivedReferralsRequestModel.setPage_no("" + pageNumber);
		mReceivedReferralsRequestModel.setRecord_per_page("" + recordPerPage);
		mReceivedReferralsRequestModel.setList_page(WebServiceConstants.SENT_ARCHIVE_LIST_PAGE);
		mReceivedReferralsRequestModel.setSender_name(senderNameStr);
		mReceivedReferralsRequestModel.setReferral_name(referralNameStr);
		mReceivedReferralsRequestModel.setStatus(statusStr);
		//Log.i("Request Data", mReceivedReferralsRequestModel.toString());
		return mReceivedReferralsRequestModel;
	}

	/**
	 * This method is use for get the request model
	 *
	 * @return
	 */
	private ReceivedReferralsSearchRequestModel getSearchRequestModel(String searchText) throws Exception {
		ReceivedReferralsSearchRequestModel mReceivedReferralsSearchRequestModel = new ReceivedReferralsSearchRequestModel();
		mReceivedReferralsSearchRequestModel.setPage_no("" + pageNumber);
		mReceivedReferralsSearchRequestModel.setRecord_per_page("" + recordPerPage);
		mReceivedReferralsSearchRequestModel.setList_page(WebServiceConstants.SENT_ARCHIVE_LIST_PAGE);
		mReceivedReferralsSearchRequestModel.setReferral_name(searchText);
		Log.i("Referrals", AppUtils.getRadioData(getActivity()));
		Log.i("Referrals", AppUtils.getRadioDataASCOrDESC(getActivity()));
		mReceivedReferralsSearchRequestModel.setSort_data(AppUtils.getRadioData(getActivity()));
		mReceivedReferralsSearchRequestModel.setSort_direction(AppUtils.getRadioDataASCOrDESC(getActivity()));


		return mReceivedReferralsSearchRequestModel;
	}

	/**
	 * This method is use for get the request model
	 *
	 * @return
	 */
	private ReceivedReferralDeleteRequestModel getDeleteRequestModel(ArrayList<ReferralsListInnerModel> mDeletedIds) throws Exception {
		ReceivedReferralDeleteRequestModel mReceivedReferralsDeleteRequestModel = new ReceivedReferralDeleteRequestModel();
		mReceivedReferralsDeleteRequestModel.setListPage(WebServiceConstants.SENT_ARCHIVE_LIST_PAGE);
		String ids="";
		for(ReferralsListInnerModel s:mDeletedIds){
			if(ids.length()==0){
				ids=s.getId();
			}else{
				ids=ids + "," +s.getId();
			}

		}
		Log.i("ids", ids);
		mReceivedReferralsDeleteRequestModel.setDeleteId(ids);
		return mReceivedReferralsDeleteRequestModel;
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
		ReferralsResponseModel get_Response = gson.fromJson(responseStr, ReferralsResponseModel.class);

		if (get_Response != null) {
			getResponse = get_Response;

			if (get_Response.getReponseCode().equals(ApplicationConstants.SUCCESS_CODE)) {
				if (get_Response.getListData != null && get_Response.getListData.size() > 0) {
					mListViewSentArchive.setVisibility(View.VISIBLE);
					mNoRecordFound.setVisibility(View.GONE);
					listDataArray.addAll(get_Response.getListData);
					if (mReferralsSentArchiveListAdapter != null) {
						mReferralsSentArchiveListAdapter.setData(listDataArray);
						mReferralsSentArchiveListAdapter.notifyDataSetChanged();
					} else {
						setListAdapter();
					}
				}
			} else {
				//ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
				listDataArray.clear();
				mListViewSentArchive.setVisibility(View.GONE);
				mNoRecordFound.setVisibility(View.VISIBLE);
				if(mStatus/*mPreferences.getSearchText(getActivity()).length()>0 && mPreferences.getSearchOrder(getActivity())==R.id.dateNewest*/){
					mNoRecordFound.setText(getActivity().getResources().getString(R.string.wrng_str_no_result));
				}else{
					mNoRecordFound.setText(getActivity().getResources().getString(R.string.wrng_str_no_record));
				}
			}
		} else {
			ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
		}
	}

	/**
	 * deleteReferralsListService
	 *
	 * @param responseStr
	 * @throws Exception
	 */

	private void deleteReferralsListService(ArrayList<ReferralsListInnerModel> mDeletedId,String responseStr) throws Exception {
		Log.e(TAG, responseStr);

		Gson gson = new Gson();
		ReferralsResponseModel get_Response = gson.fromJson(responseStr, ReferralsResponseModel.class);

		if (get_Response != null) {
			getResponse = get_Response;

			if (get_Response.getReponseCode().equals(ApplicationConstants.SUCCESS_CODE)) {
				for(ReferralsListInnerModel deletedReferrel: mDeletedId){
					if(listDataArray.contains(deletedReferrel)){
						listDataArray.remove(deletedReferrel);
					}
				}
				//listDataArray.addAll(get_Response.getListData);
				if (mReferralsSentArchiveListAdapter != null) {
					mReferralsSentArchiveListAdapter.setData(listDataArray);
					mReferralsSentArchiveListAdapter.notifyDataSetChanged();
				}
			} else {
				ErrorMsgDialog.showErrorAlert(getActivity(), "", get_Response.getMessage().equals("") ? getString(R.string.wrng_str_no_result) : get_Response.getMessage());
			}
		} else {
			ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
		}
	}

	/**
	 * Method use for set the data on List
	 *
	 * @throws Exception
	 */

	private void setListAdapter() throws Exception {
		mReferralsSentArchiveListAdapter = new ReferralsSentArchiveListAdapter(getActivity(), listDataArray, new OnCheckBoxSeletedResponse());
		// set our adapter and pass our recived list content
		mListViewSentArchive.setAdapter(mReferralsSentArchiveListAdapter);

		SwipeMenuCreator creator = new SwipeMenuCreator() {

			@Override
			public void create(SwipeMenu menu) {
				//create an action that will be showed on swiping an item in the list
				SwipeMenuItem item1 = new SwipeMenuItem(getActivity());
				//Add the list view header
				View viewHeader = LayoutInflater.from(getActivity()).inflate(R.layout.layout_swip_view, null);

				item1.setBackground(new ColorDrawable(Color.rgb(0xE4, 0x56,
						0x2B)));
				// set width of an option (px)
				item1.setWidth(200);
				item1.setTitle(getActivity().getResources().getString(R.string.str_delete));
				item1.setTitleSize(14);
				item1.setIcon(R.drawable.ic_delete);
				item1.setTitleColor(Color.WHITE);
				menu.addMenuItem(item1);
			}
		};
		//set MenuCreator
		mListViewSentArchive.setMenuCreator(creator);

		mListViewSentArchive.setOnSwipeListener(new SwipeMenuListView.OnSwipeListener() {

			@Override
			public void onSwipeStart(int position) {
				// swipe start
				//Log.i("Clicked", position + "");
			}

			@Override
			public void onSwipeEnd(int position) {
				// swipe end
				//Log.i("Clicked", position + "");
			}
		});
		//mListView.setSwipeDirection(SwipeMenuListView.DIRECTION_LEFT);
	}

	@Override
	public void onCreateOptionsMenu(Menu menu, MenuInflater inflater) {
		getActivity().getMenuInflater().inflate(R.menu.menu_main, menu);
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		int id = item.getItemId();

		if (id == R.id.action_left) {
			mListViewSentArchive.setSwipeDirection(SwipeMenuListView.DIRECTION_LEFT);
			return true;
		}
		if (id == R.id.action_right) {
			mListViewSentArchive.setSwipeDirection(SwipeMenuListView.DIRECTION_RIGHT);
			return true;
		}

		return super.onOptionsItemSelected(item);
	}

	@Override
	public void onResume() {
		super.onResume();
		if(!AppUtils.isMyServiceRunning(NotificationService.class,getActivity())){
			NotificationAlart.start(getActivity());
		}
		listDataArray.clear();
		try {
			setListAdapter();
		}catch (Exception e){
			e.printStackTrace();
		}
		pageNumber=1;
		if (mStatus) {
			if (AppUtils.isOnline(getActivity())) {
				//mFooterProgressBar.setVisibility(View.VISIBLE);
				mWebserviceStatus=false;
				mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
				mProgressHUD.setCancelable(false);
				try {
					callSearchWebservice(mSearchEditText.getText().toString());
				}catch (Exception e){
					e.printStackTrace();
				}
			} else {
				ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
			}

		} else {
			if (AppUtils.isOnline(getActivity())) {
				// mFooterProgressBar.setVisibility(View.VISIBLE);
				mWebserviceStatus=false;
				mProgressHUD = ProgressHUD.show(getActivity(), "", true, true);
				mProgressHUD.setCancelable(false);
				try {
					callWebservice();
				}catch (Exception e){
					e.printStackTrace();
				}
			} else {
				ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
			}

		}
	}

	@Override
	public void onPause() {
		super.onPause();
		//NotificationAlart.stop(getActivity());
	}
}
