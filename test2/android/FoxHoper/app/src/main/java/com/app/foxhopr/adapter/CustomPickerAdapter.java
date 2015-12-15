package com.app.foxhopr.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import com.foxhoper.app.R;

import java.util.ArrayList;


public class CustomPickerAdapter extends ArrayAdapter<String> {

	private ArrayList<String> data;
	LayoutInflater inflater;

	public CustomPickerAdapter(Context activity, int textViewResourceId,
			ArrayList<String> objects) {
		super(activity, textViewResourceId, objects);
		data = objects;

		/*********** Layout inflator to call external xml layout () **********************/
		inflater = (LayoutInflater) activity
				.getSystemService(Context.LAYOUT_INFLATER_SERVICE);

	}

	@Override
	public View getDropDownView(int position, View convertView, ViewGroup parent) {
		return getCustomView(position, convertView, parent);
	}

	@Override
	public View getView(int position, View convertView, ViewGroup parent) {
		return getCustomView(position, convertView, parent);
	}

	public View getCustomView(int position, View convertView, ViewGroup parent) {

		View row = inflater.inflate(R.layout.row_custom_picker, parent, false);

		TextView label = (TextView) row.findViewById(R.id.custom_picker_textview);
		label.setText(data.get(position));
		/*
		if(position%2==0){
			row.setBackgroundColor(getContext().getResources().getColor(R.color.white));
		}else{
			row.setBackgroundColor(getContext().getResources().getColor(R.color.gray_picker_list));
		}
*/
		return row;
	}
}
