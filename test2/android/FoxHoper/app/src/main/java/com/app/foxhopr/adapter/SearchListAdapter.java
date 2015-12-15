/**
 * Adapter class using for creating sort row in list
 */
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

import com.app.foxhopr.webservice.models.TeamMebmersListInnerModel;
import com.foxhoper.app.R;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by Chobey R. on 12/8/15.
 */
public class SearchListAdapter extends BaseAdapter {

    // Declare Variables
    Context mContext;
    LayoutInflater inflater;
    private List<TeamMebmersListInnerModel> dataList = null;
    private String[] arraylist;
    private TextView txtvNoResultFound;
    private ListView mListView;
    private CheckBox mSelectCheckBox;

    private ArrayList<String> selectedArray = new ArrayList<String>();


    public SearchListAdapter(Context context) {
        mContext = context;
        inflater = LayoutInflater.from(mContext);
        arraylist=context.getResources().getStringArray(R.array.string_search_sort_type);
    }

    public class ViewHolder {
        TextView txtvName;
        CheckBox chckBoxMember;

    }

    @Override
    public int getCount() {
        return arraylist.length;
    }

    @Override
    public String getItem(int position) {
        return arraylist[position];
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    public View getView(final int position, View view, ViewGroup parent) {
        final ViewHolder holder;
        if (view == null) {
            holder = new ViewHolder();
            view = inflater.inflate(R.layout.item_list, null);
            // Locate the TextViews in listview_item.xml
            holder.txtvName = (TextView) view.findViewById(R.id.textvNameList);
            holder.chckBoxMember = (CheckBox) view.findViewById(R.id.checkBoxItemMemberSelect);


            holder.chckBoxMember.setTag(arraylist[position]);
            // Locate the ImageView in listview_item.xml
            view.setTag(holder);
        } else {
            holder = (ViewHolder) view.getTag();
            ((ViewHolder) view.getTag()).chckBoxMember.setTag(arraylist[position]);
        }

        holder.chckBoxMember.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                //  TeamMebmersListInnerModel element = (TeamMebmersListInnerModel) holder.chckBoxMember.getTag();
                //  element.setSelected(buttonView.isChecked());


                if (isChecked) {
                    if (!selectedArray.contains(arraylist[position]))
                        selectedArray.add(arraylist[position]);
                } else {
                    if (selectedArray.contains(arraylist[position]))
                        selectedArray.remove(arraylist[position]);
                }



            }
        });

        // Set the results into TextViews


        holder.txtvName.setText(arraylist[position]);

        //holder.chckBoxMember.setChecked(dataList.get(position).isSelected());

        view.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //TeamMebmersListInnerModel element = (TeamMebmersListInnerModel) holder.chckBoxMember.getTag();
                //element.setSelected(holder.chckBoxMember.isChecked());

                if(holder.chckBoxMember.isChecked()){
                    holder.chckBoxMember.setChecked(false);
                }else{
                    holder.chckBoxMember.setChecked(true);
                }

                if(holder.chckBoxMember.isChecked()) {
                    if(!selectedArray.contains(arraylist[position]))
                        selectedArray.add(arraylist[position]);
                } else {
                    if(selectedArray.contains(arraylist[position]))
                        selectedArray.remove(arraylist[position]);
                }


            }
        });
        return view;
    }

    // Filter Class
   /* public void filter(String charText) {
        charText = charText.toLowerCase(Locale.getDefault());
        dataList.clear();
        if (charText.length() == 0) {
            dataList.addAll(arraylist);
        } else {
            for (TeamMebmersListInnerModel wp : arraylist) {
                String input = wp.getFname()+wp.getLname();
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
    }*/

    private void setChecked(boolean isChecked){
        /*if(isChecked){
            SelectMembersActivity.isAllSelected = true;
            mSelectCheckBox.setChecked(true);
        }
        else
            mSelectCheckBox.setChecked(false);*/
    }
}