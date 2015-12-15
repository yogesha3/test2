
//  ReferralViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/20/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "ContactViewController.h"
#import "ContactTableCell.h"
#import "UITableView+DragLoad.h"
#import "ContactDetailViewController.h"

#define partnerNameSpecial        @"0123456789~`@#$%^&*()_+={}[]|;'<>?,₹€£/:\""

@interface ContactViewController () <UITableViewDragLoadDelegate>
@property (nonatomic) IBOutlet UIButton* revealButtonItem;
@end

@implementation ContactViewController
@synthesize delegateContactClass;
@synthesize lblNotiCount;

- (void)viewDidLoad {
    
    lblNotiCount.layer.cornerRadius = 5.0f;
    lblNotiCount.clipsToBounds = YES;
    
    [btnSelectAll setUserInteractionEnabled:YES];
    [btnSearchMore setUserInteractionEnabled:YES];
    
    
    NSString *defaultcommentByLoginUser = [NSString stringWithFormat:@"You've been invited by %@ to join FoxHopr, Sign up today and get FoxHopping.",[AppDelegate currentDelegate].updateUserIdString];
    defaultMessageStr = defaultcommentByLoginUser;
    textViewPartnerComment.text = defaultcommentByLoginUser;
    textViewPartnerComment.contentInset = UIEdgeInsetsMake(-10.0,0.0,0,0.0);
    
    //For Invite Partner
    [viewInviteFriend setHidden:YES];
    imgPartnerName.layer.cornerRadius = 5.0f;
    imgPartnerEmail.layer.cornerRadius = 5.0f;
    imgCommentPartner.layer.cornerRadius = 5.0f;
    scrollInviteFriend.contentSize = CGSizeMake(scrollInviteFriend.frame.size.width, viewCommentPartner.frame.origin.x+viewCommentPartner.frame.size.height+10);
    
    [userDefaultManager setObject:@"" forKey:@"searchText"];
    [userDefaultManager setObject:@"" forKey:@"filter"];
    [userDefaultManager setObject:@"" forKey:@"filter_name"];
    [userDefaultManager setObject:@"" forKey:@"pre_Select"];
    
    recordArray = [[NSMutableArray alloc] init];
    [self customSetup];
    
    self.selectedImage = [UIImage imageNamed:@"Selected.png"];
    self.unSelectedImage = [UIImage imageNamed:@"Unselected.png"];
    self.readMsgImage = [UIImage imageNamed:@"readMsgIcon.png"];
    self.unReadMsgImage = [UIImage imageNamed:@"unReadMsgIcon.png"];
    
    botomView.layer.borderWidth = 1.0f;
    botomView.layer.borderColor = BoarderColour.CGColor;
    
    contantScroll.contentSize = CGSizeMake(370, 50);
    self.tableView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    
    [self.tableView setDragDelegate:self refreshDatePermanentKey:@"FriendList"];
    self.tableView.showLoadMoreView = YES;
    
    //self.scrollView.contentSize = CGSizeMake(640, self.scrollView.frame.size.height);
    
    [userDefaultManager setObject:@"0" forKey:@"module"];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    [self contactList];
    
    shortArrayOne = [NSArray arrayWithObjects:@{@"value":@"Created Date (Newest First)"},
                     @{@"value":@"Created Date (Oldest First)"},
                     @{@"value":@"Contact name (A to Z)"},
                     @{@"value":@"Contact name (Z to A)"},
                     @{@"value":@"Email (A to Z)"},
                     @{@"value":@"Email (Z to A)"},
                     @{@"value":@"Profession (A to Z)"},
                     @{@"value":@"Profession (Z to A)"},nil];
    
    shortArrayTwo = [NSArray arrayWithObjects:@{@"value":@"Sent On (Newest First)"},
                     @{@"value":@"Sent On (Oldest First)"},
                     @{@"value":@"Partner Email (A to Z)"},
                     @{@"value":@"Partner Email (Z to A)"},
                     @{@"value":@"Partner Name (A to Z)"},
                     @{@"value":@"Partner Name (Z to A)"},
                     @{@"value":@"Referral Amount (High to Low)"},
                     @{@"value":@"Referral Amount (Low to High)"},
                     @{@"value":@"Status (A to Z)"},
                     @{@"value":@"Status (Z to A)"},nil];
    
    shortArray = shortArrayOne;
    
    //For send invitation
    imgBgInviteFriend.layer.cornerRadius = 5.0f;
    btnSendInvitation.layer.cornerRadius = 5.0f;
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

// Called when the view is about to made visible. Default does nothing
-(void)viewWillAppear:(BOOL)animated {
    [self setNotificationValue];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(setNotificationValue) name:@"referralsCount" object:nil];
    [self.tableView reloadData];
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
    NSString *checkModule = [userDefaultManager valueForKey:@"module"];
    int value = checkModule.intValue;
    switch (value) {
        case 0:
             break;
        case 1:
            break;
        case 2:{
            NSString *defaultcommentByLoginUser = [NSString stringWithFormat:@"You've been invited by %@ to join FoxHopr, Sign up today and get FoxHopping.",[AppDelegate currentDelegate].updateUserIdString];
            if ([textViewPartnerComment.text isEqualToString:defaultMessageStr]) {
                textViewPartnerComment.text = defaultcommentByLoginUser;
                defaultMessageStr = defaultcommentByLoginUser;
                textViewPartnerComment.contentInset = UIEdgeInsetsMake(-10.0,0.0,0,0.0);
            }
        }
            break;
            
        default:
            break;
    }
    
}


/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Contact List
 * @Modified Date 30/09/2015
 */
#pragma mark Contact List
- (void)contactList {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(contactListDelay) withObject:nil afterDelay:0.1];
}
-(void)contactListDelay {
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:pageCountStr,@"page_no",recordPerPage,@"record_per_page",@"",@"search_filter",@"",@"sort_data",@"",@"sort_direction",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getContactList" controls:@"contacts" httpMethod:@"POST" data:data];
        
        [self hideTable];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalContacts"];
            [userDefaultManager setObject:pageNoString forKey:@"page_no"];
            [userDefaultManager setObject:totalRecString forKey:@"totalRecord"];
            NSArray *array = [parsedJSONToken valueForKey:@"result"];
            for (NSDictionary *dictionary in array){
                [recordArray addObject:dictionary];
            }
        }
        [btnDelete setHidden:YES];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
    }
    else {
        [btnDelete setHidden:YES];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [self populateUnSelected];
    [self.tableView reloadData];
    [[AppDelegate currentDelegate] removeLoading];
    
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Hide Table
 * @Modified Date 31/07/2015
 */
#pragma mark hideTable
-(void)hideTable {
    [norecordView setHidden:NO];
    [lblNoRecord setText:@"No record found"];
    [self.tableView setHidden:YES];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Hide Table
 * @Modified Date 01/10/2015
 */
#pragma mark hideTable & noRecordView
-(void)hideTableAndNoRecordView {
    [norecordView setHidden:YES];
    [lblNoRecord setText:@"No record found"];
    [self.tableView setHidden:YES];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Show table
 * @Modified Date 31/07/2015
 */
#pragma mark showTable
-(void)showTable {
    [norecordView setHidden:YES];
    [lblNoRecord setText:@"No record found"];
    [self.tableView setHidden:NO];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Hide Table On Search
 * @Modified Date 11/08/2015
 */
#pragma mark hideTable On Search
-(void)hideTableOnSearch {
    [norecordView setHidden:NO];
    [lblNoRecord setText:@"No results found"];
    [self.tableView setHidden:YES];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Partner List
 * @Modified Date 30/09/2015
 */
#pragma mark Partner List
- (void)partnerList {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(partnerListDelay) withObject:nil afterDelay:0.1];
}
- (void)partnerListDelay {
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        //For login
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:pageCountStr,@"page_no",recordPerPage,@"record_per_page",@"",@"search_filter",@"",@"sort_data",@"",@"sort_direction",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getPartnersList" controls:@"contacts" httpMethod:@"POST" data:data];
        
        [self hideTable];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalPartners"];
            [userDefaultManager setObject:pageNoString forKey:@"page_no"];
            [userDefaultManager setObject:totalRecString forKey:@"totalRecord"];
            NSArray *array = [parsedJSONToken valueForKey:@"result"];
            for (NSDictionary *dictionary in array){
                [recordArray addObject:dictionary];
            }
        }
        [btnDelete setHidden:YES];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
    }
    else {
        [btnDelete setHidden:YES];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [self populateUnSelected];
    [self.tableView reloadData];
    [[AppDelegate currentDelegate] removeLoading];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Reval View
 * @Modified Date 9/07/2015
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

- (void)scrollViewDidScroll:(UIScrollView *)scrollView1 {
    
    CGFloat pageWidth = scrollView1.frame.size.width;
    float fractionalPage = scrollView1.contentOffset.x / pageWidth;
    
    if (fractionalPage==1) {
        NSLog(@"second page");
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Finish load more
 * @Modified Date 9/07/2015
 */
#pragma mark Finish Load More
- (void)finishLoadMore {
    [self.tableView finishLoadMore];
    if (isNetworkAvailable) {
        NSString *checkModule = [userDefaultManager valueForKey:@"module"];
        NSString *pageString = [userDefaultManager valueForKey:@"page_no"];
        NSString *totalRecString = [userDefaultManager valueForKey:@"totalRecord"];
        int value = checkModule.intValue;
        switch (value) {
            case 0:
                if (recordArray.count < totalRecString.intValue) {
                    int pageCount = pageString.intValue;
                    pageCount = pageCount+1;
                    NSString *pageCountStr = [NSString stringWithFormat:@"%d",pageCount];
                    [userDefaultManager setObject:pageCountStr forKey:@"page_no"];
                    
                    NSString *searchStr =[userDefaultManager valueForKey:@"searchText"];
                    NSString *shortDesStr = [userDefaultManager valueForKey:@"filter"];
                    NSString *shortDataStr = [userDefaultManager valueForKey:@"filter_name"];
                    if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                        [self contactList];
                    }
                    else {
                        [self doneSearchLoadMore];
                    }
                    
                }
                break;
            case 1:
                if (recordArray.count < totalRecString.intValue){
                    int pageCount = pageString.intValue;
                    pageCount = pageCount+1;
                    NSString *pageCountStr = [NSString stringWithFormat:@"%d",pageCount];
                    [userDefaultManager setObject:pageCountStr forKey:@"page_no"];
                    
                    NSString *searchStr =[userDefaultManager valueForKey:@"searchText"];
                    NSString *shortDesStr = [userDefaultManager valueForKey:@"filter"];
                    NSString *shortDataStr = [userDefaultManager valueForKey:@"filter_name"];
                    if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                        [self partnerList];
                    }
                    else {
                        [self doneSearchLoadMore];
                    }
                }
                break;
                
            default:
                break;
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
}

- (void)dragTableDidTriggerLoadMore:(UITableView *)tableView
{
    //send load more request(generally network request) here
    
    [self performSelector:@selector(finishLoadMore) withObject:nil afterDelay:2];
}

- (void)dragTableLoadMoreCanceled:(UITableView *)tableView
{
    //cancel load more request(generally network request) here
    
    [NSObject cancelPreviousPerformRequestsWithTarget:self selector:@selector(finishLoadMore) object:nil];
}


#pragma marl - UITableView Data Source
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return [recordArray count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView1 cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    static NSString *PlaceholderCellIdentifier = @"ContactTableCell";
    
    ContactTableCell*cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
    cell.backgroundColor = [UIColor clearColor];
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
    if (cell == nil) {
        cell = [[ContactTableCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
    }
    
    [cell.btnMore setHidden:YES];
    
    if (recordArray.count) {
        NSDictionary *dict = [recordArray objectAtIndex:indexPath.row];
        
         NSString *checkModule = [userDefaultManager valueForKey:@"module"];
         BOOL flag;
         int value = checkModule.intValue;
         switch (value) {
             case 0:
                 cell.btnMore.tag = indexPath.row;
                 [cell.btnMore setHidden:NO];
                 flag = true;
                 break;
             case 1:
                 flag = false;
                 break;
             default:
                 break;
         }

        if (flag) {
            
            [cell.btnSelectRow setHidden:NO];
            [cell.imgPendingInvitation setHidden:YES];
            [cell.lblAmount setHidden:YES];
            
            //For First Name
            NSString *firstNameStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"first_name"]];
            if (CheckStringForNull(firstNameStr)) {
                firstNameStr = @"";
            }
            else {
                firstNameStr = [firstNameStr stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                     withString:[[firstNameStr substringToIndex:1] capitalizedString]];
            }
            //For Last Name
            NSString *lastNameStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"last_name"]];
            if (CheckStringForNull(lastNameStr)) {
                lastNameStr = @"";
            }
            else {
                lastNameStr = [NSString stringWithFormat:@" %@",[dict valueForKey:@"last_name"]];
                lastNameStr = [lastNameStr stringByReplacingCharactersInRange:NSMakeRange(0,2)
                                                                   withString:[[lastNameStr substringToIndex:2] capitalizedString]];
            }
            
            firstNameStr = [firstNameStr stringByAppendingString:lastNameStr];
            
            cell.lblTitle.text = firstNameStr;
            
            //For Profission
            cell.lblProfection.frame = CGRectMake(61, cell.lblProfection.frame.origin.y, cell.lblProfection.frame.size.width, cell.lblProfection.frame.size.height);
            NSString *profissionStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"job_title"]];
            if (CheckStringForNull(profissionStr)) {
                profissionStr = @"NA";
            }
            cell.lblProfection.text  = profissionStr;
            [cell.imgProfession setHidden:NO];
            [cell.imgProfession setImage:[UIImage imageNamed:@"professionIcon"]];
            
            //For Email
            NSString *nameStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"email"]];
            cell.lblUserName.text  = nameStr;
            
            //For Created Date
            cell.lblDate.text = ConvertDateStringFormat([dict valueForKey:@"created"]) ;
            
            [cell.btnSelectRow setTag:indexPath.row];
            [cell.btnSelectRow addTarget:self action:@selector(btnSelectRow:) forControlEvents:UIControlEventTouchUpInside];
            NSNumber *selected = [selectedRowForDeleteArray objectAtIndex:[indexPath row]];
            UIImage *image = ([selected boolValue]) ? self.selectedImage : self.unSelectedImage;
            [cell.btnSelectRow setBackgroundImage:image forState:UIControlStateNormal];
        }
        else {
            [cell.imgProfession setHidden:YES];
            [cell.imgPendingInvitation setHidden:NO];
            [cell.lblAmount setHidden:NO];
            [cell.btnSelectRow setHidden:YES];
            

            //For Invitee Name
            NSString *inviteeNameStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"invitee_name"]];
            if (CheckStringForNull(inviteeNameStr)) {
                inviteeNameStr = @"";
            }
            else {
                inviteeNameStr = [inviteeNameStr stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                     withString:[[inviteeNameStr substringToIndex:1] capitalizedString]];
            }
            cell.lblTitle.text = inviteeNameStr;
            
            //For Status
            cell.lblProfection.frame = CGRectMake(47, cell.lblProfection.frame.origin.y, cell.lblProfection.frame.size.width, cell.lblProfection.frame.size.height);
            NSString *statusStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"status"]];
            NSMutableAttributedString * string = nil;
            if (!CheckStringForNull(statusStr)) {
                NSString *statusString = [NSString stringWithFormat:@"Status: %@",statusStr.capitalizedString];
                string = [[NSMutableAttributedString alloc] initWithString:statusString];
                [string addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:NSMakeRange(0,8)];
                [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(0,8)];
                [string addAttribute:NSForegroundColorAttributeName value:[UIColor lightGrayColor] range:NSMakeRange(8,statusString.length-8)];
                [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:12.0] range:NSMakeRange(8,statusString.length-8)];
            }
            else {
                string = [[NSMutableAttributedString alloc] initWithString:@"Status: "];
                [string addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:NSMakeRange(0,8)];
                [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(0,8)];
            }
            cell.lblProfection.attributedText = string;
            
            if ([statusStr isEqualToString:@"pending"]) {
                [cell.imgPendingInvitation setImage:[UIImage imageNamed:@"statusPendingIcon"]];
            }
            else {
                [cell.imgPendingInvitation setImage:[UIImage imageNamed:@"statusAcceptIcon"]];
            }
            
            //For Amount
            NSString *amountStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"referral_amount"]];
            if (CheckStringForNull(amountStr)) {
                amountStr = @"Value:$0";
            }
            else {
                amountStr = [NSString stringWithFormat:@"Value:$%@",[dict valueForKey:@"referral_amount"]];
            }
            
            NSMutableAttributedString *stringValue = [[NSMutableAttributedString alloc] initWithString:amountStr];
            [stringValue addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:NSMakeRange(0,6)];
            [stringValue addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(0,8)];
            [stringValue addAttribute:NSForegroundColorAttributeName value:[UIColor lightGrayColor] range:NSMakeRange(6,amountStr.length-6)];
            [stringValue addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:12.0] range:NSMakeRange(6,amountStr.length-6)];

            cell.lblAmount.attributedText = stringValue;
            
           
            //For Invitee Email
            NSString *emailStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"invitee_email"]];
            cell.lblUserName.text  = emailStr;
            
            //For Created Date
            cell.lblDate.text = ConvertDateStringFormat([dict valueForKey:@"created"]) ;
            
            [cell.btnSelectRow setTag:indexPath.row];
            [cell.btnSelectRow addTarget:self action:@selector(btnSelectRow:) forControlEvents:UIControlEventTouchUpInside];
            NSNumber *selected = [selectedRowForDeleteArray objectAtIndex:[indexPath row]];
            UIImage *image = ([selected boolValue]) ? self.selectedImage : self.unSelectedImage;
            [cell.btnSelectRow setBackgroundImage:image forState:UIControlStateNormal];

        }
        
    }
    return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    NSString *checkModule = [userDefaultManager valueForKey:@"module"];
    int value = checkModule.intValue;
    switch (value) {
        case 0:{
            NSDictionary *dictReco = [recordArray objectAtIndex:indexPath.row];
            contactIDStr = [dictReco valueForKey:@"id"];
            [self performSegueWithIdentifier:@"contact_Detail" sender:nil];
            saveIndex = (int)indexPath.row;
        }
            break;
        default:
            break;
    }
}

- (CGFloat)tableView:(UITableView *)aTableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    return 75;
}

-(NSString *)tableView:(UITableView *)tableView titleForDeleteConfirmationButtonForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    NSString *checkModule = [userDefaultManager valueForKey:@"module"];
    int value = checkModule.intValue;
    switch (value) {
        case 0:
            return @"Delete";
            break;
        case 1:
            return @"Delete";
            break;
        case 2:
            return @"Delete";
            break;
        
        default:
            break;
    }
    
    return @"";
}

- (void)tableView:(UITableView *)tableView commitEditingStyle:(UITableViewCellEditingStyle)editingStyle forRowAtIndexPath:(NSIndexPath *)indexPath { //implement the delegate method
    
    if (editingStyle == UITableViewCellEditingStyleDelete) {
        // Update data source array here, something like [array removeObjectAtIndex:indexPath.row];
        //[self.tableView deleteRowsAtIndexPaths:[NSArray arrayWithObject:indexPath] withRowAnimation:UITableViewRowAnimationFade];
        [self deleteSingleRecord:(int)indexPath.row];
        
    }
}

- (UITableViewCellEditingStyle)tableView:(UITableView *)tableView editingStyleForRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSString *checkModule = [userDefaultManager valueForKey:@"module"];
    int value = checkModule.intValue;
    switch (value) {
        case 0:
            return UITableViewCellEditingStyleDelete;
            break;
        case 1:
            return UITableViewCellEditingStyleNone;
            break;
        case 2:
            return UITableViewCellEditingStyleNone;
            break;
            
        default:
            break;
    }

    return UITableViewCellEditingStyleNone;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Show more button functionality
 * @Modified Date 14/12/2015
 */
#pragma mark Show More Button
-(IBAction)showMoreButton:(UIButton *)sender {
    contactIndex = sender.tag;
    SAFE_ARC_RELEASE(popover); popover=nil;
    
    //the controller we want to present as a popover
    DemoTableController *controller = [[DemoTableController alloc] initWithStyle:UITableViewStylePlain];
    controller.delegate1 = self;
    controller.checkString = @"contact";
    controller.checkFieldString = @"";
    popover = [[FPPopoverKeyboardResponsiveController alloc] initWithViewController:controller];
    popover.tint = FPPopoverDefaultTint;
    popover.keyboardHeight = _keyboardHeight;
    
    if(UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPad)
    {
        popover.contentSize = CGSizeMake(200, 200);
    }
    else {
        popover.contentSize = CGSizeMake(200, 130);
    }
    popover.arrowDirection = FPPopoverNoArrow;
    [popover presentPopoverFromPoint: CGPointMake(self.view.center.x, self.view.center.y - popover.contentSize.height/2)];
    popover.arrowDirection = FPPopoverArrowDirectionAny;
    [popover presentPopoverFromView:sender];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Option Select
 * @Modified Date 14/12/2015
 */
#pragma mark More Option Select
-(void)selectedTableRow:(NSUInteger)rowNum {
    [popover dismissPopoverAnimated:YES];
    NSDictionary *dict = [recordArray objectAtIndex:contactIndex];
    if ([self.delegateContactClass respondsToSelector:@selector(callReferralScreenForReferMe:)]) {
        [self.delegateContactClass callReferralScreenForReferMe:dict];
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
 * @Parm sender
 * @Description These method are used for Get the Different record
 * @Modified Date 27/07/2015
 */
#pragma mark Get Record For Different Module
-(IBAction)getRecordForDiffModule:(UIButton*)sender {
    
    recordArray = [[NSMutableArray alloc] init];
    
    [userDefaultManager setObject:@"" forKey:@"searchText"];
    [userDefaultManager setObject:@"" forKey:@"filter"];
    [userDefaultManager setObject:@"" forKey:@"filter_name"];
    [userDefaultManager setObject:@"" forKey:@"pre_Select"];
    
    switch (sender.tag) {
        case 0://For contact list
            if (!CheckDeviceFunction()) {
                [contantScroll setContentOffset:CGPointMake(0,0) animated:NO];
            }
            lblTopTitle.text = @"CONTACTS";
            [viewInviteFriend setHidden:YES];
            btnSelectAll.alpha = 1.0;
            btnSearchMore.alpha = 1.0;
            [btnSelectAll setUserInteractionEnabled:YES];
            [btnSearchMore setUserInteractionEnabled:YES];
            shortArray = shortArrayOne;
            [userDefaultManager setObject:@"0" forKey:@"module"];
            [userDefaultManager setObject:@"1" forKey:@"page_no"];
            [self contactList];
            [btnReceived setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
            [btnSent setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            break;
        case 1://For partner list
            if (!CheckDeviceFunction()) {
                [contantScroll setContentOffset:CGPointMake(0,0) animated:NO];
            }
            lblTopTitle.text = @"PARTNERS";
            [viewInviteFriend setHidden:YES];
            btnSelectAll.alpha = 0.5;
            btnSearchMore.alpha = 1.0;
            [btnSelectAll setUserInteractionEnabled:NO];
            [btnSearchMore setUserInteractionEnabled:YES];
            shortArray = shortArrayTwo;
            [userDefaultManager setObject:@"1" forKey:@"module"];
            [userDefaultManager setObject:@"1" forKey:@"page_no"];
            [self partnerList];
            [btnReceived setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnSent setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
            [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            break;
        case 2://For invite Partner
            showTextArray = [[NSMutableArray alloc] init];
            [viewInviteFriend setHidden:NO];
            [self hideTableAndNoRecordView];
            lblTopTitle.text = @"INVITE PARTNERS";
            btnSelectAll.alpha = 0.5;
            btnSearchMore.alpha = 0.5;
            [btnSelectAll setUserInteractionEnabled:NO];
            [btnSearchMore setUserInteractionEnabled:NO];
            [userDefaultManager setObject:@"2" forKey:@"module"];
            [userDefaultManager setObject:@"1" forKey:@"page_no"];
            [btnReceived setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnSent setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
            
            if (!CheckDeviceFunction()) {
                [contantScroll setContentOffset:CGPointMake(50,0) animated:NO];
            }
            
            arrayInvitePartner = [[NSMutableArray alloc] init];
            [self ExpendFormForInvitePartner:@"add"];
            
            break;
            
        default:
            break;
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Expend Form For Invite Partner
 * @Modified Date 01/10/2015
 */
#pragma mark Expend Form For Invite Partner
-(void)ExpendFormForInvitePartner:(NSString *)string {
    
    [viewPartnerNameEmail.subviews makeObjectsPerformSelector:@selector(removeFromSuperview)];
    hight = 0;
    int count = 0;
    if ([string isEqualToString:@"add"]) {
        count = (int)arrayInvitePartner.count+1;
    }
    else {
        count = (int)arrayInvitePartner.count;
    }
    
    addButton.alpha = 1.0f;
    [addButton setUserInteractionEnabled:YES];
    arrayInvitePartner = [[NSMutableArray alloc] init];
    
    for (int i = 0; i<count; i++) {
        
        if (i == 0) {
            
            addButton = [UIButton buttonWithType:UIButtonTypeCustom];
            [addButton setBackgroundImage:[UIImage imageNamed:@"add_Fields"] forState:UIControlStateNormal];
            [addButton addTarget:self action:@selector(addFieldInvitePartner) forControlEvents:UIControlEventTouchUpInside];
            [viewPartnerNameEmail addSubview:addButton];
            
                addButton.frame = CGRectMake(viewPartnerNameEmail.frame.size.width-40, hight, 30, 30);
                hight = hight+10;
        }
        else {
            UIButton *removeButton = [UIButton buttonWithType:UIButtonTypeCustom];
            [removeButton setBackgroundImage:[UIImage imageNamed:@"Delete_Attach"] forState:UIControlStateNormal];
            [removeButton setTag:i];
            [removeButton addTarget:self action:@selector(ColapsFormForInvitePartner:) forControlEvents:UIControlEventTouchUpInside];
            [viewPartnerNameEmail addSubview:removeButton];
            
            
            removeButton.frame = CGRectMake(viewPartnerNameEmail.frame.size.width-40, hight, 30, 30);
            hight = hight+10;
        }
        
        UILabel *lblPartnerNa = [[UILabel alloc] initWithFrame:CGRectMake(17, hight, viewPartnerNameEmail.frame.size.width-84, 20)];
        [lblPartnerNa setText:@"Partner Name"];
        [lblPartnerNa setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
        [lblPartnerNa setTextColor:[UIColor whiteColor]];
        [viewPartnerNameEmail addSubview:lblPartnerNa];
        
        hight = hight+25;
        
        UIImageView *imgViewNa = [[UIImageView alloc] initWithFrame:CGRectMake(10, hight, viewPartnerNameEmail.frame.size.width-20, 40)];
        [imgViewNa setBackgroundColor:blackCancelBTN];
        imgViewNa.layer.cornerRadius = 5.0f;
        [viewPartnerNameEmail addSubview:imgViewNa];
        
        NSString *partnerNameStr = @"";
        NSString *partnerEmailStr = @"";
        if (i<showTextArray.count && showTextArray.count) {
            NSDictionary *dict = [showTextArray objectAtIndex:i];
            UITextField *textFieldname = [dict valueForKey:@"textFieldNa"];
            UITextField *textFieldEmail = [dict valueForKey:@"textFieldEm"];
            partnerNameStr = textFieldname.text;
            if (CheckStringForNull(partnerNameStr)) {
                partnerNameStr = @"";
            }
            partnerEmailStr = textFieldEmail.text;
            if (CheckStringForNull(partnerEmailStr)) {
                partnerEmailStr = @"";
            }
        }
        
        UITextField *textFieldNa = [[UITextField alloc] initWithFrame:CGRectMake(17, imgViewNa.frame.origin.y+5, viewPartnerNameEmail.frame.size.width-34, 30)];
        [textFieldNa setPlaceholder:@"Partner Name"];
        [textFieldNa setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
        [textFieldNa setText:partnerNameStr];
        textFieldNa.delegate = self;
        [textFieldNa setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
        [textFieldNa setTextColor:[UIColor whiteColor]];
        [viewPartnerNameEmail addSubview:textFieldNa];
        
        hight = hight+50;
        
        UILabel *lblPartnerEm = [[UILabel alloc] initWithFrame:CGRectMake(17, hight, viewPartnerNameEmail.frame.size.width-34, 20)];
        [lblPartnerEm setText:@"Partner Email"];
        [lblPartnerEm setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
        [lblPartnerEm setTextColor:[UIColor whiteColor]];
        [viewPartnerNameEmail addSubview:lblPartnerEm];
        
        hight = hight+25;
        
        UIImageView *imgViewEm = [[UIImageView alloc] initWithFrame:CGRectMake(10, hight, viewPartnerNameEmail.frame.size.width-20, 40)];
        [imgViewEm setBackgroundColor:blackCancelBTN];
        imgViewEm.layer.cornerRadius = 5.0f;
        [viewPartnerNameEmail addSubview:imgViewEm];
        
        UITextField *textFieldEm = [[UITextField alloc] initWithFrame:CGRectMake(17, imgViewEm.frame.origin.y+5, viewPartnerNameEmail.frame.size.width-34, 30)];
        [textFieldEm setPlaceholder:@"Partner Email"];
        [textFieldEm setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
        [textFieldEm setText:partnerEmailStr];
        textFieldEm.delegate = self;
        [textFieldEm setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
        [textFieldEm setTextColor:[UIColor whiteColor]];
        [viewPartnerNameEmail addSubview:textFieldEm];
        
        UIImageView *sepratorImgView = [[UIImageView alloc] initWithFrame:CGRectMake(10, imgViewEm.frame.origin.y+imgViewEm.frame.size.height+15, viewPartnerNameEmail.frame.size.width-20, 2)];
        [sepratorImgView setImage:[UIImage imageNamed:@"sepratorLine"]];
        [viewPartnerNameEmail addSubview:sepratorImgView];
        
        hight = hight+60;
        
        viewPartnerNameEmail.frame = CGRectMake(viewPartnerNameEmail.frame.origin.x, viewPartnerNameEmail.frame.origin.y, viewPartnerNameEmail.frame.size.width, hight+20);
        
        viewCommentPartner.frame = CGRectMake(viewCommentPartner.frame.origin.x, viewPartnerNameEmail.frame.origin.y+viewPartnerNameEmail.frame.size.height, viewCommentPartner.frame.size.width, viewCommentPartner.frame.size.height);
        
        scrollInviteFriend.contentSize = CGSizeMake(scrollInviteFriend.frame.size.width, viewCommentPartner.frame.origin.y+viewCommentPartner.frame.size.height+10);
        
        [arrayInvitePartner addObject:[NSDictionary dictionaryWithObjectsAndKeys:textFieldNa,@"textFieldNa",imgViewNa,@"imgViewNa",textFieldEm,@"textFieldEm",imgViewEm,@"imgViewEm", nil]];
        
        if (arrayInvitePartner.count==10) {
            addButton.alpha = 0.5f;
            [addButton setUserInteractionEnabled:NO];
        }
        hight = hight+10;
    }
    
    showTextArray = [[NSMutableArray alloc] init];
    showTextArray = arrayInvitePartner;

}

//// return NO to disallow editing.
//- (BOOL)textFieldShouldBeginEditing:(UITextField *)textField {
//    [self.view endEditing:YES];
//    return YES;
//}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Colaps Form For Invite Partner
 * @Modified Date 01/10/2015
 */
#pragma mark Colaps Form For Invite Partner
-(void)ColapsFormForInvitePartner:(UIButton *)sender {
    [arrayInvitePartner removeObjectAtIndex:sender.tag];
    showTextArray = [[NSMutableArray alloc] init];
    showTextArray = arrayInvitePartner;
    [self ExpendFormForInvitePartner:@"remove"];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Send invitation to friend
 * @Modified Date 01/10/2015
 */
#pragma mark Send Invite Partner Request
-(IBAction)invitePartner:(id)sender {
    [self makeEmptyFieldRed];
    BOOL isFilled = [self checkAllFieldAreFilled];
    if (!isFilled) {
        return;
    }
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(invitePartnerDelay) withObject:nil afterDelay:0.1];
}
-(void)invitePartnerDelay {
    //For partner List
    NSMutableArray *sendPartnerArray = [[NSMutableArray alloc] init];
    for (int i = 0; i < showTextArray.count; i++) {
        
        NSDictionary *dict = [showTextArray objectAtIndex:i];
        UITextField *textFieldname = [dict valueForKey:@"textFieldNa"];
        UITextField *textFieldEmail = [dict valueForKey:@"textFieldEm"];
        NSString *partNameStr = textFieldname.text;
        if (CheckStringForNull(partNameStr)) {
            partNameStr = @"";
        }
        NSString *partEmailStr = textFieldEmail.text;
        if (CheckStringForNull(partEmailStr)) {
            partEmailStr = @"";
        }
        
        [sendPartnerArray addObject:[NSDictionary dictionaryWithObjectsAndKeys:partNameStr,@"name",partEmailStr,@"email", nil]];
    }
    
    //For Message
    NSString *messageString = [textViewPartnerComment text];
    
    if (isNetworkAvailable) {
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:sendPartnerArray,@"data",messageString,@"message",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
                     
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"invitePartners" controls:@"contacts" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            
            if (!CheckDeviceFunction()) {
                [contantScroll setContentOffset:CGPointMake(0,0) animated:NO];
            }
            lblTopTitle.text = @"PARTNERS";
            [viewInviteFriend setHidden:YES];
            btnSelectAll.alpha = 0.5;
            btnSearchMore.alpha = 1.0;
            [btnSelectAll setUserInteractionEnabled:NO];
            [btnSearchMore setUserInteractionEnabled:YES];
            shortArray = shortArrayTwo;
            [userDefaultManager setObject:@"1" forKey:@"module"];
            [userDefaultManager setObject:@"1" forKey:@"page_no"];
            [self partnerList];
            [btnReceived setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnSent setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
            [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];

            UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:[parsedJSONToken valueForKey:@"message"] delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
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
    
    for (int i = 0; i < showTextArray.count; i++) {
        
        NSDictionary *dict = [showTextArray objectAtIndex:i];
        UITextField *textFieldname = [dict valueForKey:@"textFieldNa"];
        UITextField *textFieldEmail = [dict valueForKey:@"textFieldEm"];

        // for First Name
        NSString *nameString = textFieldname.text;
        if (CheckStringForNull(nameString)) {
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            return NO;
        }
        if (nameString.length>20) {
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Partner name can have maximum 20 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            return NO;
        }
        nameString = [nameString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:partnerNameSpecial] invertedSet]];
        if (!CheckStringForNull(nameString)) {
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Partner name can contain period, space and hyphen only including alphabets." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            return NO;
        }
        
        //For Email
        NSString *emailString = textFieldEmail.text;
        if (CheckStringForNull(emailString)) {
            [[AppDelegate currentDelegate] removeLoading];
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            return NO;
        }
        BOOL email = validateEmail(emailString);
        if (!email && !CheckStringForNull(emailString)) {
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Enter valid email address." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            return NO;
        }
    }
    
    //For Message
    NSString *msgString = textViewPartnerComment.text;
    if (CheckStringForNull(msgString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (msgString.length>350) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Only 350 characters allowed." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
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
    
    for (int i = 0; i < showTextArray.count; i++) {
        
        NSDictionary *dict = [showTextArray objectAtIndex:i];
        UITextField *textFieldname = [dict valueForKey:@"textFieldNa"];
        UITextField *textFieldEmail = [dict valueForKey:@"textFieldEm"];
        UIImageView *imgViewNam = [dict valueForKey:@"imgViewNa"];
        UIImageView *imgViewEma = [dict valueForKey:@"imgViewEm"];
        
        //For First Name
        NSString *fNameString  = textFieldname.text;
        NSString *fString = [fNameString stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:partnerNameSpecial] invertedSet]];
        if (!CheckStringForNull(fString) || CheckStringForNull(fNameString) || fNameString.length>20) {
            [[ConnectionManager sharedManager] setBoarderColorRed:imgViewNam];
        } else {
            [[ConnectionManager sharedManager] setBoarderColorClear:imgViewNam];
        }
        
        //For Email
        BOOL email = validateEmail(textFieldEmail.text);
        if ((!email && !CheckStringForNull(textFieldEmail.text)) || CheckStringForNull(textFieldEmail.text)) {
            [[ConnectionManager sharedManager] setBoarderColorRed:imgViewEma];
        } else {
            [[ConnectionManager sharedManager] setBoarderColorClear:imgViewEma];
        }

    }
    
    //For Message
    NSString *msgString = textViewPartnerComment.text;
    if (CheckStringForNull(msgString) || msgString.length>350) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgCommentPartner];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgCommentPartner];
    }

    
    return YES;
}



/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Add Field Form For Invite Partner
 * @Modified Date 01/10/2015
 */
#pragma mark Add Field into Invite Partner
-(void)addFieldInvitePartner {
    [self ExpendFormForInvitePartner:@"add"];
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for select Row for delete
 * @Modified Date 23/07/2015
 */
#pragma mark Selected Row
-(void)btnSelectRow:(UIButton*)sender {
    
    int index = (int)sender.tag;
    BOOL selected = [[selectedRowForDeleteArray objectAtIndex:index] boolValue];
    [selectedRowForDeleteArray replaceObjectAtIndex:index withObject:[NSNumber numberWithBool:!selected]];
    [self.tableView reloadData];
    if ([selectedRowForDeleteArray containsObject:[NSNumber numberWithBool:YES]]) {
        [btnDelete setHidden:NO];
        [btnSelectAll setTag:0];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        if (![selectedRowForDeleteArray containsObject:[NSNumber numberWithBool:NO]]) {
            [btnSelectAll setImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
            [btnSelectAll setTag:1];
        }
    }
    else {
        [btnDelete setHidden:YES];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Search Functionality
 * @Modified Date 27/07/2015
 */
#pragma mark Search Btn
-(IBAction)btnSearch:(UIButton*)sender {
    
    if (sender.tag == 0){
        
        if (searchFilterViewBG != nil) {
            [searchFilterViewBG removeFromSuperview];
        }
        searchFilterViewBG = [[UIView alloc] initWithFrame:CGRectMake(0, 0, self.view.frame.size.width, self.view.frame.size.height)];
        searchFilterViewBG.backgroundColor = [UIColor colorWithWhite:0.0 alpha:0.0];
        searchFilterViewBG.opaque = NO;
        [self.view addSubview:searchFilterViewBG];
        
        int Height = 350;
        if (CheckDeviceFunction()) {
            Height = 500;
        }
        
        searchFilterView = [[UIView alloc] initWithFrame:CGRectMake(0,searchFilterViewBG.frame.size.height,searchFilterViewBG.frame.size.width, Height)];
        searchFilterView.backgroundColor = [UIColor whiteColor];
        [UIView beginAnimations:@"animateTableView" context:nil];
        [UIView setAnimationDuration:0.3];
        searchFilterView.frame = CGRectMake(0, searchFilterViewBG.frame.size.height-Height, searchFilterViewBG.frame.size.width, Height);
        [UIView commitAnimations];
        [searchFilterViewBG addSubview:searchFilterView];
        
        if (prefrenceToolBar) {
            [prefrenceToolBar removeFromSuperview];
            prefrenceToolBar=nil;
        }
        prefrenceToolBar = [[UIToolbar alloc] initWithFrame:CGRectMake(0,0,searchFilterViewBG.frame.size.width, 50)];
        prefrenceToolBar.barStyle = UIBarStyleBlack;
        prefrenceToolBar.translucent = YES;
        prefrenceToolBar.tintColor = nil;
        [searchFilterView addSubview:prefrenceToolBar];
        
        UIButton *selectFilterBTN = [UIButton buttonWithType:UIButtonTypeCustom];
        selectFilterBTN.frame = CGRectMake(0, 0, 70, 40);
        [selectFilterBTN setBackgroundColor:orangeColour];
        selectFilterBTN.layer.cornerRadius = 5;
        selectFilterBTN.clipsToBounds = YES;
        [selectFilterBTN setUserInteractionEnabled:YES];
        [selectFilterBTN.titleLabel setFont:[UIFont fontWithName:@"Graphik-Regular" size:17.0f]];
        [selectFilterBTN setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
        [selectFilterBTN setTitle:@"APPLY" forState:UIControlStateNormal];
        [selectFilterBTN addTarget:self action:@selector(doneSearch) forControlEvents:UIControlEventTouchUpInside];
        selectFilterBTN.showsTouchWhenHighlighted = YES;
        UIBarButtonItem* doneButton = [[UIBarButtonItem alloc] initWithCustomView:selectFilterBTN];
        
        UIBarButtonItem *EportSpaceBetween = [[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemFlexibleSpace target:nil action:nil];
        
        UIButton *cancelFilterBTN = [UIButton buttonWithType:UIButtonTypeCustom];
        cancelFilterBTN.frame = CGRectMake(0, 0, 80, 40);
        [cancelFilterBTN setBackgroundColor:[UIColor clearColor]];
        [cancelFilterBTN setBackgroundColor:[UIColor colorWithRed:0.8901960784 green:0.8980392157 blue:0.9137254902 alpha:1]];
        cancelFilterBTN.layer.cornerRadius = 5;
        cancelFilterBTN.clipsToBounds = YES;
        [cancelFilterBTN setUserInteractionEnabled:YES];
        [cancelFilterBTN setBackgroundColor:blackCancelBTN];
        [cancelFilterBTN.titleLabel setFont:[UIFont fontWithName:@"Graphik-Regular" size:17.0f]];
        [cancelFilterBTN setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
        [cancelFilterBTN setTitle:@"CANCEL" forState:UIControlStateNormal];
        [cancelFilterBTN addTarget:self action:@selector(cancelSearch) forControlEvents:UIControlEventTouchUpInside];
        cancelFilterBTN.showsTouchWhenHighlighted = YES;
        UIBarButtonItem* cancelButton = [[UIBarButtonItem alloc] initWithCustomView:cancelFilterBTN];
        
        [prefrenceToolBar setItems:[NSArray arrayWithObjects:doneButton,EportSpaceBetween,cancelButton, nil]];
        
        //For Scroll View
        scrollView = [[TPKeyboardAvoidingScrollView alloc] initWithFrame:CGRectMake(0, 50, searchFilterView.frame.size.width, searchFilterView.frame.size.height-50)];
        [scrollView setBackgroundColor:[UIColor clearColor]];
        [searchFilterView addSubview:scrollView];
        
        //For Search Field
        UIImageView *imgTextBG = [[UIImageView alloc] initWithFrame:CGRectMake(20, 10, scrollView.frame.size.width-40, 40)];
        [imgTextBG setBackgroundColor:blackCancelBTN];
        imgTextBG.layer.cornerRadius = 5.0f;
        [scrollView addSubview:imgTextBG];
        
        textSearch = [[UITextField alloc] initWithFrame:CGRectMake(25, 15, scrollView.frame.size.width-80, 30)];
        textSearch.backgroundColor = [UIColor clearColor];
        [textSearch setTextAlignment:NSTextAlignmentLeft];
        [textSearch setTextColor:[UIColor whiteColor]];
        [textSearch setPlaceholder:@"Search"];
        [textSearch setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
        [scrollView addSubview:textSearch];
        
        UIButton *cancelSearchBTn = [UIButton buttonWithType:UIButtonTypeCustom];
        cancelSearchBTn.frame = CGRectMake(textSearch.frame.origin.x+textSearch.frame.size.width, 15, 30, 30);
        [cancelSearchBTn setBackgroundImage:[UIImage imageNamed:@"Delete_Attach.png"] forState:UIControlStateNormal];
        [cancelSearchBTn addTarget:self action:@selector(clearSearchField) forControlEvents:UIControlEventTouchUpInside];
        [scrollView addSubview:cancelSearchBTn];
        
        NSString *referralNameStr = [userDefaultManager valueForKey:@"searchText"];
        if (CheckStringForNull(referralNameStr)) {
            referralNameStr = @"";
            [cancelSearchBTn setHidden:YES];
        }
        [textSearch setText:referralNameStr];
        
        
        int Y = 60;
        addControlArray = [[NSMutableArray alloc] init];
        NSString *selectOptStr = [userDefaultManager valueForKey:@"pre_Select"];
        if (CheckStringForNull(selectOptStr)) {
            selectOptStr = @"";
        }
        
        for (int i = 0; i<shortArray.count; i++) {
            
            NSString *valueString = [[shortArray objectAtIndex:i] valueForKey:@"value"];
            
            UIImageView *imgSelect = [[UIImageView alloc] initWithFrame:CGRectMake(20, Y, 30, 30)];
            [imgSelect setBackgroundColor:[UIColor clearColor]];
            [imgSelect setImage:self.unSelectedImage];
            imgSelect.layer.cornerRadius = 15.0f;
            imgSelect.tag = i;
            imgSelect.clipsToBounds = YES;
            [scrollView addSubview:imgSelect];
            
            if ([selectOptStr isEqualToString:valueString]) {
                [imgSelect setImage:self.selectedImage];
            }
            else{
                if (CheckStringForNull(selectOptStr)) {
                    if (i == 0) {
                        [imgSelect setImage:self.selectedImage];
                    }
                }
            }
            
            UILabel *label = [[UILabel alloc] initWithFrame:CGRectMake(60, Y, scrollView.frame.size.width-70, 30)];
            label.text = valueString;
            label.textAlignment = NSTextAlignmentLeft;
            label.textColor = [UIColor blackColor];
            [label setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
            [scrollView addSubview:label];
            
            UIButton *btnSelect = [UIButton buttonWithType:UIButtonTypeCustom];
            btnSelect.frame = CGRectMake(20, Y, scrollView.frame.size.width-40, 30);
            btnSelect.tag = i;
            [btnSelect setBackgroundColor:[UIColor clearColor]];
            [btnSelect addTarget:self action:@selector(selectFilter:) forControlEvents:UIControlEventTouchUpInside];
            [scrollView addSubview:btnSelect];
            
            [addControlArray addObject:[NSDictionary dictionaryWithObjectsAndKeys:imgSelect,@"image",label,@"filter", nil]];
            
            Y = Y+40;
        }
        
        scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, Y+30);
        
        //Heare we set the selected image for filter
        [btnSearchMore setImage:[UIImage imageNamed:@"Search_unSelect.png"] forState:UIControlStateNormal];
        [sender setTag:1];
    }
    else {
        [searchFilterViewBG removeFromSuperview];
        [btnSearchMore setImage:[UIImage imageNamed:@"Search_unSelect.png"] forState:UIControlStateNormal];
        [sender setTag:0];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Clear search Field
 * @Modified Date 11/08/2015
 */
#pragma mark Clear Search Field
-(void)clearSearchField {
    [self.view endEditing:YES];
    [textSearch setText:@""];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Select Filter
 * @Modified Date 10/08/2015
 */
#pragma mark Select Filter
-(void)selectFilter:(UIButton*)sender {
    filterStr = @"";
    filterNameStr = @"";
    preSelectStr = @"";
    for (int i = 0; i<addControlArray.count; i++) {
        UIImageView *imageView = [[addControlArray objectAtIndex:i] valueForKey:@"image"];
        if (sender.tag == i) {
            if (self.selectedImage== imageView.image) {
                [imageView setImage:self.unSelectedImage];
                [userDefaultManager setObject:filterStr forKey:@"filter"];
                [userDefaultManager setObject:filterNameStr forKey:@"filter_name"];
                [userDefaultManager setObject:preSelectStr forKey:@"pre_Select"];
                UIImageView *imageView1 = [[addControlArray objectAtIndex:0] valueForKey:@"image"];
                [imageView1 setImage:self.selectedImage];
            }
            else {
                
                UILabel *lbl = [[addControlArray objectAtIndex:i] valueForKey:@"filter"];
                
                //For Contact And Partner
                if ([lbl.text isEqualToString:@"Created Date (Newest First)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"created";
                    preSelectStr = @"Created Date (Newest First)";
                }
                else if ([lbl.text isEqualToString:@"Created Date (Oldest First)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"created";
                    preSelectStr = @"Created Date (Oldest First)";
                }
                //For Contact
                else if ([lbl.text isEqualToString:@"Contact name (A to Z)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"first_name";
                    preSelectStr = @"Contact name (A to Z)";
                }
                else if ([lbl.text isEqualToString:@"Contact name (Z to A)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"first_name";
                    preSelectStr = @"Contact name (Z to A)";
                }
                else if ([lbl.text isEqualToString:@"Profession (A to Z)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"job_title";
                    preSelectStr = @"Profession (A to Z)";
                }
                else if ([lbl.text isEqualToString:@"Profession (Z to A)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"job_title";
                    preSelectStr = @"Profession (Z to A)";
                }
                else if ([lbl.text isEqualToString:@"Email (A to Z)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"email";
                    preSelectStr = @"Email (A to Z)";
                }
                else if ([lbl.text isEqualToString:@"Email (Z to A)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"email";
                    preSelectStr = @"Email (Z to A)";
                }
                //For Partner
                else if ([lbl.text isEqualToString:@"Sent On (Newest First)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"created";
                    preSelectStr = @"Sent On (Newest First)";
                }
                else if ([lbl.text isEqualToString:@"Sent On (Oldest First)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"created";
                    preSelectStr = @"Sent On (Oldest First)";
                }

                else if ([lbl.text isEqualToString:@"Partner Email (A to Z)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"invitee_email";
                    preSelectStr = @"Partner Email (A to Z)";
                }
                else if ([lbl.text isEqualToString:@"Partner Email (Z to A)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"invitee_email";
                    preSelectStr = @"Partner Email (Z to A)";
                }
                else if ([lbl.text isEqualToString:@"Partner Name (A to Z)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"invitee_name";
                    preSelectStr = @"Partner Name (A to Z)";
                }
                else if ([lbl.text isEqualToString:@"Partner Name (Z to A)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"invitee_name";
                    preSelectStr = @"Partner Name (Z to A)";
                }
                else if ([lbl.text isEqualToString:@"Referral Amount (Low to High)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"referral_amount";
                    preSelectStr = @"Referral Amount (Low to High)";
                }
                else if ([lbl.text isEqualToString:@"Referral Amount (High to Low)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"referral_amount";
                    preSelectStr = @"Referral Amount (High to Low)";
                }
                else if ([lbl.text isEqualToString:@"Status (A to Z)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"status";
                    preSelectStr = @"Status (A to Z)";
                }
                else if ([lbl.text isEqualToString:@"Status (Z to A)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"status";
                    preSelectStr = @"Status (Z to A)";
                }
                
                [imageView setImage:self.selectedImage];
            }
        }
        else {
            [imageView setImage:self.unSelectedImage];
        }
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Filter Done Button
 * @Modified Date 10/08/2015
 */
#pragma mark Filter Done
-(void)doneSearch {
    recordArray = [[NSMutableArray alloc] init];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    NSString *referralNameStr = textSearch.text;
    [userDefaultManager setObject:referralNameStr forKey:@"searchText"];
    [userDefaultManager setObject:filterStr forKey:@"filter"];
    [userDefaultManager setObject:filterNameStr forKey:@"filter_name"];
    [userDefaultManager setObject:preSelectStr forKey:@"pre_Select"];
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(doneSearchDelay) withObject:nil afterDelay:0.1];
    [self performSelector:@selector(tableRefresh) withObject:nil afterDelay:0.2];
}
-(void)tableRefresh {
    if (recordArray.count) {
        [self.tableView setContentOffset:CGPointZero animated:YES];
    }
}
#pragma mark Filter Done Load More
-(void)doneSearchLoadMore {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(doneSearchDelay) withObject:nil afterDelay:0.1];
}
-(void)doneSearchDelay {
    
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        
        NSString *referralNameStr = [userDefaultManager valueForKey:@"searchText"];
        NSString *shortDesStr = [userDefaultManager valueForKey:@"filter"];
        NSString *shortDataStr = [userDefaultManager valueForKey:@"filter_name"];
        NSString *methodString = @"";
        NSString *controlString = @"";
        
        NSString *checkModule = [userDefaultManager valueForKey:@"module"];
        int value = checkModule.intValue;
        switch (value) {
            case 0:
                methodString = @"getContactList";
                controlString = @"contacts";
                if (CheckStringForNull(referralNameStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                    [self cancelSearch];
                    [self contactListDelay];
                    return;
                }
                break;
            case 1:
                methodString = @"getPartnersList";
                controlString = @"contacts";
                if (CheckStringForNull(referralNameStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                    [self cancelSearch];
                    [self partnerListDelay];
                    return;
                }
                break;
            
            default:
                break;
        }
        
        //For login
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:pageCountStr,@"page_no",recordPerPage,@"record_per_page",referralNameStr,@"search_filter",shortDataStr,@"sort_data",shortDesStr,@"sort_direction",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:methodString controls:controlString httpMethod:@"POST" data:data];
        
        [self hideTableOnSearch];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = @"";
            if ([methodString isEqualToString:@"getContactList"]) {
                totalRecString = [parsedJSONToken valueForKey:@"totalContacts"];
            }
            else {
                totalRecString = [parsedJSONToken valueForKey:@"totalPartners"];
            }
            
            [userDefaultManager setObject:pageNoString forKey:@"page_no"];
            [userDefaultManager setObject:totalRecString forKey:@"totalRecord"];
            NSArray *array = [parsedJSONToken valueForKey:@"result"];
            for (NSDictionary *dictionary in array){
                [recordArray addObject:dictionary];
            }
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [self cancelSearch];
    [btnDelete setHidden:YES];
    [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
    [btnSelectAll setTag:0];
    [self populateUnSelected];
    [self.tableView reloadData];
    [[AppDelegate currentDelegate] removeLoading];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Filter Cancel Button
 * @Modified Date 10/08/2015
 */
#pragma mark Filter Cancel
-(void)cancelSearch {
    [searchFilterViewBG removeFromSuperview];
    [btnSearchMore setImage:[UIImage imageNamed:@"Search_unSelect.png"] forState:UIControlStateNormal];
    [btnSearchMore setTag:0];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Add Partners
 * @Modified Date 27/07/2015
 */
#pragma mark Add Partners
-(IBAction)btnAddPartner:(UIButton*)sender {
    [self performSegueWithIdentifier:@"addContact" sender:nil];
}


/*
 * @Auther Deepak chauhan
 * @Parm alertView , buttonIndex
 * @Description This method are Called when a button is clicked. The view will be automatically dismissed after this call returns
 * @Modified Date 30/07/2015
 */
#pragma mark Alert Delegate
- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex{
    if (alertView.tag == 100) {
        if (buttonIndex == 0) {
            [[AppDelegate currentDelegate] addLoading];
            [self performSelector:@selector(deleteRecordDelay) withObject:nil afterDelay:0.1];
        }
        else {
            [self.tableView setEditing:NO animated:YES];
        }
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Delete Recorde
 * @Modified Date 27/07/2015
 */
#pragma mark Delete Multiple Record
-(IBAction)deleteMultipleRecord {
    
    deleteIdString = @"";
    for (int i = 0; i<recordArray.count; i++) {
        BOOL selected = [[selectedRowForDeleteArray objectAtIndex:i] boolValue];
        if (selected) {
            if (CheckStringForNull(deleteIdString)) {
                deleteIdString = [[recordArray objectAtIndex:i] valueForKey:@"id"];
            }
            else {
                NSString *idString = [NSString stringWithFormat:@",%@",[[recordArray objectAtIndex:i] valueForKey:@"id"]];
                deleteIdString = [deleteIdString stringByAppendingString:idString];
            }
        }
    }
    
    NSString *confermationMessage = @"";
    NSString *checkModule = [userDefaultManager valueForKey:@"module"];
    int value = checkModule.intValue;
    switch (value) {
        case 0:
            confermationMessage = @"Do you want to permanently delete the contact(s)?";
            break;
        case 1:
            confermationMessage = @"Do you want to permanently delete the partner(s)?";
            break;
            
        default:
            break;
    }
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:confermationMessage delegate:self cancelButtonTitle:nil otherButtonTitles:@"OK",@"Cancel", nil];
    alert.tag = 100;
    [alert show];
}


#pragma mark Delete Single Record
-(void)deleteSingleRecord:(int)index {
    
    deleteIdString = @"";
    deleteIdString = [[recordArray objectAtIndex:index] valueForKey:@"id"];
    NSString *confermationMessage = @"";
    NSString *checkModule = [userDefaultManager valueForKey:@"module"];
    int value = checkModule.intValue;
    switch (value) {
        case 0:
            confermationMessage = @"Do you want to permanently delete the contact(s)?";
            break;
        case 1:
            confermationMessage = @"Do you want to permanently delete the partner(s)?";
            break;
            
        default:
            break;
    }
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:confermationMessage delegate:self cancelButtonTitle:nil otherButtonTitles:@"OK",@"Cancel", nil];
    alert.tag = 100;
    [alert show];
}
-(void)deleteRecordDelay {
    
    NSString *checkModule = [userDefaultManager valueForKey:@"module"];
    if (isNetworkAvailable) {
        //For delete Contact
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:deleteIdString,@"deleteId",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"deleteContact" controls:@"contacts" httpMethod:@"POST" data:data];
        
        [self hideTable];
        
        if (parsedJSONToken != nil) {
            
            [self showTable];
            
            NSString *messageString = [parsedJSONToken valueForKey:@"message"];
            
            UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:messageString delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [networkAlert show];
        }
        else {
            [[AppDelegate currentDelegate] removeLoading];
            return;
        }
    }
    else {
        [[AppDelegate currentDelegate] removeLoading];
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
        return;
    }
    
    recordArray = [[NSMutableArray alloc] init];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    [btnDelete setHidden:YES];
    [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
    [btnSelectAll setTag:0];
    
    NSString *searchStr =[userDefaultManager valueForKey:@"searchText"];
    NSString *shortDesStr = [userDefaultManager valueForKey:@"filter"];
    NSString *shortDataStr = [userDefaultManager valueForKey:@"filter_name"];
    int value1 = checkModule.intValue;
    switch (value1) {
        case 0:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self contactList];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 1:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self partnerList];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
            
        default:
            break;
    }
    
    [self populateUnSelected];
    [self.tableView reloadData];
    [[AppDelegate currentDelegate] removeLoading];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Select all record
 * @Modified Date 27/07/2015
 */
#pragma mark Select All Record
-(IBAction)btnSelectAll:(UIButton*)sender {
    if ([recordArray count]) {
        switch (sender.tag) {
            case 0:
                [btnDelete setHidden:NO];
                [btnSelectAll setImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
                [self populateSelected];
                [self.tableView reloadData];
                [sender setTag:1];
                break;
            case 1:
                [btnDelete setHidden:YES];
                [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
                [self populateUnSelected];
                [self.tableView reloadData];
                [sender setTag:0];
                break;
            default:
                break;
        }
    }
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Redirewct Home Screen
 * @Modified Date 27/07/2015
 */
#pragma mark Home Button
-(IBAction)btnHome:(UIButton*)sender {
    switch (sender.tag) {
        case 0:
            if ([self.delegateContactClass respondsToSelector:@selector(callHomeScreen)]) {
                [self.delegateContactClass callHomeScreen];
            }
            break;
        default:
            break;
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for insert bool value in array according to list
 * @Modified Date 27/07/2015
 */
#pragma mark populate UnSelected
- (void)populateUnSelected {
    NSMutableArray *array = [[NSMutableArray alloc] initWithCapacity:[recordArray count]];
    for (int i=0; i < [recordArray count]; i++)
        [array addObject:[NSNumber numberWithBool:NO]];
    selectedRowForDeleteArray = array;
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for insert bool value in array according to list
 * @Modified Date 23/07/2015
 */
#pragma mark populate Selected
- (void)populateSelected {
    NSMutableArray *array = [[NSMutableArray alloc] initWithCapacity:[recordArray count]];
    for (int i=0; i < [recordArray count]; i++)
        [array addObject:[NSNumber numberWithBool:YES]];
    selectedRowForDeleteArray = array;
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Update Contact After Add Contact from ContactDetailViewController class.
 * @Modified Date 05/10/2015
 */
#pragma mark Update Contact After Edit Contact
-(void)updateContactAfterEditContact {
    
    recordArray = [[NSMutableArray alloc] init];
    if (!CheckDeviceFunction()) {
        [contantScroll setContentOffset:CGPointMake(0,0) animated:NO];
    }
    lblTopTitle.text = @"CONTACTS";
    [viewInviteFriend setHidden:YES];
    btnSelectAll.alpha = 1.0;
    btnSearchMore.alpha = 1.0;
    [btnSelectAll setUserInteractionEnabled:YES];
    [btnSearchMore setUserInteractionEnabled:YES];
    shortArray = shortArrayOne;
    [userDefaultManager setObject:@"0" forKey:@"module"];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    //For Update Contact
    NSString *searchStr =[userDefaultManager valueForKey:@"searchText"];
    NSString *shortDesStr = [userDefaultManager valueForKey:@"filter"];
    NSString *shortDataStr = [userDefaultManager valueForKey:@"filter_name"];
    if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
        [self contactList];
    }
    else {
        [self doneSearchLoadMore];
    }
    [btnReceived setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
    [btnSent setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
    [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
}



/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Update Contact After Add Contact from AddPartnerViewController class.
 * @Modified Date 05/10/2015
 */
#pragma mark Update Contact After Add Contact
-(void)updateContactAfterAddContact {
    
    recordArray = [[NSMutableArray alloc] init];
    if (!CheckDeviceFunction()) {
        [contantScroll setContentOffset:CGPointMake(0,0) animated:NO];
    }
    lblTopTitle.text = @"CONTACTS";
    [viewInviteFriend setHidden:YES];
    btnSelectAll.alpha = 1.0;
    btnSearchMore.alpha = 1.0;
    [btnSelectAll setUserInteractionEnabled:YES];
    [btnSearchMore setUserInteractionEnabled:YES];
    shortArray = shortArrayOne;
    [userDefaultManager setObject:@"0" forKey:@"module"];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    //For Update Contact
    NSString *searchStr =[userDefaultManager valueForKey:@"searchText"];
    NSString *shortDesStr = [userDefaultManager valueForKey:@"filter"];
    NSString *shortDataStr = [userDefaultManager valueForKey:@"filter_name"];
    if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
        [self contactList];
    }
    else {
        [self doneSearchLoadMore];
    }
    [btnReceived setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
    [btnSent setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
    [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
}


#pragma mark - Navigation
// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    
    if ([segue.identifier isEqualToString:@"contact_Detail"]) {
        ContactDetailViewController *contactDetailViewController = segue.destinationViewController;
        contactDetailViewController.contactIDStr = contactIDStr;
        contactDetailViewController.delegateEditContact = self;
    }
    if ([segue.identifier isEqualToString:@"addContact"]) {
        AddPartnersViewController *addPartnersViewController = segue.destinationViewController;
        addPartnersViewController.delegateAddPartner = self;
        addPartnersViewController.checkAddOrEdit = @"add";
    }
}



@end

