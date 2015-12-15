//
//  ReferralsDetailViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 8/14/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "UIPlaceHolderTextView.h"
#import <MessageUI/MessageUI.h>
#import "EditReffralsViewController.h"
@protocol referralDetailDelegate <NSObject>

-(void)updateStatusReadUnread;
-(void)updateStatusReadUnreadGetUpdateFromServer;
-(void)updateReceivedReferralFromEditReferral;

@end

@interface ReferralsDetailViewController : UIViewController <MFMailComposeViewControllerDelegate,editReffralsDelegate>  {

    __weak IBOutlet UIButton *btnEdit;
    __weak IBOutlet TPKeyboardAvoidingScrollView *scrollView;

    __weak IBOutlet UILabel *lblName;
    __weak IBOutlet UILabel *lblsubName;

    __weak IBOutlet UIView *viewDetail;
    __weak IBOutlet UIImageView *imgMail;
    __weak IBOutlet UILabel *lblMail;
    __weak IBOutlet UIImageView *imgAddress;
    __weak IBOutlet UITextView *textViewAdress;
    __weak IBOutlet UIImageView *imgWebSite;
    __weak IBOutlet UILabel *lblWebSite;
    __weak IBOutlet UIImageView *imgMobile;
    __weak IBOutlet UILabel *lblMobile;
    __weak IBOutlet UIImageView *imgLandLine;
    __weak IBOutlet UILabel *lblLandLine;
    __weak IBOutlet UIImageView *imgSepratorOne;
    
    
    __weak IBOutlet UIView *viewStatus;
    __weak IBOutlet UIImageView *imgDate;
    __weak IBOutlet UILabel *lblDate;
    __weak IBOutlet UILabel *lblStatusHead;
    __weak IBOutlet UIButton *btnStatus;
    __weak IBOutlet UILabel *lblValueHead;
    __weak IBOutlet UILabel *lblValue;
    __weak IBOutlet UIImageView *imgSepratorTwo;
    
    __weak IBOutlet UIView *viewSender;
    __weak IBOutlet UITextView *viewMessage;
    
    __weak IBOutlet UIView *viewAttachment;
   
    __weak IBOutlet UILabel *lblNoAttach;
    __weak IBOutlet UIView *viewComment;
    __weak IBOutlet UILabel *lblNoComment;
    __weak IBOutlet UITableView *tableView;
    __weak IBOutlet UIImageView *imgTextView;
    __weak IBOutlet UIPlaceHolderTextView *textView;
    __weak IBOutlet UIButton *btnPost;
    
    NSTimer *commentTimer;
    NSArray *filearray;
    NSString *docViewURLString;
    NSMutableArray *commentArray;
    NSDictionary *recordDict;
    id<referralDetailDelegate>delegateDetail;
}
@property (nonatomic , strong) id<referralDetailDelegate>delegateDetail;
@property (nonatomic , strong) NSString *referralIDStr;
@end
