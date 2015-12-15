package com.app.foxhopr.constants;

/**
 * Name: WebServiceConstants
 *
 * Description:This class use to define all the constants that is used in webservice calling.
 *
 * Date: 20/july/2015
 *
 * @author kamalkant
 */

public class WebServiceConstants {
    //Header value
    public static String sAPP_HASHKEY                               =   "bf1f135e2cf4440ddd4b4c209d9bb319206a406a";
    public static String DEVICE_TYPE                                =   "android";

    //Url used when web service call and the basic url used for server access.

   // public static String sWEBSERVICE_URL                            ="http://10.10.11.152/foxhopr/api/";


    //Testing Url
    //public static String sWEBSERVICE_URL                            ="http://103.231.222.21/foxhopr_testing/api";

    //Testing Url demo
    //public static String sWEBSERVICE_URL                            = "http://demotest.a3logics.com/foxhopr_testing/api";


    //Live Url
    //public static String sWEBSERVICE_URL                            ="http://103.231.222.21/foxhopr/api";
    public static String sWEBSERVICE_URL                            = "http://demotest.a3logics.com/foxhopr/api";
    /*Twitter Login Testing*/
    /*public static String TWITTER_LOGIN="http://demotest.a3logics.com/foxhopr_testing/businessOwners/loginTwitter/";
    public static String FACEBOOK_LOGIN="http://demotest.a3logics.com/foxhopr_testing/businessOwners/fbLogin/";
    public static String LINKEDIN_LOGIN="http://demotest.a3logics.com/foxhopr_testing/businessOwners/linkedInLogin/";*/


    /*Twitter Login Live*/
    public static String TWITTER_LOGIN="http://demotest.a3logics.com/foxhopr/businessOwners/loginTwitter/";
    public static String FACEBOOK_LOGIN="http://demotest.a3logics.com/foxhopr/businessOwners/fbLogin/";
    public static String LINKEDIN_LOGIN="http://demotest.a3logics.com/foxhopr/businessOwners/linkedInLogin/";


    /*Twitter Login Live*/
    /*public static String TWITTER_LOGIN="http://demotest.a3logics.com/foxhopr_testing/businessOwners/linkedInLogin/";
    public static String FACEBOOK_LOGIN="http://demotest.a3logics.com/foxhopr_testing/businessOwners/fbLogin/";
    public static String LINKEDIN_LOGIN="http://demotest.a3logics.com/foxhopr_testing/businessOwners/loginTwitter/";*/

    //Login Model
    public static String LOGIN_ACTION_NAME                          =   "login";
    public static String LOGIN_CONTROL_NAME                         =   "users";


    //Forgot password
    public static String FORGOT_PASSWORD_ACTION_NAME                =   "forgotPassword";
    public static String FORGOT_PASSWORD_CONTROL_NAME               =   "users";

    //Referrals Listing

    //Referrals Recived List
    public static String REFERRALS_ACTION_NAME                      =   "getReferral";
    public static String REFERRALS_CONTROL_NAME                     =   "referrals";
    public static String PAGE_NO                                    =   "page_no";
    public static String RECORD_PER_PAGE                            =   "record_per_page";
    public static String RECEVIED_LIST_PAGE                         =   "received";
    public static String REFERRALS_NAME                             =   "referral_name";
    public static String STATUS                                     =   "status";


    //Members Listing
    public static String TEAMMEMBER_ACTION_NAME                     =   "teamMemberList";
    public static String PREVIOUSMEMBER_ACTION_NAME                 =   "previousMemberList";
    public static String TEAMMEMBER_CONTROL_NAME                    =   "users";

    //Country Model
    public static String COUNTRY_ACTION_NAME                        =   "listAllCountries";
    public static String COUNTRY_CONTROL_NAME                       =   "users";

    //State Model
    public static String STATE_ACTION_NAME                          =   "listStateList";
    public static String STATE_CONTROL_NAME                         =   "users";


   //Delete Model
   public static String DELETE_ACTION_NAME                          =   "deleteReferral";
   public static String DELETE_CONTROL_NAME                         =   "referrals";

   public static String SENT_LIST_PAGE                              =   "sent";

    //CONTROL: app
    //METHOD: getUnreadCount

    //Notification Model
    public static String NOTIFICATION_ACTION_NAME                   =   "getUnreadCount";
    public static String NOTIFICATION_CONTROL_NAME                  =   "app";


    public static String SENDREFERRAL_ACTION_NAME                         =   "sendReferral";
    public static String RECEIVED_REFERREL_DETAILSL_ACTION_NAME           =   "referralDetail";
    public static String RECEIVED_REFERREL_COMMENT_ACTION_NAME           =   "referralComment";
    public static String RECEIVED_REFERREL_ADD_COMMENT_ACTION_NAME           =   "addReferralComment";

    //profile
    public static String PROFILE_ACTION_NAME           =   "profileDetail";

    //archiveSent
    public static String SENT_ARCHIVE_LIST_PAGE                   =   "archiveSent";

    //archiveReceived
    public static String RECEIVED_ARCHIVE_LIST_PAGE               =   "archiveReceived";

    /*Logout*/
    public static String LOGOUT_ACTION_NAME                          =   "logout";

    //total counter checked read
    public static String COUNTER_CHECKED_ACTION_NAME                   =   "readTotal";
    public static String MESSAGE_COMPOSE_ACTION_NAME                   =   "composeMessage";
    public static String MESSAGE_COMPOSE_CONTROL                   =   "messages";
    public static String MESSAGE_GET_ACTION_NAME                   =   "getMessage";


    public static String INBOX_LIST_PAGE                        =   "inbox";
    public static String INBOX_ARCHIVE_LIST_PAGE                =   "inboxArchive";
    public static String SENT_ARCHIVE_LIST_PAGE_1               =   "sentArchive";
    public static String COUNTER_EDIT_REFERRAL_ACTION_NAME                   =   "editReferral";

    public static String MESSAGES_DETAILS_ACTION_NAME                   =   "messageDetail";
    public static String MESSAGES_COMMENT_ACTION_NAME                   =   "messageComment";
    public static String MESSAGES_ADD_COMMENT_ACTION_NAME                   =   "addMessageComment";

    //Delete Inbox Model
    public static String DELETE_INBOX_ACTION_NAME                          =   "deleteMessage";

    public static String MESSAGE_READ_UNREAD_ACTION_NAME                          =   "changeMessageStatus";
    public static String MESSAGE_READ                          =   "read";
    public static String MESSAGE_UNREAD                          =   "unread";

  //Contact
  public static String ADD_CONTACT_ACTION_NAME                          =   "addContact";
  public static String GET_CONTACT_ACTION_NAME                          =   "getContactList";
  public static String DELETE_CONTACT_ACTION_NAME                       =   "deleteContact";
  public static String CONTACT_DETAILS_ACTION_NAME                       =   "contactDetail";
  public static String CONTACT_EDIT_ACTION_NAME                         =   "editContact";
  public static String INVITE_PARTNER_ACTION_NAME                         =   "invitePartners";
  public static String GET_PARTNER_LIST_ACTION_NAME                         =   "getPartnersList";
  public static String ADD_CONTACT_CONTROL_NAME                         =   "contacts";

  //team

    public static String GET_TEAM_ACTION_NAME                         =   "getTeamList";
    public static String GET_TEAM_CONTROL_NAME                         =   "teams";
    public static String CURRENT_TEAM_LIST_PAGE                         =   "current";
    public static String PREVIOUS_TEAM_LIST_PAGE                         =   "previous";
    public static String TEAM_DETAILS_ACTION_NAME                         =   "memberDetail";

    //Members Listing
    public static String CONTACT_MEMBER_ACTION_NAME                     =   "contactList";

    /*GroupSelection*/
    public static String GET_GROUP_ACTION_NAME                         =   "getGroups";
    public static String GET_GROUP_CONTROL_NAME                         =   "groups";
    public static String SELECT_GROUP_CONTROL_NAME                         =   "selectGroup";
    public static String GROUP_LIST_PAGE                         =   "groupSelect";

    /*notification*/
    public static String GET_NOTIFICATION_CONTROL_NAME                         =   "businessOwners";
    public static String GET_NOTIFICATION_ACTION_NAME                         =   "changeNotifications";
    public static String GET_NOTIFICATION_LIST_PAGE                         =   "getNotif";
    public static String GET_NOTIFICATION_EDIT_LIST_PAGE                         =   "editNotif";
    public static String CHANGE_PASSWORD_ACTION_NAME                         =   "changePassword";
    public static String EDIT_PROFILE_ACTION_NAME                         =   "editProfile";

    /*Billing*/
    public static String GET_BILLING_CONTROL_NAME                         =   "users";
    public static String GET_BILLING_ACTION_NAME                         =   "billing";
    public static String GET_BILLING_CANCEL_ACTION_NAME                  =   "cancelMembership";

    /*update creditcard*/
    public static String GET_CC_CONTROL_NAME                         =   "businessOwners";
    public static String GET_CC_ACTION_NAME                         =   "updateCreditCard";

    /*receipts*/
    public static String GET_RECEIPTS_CONTROL_NAME                         =   "businessOwners";
    public static String GET_RECEIPTS_ACTION_NAME                         =   "receipts";

    /*Goals*/
    public static String GET_GOALS_CONTROL_NAME                         =   "teams";
    public static String GET_GOALS_ACTION_NAME                         =   "goals";
    public static String EDIT_GOALS_ACTION_NAME                         =   "edit";

    /*Suggestions*/
    public static String GET_SUGGESTION_CONTROL_NAME                         =   "suggestions";
    public static String GET_SUGGESTION_ACTION_NAME                         =   "addSuggestion";

    /*Social Media*/
    public static String GET_SOCIAL_MEDIA_ACTION_NAME                         =   "social";
    public static String GET_SOCIAL_REVOKE_ACTION_NAME                         =   "socialRevokeAccess";

    /*webcast*/
    public static String GET_WEB_CAST_LIST_ACTION_NAME                         =   "webcast";
    public static String GET_WEB_CAST_LIST_CONTROL_NAME                         =   "events";

    public static String GET_WEB_CAST_ADD_COMMENT_ACTION_NAME                         =   "webcastAddComment";
    public static String GET_WEB_CAST_GET_COMMENT_ACTION_NAME                         =   "webcastCommentDetail";

    /*Training Video*/
    public static String GET_TRAINING_VIDEO_ACTION_NAME                         =   "trainingVideo";

}
