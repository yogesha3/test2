//
//  CreditCardDetailViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 11/18/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import "CreditCardDetailViewController.h"

#define fNameSpecial        @"0123456789~`@#$%^&*()_+={}[]|;'<>?,₹€£/:\""

@interface CreditCardDetailViewController ()

@end

@implementation CreditCardDetailViewController
@synthesize previousCardString;
@synthesize PaymentDelegate;

- (void)viewDidLoad {
    
    NSString *previousFinalCardString = [NSString stringWithFormat:@"The credit card ending with %@ is currently associated with your account.",previousCardString];
    lblPreviousCard.text = previousFinalCardString;
    
    index = 0;
    monthIndex = 0;
    yearIndex = 0;
    
    yearArray = [[NSMutableArray alloc] init];
    
    NSDateFormatter *formatter = [[NSDateFormatter alloc] init];
    [formatter setDateFormat:@"yyyy"];
    NSString *yearString = [formatter stringFromDate:[NSDate date]];
    int yearValue = yearString.intValue;
    for (int i = yearValue; i<=yearValue+15; i++) {
        [yearArray addObject:[NSString stringWithFormat:@"%d",i]];
    }
    
    monthArray = [NSArray arrayWithObjects:
             @{@"Name": @"January",@"Value": @"01"},
             @{@"Name": @"February",@"Value": @"02"},
             @{@"Name": @"March",@"Value": @"03"},
             @{@"Name": @"April",@"Value": @"04"},
             @{@"Name": @"May",@"Value": @"05"},
             @{@"Name": @"June",@"Value": @"06"},
             @{@"Name": @"July",@"Value": @"07"},
             @{@"Name": @"August",@"Value": @"08"},
             @{@"Name": @"September",@"Value": @"09"},
             @{@"Name": @"October",@"Value": @"10"},
             @{@"Name": @"November",@"Value": @"11"},
             @{@"Name": @"December",@"Value": @"12"},
             nil];

    imgBG.layer.cornerRadius = 5.0f;
    cardNumberBG.layer.cornerRadius = 5.0f;
    imgCVC.layer.cornerRadius = 5.0f;
    imgExpireMonth.layer.cornerRadius = 5.0f;
    imgExpireYear.layer.cornerRadius = 5.0f;
    imgCardHolderName.layer.cornerRadius = 5.0f;
    btnSave.layer.cornerRadius = 5.0f;
    
    [self setPlaceHolderAndBg];
    
    scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, imgCardHolderName.frame.origin.y+imgCardHolderName.frame.size.height+20);
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description Here we set Placeholder And BG
 * @Modified Date 20/10/2015
 */
#pragma mark Set Place Holder
-(void)setPlaceHolderAndBg {
    
    [textFieldCardNumber setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [textFieldCvc setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [textFieldExpireMonth setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [textFieldExpireYear setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [textFieldCardHolderName setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
}

//for date picker ***********

#pragma mark Open Date Picker
-(IBAction)openDatePicker:(UIButton*)sender {
    
    [self.view endEditing:YES];
    
    if (sender.tag == 0) {
        checkMonthYearString = @"month";
    }
    else {
        checkMonthYearString = @"year";
    }
    
    if (prefrencePikerView) {
        [prefrencePikerView removeFromSuperview];
        prefrencePikerView=nil;
    }
    if (prefrenceToolBar) {
        [prefrenceToolBar removeFromSuperview];
        prefrenceToolBar=nil;
    }
    
    prefrencePikerView = [[UIPickerView alloc] initWithFrame:CGRectMake(0, self.view.frame.size.height+50, self.view.frame.size.width, 160)];
    prefrencePikerView.delegate = self;
    prefrencePikerView.showsSelectionIndicator = YES;
    [prefrencePikerView setBackgroundColor:[UIColor whiteColor]];
    [UIView beginAnimations:@"animateTableView" context:nil];
    [UIView setAnimationDuration:0.3];
    prefrencePikerView.frame = CGRectMake(0, self.view.frame.size.height-160, self.view.frame.size.width, 160);
    [UIView commitAnimations];
    [self.view addSubview:prefrencePikerView];
    
    prefrenceToolBar = [[UIToolbar alloc] initWithFrame:CGRectMake(0, self.view.frame.size.height, self.view.frame.size.width, 50)];
    prefrenceToolBar.barStyle = UIBarStyleBlack;
    prefrenceToolBar.translucent = YES;
    prefrenceToolBar.tintColor = nil;
    [UIView beginAnimations:@"animateTableView" context:nil];
    [UIView setAnimationDuration:0.3];
    prefrenceToolBar.frame = CGRectMake(0, self.view.frame.size.height-210, self.view.frame.size.width, 50);
    [UIView commitAnimations];
    [self.view addSubview:prefrenceToolBar];
    
    UIButton *itemSelectFromPicker = [UIButton buttonWithType:UIButtonTypeCustom];
    itemSelectFromPicker.frame = CGRectMake(0, 0, 70, 40);
    [itemSelectFromPicker setBackgroundColor:orangeColour];
    itemSelectFromPicker.layer.cornerRadius = 5;
    itemSelectFromPicker.clipsToBounds = YES;
    [itemSelectFromPicker setUserInteractionEnabled:YES];
    [itemSelectFromPicker.titleLabel setFont:[UIFont fontWithName:@"Graphik-Regular" size:20.0f]];
    [itemSelectFromPicker setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
    [itemSelectFromPicker setTitle:@"Select" forState:UIControlStateNormal];
    [itemSelectFromPicker addTarget:self action:@selector(doneButtonClick) forControlEvents:UIControlEventTouchUpInside];
    itemSelectFromPicker.showsTouchWhenHighlighted = YES;
    UIBarButtonItem* doneButton = [[UIBarButtonItem alloc] initWithCustomView:itemSelectFromPicker];
    
    UIBarButtonItem *EportSpaceBetween = [[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemFlexibleSpace target:nil action:nil];
    
    UIButton *cancelPickerButton = [UIButton buttonWithType:UIButtonTypeCustom];
    cancelPickerButton.frame = CGRectMake(0, 0, 80, 40);
    [cancelPickerButton setBackgroundColor:[UIColor clearColor]];
    [cancelPickerButton setBackgroundColor:[UIColor colorWithRed:0.8901960784 green:0.8980392157 blue:0.9137254902 alpha:1]];
    cancelPickerButton.layer.cornerRadius = 5;
    cancelPickerButton.clipsToBounds = YES;
    [cancelPickerButton setUserInteractionEnabled:YES];
    [cancelPickerButton setBackgroundColor:blackCancelBTN];
    [cancelPickerButton.titleLabel setFont:[UIFont fontWithName:@"Graphik-Regular" size:20.0f]];
    [cancelPickerButton setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
    [cancelPickerButton setTitle:@"Cancel" forState:UIControlStateNormal];
    [cancelPickerButton addTarget:self action:@selector(cancelCollection) forControlEvents:UIControlEventTouchUpInside];
    cancelPickerButton.showsTouchWhenHighlighted = YES;
    UIBarButtonItem* cancelButton = [[UIBarButtonItem alloc] initWithCustomView:cancelPickerButton];
    
    [prefrenceToolBar setItems:[NSArray arrayWithObjects:doneButton,EportSpaceBetween,cancelButton, nil]];
    
    if ([checkMonthYearString isEqualToString:@"month"]) {
        if (monthArray.count) {
            [prefrencePikerView selectRow:monthIndex inComponent:0 animated:NO];
        }
    }
    else {
        if (yearArray.count) {
            [prefrencePikerView selectRow:yearIndex inComponent:0 animated:NO];
        }
    }
}

-(void)cancelCollection {
    
    [UIView beginAnimations:@"animateTableView" context:nil];
    [UIView setAnimationDuration:0.3];
    [UIView setAnimationDelegate:self];
    prefrencePikerView.frame = CGRectMake(0, self.view.frame.size.height+50, self.view.frame.size.width, 160);
    [UIView commitAnimations];
    
    [UIView beginAnimations:@"animateTableView" context:nil];
    [UIView setAnimationDuration:0.3];
    prefrenceToolBar.frame = CGRectMake(0, self.view.frame.size.height, self.view.frame.size.width, 50);
    [UIView setAnimationDelegate:self];
    [UIView setAnimationDidStopSelector:@selector(removePicker)];
    [UIView commitAnimations];
    
}

-(void)doneButtonClick {
    
    if ([checkMonthYearString isEqualToString:@"month"]) {
        if (monthArray.count) {
            monthIndex = index;
            NSString *monthStr  = [[monthArray objectAtIndex:index] valueForKey:@"Name"];
            textFieldExpireMonth.text = monthStr;
        }
    }
    else {
        if (yearArray.count) {
            yearIndex = index;
            NSString *yearStr  = [yearArray objectAtIndex:index];
            textFieldExpireYear.text = yearStr;
        }
    }
    [UIView beginAnimations:@"animateTableView" context:nil];
    [UIView setAnimationDuration:0.3];
    [UIView setAnimationDelegate:self];
    prefrencePikerView.frame = CGRectMake(0, self.view.frame.size.height+50, self.view.frame.size.width, 160);
    [UIView commitAnimations];
    
    [UIView beginAnimations:@"animateTableView" context:nil];
    [UIView setAnimationDuration:0.3];
    prefrenceToolBar.frame = CGRectMake(0, self.view.frame.size.height, self.view.frame.size.width, 50);
    [UIView setAnimationDidStopSelector:@selector(removePicker)];
    [UIView commitAnimations];
}

-(void)removePicker {
    [prefrenceToolBar removeFromSuperview];
    [prefrencePikerView removeFromSuperview];
}


#pragma mark PICKER VIEW DELEGATE METHOD
- (NSInteger)numberOfComponentsInPickerView:(UIPickerView *)thePickerView {
    
    return 1;
}

- (NSInteger)pickerView:(UIPickerView *)thePickerView numberOfRowsInComponent:(NSInteger)component {
    
    if ([checkMonthYearString isEqualToString:@"month"]) {
        return [monthArray count];
    }
    else {
        return [yearArray count];
    }
    return 0;
}

- (NSString *)pickerView:(UIPickerView *)thePickerView titleForRow:(NSInteger)row forComponent:(NSInteger)component {
    
    if ([checkMonthYearString isEqualToString:@"month"]) {
        return [[monthArray objectAtIndex:row] valueForKey:@"Name"];
    }
    else {
        return [yearArray objectAtIndex:row];
    }
    return nil;
}

- (void)pickerView:(UIPickerView *)pickerView didSelectRow: (NSInteger)row inComponent:(NSInteger)component {
    index = row;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Save Payment
 * @Modified Date 18/11/2015
 */
#pragma mark Save Payment
- (IBAction)savePayment:(id)sender {
    [self makeEmptyFieldRed];
    BOOL isFilled = [self checkAllFieldAreFilled];
    if (!isFilled) {
        return;
    }
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(savePaymentDelay) withObject:nil afterDelay:0.1];
}
-(void)savePaymentDelay {
    
    NSString *cardNumberString = textFieldCardNumber.text;
    
    NSString *cvcNumberString = textFieldCvc.text;
    
    NSString *monthString = [[monthArray objectAtIndex:monthIndex] valueForKey:@"Value"];
    
    NSString *yearString = textFieldExpireYear.text;
    
    NSString *cardHolderNameStringString = textFieldCardHolderName.text;
    
    NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:cardNumberString,@"CC_Number",cvcNumberString,@"CC_cvv",monthString,@"CC_month",yearString,@"CC_year",cardHolderNameStringString,@"CC_Name",nil];
    
    NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                       options:NSJSONWritingPrettyPrinted error:nil];
    NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
    
    NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
    
    if (isNetworkAvailable) {
        NSDictionary *parsedJSONResult =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"updateCreditCard" controls:@"businessOwners" httpMethod:@"POST" data:data];
        
        if (parsedJSONResult != nil) {
            [self.view endEditing:YES];
            UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:[parsedJSONResult valueForKey:@"message"] delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [networkAlert show];
            if ([parsedJSONResult objectForKey:@"result"]) {
                NSDictionary *dict = [parsedJSONResult valueForKey:@"result"];
                NSString *previousFinalCardString = [NSString stringWithFormat:@"The credit card ending with %@ is currently associated with your account.",[dict valueForKey:@"credit_card_number"]];
                lblPreviousCard.text = previousFinalCardString;
                if ([self.PaymentDelegate respondsToSelector:@selector(updateBillingInfo)]) {
                    [self.PaymentDelegate updateBillingInfo];
                }
            }
            index = 0;
            monthIndex = 0;
            yearIndex = 0;
            textFieldCardNumber.text = @"";
            textFieldCvc.text = @"";
            textFieldExpireMonth.text = @"";
            textFieldExpireYear.text = @"";
            textFieldCardHolderName.text = @"";
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [[AppDelegate currentDelegate] removeLoading];
}

- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string {

    if (textField == textFieldCardNumber) {
        if (range.location >15) {
            return NO;
        }
    }
    if (textField == textFieldCvc) {
        if (range.location >2) {
            return NO;
        }
    }
    
    return YES;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for check Required Field
 * @Modified Date 04/08/2015
 */
#pragma mark Required Field
-(BOOL)checkAllFieldAreFilled {
    
    // for Card Number
    NSString *cardNumberString = textFieldCardNumber.text;
    if (CheckStringForNull(cardNumberString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (!isNumericKeyWord(cardNumberString) ) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Please enter a valid credit card number." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (cardNumberString.length >16 || cardNumberString.length <13) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Please enter a valid credit card number." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;

    }
    
    // for CVC Number
    NSString *cvcNumberString = textFieldCvc.text;
    if (CheckStringForNull(cvcNumberString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (cvcNumberString.length>3 || cvcNumberString.length<3) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Invalid CVC number." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (!isNumericKeyWord(cvcNumberString) ) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Invalid CVC number." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    
    // for Month
    NSString *monthString = textFieldExpireMonth.text;
    if (CheckStringForNull(monthString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    
    // for Year
    NSString *yearString = textFieldExpireYear.text;
    if (CheckStringForNull(yearString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    
    // for Card Holder Name
    NSString *cardHolderNameStringString = textFieldCardHolderName.text;
    if (CheckStringForNull(cardHolderNameStringString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    cardHolderNameStringString = [cardHolderNameStringString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:fNameSpecial] invertedSet]];
    if (!CheckStringForNull(cardHolderNameStringString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Name can have only alphabets, period, space and hyphen." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
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

    // for Card Number
    NSString *cardNumberString = textFieldCardNumber.text;
    if (!isNumericKeyWord(cardNumberString) || CheckStringForNull(cardNumberString) || cardNumberString.length>16 || cardNumberString.length<13) {
        [[ConnectionManager sharedManager] setBoarderColorRed:cardNumberBG];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:cardNumberBG];
    }
    // for CVC Number
    NSString *cvcNumberString = textFieldCvc.text;
    if (!isNumericKeyWord(cvcNumberString) || CheckStringForNull(cvcNumberString) || cvcNumberString.length>3 || cvcNumberString.length<3) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgCVC];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgCVC];
    }
    // for month
    NSString *monthString = textFieldExpireMonth.text;
    if (CheckStringForNull(monthString)) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgExpireMonth];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgExpireMonth];
    }
    // for year
    NSString *yearString = textFieldExpireYear.text;
    if (CheckStringForNull(yearString)) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgExpireYear];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgExpireYear];
    }
    // for card holder name
    NSString *cardHolderNameString = textFieldCardHolderName.text;
    NSString *fString = [cardHolderNameString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:fNameSpecial] invertedSet]];
    if (!CheckStringForNull(fString) || CheckStringForNull(cardHolderNameString)) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgCardHolderName];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgCardHolderName];
    }
    
    return YES;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 18/11/2015
 */
#pragma mark Back Screen
- (IBAction)backScreen:(id)sender {
    [self.navigationController popViewControllerAnimated:NO];
    if ([self.PaymentDelegate respondsToSelector:@selector(updateBillingInfo)]) {
        [self.PaymentDelegate updateBillingInfo];
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
