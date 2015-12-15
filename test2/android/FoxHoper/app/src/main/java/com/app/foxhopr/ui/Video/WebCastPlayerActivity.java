/**
 * WebCastPlayerActivity to play webcast videos
 *
 */
package com.app.foxhopr.ui.Video;

import android.app.Fragment;
import android.app.FragmentManager;
import android.content.Context;
import android.graphics.Color;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.app.foxhopr.adapter.TabsPagerAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.fragments.user.Videos.NowPlayingFragment;
import com.app.foxhopr.fragments.user.Videos.UpNextFragment;
import com.app.foxhopr.fragments.user.Videos.WebCastCommentFragment;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.WebCastSelectedCallBack;
import com.app.foxhopr.webservice.models.TeamMebmersListInnerModel;
import com.app.foxhopr.webservice.models.WebCastListInnerModel;
import com.foxhoper.app.R;
import com.google.android.youtube.player.YouTubeBaseActivity;
import com.google.android.youtube.player.YouTubeInitializationResult;
import com.google.android.youtube.player.YouTubePlayer;
import com.google.android.youtube.player.YouTubePlayerView;

import java.util.ArrayList;

public class WebCastPlayerActivity extends YouTubeBaseActivity implements View.OnClickListener,YouTubePlayer.OnInitializedListener {
    private static String TAG="WebCastPlayerActivity";
    private Context mContext;

    private TabsPagerAdapter adapter;

    private HorizontalScrollView mLLTopTabs;

    private LinearLayout mLlContactsTab;
    private LinearLayout mLlPartnerTab;
    private LinearLayout mLlGoalTab;

    private TextView mTxtvContactTab;
    private TextView mTxtvPartnerTab;
    private TextView mTextViewGoalTab;
    public static boolean mStatusSearch=false;

    private WebCastListInnerModel mWebCastListInnerModel;
    private ImageView mBackButton;
    private TextView mTxtvSendReferrals;
    private YouTubePlayerView youtube_player;
    private YouTubePlayer mYoutubePlayer;


    private ArrayList<TeamMebmersListInnerModel> getSelectedMemberListData;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.fragment_webcast_player);
        initView();
    }

    private void initView(){
        mWebCastListInnerModel=(WebCastListInnerModel) getIntent().getSerializableExtra("details");

        mBackButton=(ImageView)findViewById(R.id.imgvDrawer);
        mBackButton.setOnClickListener(this);

        mTxtvSendReferrals	=	(TextView)findViewById(R.id.textViewHeaderTitle);
        mTxtvSendReferrals.setVisibility(View.VISIBLE);
        mTxtvSendReferrals.setText(getResources().getString(R.string.str_webcast));

        youtube_player=(YouTubePlayerView)findViewById(R.id.youtube_player);

        mLLTopTabs = (HorizontalScrollView) findViewById(R.id.llReferralsTabs);

        mLlContactsTab = (LinearLayout) findViewById(R.id.llContactsTab);
        mLlPartnerTab = (LinearLayout) findViewById(R.id.llPartnerTab);
        mLlGoalTab = (LinearLayout) findViewById(R.id.llGoalTab);

        mTxtvContactTab = (TextView) findViewById(R.id.textViewContactMessages);
        mTxtvPartnerTab = (TextView) findViewById(R.id.textViewPartner);
        mTextViewGoalTab = (TextView) findViewById(R.id.textViewGoal);
        setDefultTab();
        mLlContactsTab.setOnClickListener(this);
        mLlPartnerTab.setOnClickListener(this);
        mLlGoalTab.setOnClickListener(this);

        /** Initializing YouTube player view **/
        youtube_player.initialize(ApplicationConstants.YOUTUBE_KEY, WebCastPlayerActivity.this);

    }



    private void setDefultTab() {
        mTxtvContactTab.setBackgroundResource(R.drawable.bg_tab);
        TextView textview = (TextView)findViewById(R.id.textViewHeaderTitle);
        textview.setText(getResources().getString(R.string.str_webcast));
        NowPlayingFragment frag = NowPlayingFragment.newInstance(mLLTopTabs, mWebCastListInnerModel, new OnSelectAllTabResponse());
        attachedFragement(frag);
    }

    public void attachedFragement(Fragment fragment) {
        if (fragment != null) {
            FragmentManager fragmentManager = getFragmentManager();//getSupportFragmentManager();
            fragmentManager.beginTransaction().replace(R.id.referralsViewPager, fragment).commit();
        }
    }

    @Override
    public void onInitializationSuccess(YouTubePlayer.Provider provider, YouTubePlayer youTubePlayer, boolean b) {

        if (!b) {
            //assigning youtubeplayer to global variable
            if(null!=youTubePlayer && null!=mWebCastListInnerModel) {
                mYoutubePlayer = youTubePlayer;
                //Log.e(TAG, AppUtils.getYouTubeId(mWebCastListInnerModel.getLink()));
                mYoutubePlayer.loadVideo(AppUtils.getYouTubeId(mWebCastListInnerModel.getLink()));
            }
        }
        //youTubePlayer.setPlayerStyle(YouTubePlayer.PlayerStyle.CHROMELESS);
        if (null!=mYoutubePlayer && !youTubePlayer.isPlaying())
            mYoutubePlayer.play();
    }

    @Override
    public void onInitializationFailure(YouTubePlayer.Provider provider, YouTubeInitializationResult youTubeInitializationResult) {

    }

    class OnSelectAllTabResponse implements WebCastSelectedCallBack {

        @Override
        public void selectVideo(WebCastListInnerModel selectedVideo) {
            Log.e(TAG, selectedVideo.getLink());
            mWebCastListInnerModel=selectedVideo;
            if(null!=mYoutubePlayer){
                mYoutubePlayer.loadVideo(AppUtils.getYouTubeId(mWebCastListInnerModel.getLink()));
            }
            //youtube_player.initialize(ApplicationConstants.YOUTUBE_KEY, WebCastPlayerActivity.this);
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
    @Override
    public void onClick(View view) {
        if (view == mLlContactsTab && !mStatusSearch) {
            //mPager.setCurrentItem(0);
            TextView textview = (TextView)findViewById(R.id.textViewHeaderTitle);
            textview.setText(getResources().getString(R.string.str_webcast));
            setReceviedTabSelected();
            scrollToCenter(view);

            NowPlayingFragment frag = NowPlayingFragment.newInstance(mLLTopTabs, mWebCastListInnerModel, new OnSelectAllTabResponse());
            attachedFragement(frag);
        } else if (view == mLlPartnerTab && !mStatusSearch) {
            //mPager.setCurrentItem(1);
            TextView textview = (TextView)findViewById(R.id.textViewHeaderTitle);
            textview.setText(getResources().getString(R.string.str_webcast));
            setSentTabSelected();
            scrollToCenter(view);

            UpNextFragment frag = UpNextFragment.newInstance(mLLTopTabs, mWebCastListInnerModel, new OnSelectAllTabResponse());
            attachedFragement(frag);

        }else if (view == mLlGoalTab && !mStatusSearch) {
            //mPager.setCurrentItem(1);
            TextView textview = (TextView)findViewById(R.id.textViewHeaderTitle);
            textview.setText(getResources().getString(R.string.str_webcast));
            setRecivedArchiveTabSelected();
            scrollToCenter(view);

            WebCastCommentFragment frag = WebCastCommentFragment.newInstance(mLLTopTabs, mWebCastListInnerModel, new OnSelectAllTabResponse());
            attachedFragement(frag);
        }else if(view == mBackButton){
            finish();
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

    @Override
    protected void onStop() {
        super.onStop();
        if(null!=mYoutubePlayer) {
            mYoutubePlayer.release();
        }
    }
}
