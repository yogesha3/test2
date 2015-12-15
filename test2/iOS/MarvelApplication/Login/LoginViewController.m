//
//  LoginViewController.m
//  MarvelApplication
//
//  Created by Deepak Chauhan on 7/8/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "LoginViewController.h"

@interface LoginViewController ()

@end

@implementation LoginViewController
@synthesize checkloginOrLogoutStr;
@synthesize textEmail , textPassword;

- (void)viewDidLoad {
    
    self.scrollView.contentSize = CGSizeMake(self.scrollView.frame.size.width, 350);
    
    NSString *titleStr = @"Login With FOXHOPR";
    NSMutableAttributedString * string = [[NSMutableAttributedString alloc] initWithString:titleStr];
    [string addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:NSMakeRange(0,14)];
    [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Light" size:25.0] range:NSMakeRange(0,14)];
    [string addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:NSMakeRange(14,4)];
    [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Bold" size:25.0] range:NSMakeRange(14,4)];
    lblLoginTitle.attributedText = string;
    self.emailBG.layer.borderWidth = 1.0f;
    self.emailBG.layer.borderColor = [UIColor lightGrayColor].CGColor;
    self.emailBG.layer.cornerRadius = 5.0f;
    
    self.passBG.layer.borderWidth = 1.0f;
    self.passBG.layer.borderColor = [UIColor lightGrayColor].CGColor;
    self.passBG.layer.cornerRadius = 5.0f;
    
    self.btnLogin.layer.cornerRadius = 5.0f;
    self.btnForgot.layer.cornerRadius = 5.0f;
    
    //for logout
    if ([checkloginOrLogoutStr isEqualToString:@"logout"]) {
        [[NSUserDefaults standardUserDefaults] setObject:@"" forKey:@"login"];
        [[NSUserDefaults standardUserDefaults] synchronize];
    }
    else {
        [self showSplashScreen];
    }

    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Splash Screen
 * @Modified Date 9/07/2015
 */
#pragma mark Splash Screen
-(void)showSplashScreen {
    
    CGSize result = [[UIScreen mainScreen] bounds].size;
    
    splassView = [[UIView alloc] init];
    splassView.frame = CGRectMake(0, 0, result.width, result.height);
    [splassView setBackgroundColor:[UIColor whiteColor]];
    [splassView setBackgroundColor:[UIColor clearColor]];
    [self.view addSubview:splassView];
    [self.view bringSubviewToFront:splassView];
    
    NSString *imageSplashNameString = @"FoxHopr-640x1136.png";
    if (CheckDeviceFunction()) {
        imageSplashNameString = @"FoxHopr-1536x2048.png";
    }
    else {
        imageSplashNameString = @"FoxHopr-640x1136.png";
    }
    
    UIImage *splashImage = [UIImage imageNamed:imageSplashNameString];
    UIImageView *splashImageView = [[UIImageView alloc] initWithImage:splashImage];
    [splassView addSubview:splashImageView];

    [self performSelector:@selector(startupAnimationDone) withObject:nil afterDelay:3.0];
}
- (void)startupAnimationDone {
    
    [UIView transitionWithView:splassView duration:0.90 options:UIViewAnimationOptionTransitionCurlUp animations:^{splassView.hidden = YES;} completion:^(BOOL finished) {
        [splassView removeFromSuperview];
        splassView=nil;
        
    }];
}

-(void)viewWillAppear:(BOOL)animated {

    [[[AppDelegate currentDelegate] notificationTimer] invalidate];
    [AppDelegate currentDelegate].notificationTimer = nil;
    
    [super viewWillAppear:animated];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Login
 * @Modified Date 9/07/2015
 */
#pragma mark Login
- (IBAction)loginBtnPressed:(id)sender {
    
    userNameString = self.textEmail.text;
    passwordString = self.textPassword.text;
    [self.view endEditing:YES];
    [[AppDelegate currentDelegate] addLoading];
    [self makeEmptyFieldRed];
    BOOL isFilled = [self checkAllFieldAreFilled];
    if (!isFilled) {
        return;
    }
    [self performSelector:@selector(loginDelay) withObject:nil afterDelay:0.1];
}

-(void)loginDelay {
    
    if (isNetworkAvailable) {
        //For login
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:self.textEmail.text,@"user_email",self.textPassword.text,@"password",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                              options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequestLogin:@"" methodHeader:@"login" controls:@"users" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            NSDictionary *dict = [parsedJSONToken valueForKey:@"result"];
            [self saveUserInformation:dict];
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [[AppDelegate currentDelegate] removeLoading];
}

-(void)saveUserInformation:(NSDictionary *)dict {
        
    NSString *userIdString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"id"]];
    [userDefaultManager setObject:userIdString forKey:@"user_id"];
    NSString *imgLinkString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"profile_image"]];
    [userDefaultManager setObject:imgLinkString forKey:@"profile_image"];
    NSString *fNameString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"fname"]];
    [userDefaultManager setObject:fNameString forKey:@"fname"];
    NSString *lNameString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"lname"]];
    [userDefaultManager setObject:lNameString forKey:@"lname"];
    NSString *ratingString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"rating"]];
    [userDefaultManager setObject:ratingString forKey:@"rating"];
    NSString *groupTypeString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"group_type"]];
    [userDefaultManager setObject:groupTypeString forKey:@"group_type"];
    NSString *cityString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"city"]];
    [userDefaultManager setObject:cityString forKey:@"city"];
    NSString *zipCodeString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"zipcode"]];
    [userDefaultManager setObject:zipCodeString forKey:@"zipcode"];
    NSString *groupIdString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"group_id"]];
    [userDefaultManager setObject:@"" forKey:@"checkG"];
    if (CheckStringForNull(groupIdString)) {
        groupIdString = @"";
        [userDefaultManager setObject:@"instant" forKey:@"checkG"];
    }
    [userDefaultManager setObject:groupIdString forKey:@"group_id"];
    
    [userDefaultManager setObject:@"loginSuccess" forKey:@"login"];
    
    [self dismissViewControllerAnimated:YES completion:nil];
    
    //For Start Timer Notification
    [[AppDelegate currentDelegate] updateNotification];
    [[AppDelegate currentDelegate] startTimerForNotification];
    
    [userDefaultManager synchronize];
}


/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for check Required Field
 * @Modified Date 9/07/2015
 */
#pragma mark Required Field
-(BOOL)checkAllFieldAreFilled {
    
    // for check email
    NSString *emailString = self.textEmail.text;
    if ([emailString isEqualToString:@""]) {
        [[AppDelegate currentDelegate] removeLoading];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    BOOL flagValidEmail = validateEmail(emailString);
    if (!flagValidEmail) {
        [[AppDelegate currentDelegate] removeLoading];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Please enter a valid email address." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    
    // for check password
    NSString *passwordString11 = self.textPassword.text;
    if ([passwordString11 isEqualToString:@""]) {
        [[AppDelegate currentDelegate] removeLoading];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }

    return YES;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for change the boarder color of Required Field
 * @Modified Date 9/07/2015
 */
#pragma mark Required Field
-(BOOL)makeEmptyFieldRed {
    
    if ([self.textEmail.text isEqualToString:@""]) {
        [[ConnectionManager sharedManager] setBoarderColorRed:self.emailBG];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorGray:self.emailBG];
    }
    BOOL flagValidEmail = validateEmail(self.textEmail.text);
    if (!flagValidEmail) {
        [[ConnectionManager sharedManager] setBoarderColorRed:self.emailBG];
    }
    else {
        [[ConnectionManager sharedManager] setBoarderColorGray:self.emailBG];
    }
    if ([self.textPassword.text isEqualToString:@""]) {
        [[ConnectionManager sharedManager] setBoarderColorRed:self.passBG];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorGray:self.passBG];
    }
    
    return YES;
}



/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Forgot Password
 * @Modified Date 9/07/2015
 */
#pragma mark Forgot Password
- (IBAction)forgotPasswordBtnPressed:(id)sender {
    [self performSegueWithIdentifier:@"forgot" sender:nil];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
