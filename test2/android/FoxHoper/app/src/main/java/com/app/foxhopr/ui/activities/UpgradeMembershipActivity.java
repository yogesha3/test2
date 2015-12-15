package com.app.foxhopr.ui.activities;

import android.os.Bundle;
import android.support.v4.app.FragmentActivity;

import com.foxhoper.app.R;

public class UpgradeMembershipActivity extends FragmentActivity {
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_upgrade_membership);
	}
	@Override
	public void onBackPressed() {
		super.onBackPressed();
		overridePendingTransition(R.anim.slide_in_left, R.anim.slide_out_right);
	}
}
