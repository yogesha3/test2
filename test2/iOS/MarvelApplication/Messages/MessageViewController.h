//
//  ReferralViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/20/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "MessageDetailViewController.h"

@protocol MessageClassDelegate <NSObject>

-(void)callHomeScreen;

@end

@interface MessageViewController : UIViewController <UIActionSheetDelegate,messageDetailDelegate> {
    
    
    __weak IBOutlet UIView *norecordView;
    __weak IBOutlet UILabel *lblNoRecord;
    
    __weak IBOutlet UIButton *btnSearchMore;
    __weak IBOutlet UIButton *btnSelectAll;
    __weak IBOutlet UIButton *btnDelete;
    __weak IBOutlet UIButton *btnReadUnRead;
    __weak IBOutlet UIView *botomView;
    __weak IBOutlet UIButton *btnReceived;
    __weak IBOutlet UIButton *btnSent;
    __weak IBOutlet UIButton *btnSentArchive;
    __weak IBOutlet UIButton *btnReceivedArchive;
    __weak IBOutlet UIScrollView *contantScroll;
    
    NSMutableArray *recordArray;
    NSMutableArray *selectedRowForDeleteArray;
    
    id<MessageClassDelegate>delegateMessageClass;
    
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
    NSString *messageIDStr;
    int saveIndex;
}
@property (weak, nonatomic) IBOutlet UILabel *lblNotiCount;
@property (nonatomic , strong) id<MessageClassDelegate>delegateMessageClass;
@property (nonatomic, strong) UIImage *selectedImage;
@property (nonatomic, strong) UIImage *unSelectedImage;
@property (nonatomic, strong) UIImage *readMsgImage;
@property (nonatomic, strong) UIImage *unReadMsgImage;
@property (weak, nonatomic) IBOutlet UIScrollView *scrollView;
@property (weak, nonatomic) IBOutlet UITableView *tableView;

@end
