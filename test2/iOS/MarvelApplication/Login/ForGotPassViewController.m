//
//  ForGotPassViewController.m
//  MarvelApplication
//
//  Created by Deepak Chauhan on 7/9/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "ForGotPassViewController.h"

@interface ForGotPassViewController ()

@end

@implementation ForGotPassViewController

- (void)viewDidLoad {

    UIFont *fontLight;
    UIFont *fontBoald;
    if (CheckDeviceFunction()) {
        fontLight = [UIFont fontWithName:@"Graphik-Light" size:25.0];
        fontBoald = [UIFont fontWithName:@"Graphik-Bold" size:25.0];
    }
    else {
        fontLight = [UIFont fontWithName:@"Graphik-Light" size:17.0];
        fontBoald = [UIFont fontWithName:@"Graphik-Bold" size:17.0];
    }
    
    NSString *titleStr = @"Forgot Your Password?";
    NSMutableAttributedString * string = [[NSMutableAttributedString alloc] initWithString:titleStr];
    [string addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:NSMakeRange(0,21)];
    [string addAttribute:NSFontAttributeName value:fontLight range:NSMakeRange(0,21)];
    
    self.lblTitle.attributedText = string;
    
    imgEmailBG.layer.borderWidth = 1.0f;
    imgEmailBG.layer.borderColor = [UIColor lightGrayColor].CGColor;
    imgEmailBG.layer.cornerRadius = 5.0f;
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Forgot Password
 * @Modified Date 29/07/2015
 */
#pragma mark ForGot Password
-(IBAction)forGotPasswordBtnPrassed:(id)sender {
    [self.view endEditing:YES];
    [[AppDelegate currentDelegate] addLoading];
    [self makeEmptyFieldRed];
    BOOL isFilled = [self checkAllFieldAreFilled];
    if (!isFilled) {
        return;
    }
    [self performSelector:@selector(forgotPasswordDelay) withObject:nil afterDelay:0.1];
}

-(void)forgotPasswordDelay {
    
    if (isNetworkAvailable) {
        //For login
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:textEmail.text,@"user_email",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequestLogin:@"" methodHeader:@"forgotPassword" controls:@"users" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            NSString *successMessage = [parsedJSONToken valueForKey:@"message"];
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:successMessage delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            [self dismissViewControllerAnimated:NO completion:nil];
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [[AppDelegate currentDelegate] removeLoading];
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for check Required Field
 * @Modified Date 9/07/2015
 */
#pragma mark Required Field
-(BOOL)checkAllFieldAreFilled {
    
    // for check email
    NSString *emailString = textEmail.text;
    if ([emailString isEqualToString:@""]) {
        [[AppDelegate currentDelegate] removeLoading];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    BOOL flagValidEmail = validateEmail(emailString);
    if (!flagValidEmail) {
        [[AppDelegate currentDelegate] removeLoading];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Please enter a valid email address." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    
    return YES;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for change the boarder color of Required Field
 * @Modified Date 9/07/2015
 */
#pragma mark Required Field
-(BOOL)makeEmptyFieldRed {
    
    if ([textEmail.text isEqualToString:@""]) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgEmailBG];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorGray:imgEmailBG];
    }
    BOOL flagValidEmail = validateEmail(textEmail.text);
    if (!flagValidEmail) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgEmailBG];
    }
    else {
        [[ConnectionManager sharedManager] setBoarderColorGray:imgEmailBG];
    }
    return YES;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen 
 * @Modified Date 9/07/2015
 */
#pragma mark Back Screen
- (IBAction)backForgotPassword:(id)sender {
    [self.view endEditing:YES];
    [self dismissViewControllerAnimated:NO completion:nil];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
