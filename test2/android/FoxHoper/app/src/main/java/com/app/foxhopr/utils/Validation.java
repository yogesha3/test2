package com.app.foxhopr.utils;

import android.annotation.SuppressLint;
import android.content.Context;
import android.view.View;
import android.widget.EditText;

import com.foxhoper.app.R;

import java.util.regex.Pattern;

public class Validation {

  // Regular Expression
  // you can change the expression based on your need
  private static final String EMAIL_REGEX = "^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$";
  private static final String PHONE_REGEX = "\\d{3}-\\d{7}";

  // Error Messages
  private static final String REQUIRED_MSG = "required";
  private static final String EMAIL_MSG = "invalid email";
  private static final String PHONE_MSG = "###-#######";

  private static final String JOB_TITLE_REGEX = "[a-zA-Z0-9\\.\\-\\@\\& ]*";
  private static final String SUB_TITLE_REGEX = "[a-zA-Z0-9\\.\\- ]*";
  private static final String NAME_REGEX = "[a-zA-Z\\.\\-\\@\\& ]*";
  private static final String Address_REGEX = "[a-zA-Z0-9\\.\\-\\, ]*";
  private static final String VALID_ADDRESS_REGEX = "[a-zA-Z0-9\\.\\-\\,\\(\\) ]*";

  private static final String ALFA_REGEX = "[a-zA-Z0-9\\-\\, ]*";
  private static final String ZIP_ALFA_REGEX = "[a-zA-Z0-9\\.\\-\\ ]*";

/*  zip ------        /^[A-Za-z0-9 \- . ]+$/;
  address ---------          /^[A-Za-z0-9 \- . , ( ) ]+$/;*/

  // call this method when you need to check email validation
  public static boolean isEmailAddress(Context mCtx, EditText editText, View view, boolean required) {
    return isValid(mCtx, editText, view, EMAIL_REGEX, EMAIL_MSG, required);
  }

  // call this method when you need to check email validation
  public static boolean isEmailAddressCorrect(Context mCtx, EditText editText, View view, boolean required) {
    return isValidCorrect(mCtx, editText, view, EMAIL_REGEX, EMAIL_MSG, required);
  }

  // call this method when you need to check phone number validation
  public static boolean isPhoneNumber(Context mCtx, EditText editText, View view, boolean required) {
    return isValid(mCtx, editText, view, PHONE_REGEX, PHONE_MSG, required);
  }

  // return true if the input field is valid, based on the parameter passed
  @SuppressLint("NewApi")
  public static boolean isValid(Context mCtx, EditText editText, View view, String regex, String errMsg, boolean required) {

    String text = editText.getText().toString().trim();
    // clearing the error, if it was previously set by some other values
    editText.setError(null);

    // text required and editText is blank, so return false
    if (required && !hasText(mCtx, editText, view))
      return false;

    // pattern doesn't match so returning false
    if (required && !Pattern.matches(regex, text)) {
      view.setBackgroundResource(R.drawable.bg_input_error);

      return false;
    } else {
      view.setBackgroundResource(R.drawable.bg_input);
    };

    return true;
  }
  // return true if the input field is valid, based on the parameter passed
  @SuppressLint("NewApi")
  public static boolean isValidCorrect(Context mCtx, EditText editText, View view, String regex, String errMsg, boolean required) {

    String text = editText.getText().toString().trim();
    // clearing the error, if it was previously set by some other values
    editText.setError(null);

    // text required and editText is blank, so return false
    if (required && !hasTextEmail(mCtx, editText, view))
      return false;

    // pattern doesn't match so returning false
    if (required && !Pattern.matches(regex, text)) {
      view.setBackgroundResource(R.drawable.bg_select_team_error);

      return false;
    } else {
      view.setBackgroundResource(R.drawable.bg_select_team);
    };
    return true;
  }
  // return true if the input field is valid, based on the parameter passed
  @SuppressLint("NewApi")
  public static boolean isValidEmail(String strEmail) {

    // pattern doesn't match so returning false
    if (!Pattern.matches(EMAIL_REGEX, strEmail)) {
      return false;
    }
    return true;
  }
  // check the input field has any text or not
  // return true if it contains text otherwise false
  @SuppressLint("NewApi")
  public static boolean hasText(Context mCtx, EditText editText, View view) {

    String text = editText.getText().toString().trim();
    editText.setError(null);

    // length 0 means there is no text
    if (text.length() == 0) {
      view.setBackgroundResource(R.drawable.bg_input_error);
      return false;
    } else {
      view.setBackgroundResource(R.drawable.bg_input);
    }

    return true;
  }

  @SuppressLint("NewApi")
  public static boolean hasTextEmail(Context mCtx, EditText editText, View view) {

    String text = editText.getText().toString().trim();
    editText.setError(null);

    // length 0 means there is no text
    if (text.length() == 0) {
      view.setBackgroundResource(R.drawable.bg_select_team_error);
      return false;
    } else {
      view.setBackgroundResource(R.drawable.bg_input);
    }

    return true;
  }


  // check the input field has any text or not
  // return true if it contains text otherwise false
  @SuppressLint("NewApi")
  public static boolean hasInputPassword(Context mCtx, EditText editText, View view) {

    String text = editText.getText().toString().trim();
    editText.setError(null);

    // length 0 means there is no text

      view.setBackgroundResource(R.drawable.bg_select_team_error);
      return false;

  }


  // check the input field has any text or not
  // return true if it contains text otherwise false
  @SuppressLint("NewApi")
  public static boolean hasInputValid(Context mCtx, EditText editText, View view) {

    String text = editText.getText().toString().trim();
    editText.setError(null);

    // length 0 means there is no text
    if (text.length() == 0) {
      view.setBackgroundResource(R.drawable.bg_select_team_error);
      return false;
    } else {
      view.setBackgroundResource(R.drawable.bg_select_team);
    }

    return true;
  }

  // check the input field has any text or not
  // return true if it contains text otherwise false
  @SuppressLint("NewApi")
  public static boolean hasInputPassworodNew(Context mCtx, EditText editText, View view) {

    String text = editText.getText().toString().trim();
    editText.setError(null);

    // length 0 means there is no text
    if (text.length() > 0) {
      view.setBackgroundResource(R.drawable.bg_select_team);
      return false;
    } else {
      view.setBackgroundResource(R.drawable.bg_select_team);
    }

    return true;
  }

  // check the input field has any text or not
  // return true if it contains text otherwise false
  @SuppressLint("NewApi")
  public static boolean hasInput(Context mCtx, EditText editText, View view) {

    String text = editText.getText().toString().trim();
    editText.setError(null);

    // length 0 means there is no text
    if (text.length() == 0) {
      view.setBackgroundResource(R.drawable.bg_select_team);
      return false;
    } else {
      view.setBackgroundResource(R.drawable.bg_select_team);
    }

    return true;
  }
  // check the input field has any text or not
  // return true if it contains text otherwise false
  @SuppressLint("NewApi")
  public static boolean hasInputValidComment(Context mCtx, EditText editText, View view) {

    String text = editText.getText().toString().trim();
    editText.setError(null);

    // length 0 means there is no text
    if (text.length() == 0) {
      view.setBackgroundResource(R.drawable.bg_white_corner_gray_input);
      return false;
    } else {
      view.setBackgroundResource(R.drawable.bg_white_corner_gray_input);
    }

    return true;
  }


  // check the input field has any text or not
  // return true if it contains text otherwise false
  @SuppressLint("NewApi")
  public static boolean hasInputValidMonetory(Context mCtx, EditText editText, View view) {

    String text = editText.getText().toString().trim();
    editText.setError(null);

    // length 0 means there is no text
    if (text.length() == 0) {
      view.setBackgroundResource(R.drawable.bg_select_team);
      return false;
    } else {
      view.setBackgroundResource(R.drawable.bg_select_team);
    }

    return true;
  }

  public static boolean isValidString(String strName){
    if(Pattern.matches(NAME_REGEX, strName))
      return true;
    else
      return false;
  }

  public static boolean isValidJobTitle(String strName){
    if(Pattern.matches(JOB_TITLE_REGEX, strName))
      return true;
    else
      return false;
  }

  public static boolean isValidAddress(String strAddress){
    if(Pattern.matches(Address_REGEX, strAddress))
      return true;
    else
      return false;
  }
  public static boolean isValidAddressFormat(String strAddress){
    if(Pattern.matches(VALID_ADDRESS_REGEX, strAddress))
      return true;
    else
      return false;
  }
  public static boolean isValidSubjectFormat(String strAddress){
    if(Pattern.matches(SUB_TITLE_REGEX, strAddress))
      return true;
    else
      return false;
  }
  public static boolean isValidAlfanumaric(String strAlfaNumaric){
    if(Pattern.matches(ALFA_REGEX, strAlfaNumaric))
      return true;
    else
      return false;
  }
  public static boolean isValidZip(String strAlfaNumaric){
    if(Pattern.matches(ZIP_ALFA_REGEX, strAlfaNumaric))
      return true;
    else
      return false;
  }
}