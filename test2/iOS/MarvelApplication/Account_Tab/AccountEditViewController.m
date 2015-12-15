//
//  AccountEditViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 10/24/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import "AccountEditViewController.h"

#define specialCharacter        @"0123456789~`@#$%^&*()_+={}[]|;'<>?,₹€£/:\""
#define addreNameSpecial    @"~`@#$%^&*_+={}[]|;'<>?/:₹€£\""

@interface AccountEditViewController ()

@end

@implementation AccountEditViewController
@synthesize profileDelegate;

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    
    [self setPlaceHolderAndBg];
    
    //For get the Login User Information
    [self performSelector:@selector(loginUserInformation) withObject:nil afterDelay:0.1];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description Here we set Placeholder And BG
 * @Modified Date 20/10/2015
 */
#pragma mark Set Place Holder
-(void)setPlaceHolderAndBg {

    [(UIButton *)[viewInfoUser viewWithTag:1] layer].cornerRadius = 25.0f;
    [(UIButton *)[viewInfoUser viewWithTag:1] setClipsToBounds:YES];
    
    btnUpdate.layer.cornerRadius = 5.0f;
    imgBG.layer.cornerRadius = 5.0f;

    [(UIImageView *)[scrollView viewWithTag:3] layer].cornerRadius = 5.0;
    [(UITextField *)[scrollView viewWithTag:11] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [(UIImageView *)[scrollView viewWithTag:4] layer].cornerRadius = 5.0;
    [(UITextField *)[scrollView viewWithTag:12] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [(UIImageView *)[scrollView viewWithTag:5] layer].cornerRadius = 5.0;
    [(UITextField *)[scrollView viewWithTag:13] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [(UIImageView *)[scrollView viewWithTag:6] layer].cornerRadius = 5.0;
    [(UITextField *)[scrollView viewWithTag:14] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [(UIImageView *)[scrollView viewWithTag:7] layer].cornerRadius = 5.0;
    [(UITextField *)[scrollView viewWithTag:15] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [(UIImageView *)[scrollView viewWithTag:8] layer].cornerRadius = 5.0;
    [(UITextField *)[scrollView viewWithTag:16] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    
    [(UIImageView *)[viewControl viewWithTag:1] layer].cornerRadius = 5.0;
    [(UITextField *)[viewControl viewWithTag:8] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [(UIImageView *)[viewControl viewWithTag:2] layer].cornerRadius = 5.0;
    [(UITextField *)[viewControl viewWithTag:9] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [(UIImageView *)[viewControl viewWithTag:3] layer].cornerRadius = 5.0;
    [(UITextField *)[viewControl viewWithTag:10] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [(UIImageView *)[viewControl viewWithTag:4] layer].cornerRadius = 5.0;
    [(UITextField *)[viewControl viewWithTag:11] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    
    [(UIImageView *)[viewControl viewWithTag:5] layer].cornerRadius = 5.0;
    UIPlaceHolderTextView *aboutMeTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:12];
    [aboutMeTextView setPlaceholder:@"About Me"];
    aboutMeTextView.contentInset = UIEdgeInsetsMake(-7.0,-5.0,0,0.0);
    [(UIImageView *)[viewControl viewWithTag:6] layer].cornerRadius = 5.0;
    UIPlaceHolderTextView *companyDescriptionTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:13];
    [companyDescriptionTextView setPlaceholder:@"Company Description"];
    companyDescriptionTextView.contentInset = UIEdgeInsetsMake(-7.0,-5.0,0,0.0);
    [(UIImageView *)[viewControl viewWithTag:7] layer].cornerRadius = 5.0;
    UIPlaceHolderTextView *serviceTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:14];
    [serviceTextView setPlaceholder:@"Services"];
    serviceTextView.contentInset = UIEdgeInsetsMake(-7.0,-5.0,0,0.0);
    
    scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewControl.frame.origin.y+viewControl.frame.size.height);
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for get Login User Information detail
 * @Modified Date 24/10/2015
 */
#pragma mark Get Contact Detail
- (void)loginUserInformation {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(loginUserInformationDelay) withObject:nil afterDelay:0.1];
}
- (void)loginUserInformationDelay {
    
    if (isNetworkAvailable) {
        
        NSString *loginUserIdString = [userDefaultManager valueForKey:@"user_id"];
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:loginUserIdString,@"memberId",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"memberDetail" controls:@"teams" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            
            NSDictionary *recordDict = [parsedJSONToken valueForKey:@"result"];
            
            //For First Name
            NSString *firstNameString = [recordDict valueForKey:@"fname"];
            if (CheckStringForNull(firstNameString)) {
                firstNameString = @"";
            }
            else {
                firstNameString = [firstNameString stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                           withString:[[firstNameString substringToIndex:1] capitalizedString]];
            }
            
            //For Last Name
            NSString *lastNameString = [recordDict valueForKey:@"lname"];
            if (CheckStringForNull(lastNameString)) {
                lastNameString = @"";
            }
            else {
                lastNameString = [NSString stringWithFormat:@" %@",[recordDict valueForKey:@"lname"]];
                lastNameString = [lastNameString stringByReplacingCharactersInRange:NSMakeRange(0,2)
                                                                         withString:[[lastNameString substringToIndex:2] capitalizedString]];
            }
            firstNameString = [firstNameString stringByAppendingString:lastNameString];
            [(UILabel *)[viewInfoUser viewWithTag:2] setText:firstNameString];
            [(UILabel *)[viewInfoUser viewWithTag:2] sizeToFit];
            
            
            //For Profession
            NSString *professionString = [recordDict valueForKey:@"profession_name"];
            if (CheckStringForNull(professionString)) {
                professionString = @"NA";
            }
            else {
                professionString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"profession_name"]];
            }
            
            //For Company
            NSString *coumpanyString = [recordDict valueForKey:@"company"];
            coumpanyString = [coumpanyString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(coumpanyString)) {
                coumpanyString = @", NA";
            }
            else {
                coumpanyString = [NSString stringWithFormat:@", %@",[recordDict valueForKey:@"company"]];
            }
            professionString = [professionString stringByAppendingString:coumpanyString];
            
            if ([professionString isEqualToString:@"NA, NA"]) {
                professionString = @"NA";
                [(UILabel *)[viewInfoUser viewWithTag:3] setTextColor:[UIColor darkGrayColor]];
            }
            else {
                [(UILabel *)[viewInfoUser viewWithTag:3] setTextColor:orangeColour];
            }
            [(UILabel *)[viewInfoUser viewWithTag:3] setText:professionString];
            [(UILabel *)[viewInfoUser viewWithTag:3] sizeToFit];
            
            //For User Profile Image
            NSString *profileUrlStr = [recordDict valueForKey:@"member_profile_image"];
            if (!CheckStringForNull(profileUrlStr)) {
                NSURL *url = [[NSURL alloc] initWithString:profileUrlStr ];
                UIImage *image = [UIImage imageWithData:[NSData dataWithContentsOfURL:url]];
                 [(UIButton *)[viewInfoUser viewWithTag:1] setBackgroundImage:image forState:UIControlStateNormal];
            }
            
            //For Email
            NSString *emailString = [recordDict valueForKey:@"email"];
            if (CheckStringForNull(emailString)) {
                [(UILabel *)[viewInfoUser viewWithTag:5] setTextColor:[UIColor darkGrayColor]];
                emailString = @"NA";
            }
            else {
                [(UILabel *)[viewInfoUser viewWithTag:5] setTextColor:orangeColour];
            }
            [(UILabel *)[viewInfoUser viewWithTag:5] setText:emailString];
            [(UILabel *)[viewInfoUser viewWithTag:5] sizeToFit];
            
            //For Zip
            NSString *zipString = [recordDict valueForKey:@"zipcode"];
            if (CheckStringForNull(zipString)) {
                zipString = @"";
            }
            else {
                zipString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"zipcode"]];
            }

            //For State
            NSString *stateString = [recordDict valueForKey:@"state_name"];
            if (CheckStringForNull(stateString)) {
                stateString = @"";
            }
            else {
                if (CheckStringForNull(zipString)) {
                    stateString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"state_name"]];
                }
                else {
                    stateString = [NSString stringWithFormat:@", %@",[recordDict valueForKey:@"state_name"]];
                }
            }
            zipString = [zipString stringByAppendingString:stateString];
            
            //For Country
            NSString *countryString = [recordDict valueForKey:@"country_name"];
            if (CheckStringForNull(countryString)) {
                countryString = @"";
            }
            else {
                if (CheckStringForNull(zipString)) {
                    countryString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"country_name"]];
                }
                else {
                    countryString = [NSString stringWithFormat:@", %@",[recordDict valueForKey:@"country_name"]];
                }
            }
            zipString = [zipString stringByAppendingString:countryString];
            [(UILabel *)[viewInfoUser viewWithTag:7] setText:zipString];
            [(UILabel *)[viewInfoUser viewWithTag:7] sizeToFit];
            
            //For GMT Location
            NSString *timeJonString = [recordDict valueForKey:@"timezone_id"];
            if (CheckStringForNull(timeJonString)) {
                timeJonString = @"NA";
            }
            [(UILabel *)[viewInfoUser viewWithTag:9] setText:timeJonString];
            [(UILabel *)[viewInfoUser viewWithTag:9] sizeToFit];
            
            //For Address Form
            NSString *addresSetString = [recordDict valueForKey:@"address"];
            addresSetString = [addresSetString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(addresSetString)) {
                addresSetString = @"";
            }
            else {
                addresSetString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"address"]];
            }
            [(UITextField *)[scrollView viewWithTag:12] setText:addresSetString];

            //For City Form
            NSString *citySetString = [recordDict valueForKey:@"city"];
            citySetString = [citySetString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(citySetString)) {
                citySetString = @"";
            }
            else {
                citySetString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"city"]];
            }
            [(UITextField *)[scrollView viewWithTag:11] setText:citySetString];

            //For Mobile
            NSString *mobileNoString = [recordDict valueForKey:@"mobile"];
            if (CheckStringForNull(mobileNoString)) {
                mobileNoString = @"";
            }
            else {
                mobileNoString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"mobile"]];
            }
            [(UITextField *)[scrollView viewWithTag:13] setText:mobileNoString];
            
            //For Office
            NSString *officeNoString = [recordDict valueForKey:@"office_phone"];
            if (CheckStringForNull(officeNoString)) {
                officeNoString = @"";
            }
            else {
                officeNoString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"office_phone"]];
            }
            [(UITextField *)[scrollView viewWithTag:14] setText:officeNoString];
            
            //For Website One
            NSString *webSiteOneString = [recordDict valueForKey:@"website"];
            if (CheckStringForNull(webSiteOneString)) {
                webSiteOneString = @"";
            }
            else {
                webSiteOneString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"website"]];
            }
            [(UITextField *)[scrollView viewWithTag:15] setText:webSiteOneString];
           
            //For Website Two
            NSString *webSiteTwoString = [recordDict valueForKey:@"website1"];
            if (CheckStringForNull(webSiteTwoString)) {
                webSiteTwoString = @"";
            }
            else {
                webSiteTwoString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"website1"]];
            }
            [(UITextField *)[scrollView viewWithTag:16] setText:webSiteTwoString];
            
            //For Twitter
            NSString *twitterString = [recordDict valueForKey:@"twitter_profile_id"];
            if (CheckStringForNull(twitterString)) {
                twitterString = @"";
            }
            else {
                twitterString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"twitter_profile_id"]];
            }
            [(UITextField *)[viewControl viewWithTag:8] setText:twitterString];
            
            //For Facebook
            NSString *facebookString = [recordDict valueForKey:@"facebook_profile_id"];
            if (CheckStringForNull(facebookString)) {
                facebookString = @"";
            }
            else {
                facebookString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"facebook_profile_id"]];
            }
            [(UITextField *)[viewControl viewWithTag:9] setText:facebookString];
            
            //For Linked In
            NSString *linkedInString = [recordDict valueForKey:@"linkedin_profile_id"];
            if (CheckStringForNull(linkedInString)) {
                linkedInString = @"";
            }
            else {
                linkedInString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"linkedin_profile_id"]];
            }
            [(UITextField *)[viewControl viewWithTag:10] setText:linkedInString];
            
            //For Skyp
            NSString *skypString = [recordDict valueForKey:@"skype_id"];
            if (CheckStringForNull(skypString)) {
                skypString = @"";
            }
            else {
                skypString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"skype_id"]];
            }
            [(UITextField *)[viewControl viewWithTag:11] setText:skypString];
            
            //For About Me
            NSString *aboutMeString = [recordDict valueForKey:@"aboutme"];
            if (CheckStringForNull(aboutMeString)) {
                aboutMeString = @"";
            }
            else {
                aboutMeString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"aboutme"]];
            }
            UIPlaceHolderTextView *aboutMeTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:12];
            [aboutMeTextView setText:aboutMeString];
            
            //For CompanyDescription
            NSString *companyDesString = [recordDict valueForKey:@"business_description"];
            if (CheckStringForNull(companyDesString)) {
                companyDesString = @"";
            }
            else {
                companyDesString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"business_description"]];
            }
            UIPlaceHolderTextView *companyDesTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:13];
            [companyDesTextView setText:companyDesString];
            
            //For Services
            NSString *servicesString = [recordDict valueForKey:@"services"];
            if (CheckStringForNull(servicesString)) {
                servicesString = @"";
            }
            else {
                servicesString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"services"]];
            }
             UIPlaceHolderTextView *servicesTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:14];
            [servicesTextView setText:servicesString];
            
            [(UILabel *)[viewInfoUser viewWithTag:3] setFrame:CGRectMake([viewInfoUser viewWithTag:3].frame.origin.x ,[viewInfoUser viewWithTag:2].frame.origin.y+[viewInfoUser viewWithTag:2].frame.size.height, [viewInfoUser viewWithTag:3].frame.size.width, [viewInfoUser viewWithTag:3].frame.size.height)];
            
            [(UILabel *)[viewInfoUser viewWithTag:4] setFrame:CGRectMake([viewInfoUser viewWithTag:4].frame.origin.x ,[viewInfoUser viewWithTag:3].frame.origin.y+[viewInfoUser viewWithTag:3].frame.size.height+4, [viewInfoUser viewWithTag:4].frame.size.width, [viewInfoUser viewWithTag:4].frame.size.height)];
            
            [(UILabel *)[viewInfoUser viewWithTag:5] setFrame:CGRectMake([viewInfoUser viewWithTag:5].frame.origin.x ,[viewInfoUser viewWithTag:3].frame.origin.y+[viewInfoUser viewWithTag:3].frame.size.height, [viewInfoUser viewWithTag:5].frame.size.width, [viewInfoUser viewWithTag:5].frame.size.height)];
            
            [(UIButton *)[viewInfoUser viewWithTag:10] setFrame:CGRectMake([viewInfoUser viewWithTag:10].frame.origin.x ,[viewInfoUser viewWithTag:3].frame.origin.y+[viewInfoUser viewWithTag:3].frame.size.height, [viewInfoUser viewWithTag:10].frame.size.width, [viewInfoUser viewWithTag:10].frame.size.height)];
            
            [(UILabel *)[viewInfoUser viewWithTag:6] setFrame:CGRectMake([viewInfoUser viewWithTag:6].frame.origin.x ,[viewInfoUser viewWithTag:5].frame.origin.y+[viewInfoUser viewWithTag:5].frame.size.height+4, [viewInfoUser viewWithTag:6].frame.size.width, [viewInfoUser viewWithTag:6].frame.size.height)];
            
            [(UILabel *)[viewInfoUser viewWithTag:7] setFrame:CGRectMake([viewInfoUser viewWithTag:7].frame.origin.x ,[viewInfoUser viewWithTag:5].frame.origin.y+[viewInfoUser viewWithTag:5].frame.size.height+2, [viewInfoUser viewWithTag:7].frame.size.width, [viewInfoUser viewWithTag:7].frame.size.height)];
            
            [(UILabel *)[viewInfoUser viewWithTag:8] setFrame:CGRectMake([viewInfoUser viewWithTag:8].frame.origin.x ,[viewInfoUser viewWithTag:7].frame.origin.y+[viewInfoUser viewWithTag:7].frame.size.height+9, [viewInfoUser viewWithTag:8].frame.size.width, [viewInfoUser viewWithTag:8].frame.size.height)];
            
            [(UILabel *)[viewInfoUser viewWithTag:9] setFrame:CGRectMake([viewInfoUser viewWithTag:9].frame.origin.x ,[viewInfoUser viewWithTag:7].frame.origin.y+[viewInfoUser viewWithTag:7].frame.size.height+5, [viewInfoUser viewWithTag:9].frame.size.width, [viewInfoUser viewWithTag:9].frame.size.height)];
            
            viewInfoUser.frame = CGRectMake(viewInfoUser.frame.origin.x, viewInfoUser.frame.origin.y, viewInfoUser.frame.size.width, [viewInfoUser viewWithTag:9].frame.origin.y+[viewInfoUser viewWithTag:9].frame.size.height+10);
            
            imgBG.frame = CGRectMake(imgBG.frame.origin.x, viewInfoUser.frame.origin.y+viewInfoUser.frame.size.height+10, imgBG.frame.size.width, imgBG.frame.size.height);
            
            scrollView.frame = CGRectMake(scrollView.frame.origin.x, imgBG.frame.origin.y+10, scrollView.frame.size.width, scrollView.frame.size.height);
            
            btnUpdate.frame = CGRectMake(btnUpdate.frame.origin.x, scrollView.frame.origin.y+scrollView.frame.size.height+10, btnUpdate.frame.size.width, btnUpdate.frame.size.height);
            
        }
        [[AppDelegate currentDelegate] removeLoading];
    }
    else {
        [[AppDelegate currentDelegate] removeLoading];
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Send mail
 * @Modified Date 27/10/2015
 */
#pragma mark Send Mail
-(IBAction)sendMail:(id)sender {
    NSString *mailStr = [(UILabel *)[viewInfoUser viewWithTag:5] text];
    if (![mailStr isEqualToString:@"NA"]) {
        
        if (CheckStringForNull(mailStr)) {
            mailStr = @"";
            return;
        }
        NSArray *toRecipents = [NSArray arrayWithObject:mailStr];
        
        if ([MFMailComposeViewController canSendMail])
        {
            MFMailComposeViewController *mail = [[MFMailComposeViewController alloc] init];
            mail.mailComposeDelegate = self;
            [mail setSubject:@""];
            [mail setMessageBody:@"" isHTML:NO];
            [mail setToRecipients:toRecipents];
            
            [self presentViewController:mail animated:YES completion:NULL];
        }
        else
        {
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Email client is not configured. Please configure your email account." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
        }
    }
}

/*!
 @method     mailComposeController:didFinishWithResult:error:
 @abstract   Delegate callback which is called upon user's completion of email composition.
 @discussion This delegate callback will be called when the user completes the email composition.  How the user chose
 to complete this task will be given as one of the parameters to the callback.  Upon this call, the client
 should remove the view associated with the controller, typically by dismissing modally.
 @param      controller   The MFMailComposeViewController instance which is returning the result.
 @param      result       MFMailComposeResult indicating how the user chose to complete the composition process.
 @param      error        NSError indicating the failure reason if failure did occur.  This will be <tt>nil</tt> if
 result did not indicate failure.
 */
#pragma mark mailComposeController
- (void) mailComposeController:(MFMailComposeViewController *)controller didFinishWithResult:(MFMailComposeResult)result error:(NSError *)error
{
    switch (result)
    {
        case MFMailComposeResultCancelled:
            NSLog(@"Mail cancelled");
            break;
        case MFMailComposeResultSaved:
            NSLog(@"Mail saved");
            break;
        case MFMailComposeResultSent:
            NSLog(@"Mail sent");
            break;
        case MFMailComposeResultFailed:
            NSLog(@"Mail sent failure: %@", [error localizedDescription]);
            break;
        default:
            break;
    }
    
    // Close the Mail Interface
    [self dismissViewControllerAnimated:YES completion:NULL];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Profile Update
 * @Modified Date 27/10/2015
 */
#pragma mark Profile Update
-(IBAction)profileUpdate:(UIButton *)sender {
    [self makeEmptyFieldRed];
    BOOL isFilled = [self checkAllFieldAreFilled];
    if (!isFilled) {
        return;
    }
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(profileUpdateDelay) withObject:nil afterDelay:0.1];
}
-(void)profileUpdateDelay {
    
    //For City
    NSString *cityString = [(UITextField *)[scrollView viewWithTag:11] text];
    
    //For Address
    NSString *addressString = [(UITextField *)[scrollView viewWithTag:12] text];
    
    //For Mobile
    NSString *phoneString = [(UITextField *)[scrollView viewWithTag:13] text];
    
    //For Office
    NSString *officePhoneString = [(UITextField *)[scrollView viewWithTag:14] text];
    
    //For website One
    NSString *webSiteOneString = [(UITextField *)[scrollView viewWithTag:15] text];
    
    //For website Two
    NSString *webSiteTwoString = [(UITextField *)[scrollView viewWithTag:16] text];
    
    //For Twitter
    NSString *twitterString = [(UITextField *)[viewControl viewWithTag:8] text];
    
    //For Facebook
    NSString *facebookString = [(UITextField *)[viewControl viewWithTag:9] text];
    
    //For linked in
    NSString *linkedInString = [(UITextField *)[viewControl viewWithTag:10] text];
    
    //For Skype
    NSString *skypString = [(UITextField *)[viewControl viewWithTag:11] text];
    
    //For About Me
    UIPlaceHolderTextView *aboutMeTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:12];
    NSString *aboutMeString = aboutMeTextView.text;
    
    //For Company Description
    UIPlaceHolderTextView *companyDescrTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:13];
    NSString *companyDesString = companyDescrTextView.text;
    
    //For Services
    UIPlaceHolderTextView *servicesTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:14];
    NSString *servicesString = servicesTextView.text;
    
    if (isNetworkAvailable) {
        
        NSMutableArray *tempHeavyDataArray = [NSMutableArray arrayWithObjects:cityString,@"city",addressString,@"address",phoneString,@"mobile",officePhoneString,@"office_phone",webSiteOneString,@"website",webSiteTwoString,@"website1",twitterString,@"twitter_profile_id",facebookString,@"facebook_profile_id",linkedInString,@"linkedin_profile_id",skypString,@"skype_id",aboutMeString,@"aboutme",companyDesString,@"business_description",servicesString,@"services",nil];
        for (id obj in heavyDataPathArray) {
            [tempHeavyDataArray addObject:obj];
        }
        [tempHeavyDataArray addObject:[NSString stringWithFormat:@"%d",[heavyDataPathArray count]]];
        
        tempUpload = [[UploadViewController alloc] initWithServiceName:@"" dataArray:tempHeavyDataArray methodHeader:@"editProfile" controls:@"businessOwners"];
        tempUpload.delegate=self;
        [tempUpload sendContantToServer];
    }
    else {
        [[AppDelegate currentDelegate] removeLoading];
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm responseData
 * @Description This method are used for get the received response from UploadViewController Class
 * @Modified Date 27/10/2015
 */
#pragma mark Receive response For Update Profile
-(void)receiveUploadResponseDataFromServer:(NSData *)responseData {
    
    if (responseData!=nil) {
        NSMutableDictionary *responseDict = (NSMutableDictionary*)[[ConnectionManager sharedManager] parseResponse:responseData];
        if (responseDict!=nil) {
            NSString *statusCode = [responseDict valueForKey:@"code"];
            NSString *message = [responseDict valueForKey:@"message"];
            if ([statusCode isEqualToString:@"200"]) {
                
                for (NSString *pathStr in heavyDataPathArray) {
                    [[NSFileManager defaultManager] removeItemAtPath:pathStr error:nil];
                }
                [heavyDataPathArray removeAllObjects];
                
                if ([self.profileDelegate respondsToSelector:@selector(updateProfileDetail)]) {
                    [self.profileDelegate updateProfileDetail];
                }
                [self.navigationController popViewControllerAnimated:NO];
                UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:message delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
                [networkAlert show];
            }
        }
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for take attachement from library and camera
 * @Modified Date 30/07/2015
 */
#pragma mark Back Screen
- (IBAction)profileButton:(id)sender {
    [self.view endEditing:YES];
    UIActionSheet *actionSheet = [[UIActionSheet alloc] initWithTitle:@"Select option" delegate:self cancelButtonTitle:@"Cancel" destructiveButtonTitle:nil otherButtonTitles:
                                  @"Image from Camera",
                                  @"Image from Library",
                                  nil];
    actionSheet.tag = 1;
    [actionSheet showInView:self.view];
}

- (void)actionSheet:(UIActionSheet *)popup clickedButtonAtIndex:(NSInteger)buttonIndex {
    
    switch (popup.tag) {
        case 1: {
            switch (buttonIndex) {
                case 0:
                    [self dismissViewControllerAnimated:NO completion:nil];
                    [self TakeImageFromCamera];
                    break;
                case 1:
                    [self dismissViewControllerAnimated:NO completion:nil];
                    [self takeImageFromLibrary];
                    break;
                default:
                    break;
            }
            break;
        }
        default:
            break;
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for take attachement from camera
 * @Modified Date 30/07/2015
 */
#pragma mark TAKE PICTURE FROM CAMERA
- (void)TakeImageFromCamera {
    if ([UIImagePickerController isSourceTypeAvailable:UIImagePickerControllerSourceTypeCamera]) {
        
        if (imagePicker!=nil) {
            imagePicker=nil;
        }
        
        imagePicker = [[UIImagePickerController alloc] init];
        imagePicker.sourceType =  UIImagePickerControllerSourceTypeCamera;
        imagePicker.delegate = self;
        imagePicker.allowsEditing = NO;
        
        [self presentViewController:imagePicker animated:YES completion:nil];
    }
    else {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Sorry" message:@"Camera not avilable." delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return;
    }
    
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for take attachement from library
 * @Modified Date 30/07/2015
 */
#pragma mark Take Image From Library
-(void)takeImageFromLibrary{
    
    if (imagePicker!=nil) {
        imagePicker=nil;
    }
    
    imagePicker = [[UIImagePickerController alloc] init];
    imagePicker.sourceType = UIImagePickerControllerSourceTypePhotoLibrary;
    imagePicker.delegate = self;
    imagePicker.mediaTypes = [NSArray arrayWithObjects:(NSString *) kUTTypeImage, nil];
    
    if (!CheckDeviceFunction()) {
        [self presentViewController:imagePicker animated:YES completion:NO];
        return;
    }
    
    if (popOver!=nil) {
        popOver=nil;
    }
    popOver = [[UIPopoverController alloc] initWithContentViewController:imagePicker];
    popOver.delegate = self;
    int w = 320;
    CGRect pickerFrame = CGRectMake(220, 400, w, 400);
    [popOver setPopoverContentSize:pickerFrame.size animated:NO];
    [popOver presentPopoverFromRect:[viewInfoUser viewWithTag:1].frame inView:scrollView permittedArrowDirections:UIPopoverArrowDirectionUp animated:YES];
    
}
// The picker does not dismiss itself; the client dismisses it in these callbacks.
// The delegate will receive one or the other, but not both, depending whether the user
// confirms or cancels.
- (void) imagePickerController:(UIImagePickerController *)picker didFinishPickingMediaWithInfo:(NSDictionary *)info {
    
    [[AppDelegate currentDelegate] addLoading];
    
    heavyDataPathArray = [[NSMutableArray alloc] init];
    
    UIImage *image = [info objectForKey:@"UIImagePickerControllerOriginalImage"];
    
    NSData *heavyData = UIImageJPEGRepresentation(image, 0.8);
    
    //FOR IMAGE SIZE
    int bytes = [heavyData length];
    float a = bytes/1024;
    float b = a/1024;
    if (b>2.0) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@""
                                                        message:@"File size should be less than 2 MB." delegate:nil cancelButtonTitle:@"OK"
                                              otherButtonTitles:nil, nil];
        [alert show];
        heavyData = nil;
        [imagePicker dismissViewControllerAnimated:YES completion:nil];
        [[AppDelegate currentDelegate] removeLoading];
        return;
    }
    
    image=[image fixOrientation];
    
    image = imageCompressReduseSizeProfile(image);
    
    heavyData = nil;
    heavyData = UIImageJPEGRepresentation(image, 0.8);

    
    [(UIButton *)[viewInfoUser viewWithTag:1] setBackgroundImage:image forState:UIControlStateNormal];
    
    //FOR IMAGE NAME
    NSMutableString *heavyDataName = [[NSMutableString alloc] init];
    CFUUIDRef theUUID = CFUUIDCreate(kCFAllocatorDefault);
    if (theUUID) {
        [heavyDataName appendString:CFBridgingRelease(CFUUIDCreateString(kCFAllocatorDefault, theUUID))];
        CFRelease(theUUID);
    }
    [heavyDataName appendString:@".jpg"];
    
    NSString *path = [[NSHomeDirectory() stringByAppendingPathComponent:@"Documents"] stringByAppendingPathComponent:heavyDataName];
    [heavyData writeToFile:path atomically:NO];
    [heavyDataPathArray addObject:path];
    
    
    [imagePicker dismissViewControllerAnimated:YES completion:nil];
    
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
    
    // for City
    NSString *cityString = [(UITextField *)[scrollView viewWithTag:11] text];
    if (cityString.length>35 && !CheckStringForNull(cityString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"City can have maximum 35 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    cityString = [cityString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:specialCharacter] invertedSet]];
    if (!CheckStringForNull(cityString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"City can contain alphabets, period, space and hyphen" delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    
    //For Address
    NSString *addString = [(UITextField *)[scrollView viewWithTag:12] text];
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

    // for Mobile Phone
    NSString *phoneString = [(UITextField *)[scrollView viewWithTag:13] text];
    if (phoneString.length>15&& !CheckStringForNull(phoneString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Mobile phone can have maximum 15 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (!isNumericKeyWord(phoneString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Mobile phone can contain numeric characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }

    // for Office Phone
    NSString *officePhoneString = [(UITextField *)[scrollView viewWithTag:14] text];
    if (CheckStringForNull(officePhoneString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (officePhoneString.length>15) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Office phone can have maximum 15 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (!isNumericKeyWord(officePhoneString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Office phone can contain numeric characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    
    // for WebSite One
    NSString *webSiteOneString = [(UITextField *)[scrollView viewWithTag:15] text];
    NSArray *arr = [webSiteOneString componentsSeparatedByString:@"."];
    if (!CheckStringForNull(webSiteOneString)) {
        if (arr.count>1) {
            NSString *firStr = [arr objectAtIndex:0];
            NSString *lasStr = [arr objectAtIndex:1];
            if (firStr.length>0 && lasStr.length>1 && !CheckStringForNull(webSiteOneString)) {
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
    
    // for WebSite Two
    NSString *webSiteTwoString = [(UITextField *)[scrollView viewWithTag:16] text];
    NSArray *arr2 = [webSiteTwoString componentsSeparatedByString:@"."];
    if (!CheckStringForNull(webSiteTwoString)) {
        if (arr2.count>1) {
            NSString *firStr = [arr2 objectAtIndex:0];
            NSString *lasStr = [arr2 objectAtIndex:1];
            if (firStr.length>0 && lasStr.length>1 && !CheckStringForNull(webSiteTwoString)) {
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
    
    // for Twitter
    NSString *twitterString = [(UITextField *)[viewControl viewWithTag:8] text];
    NSArray *arrTwitter = [twitterString componentsSeparatedByString:@"."];
    if (!CheckStringForNull(twitterString)) {
        if (arrTwitter.count>1) {
            NSString *firStr = [arrTwitter objectAtIndex:0];
            NSString *lasStr = [arrTwitter objectAtIndex:1];
            if (firStr.length>0 && lasStr.length>1 && !CheckStringForNull(twitterString)) {
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
    
    // for Facebook
    NSString *facebookString = [(UITextField *)[viewControl viewWithTag:9] text];
    NSArray *arrfacebook = [facebookString componentsSeparatedByString:@"."];
    if (!CheckStringForNull(facebookString)) {
        if (arrfacebook.count>1) {
            NSString *firStr = [arrfacebook objectAtIndex:0];
            NSString *lasStr = [arrfacebook objectAtIndex:1];
            if (firStr.length>0 && lasStr.length>1 && !CheckStringForNull(facebookString)) {
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

    // for Linked In
    NSString *linkedInString = [(UITextField *)[viewControl viewWithTag:10] text];
    NSArray *arrlinkedIn = [linkedInString componentsSeparatedByString:@"."];
    if (!CheckStringForNull(linkedInString)) {
        if (arrlinkedIn.count>1) {
            NSString *firStr = [arrlinkedIn objectAtIndex:0];
            NSString *lasStr = [arrlinkedIn objectAtIndex:1];
            if (firStr.length>0 && lasStr.length>1 && !CheckStringForNull(linkedInString)) {
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
    
    //For About Me
    UIPlaceHolderTextView *aboutMeTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:12];
    NSString *aboutMeString = aboutMeTextView.text;
    if (!CheckStringForNull(aboutMeString) && aboutMeString.length>500) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"About Me can have maximum 500 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }

    //For Company Description
    UIPlaceHolderTextView *companyDescrTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:13];
    NSString *companyDesString = companyDescrTextView.text;
    if (!CheckStringForNull(companyDesString) && companyDesString.length>500) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Company Description can have maximum 500 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }

    //For Services
    UIPlaceHolderTextView *servicesTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:14];
    NSString *servicesString = servicesTextView.text;
    if (!CheckStringForNull(servicesString) && servicesString.length>500) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Services can have maximum 500 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
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
    
    //For city
    NSString *cityString = [(UITextField *)[scrollView viewWithTag:11] text];
    NSString *citString = [cityString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:specialCharacter] invertedSet]];
    if ((!CheckStringForNull(citString)) || (cityString.length>35 && !CheckStringForNull(cityString))) {
        [[ConnectionManager sharedManager] setBoarderColorRed:[scrollView viewWithTag:3]];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:[scrollView viewWithTag:3]];
    }

    //For Address
    NSString *addString = [(UITextField *)[scrollView viewWithTag:12] text];
    NSString *adString = [addString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:addreNameSpecial] invertedSet]];
    if ((!CheckStringForNull(adString)) || (addString.length>60 && !CheckStringForNull(addString))) {
        [[ConnectionManager sharedManager] setBoarderColorRed:[scrollView viewWithTag:4]];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:[scrollView viewWithTag:4]];
    }

    // for Mobile Phone
    NSString *phoneString = [(UITextField *)[scrollView viewWithTag:13] text];
    if ((!isNumericKeyWord(phoneString)) || (phoneString.length>15 && !CheckStringForNull(phoneString))) {
        [[ConnectionManager sharedManager] setBoarderColorRed:[scrollView viewWithTag:5]];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:[scrollView viewWithTag:5]];
    }

    //For Office Phone
    NSString *officePhone = [(UITextField *)[scrollView viewWithTag:14] text];
    if (!isNumericKeyWord(officePhone) || CheckStringForNull(officePhone) || officePhone.length>15) {
        [[ConnectionManager sharedManager] setBoarderColorRed:[scrollView viewWithTag:6]];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:[scrollView viewWithTag:6]];
    }

    // for WebSite One
    NSString *webSiteString = [(UITextField *)[scrollView viewWithTag:15] text];
    NSArray *arr = [webSiteString componentsSeparatedByString:@"."];
    if (!CheckStringForNull(webSiteString)) {
        if (arr.count>1) {
            NSString *firStr = [arr objectAtIndex:0];
            NSString *lasStr = [arr objectAtIndex:1];
            if (firStr.length>0 && lasStr.length>1 && !CheckStringForNull(webSiteString)) {
                [[ConnectionManager sharedManager] setBoarderColorClear:[scrollView viewWithTag:7]];
            } else {
                [[ConnectionManager sharedManager] setBoarderColorRed:[scrollView viewWithTag:7]];
            }
        }
        else {
            [[ConnectionManager sharedManager] setBoarderColorRed:[scrollView viewWithTag:7]];
        }
    }
    
    // for WebSite Two
    NSString *webSiteTwoString = [(UITextField *)[scrollView viewWithTag:16] text];
    NSArray *arr2 = [webSiteTwoString componentsSeparatedByString:@"."];
    if (!CheckStringForNull(webSiteTwoString)) {
        if (arr2.count>1) {
            NSString *firStr = [arr2 objectAtIndex:0];
            NSString *lasStr = [arr2 objectAtIndex:1];
            if (firStr.length>0 && lasStr.length>1 && !CheckStringForNull(webSiteTwoString)) {
                [[ConnectionManager sharedManager] setBoarderColorClear:[scrollView viewWithTag:8]];
            } else {
                [[ConnectionManager sharedManager] setBoarderColorRed:[scrollView viewWithTag:8]];
            }
        }
        else {
            [[ConnectionManager sharedManager] setBoarderColorRed:[scrollView viewWithTag:8]];
        }
    }

    // for Twitter
    NSString *twitterString = [(UITextField *)[viewControl viewWithTag:8] text];
    NSArray *arrTwitter = [twitterString componentsSeparatedByString:@"."];
    if (!CheckStringForNull(twitterString)) {
        if (arrTwitter.count>1) {
            NSString *firStr = [arrTwitter objectAtIndex:0];
            NSString *lasStr = [arrTwitter objectAtIndex:1];
            if (firStr.length>0 && lasStr.length>1 && !CheckStringForNull(twitterString)) {
                [[ConnectionManager sharedManager] setBoarderColorClear:[viewControl viewWithTag:1]];
            } else {
                [[ConnectionManager sharedManager] setBoarderColorRed:[viewControl viewWithTag:1]];
            }
        }
        else {
            [[ConnectionManager sharedManager] setBoarderColorRed:[viewControl viewWithTag:1]];
        }
    }
    
    // for Facebook
    NSString *facebookString = [(UITextField *)[viewControl viewWithTag:9] text];
    NSArray *arrfacebook = [facebookString componentsSeparatedByString:@"."];
    if (!CheckStringForNull(facebookString)) {
        if (arrfacebook.count>1) {
            NSString *firStr = [arrfacebook objectAtIndex:0];
            NSString *lasStr = [arrfacebook objectAtIndex:1];
            if (firStr.length>0 && lasStr.length>1 && !CheckStringForNull(facebookString)) {
                [[ConnectionManager sharedManager] setBoarderColorClear:[viewControl viewWithTag:2]];
            } else {
                [[ConnectionManager sharedManager] setBoarderColorRed:[viewControl viewWithTag:2]];
            }
        }
        else {
            [[ConnectionManager sharedManager] setBoarderColorRed:[viewControl viewWithTag:2]];
        }
    }
    
    // for Linked In
    NSString *linkedInString = [(UITextField *)[viewControl viewWithTag:10] text];
    NSArray *arrlinkedIn = [linkedInString componentsSeparatedByString:@"."];
    if (!CheckStringForNull(linkedInString)) {
        if (arrlinkedIn.count>1) {
            NSString *firStr = [arrlinkedIn objectAtIndex:0];
            NSString *lasStr = [arrlinkedIn objectAtIndex:1];
            if (firStr.length>0 && lasStr.length>1 && !CheckStringForNull(linkedInString)) {
                [[ConnectionManager sharedManager] setBoarderColorClear:[viewControl viewWithTag:3]];
            } else {
                [[ConnectionManager sharedManager] setBoarderColorRed:[viewControl viewWithTag:3]];
            }
        }
        else {
            [[ConnectionManager sharedManager] setBoarderColorRed:[viewControl viewWithTag:3]];
        }
    }
    
    //For About Me
    UIPlaceHolderTextView *aboutMeTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:12];
    NSString *aboutMeString = aboutMeTextView.text;
    if (!CheckStringForNull(aboutMeString) && aboutMeString.length>500) {
        [[ConnectionManager sharedManager] setBoarderColorRed:[viewControl viewWithTag:5]];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:[viewControl viewWithTag:5]];
    }
    
    //For Company Description
    UIPlaceHolderTextView *companyDescrTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:13];
    NSString *companyDesString = companyDescrTextView.text;
    if (!CheckStringForNull(companyDesString) && companyDesString.length>500) {
        [[ConnectionManager sharedManager] setBoarderColorRed:[viewControl viewWithTag:6]];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:[viewControl viewWithTag:6]];
    }
    
    //For Services
    UIPlaceHolderTextView *servicesTextView = (UIPlaceHolderTextView *)[viewControl viewWithTag:14];
    NSString *servicesString = servicesTextView.text;
    if (!CheckStringForNull(servicesString) && servicesString.length>500) {
        [[ConnectionManager sharedManager] setBoarderColorRed:[viewControl viewWithTag:7]];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:[viewControl viewWithTag:7]];
    }
    
    return YES;
}



/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 24/10/2015
 */
#pragma mark Back Screen
- (IBAction)backScreen:(id)sender {
    [self.navigationController popViewControllerAnimated:NO];
    if ([self.profileDelegate respondsToSelector:@selector(updateProfileDetail)]) {
        [self.profileDelegate updateProfileDetail];
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
