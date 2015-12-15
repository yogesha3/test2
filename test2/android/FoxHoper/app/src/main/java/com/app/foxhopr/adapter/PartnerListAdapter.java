package com.app.foxhopr.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.webservice.models.PartnerListInnerModel;
import com.foxhoper.app.R;

import java.text.NumberFormat;
import java.util.ArrayList;

/**
 * Created by Chobey R. on 14/9/15.
 */
public class PartnerListAdapter extends BaseAdapter {

    private Context mContext;
    private LayoutInflater mInflater;

    private ArrayList<PartnerListInnerModel> mDatalist;
    /**
     * --Constructor
     * @param context
     */
    public PartnerListAdapter(Context context, ArrayList<PartnerListInnerModel> list) {
        mContext = context;
        mDatalist =list;
        /*********** Layout inflator to call external xml layout () ***********/
        mInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    public void setData(ArrayList<PartnerListInnerModel> list) {
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
        private TextView mImageViewStatus;
        private ImageView mImageStatus;
        private TextView mTextViewItemInvitee;
        private TextView mTxtvToEmail;
        private TextView mTxtvDate;
        private TextView mTxtvAmount;
    }

    @Override
    public View getView(final int position, View convertView, ViewGroup parent) {
        View view = convertView;
        final ViewHolder holder;

        if (convertView == null) {

            holder = new ViewHolder();

            view = mInflater.inflate(R.layout.item_partner_layout, null);
            holder.mImageViewStatus	= (TextView) view.findViewById(R.id.textViewViewStatus);
            holder.mImageStatus	= (ImageView) view.findViewById(R.id.imageViewStatus);
            holder.mTextViewItemInvitee	= (TextView) view.findViewById(R.id.textViewItemInvitee);
            holder.mTxtvToEmail	= (TextView) view.findViewById(R.id.textViewItemEmail);
            holder.mTxtvDate	= (TextView) view.findViewById(R.id.textViewItemDate);
            holder.mTxtvAmount	= (TextView) view.findViewById(R.id.textViewAmount);

            view.setTag(holder);
        } else {
            holder = (ViewHolder) view.getTag();
        }
        PartnerListInnerModel mInboxListInnerModel 	=	mDatalist.get(position);


        String toNameStr ="";

        if(mInboxListInnerModel.getInvitee_name() != null){

            toNameStr =mInboxListInnerModel.getInvitee_name();
            String s1 = toNameStr.substring(0, 1).toUpperCase();
            toNameStr = s1 + toNameStr.substring(1);

        }

        if(!toNameStr.equalsIgnoreCase("")){
            holder.mTextViewItemInvitee.setText(toNameStr);
        }else{
            holder.mTextViewItemInvitee.setVisibility(View.GONE);
        }

        if(null!=mInboxListInnerModel.getReferral_amount() && !mInboxListInnerModel.getReferral_amount().equals("") ){
           // holder.mTxtvAmount.setText(mInboxListInnerModel.getReferral_amount());
            holder.mTxtvAmount.setText(" $" + NumberFormat.getInstance().format(Long.parseLong(mInboxListInnerModel.getReferral_amount())));
        }else{
            holder.mTxtvAmount.setText(" $0");
        }

        if(null!=mInboxListInnerModel.getInvitee_email()){
            holder.mTxtvToEmail.setText(mInboxListInnerModel.getInvitee_email());
        }

        if(mInboxListInnerModel.getCreated() != null && !mInboxListInnerModel.getCreated().equalsIgnoreCase("")){
            if(AppUtils.parseDateToddMMyyyy(mInboxListInnerModel.getCreated()) != null){
                holder.mTxtvDate.setText(AppUtils.parseDateToddMMyyyy(mInboxListInnerModel.getCreated()));
            }
        }
        if(null!=mInboxListInnerModel.getStatus() && mInboxListInnerModel.getStatus().equals("pending")){
            holder.mImageStatus.setImageResource(R.drawable.icon_pending);
        }else{
            holder.mImageStatus.setImageResource(R.drawable.icon_accepted);
        }

        if(mInboxListInnerModel.getStatus() != null && !mInboxListInnerModel.getStatus().equalsIgnoreCase("") && !mInboxListInnerModel.getStatus().equalsIgnoreCase("null")){
            String s1 = mInboxListInnerModel.getStatus().substring(0, 1).toUpperCase();
            String nameCapitalized = s1 + mInboxListInnerModel.getStatus().substring(1);

            holder.mImageViewStatus.setText(" "+nameCapitalized);
        }else{
            holder.mImageViewStatus.setText("N/A");
        }

        return view;
    }

}
