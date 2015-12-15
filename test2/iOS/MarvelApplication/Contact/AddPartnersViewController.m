//  NewContactViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/29/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "AddPartnersViewController.h"
#import "SendReferralViewController.h"

#define fNameSpecial        @"0123456789~`@#$%^&*()_+={}[]|;'<>?,₹€£/:\""
#define jobTitleSpecial     @"0123456789~`#$%^*()_+={}[]|;'<>?,₹€£/:\""
#define companyNameSpecial  @"~`@#$%^&*()_+={}[]|;'<>?,/₹€£:\""
#define addreNameSpecial    @"~`@#$%^&*_+={}[]|;'<>?/:₹€£\""
#define phoneSpecial        @"~`@#$%^&*_[a-z][A-Z]+={},()-[]|;'<>₹€£?/:\""
#define zipSpecial          @"~`@#$%^&*()_+={}[]|;'<>?,/:₹€£\""

@interface AddPartnersViewController ()

@end

@implementation AddPartnersViewController
@synthesize delegateAddPartner;
@synthesize checkAddOrEdit;
@synthesize dictContact;

- (void)viewDidLoad {
    
    imgControllBG.layer.cornerRadius = 5.0f;
    btnNext.layer.cornerRadius = 5.0f;
    if (![checkAddOrEdit isEqualToString:@"edit"]) {
        [btnNext setTitle:@"ADD CONTACT" forState:UIControlStateNormal];
        lblTopHeading.text = @"ADD A CONTACT";
    }
    
    [self enableTextField];
    [self takePlaceHolderColorAndBGColorField];
    
    [self getCountryList];
    
    scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, 1040);
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

//// return NO to disallow editing.
//- (BOOL)textFieldShouldBeginEditing:(UITextField *)textField {
//    [self.view endEditing:YES];
//    return YES;
//}

/*
 * @Auther Deepak chauhan
 * @Parm nil
 * @Description This method are used for Edit Contact
 * @Modified Date 06/10/2015
 */
#pragma mark Edit Contact
-(void)editContact {

    lblTopHeading.text = @"EDIT CONTACT";
    [btnNext setTitle:@"UPDATE" forState:UIControlStateNormal];
    
    //For First Name
    NSString *firstNameStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"first_name"]];
    if (CheckStringForNull(firstNameStr)) {
        firstNameStr = @"";
    }
    textFirstName.text = firstNameStr;
    
    //For Last Name
    NSString *lastNameStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"last_name"]];
    if (CheckStringForNull(lastNameStr)) {
        lastNameStr = @"";
    }
    textLastName.text = lastNameStr;
    
    //For Company Name
    NSString *companyNameStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"company"]];
    if (CheckStringForNull(companyNameStr)) {
        companyNameStr = @"";
    }
    textCompany.text = companyNameStr;
    
    //For Job Title
    NSString *jobTitleStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"job_title"]];
    if (CheckStringForNull(jobTitleStr)) {
        jobTitleStr = @"";
    }
    textJobTitle.text = jobTitleStr;
    
    //For Email
    NSString *emailStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"email"]];
    if (CheckStringForNull(emailStr)) {
        emailStr = @"";
    }
    textEmail.text = emailStr;
    
    //For Web Site
    NSString *webSiteStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"website"]];
    if (CheckStringForNull(webSiteStr)) {
        webSiteStr = @"";
    }
    textWebSite.text = webSiteStr;
    
    //For Address
    NSString *addressStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"address"]];
    if (CheckStringForNull(addressStr)) {
        addressStr = @"";
    }
    textAddress.text = addressStr;

    //For Country
    NSString *countryStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"country_name"]];
    for (int i = 0; i< countryArray.count; i++) {
        NSString *countryIdStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"country_id"]];
        NSString *CountryString = [[countryArray objectAtIndex:i] valueForKey:@"country_iso_code_2"];
        if ([countryIdStr isEqualToString:CountryString]) {
            saveCounIndex = i;
            [self getStateList];
            break;
        }
    }
    if (CheckStringForNull(countryStr)) {
        countryStr = @"";
    }
    textSelectCountry.text = countryStr;
    
    //For City
    NSString *cityStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"city"]];
    if (CheckStringForNull(cityStr)) {
        cityStr = @"";
    }
    textCity.text = cityStr;
    
    //For Zip
    NSString *zipStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"zip"]];
    if (CheckStringForNull(zipStr)) {
        zipStr = @"";
    }
    textZip.text = zipStr;
    
    //For Office Phone
    NSString *officePhoneStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"office_phone"]];
    if (CheckStringForNull(officePhoneStr)) {
        officePhoneStr = @"";
    }
    textOfficePhone.text = officePhoneStr;
    
    //For Mobile Phone
    NSString *mobilePhoneStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"mobile"]];
    if (CheckStringForNull(mobilePhoneStr)) {
        mobilePhoneStr = @"";
    }
    textPhone.text = mobilePhoneStr;
    
}

/*
 * @Auther Deepak chauhan
 * @Parm nil
 * @Description This method are used for Placeholder And BG Color
 * @Modified Date 04/08/2015
 */
#pragma mark Placeholder And BG Color
-(void)takePlaceHolderColorAndBGColorField {
    
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
}

/*
 * @Auther Deepak chauhan
 * @Parm nil
 * @Description This method are used for Enable All Text Fields
 * @Modified Date 04/08/2015
 */
#pragma mark Enable All Text Fields
-(void)enableTextField {
    
    [textFirstName setUserInteractionEnabled:YES];
    [textLastName setUserInteractionEnabled:YES];
    [textCompany setUserInteractionEnabled:YES];
    [textJobTitle setUserInteractionEnabled:YES];
    [textEmail setUserInteractionEnabled:YES];
    [textWebSite setUserInteractionEnabled:YES];
    [textAddress setUserInteractionEnabled:YES];
    [textCity setUserInteractionEnabled:YES];
    [textZip setUserInteractionEnabled:YES];
    [btnChooseCountry setUserInteractionEnabled:YES];
    [btnChooseState setUserInteractionEnabled:YES];
    [textOfficePhone setUserInteractionEnabled:YES];
    [textPhone setUserInteractionEnabled:YES];
    
}


/*
 * @Auther Deepak chauhan
 * @Parm nil
 * @Description This method are used for Get Country List
 * @Modified Date 04/08/2015
 */
#pragma mark Get Country List
-(void)getCountryList {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(getCountryListDelay) withObject:nil afterDelay:0.1];
}
-(void)getCountryListDelay {
    
    if (isNetworkAvailable) {
        NSData* data = nil;
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"listAllCountries" controls:@"users" httpMethod:@"POST" data:data];
        
        countryArray = [[NSMutableArray alloc] init];
        if (parsedJSONToken != nil) {
            countryArray = [[parsedJSONToken valueForKey:@"result"] mutableCopy];
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    
    //When user will edit the contact then this function will call
    if ([checkAddOrEdit isEqualToString:@"edit"]) {
        [self editContact];
    }

    [[AppDelegate currentDelegate] removeLoading];
}

/*
 * @Auther Deepak chauhan
 * @Parm nil
 * @Description This method are used for Get State List
 * @Modified Date 04/08/2015
 */
#pragma mark Get State List
-(void)getStateList {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(getStateListDelay) withObject:nil afterDelay:0.1];
}
-(void)getStateListDelay {
    
    NSString *countryCodeStr = [NSString stringWithFormat:@"%@",[[countryArray objectAtIndex:saveCounIndex] valueForKey:@"country_iso_code_2"]];
    
    if (isNetworkAvailable) {
        
        NSDictionary *dictionary = [NSDictionary dictionaryWithObjectsAndKeys:countryCodeStr,@"countryCode",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:dictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"listStateList" controls:@"users" httpMethod:@"POST" data:data];
        
        stateArray = [[NSMutableArray alloc] init];
        if (parsedJSONToken != nil) {
            stateArray = [[parsedJSONToken valueForKey:@"result"] mutableCopy];
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    
    //This condition Will be execute When User want to edit the contact
    if ([checkAddOrEdit isEqualToString:@"edit"]) {
        //For State
        NSString *stateStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"state_name"]];
        for (int j = 0; j< stateArray.count; j++) {
            NSString *stateIdStr = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"state_id"]];
            NSString *stateIdString = [[stateArray objectAtIndex:j] valueForKey:@"state_subdivision_id"];
            if ([stateIdStr isEqualToString:stateIdString]) {
                saveStatIndex = j;
                break;
            }
        }
        
        if (CheckStringForNull(stateStr)) {
            stateStr = @"";
        }
        textState.text = stateStr;
    }
    
    [[AppDelegate currentDelegate] removeLoading];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 9/07/2015
 */
#pragma mark Back Screen
- (IBAction)backScreen:(id)sender {
    [self.navigationController popViewControllerAnimated:NO];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Add contact
 * @Modified Date 29/07/2015
 */
#pragma mark Next Screen
- (IBAction)addContactButton:(id)sender {
    [self makeEmptyFieldRed];
    BOOL isFilled = [self checkAllFieldAreFilled];
    if (!isFilled) {
        return;
    }
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(addContactDelay) withObject:nil afterDelay:0.1];
}
-(void)addContactDelay {
    
    if (isNetworkAvailable) {
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
        NSString *CountryString = @"";
        if (!CheckStringForNull(textSelectCountry.text)) {
            CountryString = [[countryArray objectAtIndex:saveCounIndex] valueForKey:@"country_iso_code_2"];
            if (CheckStringForNull(CountryString)) {
                CountryString = @"";
            }
        }
        
        //For State
        NSString *stateString = @"";
        if (!CheckStringForNull(textState.text)) {
            [[stateArray objectAtIndex:saveStatIndex] valueForKey:@"state_subdivision_id"];
            if (CheckStringForNull(stateString)) {
                stateString = @"";
            }
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
        
        //For Group Id
        NSString *groupIdString = [userDefaultManager valueForKey:@"group_id"];
        NSDictionary *parsedJSONToken;
        
        if ([checkAddOrEdit isEqualToString:@"edit"]) {
            
            NSString *contactIdString = [NSString stringWithFormat:@"%@",[dictContact valueForKey:@"id"]];
            
            NSDictionary *dictionaryDataSend = [NSDictionary dictionaryWithObjectsAndKeys:contactIdString,@"contactId",fNameString,@"first_name",lNameString,@"last_name",companyNameString,@"company",jobTitleString,@"job_title",emailString,@"email",webSiteString,@"website",addressString,@"address",CountryString,@"country_id",stateString,@"state_id",cityString,@"city",zipString,@"zip",officePhoneString,@"office_phone",phoneString,@"mobile",groupIdString,@"user_groupid", nil];
            
            NSData *jsonData = [NSJSONSerialization dataWithJSONObject:dictionaryDataSend
                                                               options:NSJSONWritingPrettyPrinted error:nil];
            NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
            
            NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
            
            parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"editContact" controls:@"contacts" httpMethod:@"POST" data:data];

        }
        else {
            
            NSDictionary *dictionaryDataSend = [NSDictionary dictionaryWithObjectsAndKeys:fNameString,@"first_name",lNameString,@"last_name",companyNameString,@"company",jobTitleString,@"job_title",emailString,@"email",webSiteString,@"website",addressString,@"address",CountryString,@"country_id",stateString,@"state_id",cityString,@"city",zipString,@"zip",officePhoneString,@"office_phone",phoneString,@"mobile",groupIdString,@"user_groupid", nil];
            
            NSData *jsonData = [NSJSONSerialization dataWithJSONObject:dictionaryDataSend
                                                               options:NSJSONWritingPrettyPrinted error:nil];
            NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
            
            NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
            
            parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"addContact" controls:@"contacts" httpMethod:@"POST" data:data];
        }
        
        if (parsedJSONToken != nil) {
            [self.navigationController popViewControllerAnimated:NO];
            if ([self.delegateAddPartner respondsToSelector:@selector(updateContactAfterAddContact)]) {
                [self.delegateAddPartner updateContactAfterAddContact];
            }
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:[parsedJSONToken valueForKey:@"message"] delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [[AppDelegate currentDelegate] removeLoading];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for check Required Field
 * @Modified Date 04/08/2015
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
    NSString *emailString = textEmail.text;
    if (CheckStringForNull(emailString)) {
        [[AppDelegate currentDelegate] removeLoading];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
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
    if ((!email && !CheckStringForNull(textEmail.text)) || CheckStringForNull(textEmail.text)) {
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
    
    return YES;
}


/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for open picker to select country and state
 * @Modified Date 04/08/2015
 */
#pragma mark Select Country And State
-(IBAction)selectCountryAndState:(UIButton*)sender {
    
    [self.view endEditing:YES];
    
    idenCouOrSta = @"state";
    if (sender.tag == 0) {
        idenCouOrSta = @"country";
    }
    if (sender.tag == 1 && [textSelectCountry.text isEqualToString:@""]) {
        return;
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
    
    if ([idenCouOrSta isEqualToString:@"country"]) {
        if (countryArray.count) {
            [prefrencePikerView selectRow:saveCounIndex inComponent:0 animated:NO];
        }
    }
    else {
        if (stateArray.count) {
            [prefrencePikerView selectRow:saveStatIndex inComponent:0 animated:NO];
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
    
    if ([idenCouOrSta isEqualToString:@"country"]) {
        if (countryArray.count) {
            NSString *countryNameStr = [[countryArray objectAtIndex:selectCounIndex] valueForKey:@"country_name"];
            textSelectCountry.text = countryNameStr;
            saveCounIndex = selectCounIndex;
            selectStatIndex = 0;
            textState.text = @"";
            [self getStateList];
        }
    }
    else {
        if (stateArray.count) {
            NSString *countryNameStr = [[stateArray objectAtIndex:selectStatIndex] valueForKey:@"state_subdivision_name"];
            saveStatIndex = selectStatIndex;
            textState.text = countryNameStr;
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
    
    if ([idenCouOrSta isEqualToString:@"country"]) {
        return [countryArray count];
    }
    else {
        return [stateArray count];
    }
    return 0;
}

- (NSString *)pickerView:(UIPickerView *)thePickerView titleForRow:(NSInteger)row forComponent:(NSInteger)component {
    
    if ([idenCouOrSta isEqualToString:@"country"]) {
        return [[countryArray objectAtIndex:row] valueForKey:@"country_name"];
    }
    else {
        return [[stateArray objectAtIndex:row] valueForKey:@"state_subdivision_name"];//state_subdivision_id
    }
    return nil;
}

- (void)pickerView:(UIPickerView *)pickerView didSelectRow: (NSInteger)row inComponent:(NSInteger)component {
    if ([idenCouOrSta isEqualToString:@"country"]) {
        selectCounIndex = row;
    }
    else {
        selectStatIndex = row;
    }
}



#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
    
}


@end
