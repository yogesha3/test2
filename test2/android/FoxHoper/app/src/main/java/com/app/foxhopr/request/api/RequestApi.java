package com.app.foxhopr.request.api;

import com.app.foxhopr.request.models.ChangePasswordRequestModel;
import com.app.foxhopr.request.models.ContactAddRequestModel;
import com.app.foxhopr.request.models.ContactDetailsRequestModel;
import com.app.foxhopr.request.models.ContactEditRequestModel;
import com.app.foxhopr.request.models.CreditCardRequestModel;
import com.app.foxhopr.request.models.CurrentTeamRequestModel;
import com.app.foxhopr.request.models.ForgotPasswordRequestModel;
import com.app.foxhopr.request.models.GoalsRequestModel;
import com.app.foxhopr.request.models.GroupSelectionRequestModel;
import com.app.foxhopr.request.models.InboxMessageCommentModel;
import com.app.foxhopr.request.models.InvitePartnerRequestModel;
import com.app.foxhopr.request.models.LoginRequestModel;
import com.app.foxhopr.request.models.MemberListRequestModel;
import com.app.foxhopr.request.models.MessageInboxRequestModel;
import com.app.foxhopr.request.models.MessageReadUnreadRequestModel;
import com.app.foxhopr.request.models.MessagesDetailsRequestModel;
import com.app.foxhopr.request.models.NotificationRequestModel;
import com.app.foxhopr.request.models.ReceivedReferralCommentModel;
import com.app.foxhopr.request.models.ReceivedReferralDeleteRequestModel;
import com.app.foxhopr.request.models.ReceivedReferralEditModel;
import com.app.foxhopr.request.models.ReceivedReferralsRequestModel;
import com.app.foxhopr.request.models.ReceivedReferralsSearchRequestModel;
import com.app.foxhopr.request.models.ReferralsDetailsRequestModel;
import com.app.foxhopr.request.models.RevokeRequestModel;
import com.app.foxhopr.request.models.SelectedGroupRequestModel;
import com.app.foxhopr.request.models.SocialListRequestModel;
import com.app.foxhopr.request.models.StateListRequestModel;
import com.app.foxhopr.request.models.SuggestionsRequestModel;
import com.app.foxhopr.request.models.TeamDetailsRequestModel;
import com.app.foxhopr.request.models.TrainingVideoRightsModel;
import com.app.foxhopr.request.models.WebCastCommentModel;
import com.app.foxhopr.request.models.WebcastListRequestModel;

import retrofit.Callback;
import retrofit.client.Response;
import retrofit.http.Body;
import retrofit.http.GET;
import retrofit.http.POST;

public interface RequestApi {

	@POST("/")
	public void getLogin(@Body LoginRequestModel mLoginRequestModel, Callback<Response> response);

	@POST("/")
	public void getForgotpasswordRequest(@Body ForgotPasswordRequestModel mForgotPasswordRequestModel, Callback<Response> response);

	@POST("/")
	public void getReferralsListRequest(@Body ReceivedReferralsRequestModel mReceivedReferralsRequestModel, Callback<Response> response);

	@POST("/")
	public void getMembersListRequest(@Body MemberListRequestModel mMemberListRequestModel, Callback<Response> response);

	@POST("/")
	public void getCountryListRequest( Callback<Response> response);

	@POST("/")
	public void getStateListRequest(@Body StateListRequestModel mStateListRequestModel, Callback<Response> response);

	@POST("/")
	public void getReferralsListSearchRequest(@Body ReceivedReferralsSearchRequestModel mReceivedReferralsSearchRequestModel, Callback<Response> response);

	@POST("/")
	public void deleteReferralsList(@Body ReceivedReferralDeleteRequestModel mReceivedReferralsRequestModel, Callback<Response> response);

	@POST("/")
	public void getReferralsSentListRequest(@Body ReceivedReferralsRequestModel mReceivedReferralsRequestModel, Callback<Response> response);

	@GET("/")
	public void getNotificationRequest(Callback<Response> response);

	@POST("/")
	public void getReferralsReceivedDetailsRequest(@Body ReferralsDetailsRequestModel mReceivedReferralsRequestModel, Callback<Response> response);

	@POST("/")
	public void ReferralsAddCommentRequest(@Body ReceivedReferralCommentModel mReceivedReferralsRequestModel, Callback<Response> response);

	@GET("/")
	public void getUserProfileRequest(Callback<Response> response);

	@GET("/")
	public void getCleanNotification(Callback<Response> response);

	@POST("/")
	public void getMessageInboxListRequest(@Body MessageInboxRequestModel mMessageInboxRequestModel, Callback<Response> response);

	@POST("/")
	public void editReferral(@Body ReceivedReferralEditModel mReceivedReferralEditModel, Callback<Response> response);

	@POST("/")
	public void getMessagessReceivedDetailsRequest(@Body MessagesDetailsRequestModel mMessagesDetailsRequestModel, Callback<Response> response);

	@POST("/")
	public void MessagesAddCommentRequest(@Body InboxMessageCommentModel mInboxMessageCommentModel, Callback<Response> response);

	@POST("/")
	public void MessageReadUnreadList(@Body MessageReadUnreadRequestModel mMessageReadUnreadRequestModel, Callback<Response> response);

	@POST("/")
	public void addNewContact(@Body ContactAddRequestModel mContactAddRequestModel, Callback<Response> response);

	@POST("/")
	public void getContactListRequest(@Body MessageInboxRequestModel mMessageInboxRequestModel, Callback<Response> response);
	@POST("/")
	public void getTeamListRequest(@Body CurrentTeamRequestModel mCurrentTeamRequestModel, Callback<Response> response);

	@POST("/")
	public void getContactDetailsRequest(@Body ContactDetailsRequestModel mContactDetailsRequestModel, Callback<Response> response);

	@POST("/")
	public void editContact(@Body ContactEditRequestModel mContactEditRequestModel, Callback<Response> response);
	@POST("/")
	public void invitePartner(@Body InvitePartnerRequestModel mInvitePartnerRequestModel, Callback<Response> response);

	@POST("/")
	public void getTeamDetailsRequest(@Body TeamDetailsRequestModel mTeamDetailsRequestModel, Callback<Response> response);

	@POST("/")
	public void getGroupSelectionListRequest(@Body GroupSelectionRequestModel mGroupSelectionRequestModel, Callback<Response> response);

	@POST("/")
	public void getSelectedGroup(@Body SelectedGroupRequestModel mSelectedGroupRequestModel, Callback<Response> response);

	@POST("/")
	public void getNotification(@Body NotificationRequestModel mNotificationRequestModel, Callback<Response> response);

	@POST("/")
	public void changePassword(@Body ChangePasswordRequestModel mChangePasswordRequestModel, Callback<Response> response);

	@GET("/")
	public void getBillingInfo(Callback<Response> response);

	@GET("/")
	public void getPurchaseReceipt(Callback<Response> response);

	@GET("/")
	public void getGoalInfo(Callback<Response> response);

	@POST("/")
	public void updateGoalInfo(@Body GoalsRequestModel mGoalsRequestModel,Callback<Response> response);

	@POST("/")
	public void updateCreditCardInfo(@Body CreditCardRequestModel mCreditCardRequestModel,Callback<Response> response);

	@POST("/")
	public void postSuggestions(@Body SuggestionsRequestModel mSuggestionsRequestModel, Callback<Response> response);

	@POST("/")
	public void revokeAccess(@Body RevokeRequestModel mRevokeRequestModel, Callback<Response> response);

	@POST("/")
	public void SocialMediaList(@Body SocialListRequestModel mSocialListRequestModel, Callback<Response> response);

	@POST("/")
	public void getWebCast(@Body WebcastListRequestModel mWebcastListRequestModel, Callback<Response> response);

	@POST("/")
	public void getWebCastComment(@Body WebCastCommentModel mWebCastCommentModel, Callback<Response> response);

	@POST("/")
	public void addWebCastComment(@Body WebCastCommentModel mWebCastCommentModel, Callback<Response> response);

	@GET("/")
	public void getTrainingVideo(Callback<Response> response);

	@POST("/")
	public void updateRightsForTrainingVideo(@Body TrainingVideoRightsModel mTrainingVideoRightsModel, Callback<Response> response);

}

