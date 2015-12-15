package com.app.foxhopr.request;

import android.content.Context;
import android.telephony.TelephonyManager;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.constants.WebServiceConstants;
import com.google.android.gcm.GCMRegistrar;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.util.UUID;

import retrofit.RequestInterceptor;
import retrofit.client.Response;

/**
 * Created by a3logics on 30/07/15.
 */
public class WebServiceUtils {

    public static String getResponseString(Response responseModel){
        String result ="";

        BufferedReader reader = null;
        StringBuilder sb = new StringBuilder();
        try {

            reader = new BufferedReader(new InputStreamReader(responseModel.getBody().in()));

            String line;

            try {
                while ((line = reader.readLine()) != null) {
                    sb.append(line);
                }
            } catch (IOException e) {
                e.printStackTrace();
            }

            result  = sb.toString();

        } catch (IOException e) {
            e.printStackTrace();
        }
        return  result;
    }


    public static RequestInterceptor getRequestInterceptor(final String modelName, final String controlName, final Context mCtx){

        RequestInterceptor requestInterceptor = new RequestInterceptor() {
            @Override
            public void intercept(RequestFacade request) {
                SharedPreference saveSharedPreference = new SharedPreference();
                String id=GCMRegistrar.getRegistrationId(mCtx);
                request.addHeader("Content-Type", "application/json");
                request.addHeader("HASHKEY", WebServiceConstants.sAPP_HASHKEY);
                request.addHeader("CONTROL",controlName);
                request.addHeader("METHOD", modelName);
                request.addHeader("DeviceToken", id);
                request.addHeader("DeviceId", WebServiceUtils.getMacAddress(mCtx));
                request.addHeader("DeviceType", WebServiceConstants.DEVICE_TYPE);


                if(!saveSharedPreference.getUserId(mCtx).equalsIgnoreCase("")){
                    request.addHeader("UserId", saveSharedPreference.getUserId(mCtx));
                    request.addHeader("groupId", saveSharedPreference.getGroupId(mCtx));
                }
            }
        };

        return  requestInterceptor;
    }

    public static String getMacAddress(Context mCtx){
        String sDeviceId ="";
        try {
            final TelephonyManager tm = (TelephonyManager) mCtx.getSystemService(Context.TELEPHONY_SERVICE);

            final String tmDevice, tmSerial, androidId;
            tmDevice = "" + tm.getDeviceId();
            tmSerial = "" + tm.getSimSerialNumber();
            androidId = "" + android.provider.Settings.Secure.getString(mCtx.getContentResolver(), android.provider.Settings.Secure.ANDROID_ID);

            UUID deviceUuid = new UUID(androidId.hashCode(), ((long) tmDevice.hashCode() << 32) | tmSerial.hashCode());
            sDeviceId = deviceUuid.toString();
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return sDeviceId;
    }

}
