//
//  LoginViewController.h
//  MarvelApplication
//
//  Created by Deepak Chauhan on 7/8/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "TPKeyboardAvoidingScrollView.h"


@interface LoginViewController : UIViewController {

    __weak IBOutlet UILabel *lblLoginTitle;
    UIView *splassView;
    
    NSString *userNameString;
    NSString *passwordString;
}


@property (weak, nonatomic) IBOutlet TPKeyboardAvoidingScrollView *scrollView;
@property (weak, nonatomic) IBOutlet UITextField *textEmail;
@property (weak, nonatomic) IBOutlet UITextField *textPassword;

@property (weak, nonatomic) IBOutlet UIImageView *emailBG;
@property (weak, nonatomic) IBOutlet UIImageView *passBG;

@property (weak, nonatomic) IBOutlet UIButton *btnLogin;

@property (weak, nonatomic) IBOutlet UIButton *btnForgot;
@property (nonatomic , strong) NSString *checkloginOrLogoutStr;
@end
