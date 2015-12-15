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
import com.app.foxhopr.utils.ContactListClickCallBack;
import com.app.foxhopr.webservice.models.ContactListInnerModel;
import com.foxhoper.app.R;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 14/9/15.
 */
public class ContactListAdapter extends BaseAdapter {

    private Context mContext;
    private LayoutInflater mInflater;
    private ContactListClickCallBack listClickCallBack;

    private ArrayList<ContactListInnerModel> mDatalist;
    /**
     * --Constructor
     * @param context
     */
    public ContactListAdapter(Context context, ArrayList<ContactListInnerModel> list, ContactListClickCallBack mListClickCallBack) {
        mContext = context;
        mDatalist =list;
        listClickCallBack =mListClickCallBack;
        /*********** Layout inflator to call external xml layout () ***********/
        mInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    public void setData(ArrayList<ContactListInnerModel> list) {
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
        private CheckBox mChckBSelect;
        private TextView mTxtvReciverName;
        private TextView mTxtvToEmail;
        private TextView mTxtvDate;
        private TextView mTxtvJobTitle;
    }

    @Override
    public View getView(final int position, View convertView, ViewGroup parent) {
        View view = convertView;
        final ViewHolder holder;

        if (convertView == null) {

            holder = new ViewHolder();

            view = mInflater.inflate(R.layout.item_contact_layout, null);


            holder.mChckBSelect	= (CheckBox) view.findViewById(R.id.checkBoxItemRecivedSelect);
            holder.mTxtvReciverName	= (TextView) view.findViewById(R.id.textViewItemContactName);
            holder.mTxtvToEmail	= (TextView) view.findViewById(R.id.textViewItemEmail);
            holder.mTxtvDate	= (TextView) view.findViewById(R.id.textViewItemDate);
            holder.mTxtvJobTitle	= (TextView) view.findViewById(R.id.textViewJobTitle);

            holder.mChckBSelect.setTag(mDatalist.get(position));
            view.setTag(holder);
        } else {
            holder = (ViewHolder) view.getTag();
            ((ViewHolder) view.getTag()).mChckBSelect.setTag(mDatalist.get(position));
        }


        holder.mChckBSelect.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                ContactListInnerModel element = (ContactListInnerModel) holder.mChckBSelect.getTag();
                element.setSelected(buttonView.isChecked());
            }
        });

        ContactListInnerModel mInboxListInnerModel 	=	mDatalist.get(position);


        String toNameStr ="";

        if(mInboxListInnerModel.getFirst_name() != null){

            toNameStr =mInboxListInnerModel.getFirst_name();
            String s1 = toNameStr.substring(0, 1).toUpperCase();
            toNameStr = s1 + toNameStr.substring(1);

        }

        if(mInboxListInnerModel.getLast_name() != null ){
            String s1 = mInboxListInnerModel.getLast_name().substring(0, 1).toUpperCase();
            toNameStr =  toNameStr+" "+ s1 + mInboxListInnerModel.getLast_name().substring(1);
        }

        if(!toNameStr.equalsIgnoreCase("")){
            holder.mTxtvReciverName.setText(toNameStr);
        }else{
            holder.mTxtvReciverName.setVisibility(View.GONE);
        }

        if(null!=mInboxListInnerModel.getJob_title() && !mInboxListInnerModel.getJob_title().equals("") ){
            holder.mTxtvJobTitle.setText(mInboxListInnerModel.getJob_title());
        }else{
            holder.mTxtvJobTitle.setText("NA");
        }

        if(null!=mInboxListInnerModel.getEmail()){
            holder.mTxtvToEmail.setText(mInboxListInnerModel.getEmail());
        }

        if(mInboxListInnerModel.getCreated() != null && !mInboxListInnerModel.getCreated().equalsIgnoreCase("")){
            if(AppUtils.parseDateToddMMyyyy(mInboxListInnerModel.getCreated()) != null){
                holder.mTxtvDate.setText(AppUtils.parseDateToddMMyyyy(mInboxListInnerModel.getCreated()));
            }
        }


        holder.mChckBSelect.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mDatalist.get(position).isSelected()) {
                    if (!mArray.contains(mDatalist.get(position).getId()))
                        mArray.add(mDatalist.get(position));
                } else {
                    if (mArray.contains(mDatalist.get(position)))
                        mArray.remove(mDatalist.get(position));
                }
                checkBox(listClickCallBack);
            }
        });



        if (mDatalist.get(position).isSelected()) {
            if(!mArray.contains(mDatalist.get(position).getId()))
                mArray.add(mDatalist.get(position));
        } else {
            if(mArray.contains(mDatalist.get(position)))
                mArray.remove(mDatalist.get(position));
        }


        holder.mChckBSelect.setChecked(mDatalist.get(position).isSelected());
        return view;
    }

    ArrayList<ContactListInnerModel> mArray = new ArrayList<ContactListInnerModel>();
    public void checkBox( ContactListClickCallBack alerAction) {
        listClickCallBack.itemClickAction(mArray);
    }

    public void cleanSelectedArray(){
        mArray.clear();
    }


}
