package com.app.foxhopr.adapter;

import android.content.Context;
import android.graphics.Typeface;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.ImageView;
import android.widget.TextView;

import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.MessageListClickCallBack;
import com.app.foxhopr.webservice.models.InboxListInnerModel;
import com.foxhoper.app.R;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 14/9/15.
 */
public class MessageInboxListAdapter extends BaseAdapter {

    private Context mContext;
    private LayoutInflater mInflater;
    private MessageListClickCallBack listClickCallBack;

    private ArrayList<InboxListInnerModel> mDatalist;
    /**
     * --Constructor
     * @param context
     */
    public MessageInboxListAdapter(Context context,  ArrayList<InboxListInnerModel> list, MessageListClickCallBack mListClickCallBack) {
        mContext = context;
        mDatalist =list;
        listClickCallBack =mListClickCallBack;
        /*********** Layout inflator to call external xml layout () ***********/
        mInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    public void setData(ArrayList<InboxListInnerModel> list) {
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
        private TextView mTxtvToName;
        private TextView mTxtvDate;
        private ImageView mAttachment;
    }

    @Override
    public View getView(final int position, View convertView, ViewGroup parent) {
        View view = convertView;
        final ViewHolder holder;

        if (convertView == null) {

            holder = new ViewHolder();

            view = mInflater.inflate(R.layout.item_message_inbox_layout, null);

            holder.mChckBSelect	= (CheckBox) view.findViewById(R.id.checkBoxItemInboxSelect);
            holder.mTxtvReciverName	= (TextView) view.findViewById(R.id.textViewItemSubjectName);
            holder.mTxtvToName	= (TextView) view.findViewById(R.id.textViewItemfromName);
            holder.mTxtvDate	= (TextView) view.findViewById(R.id.textViewItemDate);
            holder.mAttachment=(ImageView)view.findViewById(R.id.attachment);

            holder.mChckBSelect.setTag(mDatalist.get(position));
            view.setTag(holder);
        } else {
            holder = (ViewHolder) view.getTag();
            ((ViewHolder) view.getTag()).mChckBSelect.setTag(mDatalist.get(position));
        }


        holder.mChckBSelect.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                InboxListInnerModel element = (InboxListInnerModel) holder.mChckBSelect.getTag();
                element.setSelected(buttonView.isChecked());
            }
        });

        InboxListInnerModel mInboxListInnerModel 	=	mDatalist.get(position);

        String reciverNameStr ="";

        if(mInboxListInnerModel.getSubject() != null && !mInboxListInnerModel.getSubject().equalsIgnoreCase("")){
            reciverNameStr =mInboxListInnerModel.getSubject();
            String s1 = reciverNameStr.substring(0, 1).toUpperCase();
            reciverNameStr =s1 + reciverNameStr.substring(1);
        }


        if(!reciverNameStr.equalsIgnoreCase("")){
            holder.mTxtvReciverName.setText(reciverNameStr);
        }else{
            holder.mTxtvReciverName.setVisibility(View.GONE);
        }

        if( mInboxListInnerModel.is_read()){
            holder.mTxtvReciverName.setTypeface(Typeface.DEFAULT);
            //textview.setTypeface(Typeface.DEFAULT_BOLD);
        }else{
            holder.mTxtvReciverName.setTypeface(Typeface.DEFAULT_BOLD);
        }

        String toNameStr ="";

        if(mInboxListInnerModel.getFname() != null && !mInboxListInnerModel.getFname().equalsIgnoreCase("")){

            toNameStr =mInboxListInnerModel.getFname();
            String s1 = toNameStr.substring(0, 1).toUpperCase();
            toNameStr = s1 + toNameStr.substring(1);

        }

        if(mInboxListInnerModel.getLname() != null && !mInboxListInnerModel.getLname().equalsIgnoreCase("")){
            String s1 = mInboxListInnerModel.getLname().substring(0, 1).toUpperCase();
            toNameStr =  toNameStr+" "+ s1 + mInboxListInnerModel.getLname().substring(1);
        }

        if(!toNameStr.equalsIgnoreCase("")){
            holder.mTxtvToName.setText(mContext.getResources().getString(R.string.str_from)+" "+toNameStr);
        }else{
            holder.mTxtvToName.setVisibility(View.GONE);
        }

        if(mInboxListInnerModel.getCreated() != null && !mInboxListInnerModel.getCreated().equalsIgnoreCase("")){
            if(AppUtils.parseDateToddMMyyyy(mInboxListInnerModel.getCreated()) != null){
                holder.mTxtvDate.setText(AppUtils.parseDateToddMMyyyy(mInboxListInnerModel.getCreated()));
            }
        }

        if(null!= mInboxListInnerModel && mInboxListInnerModel.isAttachment()){
            holder.mAttachment.setVisibility(View.VISIBLE);
        }else{
            holder.mAttachment.setVisibility(View.INVISIBLE);
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

    ArrayList<InboxListInnerModel> mArray = new ArrayList<InboxListInnerModel>();
    public void checkBox( MessageListClickCallBack alerAction) {
        listClickCallBack.itemClickAction(mArray);
    }

    public void cleanSelectedArray(){
        mArray.clear();
    }


}
