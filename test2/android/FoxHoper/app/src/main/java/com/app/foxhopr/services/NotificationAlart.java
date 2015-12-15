package com.app.foxhopr.services;

import android.app.AlarmManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.os.Build;
import android.os.SystemClock;
import android.util.Log;

import java.util.Calendar;

/**
 * Created by Chobey R. on 17/8/15.
 */
public class NotificationAlart {
    private static PendingIntent resetAlarm;
    private static String TAG="NotificationAlart";
    private static AlarmManager am;
    public static void start(Context context) {
        try {
            // We want the alarm to go off 30 seconds from now.
            long firstTime = SystemClock.elapsedRealtime();

            // Create an IntentSender that will launch our service, to be scheduled with the alarm manager.
            resetAlarm = PendingIntent.getService(context, 0, new Intent(context, NotificationService.class), 0);

            // Schedule the alarm!
            am = (AlarmManager) context.getSystemService(Context.ALARM_SERVICE);

            if(Build.VERSION.SDK_INT < Build.VERSION_CODES.KITKAT){
                am.setRepeating(AlarmManager.ELAPSED_REALTIME_WAKEUP, firstTime, 1000*5, resetAlarm);
            }else{
                Log.v(TAG, "calling else");
                Calendar calendar = Calendar.getInstance();
                calendar.setTimeInMillis(System.currentTimeMillis());
                calendar.add(Calendar.SECOND, 5);
                am.set(AlarmManager.ELAPSED_REALTIME_WAKEUP, calendar.getTimeInMillis(), resetAlarm);
               // stop(context);
            }
        }


        catch (Exception e) {
            Log.v(TAG, "Exception while start the at: " + e.getMessage());
        }
    }

    public static void stop(Context context) {
        try {
            // When interval going to change from web services
            Log.i(TAG, "stopped alarm");
            am.cancel(resetAlarm);

        }
        catch (Exception e) {
            Log.v(TAG, "Exception while start the NotificationAlarm at: " + e.getMessage());
        }
    }


}