//
//  AccountViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 10/24/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MessageUI/MessageUI.h>
#import "AccountEditViewController.h"
#import "CreditCardDetailViewController.h"
#import "GroupViewController.h"
#import "SocialMediaDetailViewController.h"

@protocol AccountViewControllerDelegate <NSObject>

-(void)callHomeScreen;

@end

@interface AccountViewController : UIViewController <MFMailComposeViewControllerDelegate , AccountEditDelegate , CreditCardDetailDelegate , GroupClassDelegate , SocialMediaDetailDelegate> {
    NSDictionary *recordDict;
    __weak IBOutlet UIView *botomView;
    __weak IBOutlet UIImageView *imgProfile;
    __weak IBOutlet UIImageView *imgStarOne;
    __weak IBOutlet UIImageView *imgStarTwo;
    __weak IBOutlet UIImageView *imgStarThree;
    __weak IBOutlet UIImageView *imgStarFour;
    __weak IBOutlet UIImageView *imgStarFive;
    __weak IBOutlet UILabel *lblReview;//lblTop
    __weak IBOutlet UIView *viewOne;
    __weak IBOutlet UIView *viewTwo;
    __weak IBOutlet UIView *viewThree;
    __weak IBOutlet UIView *viewFour;
    __weak IBOutlet UIImageView *imgSkyp;
    __weak IBOutlet UILabel *lblSkyp;
    __weak IBOutlet UIImageView *imgTimezon;
    __weak IBOutlet UIImageView *imgPinAddress;
    __weak IBOutlet UIScrollView *scrollView;
    __weak IBOutlet UIButton *btnFacebook;
    __weak IBOutlet UIButton *btnTwitter;
    __weak IBOutlet UIButton *btnLinkedin;
    __weak IBOutlet UIScrollView *contantScroll;
    __weak IBOutlet UILabel *lblTop;
    
    CGRect frameOne;
    CGRect frameTwo;
    CGRect frameThree;
    CGRect frameFour;
    CGRect frameFive;
    CGRect frameSix;
    CGRect frameSeven;
    CGRect frameEight;
    
    //For Billing Variable
    NSDictionary *resultDict;
    __weak IBOutlet UIView *billingView;
    __weak IBOutlet UIScrollView *billingScrollView;
    __weak IBOutlet UILabel *lblLocalValue;
    __weak IBOutlet UIButton *btnLocalOne;
    __weak IBOutlet UIButton *btnLocalTwo;
    __weak IBOutlet UIButton *btnLocalThree;
    __weak IBOutlet UILabel *lblGlobalValue;
    __weak IBOutlet UIButton *btnGlobalOne;
    __weak IBOutlet UIButton *btnGlobalTwo;
    __weak IBOutlet UIButton *btnGlobalThree;
    NSString *upgradeDownGradeString;
    
    //For Social Media
    __weak IBOutlet UIView *viewSocialMedia;
    
    __weak IBOutlet TPKeyboardAvoidingScrollView *scrollViewSocialMedia;
    __weak IBOutlet UIButton *btnFacebookSocialMedia;
    __weak IBOutlet UIButton *btnFacebookSocialMediaSetting;
    __weak IBOutlet UIButton *btnTwitterSocialMedia;
    __weak IBOutlet UIButton *btnTwitterSocialMediaSetting;
    __weak IBOutlet UIButton *btnLinkedinSocialMedia;
    __weak IBOutlet UIButton *btnLinkedinSocialMediaSetting;
    NSString *checkSocialMediaSetting;
    NSString *revokeString;
    
    
}
@property (nonatomic , weak) id<AccountViewControllerDelegate>delegateAccountClass;
@property (weak, nonatomic) IBOutlet UILabel *lblNotiCount;
@property (nonatomic , strong) NSString *checkProfileEditOrNote;
@property (weak, nonatomic) IBOutlet UILabel *lblName;
@property (weak, nonatomic) IBOutlet UILabel *lblProfession;
@property (weak, nonatomic) IBOutlet UILabel *lblEmail;
@property (weak, nonatomic) IBOutlet UILabel *lblWebTwo;
@property (weak, nonatomic) IBOutlet UILabel *lblWeb;
@property (weak, nonatomic) IBOutlet UILabel *lblMobile;
@property (weak, nonatomic) IBOutlet UILabel *lblPhone;
@property (weak, nonatomic) IBOutlet UILabel *lbltimeJon;
@property (weak, nonatomic) IBOutlet UILabel *lblAddress;
@property (weak, nonatomic) IBOutlet UIImageView *imgSeprator;

-(void)updateSocialMediaInfo;
-(void)updateSocialMediaInfocallBack:(NSString *)string;

@end
