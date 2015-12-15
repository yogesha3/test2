package com.app.foxhopr.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.webservice.models.GroupListInnerModel;
import com.foxhoper.app.R;

import java.util.ArrayList;

/**
 * Created by Chobey R. on 21/10/15.
 */
public class GroupSelectionAdapter extends BaseAdapter {

    private Context mContext;
    private LayoutInflater mInflater;
    private ArrayList<GroupListInnerModel> mDatalist;
    /**
     * --Constructor
     * @param context
     */
    public GroupSelectionAdapter(Context context,  ArrayList<GroupListInnerModel> list) {
        mContext = context;
        mDatalist =list;
        /*********** Layout inflator to call external xml layout () ***********/
        mInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    public void setData(ArrayList<GroupListInnerModel> list) {
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
        public TextView mtextViewDay;
        public TextView mTextViewDayTime;
        public TextView mTextViewAccoutStatus;
        public TextView mTtextViewLocation;

        public TextView mTextViewGroupName;
        public TextView mTxtvValues;

        public LinearLayout mllFirst;
        public LinearLayout mllSecond;
    }

    @Override
    public View getView(final int position, View convertView, ViewGroup parent) {
        View view = convertView;
        final ViewHolder holder;

        if (convertView == null) {

            holder = new ViewHolder();

            view = mInflater.inflate(R.layout.item_group_selection_layout, null);

            holder.mTextViewDayTime	= (TextView) view.findViewById(R.id.textViewDayTime);
            holder.mtextViewDay	= (TextView) view.findViewById(R.id.textViewDay);
            holder.mTextViewAccoutStatus	= (TextView) view.findViewById(R.id.textViewAccoutStatus);
            holder.mTtextViewLocation	= (TextView) view.findViewById(R.id.textViewLocation);

            holder.mTextViewGroupName	= (TextView) view.findViewById(R.id.textViewGroupName);
            holder.mTxtvValues	= (TextView) view.findViewById(R.id.textViewReferralsValues);

            holder.mllFirst	= (LinearLayout) view.findViewById(R.id.llFirst);
            holder.mllSecond	= (LinearLayout) view.findViewById(R.id.llSecond);

            view.setTag(holder);
        } else {
            holder = (ViewHolder) view.getTag();
        }

        GroupListInnerModel mGroupListInnerModel 	=	mDatalist.get(position);

        String reciverNameStr ="";

        if(mGroupListInnerModel.getGroupName() != null && !mGroupListInnerModel.getGroupName().equalsIgnoreCase("")){
            reciverNameStr =mGroupListInnerModel.getGroupName();
            String s1 = reciverNameStr.substring(0, 1).toUpperCase();
            reciverNameStr =s1 + reciverNameStr.substring(1);
        }
        if(!reciverNameStr.equalsIgnoreCase("")){
            holder.mTextViewGroupName.setText(reciverNameStr);
        }else{
            holder.mTextViewGroupName.setVisibility(View.GONE);
        }

        if(null!=mGroupListInnerModel.getGroupType() && !mGroupListInnerModel.getGroupType().equalsIgnoreCase("")){
            holder.mTextViewAccoutStatus.setText(mGroupListInnerModel.getGroupType());
            if(mGroupListInnerModel.getGroupType().equals("local")){
                holder.mTextViewAccoutStatus.setBackgroundResource(R.drawable.guoup_status);
            }else if(mGroupListInnerModel.getGroupType().equals("global")){
                holder.mTextViewAccoutStatus.setBackgroundResource(R.drawable.guoup_status_global);
            }
        }

        if(null!=mGroupListInnerModel.getStateName() && !mGroupListInnerModel.getStateName().equalsIgnoreCase("")){
            holder.mTtextViewLocation.setText(mGroupListInnerModel.getStateName() +", "+ mGroupListInnerModel.getCountryName() );

        }else{
            holder.mTtextViewLocation.setText(mGroupListInnerModel.getCountryName() );
        }


        if(mGroupListInnerModel.getMeetingDate() != null && !mGroupListInnerModel.getMeetingDate().equalsIgnoreCase("")){
            if( null!=mGroupListInnerModel.getMeetingTime()){
                holder.mTextViewDayTime.setText(AppUtils.parseDaytime(mGroupListInnerModel.getMeetingDate() + " "+mGroupListInnerModel.getMeetingTime()+":00"));
            }
        }

        if(mGroupListInnerModel.getMeetingDate() != null && !mGroupListInnerModel.getMeetingDate().equalsIgnoreCase("")){
            if( null!=mGroupListInnerModel.getMeetingTime()){
                holder.mtextViewDay.setText(AppUtils.parseDay(mGroupListInnerModel.getMeetingDate() + " "+mGroupListInnerModel.getMeetingTime()+":00"));
            }
        }

        int mGetMember=Integer.parseInt(mGroupListInnerModel.getMembers());
        int mVacent=Integer.parseInt(mGroupListInnerModel.getMembers());
        int draw;
        int notdraw;
        if(mGetMember>10){
            draw=10;
            notdraw=0;
        }else{
            draw=mGetMember;
            notdraw=10-mGetMember;

        }
        setReviewValue(draw,notdraw,holder.mllFirst);

        int sdraw;
        int snotdraw;
        if(mGetMember>10){
            sdraw=mGetMember-10;
            snotdraw=mVacent;
        }else{
            sdraw=0;
            snotdraw=mVacent+(10-mGetMember);
        }
        setReviewValue(sdraw,snotdraw,holder.mllSecond);

        return view;
    }

    /**
     * Setting review result on custom layout
     * @param
     */
    public void setReviewValue(int draw,int notdraw, LinearLayout mllFirst) {
        SharedPreference mPreferences=new SharedPreference();
        mllFirst.setWeightSum(10f);
        mllFirst.removeAllViews();
        for (int i = 0; i < draw; i++) {
            LayoutInflater mInflater;
            mInflater = (LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            LinearLayout view = (LinearLayout) mInflater.inflate(R.layout.row_member_layout, null);
            ImageView iv = (ImageView) view.findViewById(R.id.iconrating);
            if(mPreferences.getSelectedGroupType(mContext).equals(ApplicationConstants.GROUP_LOCAL)) {
                iv.setBackgroundResource(R.drawable.icon_group);
            }else{
                iv.setBackgroundResource(R.drawable.icon_global_selection);
            }
            view.setLayoutParams(new LinearLayout.LayoutParams(0, LinearLayout.LayoutParams.WRAP_CONTENT, 1f));
            mllFirst.addView(view);
        }
        for (int i = 0; i < notdraw; i++) {
            LayoutInflater mInflater;
            mInflater = (LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            LinearLayout view = (LinearLayout) mInflater.inflate(R.layout.row_member_layout, null);
            ImageView iv = (ImageView) view.findViewById(R.id.iconrating);
            iv.setBackgroundResource(R.drawable.icon_goup_selection);
            view.setLayoutParams(new LinearLayout.LayoutParams(0, LinearLayout.LayoutParams.WRAP_CONTENT, 1f));
            mllFirst.addView(view);
        }

    }

}
