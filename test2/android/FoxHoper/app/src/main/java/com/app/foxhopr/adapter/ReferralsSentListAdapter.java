/**
 * SwipeListView Adapter class
 *
 */
package com.app.foxhopr.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.TextView;

import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ListClickCallBack;
import com.app.foxhopr.webservice.models.ReferralsListInnerModel;
import com.foxhoper.app.R;

import java.util.ArrayList;


public class ReferralsSentListAdapter extends BaseAdapter {

	private Context mContext;
	private LayoutInflater mInflater;
	private ListClickCallBack listClickCallBack;

	private ArrayList<ReferralsListInnerModel> mDatalist;
	/**
	 * --Constructor
	 * @param context
	 */
	public ReferralsSentListAdapter(Context context, ArrayList<ReferralsListInnerModel> list, ListClickCallBack mListClickCallBack) {
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

	/**
	 * Holder class for row
	 */
	public static class ViewHolder {
		public CheckBox mChckBSelect;
		public TextView mTxtvReciverName;
		public TextView mTxtvToName;
		public TextView mTxtvDate;

	}

	@Override
	public View getView(final int position, View convertView, ViewGroup parent) {
		View view = convertView;
		final ViewHolder holder;

		if (convertView == null) {

			holder = new ViewHolder();

			view = mInflater.inflate(R.layout.item_referral_sent_layout, null);

			holder.mChckBSelect	= (CheckBox) view.findViewById(R.id.checkBoxItemSentSelect);
			holder.mTxtvReciverName	= (TextView) view.findViewById(R.id.textViewItemSentName);
			holder.mTxtvToName	= (TextView) view.findViewById(R.id.textViewItemSecivedName);
			holder.mTxtvDate	= (TextView) view.findViewById(R.id.textViewItemDate);

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
			holder.mTxtvToName.setText(mContext.getResources().getString(R.string.str_to)+" "+toNameStr);
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
		}

		holder.mChckBSelect.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				if (mDatalist.get(position).isSelected()) {
					if(!mArray.contains(mDatalist.get(position).getId()))
						mArray.add(mDatalist.get(position).getId());
				} else {
					if(mArray.contains(mDatalist.get(position).getId()))
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
