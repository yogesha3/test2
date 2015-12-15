//
//  SettingViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 10/20/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
@protocol SettingClassDelegate <NSObject>

-(void)callHomeScreen;

@end
@interface SettingViewController : UIViewController {

    __weak IBOutlet UIView *botomView;
    __weak IBOutlet UIButton *btnSelectAll;
    __weak IBOutlet UILabel *lblTop;
     __weak IBOutlet UIScrollView *contantScroll;
    __weak IBOutlet UIView *viewChangePassword;
    __weak IBOutlet TPKeyboardAvoidingScrollView *passwordScroll;
    __weak IBOutlet UITableView *tableView;
    __weak IBOutlet UIButton *btnSaveNoti;
    NSMutableArray *notificationArray;
    NSMutableArray *finalArray;
    NSMutableArray *populateArray;
}
@property (nonatomic , weak) id<SettingClassDelegate>delegateSettingClass;
@property (nonatomic, strong) UIImage *selectedImage;
@property (nonatomic, strong) UIImage *unSelectedImage;
@property (weak, nonatomic) IBOutlet UILabel *lblNotiCount;
@end
