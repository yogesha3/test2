//
//  ContactDetailViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 9/30/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import "AccountViewController.h"

@interface AccountViewController ()
@property (nonatomic) IBOutlet UIButton* revealButtonItem;
@end

@implementation AccountViewController
@synthesize delegateAccountClass;
@synthesize checkProfileEditOrNote;

- (void)viewDidLoad {
    [super viewDidLoad];
    
    botomView.layer.borderWidth = 1.0f;
    botomView.layer.borderColor = BoarderColour.CGColor;
    
    self.lblNotiCount.layer.cornerRadius = 5.0f;
    self.lblNotiCount.clipsToBounds = YES;
    
    contantScroll.contentSize = CGSizeMake(355, 50);
    
    [self saveFrameControl];
    
    //For get the Login User Information
    [self loginUserInformation];
    // Do any additional setup after loading the view.
    [self customSetup];
    
    if ([checkProfileEditOrNote isEqualToString:@"editProfile"]) {
        [self performSegueWithIdentifier:@"editProfile" sender:nil];
    }
    
    //For Billing
    [billingView setHidden:YES];
    [billingScrollView setHidden:YES];
    
    lblLocalValue.layer.cornerRadius = 5.0f;
    lblLocalValue.clipsToBounds = YES;
    btnLocalOne.layer.cornerRadius = 5.0f;
    btnLocalTwo.layer.cornerRadius = 5.0f;
    btnLocalThree.layer.cornerRadius = 5.0f;
    
    lblGlobalValue.layer.cornerRadius = 5.0f;
    lblGlobalValue.clipsToBounds = YES;
    btnGlobalOne.layer.cornerRadius = 5.0f;
    btnGlobalTwo.layer.cornerRadius = 5.0f;
    btnGlobalThree.layer.cornerRadius = 5.0f;
    
    billingScrollView.contentSize = CGSizeMake(billingScrollView.frame.size.width, btnGlobalOne.frame.origin.y+btnGlobalOne.frame.size.height+30);

    //For Social Media
    [viewSocialMedia setHidden:YES];
    [scrollViewSocialMedia setHidden:YES];
    
    btnFacebookSocialMedia.layer.cornerRadius = 5.0f;
    btnTwitterSocialMedia.layer.cornerRadius = 5.0f;
    btnLinkedinSocialMedia.layer.cornerRadius = 5.0f;
    
    scrollViewSocialMedia.contentSize = CGSizeMake(scrollViewSocialMedia.frame.size.width, btnLinkedinSocialMedia.frame.origin.y+btnLinkedinSocialMedia.frame.size.height+80);
    
    
}

// Called when the view is about to made visible. Default does nothing
-(void)viewWillAppear:(BOOL)animated {
    [self setNotificationValue];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(setNotificationValue) name:@"referralsCount" object:nil];
    [super viewWillAppear:animated];
}

//Called when the view is dismissed, covered or otherwise hidden. Default does nothing
-(void)viewWillDisappear:(BOOL)animated {
    [[NSNotificationCenter defaultCenter] removeObserver:self name:@"referralsCount" object:nil];
    [super viewWillDisappear:animated];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description Here we set the referrals count
 * @Modified Date 13/08/2015
 */
#pragma mark Show Notification Count
-(void)setNotificationValue {
    NSString *notiCountStr =[[AppDelegate currentDelegate] notificationTotalCount];
    if (notiCountStr.integerValue > 99) {
        [self.lblNotiCount setText:@"99+"];
    }
    else {
        if ([notiCountStr isEqualToString:@"0"]) {
            [self.lblNotiCount setText:@""];
        }
        else {
            [self.lblNotiCount setText:notiCountStr];
        }
    }
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for View Notification List
 * @Modified Date 13/08/2015
 */
#pragma mark Get View Notification List
-(IBAction)viewNotificationList:(id)sender {
    if (!CheckStringForNull(self.lblNotiCount.text)) {
        [self performSegueWithIdentifier:@"notification" sender:nil];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Save Frame
 * @Modified Date 27/10/2015
 */
#pragma mark Save Frame
-(void)saveFrameControl {

    frameOne = imgTimezon.frame;
    
    frameTwo = self.lbltimeJon.frame;
    
    frameThree = imgPinAddress.frame;
    
    frameFour = self.lblAddress.frame;
    
    frameFive = self.imgSeprator.frame;
    
    frameSix = viewTwo.frame;
    
    frameSeven = viewThree.frame;
    
    frameEight = viewFour.frame;
    
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Edit Profile
 * @Modified Date 24/10/2015
 */
#pragma mark Edit Profile
-(IBAction)editProfile:(id)sender {
    [self performSegueWithIdentifier:@"editProfile" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Refresh Profile Data
 * @Modified Date 27/10/2015
 */
#pragma mark Refresh Profile Data
-(void)updateProfileDetail {
    //For get the Login User Information
    
    [[viewFour subviews] makeObjectsPerformSelector: @selector(removeFromSuperview)];
    
    imgTimezon.frame = CGRectMake(imgTimezon.frame.origin.x, frameOne.origin.y, imgTimezon.frame.size.width, imgTimezon.frame.size.height);
    self.lbltimeJon.frame = CGRectMake(self.lbltimeJon.frame.origin.x, frameTwo.origin.y, self.lbltimeJon.frame.size.width, self.lbltimeJon.frame.size.height);
    imgPinAddress.frame = CGRectMake(imgPinAddress.frame.origin.x, frameThree.origin.y, imgPinAddress.frame.size.width, imgPinAddress.frame.size.height);
    self.lblAddress.frame = CGRectMake(self.lblAddress.frame.origin.x, frameFour.origin.y, self.lblAddress.frame.size.width, self.lblAddress.frame.size.height);
    self.imgSeprator.frame = CGRectMake(self.imgSeprator.frame.origin.x, frameFive.origin.y, self.imgSeprator.frame.size.width, self.imgSeprator.frame.size.height);
    viewTwo.frame = CGRectMake(viewTwo.frame.origin.x, viewTwo.frame.origin.y, viewTwo.frame.size.width, frameSix.size.height);
    viewThree.frame = CGRectMake(viewThree.frame.origin.x, frameSeven.origin.y, viewThree.frame.size.width, viewThree.frame.size.height);
    viewFour.frame = CGRectMake(viewFour.frame.origin.x, frameEight.origin.y, viewFour.frame.size.width, viewFour.frame.size.height);

    
    [self performSelector:@selector(loginUserInformation) withObject:nil afterDelay:0.1];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Select Module Profile Billing And Social Media
 * @Modified Date 24/10/2015
 */
#pragma mark Tap Different Module
-(IBAction)selectModuleProfileBillingAndSocialMedia:(UIButton*)sender {
    
    if (sender.tag == 1) {
        [viewSocialMedia setHidden:YES];
        [scrollViewSocialMedia setHidden:YES];
        [billingView setHidden:YES];
        [billingScrollView setHidden:YES];
        if (!CheckDeviceFunction()) {
            [contantScroll setContentOffset:CGPointMake(0,0) animated:NO];
        }
        [scrollView setHidden:NO];
        [botomView setHidden:NO];
        lblTop.text = @"PROFILE";
        [(UIButton *)[contantScroll viewWithTag:1] setBackgroundImage:[UIImage imageNamed:@"SelectModule"] forState:UIControlStateNormal];
        [(UIButton *)[contantScroll viewWithTag:2] setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
        [(UIButton *)[contantScroll viewWithTag:3] setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
        [self updateProfileDetail];
    }
    else if (sender.tag == 2) {
        [viewSocialMedia setHidden:YES];
        [scrollViewSocialMedia setHidden:YES];
        [billingView setHidden:NO];
        [billingScrollView setHidden:NO];
        if (!CheckDeviceFunction()) {
            [contantScroll setContentOffset:CGPointMake(0,0) animated:NO];
        }
        [scrollView setHidden:YES];
        [botomView setHidden:YES];
        lblTop.text = @"BILLING";
        [(UIButton *)[contantScroll viewWithTag:1] setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
        [(UIButton *)[contantScroll viewWithTag:2] setBackgroundImage:[UIImage imageNamed:@"SelectModule"] forState:UIControlStateNormal];
        [(UIButton *)[contantScroll viewWithTag:3] setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
        [self billingInformation];
    }
    else {
        [viewSocialMedia setHidden:NO];
        [scrollViewSocialMedia setHidden:NO];
        [billingView setHidden:YES];
        [billingScrollView setHidden:YES];
        if (!CheckDeviceFunction()) {
            [contantScroll setContentOffset:CGPointMake(40,0) animated:NO];
        }
        [scrollView setHidden:YES];
        [botomView setHidden:YES];
        lblTop.text = @"SOCIAL MEDIA";
        [(UIButton *)[contantScroll viewWithTag:1] setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
        [(UIButton *)[contantScroll viewWithTag:2] setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
        [(UIButton *)[contantScroll viewWithTag:3] setBackgroundImage:[UIImage imageNamed:@"SelectModule"] forState:UIControlStateNormal];

        [self getSocialMediaInfo];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Billing information
 * @Modified Date 24/10/2015
 */
#pragma mark Billing
- (void)billingInformation {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(billingDelay) withObject:nil afterDelay:0.1];
}
- (void)billingDelay {
    
    [btnLocalOne setUserInteractionEnabled:NO];
    [btnLocalOne setAlpha:0.5f];
    [btnLocalTwo setUserInteractionEnabled:NO];
    [btnLocalTwo setAlpha:0.5f];
    [btnLocalThree setUserInteractionEnabled:NO];
    [btnLocalThree setAlpha:0.5f];
 
    [btnGlobalOne setUserInteractionEnabled:NO];
    [btnGlobalOne setAlpha:0.5f];
    [btnGlobalTwo setUserInteractionEnabled:NO];
    [btnGlobalTwo setAlpha:0.5f];
    [btnGlobalThree setUserInteractionEnabled:NO];
    [btnGlobalThree setAlpha:0.5f];
    
    if (isNetworkAvailable) {
        NSDictionary *parsedJSONResult =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"billing" controls:@"users" httpMethod:@"POST" data:nil];
        
        if (parsedJSONResult != nil) {
            
            resultDict = [parsedJSONResult valueForKey:@"result"];
            
            NSString *amountLocalString = [NSString stringWithFormat:@"%@",[resultDict valueForKey:@"amount_local"]];
            lblLocalValue.text = amountLocalString;
            
            NSString *amountGlobalString = [NSString stringWithFormat:@"%@",[resultDict valueForKey:@"amount_global"]];
            lblGlobalValue.text = amountGlobalString;
            
            NSString *groupTypeString = [NSString stringWithFormat:@"%@",[resultDict valueForKey:@"group_type"]];
            
            NSString *subscriptionTypeString = [NSString stringWithFormat:@"%@",[resultDict valueForKey:@"subscription_status"]];
            
            NSString *lastUpdateString = [NSString stringWithFormat:@"%@",[resultDict valueForKey:@"last_updated"]];
            
//            NSString *meetingString = [NSString stringWithFormat:@"%@",[resultDict valueForKey:@"meeting_start"]];
            
            if ([groupTypeString isEqualToString:@"global"]) {
                
                [btnLocalOne setTitle:@"Downgrade" forState:UIControlStateNormal];
                [btnLocalOne setImage:[UIImage imageNamed:@"billingDownGrades"] forState:UIControlStateNormal];
                [btnLocalOne addTarget:self action:@selector(billingupgradeDownGrade:) forControlEvents:UIControlEventTouchUpInside];
                
                [btnGlobalOne setTitle:@"Cancel" forState:UIControlStateNormal];
                [btnGlobalOne setImage:[UIImage imageNamed:@"billingCancel"] forState:UIControlStateNormal];
                [btnGlobalOne addTarget:self action:@selector(billingCancellationConfirmation) forControlEvents:UIControlEventTouchUpInside];
                
                [btnLocalOne setUserInteractionEnabled:YES];
                [btnLocalOne setAlpha:1.0f];
                [btnLocalTwo setUserInteractionEnabled:NO];
                [btnLocalTwo setAlpha:0.5f];
                [btnLocalThree setUserInteractionEnabled:NO];
                [btnLocalThree setAlpha:0.5f];
                
                [btnGlobalOne setUserInteractionEnabled:YES];
                [btnGlobalOne setAlpha:1.0f];
                [btnGlobalTwo setUserInteractionEnabled:YES];
                [btnGlobalTwo setAlpha:1.0f];
                [btnGlobalThree setUserInteractionEnabled:YES];
                [btnGlobalThree setAlpha:1.0f];
                
                if (![subscriptionTypeString isEqualToString:@"1"]) {
                    [btnLocalOne setUserInteractionEnabled:NO];
                    [btnLocalOne setAlpha:0.5f];
                    [btnGlobalOne setUserInteractionEnabled:NO];
                    [btnGlobalOne setAlpha:0.5f];
//                    [btnGlobalTwo setUserInteractionEnabled:NO];
//                    [btnGlobalTwo setAlpha:0.5f];
                }
                if (![lastUpdateString isEqualToString:@"1"]) {
                    [btnLocalOne setUserInteractionEnabled:NO];
                    [btnLocalOne setAlpha:0.5f];
                }
            }
            else {
                [btnGlobalOne setTitle:@"Upgrade" forState:UIControlStateNormal];
                [btnGlobalOne setImage:[UIImage imageNamed:@"billingUpgrade"] forState:UIControlStateNormal];
                [btnGlobalOne addTarget:self action:@selector(billingupgradeDownGrade:) forControlEvents:UIControlEventTouchUpInside];
                
                [btnLocalOne setTitle:@"Cancel" forState:UIControlStateNormal];
                [btnLocalOne setImage:[UIImage imageNamed:@"billingCancel"] forState:UIControlStateNormal];
                [btnLocalOne addTarget:self action:@selector(billingCancellationConfirmation) forControlEvents:UIControlEventTouchUpInside];
                
                [btnLocalOne setUserInteractionEnabled:YES];
                [btnLocalOne setAlpha:1.0f];
                [btnLocalTwo setUserInteractionEnabled:YES];
                [btnLocalTwo setAlpha:1.0f];
                [btnLocalThree setUserInteractionEnabled:YES];
                [btnLocalThree setAlpha:1.0f];
                
                [btnGlobalOne setUserInteractionEnabled:YES];
                [btnGlobalOne setAlpha:1.0f];
                [btnGlobalTwo setUserInteractionEnabled:NO];
                [btnGlobalTwo setAlpha:0.5f];
                [btnGlobalThree setUserInteractionEnabled:NO];
                [btnGlobalThree setAlpha:0.5f];
                
                if (![subscriptionTypeString isEqualToString:@"1"]) {
                    [btnLocalOne setUserInteractionEnabled:NO];
                    [btnLocalOne setAlpha:0.5f];
//                    [btnLocalTwo setUserInteractionEnabled:NO];
//                    [btnLocalTwo setAlpha:0.5f];
                    [btnGlobalOne setUserInteractionEnabled:NO];
                    [btnGlobalOne setAlpha:0.5f];
                }
                if (![lastUpdateString isEqualToString:@"1"]) {
                    [btnGlobalOne setUserInteractionEnabled:NO];
                    [btnGlobalOne setAlpha:0.5f];
                }
            }
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [[AppDelegate currentDelegate] removeLoading];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used Refresh Billing Information from Creditcard detail view controller
 * @Modified Date 20/10/2015
 */
#pragma mark Refresh Billing Info
-(void)updateBillingInfo {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(billingDelay) withObject:nil afterDelay:0.1];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used Billing cancellation
 * @Modified Date 20/10/2015
 */
#pragma mark Billing Cancellation
-(void)billingCancellationConfirmation {
    UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:@"Do you want to cancel the membership?" delegate:self cancelButtonTitle:nil otherButtonTitles:@"OK",@"Cancel",nil];
    [networkAlert show];
}

// Called when a button is clicked. The view will be automatically dismissed after this call returns
- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {
    if (buttonIndex == 0) {
        [self billingCancellation];
    }
}

-(void)billingCancellation {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(billingCancellationDelay) withObject:nil afterDelay:0.1];
}

-(void)billingCancellationDelay {

    if (isNetworkAvailable) {
        NSDictionary *parsedJSONResult =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"cancelMembership" controls:@"users" httpMethod:@"POST" data:nil];
        
        if (parsedJSONResult != nil) {
            NSString *codeString = [parsedJSONResult valueForKey:@"code"];
            if ([codeString isEqualToString:@"200"]) {
                [self billingDelay];
                NSString *messageString = [parsedJSONResult valueForKey:@"message"];
                UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:messageString delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
                [networkAlert show];
                return;
            }
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [[AppDelegate currentDelegate] removeLoading];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used Billing Receipts
 * @Modified Date 18/21/2015
 */
#pragma mark Billing Receiptas
-(IBAction)billingReceipts:(id)sender {
    [self performSegueWithIdentifier:@"receiptsBilling" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used Billing Credit Card
 * @Modified Date 18/21/2015
 */
#pragma mark Billing Credit Card
-(IBAction)billingCreditCard:(id)sender {
    [self performSegueWithIdentifier:@"PaymentBilling" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used Billing Upgraded/Downgraded
 * @Modified Date 18/21/2015
 */
#pragma mark Billing Upgraded Downgraded
-(void)billingupgradeDownGrade:(UIButton *)sender {
    if (sender.tag == 0) {
        upgradeDownGradeString = @"down";
    }
    else {
        upgradeDownGradeString = @"up";
    }
    [self performSegueWithIdentifier:@"upgradedDownGraded" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used For Get Social Media Information
 * @Modified Date 26/11/2015
 */
#pragma mark Get Social Media Information
-(void)getSocialMediaInfo {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(getSocialMediaInfoDelay) withObject:nil afterDelay:0.1];
}

-(void)getSocialMediaInfoDelay {
    
    [btnFacebookSocialMedia setTitle:@"Allow Access" forState:UIControlStateNormal];
    [btnFacebookSocialMediaSetting setHidden:YES];
    [btnTwitterSocialMedia setTitle:@"Allow Access" forState:UIControlStateNormal];
    [btnTwitterSocialMediaSetting setHidden:YES];
    [btnLinkedinSocialMedia setTitle:@"Allow Access" forState:UIControlStateNormal];
    [btnLinkedinSocialMediaSetting setHidden:YES];
    
    if (isNetworkAvailable) {
        NSDictionary *parsedJSONResult =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"social" controls:@"businessOwners" httpMethod:@"POST" data:nil];
        
        if (parsedJSONResult != nil) {
            NSDictionary *dict = [parsedJSONResult valueForKey:@"result"];
            NSString *facebookEnable = [NSString stringWithFormat:@"%@",[dict valueForKey:@"facebook"]];
            if ([facebookEnable isEqualToString:@"1"]) {
                [btnFacebookSocialMedia setTitle:@"Revoke Access" forState:UIControlStateNormal];
                [btnFacebookSocialMediaSetting setHidden:NO];
            }
            NSString *twitterEnable = [NSString stringWithFormat:@"%@",[dict valueForKey:@"twitter"]];
            if ([twitterEnable isEqualToString:@"1"]) {
                [btnTwitterSocialMedia setTitle:@"Revoke Access" forState:UIControlStateNormal];
                [btnTwitterSocialMediaSetting setHidden:NO];
            }
            NSString *linkedinEnable = [NSString stringWithFormat:@"%@",[dict valueForKey:@"linkedin"]];
            if ([linkedinEnable isEqualToString:@"1"]) {
                [btnLinkedinSocialMedia setTitle:@"Revoke Access" forState:UIControlStateNormal];
                [btnLinkedinSocialMediaSetting setHidden:NO];
            }
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [[AppDelegate currentDelegate] removeLoading];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used For Update Social Media Information from SocialMediaDetailViewController
 * @Modified Date 26/11/2015
 */
#pragma mark Update Social Media Information
-(void)updateSocialMediaInfo {
    [self performSelector:@selector(getSocialMediaInfo) withObject:nil afterDelay:0.3];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used For Update Social Media Information from Call Back URL
 * @Modified Date 26/11/2015
 */
#pragma mark Update Social Media Information From Call Back
-(void)updateSocialMediaInfocallBack:(NSString *)string {
    if (!CheckStringForNull(string)) {
        checkSocialMediaSetting = string;
        [self performSegueWithIdentifier:@"socialMedia" sender:nil];
    }
    else {
        [[AppDelegate currentDelegate] addLoading];
        [self performSelector:@selector(getSocialMediaInfoDelay) withObject:nil afterDelay:0.1];
    }
}



/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used Twitter Social Media Setting
 * @Modified Date 26/11/2015
 */
#pragma mark Twitter Social Media Setting
-(IBAction)twitterSocialMediaSetting:(UIButton*)sender{
    checkSocialMediaSetting = @"twitter";
    [self performSegueWithIdentifier:@"socialMedia" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used Twitter Social Media
 * @Modified Date 26/11/2015
 */
#pragma mark Twitter Social Media
-(IBAction)twitterSocialMedia:(UIButton*)sender{
    
    if ([sender.titleLabel.text isEqualToString:@"Allow Access"]) {
        NSString *completeString = [NSString stringWithFormat:@"%@/businessOwners/loginTwitter/%@",ShareRegisterURL,[userDefaultManager valueForKey:@"user_id"]];
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:completeString]];
    }
    else {
        revokeString = @"twitter";
        [self revokeTwitterFacebookLinkedIn];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used Facebook Social Media Setting
 * @Modified Date 26/11/2015
 */
#pragma mark Facebook Social Media Setting
-(IBAction)facebookSocialMediaSetting:(UIButton*)sender{
    checkSocialMediaSetting = @"facebook";
    [self performSegueWithIdentifier:@"socialMedia" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used Facebook Social Media
 * @Modified Date 26/11/2015
 */
#pragma mark Facebook Social Media
-(IBAction)facebookSocialMedia:(UIButton*)sender{
    if ([sender.titleLabel.text isEqualToString:@"Allow Access"]) {
        NSString *completeString = [NSString stringWithFormat:@"%@/businessOwners/fbLogin/%@",ShareRegisterURL,[userDefaultManager valueForKey:@"user_id"]];
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:completeString]];
    }
    else {
        revokeString = @"facebook";
        [self revokeTwitterFacebookLinkedIn];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used LinkedIn Social Media Setting
 * @Modified Date 26/11/2015
 */
#pragma mark LinkedIn Social Media Setting
-(IBAction)linkedInSocialMediaSetting:(UIButton*)sender{
    checkSocialMediaSetting = @"linkedin";
    [self performSegueWithIdentifier:@"socialMedia" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used LinkedIn Social Media
 * @Modified Date 26/11/2015
 */
#pragma mark LinkedIn Social Media
-(IBAction)linkedInSocialMedia:(UIButton*)sender{
    if ([sender.titleLabel.text isEqualToString:@"Allow Access"]) {
        NSString *completeString = [NSString stringWithFormat:@"%@/businessOwners/linkedInLogin/%@",ShareRegisterURL,[userDefaultManager valueForKey:@"user_id"]];
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:completeString]];
    }
    else {
        revokeString = @"linkedin";
        [self revokeTwitterFacebookLinkedIn];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used For Revoke Twitter / Facebook / LinkedIn
 * @Modified Date 03/12/2015
 */
#pragma mark Revoke Twitter Facebook LinkedIn
-(void)revokeTwitterFacebookLinkedIn {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(revokeTwitterFacebookLinkedInDelay) withObject:nil afterDelay:0.1];
}
-(void)revokeTwitterFacebookLinkedInDelay {
    //for Facebook / Twitter / LinkedIn
    NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:revokeString,@"revokeList",nil];
    
    NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                       options:NSJSONWritingPrettyPrinted error:nil];
    NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
    
    NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
    [[AppDelegate currentDelegate] removeLoading];
    if (isNetworkAvailable) {
        NSDictionary *parsedJSONResult =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"socialRevokeAccess" controls:@"businessOwners" httpMethod:@"POST" data:data];
        
        if (parsedJSONResult != nil) {
            [self performSelector:@selector(getSocialMediaInfo) withObject:nil afterDelay:0.1];
            UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:[parsedJSONResult valueForKey:@"message"] delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [networkAlert show];
        }
    }
    else {
        [[AppDelegate currentDelegate] removeLoading];
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used For go to dashboard From Group Select Class
 * @Modified Date 18/21/2015
 */
#pragma mark Go To Home Screen From Group Class
-(void)GoToHomeScreenFromGroupClass {
    if ([self.delegateAccountClass respondsToSelector:@selector(callHomeScreen)]) {
        [self.delegateAccountClass callHomeScreen];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Reval View
 * @Modified Date 20/10/2015
 */
#pragma mark Custom Setup
- (void)customSetup
{
    SWRevealViewController *revealViewController = self.revealViewController;
    if ( revealViewController )
    {
        [self.revealButtonItem addTarget:self action:@selector( toggle) forControlEvents:UIControlEventTouchUpInside];
        [self.navigationController.navigationBar addGestureRecognizer:revealViewController.panGestureRecognizer];
    }
}

-(void)toggle {
    
    [self.view endEditing:YES];
    [self.revealViewController revealToggleAnimated:YES];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for get Login User Information detail
 * @Modified Date 24/10/2015
 */
#pragma mark Get Contact Detail
- (void)loginUserInformation {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(loginUserInformationDelay) withObject:nil afterDelay:0.1];
}
- (void)loginUserInformationDelay {
    if (isNetworkAvailable) {
        
        NSString *loginUserIdString = [userDefaultManager valueForKey:@"user_id"];
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:loginUserIdString,@"memberId",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"memberDetail" controls:@"teams" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            
            recordDict = [parsedJSONToken valueForKey:@"result"];
            
            //For First Name
            NSString *firstNameString = [recordDict valueForKey:@"fname"];
            if (CheckStringForNull(firstNameString)) {
                firstNameString = @"";
            }
            else {
                firstNameString = [firstNameString stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                           withString:[[firstNameString substringToIndex:1] capitalizedString]];
            }
            //For Last Name
            NSString *lastNameString = [recordDict valueForKey:@"lname"];
            if (CheckStringForNull(lastNameString)) {
                lastNameString = @"";
            }
            else {
                lastNameString = [NSString stringWithFormat:@" %@",[recordDict valueForKey:@"lname"]];
                lastNameString = [lastNameString stringByReplacingCharactersInRange:NSMakeRange(0,2)
                                                                         withString:[[lastNameString substringToIndex:2] capitalizedString]];
            }
            firstNameString = [firstNameString stringByAppendingString:lastNameString];
            self.lblName.text = firstNameString;
            [self.lblName sizeToFit];
            
            self.lblProfession.frame = CGRectMake(self.lblProfession.frame.origin.x, self.lblName.frame.origin.y+self.lblName.frame.size.height, self.lblProfession.frame.size.width, self.lblProfession.frame.size.height);
            
            //For Profession
            NSString *professionString = [recordDict valueForKey:@"profession_name"];
            if (CheckStringForNull(professionString)) {
                professionString = @"NA";
            }
            else {
                professionString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"profession_name"]];
            }
            
            //For Company
            NSString *coumpanyString = [recordDict valueForKey:@"company"];
            coumpanyString = [coumpanyString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(coumpanyString)) {
                coumpanyString = @", NA";
            }
            else {
                coumpanyString = [NSString stringWithFormat:@", %@",[recordDict valueForKey:@"company"]];
            }
            professionString = [professionString stringByAppendingString:coumpanyString];
            
            if ([professionString isEqualToString:@"NA, NA"]) {
                professionString = @"NA";
                self.lblProfession.textColor = [UIColor darkGrayColor];
            }
            else {
                self.lblProfession.textColor = orangeColour;
            }
            
            self.lblProfession.text = professionString;
            [self.lblProfession sizeToFit];
            
            viewOne.frame = CGRectMake(viewOne.frame.origin.x, viewOne.frame.origin.y, viewOne.frame.size.width, self.lblProfession.frame.origin.y+self.lblProfession.frame.size.height+20);
            
            viewTwo.frame = CGRectMake(viewTwo.frame.origin.x, viewOne.frame.origin.y+viewOne.frame.size.height, viewTwo.frame.size.width, viewTwo.frame.size.height);
            
            //For User Profile Image
            imgProfile.layer.cornerRadius = 25.0f;
            imgProfile.clipsToBounds = YES;
            NSString *profileUrlStr = [recordDict valueForKey:@"member_profile_image"];
            if (!CheckStringForNull(profileUrlStr)) {
                [imgProfile setImage:[UIImage imageWithData:[NSData dataWithContentsOfURL:[NSURL URLWithString:profileUrlStr]]]];
            }
            
            //For fill Star
            NSString *ratingValStr = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"rating"]];
            NSString *totalSt = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"totalReview"]];
            NSString *totalReviewStr  = [NSString stringWithFormat:@"%@\nreview(s)",totalSt];
            NSMutableAttributedString * string = [[NSMutableAttributedString alloc] initWithString:totalReviewStr];
            [string addAttribute:NSForegroundColorAttributeName value:orangeColour range:NSMakeRange(0,totalSt.length)];
            [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Bold" size:12.0] range:NSMakeRange(0,totalSt.length)];
            lblReview.attributedText = string;
            
            if (!CheckStringForNull(ratingValStr)) {
                int starVal = (int)ratingValStr.intValue;
                switch (starVal) {
                    case 0:
                        [imgStarOne setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarTwo setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarThree setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarFour setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                        break;
                    case 1:
                        [imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarTwo setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarThree setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarFour setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                        break;
                    case 2:
                        [imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarTwo setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarThree setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarFour setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                        break;
                    case 3:
                        [imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarTwo setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarThree setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarFour setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                        break;
                    case 4:
                        [imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarTwo setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarThree setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarFour setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                        break;
                    case 5:
                        [imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarTwo setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarThree setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarFour setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarFive setImage:[UIImage imageNamed:@"Full-star"]];
                        break;
                        
                    default:
                        break;
                }
            }
            
            
            //For Email
            NSString *emailString = [recordDict valueForKey:@"email"];
            if (CheckStringForNull(emailString)) {
                self.lblEmail.textColor = [UIColor darkGrayColor];
                emailString = @"NA";
            }
            else {
                self.lblEmail.textColor = orangeColour;
            }
            self.lblEmail.text = emailString;
            
            //For Web Link
            NSString *webLinkString = [recordDict valueForKey:@"website"];
            if (CheckStringForNull(webLinkString)) {
                self.lblWeb.textColor = [UIColor darkGrayColor];
                webLinkString = @"NA";
            }
            else {
                self.lblWeb.textColor = orangeColour;
            }
            self.lblWeb.text = webLinkString;
            
            //For Web Link Two
            NSString *webLinkTwoString = [recordDict valueForKey:@"website1"];
            if (CheckStringForNull(webLinkTwoString)) {
                self.lblWebTwo.textColor = [UIColor darkGrayColor];
                webLinkTwoString = @"NA";
            }
            else {
                self.lblWebTwo.textColor = orangeColour;
            }
            self.lblWebTwo.text = webLinkTwoString;
            
            //For Mobile Number
            NSString *mobileNumString = [recordDict valueForKey:@"mobile"];
            if (CheckStringForNull(mobileNumString)) {
                mobileNumString = @"NA";
            }
            self.lblMobile.text = mobileNumString;
            
            //For Office Number
            NSString *officeNumString = [recordDict valueForKey:@"office_phone"];
            if (CheckStringForNull(officeNumString)) {
                officeNumString = @"NA";
            }
            self.lblPhone.text = officeNumString;
            
            //For Skyp
            NSString *skypIdStr  = [recordDict valueForKey:@"skype_id"];
            if (CheckStringForNull(skypIdStr)) {
                [imgSkyp setHidden:YES];
                [lblSkyp setHidden:YES];
                imgTimezon.frame = CGRectMake(imgTimezon.frame.origin.x, imgTimezon.frame.origin.y-36, imgTimezon.frame.size.width, imgTimezon.frame.size.height);
                self.lbltimeJon.frame = CGRectMake(self.lbltimeJon.frame.origin.x, self.lbltimeJon.frame.origin.y-36, self.lbltimeJon.frame.size.width, self.lbltimeJon.frame.size.height);
                imgPinAddress.frame = CGRectMake(imgPinAddress.frame.origin.x, imgPinAddress.frame.origin.y-36, imgPinAddress.frame.size.width, imgPinAddress.frame.size.height);
                self.lblAddress.frame = CGRectMake(self.lblAddress.frame.origin.x, self.lblAddress.frame.origin.y-36, self.lblAddress.frame.size.width, self.lblAddress.frame.size.height);
                self.imgSeprator.frame = CGRectMake(self.imgSeprator.frame.origin.x, self.imgSeprator.frame.origin.y-36, self.imgSeprator.frame.size.width, self.imgSeprator.frame.size.height);
                viewTwo.frame = CGRectMake(viewTwo.frame.origin.x, viewTwo.frame.origin.y, viewTwo.frame.size.width, viewTwo.frame.size.height-36);
                viewThree.frame = CGRectMake(viewThree.frame.origin.x, viewThree.frame.origin.y-36, viewThree.frame.size.width, viewThree.frame.size.height);
                viewFour.frame = CGRectMake(viewFour.frame.origin.x, viewFour.frame.origin.y-36, viewFour.frame.size.width, viewFour.frame.size.height);
            }
            else {
                [imgSkyp setHidden:NO];
                [lblSkyp setHidden:NO];
                lblSkyp.text = skypIdStr;
                
            }
            
            //For GMT Location
            NSString *timeJonString = [recordDict valueForKey:@"timezone_id"];
            if (CheckStringForNull(timeJonString)) {
                timeJonString = @"NA";
            }
            self.lbltimeJon.text = timeJonString;
            [self.lbltimeJon sizeToFit];
            
            imgPinAddress.frame = CGRectMake(imgPinAddress.frame.origin.x, self.lbltimeJon.frame.origin.y+self.lbltimeJon.frame.size.height+19, imgPinAddress.frame.size.width, imgPinAddress.frame.size.height);
            self.lblAddress.frame = CGRectMake(self.lblAddress.frame.origin.x, self.lbltimeJon.frame.origin.y+self.lbltimeJon.frame.size.height+16, self.lblAddress.frame.size.width, self.lblAddress.frame.size.height);
            
            //For Address
            NSString *addressString = [recordDict valueForKey:@"address"];
            addressString = [addressString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            addressString = [addressString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(addressString)) {
                addressString = @"";
            }
            else {
                addressString = [NSString stringWithFormat:@"%@\n",[recordDict valueForKey:@"address"]];
            }
            //For City
            NSString *cityString = [recordDict valueForKey:@"city"];
            cityString = [cityString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(cityString)) {
                cityString = @"";
            }
            else {
                cityString = [NSString stringWithFormat:@"%@, ",[recordDict valueForKey:@"city"]];
            }
            
            //For Zip
            NSString *zipString = [recordDict valueForKey:@"zipcode"];
            if (CheckStringForNull(zipString)) {
                zipString = @"";
            }
            else {
                zipString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"zipcode"]];
            }

            //For State
            NSString *stateString = [recordDict valueForKey:@"state_name"];
            if (CheckStringForNull(stateString)) {
                stateString = @"";
            }
            else {
                if (CheckStringForNull(zipString)) {
                     stateString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"state_name"]];
                }
                else {
                     stateString = [NSString stringWithFormat:@", %@",[recordDict valueForKey:@"state_name"]];
                }
            }
            zipString = [zipString stringByAppendingString:stateString];
            
            //For Country
            NSString *countryString = [recordDict valueForKey:@"country_name"];
            if (CheckStringForNull(countryString)) {
                countryString = @"";
            }
            else {
                if (CheckStringForNull(zipString)) {
                    countryString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"country_name"]];
                }
                else {
                    countryString = [NSString stringWithFormat:@", %@",[recordDict valueForKey:@"country_name"]];
                }
            }
            zipString = [zipString stringByAppendingString:countryString];
            cityString = [cityString stringByAppendingString:zipString];
            addressString = [addressString stringByAppendingString:cityString];
            
            self.lblAddress.text = addressString;
            [self.lblAddress sizeToFit];
            
            self.imgSeprator.frame = CGRectMake(self.imgSeprator.frame.origin.x, self.lblAddress.frame.origin.y+self.lblAddress.frame.size.height+30, self.imgSeprator.frame.size.width, self.imgSeprator.frame.size.height);
            viewTwo.frame = CGRectMake(viewTwo.frame.origin.x, viewTwo.frame.origin.y, viewTwo.frame.size.width, self.imgSeprator.frame.origin.y+10);
            viewThree.frame = CGRectMake(viewThree.frame.origin.x, viewTwo.frame.origin.y+viewTwo.frame.size.height, viewThree.frame.size.width, viewThree.frame.size.height);
            viewFour.frame = CGRectMake(viewFour.frame.origin.x, viewThree.frame.origin.y+viewThree.frame.size.height, viewFour.frame.size.width, viewFour.frame.size.height);
            
            scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewThree.frame.origin.y+viewThree.frame.size.height);
            
            float height = 5.0;
            //For About Me
            NSString *aboutMeString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"aboutme"]];
            if (!CheckStringForNull(aboutMeString)) {
                UILabel *label = [[UILabel alloc] initWithFrame:CGRectMake(50, height, viewFour.frame.size.width-100, 30)];
                [label setFont:[UIFont fontWithName:@"Graphik-Bold" size:15.0f]];
                [label setTextAlignment:NSTextAlignmentCenter];
                [label setText:@"About Me"];
                [label setTextColor:[UIColor blackColor]];
                [viewFour addSubview:label];
                
                UILabel *label2 = [[UILabel alloc] initWithFrame:CGRectMake(10, height+30, viewFour.frame.size.width-20, 30)];
                [label2 setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
                [label2 setText:aboutMeString];
                [label2 setNumberOfLines:0];
                [label2 setTextAlignment:NSTextAlignmentJustified];
                [label2 sizeToFit];
                [label2 setTextColor:[UIColor darkGrayColor]];
                [viewFour addSubview:label2];
                
                UIImageView *sepratorImgView = [[UIImageView alloc] initWithFrame:CGRectMake(10, label2.frame.origin.y+label2.frame.size.height+5, viewFour.frame.size.width-20, 2)];
                [sepratorImgView setImage:[UIImage imageNamed:@"sepratorLine"]];
                [viewFour addSubview:sepratorImgView];
                
                viewFour.frame = CGRectMake(viewFour.frame.origin.x, viewThree.frame.origin.y+viewThree.frame.size.height, viewFour.frame.size.width, label2.frame.origin.y+label2.frame.size.height+15);
                
                height  = label2.frame.origin.y+label2.frame.size.height+15;
                
                scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewFour.frame.origin.y+viewFour.frame.size.height);
            }
            
            //For company Description
            NSString *compDescString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"business_description"]];
            if (!CheckStringForNull(compDescString)) {
                UILabel *label = [[UILabel alloc] initWithFrame:CGRectMake(50, height, viewFour.frame.size.width-100, 30)];
                [label setFont:[UIFont fontWithName:@"Graphik-Bold" size:15.0f]];
                [label setText:@"Company Description"];
                [label setTextAlignment:NSTextAlignmentCenter];
                [label setTextColor:[UIColor blackColor]];
                [viewFour addSubview:label];
                
                UILabel *label2 = [[UILabel alloc] initWithFrame:CGRectMake(10, height+30, viewFour.frame.size.width-20, 30)];
                [label2 setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
                [label2 setText:compDescString];
                [label2 setNumberOfLines:0];
                [label2 setTextAlignment:NSTextAlignmentJustified];
                [label2 sizeToFit];
                [label2 setTextColor:[UIColor darkGrayColor]];
                [viewFour addSubview:label2];
                
                UIImageView *sepratorImgView = [[UIImageView alloc] initWithFrame:CGRectMake(10, label2.frame.origin.y+label2.frame.size.height+5, viewFour.frame.size.width-20, 2)];
                [sepratorImgView setImage:[UIImage imageNamed:@"sepratorLine"]];
                [viewFour addSubview:sepratorImgView];
                
                viewFour.frame = CGRectMake(viewFour.frame.origin.x, viewThree.frame.origin.y+viewThree.frame.size.height, viewFour.frame.size.width, label2.frame.origin.y+label2.frame.size.height+15);
                
                height  = label2.frame.origin.y+label2.frame.size.height+15;
                
                scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewFour.frame.origin.y+viewFour.frame.size.height);
            }
            //For Services
            NSString *servicesString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"services"]];
            if (!CheckStringForNull(servicesString)) {
                UILabel *label = [[UILabel alloc] initWithFrame:CGRectMake(50, height, viewFour.frame.size.width-100, 30)];
                [label setFont:[UIFont fontWithName:@"Graphik-Bold" size:15.0f]];
                [label setText:@"Services"];
                [label setTextAlignment:NSTextAlignmentCenter];
                [label setTextColor:[UIColor blackColor]];
                [viewFour addSubview:label];
                
                UILabel *label2 = [[UILabel alloc] initWithFrame:CGRectMake(10, height+30, viewFour.frame.size.width-20, 30)];
                [label2 setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
                [label2 setText:servicesString];
                [label2 setNumberOfLines:0];
                [label2 setTextAlignment:NSTextAlignmentJustified];
                [label2 sizeToFit];
                [label2 setTextColor:[UIColor darkGrayColor]];
                [viewFour addSubview:label2];
                
                viewFour.frame = CGRectMake(viewFour.frame.origin.x, viewThree.frame.origin.y+viewThree.frame.size.height, viewFour.frame.size.width, label2.frame.origin.y+label2.frame.size.height+15);
                
                height  = label2.frame.origin.y+label2.frame.size.height+15;
                
                scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewFour.frame.origin.y+viewFour.frame.size.height);
            }
            
            //For Facebook
            NSString *facebookIdString = [recordDict valueForKey:@"facebook_profile_id"];
            [btnFacebook setUserInteractionEnabled:YES];
            [btnFacebook setAlpha:1.0f];
            if (CheckStringForNull(facebookIdString)) {
                [btnFacebook setUserInteractionEnabled:NO];
                [btnFacebook setAlpha:0.5f];
            }
            //For Twitter
            NSString *twitterIdString = [recordDict valueForKey:@"twitter_profile_id"];
            [btnTwitter setUserInteractionEnabled:YES];
            [btnTwitter setAlpha:1.0f];
            if (CheckStringForNull(twitterIdString)) {
                [btnTwitter setUserInteractionEnabled:NO];
                [btnTwitter setAlpha:0.5f];
            }
            //For Linked IN
            NSString *linkedInIdString = [recordDict valueForKey:@"linkedin_profile_id"];
            [btnLinkedin setUserInteractionEnabled:YES];
            [btnLinkedin setAlpha:1.0f];
            if (CheckStringForNull(linkedInIdString)) {
                [btnLinkedin setUserInteractionEnabled:NO];
                [btnLinkedin setAlpha:0.5f];
            }
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [[AppDelegate currentDelegate] removeLoading];
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Open nativ app for facebook
 * @Modified Date 12/10/2015
 */
#pragma mark Open Facebook
-(IBAction)OpenFacebook:(id)sender {
    
    NSString *facebookIdString = [recordDict valueForKey:@"facebook_profile_id"];
    if (!CheckStringForNull(facebookIdString)) {
        NSArray *arrayYouTube = [facebookIdString componentsSeparatedByString:@"//"];
        NSString *metchString = @"";
        if (arrayYouTube.count > 1) {
            NSString *typeString  = [arrayYouTube objectAtIndex:0];
            if ([typeString isEqualToString:@"http:"] || [typeString isEqualToString:@"https:"]) {
                metchString = @"";
            }
            else {
                metchString = @"http://";
            }
        }
        else {
            metchString = @"http://";
        }
        metchString = [metchString stringByAppendingString:facebookIdString];
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:metchString]];
    }
    
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Open nativ app for Twitter
 * @Modified Date 12/10/2015
 */
#pragma mark Open Twitter
-(IBAction)OpenTwitter:(id)sender {
    NSString *twitterString = [recordDict valueForKey:@"twitter_profile_id"];
    if (!CheckStringForNull(twitterString)) {
        NSArray *arrayTwitter = [twitterString componentsSeparatedByString:@"//"];
        NSString *metchString = @"";
        if (arrayTwitter.count > 1) {
            NSString *typeString  = [arrayTwitter objectAtIndex:0];
            if ([typeString isEqualToString:@"http:"] || [typeString isEqualToString:@"https:"]) {
                metchString = @"";
            }
            else {
                metchString = @"http://";
            }
        }
        else {
            metchString = @"http://";
        }
        metchString = [metchString stringByAppendingString:twitterString];
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:metchString]];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Open nativ app for Linked In
 * @Modified Date 12/10/2015
 */
#pragma mark Open Linked In
-(IBAction)OpenLinkedIn:(id)sender {
    NSString *linkedInString = [recordDict valueForKey:@"linkedin_profile_id"];
    if (!CheckStringForNull(linkedInString)) {
        NSArray *arrayLinkedIn = [linkedInString componentsSeparatedByString:@"//"];
        NSString *metchString = @"";
        if (arrayLinkedIn.count > 1) {
            NSString *typeString  = [arrayLinkedIn objectAtIndex:0];
            if ([typeString isEqualToString:@"http:"] || [typeString isEqualToString:@"https:"]) {
                metchString = @"";
            }
            else {
                metchString = @"http://";
            }
        }
        else {
            metchString = @"http://";
        }
        metchString = [metchString stringByAppendingString:linkedInString];
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:metchString]];
    }
}


/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Open web site link on browser
 * @Modified Date 05/10/2015
 */
#pragma mark Redirect On Browser
-(IBAction)redirectOnBrowser:(UIButton *)sender {
    NSString *webLinkStr = @"";
    if (sender.tag == 200) {
        webLinkStr =  [self.lblWeb text];
    }
    else {
        webLinkStr =  [self.lblWebTwo text];
    }
    
    if (![webLinkStr isEqualToString:@"NA"]) {
        NSArray *arrayYouTube = [webLinkStr componentsSeparatedByString:@"//"];
        NSString *metchString = @"";
        if (arrayYouTube.count > 1) {
            NSString *typeString  = [arrayYouTube objectAtIndex:0];
            if ([typeString isEqualToString:@"http:"] || [typeString isEqualToString:@"https:"]) {
                metchString = @"";
            }
            else {
                metchString = @"http://";
            }
        }
        else {
            metchString = @"http://";
        }
        metchString = [metchString stringByAppendingString:webLinkStr];
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:metchString]];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Send mail
 * @Modified Date 06/10/2015
 */
#pragma mark Send Mail
-(IBAction)sendMail:(id)sender {
    NSString *mailStr = [self.lblEmail text];
    if (![mailStr isEqualToString:@"NA"]) {
        
        if (CheckStringForNull(mailStr)) {
            mailStr = @"";
            return;
        }
        NSArray *toRecipents = [NSArray arrayWithObject:mailStr];
        
        if ([MFMailComposeViewController canSendMail])
        {
            MFMailComposeViewController *mail = [[MFMailComposeViewController alloc] init];
            mail.mailComposeDelegate = self;
            [mail setSubject:@""];
            [mail setMessageBody:@"" isHTML:NO];
            [mail setToRecipients:toRecipents];
            
            [self presentViewController:mail animated:YES completion:NULL];
        }
        else
        {
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Email client is not configured. Please configure your email account." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
        }
    }
}

/*!
 @method     mailComposeController:didFinishWithResult:error:
 @abstract   Delegate callback which is called upon user's completion of email composition.
 @discussion This delegate callback will be called when the user completes the email composition.  How the user chose
 to complete this task will be given as one of the parameters to the callback.  Upon this call, the client
 should remove the view associated with the controller, typically by dismissing modally.
 @param      controller   The MFMailComposeViewController instance which is returning the result.
 @param      result       MFMailComposeResult indicating how the user chose to complete the composition process.
 @param      error        NSError indicating the failure reason if failure did occur.  This will be <tt>nil</tt> if
 result did not indicate failure.
 */
#pragma mark mailComposeController
- (void) mailComposeController:(MFMailComposeViewController *)controller didFinishWithResult:(MFMailComposeResult)result error:(NSError *)error
{
    switch (result)
    {
        case MFMailComposeResultCancelled:
            NSLog(@"Mail cancelled");
            break;
        case MFMailComposeResultSaved:
            NSLog(@"Mail saved");
            break;
        case MFMailComposeResultSent:
            NSLog(@"Mail sent");
            break;
        case MFMailComposeResultFailed:
            NSLog(@"Mail sent failure: %@", [error localizedDescription]);
            break;
        default:
            break;
    }
    
    // Close the Mail Interface
    [self dismissViewControllerAnimated:YES completion:NULL];
}


- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    if ([segue.identifier isEqualToString:@"editProfile"]) {
        AccountEditViewController *accountEditViewController = segue.destinationViewController;
        accountEditViewController.profileDelegate = self;
    }
    else if ([segue.identifier isEqualToString:@"PaymentBilling"]){
        NSString *cardNumberString = [NSString stringWithFormat:@"%@",[resultDict valueForKey:@"credit_card_number"]];
        CreditCardDetailViewController *creditCardDetailViewController = segue.destinationViewController;
        creditCardDetailViewController.PaymentDelegate = self;
        creditCardDetailViewController.previousCardString = cardNumberString;
    }
    else if ([segue.identifier isEqualToString:@"upgradedDownGraded"]) {
        GroupViewController *groupViewController = segue.destinationViewController;
        groupViewController.groupDelegate = self;
        groupViewController.checkString = upgradeDownGradeString;
    }
    else if ([segue.identifier isEqualToString:@"socialMedia"]) {
        SocialMediaDetailViewController *socialMediaDetailViewController = segue.destinationViewController;
        socialMediaDetailViewController.socialMediaDelegate = self;
        socialMediaDetailViewController.checkConditionString = checkSocialMediaSetting;
    }
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}


@end
