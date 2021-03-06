/**
 * Comment list Adapter class
 * Using for all type of comment
 * Received,Sent,SentArchive,ReceiveArchive
 *
 */
package com.app.foxhopr.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.WebCastListClickCallBack;
import com.app.foxhopr.webservice.models.WebCastListInnerModel;
import com.foxhoper.app.R;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;


public class WebCastListAdapter extends BaseAdapter {

	private Context mContext;
	private LayoutInflater mInflater;
	private WebCastListClickCallBack listClickCallBack;

	private ArrayList<WebCastListInnerModel> mDatalist;
	/**
	 * --Constructor
	 * @param context
	 */
	public WebCastListAdapter(Context context, ArrayList<WebCastListInnerModel> list, WebCastListClickCallBack mListClickCallBack) {
		mContext = context;
		mDatalist =list;
		listClickCallBack =mListClickCallBack;
		/*********** Layout inflator to call external xml layout () ***********/
		mInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
	}

	public void setData(ArrayList<WebCastListInnerModel> list) {
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

			view = mInflater.inflate(R.layout.item_row_webcast_video, null);

			holder.mName	= (TextView) view.findViewById(R.id.textViewItemRecivedName);
			holder.mAcceptedDate	= (TextView) view.findViewById(R.id.textViewItemSecivedName);
			holder.mIcon	= (ImageView) view.findViewById(R.id.checkBoxItemRecivedSelect);
			view.setTag(holder);
		} else {
			holder = (ViewHolder) view.getTag();
		}
		//String commentText= "<font color=#F15A2B>"+mDatalist.get(position).getTitle().trim()+ " " +"</font> <font color=#d3d3d3>"+mDatalist.get(position).getTitle()+"</font>";

		holder.mName.setText(mDatalist.get(position).getTitle().trim());
		holder.mAcceptedDate.setText(AppUtils.parseDateToddMMyyyy(mDatalist.get(position).getCreated()) +" @ "+ AppUtils.parseDateToddMMyyyyTime(mDatalist.get(position).getCreated()));
		Picasso.with(mContext)
				.load(mDatalist.get(position).getThumbnail())
				.resize(200, 200)
				.placeholder(R.drawable.video) // optional
				.into(holder.mIcon);

		return view;
	}


}
