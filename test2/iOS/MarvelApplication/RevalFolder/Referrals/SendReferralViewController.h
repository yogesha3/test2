//
//  SendReferralViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/29/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "UIPlaceHolderTextView.h"
#import "UploadViewController.h"

@interface SendReferralViewController : UIViewController<UIPopoverControllerDelegate,UIImagePickerControllerDelegate,UINavigationControllerDelegate,UIActionSheetDelegate,UploadViewControllerDelegate> {
    
    
    __weak IBOutlet UIImageView *imgBG;
    __weak IBOutlet UIButton *btnNext;
    __weak IBOutlet TPKeyboardAvoidingScrollView *scrollView;
    __weak IBOutlet UIButton *btnAttach;
    __weak IBOutlet UIImageView *imgBgTextView;
    __weak IBOutlet UIPlaceHolderTextView *textView;
    __weak IBOutlet UIImageView *imgBGAttach;
    __weak IBOutlet UIScrollView *scrollAttach;
    long long maxVideoSizeUpload;
    UIPopoverController *popOver;
    UIImagePickerController *imagePicker;
    NSMutableString *heavyDataName;
    NSMutableArray *showAttechedArray;
    int indexAttach;
    UploadViewController *tempUpload;
    NSMutableArray *heavyDataPathArray;
}
@property (nonatomic , strong)NSMutableDictionary *dataDictionary;
@end