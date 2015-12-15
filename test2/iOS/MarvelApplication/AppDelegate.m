//
//  AppDelegate.m
//  MarvelApplication
//
//  Created by Deepak Chauhan on 7/8/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "AppDelegate.h"
#import "LoginViewController.h"
#import "ReferralViewController.h"
#import "RearViewController.h"
#import "AccountViewController.h"

@interface AppDelegate ()

@end

@implementation AppDelegate

@synthesize notificationTimer , notificationReferralsCount , notificationMessageCount ,notificationReferralsCountView ,
notificationMessageCountView , updateUserRoleString;

@synthesize window;

+(AppDelegate*)currentDelegate {
    return (AppDelegate*)[[UIApplication sharedApplication] delegate];
}

/**
 *
 * @Description For add loading
 * @author Deepak Kumar.
 *
 **/
#pragma mark Add Loading
-(void)addLoading {
    [DSBezelActivityView  newActivityViewForView:self.window];
}

/**
 *
 * @Description For remove loading
 * @author Deepak Kumar.
 *
 **/
#pragma mark Remove Loading
-(void)removeLoading {
    [DSBezelActivityView  removeViewAnimated:NO];
}


- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {
    // Override point for customization after application launch.
    
    [userDefaultManager setObject:@"123" forKey:@"DeviceTokenKey"];
    
    [userDefaultManager setObject:@"0" forKey:@"menuSelect"];
    //for check user are login or not
    NSString *checkLogin = [[NSUserDefaults standardUserDefaults] valueForKey:@"login"];
    if ([checkLogin isEqualToString:@"loginSuccess"]) {
        [self showSplashScreen];
        [self updateNotification];
    }
    
    [[ConnectionManager sharedManager] setServiceURLHost:RegisterURL];
    [[ConnectionManager sharedManager] setHashKey:appkey];
    
    [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
    internetReach = [Reachability reachabilityForInternetConnection];
    [internetReach startNotifier];
    [self updateInterfaceWithReachability: internetReach];
    
    // Let the device know we want to receive push notifications
    if([[[UIDevice currentDevice] systemVersion] integerValue]<8.0)
    {
        UIRemoteNotificationType myTypes = UIRemoteNotificationTypeBadge | UIRemoteNotificationTypeAlert | UIRemoteNotificationTypeSound;
        [[UIApplication sharedApplication] registerForRemoteNotificationTypes:myTypes];
    }
    else
    {
        
        UIUserNotificationSettings *settings = [UIUserNotificationSettings settingsForTypes:(UIRemoteNotificationTypeBadge
                                                                                             |UIRemoteNotificationTypeSound
                                                                                             |UIRemoteNotificationTypeAlert) categories:nil];
        [[UIApplication sharedApplication] registerUserNotificationSettings:settings];
    }
    
    return YES;
}

- (BOOL)application:(UIApplication *)application handleOpenURL:(NSURL *)url {
    
    NSLog(@"%@",url.host);
    
    NSString *messageString = @"";
    NSString *callBackString = @"";
    
    //For First Time Login
    if ([url.host isEqualToString:@"twitter"]) {
        callBackString = @"twitter";
        messageString = @"Your Twitter account has been successfully linked.";
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:messageString delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
        [alert show];
    }
    else if ([url.host isEqualToString:@"facebook"]) {
        callBackString = @"facebook";
        messageString = @"Your Facebook account has been successfully linked.";
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:messageString delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
        [alert show];
    }
    else if ([url.host isEqualToString:@"linkedin"]) {
        callBackString = @"linkedin";
        messageString = @"Your LinkedIn account has been successfully linked.";
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:messageString delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
        [alert show];
    }
    //For Already Login
    else if ([url.host isEqualToString:@"alreadytwitter"]) {
        callBackString = @"twitter";
        messageString = @"Your Twitter account has already been linked.";
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:messageString delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
        [alert show];
    }
    else if ([url.host isEqualToString:@"alreadyfacebook"]) {
        callBackString = @"facebook";
        messageString = @"Your Facebook account has already been linked.";
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:messageString delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
        [alert show];
    }
    else if ([url.host isEqualToString:@"alreadylinkedin"]) {
        callBackString = @"linkedin";
        messageString = @"Your LinkedIn account has already been linked.";
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:messageString delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
        [alert show];
    }
    
    UINavigationController *navController = (UINavigationController *) [self.window rootViewController];
    SWRevealViewController *swRevealController = [navController.viewControllers objectAtIndex:0];
    UINavigationController *nController = (UINavigationController *) swRevealController.frontViewController;
    AccountViewController *VC = [nController.viewControllers objectAtIndex:0];
    [VC updateSocialMediaInfocallBack:callBackString];

    return YES;
}

#pragma mark push notification

#ifdef __IPHONE_8_0
- (void)application:(UIApplication *)application didRegisterUserNotificationSettings:(UIUserNotificationSettings *)notificationSettings
{
    //register to receive notifications
    [application registerForRemoteNotifications];
}

- (void)application:(UIApplication *)application handleActionWithIdentifier:(NSString *)identifier forRemoteNotification:(NSDictionary *)userInfo completionHandler:(void(^)())completionHandler
{
    //handle the actions
    if ([identifier isEqualToString:@"declineAction"]){
    }
    else if ([identifier isEqualToString:@"answerAction"]){
    }
}
#endif

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Splash Screen
 * @Modified Date 9/07/2015
 */
#pragma mark showSplashScreen
-(void)showSplashScreen {
    
    CGSize result = [[UIScreen mainScreen] bounds].size;
    
    splassView = [[UIView alloc] init];
    splassView.frame = CGRectMake(0, 0, result.width, result.height);
    [splassView setBackgroundColor:[UIColor whiteColor]];
    [splassView setBackgroundColor:[UIColor clearColor]];
    [self.window.rootViewController.view addSubview:splassView];
    [self.window.rootViewController.view bringSubviewToFront:splassView];
    
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

/*
 * @Auther Deepak chauhan
 * @Parm application , deviceToken
 * @Description This delegate method are used for get the device token
 * @Modified Date 24/07/2015
 */
#pragma mark didRegisterForRemoteNotificationsWithDeviceToken
- (void)application:(UIApplication*)application didRegisterForRemoteNotificationsWithDeviceToken:(NSData*)deviceToken
{
    NSString *Token = [[deviceToken description] stringByTrimmingCharactersInSet: [NSCharacterSet characterSetWithCharactersInString:@"<>"]];
    Token = [Token stringByReplacingOccurrencesOfString:@" " withString:@""];
    [userDefaultManager setObject:Token forKey:@"DeviceTokenKey"];
}
/*
 * @Auther Deepak chauhan
 * @Parm application , userInfo
 * @Description This delegate method are used user information
 * @Modified Date 9/07/2015
 */
#pragma mark didReceiveRemoteNotification
- (void)application:(UIApplication *)application didReceiveRemoteNotification:(NSDictionary *)userInfo {
    
//    if ( application.applicationState == UIApplicationStateInactive || application.applicationState == UIApplicationStateBackground  )
//    {
//        
//        ///celeb profile
//        ////schedule, reschedule,  Reminding fans regarding their pre-registered calls
//        [self performSelector:@selector(jumpToNotificationViewWithInfo:) withObject:userInfo afterDelay:1.0];
//    }
//    else{
        [[NotificationView sharedView] showMessage:userInfo];
//    }
}

/*
 * @Auther Deepak chauhan
 * @Parm application , error
 * @Description This delegate method are used when error will be occured to gat the device token
 * @Modified Date 24/07/2015
 */
#pragma mark didFailToRegisterForRemoteNotificationsWithError
- (void)application:(UIApplication*)application didFailToRegisterForRemoteNotificationsWithError:(NSError*)error
{
    NSLog(@"Failed to get token, error: %@", error);
}

/*
 * @Auther Deepak chauhan
 * @Parm note
 * @Description Called by Reachability whenever status changes.
 * @Modified Date 24/07/2015
 */
#pragma mark reachabilityChanged
- (void) reachabilityChanged: (NSNotification* )note
{
    Reachability* curReach = [note object];
    NSParameterAssert([curReach isKindOfClass: [Reachability class]]);
    
    NetworkStatus netStatus = [curReach currentReachabilityStatus];
    switch (netStatus)
    {
        case ReachableViaWWAN:
        {
            [[NSUserDefaults standardUserDefaults] setBool:YES forKey:@"isNetworkAvailable"];
            [[NSUserDefaults standardUserDefaults] synchronize];
          
            break;
        }
        case ReachableViaWiFi:
        {
            [[NSUserDefaults standardUserDefaults] setBool:YES forKey:@"isNetworkAvailable"];
            [[NSUserDefaults standardUserDefaults] synchronize];
            break;
        }
        case NotReachable:
        {
            [[NSUserDefaults standardUserDefaults] setBool:NO forKey:@"isNetworkAvailable"];
            [[NSUserDefaults standardUserDefaults] synchronize];
            break;
        }
    }
}
/*
 * @Auther Deepak chauhan
 * @Parm note
 * @Description This method are used for update interface with reachability
 * @Modified Date 24/07/2015
 */
#pragma mark updateInterfaceWithReachability
- (void) updateInterfaceWithReachability: (Reachability*) curReach {
    
    if(curReach == internetReach) {
        
        NetworkStatus netStatus = [curReach currentReachabilityStatus];
        BOOL connectionRequired= [curReach connectionRequired];
        NSString* statusString= @"";
        switch (netStatus)
        {
            case NotReachable:
            {
                statusString = @"Access Not Available";
                //Minor interface detail- connectionRequired may return yes, even when the host is unreachable.  We cover that up here...
                connectionRequired= NO;
                //isWifiAvailable=NO;
                [[NSUserDefaults standardUserDefaults] setBool:NO forKey:@"isNetworkAvailable"];
                [[NSUserDefaults standardUserDefaults] synchronize];
                break;
            }
                
            case ReachableViaWWAN:
            {
                statusString = @"Reachable WWAN";
                [[NSUserDefaults standardUserDefaults] setBool:YES forKey:@"isNetworkAvailable"];
                [[NSUserDefaults standardUserDefaults] synchronize];
                break;
            }
            case ReachableViaWiFi:
            {
                statusString= @"Reachable WiFi";
                //isWifiAvailable=YES;
                [[NSUserDefaults standardUserDefaults] setBool:YES forKey:@"isNetworkAvailable"];
                [[NSUserDefaults standardUserDefaults] synchronize];
                break;
            }
        }
        if(connectionRequired) {
            statusString= [NSString stringWithFormat: @"%@, Connection Required", statusString];
        }
        NSLog(@"%@",statusString);
    }
}


/**
 *
 * Remove application temp folder
 * @author Deepak Kumar.
 *
 **/
-(void)removeTempFiles {
    NSString *tempPath = NSTemporaryDirectory();
    NSArray *fileList = [[NSFileManager defaultManager] contentsOfDirectoryAtPath:tempPath error:nil];
    NSFileManager *fileManager = [NSFileManager defaultManager];
    
    if (fileList) {
        for (int i = 0; i < [fileList count]; i++) {
            NSString *contentsOnly = [NSString stringWithFormat:@"%@%@", tempPath, [fileList objectAtIndex:i]];
            [fileManager removeItemAtPath:contentsOnly error:nil];
        }
    }
    
    tempPath = [self applicationDocumentsDirectory];
    fileList = [[NSFileManager defaultManager] contentsOfDirectoryAtPath:tempPath error:nil];
    if (fileList) {
        for (int i = 0; i < [fileList count]; i++) {
            NSString *contentsOnly = [NSString stringWithFormat:@"%@/%@", tempPath, [fileList objectAtIndex:i]];
            [fileManager removeItemAtPath:contentsOnly error:nil];
        }
    }
}

/**
 *
 * Remove application directory folder
 * @author Deepak Kumar.
 *
 **/
- (NSString *)applicationDocumentsDirectory {
    
    NSArray *paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES);
    NSString *basePath = ([paths count] > 0) ? [paths objectAtIndex:0] : nil;
    return basePath;
}

- (void)applicationWillResignActive:(UIApplication *)application {
    
    [notificationTimer invalidate];
    notificationTimer = nil;
    // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
    // Use this method to pause ongoing tasks, disable timers, and throttle down OpenGL ES frame rates. Games should use this method to pause the game.
}

- (void)applicationDidEnterBackground:(UIApplication *)application {
    [[NSNotificationCenter defaultCenter] postNotificationName:@"TrainingVideoPaus" object:nil];
    [[NSNotificationCenter defaultCenter] removeObserver:self name: kReachabilityChangedNotification object: nil];
    
    // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later.
    // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
}

- (void)applicationWillEnterForeground:(UIApplication *)application {
    [[NSNotificationCenter defaultCenter] removeObserver:self name:@"TrainingVideoPaus" object:nil];
    // Called as part of the transition from the background to the inactive state; here you can undo many of the changes made on entering the background.
}

- (void)applicationDidBecomeActive:(UIApplication *)application {
    
     [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
    
    //for check user are login or not
    NSString *checkLogin = [[NSUserDefaults standardUserDefaults] valueForKey:@"login"];
    if ([checkLogin isEqualToString:@"loginSuccess"]) {
        [self startTimerForNotification];
    }    
    // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Start Timer
 * @Modified Date 13/08/2015
 */
#pragma mark Start Timer
-(void)startTimerForNotification {

    [notificationTimer invalidate];
    notificationTimer=nil;
    notificationTimer = [NSTimer scheduledTimerWithTimeInterval:5 target:self selector:@selector(updateNotification) userInfo:nil repeats:YES];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Update Notification
 * @Modified Date 13/08/2015
 */
#pragma mark Update Notification
-(void)updateNotification {
    if (netWorkNotAvilable) {
        //For Message And Referrals Count
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequestNotification:@"" methodHeader:@"getUnreadCount" controls:@"app" httpMethod:@"POST" data:nil];
        if (parsedJSONToken != nil) {
            NSDictionary *dict = [parsedJSONToken valueForKey:@"result"];
            NSString *referralsUnReadString = [NSString stringWithFormat:@"%@",[[dict valueForKey:@"referralUnread"] stringValue]];
            NSString *messageUnReadString = [NSString stringWithFormat:@"%@",[[dict valueForKey:@"messageUnread"] stringValue]];
             NSString *totlaString = [NSString stringWithFormat:@"%@",[[dict valueForKey:@"total"] stringValue]];
             NSString *referralsUnReadCurrentString = [NSString stringWithFormat:@"%@",[[dict valueForKey:@"referralTotalUnread"] stringValue]];
             NSString *messageUnReadCurrentString = [NSString stringWithFormat:@"%@",[[dict valueForKey:@"messageTotalUnread"] stringValue]];
             NSString *updateUserIdStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"user_name"]];
            
            NSString *userRoleString = [[NSString stringWithFormat:@"%@",[dict valueForKey:@"user_role"]] uppercaseString];
            
            [AppDelegate currentDelegate].updateUserRoleString = userRoleString;
            [AppDelegate currentDelegate].notificationReferralsCount = referralsUnReadString;
            [AppDelegate currentDelegate].notificationMessageCount = messageUnReadString;
            [AppDelegate currentDelegate].notificationTotalCount = totlaString;
            [AppDelegate currentDelegate].notificationReferralsCountView = referralsUnReadCurrentString;
            [AppDelegate currentDelegate].notificationMessageCountView = messageUnReadCurrentString;
            [AppDelegate currentDelegate].updateUserIdString = updateUserIdStr;
            
            [[NSNotificationCenter defaultCenter] postNotificationName:@"referralsCount" object:nil];
            
            NSString *loginOrNotStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"is_login"]];
            if (![loginOrNotStr isEqualToString:@"yes"]) {
                [notificationTimer invalidate];
                notificationTimer=nil;
                UINavigationController *nav  = (UINavigationController *)self.window.rootViewController;
                SWRevealViewController *rvc = [[nav viewControllers] objectAtIndex:0];
                RearViewController *rear = (RearViewController *)rvc.rearViewController;
                [rear logOutFromOtherDevice];
            }
        }
    }
}

- (void)applicationWillTerminate:(UIApplication *)application {
    // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
}

@end
