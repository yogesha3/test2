//
//  EditReffralsViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 9/8/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "EditReffralsViewController.h"

#define fNameSpecial        @"0123456789~`@#$%^&*()_+={}[]|;'<>?,₹€£/:\""
#define jobTitleSpecial     @"0123456789~`#$%^*()_+={}[]|;'<>?,₹€£/:\""
#define companyNameSpecial  @"~`@#$%^&*()_+={}[]|;'<>?,/₹€£:\""
#define addreNameSpecial    @"~`@#$%^&*_+={}[]|;'<>?/:₹€£\""
#define phoneSpecial        @"~`@#$%^&*_[a-z][A-Z]+={},()-[]|;'<>₹€£?/:\""
#define zipSpecial          @"~`@#$%^&*()_+={}[]|;'<>?,/:₹€£\""

@interface EditReffralsViewController ()

@end

@implementation EditReffralsViewController

@synthesize editRefDict;
@synthesize delegateEditReferral;

- (void)viewDidLoad {
   
    statusArray = [NSArray arrayWithObjects:@{@"status":@"Received"},@{@"status":@"Contacted"},@{@"status":@"Proposal"},@{@"status":@"Success"},@{@"status":@"Kaput"}, nil];

    selectStatusIndex = 0;
    preSelectStatusIndex = 0;
    
    [self takePlaceHolderColorAndBGColorField];
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

/*
 * @Auther Deepak chauhan
 * @Parm nil
 * @Description This method are used for Placeholder And BG Color
 * @Modified Date 08/09/2015
 */
#pragma mark Placeholder And BG Color
-(void)takePlaceHolderColorAndBGColorField {
    
    imgControllBG.layer.cornerRadius = 5.0f;
    btnNext.layer.cornerRadius = 5.0f;
    scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, imgValueBG.frame.origin.y+imgValueBG.frame.size.height+20);
    
    imgFirstBG.layer.cornerRadius = 5.0f;
    [textFirstName setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    imgLastBG.layer.cornerRadius = 5.0f;
    [textLastName setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    imgCompanyBG.layer.cornerRadius = 5.0f;
    [textCompany setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    imgJobTitlBG.layer.cornerRadius = 5.0f;
    [textJobTitle setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    imgEmailBG.layer.cornerRadius = 5.0f;
    [textEmail setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    imgWebsiteBG.layer.cornerRadius = 5.0f;
    [textWebSite setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    imgAddressBg.layer.cornerRadius = 5.0f;
    [textAddress setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    imgSelectCouBG.layer.cornerRadius = 5.0f;
    [textSelectCountry setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    imgSelectStateBG.layer.cornerRadius = 5.0f;
    [textState setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    imgCityBG.layer.cornerRadius = 5.0f;
    [textCity setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    imgZipBG.layer.cornerRadius = 5.0f;
    [textZip setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    imgOfficePhoneBG.layer.cornerRadius = 5.0f;
    [textOfficePhone setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    imgMobileBG.layer.cornerRadius = 5.0f;
    [textPhone setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    imgStatusBG.layer.cornerRadius = 5.0f;
    [textStatus setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    imgValueBG.layer.cornerRadius = 5.0f;
    [textValue setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    
    //For First Name
    NSString *fNameString = [editRefDict valueForKey:@"first_name"];
    if (CheckStringForNull(fNameString)) {
        fNameString = @"";
    }
    textFirstName.text = fNameString;
    //For Last Name
    NSString *lNameString = [editRefDict valueForKey:@"last_name"];
    if (CheckStringForNull(lNameString)) {
        lNameString = @"";
    }
    textLastName.text = lNameString;
    
    //For Job Title
    NSString *jobTitleString = [editRefDict valueForKey:@"job_title"];
    jobTitleString = [jobTitleString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    if (CheckStringForNull(jobTitleString)) {
        jobTitleString = @"";
    }
    textJobTitle.text = jobTitleString;
    
    //For Company
    NSString *companyString = [editRefDict valueForKey:@"company"];
    companyString = [companyString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    if (CheckStringForNull(companyString)) {
        companyString = @"";
    }
    textCompany.text = companyString;
    
    //For Email
    NSString *emailString = [editRefDict valueForKey:@"email"];
    if (CheckStringForNull(emailString)) {
        emailString = @"";
    }
    textEmail.text = emailString;
    
    //For Address
    NSString *addressString = [editRefDict valueForKey:@"address"];
    addressString = [addressString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    addressString = [addressString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    if (CheckStringForNull(addressString)) {
        addressString = @"";
    }
    textAddress.text = addressString;
    
    //For City
    NSString *cityString = [editRefDict valueForKey:@"city"];
    cityString = [cityString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    if (CheckStringForNull(cityString)) {
        cityString = @"";
    }
    textCity.text = cityString;
    
    //For State
    NSString *stateString = [editRefDict valueForKey:@"state_name"];
    if (CheckStringForNull(stateString)) {
        stateString = @"";
    }
    textState.text = stateString;
    
    //For Zip
    NSString *zipString = [editRefDict valueForKey:@"zip"];
    if (CheckStringForNull(zipString)) {
        zipString = @"";
    }
    textZip.text = zipString;
    
    //For Country
    NSString *countryString = [editRefDict valueForKey:@"country_name"];
    if (CheckStringForNull(countryString)) {
        countryString = @"";
    }
    textSelectCountry.text = countryString;
    
    //For Web Site
    NSString *webSiteString = [editRefDict valueForKey:@"website"];
    if (CheckStringForNull(webSiteString)) {
        webSiteString = @"";
    }
    textWebSite.text = webSiteString;
    
    //For Mobile Number
    NSString *mobileString = [editRefDict valueForKey:@"mobile"];
    if (CheckStringForNull(mobileString)) {
        mobileString = @"";
    }
    textPhone.text = mobileString;
    
    //For Office Number
    NSString *officeString = [editRefDict valueForKey:@"office_phone"];
    if (CheckStringForNull(officeString)) {
        officeString = @"";
    }
    textOfficePhone.text = officeString;
   
    //For Status value
    NSString *statusString = [[editRefDict valueForKey:@"referral_status"] capitalizedString];
    if (CheckStringForNull(statusString)) {
        statusString = @"";
    }
    for (int i = 0; i<statusArray.count; i++) {
        NSString *statusString2 = [[[statusArray objectAtIndex:i] valueForKey:@"status"] capitalizedString];
        if ([statusString isEqualToString:statusString2]) {
            preSelectStatusIndex = i;
        }
    }
    
    textStatus.text = statusString;
   
    //For monetary value
    NSString *valueString = [editRefDict valueForKey:@"monetary_value"];
    if (CheckStringForNull(valueString)) {
        valueString = @"";
    }
    textValue.text = valueString;
}


/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen on Referral detail
 * @Modified Date 08/09/2015
 */
#pragma mark Back Screen
- (IBAction)backScreen:(id)sender {
    [self.navigationController popViewControllerAnimated:NO];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Next Process
 * @Modified Date 08/09/2015
 */
#pragma mark Next Screen
- (IBAction)nextButton:(id)sender {
    [self makeEmptyFieldRed];
    BOOL isFilled = [self checkAllFieldAreFilled];
    if (!isFilled) {
        return;
    }
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(saveEditReferral) withObject:nil afterDelay:0.1];
}
-(void)saveEditReferral {
    //For First Name
    NSString *fNameString = textFirstName.text;
    if (CheckStringForNull(fNameString)) {
        fNameString = @"";
    }
    //For Last Name
    NSString *lNameString = textLastName.text;
    if (CheckStringForNull(lNameString)) {
        lNameString = @"";
    }
    //For Company Name
    NSString *companyNameString = textCompany.text;
    if (CheckStringForNull(companyNameString)) {
        companyNameString = @"";
    }
    //For Job Name
    NSString *jobTitleString = textJobTitle.text;
    if (CheckStringForNull(jobTitleString)) {
        jobTitleString = @"";
    }
    //For Email Name
    NSString *emailString = textEmail.text;
    if (CheckStringForNull(emailString)) {
        emailString = @"";
    }
    //For Website Name
    NSString *webSiteString = textWebSite.text;
    if (CheckStringForNull(webSiteString)) {
        webSiteString = @"";
    }
    //For Address Name
    NSString *addressString = textAddress.text;
    if (CheckStringForNull(addressString)) {
        addressString = @"";
    }
    //For Country
    NSString *CountryString = [editRefDict valueForKey:@"country_id"];
    if (CheckStringForNull(CountryString)) {
        CountryString = @"";
    }
    //For State
    NSString *stateString = [editRefDict valueForKey:@"state_id"];
    if (CheckStringForNull(stateString)) {
        stateString = @"";
    }
    //For City Name
    NSString *cityString = textCity.text;
    if (CheckStringForNull(cityString)) {
        cityString = @"";
    }
    //For Zip Name
    NSString *zipString = textZip.text;
    if (CheckStringForNull(zipString)) {
        zipString = @"";
    }
    //For Office Phone Number
    NSString *officePhoneString = textOfficePhone.text;
    if (CheckStringForNull(officePhoneString)) {
        officePhoneString = @"";
    }
    //For Phone Number
    NSString *phoneString = textPhone.text;
    if (CheckStringForNull(phoneString)) {
        phoneString = @"";
    }
    //For Status
    NSString *statusString = textStatus.text;
    if (CheckStringForNull(statusString)) {
        statusString = @"";
    }
    else {
        statusString = [statusString stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                 withString:[[statusString substringToIndex:1] lowercaseString]];
    }
    //For Value
    NSString *valueString = textValue.text;
    if (CheckStringForNull(valueString)) {
        valueString = @"";
    }
    //For referral ID
    NSString *referralIdString = [editRefDict valueForKey:@"id"];
    if (CheckStringForNull(referralIdString)) {
        referralIdString = @"";
    }
    dictionaryEditReferral = [NSDictionary dictionaryWithObjectsAndKeys:fNameString,@"first_name",lNameString,@"last_name",companyNameString,@"company",jobTitleString,@"job_title",emailString,@"email",webSiteString,@"website",addressString,@"address",CountryString,@"country_id",stateString,@"state_id",cityString,@"city",zipString,@"zip",officePhoneString,@"office_phone",phoneString,@"mobile",statusString,@"referral_status",valueString,@"monetary_value",referralIdString,@"referralId", nil];

    NSData *jsonData = [NSJSONSerialization dataWithJSONObject:dictionaryEditReferral
                                                       options:NSJSONWritingPrettyPrinted error:nil];
    NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
    
    NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
    
    NSDictionary *editRefResDict =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"editReferral" controls:@"referrals" httpMethod:@"POST" data:data];
    
    if (editRefResDict!=nil) {
        NSString *message = [editRefResDict valueForKey:@"message"];
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:message delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
        if ([self.delegateEditReferral respondsToSelector:@selector(updateReferralFromEdit)]) {
            [self.delegateEditReferral updateReferralFromEdit];
        }
        [self.navigationController popToRootViewControllerAnimated:NO];
    }
    
     [[AppDelegate currentDelegate] removeLoading];
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for check Required Field
 * @Modified Date 08/09/2015
 */
#pragma mark Required Field
-(BOOL)checkAllFieldAreFilled {
    
    // for First Name
    NSString *fNameString = textFirstName.text;
    if (CheckStringForNull(fNameString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (fNameString.length>20) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"First name can have maximum 20 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    fNameString = [fNameString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:fNameSpecial] invertedSet]];
    if (!CheckStringForNull(fNameString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"First name can contain period, space and hyphen only including alphabets." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    // for Last Name
    NSString *lNameString = textLastName.text;
    if ([lNameString isEqualToString:@""]) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (lNameString.length>20) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Last name can have maximum 20 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    lNameString = [lNameString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:fNameSpecial] invertedSet]];
    if (!CheckStringForNull(lNameString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Last name can contain period, space and hyphen only including alphabets." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    //For Company
    NSString *compNameStr = [textCompany text];
    if (compNameStr.length>35 && !CheckStringForNull(compNameStr)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Company name can have maximum 35 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    compNameStr = [compNameStr stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:companyNameSpecial] invertedSet]];
    if (!CheckStringForNull(compNameStr)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Company name can contain alphanumeric characters, space, period and hyphen." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    //For Job
    NSString *jobNameStr = [textJobTitle text];
    if (CheckStringForNull(jobNameStr)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (jobNameStr.length>35 && !CheckStringForNull(jobNameStr)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Job title can have maximum 35 characters" delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
   //For Email
    BOOL email = validateEmail(textEmail.text);
    if (!email && !CheckStringForNull(textEmail.text)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Please enter valid email address." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    
    // for WebSite
    NSString *webSiteString = textWebSite.text;
    NSArray *arr = [webSiteString componentsSeparatedByString:@"."];
    if (!CheckStringForNull(webSiteString)) {
        if (arr.count>1) {
            NSString *firStr = [arr objectAtIndex:0];
            NSString *lasStr = [arr objectAtIndex:1];
            if (firStr.length>0 && lasStr.length>1 && !CheckStringForNull(webSiteString)) {
            }
            else {
                UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Please enter valid URL." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
                [alert show];
                return NO;
            }
        }
        else {
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Please enter valid URL." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            return NO;
        }
    }
    
    //For Address
    NSString *addString = [textAddress text];
    if (addString.length>60 && !CheckStringForNull(addString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Address can have maximum 60 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    addString = [addString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:addreNameSpecial] invertedSet]];
    if (!CheckStringForNull(addString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Address can contain alphanumeric characters, space, period, comma, parenthesis and hyphen ." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    //For city
    NSString *cityString = [textCity text];
    if (cityString.length>35 && !CheckStringForNull(cityString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"City can have maximum 35 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    cityString = [cityString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:fNameSpecial] invertedSet]];
    if (!CheckStringForNull(cityString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"City can contain alphabets, period, space and hyphen" delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    //For Zip
    NSString *zipString = [textZip text];
    if ((zipString.length<3 || zipString.length>12) && (!CheckStringForNull(zipString))) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"ZIP code should be minimum 3 and maximum 12 characters long." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    zipString = [zipString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:zipSpecial] invertedSet]];
    if (!CheckStringForNull(zipString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"ZIP Code can contain Alphanumeric characters, space, space, and hyphen." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
//    // for Country Name
//    NSString *countryString = textSelectCountry.text;
//    if (CheckStringForNull(countryString)) {
//        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
//        [alert show];
//        return NO;
//    }
//    // for State Name
//    NSString *stateString = textState.text;
//    if (CheckStringForNull(stateString)) {
//        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
//        [alert show];
//        return NO;
//    }
    // for Office Phone
    NSString *officePhoneString = textOfficePhone.text;
//    if (CheckStringForNull(officePhoneString)) {
//        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
//        [alert show];
//        return NO;
//    }
    if (officePhoneString.length>15 && !CheckStringForNull(officePhoneString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Office phone can have maximum 15 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (!isNumericKeyWord(officePhoneString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Office phone can contain numeric characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    // for Mobile Phone
    NSString *phoneString = textPhone.text;
    if (phoneString.length>15 && !CheckStringForNull(phoneString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Mobile phone can have maximum 15 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (!isNumericKeyWord(phoneString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Mobile phone can contain numeric characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    // for Value
    NSString *valueString = textValue.text;
    if (valueString.length>11 && !CheckStringForNull(valueString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Monetary value can have maximum 11 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (!isNumericKeyWord(valueString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Monetary value can contain numeric characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    
    return YES;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for change the boarder color of Required Field
 * @Modified Date 08/09/2015
 */
#pragma mark Required Field
-(BOOL)makeEmptyFieldRed {
    
    //For First Name
    NSString *fNameString  = textFirstName.text;
    NSString *fString = [fNameString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:fNameSpecial] invertedSet]];
    if (!CheckStringForNull(fString) || CheckStringForNull(fNameString) || fNameString.length>20) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgFirstBG];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgFirstBG];
    }
    //For Last Name
    NSString *lNameString  = textLastName.text;
    NSString *lString = [lNameString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:fNameSpecial] invertedSet]];
    if (!CheckStringForNull(lString) || CheckStringForNull(lNameString) || lNameString.length>20) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgLastBG];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgLastBG];
    }
    //For company
    NSString *compNameStr = [textCompany text];
    NSString *comNameStr = [compNameStr stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:companyNameSpecial] invertedSet]];
    if ((!CheckStringForNull(comNameStr)) || (compNameStr.length>35 && !CheckStringForNull(compNameStr))) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgCompanyBG];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgCompanyBG];
    }
    //For Job
    NSString *jobNameStr = [textJobTitle text];
    if ((jobNameStr.length>35 && !CheckStringForNull(jobNameStr)) || CheckStringForNull(jobNameStr)) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgJobTitlBG];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgJobTitlBG];
    }
    //For Email
    BOOL email = validateEmail(textEmail.text);
    if (!email && !CheckStringForNull(textEmail.text)) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgEmailBG];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgEmailBG];
    }
    // for WebSite
    NSString *webSiteString = textWebSite.text;
    NSArray *arr = [webSiteString componentsSeparatedByString:@"."];
    if (!CheckStringForNull(webSiteString)) {
        if (arr.count>1) {
            NSString *firStr = [arr objectAtIndex:0];
            NSString *lasStr = [arr objectAtIndex:1];
            if (firStr.length>0 && lasStr.length>1 && !CheckStringForNull(webSiteString)) {
                [[ConnectionManager sharedManager] setBoarderColorClear:imgWebsiteBG];
            } else {
                [[ConnectionManager sharedManager] setBoarderColorRed:imgWebsiteBG];
            }
        }
        else {
            [[ConnectionManager sharedManager] setBoarderColorRed:imgWebsiteBG];
        }
    }
    //For Address
    NSString *addString = [textAddress text];
    NSString *adString = [addString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:addreNameSpecial] invertedSet]];
    if ((!CheckStringForNull(adString)) || (addString.length>60 && !CheckStringForNull(addString))) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgAddressBg];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgAddressBg];
    }
    //For city
    NSString *cityString = [textCity text];
    NSString *citString = [cityString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:fNameSpecial] invertedSet]];
    if ((!CheckStringForNull(citString)) || (cityString.length>35 && !CheckStringForNull(cityString))) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgCityBG];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgCityBG];
    }
    //For Zip
    NSString *zipString = [textZip text];
    NSString *zString = [zipString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:zipSpecial] invertedSet]];
    if ((!CheckStringForNull(zString)) || (!CheckStringForNull(zipString) && (zipString.length<3 || zipString.length>12))) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgZipBG];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgZipBG];
    }
//    //For country
//    if (CheckStringForNull(textSelectCountry.text)) {
//        [[ConnectionManager sharedManager] setBoarderColorRed:imgSelectCouBG];
//        
//    } else {
//        [[ConnectionManager sharedManager] setBoarderColorClear:imgSelectCouBG];
//    }
//    //For State
//    if (CheckStringForNull(textState.text)) {
//        [[ConnectionManager sharedManager] setBoarderColorRed:imgSelectStateBG];
//    } else {
//        [[ConnectionManager sharedManager] setBoarderColorClear:imgSelectStateBG];
//    }
    //For Office Phone
    NSString *officePhone = [textOfficePhone text];
    if (!isNumericKeyWord(officePhone) || (!CheckStringForNull(officePhone) && officePhone.length>15)) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgOfficePhoneBG];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgOfficePhoneBG];
    }
    // for Mobile Phone
    NSString *phoneString = textPhone.text;
    if ((!isNumericKeyWord(phoneString)) || (phoneString.length>15 && !CheckStringForNull(phoneString))) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgMobileBG];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgMobileBG];
    }
    // for Value
    NSString *valueString = textValue.text;
    if (valueString.length>12 && !CheckStringForNull(valueString)) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgValueBG];
    }
    else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgValueBG];
    }
    
    if (!isNumericKeyWord(valueString)) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgValueBG];
    }
    else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgValueBG];
    }
    
    return YES;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for open picker to select country and state
 * @Modified Date 04/08/2015
 */
#pragma mark Select Country And State
-(IBAction)selectStatus:(UIButton*)sender {
    
    [self.view endEditing:YES];
    
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
    
    if (statusArray.count) {
        [prefrencePikerView selectRow:preSelectStatusIndex inComponent:0 animated:NO];
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
    
    if (statusArray.count) {
        preSelectStatusIndex = selectStatusIndex;
        NSString *countryNameStr = [[[statusArray objectAtIndex:preSelectStatusIndex] valueForKey:@"status"] capitalizedString];
        textStatus.text = countryNameStr;
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
    
    return [statusArray count];
}

- (NSString *)pickerView:(UIPickerView *)thePickerView titleForRow:(NSInteger)row forComponent:(NSInteger)component {

    return [[statusArray objectAtIndex:row] valueForKey:@"status"];
}

- (void)pickerView:(UIPickerView *)pickerView didSelectRow: (NSInteger)row inComponent:(NSInteger)component {
    
    selectStatusIndex = row;
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
