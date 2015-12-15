/**
 * notification list Adapter class
 *
 */
package com.app.foxhopr.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import com.app.foxhopr.ui.activities.NotificationListActivity;
import com.app.foxhopr.utils.ListClickCallBack;
import com.foxhoper.app.R;

import java.util.ArrayList;


public class NotificationListAdapter extends BaseAdapter {

	private Context mContext;
	private LayoutInflater mInflater;
	private ListClickCallBack listClickCallBack;

	private ArrayList<NotificationListActivity.Row> mDatalist;
	/**
	 * --Constructor
	 * @param context
	 */
	public NotificationListAdapter(Context context, ArrayList<NotificationListActivity.Row> list, ListClickCallBack mListClickCallBack) {
		mContext = context;
		mDatalist =list;
		listClickCallBack =mListClickCallBack;
		/*********** Layout inflator to call external xml layout () ***********/
		mInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
	}

	public void setData(ArrayList<NotificationListActivity.Row> list) {
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
		public TextView mTxtvToName;
	}

	@Override
	public View getView(final int position, View convertView, ViewGroup parent) {
		View view = convertView;
		final ViewHolder holder;

		if (convertView == null) {

			holder = new ViewHolder();

			view = mInflater.inflate(R.layout.row_notification_layout, null);

			holder.mTxtvToName	= (TextView) view.findViewById(R.id.norification);
			view.setTag(holder);
		} else {
			holder = (ViewHolder) view.getTag();
		}

		if(mDatalist.get(position).getType().equals("referrel")) {
			holder.mTxtvToName.setText(String.format(mContext.getString(R.string.notification_referrel), mDatalist.get(position).getCount()));
		}
		if(mDatalist.get(position).getType().equals("messages")) {
			holder.mTxtvToName.setText(String.format(mContext.getString(R.string.notification_message), mDatalist.get(position).getCount()));
		}


		return view;
	}



}
