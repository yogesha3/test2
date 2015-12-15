package com.app.foxhopr.custom.view;

import android.content.Context;
import android.widget.ListView;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Spinner;

import com.app.foxhopr.adapter.CustomPickerAdapter;
import com.app.foxhopr.adapter.SpinnerAdapter;
import com.foxhoper.app.R;

import java.util.ArrayList;
import java.util.Arrays;

public class RegistrationSupport {

	public static void setLocalSpinnerData(Context context,
			int array_resource_id, Spinner target_spinner) {
		String[] navMenuTitles = context.getResources().getStringArray(
				array_resource_id);

		ArrayList<String> data = new ArrayList<String>(
				Arrays.asList(navMenuTitles));
		SpinnerAdapter adapter = new SpinnerAdapter(context,
				R.layout.row_spinner, data);
		target_spinner.setAdapter(adapter);
	}
	
	public static void setLocalCustomSpinnerData(Context context,
			int array_resource_id, ListView listView) {
		String[] navMenuTitles = context.getResources().getStringArray(
				array_resource_id);

		ArrayList<String> data = new ArrayList<String>(
				Arrays.asList(navMenuTitles));
		CustomPickerAdapter adapter = new CustomPickerAdapter(context,
				R.layout.row_custom_picker, data);
		listView.setAdapter(adapter);
	}


	public static int getSpinnerPosition(Context context,
			int array_resource_id, String text) {

		int selectedPosition = 0;

		String[] navMenuTitles = context.getResources().getStringArray(
				array_resource_id);
		ArrayList<String> data = new ArrayList<String>(
				Arrays.asList(navMenuTitles));
		int position = data.indexOf(text);

		if (position != -1)
			selectedPosition = position;

		return selectedPosition;
	}

	public static int getSelectedRadioButtonId(RadioGroup radio_group, String selected_button_text) {

		int selected_button_id = 0;
		for (int i = 0; i < radio_group.getChildCount(); i++) {
			RadioButton btn = (RadioButton) radio_group.getChildAt(i);
			if (btn != null) {
				if (btn.getText().toString().equalsIgnoreCase(selected_button_text)) {
					selected_button_id = btn.getId();
					break;
				}
			}
		}

		return selected_button_id;
	}

}
