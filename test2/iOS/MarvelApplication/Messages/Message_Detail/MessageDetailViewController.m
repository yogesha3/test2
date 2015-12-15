//
//  MessageDetailViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 9/22/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "MessageDetailViewController.h"
#import "AsyncImageView.h"
#import "ReadPDFViewController.h"

@interface MessageDetailViewController ()

@end

@implementation MessageDetailViewController
@synthesize delegateMessDetail;
@synthesize messageIDStr;

- (void)viewDidLoad {
    
    scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewChat.frame.origin.y+viewChat.frame.size.height);
    
    imgTextView.layer.cornerRadius = 5.0f;
    imgTextView.layer.borderColor = [UIColor lightGrayColor].CGColor;
    imgTextView.layer.borderWidth = 1.0f;
    
    [textViewComment setPlaceholder:@""];
    
    btnPost.layer.cornerRadius = 5.0;

    [self messageDetail];
    
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
    [self getMessageComment];
    [super viewWillAppear:animated];
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
        NSString *detailPageStr = @"";
        NSString *value = [userDefaultManager valueForKey:@"module"];
        switch (value.intValue) {
            case 0:
                detailPageStr = @"inbox";
                break;
            case 1:
                detailPageStr = @"sent";
                break;
            case 2:
                detailPageStr = @"inboxArchive";
                break;
            case 3:
                detailPageStr = @"sentArchive";
                break;
                
            default:
                break;
        }
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:messageIDStr,@"messageId",detailPageStr,@"detailPage",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequestNotification:@"" methodHeader:@"messageComment" controls:@"messages" httpMethod:@"POST" data:data];
        
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
                viewChat.frame = CGRectMake(viewChat.frame.origin.x, viewChat.frame.origin.y, viewChat.frame.size.width, 257);
            }
            else if (commentArray.count == 2) {
                tableView.frame = CGRectMake(tableView.frame.origin.x, tableView.frame.origin.y, tableView.frame.size.width, 110);
                viewChat.frame = CGRectMake(viewChat.frame.origin.x, viewChat.frame.origin.y, viewChat.frame.size.width, 307);
            }
            else {
                tableView.frame = CGRectMake(tableView.frame.origin.x, tableView.frame.origin.y, tableView.frame.size.width, 165);
                viewChat.frame = CGRectMake(viewChat.frame.origin.x, viewChat.frame.origin.y, viewChat.frame.size.width, 357);
            }
            imgTextView.frame = CGRectMake(imgTextView.frame.origin.x, tableView.frame.origin.y+tableView.frame.size.height+8, imgTextView.frame.size.width, imgTextView.frame.size.height);
            textViewComment.frame = CGRectMake(textViewComment.frame.origin.x, tableView.frame.origin.y+tableView.frame.size.height+13, textViewComment.frame.size.width, textViewComment.frame.size.height);
            btnPost.frame = CGRectMake(btnPost.frame.origin.x, imgTextView.frame.origin.y+imgTextView.frame.size.height+15, btnPost.frame.size.width, btnPost.frame.size.height);
        }
        else {
            imgTextView.frame = CGRectMake(imgTextView.frame.origin.x, lblNoComment.frame.origin.y+lblNoComment.frame.size.height+8, imgTextView.frame.size.width, imgTextView.frame.size.height);
            textViewComment.frame = CGRectMake(textViewComment.frame.origin.x, lblNoComment.frame.origin.y+lblNoComment.frame.size.height+13, textViewComment.frame.size.width, textViewComment.frame.size.height);
            btnPost.frame = CGRectMake(btnPost.frame.origin.x, imgTextView.frame.origin.y+imgTextView.frame.size.height+15, btnPost.frame.size.width, btnPost.frame.size.height);
            
            viewChat.frame = CGRectMake(viewChat.frame.origin.x, viewChat.frame.origin.y, viewChat.frame.size.width, 257);
        }
        scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewChat.frame.origin.y+viewChat.frame.size.height);
        [tableView reloadData];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Send comment
 * @Modified Date 22/09/2015
 */
#pragma mark Send Comment
- (IBAction)sendComment:(id)sender {
    [[AppDelegate currentDelegate] addLoading];
    [self.view endEditing:YES];
    [self performSelector:@selector(sendCommentDelay) withObject:nil afterDelay:0.1];
}
- (void)sendCommentDelay {
    if (isNetworkAvailable) {
        
        NSString *commentString = [textViewComment text];
        
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
    
        NSString *detailPageStr = @"";
        NSString *value = [userDefaultManager valueForKey:@"module"];
        switch (value.intValue) {
            case 0:
                detailPageStr = @"inbox";
                break;
            case 1:
                detailPageStr = @"sent";
                break;
            case 2:
                detailPageStr = @"inboxArchive";
                break;
            case 3:
                detailPageStr = @"sentArchive";
                break;
                
            default:
                break;
        }
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:detailPageStr,@"detailPage",messageIDStr,@"messageId",commentString,@"comment",sendMailTo,@"sendMailTo",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"addMessageComment" controls:@"messages" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            textViewComment.text = @"";
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:[parsedJSONToken valueForKey:@"message"] delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
           
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
 * @Description This method are used for Start Timer
 * @Modified Date 22/09/2015
 */
#pragma mark Start Timer
-(void)getMessageComment {
    
    [commentTimer invalidate];
    commentTimer=nil;
    commentTimer = [NSTimer scheduledTimerWithTimeInterval:5 target:self selector:@selector(updatecomment) userInfo:nil repeats:YES];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for tableView Scroll Position Top
 * @Modified Date 22/09/2015
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
 * @Modified Date 22/09/2015
 */
#pragma mark Get Message Detail
- (void)messageDetail {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(messageDetailDelay) withObject:nil afterDelay:0.1];
}
- (void)messageDetailDelay {
    
    if (isNetworkAvailable) {
        //For Referrals details
        NSString *detailPageStr = @"";
        NSString *value = [userDefaultManager valueForKey:@"module"];
        switch (value.intValue) {
            case 0:
                detailPageStr = @"inbox";
                break;
            case 1:
                detailPageStr = @"sent";
                break;
            case 2:
                detailPageStr = @"inboxArchive";
                break;
            case 3:
                detailPageStr = @"sentArchive";
                break;
                
            default:
                break;
        }
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:detailPageStr,@"detailPage",messageIDStr,@"messageId",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"messageDetail" controls:@"messages" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            
           NSDictionary *recordDict = [parsedJSONToken valueForKey:@"result"];
            //For mailId
            sendMailTo = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"sendMailTo"]];
            if (CheckStringForNull(sendMailTo)) {
                sendMailTo = @"";
            }
            //For User Name
            NSString *userNameString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"user_name"]];
            if (CheckStringForNull(userNameString)) {
                userNameString = @"";
            }
            else {
                userNameString = [userNameString stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                   withString:[[userNameString substringToIndex:1] capitalizedString]];
            }
            lblName.text = userNameString;
            
            //For Date
            NSString *dateString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"created"]];
            if (CheckStringForNull(dateString)) {
                dateString = @"";
            }
            else {
                dateString = ConvertDateStringFormat(dateString);
            }
            lblDate.text = dateString;
            
            //For Receiver Name
            NSString *receiverNameString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"ReceiversName"]];
            if (CheckStringForNull(receiverNameString)) {
                receiverNameString = @"";
            }
            else {
                receiverNameString = [@"To " stringByAppendingString:receiverNameString];
            }
            NSDictionary *userAttributes = @{NSFontAttributeName: [UIFont fontWithName:@"Graphik-Regular" size:12.0],
                                             NSForegroundColorAttributeName: [UIColor blackColor]};
            sizeTeam = [receiverNameString sizeWithAttributes: userAttributes];
            if (sizeTeam.width>viewTop.frame.size.width-75) {
                lblTeamMember.frame = CGRectMake(lblTeamMember.frame.origin.x, lblTeamMember.frame.origin.y, viewTop.frame.size.width-65, lblTeamMember.frame.size.height);
                expendBtn.frame = CGRectMake(lblTeamMember.frame.origin.x+lblTeamMember.frame.size.width+10, expendBtn.frame.origin.y, expendBtn.frame.size.width, expendBtn.frame.size.height);
                [expendBtn setHidden:NO];
                
            }
            else {
                lblTeamMember.frame = CGRectMake(lblTeamMember.frame.origin.x, lblTeamMember.frame.origin.y, sizeTeam.width, lblTeamMember.frame.size.height);
                expendBtn.frame = CGRectMake(lblTeamMember.frame.origin.x+lblTeamMember.frame.size.width+10, expendBtn.frame.origin.y, expendBtn.frame.size.width, expendBtn.frame.size.height);
                [expendBtn setHidden:YES];
            }
            lblTeamMember.text = receiverNameString;
            
            //For Subject
            NSString *subjectString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"subject"]];
            if (CheckStringForNull(subjectString)) {
                subjectString = @"";
            }
            lblSubject.text = subjectString;
            
            //For Message Body
            NSString *messageBodyString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"content"]];
            if (CheckStringForNull(messageBodyString)) {
                messageBodyString = @"";
                textViewMessageBody.text = messageBodyString;
            }
            else {
                if (![[recordDict valueForKey:@"message_type"] isEqualToString:@"message"]) {
                    messageBodyString = [[NSString stringWithFormat:@"Dear %@ %@,\n",[userDefaultManager valueForKey:@"fname"],[userDefaultManager valueForKey:@"lname"]] stringByAppendingString:messageBodyString];
                }
                messageBodyString = [messageBodyString stringByReplacingOccurrencesOfString:@"&lt;br/&gt;" withString:@"\n"];
                messageBodyString = [messageBodyString stringByReplacingOccurrencesOfString:@"\r\n        " withString:@""];
                
                textViewMessageBody.text = messageBodyString;
                [textViewMessageBody setEditable:NO];
                [textViewMessageBody setUserInteractionEnabled:NO];
                [textViewMessageBody sizeToFit];
                
                viewMessBody.frame = CGRectMake(viewMessBody.frame.origin.x, viewTop.frame.origin.y+viewTop.frame.size.height , viewMessBody.frame.size.width, textViewMessageBody.frame.size.height+60);
                
                viewAttach.frame = CGRectMake(viewAttach.frame.origin.x, viewMessBody.frame.origin.y+viewMessBody.frame.size.height, viewAttach.frame.size.width, viewAttach.frame.size.height);
               
                viewChat.frame = CGRectMake(viewChat.frame.origin.x, viewAttach.frame.origin.y+viewAttach.frame.size.height, viewChat.frame.size.width, viewChat.frame.size.height);
            }
            
            //For Files
            [lblAttach setHidden:NO];
            filearray = [recordDict valueForKey:@"files"];
            [imgAttach setHidden:YES];
            if (filearray.count) {
                [imgAttach setHidden:NO];
            }
            int Y = 5;
            for (int i = 0; i<filearray.count; i++) {
                
                [lblAttach setHidden:YES];
                
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
                    [viewAttach addSubview:rightImageView];
                    
                    UILabel *LBL = [[UILabel alloc] initWithFrame:CGRectMake(30, Y, viewAttach.frame.size.width-35, 20)];
                    [LBL setBackgroundColor:[UIColor clearColor]];
                    [LBL setTextColor:orangeColour];
                    LBL.text = docNameStr;
                    [LBL setFont:[UIFont fontWithName:@"Graphik-Regular" size:12.0f]];
                    [LBL setTag:2];
                    [viewAttach addSubview:LBL];
                    
                    UIButton *btn = [UIButton buttonWithType:UIButtonTypeCustom];
                    [btn setBackgroundColor:[UIColor clearColor]];
                    [btn setTag:i];
                    btn.frame = CGRectMake(10, Y, viewAttach.frame.size.width-15, 20);
                    [btn addTarget:self action:@selector(openDocument:) forControlEvents:UIControlEventTouchUpInside];
                    [viewAttach addSubview:btn];
                    
                    Y = Y+40;
                    
                    if (i<filearray.count-1) {
                        viewAttach.frame = CGRectMake(viewAttach.frame.origin.x, viewAttach.frame.origin.y, viewAttach.frame.size.width, viewAttach.frame.size.height+40);
                    }
                }
                
            }
            viewChat.frame = CGRectMake(viewChat.frame.origin.x, viewAttach.frame.origin.y+viewAttach.frame.size.height, viewChat.frame.size.width, viewChat.frame.size.height);
            scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewChat.frame.origin.y+viewChat.frame.size.height);
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
 * @Description This method are used for Expend Name
 * @Modified Date 23/09/2015
 */
#pragma mark Expend Name
-(IBAction)expendNameForShowUserName:(UIButton *)sender {
    if (sender.tag == 0) {
        [sender setBackgroundImage:[UIImage imageNamed:@"upArrowIcon"] forState:UIControlStateNormal];
        if (sizeTeam.width>viewTop.frame.size.width-75) {
            lblTeamMember.frame = CGRectMake(lblTeamMember.frame.origin.x, lblTeamMember.frame.origin.y, viewTop.frame.size.width-65, sizeTeam.height+41);
            [lblTeamMember setNumberOfLines:0];
            [lblTeamMember sizeToFit];
            
            viewTop.frame = CGRectMake(viewTop.frame.origin.x, viewTop.frame.origin.y , viewTop.frame.size.width, lblTeamMember.frame.origin.y+lblTeamMember.frame.size.height+7);
            
            viewMessBody.frame = CGRectMake(viewMessBody.frame.origin.x, viewTop.frame.origin.y+viewTop.frame.size.height , viewMessBody.frame.size.width, viewMessBody.frame.size.height);
            
            viewAttach.frame = CGRectMake(viewAttach.frame.origin.x, viewMessBody.frame.origin.y+viewMessBody.frame.size.height, viewAttach.frame.size.width, viewAttach.frame.size.height);
            
            viewChat.frame = CGRectMake(viewChat.frame.origin.x, viewAttach.frame.origin.y+viewAttach.frame.size.height, viewChat.frame.size.width, viewChat.frame.size.height);
        }
        [sender setTag:1];
    }
    else {
        [sender setBackgroundImage:[UIImage imageNamed:@"downArrowIcon"] forState:UIControlStateNormal];
        lblTeamMember.frame = CGRectMake(lblTeamMember.frame.origin.x, lblTeamMember.frame.origin.y, viewTop.frame.size.width-65, 21);
        [lblTeamMember setNumberOfLines:1];
        
        viewTop.frame = CGRectMake(viewTop.frame.origin.x, viewTop.frame.origin.y , viewTop.frame.size.width, lblTeamMember.frame.origin.y+lblTeamMember.frame.size.height+7);
        
        viewMessBody.frame = CGRectMake(viewMessBody.frame.origin.x, viewTop.frame.origin.y+viewTop.frame.size.height , viewMessBody.frame.size.width, viewMessBody.frame.size.height);
        
        viewAttach.frame = CGRectMake(viewAttach.frame.origin.x, viewMessBody.frame.origin.y+viewMessBody.frame.size.height, viewAttach.frame.size.width, viewAttach.frame.size.height);
        
        viewChat.frame = CGRectMake(viewChat.frame.origin.x, viewAttach.frame.origin.y+viewAttach.frame.size.height, viewChat.frame.size.width, viewChat.frame.size.height);
        [sender setTag:0];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for open document in Web view
 * @Modified Date 22/09/2015
 */
#pragma mark Open Document
-(void)openDocument:(UIButton *)sender {
    docViewURLString =  [[filearray objectAtIndex:sender.tag] valueForKey:@"url"];
    [self performSegueWithIdentifier:@"readMessagePDF" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for update referral and received referral list
 * @Modified Date 22/09/2015
 */
#pragma mark updateReceivedReferralList
-(void)updateReceivedReferralList:(NSString *)value {
    
    switch (value.intValue) {
        case 0:
            if ([self.delegateMessDetail respondsToSelector:@selector(updateStatusReadUnreadGetUpdateFromServer)]) {
                [self.delegateMessDetail updateStatusReadUnreadGetUpdateFromServer];
            }
            break;
        case 1:
            if ([self.delegateMessDetail respondsToSelector:@selector(updateStatusReadUnread)]) {
                [self.delegateMessDetail updateStatusReadUnread];
            }
            break;
        case 2:
            if ([self.delegateMessDetail respondsToSelector:@selector(updateStatusReadUnread)]) {
                [self.delegateMessDetail updateStatusReadUnread];
            }
            break;
        case 3:
            if ([self.delegateMessDetail respondsToSelector:@selector(updateStatusReadUnread)]) {
                [self.delegateMessDetail updateStatusReadUnread];
            }
            break;
        default:
            break;
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
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 22/09/2015
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
    if ([segue.identifier isEqualToString:@"readMessagePDF"]) {
        ReadPDFViewController *readPDFViewController = [segue destinationViewController];
        readPDFViewController.docURLString =  docViewURLString;
    }
}


@end
