//
//  ContactViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 9/29/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "TeamMemberDetailViewController.h"
#import "ComposeMessageViewController.h"
@protocol TeamClassDelegate <NSObject>

-(void)callHomeScreen;

@end

@interface TeamViewController : UIViewController <UIActionSheetDelegate, UITextFieldDelegate , ComposeMessageDelegate> {
        
    //For Contact And Partner
    __weak IBOutlet UIView *norecordView;
    __weak IBOutlet UILabel *lblNoRecord;
    
    __weak IBOutlet UIButton *btnSearchMore;
    __weak IBOutlet UIButton *btnSelectAll;
    __weak IBOutlet UIButton *btnReferral;
    __weak IBOutlet UIButton *btnMessage;
    __weak IBOutlet UIView *botomView;
    __weak IBOutlet UIButton *btnReceived;
    __weak IBOutlet UIButton *btnSent;
    __weak IBOutlet UIButton *btnReceivedArchive;
    __weak IBOutlet UIScrollView *contantScroll;
    
    NSMutableArray *recordArray;
    NSMutableArray *selectedRowForDeleteArray;
    
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
    NSMutableArray *memberRecordArray;
    NSString *teamMemberIDStr;
    int saveIndex;
    
    //For Goal
    __weak IBOutlet UIView *viewInviteFriend;
    __weak IBOutlet UIImageView *imgBgInviteFriend;
    __weak IBOutlet TPKeyboardAvoidingScrollView *scrollInviteFriend;
    __weak IBOutlet UIButton *btnSendInvitation;
    __weak IBOutlet UIImageView *imgGoalOne;
    __weak IBOutlet UITextField *textFieldGoalOne;
    __weak IBOutlet UIImageView *imgGoalTwo;
    __weak IBOutlet UITextField *textFieldGoalTwo;
    __weak IBOutlet UIImageView *imgGoalThree;
    __weak IBOutlet UITextField *textFieldGoalThree;
    __weak IBOutlet UIImageView *imgGoalFour;
    __weak IBOutlet UITextField *textFieldGoalFour;
    __weak IBOutlet UIImageView *imgGoalFive;
    __weak IBOutlet UITextField *textFieldGoalFive;
    __weak IBOutlet UIImageView *imgGoalSix;
    __weak IBOutlet UITextField *textFieldGoalSix;
    NSDictionary *goalDict;
    
}
@property (weak, nonatomic) IBOutlet UILabel *lblNotiCount;
@property (nonatomic , weak) id<TeamClassDelegate>delegateTeamClass;
@property (nonatomic, strong) UIImage *selectedImage;
@property (nonatomic, strong) UIImage *unSelectedImage;
@property (nonatomic, strong) UIImage *readMsgImage;
@property (nonatomic, strong) UIImage *unReadMsgImage;
@property (weak, nonatomic) IBOutlet UITableView *tableView;

@end
