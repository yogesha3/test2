/**
 * Fragment contains the Message module
 */
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
import com.app.foxhopr.fragments.user.Messages.MessageInboxArchiveFragment;
import com.app.foxhopr.fragments.user.Messages.MessageInboxFragment;
import com.app.foxhopr.fragments.user.Messages.MessageSentArchiveFragment;
import com.app.foxhopr.fragments.user.Messages.MessageSentFragment;
import com.app.foxhopr.ui.messages.MessageComposeActivity;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.foxhoper.app.R;


public class MessageFragment extends Fragment implements View.OnClickListener {

    private Context mContext;
    //private ViewPager mPager;
    private TabsPagerAdapter adapter;

    private HorizontalScrollView mLLTopTabs;
    private LinearLayout mLLBottomTabs;

    private LinearLayout mLlMessageInboxTab;
    private LinearLayout mLlMessageSendTab;
    private LinearLayout mLlMessageInboxArchivedTab;
    private LinearLayout mLlMessageSendArchivedTab;

    private TextView mTxtvInboxTab;
    private TextView mTxtvSendTab;
    private TextView mTxtvInboxArchiveTab;
    private TextView mTxtvSendArchiveTab;


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
    public MessageFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        mContext = getActivity();
        View view = inflater.inflate(R.layout.fragment_message, container, false);
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

        mLlMessageInboxTab = (LinearLayout) view.findViewById(R.id.llMessageInboxTab);
        mLlMessageSendTab = (LinearLayout) view.findViewById(R.id.llMessageSendTab);
        mLlMessageInboxArchivedTab = (LinearLayout) view.findViewById(R.id.llMessageInboxArchivedTab);
        mLlMessageSendArchivedTab = (LinearLayout) view.findViewById(R.id.llMessageSendArchivedTab);

        mTxtvInboxTab = (TextView) view.findViewById(R.id.textViewInboxMessages);
        mTxtvSendTab = (TextView) view.findViewById(R.id.textViewSendMessage);
        mTxtvInboxArchiveTab = (TextView) view.findViewById(R.id.textViewInboxArchiveMessages);
        mTxtvSendArchiveTab = (TextView) view.findViewById(R.id.textViewSendArchiveMessages);

        mBtnDelete = (Button) view.findViewById(R.id.btnMessagesDelete);
        mBtnReadUnRead = (Button) view.findViewById(R.id.btnMessagesReadUnRead);

        initBottomUi(view);
        //Set the defult tab
        setDefultTab();

        mLlMessageInboxTab.setOnClickListener(this);
        mLlMessageSendTab.setOnClickListener(this);
        mLlMessageInboxArchivedTab.setOnClickListener(this);
        mLlMessageSendArchivedTab.setOnClickListener(this);
    }
    private void initBottomUi(View view){
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

        mLLBottomSelectTab.setOnClickListener(this);
        mLLBottomComposeTab.setOnClickListener(this);
        mLLBottomMoreTab.setOnClickListener(this);
    }

    private void setDefultTab() {
        mTxtvInboxTab.setBackgroundResource(R.drawable.bg_tab);

        MessageInboxFragment frag = MessageInboxFragment.newInstance(mLLTopTabs, mLLBottomTabs, mBtnDelete,mBtnReadUnRead, mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
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
        mTxtvInboxTab.setBackgroundResource(R.drawable.bg_tab);
        mTxtvSendTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvInboxArchiveTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvSendArchiveTab.setBackgroundColor(Color.parseColor("#00000000"));
    }

    //Code for show the sent tabs selected and other un selected
    private void setSentTabSelected() {
        mTxtvInboxTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvSendTab.setBackgroundResource(R.drawable.bg_tab);
        mTxtvInboxArchiveTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvSendArchiveTab.setBackgroundColor(Color.parseColor("#00000000"));
    }


    //Code for show the Archive tabs selected and other un selected
    private void setRecivedArchiveTabSelected() {
        mTxtvInboxTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvSendTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvInboxArchiveTab.setBackgroundResource(R.drawable.bg_tab);
        mTxtvSendArchiveTab.setBackgroundColor(Color.parseColor("#00000000"));
    }

    //Code for show the Archive tabs selected and other un selected
    private void setSendArchiveTabSelected() {
        mTxtvInboxTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvSendTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvInboxArchiveTab.setBackgroundColor(Color.parseColor("#00000000"));
        mTxtvSendArchiveTab.setBackgroundResource(R.drawable.bg_tab);
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
        if (view == mLlMessageInboxTab && !mStatusSearch) {
            //mPager.setCurrentItem(0);
            setReceviedTabSelected();
            scrollToCenter(view);

            MessageInboxFragment frag = MessageInboxFragment.newInstance(mLLTopTabs, mLLBottomTabs, mBtnDelete,mBtnReadUnRead, mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
            attachedFragement(frag);
        } else if (view == mLlMessageSendTab && !mStatusSearch) {
            //mPager.setCurrentItem(1);
            setSentTabSelected();
            scrollToCenter(view);

            MessageSentFragment frag = MessageSentFragment.newInstance(mLLTopTabs, mLLBottomTabs, mBtnDelete,mBtnReadUnRead, mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
            attachedFragement(frag);

        } else if (view == mLlMessageInboxArchivedTab && !mStatusSearch) {
            //mPager.setCurrentItem(2);
            setRecivedArchiveTabSelected();
            scrollToCenter(view);

            MessageInboxArchiveFragment frag = MessageInboxArchiveFragment.newInstance(mLLTopTabs, mLLBottomTabs, mBtnDelete,mBtnReadUnRead, mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
            attachedFragement(frag);

        } else if (view == mLlMessageSendArchivedTab && !mStatusSearch) {
            //mPager.setCurrentItem(3);
            setSendArchiveTabSelected();
            scrollToCenter(view);

            MessageSentArchiveFragment frag = MessageSentArchiveFragment.newInstance(mLLTopTabs, mLLBottomTabs, mBtnDelete,mBtnReadUnRead, mLLBottomSelectTab, mLLBottomMoreTab, new OnSelectAllTabResponse());
            attachedFragement(frag);

        } else if (view == mLLBottomSelectTab) {
            Toast.makeText(getActivity(), "mImagevBottomSelectTab", Toast.LENGTH_SHORT).show();
        } else if (view == mLLBottomComposeTab) {
            Intent intObj = new Intent(getActivity(), MessageComposeActivity.class);
            startActivity(intObj);
            getActivity().overridePendingTransition(0, 0);
        } else if (view == mLLBottomMoreTab) {
            Toast.makeText(getActivity(), "mImagevBottomSelectTab", Toast.LENGTH_SHORT).show();
        }

    }

}
