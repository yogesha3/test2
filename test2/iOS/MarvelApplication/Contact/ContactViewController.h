//
//  ContactViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 9/29/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "AddPartnersViewController.h"
#import "ContactDetailViewController.h"

#import "FPPopoverKeyboardResponsiveController.h"
#import "ARCMacros.h"
#import "DemoTableController.h"
#import "FPPopoverController.h"

@protocol ContactClassDelegate <NSObject>

-(void)callHomeScreen;
-(void)callReferralScreenForReferMe:(NSDictionary *)dict;

@end

@interface ContactViewController : UIViewController <UIActionSheetDelegate,AddPartnersDelegate , UITextFieldDelegate , ContactDetailDelegate, FPPopoverControllerDelegate> {
    
    FPPopoverKeyboardResponsiveController *popover;
    CGFloat _keyboardHeight;
    
    __weak IBOutlet UILabel *lblTopTitle;
    
    //For Invite Partner
    __weak IBOutlet UIView *viewInviteFriend;
    __weak IBOutlet UIImageView *imgBgInviteFriend;
    __weak IBOutlet TPKeyboardAvoidingScrollView *scrollInviteFriend;
    __weak IBOutlet UIButton *btnSendInvitation;
    
    __weak IBOutlet UIView *viewPartnerNameEmail;
    UIButton *addButton;
    __weak IBOutlet UILabel *lblPartnerName;
    __weak IBOutlet UIImageView *imgPartnerName;
    __weak IBOutlet UITextField *textFieldPartnerName;
    __weak IBOutlet UILabel *lblPartnerEmail;
    __weak IBOutlet UIImageView *imgPartnerEmail;
    __weak IBOutlet UITextField *textFieldPartnerEmail;
    
    __weak IBOutlet UIView *viewCommentPartner;
    __weak IBOutlet UIImageView *imgCommentPartner;
    __weak IBOutlet UITextView *textViewPartnerComment;
    NSMutableArray *arrayInvitePartner;
    NSMutableArray *showTextArray;
    int hight;
    
    //For Contact And Partner
    __weak IBOutlet UIView *norecordView;
    __weak IBOutlet UILabel *lblNoRecord;
    
    __weak IBOutlet UIButton *btnSearchMore;
    __weak IBOutlet UIButton *btnSelectAll;
    __weak IBOutlet UIButton *btnDelete;
    __weak IBOutlet UIView *botomView;
    __weak IBOutlet UIButton *btnReceived;
    __weak IBOutlet UIButton *btnSent;
    __weak IBOutlet UIButton *btnReceivedArchive;
    __weak IBOutlet UIScrollView *contantScroll;
    
    NSMutableArray *recordArray;
    NSMutableArray *selectedRowForDeleteArray;
    
    id<ContactClassDelegate>delegateContactClass;
    
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
    NSString *contactIDStr;
    int saveIndex;
    NSString *defaultMessageStr;
    int contactIndex;
}
@property (weak, nonatomic) IBOutlet UILabel *lblNotiCount;
@property (nonatomic , strong) id<ContactClassDelegate>delegateContactClass;
@property (nonatomic, strong) UIImage *selectedImage;
@property (nonatomic, strong) UIImage *unSelectedImage;
@property (nonatomic, strong) UIImage *readMsgImage;
@property (nonatomic, strong) UIImage *unReadMsgImage;
@property (weak, nonatomic) IBOutlet UIScrollView *scrollView;
@property (weak, nonatomic) IBOutlet UITableView *tableView;

-(void)selectedTableRow:(NSUInteger)rowNum;

@end
