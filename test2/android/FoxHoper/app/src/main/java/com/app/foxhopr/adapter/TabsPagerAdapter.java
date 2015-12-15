package com.app.foxhopr.adapter;

import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentStatePagerAdapter;
import android.widget.Button;
import android.widget.HorizontalScrollView;
import android.widget.LinearLayout;

import com.app.foxhopr.fragments.HomeFragment;
import com.app.foxhopr.fragments.user.Referrals.ReferralsReceivedFragment;
import com.app.foxhopr.utils.TabSelectedCallBack;

public class TabsPagerAdapter extends FragmentStatePagerAdapter {

	private String[] TITLES;
	private HorizontalScrollView mLlTopTabs;
	private LinearLayout mLlBottomTabs;
	private Button mBtnDelete;
	private LinearLayout mLLBottomSelectTab;
	private LinearLayout mLLBottomMoreTab;
	private TabSelectedCallBack mTabSelectedCallBack;
	public TabsPagerAdapter(FragmentManager fm, String[] TITLES, HorizontalScrollView mLlTopTabs, LinearLayout mLlBottomTabs,
							Button mBtnDelete, LinearLayout lLBottomSelectTab, LinearLayout lLBottomMoreTab,
							TabSelectedCallBack mTabSelectedCallBack) {
		super(fm);
		this.TITLES = TITLES;
		this.mLlTopTabs = mLlTopTabs;
		this.mLlBottomTabs = mLlBottomTabs;
		this.mBtnDelete = mBtnDelete;
		this.mLLBottomSelectTab = lLBottomSelectTab;
		this.mLLBottomMoreTab = lLBottomMoreTab;
		this.mTabSelectedCallBack = mTabSelectedCallBack;
	}

	@Override
	public CharSequence getPageTitle(int position) {
		return TITLES[position];
	}

	@Override
	public int getCount() {
		return TITLES.length;
	}

	@Override
	public Fragment getItem(int position) {
		Fragment frag = null;
		switch (position) {
			case 0 :
				frag = ReferralsReceivedFragment.newInstance(mLlTopTabs, mLlBottomTabs, mBtnDelete, mLLBottomSelectTab, mLLBottomMoreTab,mTabSelectedCallBack);
				break;
			case 1 :
				frag = new HomeFragment();
				break;
			case 2 :
				frag = new HomeFragment();
				break;
			case 3 :
				frag = new HomeFragment();
				break;
			default :
				break;
		}
		return frag;
	}

}