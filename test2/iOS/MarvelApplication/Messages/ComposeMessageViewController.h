//
//  ComposeMessageViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 9/9/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "UIPlaceHolderTextView.h"
#import "SelectTeamMemberViewController.h"
#import "UploadViewController.h"
@protocol ComposeMessageDelegate <NSObject>

-(void)updateTeamRecord;

@end
@interface ComposeMessageViewController : UIViewController <UIPopoverControllerDelegate , UIImagePickerControllerDelegate , UINavigationControllerDelegate , selectTeamMemberDelegate , UploadViewControllerDelegate , UIActionSheetDelegate> {
   
    __weak IBOutlet TPKeyboardAvoidingScrollView *scrollView;
    __weak IBOutlet UIButton *btnNext;
    __weak IBOutlet UIPlaceHolderTextView *msgTextView;
    __weak IBOutlet UIScrollView *scrollAttach;
    __weak IBOutlet UIButton *btnAttach;
    UIImagePickerController *imagePicker;
    UIPopoverController *popOver;
    NSMutableString *heavyDataName;
    long long maxVideoSizeUpload;
    NSMutableArray *showAttechedArray;
    NSMutableArray *heavyDataPathArray;
    int indexAttach;
    NSMutableArray *teamMemberArray;
    UploadViewController *tempUpload;
}
@property (nonatomic , weak) id<ComposeMessageDelegate>teamDelegate;
@property (nonatomic , strong) NSMutableArray *recordArr;
@property (weak, nonatomic) IBOutlet UIImageView *imgControlBG;
@property (weak, nonatomic) IBOutlet UIImageView *imgBgTextField;
@property (weak, nonatomic) IBOutlet UITextField *textField;
@property (weak, nonatomic) IBOutlet UIImageView *imgBgSubTextField;
@property (weak, nonatomic) IBOutlet UITextField *subTextField;
@property (weak, nonatomic) IBOutlet UIImageView *imgBgMsgTextView;
@property (weak, nonatomic) IBOutlet UIImageView *imgAttachDocBG;

@end
