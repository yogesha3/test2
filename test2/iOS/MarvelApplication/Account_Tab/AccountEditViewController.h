//
//  AccountEditViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 10/24/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "UIPlaceHolderTextView.h"
#import <MessageUI/MessageUI.h>
#import "UploadViewController.h"
@protocol AccountEditDelegate <NSObject>

-(void)updateProfileDetail;

@end
@interface AccountEditViewController : UIViewController <MFMailComposeViewControllerDelegate , UploadViewControllerDelegate , UIActionSheetDelegate , UIPopoverControllerDelegate , UIImagePickerControllerDelegate , UINavigationControllerDelegate> {

    __weak IBOutlet UIView *viewInfoUser;
    __weak IBOutlet UIImageView *imgBG;
    __weak IBOutlet TPKeyboardAvoidingScrollView *scrollView;
    __weak IBOutlet UIButton *btnUpdate;
    __weak IBOutlet UIView *viewControl;
    
    UIPopoverController *popOver;
    UIImagePickerController *imagePicker;
    UploadViewController *tempUpload;
    NSMutableArray *heavyDataPathArray;
}
@property (nonatomic , weak) id<AccountEditDelegate>profileDelegate;
@end
