/**
 * Fragment using to watch traingin video
 * there will be a delay where video will be pause automatically
 * to me a leader it is mandatory to watch all video and update the role for user as Group Leader
 */
package com.app.foxhopr.fragments.user.Videos;


import android.content.Context;
import android.media.MediaPlayer;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.MediaController;
import android.widget.VideoView;

import com.app.foxhopr.adapter.CurrentTeamListAdapter;
import com.app.foxhopr.constants.ApplicationConstants;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.app.foxhopr.request.api.RequestApi;
import com.app.foxhopr.request.models.TrainingVideoRightsModel;
import com.app.foxhopr.utils.AlertCallBack;
import com.app.foxhopr.utils.AppUtils;
import com.app.foxhopr.utils.ErrorMsgDialog;
import com.app.foxhopr.utils.ProgressHUD;
import com.app.foxhopr.utils.TabSelectedCallBack;
import com.app.foxhopr.webservice.models.TrainingVideoResponseModel;
import com.foxhoper.app.R;
import com.google.gson.Gson;

import java.util.concurrent.TimeUnit;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;

/**
 * A simple {@link Fragment} subclass.
 */
public class TrainingVideoFragment extends Fragment  implements View.OnClickListener{
    private static String TAG = "TrainingVideoFragment";
    private CurrentTeamListAdapter mCurrentTeamListAdapter;
    private Context mContext;
    private HorizontalScrollView mLlTopTabs;
    private LinearLayout mLlBottomTabs;
    private Button mBtnDelete;
    private Button mBtnReadUnRead;
    private LinearLayout mLLBottomSelectTab;
    private LinearLayout mLLBottomMoreTab;
    private TabSelectedCallBack mTabSelectedCallBack;

    private ProgressHUD mProgressHUD;
    private boolean mWebserviceStatus = true;

    private ImageView mPlayPause;
    private VideoView mTrainingVideoView;

    private TrainingVideoResponseModel getResponse;
    private MediaController mediacontroller=null;
    private int mVideoDuration;
    private int mProgressVideo=0;
    private View view;
    private int mCurrentPosition;
    private MediaPlayer mMediaPlayer;
    private long delay=TimeUnit.SECONDS.toMillis(5l);;


    public TrainingVideoFragment() {
        // Required empty public constructor
    }
    public static TrainingVideoFragment newInstance(HorizontalScrollView mLlTopTabs, LinearLayout mLlBottomTabs, Button mBtnDelete,Button mBtnReadUnRead,
                                                  LinearLayout lLBottomSelectTab, LinearLayout lLBottomMoreTab, TabSelectedCallBack mTabSelectedCallBack) {
        TrainingVideoFragment mCurrentTeamFragment = new TrainingVideoFragment();
        mCurrentTeamFragment.mLlTopTabs = mLlTopTabs;
        mCurrentTeamFragment.mLlBottomTabs = mLlBottomTabs;
        mCurrentTeamFragment.mBtnDelete = mBtnDelete;
        mCurrentTeamFragment.mBtnReadUnRead = mBtnReadUnRead;
        mCurrentTeamFragment.mLLBottomSelectTab = lLBottomSelectTab;
        mCurrentTeamFragment.mLLBottomMoreTab = lLBottomMoreTab;
        mCurrentTeamFragment.mTabSelectedCallBack = mTabSelectedCallBack;

        return mCurrentTeamFragment;
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        mContext = getActivity();
        View view = inflater.inflate(R.layout.fragment_training_video, container, false);
        this.view=view;
        //initView(view);
        return view;
    }

    /**
     * Initializing view
     * @param view
     */
    private void initView(View view) {
        mPlayPause=(ImageView)view.findViewById(R.id.playpauseView);
        mTrainingVideoView=(VideoView)view.findViewById(R.id.trainingVideoView);
        mPlayPause.setVisibility(View.GONE);
        //listener
        mPlayPause.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mPlayPause.setVisibility(View.GONE);
                //int position = mTrainingVideoView.getCurrentPosition();
                //Log.e(TAG, mCurrentPosition + "");
                //mTrainingVideoView.seekTo(mCurrentPosition);
                //mMediaPlayer.start();
                mTrainingVideoView.start();
                boolean b=callStatus_handler.postDelayed(runnable,delay);
                Log.e("boolean after click",b+"");
            }
        });


        try{
            if (AppUtils.isOnline(getActivity())) {
                mProgressHUD = ProgressHUD.show(getActivity(),"", true,true);
                mProgressHUD.setCancelable(false);
                callWebserviceTrainingVideo();
            }
            else{
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
            }
        }catch (Exception ex){
            ex.printStackTrace();
        }
    }

    /**
     * THis method is using to get the training video url from server
     * provide  successful  response or  error from server
     */
    private void callWebserviceTrainingVideo() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_TRAINING_VIDEO_ACTION_NAME, WebServiceConstants.GET_RECEIPTS_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.getTrainingVideo(new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        parseTrainingVideo(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    /**
     * After watching all video user role will be updated on server
     */
    private void callWebserviceRightsForTeamLead() throws Exception {
        //setting timeout for reading data as well as for connecting with client

        RestAdapter restAdapter = new RestAdapter.Builder().setEndpoint(WebServiceConstants.sWEBSERVICE_URL).setRequestInterceptor(WebServiceUtils.getRequestInterceptor(WebServiceConstants.GET_TRAINING_VIDEO_ACTION_NAME, WebServiceConstants.GET_RECEIPTS_CONTROL_NAME, getActivity())).build();
        RequestApi requestApi = restAdapter.create(RequestApi.class);

        requestApi.updateRightsForTrainingVideo(getRequestModel(), new Callback<Response>() {
            @Override
            public void success(Response responseModel, Response response) {
                // Try to get response body
                mProgressHUD.dismiss();
                try {
                    if (responseModel != null) {
                        Log.e(TAG, WebServiceUtils.getResponseString(responseModel));
                        //parseTrainingVideo(WebServiceUtils.getResponseString(responseModel));
                    } else {
                        ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                }
            }

            @Override
            public void failure(RetrofitError error) {
                error.printStackTrace();
                mProgressHUD.dismiss();
                ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_server_error));
            }
        });
    }

    //get request model
    private TrainingVideoRightsModel getRequestModel(){
        TrainingVideoRightsModel mTrainingVideoRightsModel =new TrainingVideoRightsModel();
        mTrainingVideoRightsModel.setTrainingVideo(ApplicationConstants.WATCHED);
        return mTrainingVideoRightsModel;
    }

    /**
     * parse trainging video getting from server
     *
     * @param responseStr
     * @throws Exception
     */

    private void parseTrainingVideo(String responseStr) throws Exception {
        Log.e(TAG, responseStr);
        Gson gson = new Gson();
        TrainingVideoResponseModel get_Response = gson.fromJson(responseStr, TrainingVideoResponseModel.class);

        if (get_Response != null) {
            getResponse = get_Response;

            if (get_Response.getCode().equals(ApplicationConstants.SUCCESS_CODE)) {
                if (get_Response.getResult() != null) {
                    BufferVideo();
                }
            } else {
                //ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", get_Response.getMessage().equals("")? getString(R.string.wrng_str_no_record): get_Response.getMessage());
            }
        } else {
            //ErrorMsgDialog.showErrorAlert(TeamDetailsActivity.this, "", getString(R.string.wrng_str_server_error));
        }
    }

    /**
     * Initialize the video view
     * video will be start automatically
     *
     */
    private void  BufferVideo(){
        if (mediacontroller == null) {
            //mPlayButton.setText("Play");
            // Create a progressbar
            mProgressHUD = ProgressHUD.show(getActivity(),"", true,true);
            mProgressHUD.setCancelable(false);
            // Set progressbar title
            //mProgressHUD.setTitle("Android Video Streaming Tutorial");
            // Set progressbar message
            //mProgressHUD.setMessage("Buffering...");
            // Show progressbar
            mProgressHUD.show();

            try {
                // Start the MediaController
                mediacontroller = new MediaController(getActivity());
                mediacontroller.setAnchorView(mTrainingVideoView);
                // Get the URL from String VideoURL
                Uri video = Uri.parse(getResponse.getResult());
                //Uri video = Uri.parse("http://www.androidbegin.com/tutorial/AndroidCommercial.3gp");
                mTrainingVideoView.setMediaController(mediacontroller);
                mediacontroller.setVisibility(View.GONE);
                mTrainingVideoView.setVideoURI(video);

            } catch (Exception e) {
                Log.e("Error", e.getMessage());
                e.printStackTrace();
            }

            mTrainingVideoView.requestFocus();
            mTrainingVideoView.setOnPreparedListener(new MediaPlayer.OnPreparedListener() {
                // Close the progress bar and play the video
                public void onPrepared(MediaPlayer mp) {
                    mProgressHUD.dismiss();
                    mVideoDuration = mTrainingVideoView.getDuration();
                    Log.e("Duration", mVideoDuration + "");
                    //mProgressVideo += 5000;
                    //mTrainingVideoView.start();
                    //mMediaPlayer=mp;
                    //mMediaPlayer.start();
                    mTrainingVideoView.start();

                   boolean b= callStatus_handler.postDelayed(runnable, delay);
                    Log.e("boolean start click", b + "");


                }
            });
            mTrainingVideoView.setOnCompletionListener(new MediaPlayer.OnCompletionListener() {
                @Override
                public void onCompletion(MediaPlayer mp) {
                    //mPlayButton.setText("Finish");
                    //videoview.stopPlayback();
                    mPlayPause.setVisibility(View.GONE);
                    callStatus_handler.removeCallbacks(runnable);
                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.str_thanks_message), new UpdateRightsForTeamLead());
                }
            });
        }
    }

    //After completion of video user will update the role on server
    public class UpdateRightsForTeamLead implements AlertCallBack {

        @Override
        public void alertAction(boolean select) {
            try{
                if (AppUtils.isOnline(getActivity())) {
                    mProgressHUD = ProgressHUD.show(getActivity(),"", true,true);
                    mProgressHUD.setCancelable(false);
                    callWebserviceRightsForTeamLead();
                }
                else{
                    ErrorMsgDialog.showErrorAlert(getActivity(), "", getString(R.string.wrng_str_lost_internet_error));
                }
            }catch (Exception ex){
                ex.printStackTrace();
            }
        }
    }
    @Override
    public void onClick(View view) {

    }

    /**
     *Handler managing video view after each provided interval
     */
    Handler callStatus_handler = new Handler();
    Runnable runnable = new Runnable() {

        @Override
        public void run() {
            /*if(mProgressVideo>=mVideoDuration){
                mPlayPause.setVisibility(View.VISIBLE);
            }else{
                mProgressVideo+=5000;
                mMediaPlayer.pause();
                mCurrentPosition = mMediaPlayer.getCurrentPosition();
                mPlayPause.setVisibility(View.VISIBLE);
            }*/
            //mMediaPlayer.pause();
            mTrainingVideoView.pause();
            //mCurrentPosition = mTrainingVideoView.getCurrentPosition();
            mPlayPause.setVisibility(View.VISIBLE);

        }
    };

    public void onPause() {
        super.onPause();
        if (callStatus_handler != null)
            callStatus_handler.removeCallbacks(runnable);
    }

}
