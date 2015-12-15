package com.app.foxhopr.fragments.user;

import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.app.foxhopr.adapter.TabsPagerAdapter;
import com.app.foxhopr.fragments.user.Referrals.ReferralsReceivedArchiveFragment;
import com.app.foxhopr.fragments.user.Referrals.ReferralsReceivedFragment;
import com.app.foxhopr.fragments.user.Referrals.ReferralsSentArchiveFragment;
import com.app.foxhopr.fragments.user.Referrals.ReferralsSentFragment;
import com.app.foxhopr.ui.activities.SendReferralsSelectMembersActivity;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.foxhoper.app.R;

public class ReferralsFragment extends Fragment implements View.OnClickListener {
    private Context mContext;
    //private ViewPager mPager;
    private TabsPagerAdapter adapter;

    private HorizontalScrollView mLLTopTabs;
    private LinearLayout mLLBottomTabs;

    private LinearLayout mLLReferralsTab;
    private LinearLayout mLLSentTab;
    private LinearLayout mLLRecivedArchiveTab;
    private LinearLayout mLLSendArchiveTab;

    private TextView mTxtvReferralsTab;
    private TextView mTxtvSentTab;
    private TextView mTxtvRecivedArchiveTab;
    private TextView mTxtvSendArchiveTab;

    private Button mBtnDelete;

    private LinearLayout mLLBottomHomeTab;
    private LinearLayout mLLBottomSelectTab;
    private LinearLayout mLLBottomComposeTab;
    private LinearLayout mLLBottomMoreTab;

    private TextView mTxtvBottomHomeTab;
    private TextView mTxtvBottomSelectTab;
    private TextView mTxtvBottomComposeTab;
    private TextView mTxtvBottomMoreTab;

    private ImageView mImagevBottomHomeTab;
    private ImageView mImagevBottomSelectTab;
    private ImageView mImagevBottomComposeTab;
    private ImageView mImagevBottomMoreTab;
    public static boolean mStatusSearch=false;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        mContext = getActivity();
        View view = inflater.inflate(R.layout.fragment_referrals, container, false);
        initView(view);

        return view;
    }

    private void initView(View view) {

        mLLTopTabs = (HorizontalScrollView) view.findViewById(R.id.llReferralsTabs);
        mLLBottomTabs = (LinearLayout) view.findViewById(R.id.llReferralsBottomTabs);

        mLLReferralsTab = (LinearLayout) view.findViewById(R.id.llReferralsRecivedTab);
        mLLSentTab = (LinearLayout) view.findViewById(R.id.llReferralsSentTab);
        mLLRecivedArchiveTab = (LinearLayout) view.findViewById(R.id.llReferralsRecivedArchivedTab);
        mLLSendArchiveTab = (LinearLayout) view.findViewById(R.id.llReferralsArchivedTab);

        mTxtvReferralsTab = (TextView) view.findViewById(R.id.textViewReceviedReferrals);
        mTxtvSentTab = (TextView) view.findViewById(R.id.textViewSentReferrals);
        mTxtvRecivedArchiveTab = (TextView) view.findViewById(R.id.textViewRecivedArchiveReferrals);
        mTxtvSendArchiveTab = (TextView) view.findViewById(R.id.textViewSendArchiveReferrals);

        mBtnDelete = (Button) view.findViewById(R.id.btnReferralsDelete);

        //mPager 					= (ViewPager) view.findViewById(R.id.referralsViewPager);


        initBottomUi(view);
        //Set the defult tab
        setDefultTab();

        //Set the view pager on adapter
        //	setViewPagerAdapter();

        //Set the tabs on page scroll
        //	setTab();

        //Set on click

        mLLReferralsTab.setOnClickListener(this);
        mLLSentTab.setOnClickListener(this);
        mLLRecivedArchiveTab.setOnClickListener(this);
        mLLSendArchiveTab.setOnClickListener(this);
    }

    private void initBottomUi(View view) {

        mLLBottomHomeTab = (LinearLayout) view.findViewById(R.id.llBottomHomeTab);
        mLLBottomSelectTab = (LinearLayout) view.findViewById(R.id.llBottomSelectAllTab);
        mLLBottomComposeTab = (LinearLayout) view.findViewById(R.id.llBottomComposeTab);
        mLLBottomMoreTab = (LinearLayout) view.findViewById(R.id.llBottomSearchTab);

        mTxtvBottomHomeTab = (TextView) view.findViewById(R.id.txtvBottomTabHome);
        mTxtvBottomSelectTab = (TextView) view.findViewById(R.id.txtvBottomTabSelect);
        mTxtvBottomComposeTab = (TextView) view.findViewById(R.id.txtvBottomTabCompose);
        mTxtvBottomMoreTab = (TextView) view.findViewById(R.id.txtvBottomTabSearch);

        mImagevBottomHomeTab = (ImageView) view.findViewById(R.id.imgvBottomTabHome);
        mImagevBottomSelectTab = (ImageView) view.findViewById(R.id.imgvBottomTabSelect);
        mImagevBottomComposeTab = (ImageView) view.findViewById(R.id.imgvBottomTabCompose);
        mImagevBottomMoreTab = (ImageView) view.findViewById(R.id.imgvBottomTabSearch);

        //Set on click

        mLLBottomSelectTab.setOnClickListener(this);
        mLLBottomComposeTab.setOnClickListener(this);
        mLLBottomMoreTab.setOnClickListener(this);
    }


    /*private void setViewPagerAdapter(){
        String[] TITLES = {getString(R.string.str_recevied), getString(R.string.str_sent), getString(R.string.str_send_archive), getString(R.string.str_recevied_archive)};

        adapter = new TabsPagerAdapter(getActivity().getSupportFragmentManager(), TITLES, mLLTopTabs,mLLBottomTabs, mBtnDelete, mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
        mPager.setAdapter(adapter);

    }*/
    private void setDefultTab() {
        mTxtvReferralsTab.setBackgroundResource(R.drawable.bg_tab);

        ReferralsReceivedFragment frag = ReferralsReceivedFragment.newInstance(mLLTopTabs, mLLBottomTabs, mBtnDelete, mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
        attachedFragement(frag);
    }


/*	private void setTab(){
        mPager.addOnPageChangeListener(new ViewPager.OnPageChangeListener() {
			@Override
			public void onPageScrolled(int position, float positionOffset, int positionOffsetPixels) {
			}

			@Override
			public void onPageSelected(int position) {
				if (position == 0) {
					setReceviedTabSelected();
					scrollToCenter(mLLReferralsTab);
				} else if (position == 1) {
					setSentTabSelected();
					scrollToCenter(mLLSentTab);
				} else if (position == 2) {
					scrollToCenter(mLLRecivedArchiveTab);
					setRecivedArchiveTabSelected();
				} else if (position == 3) {
					scrollToCenter(mLLSendArchiveTab);
					setSendArchiveTabSelected();
				}

			}

			@Override
			public void onPageScrollStateChanged(int state) {

			}
		});
	}*/

    @Override
    public void onDestroyView() {
        super.onDestroyView();
    }


    @Override
    public void onClick(View view) {
        if (view == mLLReferralsTab && !mStatusSearch) {
            //mPager.setCurrentItem(0);
            setReceviedTabSelected();
            scrollToCenter(view);

            ReferralsReceivedFragment frag = ReferralsReceivedFragment.newInstance(mLLTopTabs, mLLBottomTabs, mBtnDelete, mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
            attachedFragement(frag);
        } else if (view == mLLSentTab && !mStatusSearch) {
            //mPager.setCurrentItem(1);
            setSentTabSelected();
            scrollToCenter(view);

            ReferralsSentFragment frag = ReferralsSentFragment.newInstance(mLLTopTabs, mLLBottomTabs, mBtnDelete, mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
            attachedFragement(frag);

        } else if (view == mLLRecivedArchiveTab && !mStatusSearch) {
            //mPager.setCurrentItem(2);
            setRecivedArchiveTabSelected();
            scrollToCenter(view);

            ReferralsReceivedArchiveFragment frag = ReferralsReceivedArchiveFragment.newInstance(mLLTopTabs, mLLBottomTabs, mBtnDelete, mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
            attachedFragement(frag);

        } else if (view == mLLSendArchiveTab && !mStatusSearch) {
            //mPager.setCurrentItem(3);
            setSendArchiveTabSelected();
            scrollToCenter(view);

            ReferralsSentArchiveFragment frag = ReferralsSentArchiveFragment.newInstance(mLLTopTabs, mLLBottomTabs, mBtnDelete, mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
            attachedFragement(frag);

        } else if (view == mLLBottomSelectTab) {
            Toast.makeText(getActivity(), "mImagevBottomSelectTab", Toast.LENGTH_SHORT).show();
        } else if (view == mLLBottomComposeTab) {
            Intent intObj = new Intent(getActivity(), SendReferralsSelectMembersActivity.class);
            startActivity(intObj);
            getActivity().overridePendingTransition(0, 0);
        } else if (view == mLLBottomMoreTab) {
            Toast.makeText(getActivity(), "mImagevBottomSelectTab", Toast.LENGTH_SHORT).show();
        }

    }

    private void scrollToCenter(final View view) {
        mLLTopTabs.post(new Runnable() {

            @Override
            public void run() {


                int scrollTo = 0;
                final int count = ((LinearLayout) mLLTopTabs.getChildAt(0))
                        .getChildCount();
                for (int i = 0; i < count; i++) {
                    final View child = ((LinearLayout) mLLTopTabs.getChildAt(0))
                            .getChildAt(i);
                    if (child != view) {
                        scrollTo += child.getWidth() - 50;
                    } else {
                        break;
                    }
                }
                mLLTopTabs.scrollTo(scrollTo, 0);
            }

        });
    }

    //Code for show the recvied tabs selected and other un selected
    private void setReceviedTabSelected() {
        mTxtvReferralsTab.setBackgroundResource(R.drawable.bg_tab);
        mTxtvSentTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvRecivedArchiveTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvSendArchiveTab.setBackgroundColor(Color.parseColor("#00000000"));
    }

    //Code for show the sent tabs selected and other un selected
    private void setSentTabSelected() {
        mTxtvReferralsTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvSentTab.setBackgroundResource(R.drawable.bg_tab);
        mTxtvRecivedArchiveTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvSendArchiveTab.setBackgroundColor(Color.parseColor("#00000000"));
    }


    //Code for show the Archive tabs selected and other un selected
    private void setRecivedArchiveTabSelected() {
        mTxtvReferralsTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvSentTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvRecivedArchiveTab.setBackgroundResource(R.drawable.bg_tab);
        mTxtvSendArchiveTab.setBackgroundColor(Color.parseColor("#00000000"));
    }

    //Code for show the Archive tabs selected and other un selected
    private void setSendArchiveTabSelected() {
        mTxtvReferralsTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvSentTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvRecivedArchiveTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvSendArchiveTab.setBackgroundResource(R.drawable.bg_tab);
    }


    class OnSelectAllTabResponse implements TabSelectedCallBack {

        @Override
        public void selectAction(boolean select) {
            if (select) {
                mImagevBottomSelectTab.setImageResource(R.drawable.ic_uncheck_bottom);
                mTxtvBottomSelectTab.setTextColor(getActivity().getResources().getColor(R.color.bottom_tab_text_color_selected));
            } else {
                mImagevBottomSelectTab.setImageResource(R.drawable.ic_checked_b);
                mTxtvBottomSelectTab.setTextColor(getActivity().getResources().getColor(R.color.black_color));
            }
        }
    }

    public void attachedFragement(Fragment fragment) {
        if (fragment != null) {
            FragmentManager fragmentManager = getActivity().getSupportFragmentManager();
            fragmentManager.beginTransaction().replace(R.id.referralsViewPager, fragment).commit();
        }
    }


}
