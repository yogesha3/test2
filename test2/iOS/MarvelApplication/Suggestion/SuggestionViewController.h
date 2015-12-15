//
//  SuggestionViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 12/1/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "UIPlaceHolderTextView.h"

@protocol SuggestionViewControllerDelegate <NSObject>

-(void)callHomeScreen;

@end

@interface SuggestionViewController : UIViewController {

    __weak IBOutlet TPKeyboardAvoidingScrollView *scrollView;
    __weak IBOutlet UIImageView *imgBg;
    __weak IBOutlet UIImageView *imgCommentBG;
    __weak IBOutlet UIPlaceHolderTextView *textView;
    __weak IBOutlet UIButton *btnSubmit;
    __weak IBOutlet UIView *botomView;
}

@property (nonatomic , weak) id<SuggestionViewControllerDelegate>delegateSuggestionClass;
@property (weak, nonatomic) IBOutlet UILabel *lblNotiCount;
@end
