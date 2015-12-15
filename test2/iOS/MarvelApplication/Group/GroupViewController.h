//
//  GroupViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 10/15/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
@protocol GroupClassDelegate <NSObject>
-(void)logOutUserFromGroupClass;
-(void)GoToHomeScreenFromGroupClass;
@end

@interface GroupViewController : UIViewController <UIActionSheetDelegate , UIPickerViewDelegate> {
    __weak IBOutlet UIView *botomView;
    __weak IBOutlet UILabel *lblTopTitle;
    __weak IBOutlet UIButton *btnLogOut;
    __weak IBOutlet UIButton *btnBack;
    __weak IBOutlet UITableView *tableView;
    
    __weak IBOutlet UIView *viewNoRecord;
    __weak IBOutlet UILabel *lblNoRecord;
    //For Local Filter
    __weak IBOutlet UIView *viewLocalFilter;
    __weak IBOutlet UIButton *btnApply;
    __weak IBOutlet UIButton *btnCancel;
    __weak IBOutlet TPKeyboardAvoidingScrollView *scrollLocalFilter;
    __weak IBOutlet UIButton *btnMostMemberLocal;
    __weak IBOutlet UIButton *btnNewestLocal;
    UIActionSheet *actionSheet;
    
    //For Gloval Filter
    __weak IBOutlet UIView *viewGlobalFilter;
    __weak IBOutlet UIButton *btnGlobalApply;
    __weak IBOutlet UIButton *btnGlobalCancel;
    __weak IBOutlet TPKeyboardAvoidingScrollView *scrollGlobalFilter;
    __weak IBOutlet UIButton *btnMostMemberGlobal;
    __weak IBOutlet UIButton *btnNewestGlobal;

    UIPopoverController *popoverController;
    UIAlertController *projectSelector;
    NSMutableArray *recordArray;
    UIPickerView *prefrencePikerView;
    UIToolbar *prefrenceToolBar;
    int selectMilesIndex , saveMilesIndex;
    NSArray *milesArray;
    NSArray *dayArray;
    NSArray *timeArray;
    NSString *viewIdentify;
    
    UIView *dayTimeBGView;
    UIView *dayTimeView;
    TPKeyboardAvoidingScrollView *scrollDayTime;
    NSMutableArray *addTimeArray;
    NSMutableArray *addDayArray;
    NSMutableArray *populateDayArray;
    NSMutableArray *populateTimeArray;
    
    NSMutableArray *popDayLocalArray;
    NSMutableArray *popTimeLocalArray;
    
    NSMutableArray *popDayGlobalArray;
    NSMutableArray *popTimeGlobalArray;
    
    NSMutableArray *actualDaySelectedArray;
    NSMutableArray *actualTimeSelectArray;
    
    BOOL dateFormateCheck;
    NSString *selectedGroupIdString;
}
@property (nonatomic , weak) id<GroupClassDelegate>groupDelegate;
@property (nonatomic, strong) UIImage *selectedImage;
@property (nonatomic, strong) UIImage *unSelectedImage;
@property (nonatomic , strong) NSString *checkString;
@end
