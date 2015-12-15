//
//  SettingViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 10/20/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import "SettingViewController.h"
#import "SettingNotiTableViewCell.h"

#define passwordSpecial        @" "

@interface SettingViewController ()
@property (nonatomic) IBOutlet UIButton* revealButtonItem;
@end

@implementation SettingViewController

@synthesize lblNotiCount;
@synthesize delegateSettingClass;

- (void)viewDidLoad {
    
    tableView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    
    botomView.layer.borderWidth = 1.0f;
    botomView.layer.borderColor = BoarderColour.CGColor;
    
    self.selectedImage = [UIImage imageNamed:@"Selected.png"];
    self.unSelectedImage = [UIImage imageNamed:@"Unselected.png"];
    
    [tableView setHidden:YES];
    [btnSaveNoti setHidden:YES];
    btnSaveNoti.layer.cornerRadius = 5.0f;
    [viewChangePassword setHidden:NO];
    
    self.lblNotiCount.layer.cornerRadius = 5.0f;
    self.lblNotiCount.clipsToBounds = YES;

    contantScroll.contentSize = CGSizeMake(340, 50);
    
    //For reval Controller
    [self customSetup];
    
    //For set the Placeholder
    [self setPlaceHolderAndBg];
    
    //For get the Notification List
    notificationArray = [[NSMutableArray alloc]initWithObjects:@{@"title":@"Weekly summary", @"sub_title":@"The weekly summary email",@"weeklySummery":@"0",@"value":@"weeklySummery"},
                 @{@"title":@"When you receive a referral", @"sub_title":@"Notifies you that another user has sent you a referral and includes details",@"receiveReferral":@"0",@"value":@"receiveReferral"},
                 @{@"title":@"When a comment is made on a referral", @"sub_title":@"Notifies you that the other party has commented on a referral you sent or received",@"commentMadeOnReferral":@"0",@"value":@"commentMadeOnReferral"},
                 @{@"title":@"When you receive a message", @"sub_title":@"Notifies you that another user has sent you a message and includes details",@"receiveMessage":@"0",@"value":@"receiveMessage"},
                 @{@"title":@"When a comment is made on a message", @"sub_title":@"Notifies you that the other party has commented on a message you sent or received",@"commentMadeOnMessage":@"0",@"value":@"commentMadeOnMessage"},
                 @{@"title":@"When you receive an event invitation", @"sub_title":@"Notifies you that another user has sent you an event invitation and includes details",@"receiveEventInvitation":@"0",@"value":@"receiveEventInvitation"},
                 @{@"title":@"When a comment is made on a event", @"sub_title":@"Notifies you that the other party has commented on a calendar event you sent or received",@"commentMadeOnEvent":@"0",@"value":@"commentMadeOnEvent"},nil];

    [self notificationList];
    [self performSelector:@selector(setSelectAllNotiBtnBlur) withObject:nil afterDelay:0.5];
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

-(void)setSelectAllNotiBtnBlur {
    [btnSelectAll setAlpha:0.5f];
    [btnSelectAll setTag:0];
    [btnSelectAll setUserInteractionEnabled:NO];
    [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description Here we set Placeholder And BG
 * @Modified Date 20/10/2015
 */
#pragma mark Set Place Holder
-(void)setPlaceHolderAndBg {

    [(UIImageView *)[viewChangePassword viewWithTag:1] layer].cornerRadius = 5.0;
    [(UIButton *)[viewChangePassword viewWithTag:8] layer].cornerRadius = 5.0;
    [(UIButton *)[viewChangePassword viewWithTag:9] layer].cornerRadius = 5.0;
    
    [(UIImageView *)[passwordScroll viewWithTag:2] layer].cornerRadius = 5.0;
    [(UITextField *)[passwordScroll viewWithTag:5] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [(UIImageView *)[passwordScroll viewWithTag:3] layer].cornerRadius = 5.0;
    [(UITextField *)[passwordScroll viewWithTag:6] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [(UIImageView *)[passwordScroll viewWithTag:4] layer].cornerRadius = 5.0;
    [(UITextField *)[passwordScroll viewWithTag:7] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    passwordScroll.contentSize = CGSizeMake(passwordScroll.frame.size.width, [passwordScroll viewWithTag:4].frame.size.height+[passwordScroll viewWithTag:4].frame.origin.y);
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
 * @Modified Date 13/08/2015
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
 * @Modified Date 13/08/2015
 */
#pragma mark Get View Notification List
-(IBAction)viewNotificationList:(id)sender {
    if (!CheckStringForNull(self.lblNotiCount.text)) {
        [self performSegueWithIdentifier:@"notification" sender:nil];
    }
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Reval View
 * @Modified Date 20/10/2015
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

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description Here we get Notification List
 * @Modified Date 26/10/2015
 */
#pragma mark get Notification List
-(void)notificationList {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(notificationListDelay) withObject:nil afterDelay:0.1];
}
-(void)notificationListDelay {
    NSArray *array = [[NSArray alloc] init];
    if (isNetworkAvailable) {
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:@"getNotif",@"notifPage",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"changeNotifications" controls:@"businessOwners" httpMethod:@"POST" data:data];
       
        if (parsedJSONToken != nil) {
            array = [parsedJSONToken valueForKey:@"result"];
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    
    for (int i = 0; i<notificationArray.count; i++) {
        NSMutableDictionary *dict = [[notificationArray objectAtIndex:i] mutableCopy];
        NSString *valStr = [dict valueForKey:@"value"];
        NSString *valueString = [NSString stringWithFormat:@"%@",[array valueForKey:valStr]];
        [dict setObject:valueString forKey:valStr];
        [notificationArray replaceObjectAtIndex:i withObject:dict];
    }
    finalArray = [[NSMutableArray alloc] initWithArray:notificationArray];
    [self populateSelected];
    [[AppDelegate currentDelegate] removeLoading];
    [tableView reloadData];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for insert bool value in array according to list
 * @Modified Date 26/10/2015
 */
#pragma mark populate Day Selected
- (void)populateSelected {
    NSMutableArray *array = [[NSMutableArray alloc] initWithCapacity:[finalArray count]];
    for (int i=0; i < [finalArray count]; i++) {
        NSDictionary *dict = [finalArray objectAtIndex:i];
        NSString *keyString = [dict valueForKey:@"value"];
        NSString *valueString = [dict valueForKey:keyString];
        if ([valueString isEqualToString:@"1"]) {
            [array addObject:[NSNumber numberWithBool:YES]];
        }
        else {
            [array addObject:[NSNumber numberWithBool:NO]];
        }
    }
    
    if (![array containsObject:[NSNumber numberWithBool:NO]]) {
        [btnSelectAll setImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:1];
    }
    
    populateArray = array;
    
    
}


/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Select Module Password And Notification
 * @Modified Date 20/10/2015
 */
#pragma mark Select Module
-(IBAction)selectModulePassAndNoti:(UIButton*)sender {
    
    if (sender.tag == 1) {
        [btnSelectAll setAlpha:0.5f];
        [btnSelectAll setTag:0];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setUserInteractionEnabled:NO];
        [tableView setHidden:YES];
        [btnSaveNoti setHidden:YES];
        [viewChangePassword setHidden:NO];
        lblTop.text = @"CHANGE PASSWORD";
        [(UIButton *)[contantScroll viewWithTag:1] setBackgroundImage:[UIImage imageNamed:@"SelectModule"] forState:UIControlStateNormal];
        [(UIButton *)[contantScroll viewWithTag:2] setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
    }
    else {
        [btnSelectAll setAlpha:1.0f];
        [btnSelectAll setTag:0];
        [btnSelectAll setUserInteractionEnabled:YES];
        [self.view endEditing:YES];
        [tableView setHidden:NO];
        [btnSaveNoti setHidden:NO];
        [(UITextField *)[passwordScroll viewWithTag:5] setText:@""];
        [(UITextField *)[passwordScroll viewWithTag:6] setText:@""];
        [(UITextField *)[passwordScroll viewWithTag:7] setText:@""];
        [[ConnectionManager sharedManager] setBoarderColorClear:(UIImageView *)[passwordScroll viewWithTag:2]];
        [[ConnectionManager sharedManager] setBoarderColorClear:(UIImageView *)[passwordScroll viewWithTag:3]];
        [[ConnectionManager sharedManager] setBoarderColorClear:(UIImageView *)[passwordScroll viewWithTag:4]];
        
        [viewChangePassword setHidden:YES];
        lblTop.text = @"NOTIFICATIONS";
        [(UIButton *)[contantScroll viewWithTag:1] setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
        [(UIButton *)[contantScroll viewWithTag:2] setBackgroundImage:[UIImage imageNamed:@"SelectModule"] forState:UIControlStateNormal];

        [self notificationList];
        
        [tableView reloadData];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Select All Notification
 * @Modified Date 20/10/2015
 */
#pragma mark Select All Notification
-(IBAction)selectAllNotification:(UIButton *)sender {

    if (sender.tag == 0) {
        [self populateUnSelected:0];
        [btnSelectAll setImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
        [sender setTag:1];
    }
    else {
        [self populateUnSelected:1];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [sender setTag:0];
    }
    [tableView reloadData];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Redirewct Home Screen
 * @Modified Date 27/07/2015
 */
#pragma mark Home Button
-(IBAction)btnHome:(UIButton*)sender {
    if ([self.delegateSettingClass respondsToSelector:@selector(callHomeScreen)]) {
        [self.delegateSettingClass callHomeScreen];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for insert bool value in array according to list
 * @Modified Date 27/07/2015
 */
#pragma mark populate UnSelected
- (void)populateUnSelected:(int)index {
    
    NSMutableArray *array = [[NSMutableArray alloc] initWithCapacity:[finalArray count]];
    for (int i=0; i < [finalArray count]; i++) {
        if (index == 0) {
            [array addObject:[NSNumber numberWithBool:YES]];
        }
        else {
            [array addObject:[NSNumber numberWithBool:NO]];
        }
    }
    populateArray = array;
}


#pragma mark - UITableView Data Source
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return finalArray.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView1 cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    static NSString *PlaceholderCellIdentifier = @"SettingNotiTableViewCell";
    
    SettingNotiTableViewCell*cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
    cell.backgroundColor = [UIColor clearColor];
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
    if (cell == nil) {
        cell = [[SettingNotiTableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
    }
    
    if (finalArray.count) {
        
        NSDictionary *dict = [finalArray objectAtIndex:indexPath.row];
        
        NSNumber *selected = [populateArray objectAtIndex:[indexPath row]];
        UIImage *image = ([selected boolValue]) ? self.selectedImage : self.unSelectedImage;
        [cell.btnSelectNotiType setBackgroundImage:image forState:UIControlStateNormal];
        
        [cell.btnSelectNotiType setTag:indexPath.row];
        [cell.btnSelectNotiType addTarget:self action:@selector(SelectNotification:) forControlEvents:UIControlEventTouchUpInside];
        
        NSString *commentedStr = [NSString stringWithFormat:@"%@.",[dict valueForKey:@"title"]];
        cell.lblNotiName.text = commentedStr;
        CGSize size1 = [self MaxHeighForTextInRow:commentedStr width:tableView.frame.size.width-60];
        cell.lblNotiName.frame = CGRectMake(cell.lblNotiName.frame.origin.x, cell.lblNotiName.frame.origin.y, cell.lblNotiName.frame.size.width, size1.height);
       
        cell.lblNotiDetail.frame = CGRectMake(cell.lblNotiDetail.frame.origin.x, cell.lblNotiName.frame.origin.y+cell.lblNotiName.frame.size.height, cell.lblNotiDetail.frame.size.width, cell.lblNotiDetail.frame.size.height);
        
        NSString *commentedStr2 = [NSString stringWithFormat:@"%@.",[dict valueForKey:@"sub_title"]];
        cell.lblNotiDetail.text = commentedStr2;
        CGSize size2 = [self MaxHeighForTextInRow:commentedStr2 width:tableView.frame.size.width-60];
        cell.lblNotiDetail.frame = CGRectMake(cell.lblNotiDetail.frame.origin.x, cell.lblNotiDetail.frame.origin.y, cell.lblNotiDetail.frame.size.width, size2.height);

    }
    
    return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
  
}

- (CGFloat)tableView:(UITableView *)aTableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    NSDictionary *dict = [finalArray objectAtIndex:indexPath.row];
    NSString *commentedStr = [NSString stringWithFormat:@"%@.",[dict valueForKey:@"title"]];
    CGSize size1 = [self MaxHeighForTextInRow:commentedStr width:tableView.frame.size.width-60];
    
    NSString *commentedStr2 = [NSString stringWithFormat:@"%@.",[dict valueForKey:@"sub_title"]];
    CGSize size2 = [self MaxHeighForTextInRow:commentedStr2 width:tableView.frame.size.width-60];
    
    return size1.height+size2.height+10;
}
//For calculate the string Height And Width
#pragma mark Calculate the Height And Width
-(CGSize)MaxHeighForTextInRow:(NSString *)RowText width:(float)UITextviewWidth {
    
    CGSize constrainedSize = CGSizeMake(UITextviewWidth, CGFLOAT_MAX);
    
    NSDictionary *attributesDictionary = [NSDictionary dictionaryWithObjectsAndKeys:
                                          [UIFont fontWithName:@"Graphik-Regular" size:15.0], NSFontAttributeName,
                                          nil];
    
    NSMutableAttributedString *string = [[NSMutableAttributedString alloc] initWithString:RowText attributes:attributesDictionary];
    
    CGRect requiredHeight = [string boundingRectWithSize:constrainedSize options:NSStringDrawingUsesLineFragmentOrigin context:nil];
    
    if (requiredHeight.size.width > UITextviewWidth) {
        requiredHeight = CGRectMake(0, 0, UITextviewWidth, requiredHeight.size.height);
    }
    
    return requiredHeight.size;
}


-(void)SelectNotification:(UIButton *)sender {
    BOOL selected = [[populateArray objectAtIndex:sender.tag] boolValue];
    [populateArray replaceObjectAtIndex:sender.tag withObject:[NSNumber numberWithBool:!selected]];
    
    if (![populateArray containsObject:[NSNumber numberWithBool:NO]]) {
        [btnSelectAll setImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:1];
    }
    else {
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
    }
    [tableView reloadData];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Reset Password
 * @Modified Date 23/10/2015
 */
#pragma mark ResetPassword
-(IBAction)resetPassword:(UIButton *)sender {
    [(UITextField *)[passwordScroll viewWithTag:5] setText:@""];
    [(UITextField *)[passwordScroll viewWithTag:6] setText:@""];
    [(UITextField *)[passwordScroll viewWithTag:7] setText:@""];
    [[ConnectionManager sharedManager] setBoarderColorClear:(UIImageView *)[passwordScroll viewWithTag:3]];
    [[ConnectionManager sharedManager] setBoarderColorClear:(UIImageView *)[passwordScroll viewWithTag:4]];
    [[ConnectionManager sharedManager] setBoarderColorClear:(UIImageView *)[passwordScroll viewWithTag:4]];
    [self.view endEditing:YES];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Save Password
 * @Modified Date 23/10/2015
 */
#pragma mark SavePassword
-(IBAction)savePassword:(UIButton *)sender {
    [self makeEmptyFieldRed];
    BOOL isFilled = [self checkAllFieldAreFilled];
    if (!isFilled) {
        return;
    }
    
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(savePasswordDelay) withObject:nil afterDelay:0.1];
}
-(void)savePasswordDelay {
    //For Old Password
    NSString *oldPasswordString = [(UITextField *)[passwordScroll viewWithTag:5] text];
    //For New Password
    NSString *newPasswordString = [(UITextField *)[passwordScroll viewWithTag:6] text];
    //For Confirm Password
    NSString *confirmPasswordString = [(UITextField *)[passwordScroll viewWithTag:7] text];
    
    if (isNetworkAvailable) {
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:oldPasswordString,@"old_password",newPasswordString,@"new_password",confirmPasswordString,@"confirm_password",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"changePassword" controls:@"businessOwners" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            [(UITextField *)[passwordScroll viewWithTag:5] setText:@""];
            [(UITextField *)[passwordScroll viewWithTag:6] setText:@""];
            [(UITextField *)[passwordScroll viewWithTag:7] setText:@""];
            [self.view endEditing:YES];
            NSString *messageString = [parsedJSONToken valueForKey:@"message"];
            UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:messageString delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [networkAlert show];
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
 * @Modified Date 04/08/2015
 */
#pragma mark Required Field
-(BOOL)checkAllFieldAreFilled {
    
    // for Old Password
    NSString *oldPasswordString = [(UITextField *)[passwordScroll viewWithTag:5] text];
    if (CheckStringForNull(oldPasswordString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (oldPasswordString.length<6 || oldPasswordString.length>20) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Password should be minimum 6 characters and maximum 20 characters long." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    oldPasswordString = [oldPasswordString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:passwordSpecial] invertedSet]];
    if (!CheckStringForNull(oldPasswordString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Space is not allowed in password." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    
    // for New Password
    NSString *newPasswordString = [(UITextField *)[passwordScroll viewWithTag:6] text];
    if (CheckStringForNull(newPasswordString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (newPasswordString.length<6 || newPasswordString.length>20) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Password should be minimum 6 characters and maximum 20 characters long." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    NSString *newPassS = [newPasswordString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:passwordSpecial] invertedSet]];
    if (!CheckStringForNull(newPassS)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Space is not allowed in password." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }

    // for Confirm Password
    NSString *confirmPasswordString = [(UITextField *)[passwordScroll viewWithTag:7] text];
    if (CheckStringForNull(confirmPasswordString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (confirmPasswordString.length<6 || confirmPasswordString.length>20) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Password should be minimum 6 characters and maximum 20 characters long." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    NSString *confirmS = [confirmPasswordString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:passwordSpecial] invertedSet]];
    if (!CheckStringForNull(confirmS)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Space is not allowed in password." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    
    if (![newPasswordString isEqualToString:confirmPasswordString]) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Password does not match." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }

    
    return YES;
}

/*
* @Auther Deepak chauhan
* @Parm sender
* @Description This method are used for change the boarder color of Required Field
* @Modified Date 04/08/2015
*/
#pragma mark Required Field
-(BOOL)makeEmptyFieldRed {
    
    //For Old Password
    NSString *oldPasswordString  = [(UITextField *)[passwordScroll viewWithTag:5] text];
    NSString *olddPasswordString = [oldPasswordString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:passwordSpecial] invertedSet]];
    if (!CheckStringForNull(olddPasswordString) || CheckStringForNull(oldPasswordString) || oldPasswordString.length>20 || oldPasswordString.length < 6) {
        [[ConnectionManager sharedManager] setBoarderColorRed:(UIImageView *)[passwordScroll viewWithTag:2]];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:(UIImageView *)[passwordScroll viewWithTag:2]];
    }
    
    //For New Password
    NSString *newPasswordString  = [(UITextField *)[passwordScroll viewWithTag:6] text];
    NSString *newwPasswordString = [oldPasswordString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:passwordSpecial] invertedSet]];
    if (!CheckStringForNull(newwPasswordString) || CheckStringForNull(newPasswordString) || newPasswordString.length>20 || newPasswordString.length < 6) {
        [[ConnectionManager sharedManager] setBoarderColorRed:(UIImageView *)[passwordScroll viewWithTag:3]];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:(UIImageView *)[passwordScroll viewWithTag:3]];
    }
    
    //For Confirm Password
    NSString *confirmPasswordString  = [(UITextField *)[passwordScroll viewWithTag:7] text];
    NSString *confirmmPasswordString = [oldPasswordString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:passwordSpecial] invertedSet]];
    if (!CheckStringForNull(confirmmPasswordString) || CheckStringForNull(confirmPasswordString) || confirmPasswordString.length>20 || confirmPasswordString.length < 6) {
        [[ConnectionManager sharedManager] setBoarderColorRed:(UIImageView *)[passwordScroll viewWithTag:4]];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:(UIImageView *)[passwordScroll viewWithTag:4]];
    }
    
    if (!CheckStringForNull(newPasswordString) && !CheckStringForNull(confirmPasswordString)) {
        if (![newPasswordString isEqualToString:confirmPasswordString] ) {
            [[ConnectionManager sharedManager] setBoarderColorRed:(UIImageView *)[passwordScroll viewWithTag:4]];
        } else {
            [[ConnectionManager sharedManager] setBoarderColorClear:(UIImageView *)[passwordScroll viewWithTag:4]];
        }
    }
    
    return YES;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Set Notification Value
 * @Modified Date 04/08/2015
 */
#pragma mark Set Notification
-(IBAction)saveNotification:(id)sender {

    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(saveNotificationDelay) withObject:nil afterDelay:0.1];
}
-(void)saveNotificationDelay {
    
    NSMutableArray *sendNotiArr = [[NSMutableArray alloc] init];
    for (int i = 0; i<populateArray.count; i++) {
        BOOL selected = [[populateArray objectAtIndex:i] boolValue];
        if (selected) {
            NSString *valueString = [[finalArray objectAtIndex:i] valueForKey:@"value"];
            [sendNotiArr addObject:valueString];
        }
    }
    
    
    if (isNetworkAvailable) {
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:@"editNotif",@"notifPage",sendNotiArr,@"notifArr",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"changeNotifications" controls:@"businessOwners" httpMethod:@"POST" data:data];
        
        [[AppDelegate currentDelegate] removeLoading];
        
        if (parsedJSONToken != nil) {
            [self performSelector:@selector(notificationList) withObject:nil afterDelay:0.1];
            //[self notificationList];
            NSString *messageString = [parsedJSONToken valueForKey:@"message"];
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:messageString delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
        }
    }
    else {
        [[AppDelegate currentDelegate] removeLoading];
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
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
