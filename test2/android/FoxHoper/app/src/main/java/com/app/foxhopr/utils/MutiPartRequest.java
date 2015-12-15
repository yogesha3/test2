package com.app.foxhopr.utils;

import android.content.Context;
import android.util.Log;

import com.app.foxhopr.SharedPrefrances.SharedPreference;
import com.app.foxhopr.constants.WebServiceConstants;
import com.app.foxhopr.request.WebServiceUtils;
import com.google.android.gcm.GCMRegistrar;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.ByteArrayBody;
import org.apache.http.entity.mime.content.StringBody;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.nio.charset.Charset;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map.Entry;


public class MutiPartRequest {

	public static String postImage(ArrayList<File> files, HashMap<String, String> params, Context context, String controlName, String modelName) {
		byte[] data = null;
		String response_str = "";
		SharedPreference saveSharedPreference = new SharedPreference();
		try {
			String url = WebServiceConstants.sWEBSERVICE_URL;
			HttpClient httpclient = new DefaultHttpClient();
			HttpPost httppost = new HttpPost(url);
			Charset chars = Charset.forName("UTF-8");
			MultipartEntity entity = new MultipartEntity();

			for(int i=0; i <files.size(); i++){
				if (files.get(i) != null) {
					ByteArrayOutputStream bos = new ByteArrayOutputStream();
					data =convertFileToByteArray(files.get(i));
					Long tsLong = System.currentTimeMillis() / 1000;
					String ts = tsLong.toString();
					entity.addPart("fileupload"+i, new ByteArrayBody(data, "*/*", ""+files.get(i).getName()));
				}
			}

			for (Entry<String, String> mapEntry : params.entrySet()) {
				String key = mapEntry.getKey();
				String value = mapEntry.getValue();
				String blankValue =" ";
				//Log.e(key, value);

				if(null!=value && value.length() >0){
					entity.addPart(key, new StringBody(value, chars));
				}else{
					entity.addPart(key, new StringBody(blankValue));
				}
			}

			httppost.setEntity(entity);
			httppost.setHeader("HASHKEY", WebServiceConstants.sAPP_HASHKEY);
			httppost.setHeader("CONTROL",controlName);
			httppost.setHeader("METHOD", modelName);
			httppost.setHeader("DeviceToken", GCMRegistrar.getRegistrationId(context));
			httppost.setHeader("DeviceId", WebServiceUtils.getMacAddress(context));
			httppost.setHeader("DeviceType", WebServiceConstants.DEVICE_TYPE);
			httppost.setHeader("UserId", saveSharedPreference.getUserId(context));
			HttpResponse resp = httpclient.execute(httppost);
			HttpEntity resEntity = resp.getEntity();
			response_str = EntityUtils.toString(resEntity);
			return response_str;
		} catch (ClientProtocolException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		return response_str;
	}

	public static String postImageProfile(ArrayList<File> files, HashMap<String, String> params, Context context, String controlName, String modelName) {
		byte[] data = null;
		String response_str = "";
		SharedPreference saveSharedPreference = new SharedPreference();
		try {
			String url = WebServiceConstants.sWEBSERVICE_URL;
			HttpClient httpclient = new DefaultHttpClient();
			HttpPost httppost = new HttpPost(url);
			Charset chars = Charset.forName("UTF-8");
			MultipartEntity entity = new MultipartEntity();

			for(int i=0; i <files.size(); i++){
				if (files.get(i) != null) {
					ByteArrayOutputStream bos = new ByteArrayOutputStream();
					data =convertFileToByteArray(files.get(i));
					Long tsLong = System.currentTimeMillis() / 1000;
					String ts = tsLong.toString();
					entity.addPart("fileUpload"+i, new ByteArrayBody(data, "*/*", ""+files.get(i).getName()));
				}
			}

			for (Entry<String, String> mapEntry : params.entrySet()) {
				String key = mapEntry.getKey();
				String value = mapEntry.getValue();
				String blankValue ="";
				Log.e(key, value);

				if(value.length() >0){
					entity.addPart(key, new StringBody(value, chars));
				}else{
					entity.addPart(key, new StringBody(blankValue));
				}



			}

			httppost.setEntity(entity);
			httppost.setHeader("HASHKEY", WebServiceConstants.sAPP_HASHKEY);
			httppost.setHeader("CONTROL",controlName);
			httppost.setHeader("METHOD", modelName);
			httppost.setHeader("DeviceToken", GCMRegistrar.getRegistrationId(context));
			httppost.setHeader("DeviceId", WebServiceUtils.getMacAddress(context));
			httppost.setHeader("DeviceType", WebServiceConstants.DEVICE_TYPE);
			httppost.setHeader("UserId", saveSharedPreference.getUserId(context));
			HttpResponse resp = httpclient.execute(httppost);
			HttpEntity resEntity = resp.getEntity();
			response_str = EntityUtils.toString(resEntity);
			return response_str;
		} catch (ClientProtocolException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		return response_str;
	}

	public static byte[] convertFileToByteArray(File f)
	{
		byte[] byteArray = null;
		try
		{
			InputStream inputStream = new FileInputStream(f);
			ByteArrayOutputStream bos = new ByteArrayOutputStream();
			byte[] b = new byte[1024*8];
			int bytesRead =0;

			while ((bytesRead = inputStream.read(b)) != -1)
			{
				bos.write(b, 0, bytesRead);
			}

			byteArray = bos.toByteArray();
		}
		catch (IOException e)
		{
			e.printStackTrace();
		}
		return byteArray;
	}
}
