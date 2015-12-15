//
//  ReferralsDetailViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 8/14/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "ReferralsDetailViewController.h"
#import "AsyncImageView.h"
#import "ReadPDFViewController.h"

@interface ReferralsDetailViewController ()

@end

@implementation ReferralsDetailViewController
@synthesize delegateDetail;
@synthesize referralIDStr;

- (void)viewDidLoad {
    
    NSString *value = [userDefaultManager valueForKey:@"module"];
    switch (value.intValue) {
        case 0:
            [btnEdit setHidden:NO];
            break;
        case 1:
            [btnEdit setHidden:YES];
            break;
        case 2:
            [btnEdit setHidden:YES];
            break;
        case 3:
            [btnEdit setHidden:YES];
            break;
            
        default:
            break;
    }
    
    btnStatus.layer.cornerRadius = 5.0f;
    btnStatus.clipsToBounds = YES;
    
    scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewComment.frame.origin.y+viewComment.frame.size.height);
    
    imgTextView.layer.cornerRadius = 5.0f;
    imgTextView.layer.borderColor = [UIColor lightGrayColor].CGColor;
    imgTextView.layer.borderWidth = 1.0f;
    
    [textView setPlaceholder:@""];
    
    btnPost.layer.cornerRadius = 5.0;
    
    [self referralDetail];
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}
- (void)viewWillDisappear:(BOOL)animated {
    [commentTimer invalidate];
    commentTimer=nil;
    [super viewWillDisappear:animated];
}
- (void)viewWillAppear:(BOOL)animated {
    [self updatecomment];
    [self performSelector:@selector(tableViewScrollPositionTop) withObject:nil afterDelay:0.1];
    [self getReferralComment];
    [super viewWillAppear:animated];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for tableView Scroll Position Top
 * @Modified Date 14/08/2015
 */
#pragma mark tableViewScrollPosition
-(void)tableViewScrollPositionTop {
    if (commentArray.count>0) {
        NSIndexPath *indexPath = [NSIndexPath indexPathForRow:commentArray.count-1 inSection:0];
        [tableView scrollToRowAtIndexPath:indexPath
                         atScrollPosition:UITableViewScrollPositionTop
                                 animated:YES];
        [tableView reloadData];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for get referral detail
 * @Modified Date 14/08/2015
 */
#pragma mark Get Referral Detail
- (void)referralDetail {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(referralDetailDelay) withObject:nil afterDelay:0.1];
}
- (void)referralDetailDelay {
    if (isNetworkAvailable) {
       //For Referrals details
        NSString *detailPageStr = @"";
        NSString *value = [userDefaultManager valueForKey:@"module"];
        switch (value.intValue) {
            case 0:
                detailPageStr = @"received";
                break;
            case 1:
                detailPageStr = @"sent";
                break;
            case 2:
                detailPageStr = @"archiveReceived";
                break;
            case 3:
                detailPageStr = @"archiveSent";
                break;
                
            default:
                break;
        }
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:detailPageStr,@"detailPage",referralIDStr,@"referralId",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"referralDetail" controls:@"referrals" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            
            recordDict = [parsedJSONToken valueForKey:@"result"];
            
            //For First Name
            NSString *fNameString = [recordDict valueForKey:@"first_name"];
            if (CheckStringForNull(fNameString)) {
                fNameString = @"";
            }
            //For Last Name
            NSString *lNameString = [recordDict valueForKey:@"last_name"];
            if (CheckStringForNull(lNameString)) {
                lNameString = @"";
            }
            NSString *titleString = [NSString stringWithFormat:@"%@ %@",fNameString,lNameString];
            lblName.text = titleString;
            //For Job Title
            NSString *jobTitleString = [recordDict valueForKey:@"job_title"];
            jobTitleString = [jobTitleString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(jobTitleString)) {
                jobTitleString = @"";
            }
            else {
                jobTitleString = [NSString stringWithFormat:@"%@, ",[recordDict valueForKey:@"job_title"]];
            }
            //For Company
            NSString *companyString = [recordDict valueForKey:@"company"];
            companyString = [companyString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(companyString)) {
                companyString = @"";
                if (!CheckStringForNull(jobTitleString)) {
                    jobTitleString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"job_title"]];
                }
            }
            NSString *subTitleString = [NSString stringWithFormat:@"%@%@",jobTitleString,companyString];
            lblsubName.text = subTitleString;
            
            //For Email
            NSString *emailString = [recordDict valueForKey:@"email"];
            if (CheckStringForNull(emailString)) {
                emailString = @"NA";
                lblMail.textColor = [UIColor lightGrayColor];
            }
            else {
                lblMail.textColor = orangeColour;
            }
            lblMail.text = emailString;
            
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
                zipString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"zip"]];
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
            
            textViewAdress.text = addressString;
            textViewAdress.editable = NO;
            textViewAdress.contentInset = UIEdgeInsetsMake(-7.0,0.0,0,0.0);
            //For Web Site
            NSString *webSiteString = [recordDict valueForKey:@"website"];
            if (CheckStringForNull(webSiteString)) {
                webSiteString = @"NA";
                lblWebSite.textColor = [UIColor lightGrayColor];
            }
            else {
                lblWebSite.textColor = orangeColour;
            }
            
            lblWebSite.text = webSiteString;
            //For Mobile Number
            NSString *mobileString = [recordDict valueForKey:@"mobile"];
            if (CheckStringForNull(mobileString)) {
                mobileString = @"NA";
            }
            lblMobile.text = mobileString;
            //For Office Number
            NSString *officeString = [recordDict valueForKey:@"office_phone"];
            if (CheckStringForNull(officeString)) {
                officeString = @"NA";
            }
            lblLandLine.text = officeString;
            
            //For DateAndTime
            NSString *dateAndTimeString = [recordDict valueForKey:@"created"];
            if (CheckStringForNull(dateAndTimeString)) {
                dateAndTimeString = @"";
            }
            else {
                dateAndTimeString = ConvertDateStringFormatWithTime(dateAndTimeString);
            }
            NSArray *dateArr = [dateAndTimeString componentsSeparatedByString:@" "];
            NSString *completeDateStr = [NSString stringWithFormat:@"%@ %@ %@ @ %@ %@",[dateArr objectAtIndex:0],[dateArr objectAtIndex:1],[dateArr objectAtIndex:2],[dateArr objectAtIndex:3],[dateArr objectAtIndex:4]];
            NSMutableAttributedString * stringVal =
            [[NSMutableAttributedString alloc] initWithString:completeDateStr];
            [stringVal addAttribute:NSForegroundColorAttributeName value:orangeColour range:NSMakeRange(completeDateStr.length-10,1)];
            [stringVal addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(completeDateStr.length-10,1)];
            lblDate.attributedText = stringVal;
            
            if ([[userDefaultManager valueForKey:@"module"] isEqualToString:@"0"] || [[userDefaultManager valueForKey:@"module"] isEqualToString:@"2"]) {
                //For Value
                NSString *valueString = @"";
                NSString *val = [recordDict valueForKey:@"monetary_value"];
                if (CheckStringForNull(val)) {
                    valueString = @"$0";
                }
                else {
                    NSString *dolerString = [NSString stringWithFormat:@"$"];
                    NSNumberFormatter *formatter = [NSNumberFormatter new];
                    [formatter setNumberStyle:NSNumberFormatterDecimalStyle]; // this line is important!
                    NSNumber *myNumber = [formatter numberFromString:val];
                    valueString = [formatter stringFromNumber:myNumber];
                    valueString = [dolerString stringByAppendingString:valueString];
                }
                lblValue.text = valueString;
                //For Status
                NSString *statusString = [[recordDict valueForKey:@"referral_status"] capitalizedString];
                if (CheckStringForNull(statusString)) {
                    statusString = @"";
                }
                [btnStatus setTitle:statusString forState:UIControlStateNormal];
                
                [lblStatusHead setHidden:NO];
                [btnStatus setHidden:NO];
                [lblValueHead setHidden:NO];
                [lblValue setHidden:NO];
                viewStatus.frame = CGRectMake(viewStatus.frame.origin.x, viewStatus.frame.origin.y, viewStatus.frame.size.width, 107);
                imgSepratorTwo.frame = CGRectMake(imgSepratorTwo.frame.origin.x, 99, imgSepratorTwo.frame.size.width, imgSepratorTwo.frame.size.height);
            }
            else {
                [lblStatusHead setHidden:YES];
                [btnStatus setHidden:YES];
                [lblValueHead setHidden:YES];
                [lblValue setHidden:YES];
                viewStatus.frame = CGRectMake(viewStatus.frame.origin.x, viewStatus.frame.origin.y, viewStatus.frame.size.width, 50);
                imgSepratorTwo.frame = CGRectMake(imgSepratorTwo.frame.origin.x, 45, imgSepratorTwo.frame.size.width, imgSepratorTwo.frame.size.height);
                
                viewSender.frame = CGRectMake(viewSender.frame.origin.x, viewStatus.frame.origin.y+viewStatus.frame.size.height, viewSender.frame.size.width, viewSender.frame.size.height);
                viewAttachment.frame = CGRectMake(viewAttachment.frame.origin.x, viewSender.frame.origin.y+viewSender.frame.size.height, viewAttachment.frame.size.width, viewAttachment.frame.size.height);
                viewComment.frame = CGRectMake(viewComment.frame.origin.x, viewAttachment.frame.origin.y+viewAttachment.frame.size.height, viewComment.frame.size.width, viewComment.frame.size.height);
            }
            
            //For Message
            NSString *messageString = [recordDict valueForKey:@"message"];
            if (CheckStringForNull(messageString)) {
                messageString = @"No message from sender";
            }
            viewMessage.text = messageString;
            viewMessage.editable = NO;
            
            //For Files
            [lblNoAttach setHidden:NO];
            filearray = [recordDict valueForKey:@"files"];
            int Y = 5;
            for (int i = 0; i<filearray.count; i++) {
                
                [lblNoAttach setHidden:YES];
                
                NSString *urlString = [[filearray objectAtIndex:i] valueForKey:@"url"];
                NSURL *url = [NSURL URLWithString:urlString];
                if (url != nil) {
                    NSString *docExtension = [[url pathExtension] uppercaseString];
                    UIImage *image = nil;
                    NSString *docNameStr = [[filearray objectAtIndex:i] valueForKey:@"name"];
                    if ([docExtension isEqualToString:@"DOC"] || [docExtension isEqualToString:@"DOCS"] || [docExtension isEqualToString:@"DOCX"]) {
                        image = [UIImage imageNamed:@"docsIcn.png"];
                    }
                    else if ([docExtension isEqualToString:@"XLS"] || [docExtension isEqualToString:@"XLSX"]) {
                        image = [UIImage imageNamed:@"xlsIcn.png"];
                    }
                    else if ([docExtension isEqualToString:@"PDF"]) {
                        image = [UIImage imageNamed:@"pdfIcn.png"];
                    }
                    else if([docExtension isEqualToString:@"PNG"] || [docExtension isEqualToString:@"JPEG"] || [docExtension isEqualToString:@"JPG"]) {
                        image = [UIImage imageNamed:@"imgIcon.png"];
                    }
                    
                    UIImageView *rightImageView = [[UIImageView alloc] initWithFrame:CGRectMake(10, Y+5, 8 , 10)];
                    rightImageView.backgroundColor = [UIColor clearColor];
                    [rightImageView setImage:image];
                    [viewAttachment addSubview:rightImageView];
                    
                    UILabel *LBL = [[UILabel alloc] initWithFrame:CGRectMake(30, Y, viewAttachment.frame.size.width-35, 20)];
                    [LBL setBackgroundColor:[UIColor clearColor]];
                    [LBL setTextColor:orangeColour];
                    LBL.text = docNameStr;
                    [LBL setFont:[UIFont fontWithName:@"Graphik-Regular" size:12.0f]];
                    [LBL setTag:2];
                    [viewAttachment addSubview:LBL];
                    
                    UIButton *btn = [UIButton buttonWithType:UIButtonTypeCustom];
                    [btn setBackgroundColor:[UIColor clearColor]];
                    [btn setTag:i];
                    btn.frame = CGRectMake(10, Y, viewAttachment.frame.size.width-15, 20);
                    [btn addTarget:self action:@selector(openDocument:) forControlEvents:UIControlEventTouchUpInside];
                    [viewAttachment addSubview:btn];
                    
                    Y = Y+40;
                    
                    if (i<filearray.count-1) {
                        viewAttachment.frame = CGRectMake(viewAttachment.frame.origin.x, viewAttachment.frame.origin.y, viewAttachment.frame.size.width, viewAttachment.frame.size.height+40);
                    }
                }

            }
            viewComment.frame = CGRectMake(viewComment.frame.origin.x, viewAttachment.frame.origin.y+viewAttachment.frame.size.height, viewComment.frame.size.width, viewComment.frame.size.height);
            scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewComment.frame.origin.y+viewComment.frame.size.height);
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
 * @Description This method are used for update referral and received referral list
 * @Modified Date 20/08/2015
 */
#pragma mark updateReceivedReferralList
-(void)updateReceivedReferralList:(NSString *)value {

    switch (value.intValue) {
        case 0:
            if ([self.delegateDetail respondsToSelector:@selector(updateStatusReadUnreadGetUpdateFromServer)]) {
                [self.delegateDetail updateStatusReadUnreadGetUpdateFromServer];
            }
            break;
        case 1:
            break;
        case 2:
            if ([self.delegateDetail respondsToSelector:@selector(updateStatusReadUnread)]) {
                [self.delegateDetail updateStatusReadUnread];
            }
            break;
        case 3:
            break;
        default:
            break;
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Open web site link on browser
 * @Modified Date 18/08/2015
 */
#pragma mark Redirect On Browser
-(IBAction)redirectOnBrowser:(id)sender {
    NSString *webLinkStr = [lblWebSite text];
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
 * @Modified Date 18/08/2015
 */
#pragma mark Send Mail
-(IBAction)sendMail:(id)sender {
    NSString *mailStr = [lblMail text];
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
 * @Description This method are used for Start Timer
 * @Modified Date 13/08/2015
 */
#pragma mark Start Timer
-(void)getReferralComment {
    
    [commentTimer invalidate];
    commentTimer=nil;
    commentTimer = [NSTimer scheduledTimerWithTimeInterval:5 target:self selector:@selector(updatecomment) userInfo:nil repeats:YES];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Update Notification
 * @Modified Date 13/08/2015
 */
#pragma mark Update Comment
-(void)updatecomment {
    if (netWorkNotAvilable) {
        //For Get Comment List
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:referralIDStr,@"referralId",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequestNotification:@"" methodHeader:@"referralComment" controls:@"referrals" httpMethod:@"POST" data:data];
        
        commentArray = [[NSMutableArray alloc] init];
        [tableView setHidden:YES];
        [lblNoComment setHidden:NO];
        if (parsedJSONToken != nil) {
            commentArray = [parsedJSONToken valueForKey:@"result"];
            [tableView setHidden:NO];
            [lblNoComment setHidden:YES];
        }
        
        if (commentArray.count) {
            if (commentArray.count == 1) {
                tableView.frame = CGRectMake(tableView.frame.origin.x, tableView.frame.origin.y, tableView.frame.size.width, 55);
                viewComment.frame = CGRectMake(viewComment.frame.origin.x, viewComment.frame.origin.y, viewComment.frame.size.width, 257);
            }
            else if (commentArray.count == 2) {
                tableView.frame = CGRectMake(tableView.frame.origin.x, tableView.frame.origin.y, tableView.frame.size.width, 110);
                viewComment.frame = CGRectMake(viewComment.frame.origin.x, viewComment.frame.origin.y, viewComment.frame.size.width, 307);
            }
            else {
                tableView.frame = CGRectMake(tableView.frame.origin.x, tableView.frame.origin.y, tableView.frame.size.width, 165);
                viewComment.frame = CGRectMake(viewComment.frame.origin.x, viewComment.frame.origin.y, viewComment.frame.size.width, 357);
            }
            imgTextView.frame = CGRectMake(imgTextView.frame.origin.x, tableView.frame.origin.y+tableView.frame.size.height+8, imgTextView.frame.size.width, imgTextView.frame.size.height);
            textView.frame = CGRectMake(textView.frame.origin.x, tableView.frame.origin.y+tableView.frame.size.height+13, textView.frame.size.width, textView.frame.size.height);
            btnPost.frame = CGRectMake(btnPost.frame.origin.x, imgTextView.frame.origin.y+imgTextView.frame.size.height+15, btnPost.frame.size.width, btnPost.frame.size.height);
        }
        else {
            imgTextView.frame = CGRectMake(imgTextView.frame.origin.x, lblNoComment.frame.origin.y+lblNoComment.frame.size.height+8, imgTextView.frame.size.width, imgTextView.frame.size.height);
            textView.frame = CGRectMake(textView.frame.origin.x, lblNoComment.frame.origin.y+lblNoComment.frame.size.height+13, textView.frame.size.width, textView.frame.size.height);
            btnPost.frame = CGRectMake(btnPost.frame.origin.x, imgTextView.frame.origin.y+imgTextView.frame.size.height+15, btnPost.frame.size.width, btnPost.frame.size.height);
            
            viewComment.frame = CGRectMake(viewComment.frame.origin.x, viewComment.frame.origin.y, viewComment.frame.size.width, 257);
        }
        scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewComment.frame.origin.y+viewComment.frame.size.height);
        [tableView reloadData];
    }
}


#pragma marl - UITableView Data Source
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return commentArray.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView1 cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    static NSString *PlaceholderCellIdentifier = @"PlaceholderCell";
    
    UITableViewCell *cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
    
    if (cell == nil)
    {
        cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
        cell.backgroundColor = [UIColor clearColor];
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        
        AsyncImageView *iconImageView = [[AsyncImageView alloc] initWithFrame:CGRectMake(10, 10, 30, 30)];
        [iconImageView setBackgroundColor:[UIColor clearColor]];
        [iconImageView setTag:1];
        iconImageView.layer.cornerRadius = 15.0f;
        [cell.contentView addSubview:iconImageView];
        
        UILabel *lblComment = [[UILabel alloc] initWithFrame:CGRectMake(50, 0, tableView.frame.size.width-55, 30)];
        [lblComment setBackgroundColor:[UIColor clearColor]];
        [lblComment setTextColor:[UIColor lightGrayColor]];
        [lblComment setNumberOfLines:0];
        [lblComment setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
        [lblComment setTag:2];
        [cell.contentView addSubview:lblComment];
        
        UILabel *lblDateComment = [[UILabel alloc] initWithFrame:CGRectMake(50, lblComment.frame.size.height+5, cell.frame.size.width-55, 20)];
        [lblDateComment setBackgroundColor:[UIColor clearColor]];
        [lblDateComment setTextColor:[UIColor lightGrayColor]];
        [lblDateComment setFont:[UIFont fontWithName:@"Graphik-Regular" size:12.0f]];
        [lblDateComment setTag:3];
        [cell.contentView addSubview:lblDateComment];
    }
    
    NSDictionary *dict = [commentArray objectAtIndex:indexPath.row];
    //For Image
    NSString *imageURLStr = [dict valueForKey:@"commented_by_profile_image"];
    AsyncImageView *commentImage = (AsyncImageView*)[cell viewWithTag:1];
    [commentImage setImageURL:[NSURL URLWithString:imageURLStr]];
    commentImage.layer.cornerRadius = 15.0f;
    //For User
    NSString *nameStr = [dict valueForKey:@"commented_by"];
    NSString *commentedStr = [NSString stringWithFormat:@"%@ %@",[dict valueForKey:@"commented_by"],[dict valueForKey:@"comment"]];
    UILabel *lblCommen = (UILabel*)[cell viewWithTag:2];
    NSMutableAttributedString * stringVal = [[NSMutableAttributedString alloc] initWithString:commentedStr];
    [stringVal addAttribute:NSForegroundColorAttributeName value:orangeColour range:NSMakeRange(0,nameStr.length)];
    [stringVal addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(0,nameStr.length)];
    [stringVal addAttribute:NSForegroundColorAttributeName value:[UIColor lightGrayColor] range:NSMakeRange(nameStr.length,commentedStr.length-nameStr.length)];
    [stringVal addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(nameStr.length,commentedStr.length-nameStr.length)];
    lblCommen.attributedText = stringVal;
    
    
    //For Date
    UILabel *lblSetDate = (UILabel*)[cell viewWithTag:3];
    
    CGSize size = [self MaxHeighForTextInRow:commentedStr width:cell.frame.size.width-55];
    lblCommen.frame = CGRectMake(50, 0, tableView.frame.size.width-55, size.height+5);
    lblSetDate.frame = CGRectMake(50, lblCommen.frame.size.height+5, cell.frame.size.width-55, 20);
    
    NSString *dateStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"created"]];
    if (CheckStringForNull(dateStr)) {
        dateStr = @"";
    }
    else {
        dateStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"created"]];
        dateStr = ConvertDateStringFormatWithTime(dateStr);
    }
    NSString *finalDateStr = @"Posted on ";
    finalDateStr = [finalDateStr stringByAppendingString:dateStr];
    lblSetDate.text = finalDateStr;
    
    return cell;
    
}


- (void)tableView:(UITableView *)tableView1 didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    
}

- (CGFloat)tableView:(UITableView *)aTableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
   
    NSDictionary *dict = [commentArray objectAtIndex:indexPath.row];
    NSString *commentedStr = [NSString stringWithFormat:@"%@ %@",[dict valueForKey:@"commented_by"],[dict valueForKey:@"comment"]];
    CGSize size = [self MaxHeighForTextInRow:commentedStr width:tableView.frame.size.width-55];
    
    return (size.height+35);
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

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Send comment
 * @Modified Date 17/08/2015
 */
#pragma mark Send Comment
- (IBAction)sendComment:(id)sender {
    [[AppDelegate currentDelegate] addLoading];
    [self.view endEditing:YES];
    [self performSelector:@selector(sendCommentDelay) withObject:nil afterDelay:0.1];
}
- (void)sendCommentDelay {
    if (isNetworkAvailable) {
        
        NSString *commentString = [textView text];
        
        if (CheckStringForNull(commentString)) {
            [[AppDelegate currentDelegate] removeLoading];
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Comment field cannot be left blank." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            return;
        }
        if (commentString.length > 350){
            [[AppDelegate currentDelegate] removeLoading];
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Only 350 characters allowed." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            return;
            
        }
        //commentString = [commentString stringByAppendingString:@"\n"];
        
        NSString *detailPageStr = @"";
        NSString *detailTypeStr = @"";
        NSString *value = [userDefaultManager valueForKey:@"module"];
        switch (value.intValue) {
            case 0:
                detailPageStr = @"received";
                detailTypeStr = @"received";
                break;
            case 1:
                detailPageStr = @"sent";
                detailTypeStr = @"sent";
                break;
            case 2:
                detailPageStr = @"archiveReceived";
                detailTypeStr = @"received";
                break;
            case 3:
                detailPageStr = @"archiveSent";
                detailTypeStr = @"sent";
                break;
                
            default:
                break;
        }
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:detailPageStr,@"detailPage",referralIDStr,@"referralId",commentString,@"comment",detailTypeStr,@"type",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"addReferralComment" controls:@"referrals" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:[parsedJSONToken valueForKey:@"message"] delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            textView.text = @"";
            [self updatecomment];
            if (commentArray.count) {
                NSIndexPath *indexPath = [NSIndexPath indexPathForRow:commentArray.count-1 inSection:0];
                [tableView scrollToRowAtIndexPath:indexPath
                                 atScrollPosition:UITableViewScrollPositionTop
                                         animated:YES];
            }
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
 * @Description This method are used for Edit received referrals
 * @Modified Date 08/09/2015
 */
#pragma mark Edit Referrals
-(IBAction)editReferrals:(id)sender {
    [self performSegueWithIdentifier:@"editRef" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for open document in Web view
 * @Modified Date 17/08/2015
 */
#pragma mark Open Document
-(void)openDocument:(UIButton *)sender {
    docViewURLString =  [[filearray objectAtIndex:sender.tag] valueForKey:@"url"];
    [self performSegueWithIdentifier:@"readPDF" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 14/08/2015
 */
#pragma mark Back Screen
- (IBAction)backScreen:(id)sender {
    NSString *value = [userDefaultManager valueForKey:@"module"];
    [self updateReceivedReferralList:value];
    [self.navigationController popViewControllerAnimated:NO];
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    if ([segue.identifier isEqualToString:@"readPDF"]) {
        ReadPDFViewController *readPDFViewController = [segue destinationViewController];
        readPDFViewController.docURLString =  docViewURLString;
    }
    if ([segue.identifier isEqualToString:@"editRef"]) {
        EditReffralsViewController *editReffralsViewController = [segue destinationViewController];
        editReffralsViewController.editRefDict =  recordDict;
        editReffralsViewController.delegateEditReferral = self;
    }
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for get update of referral from came back to Edit referral page.
 * @Modified Date 08/09/2015
 */
#pragma mark get update referral from came back to Edit referral page
-(void)updateReferralFromEdit {
    if ([self.delegateDetail respondsToSelector:@selector(updateReceivedReferralFromEditReferral)]) {
        [self.delegateDetail updateReceivedReferralFromEditReferral];
    }
}
@end
