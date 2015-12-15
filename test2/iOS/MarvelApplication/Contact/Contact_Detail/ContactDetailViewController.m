//
//  ContactDetailViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 9/30/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import "ContactDetailViewController.h"

@interface ContactDetailViewController ()

@end

@implementation ContactDetailViewController
@synthesize contactIDStr;
@synthesize delegateEditContact;

- (void)viewDidLoad {
    [super viewDidLoad];
    
    //For get the contact detail from server
    [self contactDetail];
    // Do any additional setup after loading the view.
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 09/09/2015
 */
#pragma mark Back Screen
- (IBAction)backScreen:(id)sender {
    if ([self.delegateEditContact respondsToSelector:@selector(updateContactAfterEditContact)]) {
        [self.navigationController popViewControllerAnimated:NO];
        [self.delegateEditContact updateContactAfterEditContact];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Edit Contact from AddPartnerViewControllers
 * @Modified Date 06/10/2015
 */
#pragma mark Edit Contact Response
-(void)updateContactAfterAddContact {
    if ([self.delegateEditContact respondsToSelector:@selector(updateContactAfterEditContact)]) {
        [self.navigationController popViewControllerAnimated:NO];
        [self.delegateEditContact updateContactAfterEditContact];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for get Contact detail
 * @Modified Date 05/10/2015
 */
#pragma mark Get Contact Detail
- (void)contactDetail {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(contactDetailDelay) withObject:nil afterDelay:0.1];
}
- (void)contactDetailDelay {
    if (isNetworkAvailable) {
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:contactIDStr,@"contactId",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"contactDetail" controls:@"contacts" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            
            recordDict = [parsedJSONToken valueForKey:@"result"];
            
            //For First Name
            NSString *firstNameString = [recordDict valueForKey:@"first_name"];
            if (CheckStringForNull(firstNameString)) {
                firstNameString = @"";
            }
            else {
                firstNameString = [firstNameString stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                         withString:[[firstNameString substringToIndex:1] capitalizedString]];
            }
            //For Last Name
            NSString *lastNameString = [recordDict valueForKey:@"last_name"];
            if (CheckStringForNull(lastNameString)) {
                lastNameString = @"";
            }
            else {
                lastNameString = [NSString stringWithFormat:@" %@",[recordDict valueForKey:@"last_name"]];
                lastNameString = [lastNameString stringByReplacingCharactersInRange:NSMakeRange(0,2)
                                                                         withString:[[lastNameString substringToIndex:2] capitalizedString]];
            }
            firstNameString = [firstNameString stringByAppendingString:lastNameString];
            self.lblName.text = firstNameString;
            [self.lblName sizeToFit];
            
            self.lblProfession.frame = CGRectMake(self.lblProfession.frame.origin.x, self.lblName.frame.origin.x+self.lblName.frame.size.height, self.lblProfession.frame.size.width, self.lblProfession.frame.size.height);
            
            viewOne.frame = CGRectMake(viewOne.frame.origin.x, viewOne.frame.origin.y, viewOne.frame.size.width, self.lblProfession.frame.origin.y+self.lblProfession.frame.size.height+10);
            
            viewTwo.frame = CGRectMake(viewTwo.frame.origin.x, viewOne.frame.origin.y+viewOne.frame.size.height, viewTwo.frame.size.width, viewTwo.frame.size.height);
            
            //For Profession
            NSString *professionString = [recordDict valueForKey:@"job_title"];
            if (CheckStringForNull(professionString)) {
                professionString = @",";
            }
            else {
                professionString = [NSString stringWithFormat:@"%@, ",[recordDict valueForKey:@"job_title"]];
            }
            
            //For Company
            NSString *coumpanyString = [recordDict valueForKey:@"company"];
            coumpanyString = [coumpanyString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(coumpanyString)) {
                coumpanyString = @"NA";
            }
            professionString = [professionString stringByAppendingString:coumpanyString];
            
            if ([professionString isEqualToString:@",NA"]) {
                professionString = @"NA";
                self.lblProfession.textColor = [UIColor darkGrayColor];
            }
            else {
                self.lblProfession.textColor = orangeColour;
            }
            
            self.lblProfession.text = professionString;
            
            //For Email
            NSString *emailString = [recordDict valueForKey:@"email"];
            if (CheckStringForNull(emailString)) {
                self.lblEmail.textColor = [UIColor darkGrayColor];
                emailString = @"NA";
            }
            else {
                self.lblEmail.textColor = orangeColour;
            }
            self.lblEmail.text = emailString;
            
            //For Web Link
            NSString *webLinkString = [recordDict valueForKey:@"website"];
            if (CheckStringForNull(webLinkString)) {
                self.lblWeb.textColor = [UIColor darkGrayColor];
                webLinkString = @"NA";
            }
            else {
                self.lblWeb.textColor = orangeColour;
            }
            self.lblWeb.text = webLinkString;
            
            //For Mobile Number
            NSString *mobileNumString = [recordDict valueForKey:@"mobile"];
            if (CheckStringForNull(mobileNumString)) {
                mobileNumString = @"NA";
            }
            self.lblMobile.text = mobileNumString;
            
            //For Office Number
            NSString *officeNumString = [recordDict valueForKey:@"office_phone"];
            if (CheckStringForNull(officeNumString)) {
                officeNumString = @"NA";
            }
            self.lblPhone.text = officeNumString;
            
            //For Address
            NSString *addressString = [recordDict valueForKey:@"address"];
            addressString = [addressString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            addressString = [addressString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(addressString)) {
                addressString = @"";
            }
            else {
                addressString = [NSString stringWithFormat:@"%@\n",[recordDict valueForKey:@"address"]];
            }
            //For City
            NSString *cityString = [recordDict valueForKey:@"city"];
            cityString = [cityString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(cityString)) {
                cityString = @"";
            }
            else {
                cityString = [NSString stringWithFormat:@"%@, ",[recordDict valueForKey:@"city"]];
            }
            //For Zip
            NSString *zipString = [recordDict valueForKey:@"zip"];
            if (CheckStringForNull(zipString)) {
                zipString = @"";
            }
            else {
                zipString = [NSString stringWithFormat:@"%@, ",[recordDict valueForKey:@"zip"]];
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
            cityString = [cityString stringByAppendingString:zipString];
            addressString = [addressString stringByAppendingString:cityString];

            if (CheckStringForNull(addressString)) {
                addressString = @"NA";
            }
            
            self.lblAddress.text = addressString;
            [self.lblAddress sizeToFit];
            
            self.imgSeprator.frame = CGRectMake(self.imgSeprator.frame.origin.x, self.lblAddress.frame.origin.y+self.lblAddress.frame.size.height+30, self.imgSeprator.frame.size.width, self.imgSeprator.frame.size.height);
            viewTwo.frame = CGRectMake(viewTwo.frame.origin.x, viewTwo.frame.origin.y, viewTwo.frame.size.width, self.imgSeprator.frame.origin.y+10);
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
 * @Description This method are used for Contact Edit
 * @Modified Date 05/10/2015
 */
#pragma mark Edit Contact
-(IBAction)editContact:(id)sender {
    [self performSegueWithIdentifier:@"editContact" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Open web site link on browser
 * @Modified Date 05/10/2015
 */
#pragma mark Redirect On Browser
-(IBAction)redirectOnBrowser:(id)sender {
    NSString *webLinkStr = [self.lblWeb text];
    if (![webLinkStr isEqualToString:@"NA"]) {
        NSArray *arrayYouTube = [webLinkStr componentsSeparatedByString:@"//"];
        NSString *metchString = @"";
        if (arrayYouTube.count > 1) {
            NSString *typeString  = [arrayYouTube objectAtIndex:0];
            if ([typeString isEqualToString:@"http:"] || [typeString isEqualToString:@"https:"]) {
                metchString = @"";
            }
            else {
                metchString = @"http://";
            }
        }
        else {
            metchString = @"http://";
        }
        metchString = [metchString stringByAppendingString:webLinkStr];
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:metchString]];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Send mail
 * @Modified Date 06/10/2015
 */
#pragma mark Send Mail
-(IBAction)sendMail:(id)sender {
    NSString *mailStr = [self.lblEmail text];
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


- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    
    if ([segue.identifier isEqualToString:@"editContact"]) {
        AddPartnersViewController *addPartnersViewController = segue.destinationViewController;
        addPartnersViewController.delegateAddPartner = self;
        addPartnersViewController.checkAddOrEdit = @"edit";
        addPartnersViewController.dictContact = recordDict;
    }
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}


@end
