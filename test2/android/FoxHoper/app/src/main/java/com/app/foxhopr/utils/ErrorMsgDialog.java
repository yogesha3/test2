package com.app.foxhopr.utils;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;

import com.foxhoper.app.R;

import java.util.Locale;

public class ErrorMsgDialog {

	public static void showErrorAlert(Context mAct, String title, String message) {

		new AlertDialog.Builder(mAct)
				.setTitle(title)
				.setMessage(message)
				.setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
					@Override
					public void onClick(DialogInterface dialog, int which) {
						// continue with delete
					}
				}).setCancelable(false).show();

	}

	public static void alertOkButtonCallBack(Context mAct, String title, String message, final AlertCallBack alerAction) {

		try {
			final AlertDialog alertDialog = new AlertDialog.Builder(mAct).create();
			alertDialog.setTitle(title);
			if (message != null) {
				String outputMessage = message.substring(0, 1).toUpperCase(Locale.ENGLISH) + message.substring(1).toLowerCase(Locale.ENGLISH);
				alertDialog.setMessage(outputMessage);
				alertDialog.setCancelable(false);
				alertDialog.setCanceledOnTouchOutside(false);

				alertDialog.setButton(DialogInterface.BUTTON_NEGATIVE, mAct.getResources().getString(R.string.ok), new DialogInterface.OnClickListener() {
					@Override
					public void onClick(DialogInterface dialog, int which) {

						alerAction.alertAction(true);
					}
				});
				alertDialog.show();
			}

		} catch (Exception ex) {
			ex.printStackTrace();
		}

	}

	public static void alertOkButtonCallBackNoChange(Context mAct, String title, String message, final AlertCallBack alerAction) {

		try {
			final AlertDialog alertDialog = new AlertDialog.Builder(mAct).create();
			alertDialog.setTitle(title);
			if (message != null) {
				//String outputMessage = message.substring(0, 1).toUpperCase(Locale.ENGLISH) + message.substring(1).toLowerCase(Locale.ENGLISH);
				alertDialog.setMessage(message);
				alertDialog.setCancelable(false);
				alertDialog.setCanceledOnTouchOutside(false);

				alertDialog.setButton(DialogInterface.BUTTON_NEGATIVE, mAct.getResources().getString(R.string.ok), new DialogInterface.OnClickListener() {
					@Override
					public void onClick(DialogInterface dialog, int which) {

						alerAction.alertAction(true);
					}
				});
				alertDialog.show();
			}

		} catch (Exception ex) {
			ex.printStackTrace();
		}

	}
	public static void alertOkCancelButtonCallBack(Context mAct, String title, String message, final AlertCallBack alerAction) {

		try {
			final AlertDialog alertDialog = new AlertDialog.Builder(mAct).create();
			alertDialog.setTitle(title);
			if (message != null) {
				String outputMessage = message.substring(0, 1).toUpperCase(Locale.ENGLISH) + message.substring(1).toLowerCase(Locale.ENGLISH);
				alertDialog.setMessage(outputMessage);
				alertDialog.setCancelable(false);
				alertDialog.setCanceledOnTouchOutside(false);


				alertDialog.setButton(DialogInterface.BUTTON_POSITIVE, mAct.getResources().getString(R.string.ok), new DialogInterface.OnClickListener() {
					@Override
					public void onClick(DialogInterface dialog, int which) {

						alerAction.alertAction(true);
					}
				});
				alertDialog.setButton(DialogInterface.BUTTON_NEGATIVE, mAct.getResources().getString(R.string.cancel), new DialogInterface.OnClickListener() {
					@Override
					public void onClick(DialogInterface dialog, int which) {

					}
				});
				alertDialog.show();
			}

		} catch (Exception ex) {
			ex.printStackTrace();
		}

	}

	/**
	 * Method for callback doing after somting on pressing ok
	 * @param mAct
	 * @param title
	 * @param message
	 * @param alerAction
	 */
	public static void showErrorAlert(Context mAct, String title, String message, final AlertCallBack alerAction) {

		new AlertDialog.Builder(mAct)
				.setTitle(title)
				.setMessage(message)
				.setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
					@Override
					public void onClick(DialogInterface dialog, int which) {
						alerAction.alertAction(true);
					}
				}).setCancelable(false).show();

	}
}
