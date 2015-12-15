package com.app.foxhopr.ui.Account;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.util.Log;
import android.webkit.WebView;
import android.webkit.WebViewClient;

import com.app.foxhopr.constants.ApplicationConstants;
import com.foxhoper.app.R;

public class WebViewActivity extends FragmentActivity {
    private WebView webView;
    private String mStringSocial;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_webview_social);
        setView();
    }

    private void  setView(){
        String authenticationURL=getIntent().getStringExtra(ApplicationConstants.FORCONFIGURATION);
        mStringSocial=getIntent().getStringExtra(ApplicationConstants.CANCEL_SOCIAL_MEDIA);
        webView=(WebView)findViewById(R.id.socialWebView);
        webView.getSettings().setJavaScriptEnabled(true);
        //startWebView("http://103.231.222.21/foxhopr_testing/businessOwners/loginTwitter/9iZPcW0crX9fJivnkTQARpJPREfDmDV47TRIKMO1_Do");
        startWebView(authenticationURL);
    }
    private void startWebView(String url) {

        //Create new webview Client to show progress dialog
        //When opening a url or click on link

        webView.setWebViewClient(new WebViewClient() {
            ProgressDialog progressDialog;

            //If you will not use this method url links are opeen in new brower not in webview
            public boolean shouldOverrideUrlLoading(WebView view, String url) {
                //view.loadUrl(url);
                return false;
            }

            //Show loader on url load
            public void onLoadResource (WebView view, String url) {
                if (progressDialog == null) {
                    // in standard case YourActivity.this
                    progressDialog = new ProgressDialog(WebViewActivity.this);
                    progressDialog.setMessage("Loading...");
                    progressDialog.show();
                }
            }
            public void onPageFinished(WebView view, String url) {
                try{
                    Log.e("URL value", url + "");
                    progressDialog.dismiss();
                    if (progressDialog.isShowing()) {
                        progressDialog.dismiss();
                        progressDialog = null;
                    }
                    if(url.contains("foxhoprapplication://twitter")){
                        //Intent intent=new Intent(MainActivity.this,VideoActivity.class);
                        //startActivity(intent);
                        //finish();
                       /* Intent intent=new Intent(Intent.ACTION_VIEW, Uri.parse("foxhoprapplication://twitter"));
                        startActivity(intent);
                        finish();*/
                        Intent intent=new Intent(WebViewActivity.this,SocialDetailsActivity.class);
                        intent.putExtra(ApplicationConstants.FORCONFIGURATION,ApplicationConstants.TWITTER_VALUE);
                        intent.putExtra(ApplicationConstants.FORCONFIGURATION_ALERT,ApplicationConstants.TWITTER_TEXT);
                        startActivity(intent);
                        finish();
                    }if(url.contains("foxhoprapplication://alreadytwitter") ){
                        //Intent intent=new Intent(MainActivity.this,VideoActivity.class);
                        //startActivity(intent);
                        //finish();
                       /* Intent intent=new Intent(Intent.ACTION_VIEW, Uri.parse("foxhoprapplication://twitter"));
                        startActivity(intent);
                        finish();*/
                        Intent intent=new Intent(WebViewActivity.this,SocialDetailsActivity.class);
                        intent.putExtra(ApplicationConstants.FORCONFIGURATION,ApplicationConstants.TWITTER_VALUE);
                        intent.putExtra(ApplicationConstants.FORCONFIGURATION_ALERT,ApplicationConstants.FORCONFIGURATION_ALERT);
                        startActivity(intent);
                        finish();
                    }if(url.contains("foxhoprapplication://facebook") ){
                        //Intent intent=new Intent(MainActivity.this,VideoActivity.class);
                        //startActivity(intent);
                        //finish();
                        /*Intent intent=new Intent(Intent.ACTION_VIEW, Uri.parse("foxhoprapplication://facebook"));
                        startActivity(intent);
                        finish();*/
                        Intent intent=new Intent(WebViewActivity.this,SocialDetailsActivity.class);
                        intent.putExtra(ApplicationConstants.FORCONFIGURATION,ApplicationConstants.FACEBOOK_VALUE);
                        intent.putExtra(ApplicationConstants.FORCONFIGURATION_ALERT,ApplicationConstants.FACEBOOK_TEXT);
                        startActivity(intent);
                        finish();
                    }if(url.contains("foxhoprapplication://alreadyfacebook")){
                        //Intent intent=new Intent(MainActivity.this,VideoActivity.class);
                        //startActivity(intent);
                        //finish();
                        /*Intent intent=new Intent(Intent.ACTION_VIEW, Uri.parse("foxhoprapplication://facebook"));
                        startActivity(intent);
                        finish();*/
                        Intent intent=new Intent(WebViewActivity.this,SocialDetailsActivity.class);
                        intent.putExtra(ApplicationConstants.FORCONFIGURATION,ApplicationConstants.FACEBOOK_VALUE);
                        intent.putExtra(ApplicationConstants.FORCONFIGURATION_ALERT,ApplicationConstants.FORCONFIGURATION_ALERT);
                        startActivity(intent);
                        finish();
                    }if(url.contains("foxhoprapplication://linkedin") ){
                        //Intent intent=new Intent(MainActivity.this,VideoActivity.class);
                        //startActivity(intent);
                        //finish();
                        /*Intent intent=new Intent(Intent.ACTION_VIEW, Uri.parse("foxhoprapplication://linkedin"));
                        startActivity(intent);
                        finish();*/
                        Intent intent=new Intent(WebViewActivity.this,SocialDetailsActivity.class);
                        intent.putExtra(ApplicationConstants.FORCONFIGURATION,ApplicationConstants.LINKED_IN_VALUE);
                        intent.putExtra(ApplicationConstants.FORCONFIGURATION_ALERT,ApplicationConstants.LINKEDIN_TEXT);
                        startActivity(intent);
                        finish();
                    }if(url.contains("foxhoprapplication://alreadylinkedin")){
                        //Intent intent=new Intent(MainActivity.this,VideoActivity.class);
                        //startActivity(intent);
                        //finish();
                        /*Intent intent=new Intent(Intent.ACTION_VIEW, Uri.parse("foxhoprapplication://linkedin"));
                        startActivity(intent);
                        finish();*/
                        Intent intent=new Intent(WebViewActivity.this,SocialDetailsActivity.class);
                        intent.putExtra(ApplicationConstants.FORCONFIGURATION,ApplicationConstants.LINKED_IN_VALUE);
                        intent.putExtra(ApplicationConstants.FORCONFIGURATION_ALERT,ApplicationConstants.FORCONFIGURATION_ALERT);
                        startActivity(intent);
                        finish();
                    }if(url.contains("foxhoprapplication://cancel")){
                        //Intent intent=new Intent(MainActivity.this,VideoActivity.class);
                        //startActivity(intent);
                        //finish();
                        /*Intent intent=new Intent(Intent.ACTION_VIEW, Uri.parse("foxhoprapplication://linkedin"));
                        startActivity(intent);
                        finish();*/
                       /* Intent intent=new Intent(WebViewActivity.this,SocialDetailsActivity.class);
                        intent.putExtra(ApplicationConstants.FORCONFIGURATION,ApplicationConstants.LINKED_IN_VALUE);
                        startActivity(intent);*/
                        Intent intent = getIntent();
                        intent.putExtra(ApplicationConstants.CANCEL_SOCIAL_MEDIA, mStringSocial);
                        setResult(RESULT_OK, intent);
                        finish();
                    }
                }catch(Exception exception){
                    exception.printStackTrace();
                }
            }


        });

        // Javascript inabled on webview
        webView.getSettings().setJavaScriptEnabled(true);

        // Other webview options
        /*
        webView.getSettings().setLoadWithOverviewMode(true);
        webView.getSettings().setUseWideViewPort(true);
        webView.setScrollBarStyle(WebView.SCROLLBARS_OUTSIDE_OVERLAY);
        webView.setScrollbarFadingEnabled(false);
        webView.getSettings().setBuiltInZoomControls(true);
        */

        /*
         String summary = "<html><body>You scored <b>192</b> points.</body></html>";
         webview.loadData(summary, "text/html", null);
         */

        //Load url in webview
        webView.loadUrl(url);


    }

    // Open previous opened link from history on webview when back button pressed

    @Override
    // Detect when the back button is pressed
    public void onBackPressed() {
        if(webView.canGoBack()) {
            webView.goBack();
        } else {
            // Let the system handle the back button
            super.onBackPressed();
        }
    }

}