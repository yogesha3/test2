/**
 * Fragments contains the Contacts Module
 * It includes partner also.
 */
package com.app.foxhopr.fragments.user;


import android.content.Context;
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
import com.app.foxhopr.fragments.user.Account.BillingFragment;
import com.app.foxhopr.fragments.user.Account.ProfileFragment;
import com.app.foxhopr.fragments.user.Account.SocialMediaFragment;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.foxhoper.app.R;

/**
 * A simple {@link Fragment} subclass.
 */
public class AccountFragment extends Fragment implements View.OnClickListener{
    private Context mContext;

    private TabsPagerAdapter adapter;

    private HorizontalScrollView mLLTopTabs;
    private LinearLayout mLLBottomTabs;

    private LinearLayout mLlContactsTab;
    private LinearLayout mLlPartnerTab;
    private LinearLayout mLlGoalTab;

    private TextView mTxtvContactTab;
    private TextView mTxtvPartnerTab;
    private TextView mTextViewGoalTab;


    private Button mBtnDelete;
    private Button mBtnReadUnRead;

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
    public AccountFragment() {
        // Required empty public constructor
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        mContext = getActivity();
        View view = inflater.inflate(R.layout.fragment_account, container, false);
        initView(view);

        return view;
    }

    /**
     * Initializing view
     * @param view
     */
    private void initView(View view) {
        mLLTopTabs = (HorizontalScrollView) view.findViewById(R.id.llReferralsTabs);
        mLLBottomTabs = (LinearLayout) view.findViewById(R.id.llReferralsBottomTabs);

        mLlContactsTab = (LinearLayout) view.findViewById(R.id.llContactsTab);
        mLlPartnerTab = (LinearLayout) view.findViewById(R.id.llPartnerTab);
        mLlGoalTab = (LinearLayout) view.findViewById(R.id.llGoalTab);

        mTxtvContactTab = (TextView) view.findViewById(R.id.textViewContactMessages);
        mTxtvPartnerTab = (TextView) view.findViewById(R.id.textViewPartner);
        mTextViewGoalTab = (TextView) view.findViewById(R.id.textViewGoal);


        mBtnDelete = (Button) view.findViewById(R.id.btnMessagesCompose);
        mBtnReadUnRead = (Button) view.findViewById(R.id.btnMessagesSentRefferral);

        initBottomUi(view);
        //Set the defult tab
        setDefultTab();

        mLlContactsTab.setOnClickListener(this);
        mLlPartnerTab.setOnClickListener(this);
        mLlGoalTab.setOnClickListener(this);
    }
    private void initBottomUi(View view){
        mLLBottomHomeTab = (LinearLayout) view.findViewById(R.id.llBottomHomeTab);
        mLLBottomSelectTab = (LinearLayout) view.findViewById(R.id.llBottomSelectAllTab);
        mLLBottomComposeTab = (LinearLayout) view.findViewById(R.id.llBottomComposeTab);
        //not using this tab so setting alfa
        mLLBottomComposeTab.findViewById(R.id.imgvBottomTabCompose).setAlpha(0.5f);
        mLLBottomMoreTab = (LinearLayout) view.findViewById(R.id.llBottomSearchTab);

        mTxtvBottomHomeTab = (TextView) view.findViewById(R.id.txtvBottomTabHome);
        mTxtvBottomSelectTab = (TextView) view.findViewById(R.id.txtvBottomTabSelect);
        mTxtvBottomComposeTab = (TextView) view.findViewById(R.id.txtvBottomTabCompose);
        mTxtvBottomMoreTab = (TextView) view.findViewById(R.id.txtvBottomTabSearch);

        mImagevBottomHomeTab = (ImageView) view.findViewById(R.id.imgvBottomTabHome);
        mImagevBottomSelectTab = (ImageView) view.findViewById(R.id.imgvBottomTabSelect);
        mImagevBottomComposeTab = (ImageView) view.findViewById(R.id.imgvBottomTabCompose);
        mImagevBottomMoreTab = (ImageView) view.findViewById(R.id.imgvBottomTabSearch);

        mLLBottomSelectTab.setOnClickListener(this);
        mLLBottomComposeTab.setOnClickListener(null);
        mLLBottomMoreTab.setOnClickListener(this);
    }
    private void setDefultTab() {
        mTxtvContactTab.setBackgroundResource(R.drawable.bg_tab);
        TextView textview = (TextView)getActivity().findViewById(R.id.textViewHeaderTitle);
        textview.setText(getResources().getString(R.string.str_profile_account));
        ProfileFragment frag = ProfileFragment.newInstance(mLLTopTabs, mLLBottomTabs, mBtnDelete,mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
        attachedFragement(frag);
    }

    public void attachedFragement(Fragment fragment) {
        if (fragment != null) {
            FragmentManager fragmentManager = getActivity().getSupportFragmentManager();
            fragmentManager.beginTransaction().replace(R.id.referralsViewPager, fragment).commit();
        }
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

    //Code for show the recvied tabs selected and other un selected
    private void setReceviedTabSelected() {
        mTxtvContactTab.setBackgroundResource(R.drawable.bg_tab);
        mTxtvPartnerTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTextViewGoalTab.setBackgroundColor(Color.parseColor("#00000000"));
    }

    //Code for show the sent tabs selected and other un selected
    private void setSentTabSelected() {
        mTxtvContactTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvPartnerTab.setBackgroundResource(R.drawable.bg_tab);
        mTextViewGoalTab.setBackgroundColor(Color.parseColor("#00000000"));

    }
    //Code for show the Archive tabs selected and other un selected
    private void setRecivedArchiveTabSelected() {
        mTxtvContactTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvPartnerTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTextViewGoalTab.setBackgroundResource(R.drawable.bg_tab);
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

    @Override
    public void onDestroyView() {
        super.onDestroyView();
    }


    @Override
    public void onClick(View view) {
        if (view == mLlContactsTab && !mStatusSearch) {
            //mPager.setCurrentItem(0);
            TextView textview = (TextView)getActivity().findViewById(R.id.textViewHeaderTitle);
            textview.setText(getResources().getString(R.string.str_profile_account));
            setReceviedTabSelected();
            scrollToCenter(view);

            ProfileFragment frag = ProfileFragment.newInstance(mLLTopTabs, mLLBottomTabs, mBtnDelete, mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
            attachedFragement(frag);
        } else if (view == mLlPartnerTab && !mStatusSearch) {
            //mPager.setCurrentItem(1);
            TextView textview = (TextView)getActivity().findViewById(R.id.textViewHeaderTitle);
            textview.setText(getResources().getString(R.string.str_billing_account));
            setSentTabSelected();
            scrollToCenter(view);

            BillingFragment frag = BillingFragment.newInstance(mLLTopTabs, mLLBottomTabs, mBtnDelete, mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
            attachedFragement(frag);

        }else if (view == mLlGoalTab && !mStatusSearch) {
            //mPager.setCurrentItem(1);
            TextView textview = (TextView)getActivity().findViewById(R.id.textViewHeaderTitle);
            textview.setText(getResources().getString(R.string.str_social_media));
            setRecivedArchiveTabSelected();
            scrollToCenter(view);

            SocialMediaFragment frag = SocialMediaFragment.newInstance(mLLTopTabs, mLLBottomTabs, mBtnDelete, mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
            attachedFragement(frag);

        } else if (view == mLLBottomSelectTab) {
            Toast.makeText(getActivity(), "mImagevBottomSelectTab", Toast.LENGTH_SHORT).show();
        } else if (view == mLLBottomComposeTab) {
            //Intent intObj = new Intent(getActivity(), ContactAddActivity.class);
            //startActivity(intObj);
            //getActivity().overridePendingTransition(0, 0);
        } else if (view == mLLBottomMoreTab) {
            Toast.makeText(getActivity(), "mImagevBottomSelectTab", Toast.LENGTH_SHORT).show();
        }
    }


}
