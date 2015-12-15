package com.app.foxhopr.adapter;

import android.content.Context;
import android.text.Html;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.TextView;

import com.app.foxhopr.utils.CurrentTeamListClickCallBack;
import com.app.foxhopr.webservice.models.CurrentTeamInnerModel;
import com.foxhoper.app.R;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 12/10/15.
 */
public class PreviousTeamListAdapter extends BaseAdapter {

    private Context mContext;
    private LayoutInflater mInflater;
    private CurrentTeamListClickCallBack listClickCallBack;

    private ArrayList<CurrentTeamInnerModel> mDatalist;
    /**
     * --Constructor
     * @param context
     */
    public PreviousTeamListAdapter(Context context, ArrayList<CurrentTeamInnerModel> list, CurrentTeamListClickCallBack mListClickCallBack) {
        mContext = context;
        mDatalist =list;
        listClickCallBack =mListClickCallBack;
        /*********** Layout inflator to call external xml layout () ***********/
        mInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    public void setData(ArrayList<CurrentTeamInnerModel> list) {
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
        private TextView mTextViewItemLocation;
        private TextView mTxtvDate;
        private TextView mTxtvJobTitle;
    }

    @Override
    public View getView(final int position, View convertView, ViewGroup parent) {
        View view = convertView;
        final ViewHolder holder;

        if (convertView == null) {

            holder = new ViewHolder();

            view = mInflater.inflate(R.layout.item_current_team_layout, null);


            holder.mChckBSelect	= (CheckBox) view.findViewById(R.id.checkBoxItemRecivedSelect);
            holder.mTxtvReciverName	= (TextView) view.findViewById(R.id.textViewItemTeamName);
            holder.mTextViewItemLocation	= (TextView) view.findViewById(R.id.textViewItemLocation);
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
                CurrentTeamInnerModel element = (CurrentTeamInnerModel) holder.mChckBSelect.getTag();
                element.setSelected(buttonView.isChecked());
            }
        });

        CurrentTeamInnerModel mInboxListInnerModel 	=	mDatalist.get(position);


        String toNameStr ="";

        if(mInboxListInnerModel.getFname() != null){

            toNameStr =mInboxListInnerModel.getFname();
            String s1 = toNameStr.substring(0, 1).toUpperCase();
            toNameStr = s1 + toNameStr.substring(1);

        }

        if(mInboxListInnerModel.getLname() != null ){
            String s1 = mInboxListInnerModel.getLname().substring(0, 1).toUpperCase();
            toNameStr =  toNameStr+" "+ s1 + mInboxListInnerModel.getLname().substring(1);
        }

        if(!toNameStr.equalsIgnoreCase("")){
            holder.mTxtvReciverName.setText(toNameStr);
        }else{
            holder.mTxtvReciverName.setVisibility(View.GONE);
        }

        if(null!=mInboxListInnerModel.getProfession_name() && !mInboxListInnerModel.getProfession_name().equals("") ){

            if(null!=mInboxListInnerModel.getCompany()){
                String jobTitle= "<font color=#504D4E>"+mInboxListInnerModel.getProfession_name()+  "</font>" + "<font color=#504D4E>"+" @ " +"</font> <font color=#D45D32>" +mInboxListInnerModel.getCompany()+"</font>";
                holder.mTxtvJobTitle.setText(Html.fromHtml(jobTitle));
            }else{
                holder.mTxtvJobTitle.setText(mInboxListInnerModel.getProfession_name());
            }

        }else{
            holder.mTxtvJobTitle.setText("NA");
        }

        if(null!=mInboxListInnerModel.getState_name() && null!=mInboxListInnerModel.getCountry_name()){
            holder.mTextViewItemLocation.setText(mInboxListInnerModel.getState_name()+ ", " + mInboxListInnerModel.getCountry_name());
        }

       /* if(mInboxListInnerModel.getCreated() != null && !mInboxListInnerModel.getCreated().equalsIgnoreCase("")){
            if(AppUtils.parseDateToddMMyyyy(mInboxListInnerModel.getCreated()) != null){
                holder.mTxtvDate.setText(AppUtils.parseDateToddMMyyyy(mInboxListInnerModel.getCreated()));
            }
        }*/


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

    ArrayList<CurrentTeamInnerModel> mArray = new ArrayList<CurrentTeamInnerModel>();
    public void checkBox( CurrentTeamListClickCallBack alerAction) {
        listClickCallBack.itemClickAction(mArray);
    }

    public void cleanSelectedArray(){
        mArray.clear();
    }


}
