/**
 * Comment list Adapter class
 * Using for all type of comment
 * Received,Sent,SentArchive,ReceiveArchive
 *
 */
package com.app.foxhopr.adapter;

import android.content.Context;
import android.graphics.Color;
import android.text.Spannable;
import android.text.SpannableString;
import android.text.style.ForegroundColorSpan;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ListClickCallBack;
import com.app.foxhopr.webservice.models.WebCastCommentModel;
import com.foxhoper.app.R;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;


public class WebCastCommentListAdapter extends BaseAdapter {

	private Context mContext;
	private LayoutInflater mInflater;
	private ListClickCallBack listClickCallBack;

	private ArrayList<WebCastCommentModel> mDatalist;
	/**
	 * --Constructor
	 * @param context
	 */
	public WebCastCommentListAdapter(Context context, ArrayList<WebCastCommentModel> list, ListClickCallBack mListClickCallBack) {
		mContext = context;
		mDatalist =list;
		listClickCallBack =mListClickCallBack;
		/*********** Layout inflator to call external xml layout () ***********/
		mInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
	}

	public void setData(ArrayList<WebCastCommentModel> list) {
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
		public TextView mName;
		public TextView mAcceptedDate;
		public ImageView mIcon;

	}

	@Override
	public View getView(final int position, View convertView, ViewGroup parent) {
		View view = convertView;
		final ViewHolder holder;

		if (convertView == null) {

			holder = new ViewHolder();

			view = mInflater.inflate(R.layout.item_row_comment_referral_details, null);

			holder.mName	= (TextView) view.findViewById(R.id.textViewItemRecivedName);
			holder.mAcceptedDate	= (TextView) view.findViewById(R.id.textViewItemSecivedName);
			holder.mIcon	= (ImageView) view.findViewById(R.id.checkBoxItemRecivedSelect);
			view.setTag(holder);
		} else {
			holder = (ViewHolder) view.getTag();
		}
		//String commentText= "<font color=#F15A2B>"+mDatalist.get(position).getCommented_by().trim()+ " " +"</font> <font color=#d3d3d3>"+mDatalist.get(position).getComment()+"</font>";

		String commentText=mDatalist.get(position).getCommented_by().trim()+ " " +mDatalist.get(position).getComment();
		Spannable wordtoSpan = new SpannableString(commentText);
		wordtoSpan.setSpan(new ForegroundColorSpan(Color.parseColor("#F15A2B")), 0, mDatalist.get(position).getCommented_by().trim().length(), Spannable.SPAN_EXCLUSIVE_EXCLUSIVE);
		wordtoSpan.setSpan(new ForegroundColorSpan(Color.parseColor("#d3d3d3")), mDatalist.get(position).getCommented_by().trim().length(), commentText.length(), Spannable.SPAN_EXCLUSIVE_EXCLUSIVE);
		//tv.setText(wordtoSpan);
		//holder.mName.setText(Html.fromHtml(commentText),TextView.BufferType.SPANNABLE);
		holder.mName.setText(wordtoSpan);

		holder.mAcceptedDate.setText(mContext.getResources().getString(R.string.posted_on) + " " +AppUtils.parseDateToddMMyyyy(mDatalist.get(position).getCreated()) +" @ "+ AppUtils.parseDateToddMMyyyyTime(mDatalist.get(position).getCreated()));
		Picasso.with(mContext)
				.load(mDatalist.get(position).getCommented_by_profile_image())
				.resize(100, 100)
				.placeholder(R.drawable.icon_user) // optional
				.into(holder.mIcon);

		return view;
	}


}
