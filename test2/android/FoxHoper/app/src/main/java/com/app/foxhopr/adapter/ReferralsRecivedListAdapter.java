package com.app.foxhopr.adapter;

import android.content.Context;
import android.graphics.Typeface;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ListClickCallBack;
import com.app.foxhopr.webservice.models.ReferralsListInnerModel;
import com.foxhoper.app.R;

import java.text.NumberFormat;
import java.util.ArrayList;


public class ReferralsRecivedListAdapter extends BaseAdapter {

	private Context mContext;
	private LayoutInflater mInflater;
	private ListClickCallBack listClickCallBack;

	private ArrayList<ReferralsListInnerModel> mDatalist;
	/**
	 * --Constructor
	 * @param context
	 */
	public ReferralsRecivedListAdapter(Context context,  ArrayList<ReferralsListInnerModel> list, ListClickCallBack mListClickCallBack) {
		mContext = context;
		mDatalist =list;
		listClickCallBack =mListClickCallBack;
		/*********** Layout inflator to call external xml layout () ***********/
		mInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
	}

	public void setData(ArrayList<ReferralsListInnerModel> list) {
		this.mDatalist = list;
	}

	@Override
	public int getCount() {
		return mDatalist.size();
	}

	@Override
	public Object getItem(int position) {
		return position;
	}

	@Override
	public long getItemId(int position) {
		return position;
	}

	public static class ViewHolder {
		public CheckBox mChckBSelect;
		public TextView mTxtvReciverName;
		public TextView mTxtvToName;
		public TextView mTxtvDate;

		public TextView mTxtvStaus;
		public TextView mTxtvValues;

		public LinearLayout mLlStatus;
		public LinearLayout mLlValues;
	}

	@Override
	public View getView(final int position, View convertView, ViewGroup parent) {
		View view = convertView;
		final ViewHolder holder;

		if (convertView == null) {

			holder = new ViewHolder();

			view = mInflater.inflate(R.layout.item_referral_recived_layout, null);

			holder.mChckBSelect	= (CheckBox) view.findViewById(R.id.checkBoxItemRecivedSelect);
			holder.mTxtvReciverName	= (TextView) view.findViewById(R.id.textViewItemRecivedName);
			holder.mTxtvToName	= (TextView) view.findViewById(R.id.textViewItemSecivedName);
			holder.mTxtvDate	= (TextView) view.findViewById(R.id.textViewItemDate);

			holder.mTxtvStaus	= (TextView) view.findViewById(R.id.textViewReferralsStatus);
			holder.mTxtvValues	= (TextView) view.findViewById(R.id.textViewReferralsValues);

			holder.mLlStatus	= (LinearLayout) view.findViewById(R.id.llListReferralsStatus);
			holder.mLlValues	= (LinearLayout) view.findViewById(R.id.llListReferralsValues);

			holder.mChckBSelect.setTag(mDatalist.get(position));
			view.setTag(holder);
		} else {
			holder = (ViewHolder) view.getTag();
			((ViewHolder) view.getTag()).mChckBSelect.setTag(mDatalist.get(position));
		}


		holder.mChckBSelect.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
			@Override
			public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
				ReferralsListInnerModel element = (ReferralsListInnerModel) holder.mChckBSelect.getTag();
				element.setSelected(buttonView.isChecked());
			}
		});

		ReferralsListInnerModel mReferralsListInnerModel 	=	mDatalist.get(position);

		String reciverNameStr ="";

		if(mReferralsListInnerModel.getFirst_name() != null && !mReferralsListInnerModel.getFirst_name().equalsIgnoreCase("")){
			reciverNameStr =mReferralsListInnerModel.getFirst_name();
			String s1 = reciverNameStr.substring(0, 1).toUpperCase();
			reciverNameStr =s1 + reciverNameStr.substring(1);
		}

		if(mReferralsListInnerModel.getLast_name() != null && !mReferralsListInnerModel.getLast_name().equalsIgnoreCase("")){
			String s1 = mReferralsListInnerModel.getLast_name().substring(0, 1).toUpperCase();
			reciverNameStr =  reciverNameStr+" "+ s1 + mReferralsListInnerModel.getLast_name().substring(1);
		}

		if(!reciverNameStr.equalsIgnoreCase("")){
			holder.mTxtvReciverName.setText(reciverNameStr);
		}else{
			holder.mTxtvReciverName.setVisibility(View.GONE);
		}

		if(null!=mReferralsListInnerModel.getIs_read() && mReferralsListInnerModel.getIs_read()){
			holder.mTxtvReciverName.setTypeface(Typeface.DEFAULT);
			//textview.setTypeface(Typeface.DEFAULT_BOLD);
		}else{
			holder.mTxtvReciverName.setTypeface(Typeface.DEFAULT_BOLD);
		}

		String toNameStr ="";

		if(mReferralsListInnerModel.getFname() != null && !mReferralsListInnerModel.getFname().equalsIgnoreCase("")){

			toNameStr =mReferralsListInnerModel.getFname();
			String s1 = toNameStr.substring(0, 1).toUpperCase();
			toNameStr = s1 + toNameStr.substring(1);

		}

		if(mReferralsListInnerModel.getLname() != null && !mReferralsListInnerModel.getLname().equalsIgnoreCase("")){
			String s1 = mReferralsListInnerModel.getLname().substring(0, 1).toUpperCase();
			toNameStr =  toNameStr+" "+ s1 + mReferralsListInnerModel.getLname().substring(1);
		}

		if(!toNameStr.equalsIgnoreCase("")){
			holder.mTxtvToName.setText(mContext.getResources().getString(R.string.str_from)+" "+toNameStr);
		}else{
			holder.mTxtvToName.setVisibility(View.GONE);
		}

		if(mReferralsListInnerModel.getCreated() != null && !mReferralsListInnerModel.getCreated().equalsIgnoreCase("")){
			if(AppUtils.parseDateToddMMyyyy(mReferralsListInnerModel.getCreated()) != null){
				holder.mTxtvDate.setText(AppUtils.parseDateToddMMyyyy(mReferralsListInnerModel.getCreated()));
			}
		}

		if(mReferralsListInnerModel.getReferral_status() != null && !mReferralsListInnerModel.getReferral_status().equalsIgnoreCase("") && !mReferralsListInnerModel.getReferral_status().equalsIgnoreCase("null")){
			String s1 = mReferralsListInnerModel.getReferral_status().substring(0, 1).toUpperCase();
			String nameCapitalized = s1 + mReferralsListInnerModel.getReferral_status().substring(1);

			holder.mTxtvStaus.setText(" "+nameCapitalized);
		}else{
			holder.mTxtvStaus.setText("N/A");
		}

		if(mReferralsListInnerModel.getMonetary_value() != null && !mReferralsListInnerModel.getMonetary_value().equalsIgnoreCase("") && !mReferralsListInnerModel.getMonetary_value().equalsIgnoreCase("null")){
			holder.mTxtvValues.setText(" $" + NumberFormat.getInstance().format(Long.parseLong(mReferralsListInnerModel.getMonetary_value())));

		}else{
			holder.mTxtvValues.setText(" $0");
		}



		holder.mChckBSelect.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				if (mDatalist.get(position).isSelected()) {
					if (!mArray.contains(mDatalist.get(position).getId()))
						mArray.add(mDatalist.get(position).getId());
				} else {
					if (mArray.contains(mDatalist.get(position).getId()))
						mArray.remove(mDatalist.get(position).getId());
				}
				checkBox(listClickCallBack);
			}
		});



		if (mDatalist.get(position).isSelected()) {
			if(!mArray.contains(mDatalist.get(position).getId()))
				mArray.add(mDatalist.get(position).getId());
		} else {
			if(mArray.contains(mDatalist.get(position).getId()))
				mArray.remove(mDatalist.get(position).getId());
		}


		holder.mChckBSelect.setChecked(mDatalist.get(position).isSelected());

		/*view.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Intent detailsReferrels = new Intent(mContext, ReferrelDetailsActivity.class);
				detailsReferrels.putExtra("referral_id", mDatalist.get(position).getId());
				mContext.startActivity(detailsReferrels);
			}
		});*/

		return view;
	}

	ArrayList<String> mArray = new ArrayList<>();
	public void checkBox( ListClickCallBack alerAction) {
		listClickCallBack.itemClickAction(mArray);
	}

	public void cleanSelectedArray(){
		mArray.clear();
	}


}
