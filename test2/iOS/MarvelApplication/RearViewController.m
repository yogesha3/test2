//
//  RearViewController.m
//  PayToAfrica
//
//  Created by A3logics on 8/29/14.
//  Copyright (c) 2014 Deepak kumar. All rights reserved.
//

#import "RearViewController.h"
#import "AsyncImageView.h"
#import "AppDelegate.h"
#import "SWRevealViewController.h"
#import "LoginViewController.h"

@interface RearViewController ()

@end

@implementation RearViewController

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    tableVIew.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    
    CGAffineTransform transform = CGAffineTransformMakeScale(1.0f, 5.0f);
    _progressIndicator.transform = transform;
    _progressIndicator.layer.cornerRadius = 5.0f;
    _progressIndicator.clipsToBounds = YES;
    
    self.btnProfileImage.layer.cornerRadius = 40.0f;
    self.btnProfileImage.clipsToBounds = YES;
    self.btnProfileImage.layer.borderColor = [UIColor whiteColor].CGColor;
    self.btnProfileImage.layer.borderWidth = 3.0;
    
//    CABasicAnimation* rotationAnimation = [CABasicAnimation animationWithKeyPath:@"transform.rotation.z"];
//    rotationAnimation.toValue = [NSNumber numberWithFloat: M_PI * 2.0];
//    rotationAnimation.duration = 1.0;
//    rotationAnimation.cumulative = YES;
//    rotationAnimation.repeatCount = YES;
//    [imageView.layer addAnimation:rotationAnimation forKey:@"rotationAnimation"];

    
    items = [NSArray arrayWithObjects:
           @{@"Title": @"Dashboard",@"Image": @"DashBoard"},
           @{@"Title": @"Team",@"Image": @"Contacts"},
           @{@"Title": @"Referrals",@"Image": @"Referrals"},
           @{@"Title": @"Messages",@"Image": @"Messages"},
           @{@"Title": @"Events",@"Image": @"Events"},
           @{@"Title": @"Contacts",@"Image": @"Contacts"},
           @{@"Title": @"Account",@"Image": @"accountIcon"},
           @{@"Title": @"Reviews",@"Image": @"Reviews"},
           @{@"Title": @"Settings",@"Image": @"settingIcon"},
           @{@"Title": @"Videos",@"Image": @"videoTabIcon"},
           @{@"Title": @"Suggestions",@"Image": @"suggestionTabIcon"},
           @{@"Title": @"Logout",@"Image": @"Logout"},
           nil];
    
	// Do any additional setup after loading the view.
}



#pragma marl - UITableView Data Source
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
	return [items count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView1 cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
	
    static NSString *PlaceholderCellIdentifier = @"PlaceholderCell";
	
    UITableViewCell *cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
    
    if (cell == nil)
	{
        cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
        cell.backgroundColor = [UIColor clearColor];
        
        UIView *bgColorView = [[UIView alloc] initWithFrame:CGRectMake(0, 0, 400, 50)];
        bgColorView.backgroundColor = [UIColor whiteColor];
        [cell setSelectedBackgroundView:bgColorView];
        
        AsyncImageView *iconImageView = [[AsyncImageView alloc] initWithFrame:CGRectMake(10, 14, 26, 26)];
        [iconImageView setBackgroundColor:[UIColor clearColor]];
        [iconImageView setTag:1];
        [cell.contentView addSubview:iconImageView];
        
        UIImageView *rightImageView = [[UIImageView alloc] initWithFrame:CGRectMake(240, 20, 7, 12)];
        rightImageView.backgroundColor = [UIColor clearColor];
        [rightImageView setImage:[UIImage imageNamed:@"Next.png"]];
        [cell.contentView addSubview:rightImageView];
        
        UILabel *LBL = [[UILabel alloc] initWithFrame:CGRectMake(50, 5, 120, 40)];
        [LBL setBackgroundColor:[UIColor clearColor]];
        [LBL setTextColor:[UIColor blackColor]];
        [LBL setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
        [LBL setTag:2];
        [cell.contentView addSubview:LBL];
        
        UILabel *LBLNoti = [[UILabel alloc] initWithFrame:CGRectMake(180, 10, 50, 30)];
        [LBLNoti setBackgroundColor:[UIColor clearColor]];
        LBLNoti.layer.cornerRadius = 10;
        LBLNoti.clipsToBounds = YES;
        [LBLNoti setTextAlignment:NSTextAlignmentCenter];
        [LBLNoti setHidden:YES];
        [LBLNoti setTextColor:orangeColour];
        [LBLNoti setHighlightedTextColor:orangeColour];
        [LBLNoti setFont:[UIFont fontWithName:@"Graphik-Bold" size:15.0f]];
        [LBLNoti setTag:3];
        [cell.contentView addSubview:LBLNoti];
	}
    
    NSDictionary *item=[items objectAtIndex:indexPath.row];
    
    AsyncImageView *iconImageView = (AsyncImageView*)[cell viewWithTag:1];
    UILabel *lbl = (UILabel*)[cell viewWithTag:2];
    
    NSString *imageNameString = [item valueForKey:@"Image"];
    [iconImageView setImage:[UIImage imageNamed:imageNameString]];
    
    NSString *titleString = [item valueForKey:@"Title"];
    lbl.text = titleString;
    
    UILabel *lblNoti = (UILabel*)[cell viewWithTag:3];
    [lblNoti setHidden:YES];
    if (indexPath.row == 2) {
        [lblNoti setHidden:NO];
        if ([[[AppDelegate currentDelegate] notificationReferralsCount] isEqualToString:@"0"]) {
            lblNoti.text = @"";
        }
        else {
            lblNoti.text = [[AppDelegate currentDelegate] notificationReferralsCount];
        }
    }
    else if (indexPath.row == 3) {
        [lblNoti setHidden:NO];
        if ([[[AppDelegate currentDelegate] notificationMessageCount] isEqualToString:@"0"]) {
            lblNoti.text = @"";
        }
        else {
            lblNoti.text = [[AppDelegate currentDelegate] notificationMessageCount];
        }
    }
    
	return cell;
    
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    checkProfile = @"";

    if (indexPath.row == 0) {
        [self performSegueWithIdentifier:@"home" sender:nil];
    }
    else if (indexPath.row == 1) {
        [self performSegueWithIdentifier:@"TeamModule" sender:nil];
    }
    else if (indexPath.row == 2) {
        referMeDict = nil;
        [self performSegueWithIdentifier:@"referrals" sender:nil];
    }
    else if (indexPath.row == 3) {
        [self performSegueWithIdentifier:@"messages" sender:nil];
    }
    else if (indexPath.row == 5) {
        [self performSegueWithIdentifier:@"ContactModule" sender:nil];
    }
    else if (indexPath.row == 6) {
        checkProfile = @"viewProfile";
        [self performSegueWithIdentifier:@"AccountModule" sender:nil];
    }
    else if (indexPath.row == 8) {
        [self performSegueWithIdentifier:@"setting" sender:nil];
    }
    
    else if (indexPath.row == 9) {
        [self performSegueWithIdentifier:@"webcast" sender:nil];
    }
    
    else if (indexPath.row == 10) {
        [self performSegueWithIdentifier:@"suggestion" sender:nil];
    }
    
    else if (indexPath.row == 11) {
        UIAlertView *alertDelete =[[UIAlertView alloc] initWithTitle:@"" message:@"Are you sure you want to logout?" delegate:self cancelButtonTitle:nil otherButtonTitles:@"OK" , @"Cancel" , nil];
        alertDelete.tag = 100;
        [alertDelete show];
    }
    
    int index = (int)indexPath.row;
    NSString *indexStr = [NSString stringWithFormat:@"%d",index];
    [userDefaultManager setObject:indexStr forKey:@"menuSelect"];
}

- (CGFloat)tableView:(UITableView *)aTableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    return 50;
}

// In a storyboard-based application, you will often want to do a little preparation before navigation
#pragma mark - Navigation
-(void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    if ([segue.identifier isEqualToString:@"logout"]) {
        [userDefaultManager setObject:@"0" forKey:@"menuSelect"];
        [self performSegueWithIdentifier:@"home" sender:nil];//for set home screen
         LoginViewController*loginViewController = segue.destinationViewController;
        loginViewController.checkloginOrLogoutStr = @"logout";
        if (LoginAnothDevic) {
            [self toggle];
        }
    }
    else if ([segue.identifier isEqualToString:@"TeamModule"]) {
        UINavigationController*controller = segue.destinationViewController;
        TeamViewController *teamViewController = [controller.viewControllers objectAtIndex:0];
        teamViewController.delegateTeamClass = self;
    }
    else if ([segue.identifier isEqualToString:@"referrals"]) {
        UINavigationController*controller = segue.destinationViewController;
        ReferralViewController *referralViewController = [controller.viewControllers objectAtIndex:0];
        referralViewController.checkReferMeDict = referMeDict;
        referralViewController.delegateOne = self;
    }
    else if ([segue.identifier isEqualToString:@"messages"]) {
        UINavigationController*controller = segue.destinationViewController;
        MessageViewController *messageViewController = [controller.viewControllers objectAtIndex:0];
        messageViewController.delegateMessageClass = self;
    }
    else if ([segue.identifier isEqualToString:@"ContactModule"]) {
        UINavigationController*controller = segue.destinationViewController;
        ContactViewController *contactViewController = [controller.viewControllers objectAtIndex:0];
        contactViewController.delegateContactClass = self;
    }
    else if ([segue.identifier isEqualToString:@"setting"]) {
        UINavigationController*controller = segue.destinationViewController;
        SettingViewController *settingViewController = [controller.viewControllers objectAtIndex:0];
        settingViewController.delegateSettingClass = self;
    }
    else if ([segue.identifier isEqualToString:@"webcast"]) {
        UINavigationController*controller = segue.destinationViewController;
        WebCastViewController *webCastViewController = [controller.viewControllers objectAtIndex:0];
        webCastViewController.delegateWebCastClass = self;
    }
    else if ([segue.identifier isEqualToString:@"suggestion"]) {
        UINavigationController*controller = segue.destinationViewController;
        SuggestionViewController *suggestionViewController = [controller.viewControllers objectAtIndex:0];
        suggestionViewController.delegateSuggestionClass = self;
    }
    else if ([segue.identifier isEqualToString:@"AccountModule"]) {
        UINavigationController*controller = segue.destinationViewController;
        AccountViewController *accountViewController = [controller.viewControllers objectAtIndex:0];
        accountViewController.delegateAccountClass = self;
        if ([checkProfile isEqualToString:@"viewProfile"]) {
            accountViewController.checkProfileEditOrNote = checkProfile;
        }
        else {
            accountViewController.checkProfileEditOrNote = checkProfile;
        }
    }
}


/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Redirect On Edit Profile From Tap On Profile Picture Ans User Name
 * @Modified Date 24/10/2015
 */
#pragma mark Redirect On Edit Profile From Tap On Profile Picture And User Name
-(IBAction)editProfileFromProfilePictureAndUserNameAndEditIcon:(UIButton *)sender {
   
    int index = 6;
    NSString *indexStr = [NSString stringWithFormat:@"%d",index];
    [userDefaultManager setObject:indexStr forKey:@"menuSelect"];
    
    if (sender.tag == 1) {
        checkProfile = @"viewProfile";
    }
    else if (sender.tag == 2) {
        checkProfile = @"viewProfile";
    }
    else {
        checkProfile = @"editProfile";
    }
    
    [self performSegueWithIdentifier:@"AccountModule" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm alertView , buttonIndex
 * @Description These method are Called when a button is clicked. The view will be automatically dismissed after this call returns
 * @Modified Date 27/07/2015
 */
#pragma mark Delete Record
- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {
    
    switch (alertView.tag) {
        case 100:
            if (buttonIndex == 0) {
                //For Yes
                [self logOut];
            }
            else {
                //For No
            }
            break;
            
        default:
            break;
    }
    
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for LOGOUT
 * @Modified Date 27/07/2015
 */
#pragma mark LOGOUT
-(void)logOutFromOtherDevice {
    LoginAnothDevic = false;
    [self performSegueWithIdentifier:@"logout" sender:nil];
}

-(void)logOut {
    LoginAnothDevic = true;
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(logOutDelay) withObject:nil afterDelay:0.1];
}
-(void)logOutDelay {
    if (isNetworkAvailable) {
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"logout" controls:@"users" httpMethod:@"POST" data:nil];
        
        if (parsedJSONToken != nil) {
            [self performSegueWithIdentifier:@"logout" sender:nil];
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
 * @Description These method are used for Open Home Screen
 * @Modified Date 27/07/2015
 */
#pragma mark HOME SCREEN
-(void)callHomeScreen {
    [userDefaultManager setObject:@"0" forKey:@"menuSelect"];
    [self performSegueWithIdentifier:@"home" sender:nil];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Refer Me From ContactViewController Class
 * @Modified Date 14/12/2015
 */
#pragma mark Refer Me
-(void)callReferralScreenForReferMe:(NSDictionary *)dict {
    referMeDict = dict;
    [userDefaultManager setObject:@"2" forKey:@"menuSelect"];
    [self performSegueWithIdentifier:@"referrals" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Reval View
 * @Modified Date 9/07/2015
 */
#pragma mark toggle
-(void)toggle {
    [self.view endEditing:YES];
    [self.revealViewController revealToggleAnimated:YES];
}

- (void)viewWillAppear:(BOOL)animated {
        
    [self performSelector:@selector(updateProfile) withObject:nil afterDelay:0.1];
    
    [self setSwipGesture];
    
    [self setNotificationValue];
    
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(setNotificationValue) name:@"referralsCount" object:nil];
    
    [super viewWillAppear:animated];
}
//Called when the view is dismissed, covered or otherwise hidden. Default does nothing
-(void)viewWillDisappear:(BOOL)animated {
    [vieww removeFromSuperview];
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
    [tableVIew reloadData];
    NSString *indexStr = [userDefaultManager valueForKey:@"menuSelect"];
    NSIndexPath *indexPath=[NSIndexPath indexPathForRow:indexStr.intValue inSection:0];
    [tableVIew selectRowAtIndexPath:indexPath animated:YES  scrollPosition:UITableViewScrollPositionNone];
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for generate the View for swip gesture
 * @Modified Date 9/07/2015
 */
#pragma mark Swip Gesture
-(void)setSwipGesture {

    vieww = [[UIView alloc] init];
    vieww.frame = CGRectMake(250, 60, [AppDelegate currentDelegate].window.frame.size.width, self.view.frame.size.height);
   
    [vieww setBackgroundColor:[UIColor clearColor]];
    [[AppDelegate currentDelegate].window addSubview:vieww];
    
    UISwipeGestureRecognizer *tap = [[UISwipeGestureRecognizer alloc]
                                     initWithTarget:self
                                     action:@selector(dismissRevalView)];
    tap.delegate = self;
    [tap setDirection:UISwipeGestureRecognizerDirectionLeft];
    [vieww addGestureRecognizer:tap];

}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for update profile
 * @Modified Date 9/07/2015
 */
#pragma mark Update Profile
-(void)updateProfile {
    if (isNetworkAvailable) {
        NSDictionary *dictionary =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequestNotification:@"" methodHeader:@"profileDetail" controls:@"users" httpMethod:@"POST" data:nil];
        if (dictionary != nil) {
            NSDictionary *dict = [dictionary valueForKey:@"result"];
            NSString *imgLinkString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"profile_image"]];
            [userDefaultManager setObject:imgLinkString forKey:@"profile_image"];
            NSString *fNameString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"fname"]];
            [userDefaultManager setObject:fNameString forKey:@"fname"];
            NSString *lNameString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"lname"]];
            [userDefaultManager setObject:lNameString forKey:@"lname"];
            [userDefaultManager synchronize];
            
            //Group Type
            NSString *groupTypeString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"group_role"]];
            if ([groupTypeString isEqualToString:@"leader"]) {
                groupTypeString = [groupTypeString stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                         withString:[[groupTypeString substringToIndex:1] capitalizedString]];
                groupTypeString = [NSString stringWithFormat:@"Group %@",groupTypeString];
            }
            else if ([groupTypeString isEqualToString:@"co-leader"]) {
                groupTypeString = [groupTypeString stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                           withString:[[groupTypeString substringWithRange:NSMakeRange(0, 1)] capitalizedString]];
                groupTypeString = [groupTypeString stringByReplacingCharactersInRange:NSMakeRange(3,1)
                                                                           withString:[[groupTypeString substringWithRange:NSMakeRange(3, 1)] capitalizedString]];
                groupTypeString = [NSString stringWithFormat:@"Group %@",groupTypeString];
            }
            else {
                groupTypeString = [groupTypeString stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                           withString:[[groupTypeString substringToIndex:1] capitalizedString]];
            }
            self.lblGroupTypr.text = groupTypeString;
            
            //Group Number
            NSString *groupNumberString = [NSString stringWithFormat:@"Group %@",[dict valueForKey:@"group_id"]];
            self.lblGroupNo.text = groupNumberString;
            
            //Suffel Date
            NSString *suffelDateString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"shuffling_date"]];
            suffelDateString = ConvertDateStringFormat(suffelDateString);
            suffelDateString = [NSString stringWithFormat:@"Next Shuffle - %@",suffelDateString];
            self.lblDate.text = suffelDateString;
            
            //Member Shop Type
            NSString *memberShipTypeString = [NSString stringWithFormat:@"%@ Member",[dict valueForKey:@"membership_type"]];
            memberShipTypeString = [memberShipTypeString stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                       withString:[[memberShipTypeString substringToIndex:1] capitalizedString]];
            self.lblSubName.text = memberShipTypeString;
            
            NSString *percentProfileString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"profile_completion_status"]];
            
            NSString *perStr = @"% Complete";
            self.lblProgressStatus.text = [NSString stringWithFormat:@"Profile Completion: %@%@",[dict valueForKey:@"profile_completion_status"],perStr];
            
            float percentProfile;
            percentProfile = percentProfileString.floatValue;
            float finalProfilePer = (percentProfile)/100.0f;
            _progressIndicator.progress = finalProfilePer;
            
            NSString *ratingValStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"rating"]];
            if (!CheckStringForNull(ratingValStr)) {
                int starVal = (int)ratingValStr.intValue;
                switch (starVal) {
                    case 0:
                        [self.imgStarOne setImage:[UIImage imageNamed:@"Blank-star"]];
                        [self.imgStarTwo setImage:[UIImage imageNamed:@"Blank-star"]];
                        [self.imgStarThree setImage:[UIImage imageNamed:@"Blank-star"]];
                        [self.imgStarFour setImage:[UIImage imageNamed:@"Blank-star"]];
                        [self.imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                        break;
                    case 1:
                        [self.imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [self.imgStarTwo setImage:[UIImage imageNamed:@"Blank-star"]];
                        [self.imgStarThree setImage:[UIImage imageNamed:@"Blank-star"]];
                        [self.imgStarFour setImage:[UIImage imageNamed:@"Blank-star"]];
                        [self.imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                        break;
                    case 2:
                        [self.imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [self.imgStarTwo setImage:[UIImage imageNamed:@"Full-star"]];
                        [self.imgStarThree setImage:[UIImage imageNamed:@"Blank-star"]];
                        [self.imgStarFour setImage:[UIImage imageNamed:@"Blank-star"]];
                        [self.imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                        break;
                    case 3:
                        [self.imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [self.imgStarTwo setImage:[UIImage imageNamed:@"Full-star"]];
                        [self.imgStarThree setImage:[UIImage imageNamed:@"Full-star"]];
                        [self.imgStarFour setImage:[UIImage imageNamed:@"Blank-star"]];
                        [self.imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                        break;
                    case 4:
                        [self.imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [self.imgStarTwo setImage:[UIImage imageNamed:@"Full-star"]];
                        [self.imgStarThree setImage:[UIImage imageNamed:@"Full-star"]];
                        [self.imgStarFour setImage:[UIImage imageNamed:@"Full-star"]];
                        [self.imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                        break;
                    case 5:
                        [self.imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [self.imgStarTwo setImage:[UIImage imageNamed:@"Full-star"]];
                        [self.imgStarThree setImage:[UIImage imageNamed:@"Full-star"]];
                        [self.imgStarFour setImage:[UIImage imageNamed:@"Full-star"]];
                        [self.imgStarFive setImage:[UIImage imageNamed:@"Full-star"]];
                        break;
                        
                    default:
                        break;
                }
            }
        }
    }
    
    NSString *fNameString = [userDefaultManager valueForKey:@"fname"];
    if (CheckStringForNull(fNameString)) {
        fNameString = @"";
    }
    NSString *lNameString = [NSString stringWithFormat:@" %@",[userDefaultManager valueForKey:@"lname"]];
    if (CheckStringForNull(lNameString)) {
        lNameString = @"";
    }
    fNameString = [fNameString stringByAppendingString:lNameString];
    self.lblName.text = fNameString;
    
    NSString *profileImgStr = [userDefaultManager valueForKey:@"profile_image"];
    [self.btnProfileImage setBackgroundImage:[UIImage imageWithData:[NSData dataWithContentsOfURL:[NSURL URLWithString:profileImgStr]]] forState:UIControlStateNormal];
}

-(void)dismissRevalView {
    [self.revealViewController revealToggleAnimated:YES];
    [[NSNotificationCenter defaultCenter] postNotificationName:@"training" object:nil];
}


- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
