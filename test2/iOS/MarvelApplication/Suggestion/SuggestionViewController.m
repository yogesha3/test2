//
//  SuggestionViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 12/1/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import "SuggestionViewController.h"

@interface SuggestionViewController ()
@property (nonatomic) IBOutlet UIButton* revealButtonItem;
@end

@implementation SuggestionViewController
@synthesize delegateSuggestionClass;

- (void)viewDidLoad {
    
    [self customSetup];
    
    botomView.layer.borderWidth = 1.0f;
    botomView.layer.borderColor = BoarderColour.CGColor;
    
    btnSubmit.layer.cornerRadius = 5.0f;
    
    imgBg.layer.cornerRadius = 5.0f;
    imgBg.clipsToBounds = YES;
    
    imgCommentBG.layer.cornerRadius = 5.0f;
    imgCommentBG.clipsToBounds = YES;
    
    textView.contentInset = UIEdgeInsetsMake(-7.0,0.0,0,0.0);
    [textView setPlaceholder:@"Post your suggestions here..."];
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Reval View
 * @Modified Date 1/12/2015
 */
#pragma mark Custom Setup
- (void)customSetup
{
    SWRevealViewController *revealViewController = self.revealViewController;
    if ( revealViewController )
    {
        [self.revealButtonItem addTarget:self action:@selector( toggle) forControlEvents:UIControlEventTouchUpInside];
        [self.navigationController.navigationBar addGestureRecognizer:revealViewController.panGestureRecognizer];
    }
}

-(void)toggle {
    [self.view endEditing:YES];
    [self.revealViewController revealToggleAnimated:YES];
}


// Called when the view is about to made visible. Default does nothing
-(void)viewWillAppear:(BOOL)animated {
    [self setNotificationValue];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(setNotificationValue) name:@"referralsCount" object:nil];
    [super viewWillAppear:animated];
}

//Called when the view is dismissed, covered or otherwise hidden. Default does nothing
-(void)viewWillDisappear:(BOOL)animated {
    [[NSNotificationCenter defaultCenter] removeObserver:self name:@"referralsCount" object:nil];
    [super viewWillDisappear:animated];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description Here we set the referrals count
 * @Modified Date 1/12/2015
 */
#pragma mark Show Notification Count
-(void)setNotificationValue {
    NSString *notiCountStr =[[AppDelegate currentDelegate] notificationTotalCount];
    if (notiCountStr.integerValue > 99) {
        [self.lblNotiCount setText:@"99+"];
    }
    else {
        if ([notiCountStr isEqualToString:@"0"]) {
            [self.lblNotiCount setText:@""];
        }
        else {
            [self.lblNotiCount setText:notiCountStr];
        }
    }
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for View Notification List
 * @Modified Date 1/12/2015
 */
#pragma mark Get View Notification List
-(IBAction)viewNotificationList:(id)sender {
    if (!CheckStringForNull(self.lblNotiCount.text)) {
        [self performSegueWithIdentifier:@"notification" sender:nil];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Save Suggestion
 * @Modified Date 01/12/2015
 */
#pragma mark Save Suggestion
- (IBAction)saveSuggestion:(id)sender {
    [self makeEmptyFieldRed];
    BOOL isFilled = [self checkAllFieldAreFilled];
    if (!isFilled) {
        return;
    }
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(saveSuggestionDelay) withObject:nil afterDelay:0.1];
}
-(void)saveSuggestionDelay {
    
    //for Suggestions
    NSString *suggestionString = textView.text;
    
    NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:suggestionString,@"suggestion",nil];
    
    NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                       options:NSJSONWritingPrettyPrinted error:nil];
    NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
    
    NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
    
    if (isNetworkAvailable) {
        NSDictionary *parsedJSONResult =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"addSuggestion" controls:@"suggestions" httpMethod:@"POST" data:data];
        
        if (parsedJSONResult != nil) {
            [self.view endEditing:YES];
            UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:[parsedJSONResult valueForKey:@"message"] delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [networkAlert show];
            textView.text = @"";
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
 * @Modified Date 01/12/2015
 */
#pragma mark Required Field
-(BOOL)checkAllFieldAreFilled {
    
    //for Suggestions
    NSString *suggestionString = textView.text;
    if (CheckStringForNull(suggestionString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field is required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (suggestionString.length >500) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Only 500 characters allowed." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    return YES;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for change the boarder color of Required Field
 * @Modified Date 01/12/2015
 */
#pragma mark Required Field
-(BOOL)makeEmptyFieldRed {
    
    // for Suggestions
    NSString *suggestionString = textView.text;
    if (CheckStringForNull(suggestionString) || suggestionString.length>500) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgCommentBG];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgCommentBG];
    }
    return YES;
}



/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used For go to dashboard From Suggestion Class
 * @Modified Date 1/12/2015
 */
#pragma mark Go To Home Screen From Suggestion Class
-(IBAction)GoToHomeScreen:(id)sender {
    if ([self.delegateSuggestionClass respondsToSelector:@selector(callHomeScreen)]) {
        [self.delegateSuggestionClass callHomeScreen];
    }
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
