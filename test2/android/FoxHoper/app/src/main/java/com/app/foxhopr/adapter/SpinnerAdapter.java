package com.app.foxhopr.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import com.foxhoper.app.R;

import java.util.ArrayList;

public class SpinnerAdapter extends ArrayAdapter<String> {

	private ArrayList<String> data;
	LayoutInflater inflater;

	public SpinnerAdapter(Context activity, int textViewResourceId,
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

		View row = inflater.inflate(R.layout.row_spinner, parent, false);

		TextView label = (TextView) row.findViewById(R.id.spinner_textview);
		label.setText(data.get(position));

		return row;
	}
}
