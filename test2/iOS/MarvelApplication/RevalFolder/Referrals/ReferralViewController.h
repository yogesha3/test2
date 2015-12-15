//
//  ReferralViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/20/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "ReferralsDetailViewController.h"

#import "FPPopoverKeyboardResponsiveController.h"
#import "ARCMacros.h"
#import "DemoTableController.h"
#import "FPPopoverController.h"

@protocol ReferralViewControllerDelegate <NSObject>

-(void)callHomeScreen;

@end

@interface ReferralViewController : UIViewController <UIActionSheetDelegate,referralDetailDelegate , FPPopoverControllerDelegate> {

    FPPopoverKeyboardResponsiveController *popover;
    CGFloat _keyboardHeight;
    
    __weak IBOutlet UIView *norecordView;
    __weak IBOutlet UILabel *lblNoRecord;
    
    __weak IBOutlet UIButton *btnSearchMore;
    __weak IBOutlet UIButton *btnSelectAll;
    __weak IBOutlet UIButton *btnDelete;
    __weak IBOutlet UIView *botomView;
    __weak IBOutlet UIButton *btnReceived;
    __weak IBOutlet UIButton *btnSent;
    __weak IBOutlet UIButton *btnSentArchive;
    __weak IBOutlet UIButton *btnReceivedArchive;
    __weak IBOutlet UIScrollView *contantScroll;
    
    NSMutableArray *recordArray;
    NSMutableArray *selectedRowForDeleteArray;
    
    id<ReferralViewControllerDelegate>delegateOne;
    
    UIView *searchFilterViewBG;
    UIView *searchFilterView;
    UIToolbar *prefrenceToolBar;
    TPKeyboardAvoidingScrollView *scrollView;
    NSArray *shortArray;
    NSArray *shortArrayOne;
    NSArray *shortArrayTwo;
    
    UITextField *textSearch;
    NSMutableArray *addControlArray;
    NSString *filterStr;
    NSString *filterNameStr;
    NSString *preSelectStr;
    NSString *deleteIdString;
    NSString *referralIDStr;
    int saveIndex;
    
    //For more functionality
    UIView *moreButtonView;
    UIView *moreButtonAddView;
    int referralIndex;
}
@property (nonatomic , strong) NSDictionary *checkReferMeDict;
@property (weak, nonatomic) IBOutlet UILabel *lblNotiCount;
@property (nonatomic , strong) id<ReferralViewControllerDelegate>delegateOne;
@property (nonatomic, strong) UIImage *selectedImage;
@property (nonatomic, strong) UIImage *unSelectedImage;
@property (weak, nonatomic) IBOutlet UIScrollView *scrollView;
@property (weak, nonatomic) IBOutlet UITableView *tableView;

-(void)selectedTableRow:(NSUInteger)rowNum;

@end
