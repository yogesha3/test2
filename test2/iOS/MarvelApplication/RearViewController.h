//
//  RearViewController.h
//  PayToAfrica
//
//  Created by A3logics on 8/29/14.
//  Copyright (c) 2014 Deepak kumar. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "ReferralViewController.h"
#import "MessageViewController.h"
#import "ContactViewController.h"
#import "TeamViewController.h"
#import "AccountViewController.h"
#import "SettingViewController.h"
#import "SuggestionViewController.h"
#import "WebCastViewController.h"

@interface RearViewController : UIViewController <UIGestureRecognizerDelegate , ReferralViewControllerDelegate , MessageClassDelegate , ContactClassDelegate , TeamClassDelegate , SettingClassDelegate , AccountViewControllerDelegate , SuggestionViewControllerDelegate , WebCastViewControllerDelegate> {
    
    IBOutlet UITableView *tableVIew;
    NSArray *items;
    UIView *vieww;
    BOOL flag;
    BOOL LoginAnothDevic;
    NSString *checkProfile;
    NSDictionary *referMeDict;
}
@property (weak, nonatomic) IBOutlet UIButton *btnProfileImage;
@property (weak, nonatomic) IBOutlet UILabel *lblName;
@property (weak, nonatomic) IBOutlet UILabel *lblSubName;
@property (weak, nonatomic) IBOutlet UILabel *lblGroupTypr;
@property (weak, nonatomic) IBOutlet UILabel *lblGroupNo;
@property (weak, nonatomic) IBOutlet UIImageView *imgStarOne;
@property (weak, nonatomic) IBOutlet UIImageView *imgStarTwo;
@property (weak, nonatomic) IBOutlet UIImageView *imgStarThree;
@property (weak, nonatomic) IBOutlet UIImageView *imgStarFour;
@property (weak, nonatomic) IBOutlet UIImageView *imgStarFive;
@property (weak, nonatomic) IBOutlet UILabel *lblDate;
@property (weak, nonatomic) IBOutlet UIProgressView *progressIndicator;
@property (weak, nonatomic) IBOutlet UILabel *lblProgressStatus;

-(void)logOut;
-(void)logOutFromOtherDevice;
-(void)dismissRevalView;

@end
