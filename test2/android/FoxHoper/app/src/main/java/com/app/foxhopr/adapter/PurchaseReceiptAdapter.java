package com.app.foxhopr.adapter;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.webservice.models.ReceiptDetailModel;
import com.foxhoper.app.R;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 18/11/15.
 */
public class PurchaseReceiptAdapter extends BaseAdapter {

    private Context mContext;
    private LayoutInflater mInflater;
    private ArrayList<ReceiptDetailModel>  mDatalist;
    /**
     * --Constructor
     * @param context
     */
    public PurchaseReceiptAdapter(Context context, ArrayList<ReceiptDetailModel>  list) {
        mContext = context;
        mDatalist =list;
        /*********** Layout inflator to call external xml layout () ***********/
        mInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    public void setData(ArrayList<ReceiptDetailModel> list) {
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
        public TextView mTextViewInvoiceDate;
        public TextView mTextPurchaseDate;
        public TextView mTextViewMembershipPlan;
        public ImageView mCheckBoxItemRecivedSelect;

    }

    @Override
    public View getView(final int position, View convertView, ViewGroup parent) {
        View view = convertView;
        final ViewHolder holder;

        if (convertView == null) {
            holder = new ViewHolder();
            view = mInflater.inflate(R.layout.item_purchase_receipt_layout, null);
            holder.mTextViewInvoiceDate	= (TextView) view.findViewById(R.id.invoice_date);
            holder.mTextPurchaseDate	= (TextView) view.findViewById(R.id.purchase_date);
            holder.mTextViewMembershipPlan	= (TextView) view.findViewById(R.id.membership_plan);
            holder.mCheckBoxItemRecivedSelect	= (ImageView) view.findViewById(R.id.checkBoxItemRecivedSelect);

            view.setTag(holder);
        } else {
            holder = (ViewHolder) view.getTag();
        }

        final ReceiptDetailModel mReceiptDetailModel 	=	mDatalist.get(position);

        if(null!=mReceiptDetailModel && null!=mReceiptDetailModel.getInvoice_date()){
            holder.mTextViewInvoiceDate.setText(" " +AppUtils.parseDateToddMMyyyy(mReceiptDetailModel.getInvoice_date()));
        }
        if(null!=mReceiptDetailModel && null!=mReceiptDetailModel.getPurchase()){
            holder.mTextPurchaseDate.setText(" " +AppUtils.parseDateToddMMyyyy(mReceiptDetailModel.getPurchase()));
        }
        if(null!=mReceiptDetailModel && null!=mReceiptDetailModel.getGroup_type()){
            if(mReceiptDetailModel.getGroup_type().equals(ApplicationConstants.GROUP_LOCAL)){
                holder.mTextViewMembershipPlan.setBackgroundResource(R.drawable.guoup_status);
            }else if(mReceiptDetailModel.getGroup_type().equals(ApplicationConstants.GROUP_GLOBAL)){
                holder.mTextViewMembershipPlan.setBackgroundResource(R.drawable.global_guoup_status);
            }
            holder.mTextViewMembershipPlan.setText(" "+mReceiptDetailModel.getGroup_type());
        }
        holder.mCheckBoxItemRecivedSelect.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(null!=mReceiptDetailModel.getPdfUrl()){
                    Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(mReceiptDetailModel.getPdfUrl()));
                    mContext.startActivity(browserIntent);
                }
            }
        });


        return view;
    }



}
