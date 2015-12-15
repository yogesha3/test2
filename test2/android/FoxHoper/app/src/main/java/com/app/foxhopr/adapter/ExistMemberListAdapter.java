package com.app.foxhopr.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.ListView;
import android.widget.TextView;

import com.app.foxhopr.ui.activities.SelectMembersActivity;
import com.app.foxhopr.utils.ExistContactClickCallBack;
import com.app.foxhopr.webservice.models.ExistTeamMebmersListInnerModel;
import com.foxhoper.app.R;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class ExistMemberListAdapter extends BaseAdapter {

    // Declare Variables
    Context mContext;
    LayoutInflater inflater;
    private List<ExistTeamMebmersListInnerModel> dataList = null;
    private ArrayList<ExistTeamMebmersListInnerModel> arraylist;
    private TextView txtvNoResultFound;
    private ListView mListView;
    private CheckBox mSelectCheckBox;
    private ExistContactClickCallBack listClickCallBack;
    private ArrayList<String> selectedArray = new ArrayList<String>();


    public ExistMemberListAdapter(Context context, List<ExistTeamMebmersListInnerModel> dataArrlist, TextView txtvNoResultFound, ListView mListView, CheckBox mSelectCheckBox,ExistContactClickCallBack mExistContactClickCallBack) {
        mContext = context;
        this.dataList = dataArrlist;
        inflater = LayoutInflater.from(mContext);
        this.arraylist = new ArrayList<ExistTeamMebmersListInnerModel>();
        this.arraylist.addAll(dataArrlist);
        this.txtvNoResultFound =txtvNoResultFound;
        this.mListView =mListView;
        this.mSelectCheckBox = mSelectCheckBox;
        this.listClickCallBack=mExistContactClickCallBack;
    }

    public class ViewHolder {
        TextView txtvName;
        CheckBox chckBoxMember;

    }

    @Override
    public int getCount() {
        return dataList.size();
    }

    @Override
    public ExistTeamMebmersListInnerModel getItem(int position) {
        return dataList.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    public View getView(final int position, View view, ViewGroup parent) {
        final ViewHolder holder;
        if (view == null) {
            holder = new ViewHolder();
            view = inflater.inflate(R.layout.contact_item_list, null);
            // Locate the TextViews in listview_item.xml
            holder.txtvName = (TextView) view.findViewById(R.id.textvNameList);
            holder.chckBoxMember = (CheckBox) view.findViewById(R.id.checkBoxItemMemberSelect);


            holder.chckBoxMember.setTag(dataList.get(position));
            // Locate the ImageView in listview_item.xml
            view.setTag(holder);
        } else {
            holder = (ViewHolder) view.getTag();
            ((ViewHolder) view.getTag()).chckBoxMember.setTag(dataList.get(position));
        }

        holder.chckBoxMember.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                ExistTeamMebmersListInnerModel element = (ExistTeamMebmersListInnerModel) holder.chckBoxMember.getTag();
                element.setSelected(buttonView.isChecked());

                if(isChecked) {
                    if(!selectedArray.contains(dataList.get(position).getId()))
                        selectedArray.clear();
                        listClickCallBack.itemClickAction(null,holder.chckBoxMember,position);
                        selectedArray.clear();
                        selectedArray.add(dataList.get(position).getId());

                } else {
                    if(selectedArray.contains(dataList.get(position).getId()))
                        selectedArray.remove(dataList.get(position).getId());
                }

                if(selectedArray.size() == 1){
                    setChecked(false);
                }

            }
        });

        // Set the results into TextViews
        String userName ="";
        if(dataList.get(position).getFirst_name() != null && !dataList.get(position).getFirst_name().equalsIgnoreCase(""))
        {
            userName = dataList.get(position).getFirst_name();
            String s1 = userName.substring(0, 1).toUpperCase();
            userName = s1 + userName.substring(1);
        }
        if(dataList.get(position).getLast_name() != null && !dataList.get(position).getLast_name().equalsIgnoreCase(""))
        {
            String s1 = dataList.get(position).getLast_name().substring(0, 1).toUpperCase();
            userName =  userName+" "+ s1 + dataList.get(position).getLast_name().substring(1);
        }

        holder.txtvName.setText(userName);

        holder.chckBoxMember.setChecked(dataList.get(position).isSelected());

        /*view.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                ExistTeamMebmersListInnerModel element = (ExistTeamMebmersListInnerModel) holder.chckBoxMember.getTag();
                element.setSelected(holder.chckBoxMember.isChecked());

                if(holder.chckBoxMember.isChecked()){
                    holder.chckBoxMember.setChecked(false);
                }else{
                    holder.chckBoxMember.setChecked(true);
                }

                if(holder.chckBoxMember.isChecked()) {
                    if(!selectedArray.contains(dataList.get(position).getId()))
                        selectedArray.add(dataList.get(position).getId());
                } else {
                    if(selectedArray.contains(dataList.get(position).getId()))
                        selectedArray.remove(dataList.get(position).getId());
                }

                if(selectedArray.size() == dataList.size()){
                    setChecked(true);
                }else{
                    setChecked(false);
                }
            }
        });*/
        return view;
    }

    // Filter Class
    public void filter(String charText) {
        charText = charText.toLowerCase(Locale.getDefault());
        dataList.clear();
        if (charText.length() == 0) {
            dataList.addAll(arraylist);
        } else {
            for (ExistTeamMebmersListInnerModel wp : arraylist) {
                String input = wp.getFirst_name()+wp.getLast_name();
                input = input.replace(" ", "");

                charText = charText.replace(" ", "");
                if (input.toLowerCase(Locale.getDefault()).contains(charText)) {
                    dataList.add(wp);
                }
            }

        }

        if(dataList.size() == 0){
            txtvNoResultFound.setVisibility(View.VISIBLE);
            mListView.setVisibility(View.GONE);
        }else
        {
            mListView.setVisibility(View.VISIBLE);
            txtvNoResultFound.setVisibility(View.GONE);
        }

        notifyDataSetChanged();
    }

    private void setChecked(boolean isChecked){
        if(isChecked){
            SelectMembersActivity.isAllSelected = true;
            mSelectCheckBox.setChecked(true);
        }
        else
            mSelectCheckBox.setChecked(false);
    }

}