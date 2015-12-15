//
//  AppDelegate.h
//  MarvelApplication
//
//  Created by Deepak Chauhan on 7/8/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
@class Reachability;

@interface AppDelegate : UIResponder <UIApplicationDelegate> {

    UIView *splassView;;
    Reachability *internetReach;
    NSTimer *notificationTimer;
    
}
@property (nonatomic , strong) NSString *updateUserRoleString;
@property (nonatomic , strong) NSString *updateUserIdString;
@property (nonatomic , strong) NSString *notificationReferralsCount;
@property (nonatomic , strong) NSString *notificationMessageCount;
@property (nonatomic , strong) NSString *notificationTotalCount;
@property (nonatomic , strong) NSString *notificationReferralsCountView;
@property (nonatomic , strong) NSString *notificationMessageCountView;
@property (nonatomic , strong) NSTimer *notificationTimer;
@property (strong, nonatomic) UIWindow *window;

+(AppDelegate*)currentDelegate;
-(void)addLoading;
-(void)removeLoading;
-(void)removeTempFiles;
-(void)startTimerForNotification;
-(void)updateNotification;

@end

