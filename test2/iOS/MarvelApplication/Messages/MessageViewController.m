
//  ReferralViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/20/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "MessageViewController.h"
#import "MessageCell.h"
#import "UITableView+DragLoad.h"
#import "MessageDetailViewController.h"

@interface MessageViewController () <UITableViewDragLoadDelegate>
@property (nonatomic) IBOutlet UIButton* revealButtonItem;
@end

@implementation MessageViewController
@synthesize delegateMessageClass;
@synthesize lblNotiCount;

- (void)viewDidLoad {
    
    self.lblNotiCount.layer.cornerRadius = 5.0f;
    self.lblNotiCount.clipsToBounds = YES;
    
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
    
    contantScroll.contentSize = CGSizeMake(530, 50);
    self.tableView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    
    [self.tableView setDragDelegate:self refreshDatePermanentKey:@"FriendList"];
    self.tableView.showLoadMoreView = YES;
    
    //self.scrollView.contentSize = CGSizeMake(640, self.scrollView.frame.size.height);
    
    [userDefaultManager setObject:@"0" forKey:@"module"];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    [self inboxMessage];
    
    shortArrayOne = [NSArray arrayWithObjects:@{@"value":@"Date (Newest First)"},
                     @{@"value":@"Date (Oldest First)"},
                     @{@"value":@"From (A to Z)"},
                     @{@"value":@"From (Z to A)"},
                     @{@"value":@"Subject (A to Z)"},
                     @{@"value":@"Subject (Z to A)"},nil];
    
    shortArrayTwo = [NSArray arrayWithObjects:@{@"value":@"Date (Newest First)"},
                     @{@"value":@"Date (Oldest First)"},
                     @{@"value":@"Subject (A to Z)"},
                     @{@"value":@"Subject (Z to A)"},nil];
    
    shortArray = shortArrayOne;
    
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
}


/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Inbox Messages
 * @Modified Date 09/09/2015
 */
#pragma mark Inbox Messages
- (void)inboxMessage {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(inboxMessageDelay) withObject:nil afterDelay:0.1];
}
-(void)inboxMessageDelay {
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:pageCountStr,@"page_no",recordPerPage,@"record_per_page",@"inbox",@"list_page",@"",@"search_filter",@"",@"sort_data",@"",@"sort_direction",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getMessage" controls:@"messages" httpMethod:@"POST" data:data];
        
        [self hideTable];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalMessages"];
            [userDefaultManager setObject:pageNoString forKey:@"page_no"];
            [userDefaultManager setObject:totalRecString forKey:@"totalRecord"];
            NSArray *array = [parsedJSONToken valueForKey:@"result"];
            for (NSDictionary *dictionary in array){
                [recordArray addObject:dictionary];
            }
        }
        [btnDelete setHidden:YES];
        [btnReadUnRead setHidden:YES];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
    }
    else {
        [btnDelete setHidden:YES];
        [btnReadUnRead setHidden:YES];
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
 * @Description These method are used for Send Messages
 * @Modified Date 10/09/2015
 */
#pragma mark Send Messages
- (void)sendMessage {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(sendMessageDelay) withObject:nil afterDelay:0.1];
}
- (void)sendMessageDelay {
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        //For login
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:pageCountStr,@"page_no",recordPerPage,@"record_per_page",@"sent",@"list_page",@"",@"search_filter",@"",@"sort_data",@"",@"sort_direction",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getMessage" controls:@"messages" httpMethod:@"POST" data:data];
        
        [self hideTable];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalMessages"];
            [userDefaultManager setObject:pageNoString forKey:@"page_no"];
            [userDefaultManager setObject:totalRecString forKey:@"totalRecord"];
            NSArray *array = [parsedJSONToken valueForKey:@"result"];
            for (NSDictionary *dictionary in array){
                [recordArray addObject:dictionary];
            }
        }
        [btnDelete setHidden:YES];
        [btnReadUnRead setHidden:YES];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
    }
    else {
        [btnDelete setHidden:YES];
        [btnReadUnRead setHidden:YES];
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
 * @Description These method are used for Inbox Archive Messages
 * @Modified Date 10/09/2015
 */
#pragma mark Inbox Archive Messages
- (void)inboxArchiveMessage {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(inboxArchiveMessageDelay) withObject:nil afterDelay:0.1];
}
- (void)inboxArchiveMessageDelay {
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        //For login
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:pageCountStr,@"page_no",recordPerPage,@"record_per_page",@"inboxArchive",@"list_page",@"",@"search_filter",@"",@"sort_data",@"",@"sort_direction",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getMessage" controls:@"messages" httpMethod:@"POST" data:data];
        
        [self hideTable];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalMessages"];
            [userDefaultManager setObject:pageNoString forKey:@"page_no"];
            [userDefaultManager setObject:totalRecString forKey:@"totalRecord"];
            NSArray *array = [parsedJSONToken valueForKey:@"result"];
            for (NSDictionary *dictionary in array){
                [recordArray addObject:dictionary];
            }
        }
        [btnDelete setHidden:YES];
        [btnReadUnRead setHidden:YES];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
    }
    else {
        [btnDelete setHidden:YES];
        [btnReadUnRead setHidden:YES];
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
 * @Description These method are used for Send Archive messages
 * @Modified Date 10/09/2015
 */
#pragma mark Send Archive messages
- (void)sendArchiveMessage {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(sendArchiveMessageDelay) withObject:nil afterDelay:0.1];
}
- (void)sendArchiveMessageDelay {
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        //For login
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:pageCountStr,@"page_no",recordPerPage,@"record_per_page",@"sentArchive",@"list_page",@"",@"search_filter",@"",@"sort_data",@"",@"sort_direction",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getMessage" controls:@"messages" httpMethod:@"POST" data:data];
        
        [self hideTable];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalMessages"];
            [userDefaultManager setObject:pageNoString forKey:@"page_no"];
            [userDefaultManager setObject:totalRecString forKey:@"totalRecord"];
            NSArray *array = [parsedJSONToken valueForKey:@"result"];
            for (NSDictionary *dictionary in array){
                [recordArray addObject:dictionary];
            }
        }
        [btnDelete setHidden:YES];
        [btnReadUnRead setHidden:YES];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
    }
    else {
        [btnDelete setHidden:YES];
        [btnReadUnRead setHidden:YES];
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
                        [self inboxMessage];
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
                        [self sendMessage];
                    }
                    else {
                        [self doneSearchLoadMore];
                    }
                }
                break;
            case 2:
                if (recordArray.count < totalRecString.intValue){
                    int pageCount = pageString.intValue;
                    pageCount = pageCount+1;
                    NSString *pageCountStr = [NSString stringWithFormat:@"%d",pageCount];
                    [userDefaultManager setObject:pageCountStr forKey:@"page_no"];
                    
                    NSString *searchStr =[userDefaultManager valueForKey:@"searchText"];
                    NSString *shortDesStr = [userDefaultManager valueForKey:@"filter"];
                    NSString *shortDataStr = [userDefaultManager valueForKey:@"filter_name"];
                    if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                        [self inboxArchiveMessage];
                    }
                    else {
                        [self doneSearchLoadMore];
                    }
                }
                break;
            case 3:
                if (recordArray.count < totalRecString.intValue){
                    int pageCount = pageString.intValue;
                    pageCount = pageCount+1;
                    NSString *pageCountStr = [NSString stringWithFormat:@"%d",pageCount];
                    [userDefaultManager setObject:pageCountStr forKey:@"page_no"];
                    
                    NSString *searchStr =[userDefaultManager valueForKey:@"searchText"];
                    NSString *shortDesStr = [userDefaultManager valueForKey:@"filter"];
                    NSString *shortDataStr = [userDefaultManager valueForKey:@"filter_name"];
                    if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                        [self sendArchiveMessage];
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
    
    static NSString *PlaceholderCellIdentifier = @"MessageCell";
    
    MessageCell*cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
    cell.backgroundColor = [UIColor clearColor];
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
    if (cell == nil) {
        cell = [[MessageCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
    }
    if (recordArray.count) {
        NSDictionary *dict = [recordArray objectAtIndex:indexPath.row];
        
        NSString *subjectStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"subject"]];
        subjectStr = [subjectStr stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                         withString:[[subjectStr substringToIndex:1] capitalizedString]];
        cell.lblTitle.text = subjectStr;
        
        //For check read / unread status
        NSString *readStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"is_read"]];
        if ([readStr isEqualToString:@"0"]) {
            [cell.lblTitle setFont:[UIFont fontWithName:@"Graphik-Bold" size:15.0]];
        }
        else {
            [cell.lblTitle setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0]];
        }
        
        //For check attachment status
        NSString *attachStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"attachment"]];
        if ([attachStr isEqualToString:@"0"]) {
            [cell.attachImg setHidden:YES];
        }
        else {
            [cell.attachImg setHidden:NO];
        }
        
        NSString *checkModule = [userDefaultManager valueForKey:@"module"];
        BOOL flag;
        int value = checkModule.intValue;
        switch (value) {
            case 0:
                flag = true;
                break;
            case 1:
                flag = false;
                break;
            case 2:
                flag = true;
                break;
            case 3:
                flag = false;
                break;
                
            default:
                break;
        }
        
        NSString *fromToStr  = @"From";
        if (!flag) {
            NSString *nameStr = [NSString stringWithFormat:@"To %@",[dict valueForKey:@"recipient_users"]];
            cell.lblUserName.text  = nameStr;
        }
        else {
            NSString *fNam = [dict valueForKey:@"fname"];
            if (CheckStringForNull(fNam)) {
                fNam = @"";
            }
            NSString *fromFirstNameStr = [NSString stringWithFormat:@"%@ %@",fromToStr,[fNam capitalizedString]];
            NSString *lNam = [dict valueForKey:@"lname"];
            if (CheckStringForNull(lNam)) {
                lNam = @"";
            }
            NSString *fromLastNameStr = [NSString stringWithFormat:@" %@",[lNam capitalizedString]];
            fromFirstNameStr = [fromFirstNameStr stringByAppendingString:fromLastNameStr];
            cell.lblUserName.text  = fromFirstNameStr;
        }
        
        cell.lblDate.text = ConvertDateStringFormat([dict valueForKey:@"created"]) ;
        
        [cell.btnSelectRow setTag:indexPath.row];
        [cell.btnSelectRow addTarget:self action:@selector(btnSelectRow:) forControlEvents:UIControlEventTouchUpInside];
        NSNumber *selected = [selectedRowForDeleteArray objectAtIndex:[indexPath row]];
        UIImage *image = ([selected boolValue]) ? self.selectedImage : self.unSelectedImage;
        [cell.btnSelectRow setBackgroundImage:image forState:UIControlStateNormal];

    }
    return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    NSDictionary *dictReco = [recordArray objectAtIndex:indexPath.row];
    messageIDStr = [dictReco valueForKey:@"id"];
    [self performSegueWithIdentifier:@"detailMessage" sender:nil];
    saveIndex = (int)indexPath.row;
}

- (CGFloat)tableView:(UITableView *)aTableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    return 60;
}

-(NSString *)tableView:(UITableView *)tableView titleForDeleteConfirmationButtonForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    NSString *checkModule = [userDefaultManager valueForKey:@"module"];
    int value = checkModule.intValue;
    switch (value) {
        case 0:
            return @"Archive";
            break;
        case 1:
            return @"Archive";
            break;
        case 2:
            return @"Delete";
            break;
        case 3:
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
        [self deleteSingleRecord:indexPath.row];
        
    }
}

- (UITableViewCellEditingStyle)tableView:(UITableView *)tableView editingStyleForRowAtIndexPath:(NSIndexPath *)indexPath
{
    return UITableViewCellEditingStyleDelete;
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
        case 0://For Receiver record
            shortArray = shortArrayOne;
            [userDefaultManager setObject:@"0" forKey:@"module"];
            [userDefaultManager setObject:@"1" forKey:@"page_no"];
            [self inboxMessage];
            [btnReceived setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
            [btnSent setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnSentArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            break;
        case 1://For Sent record
            shortArray = shortArrayTwo;
            [userDefaultManager setObject:@"1" forKey:@"module"];
            [userDefaultManager setObject:@"1" forKey:@"page_no"];
            [self sendMessage];
            [btnReceived setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnSent setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
            [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnSentArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            break;
        case 2://For Receiver Archive
            shortArray = shortArrayOne;
            [userDefaultManager setObject:@"2" forKey:@"module"];
            [userDefaultManager setObject:@"1" forKey:@"page_no"];
            [self inboxArchiveMessage];
            [btnReceived setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnSent setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
            [btnSentArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            break;
        case 3://For Sent Archive
            shortArray = shortArrayTwo;
            [userDefaultManager setObject:@"3" forKey:@"module"];
            [userDefaultManager setObject:@"1" forKey:@"page_no"];
            [self sendArchiveMessage];
            [btnReceived setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnSent setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnSentArchive setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
            break;
            
        default:
            break;
    }
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
    if (![selectedRowForDeleteArray containsObject:[NSNumber numberWithBool:YES]]) {
        NSDictionary *reaMsgDict = [recordArray objectAtIndex:index];
        NSString *readStr = [NSString stringWithFormat:@"%@",[reaMsgDict valueForKey:@"is_read"]];
        if ([readStr isEqualToString:@"1"]) {
            [btnReadUnRead setBackgroundImage:self.unReadMsgImage forState:UIControlStateNormal];
        }
        else {
            [btnReadUnRead setBackgroundImage:self.readMsgImage forState:UIControlStateNormal];
        }
    }
    
    [selectedRowForDeleteArray replaceObjectAtIndex:index withObject:[NSNumber numberWithBool:!selected]];
    [self.tableView reloadData];
    if ([selectedRowForDeleteArray containsObject:[NSNumber numberWithBool:YES]]) {
        [btnDelete setHidden:NO];
        [btnReadUnRead setHidden:NO];
        [btnSelectAll setTag:0];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        if (![selectedRowForDeleteArray containsObject:[NSNumber numberWithBool:NO]]) {
            [btnSelectAll setImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
            [btnSelectAll setTag:1];
        }
    }
    else {
        [btnDelete setHidden:YES];
        [btnReadUnRead setHidden:YES];
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
        
        NSString *checkModule = [userDefaultManager valueForKey:@"module"];
        BOOL flag = false;
        int value = checkModule.intValue;
        switch (value) {
            case 0:
                flag = true;
                break;
            case 1:
                flag = false;
                break;
            case 2:
                flag = true;
                break;
            case 3:
                flag = false;
                break;
                
            default:
                break;
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
            
            if (!flag) {
                if (i==5) {
                    break;
                }
            }
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
                
                if ([lbl.text isEqualToString:@"Subject (A to Z)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"subject";
                    preSelectStr = @"Subject (A to Z)";
                }
                else if ([lbl.text isEqualToString:@"Subject (Z to A)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"subject";
                    preSelectStr = @"Subject (Z to A)";
                }
                else if ([lbl.text isEqualToString:@"From (A to Z)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"fname";
                    preSelectStr = @"From (A to Z)";
                }
                else if ([lbl.text isEqualToString:@"From (Z to A)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"fname";
                    preSelectStr = @"From (Z to A)";
                }
                else if ([lbl.text isEqualToString:@"Date (Newest First)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"created";
                    preSelectStr = @"Date (Newest First)";
                }
                else if ([lbl.text isEqualToString:@"Date (Oldest First)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"created";
                    preSelectStr = @"Date (Oldest First)";
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
        
        NSString *listPage = @"";
        
        NSString *checkModule = [userDefaultManager valueForKey:@"module"];
        int value = checkModule.intValue;
        switch (value) {
            case 0:
                listPage = @"inbox";
                if (CheckStringForNull(referralNameStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                    [self cancelSearch];
                    [self inboxMessageDelay];
                    return;
                }
                break;
            case 1:
                listPage = @"sent";
                if (CheckStringForNull(referralNameStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                    [self cancelSearch];
                    [self sendMessageDelay];
                    return;
                }
                break;
            case 2:
                listPage = @"inboxArchive";
                if (CheckStringForNull(referralNameStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                    [self cancelSearch];
                    [self inboxArchiveMessageDelay];
                    return;
                }
                break;
            case 3:
                listPage = @"sentArchive";
                if (CheckStringForNull(referralNameStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                    [self cancelSearch];
                    [self sendArchiveMessageDelay];
                    return;
                }
                break;
            default:
                break;
        }
        
        //For login
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:pageCountStr,@"page_no",recordPerPage,@"record_per_page",listPage,@"list_page",referralNameStr,@"search_filter",shortDataStr,@"sort_data",shortDesStr,@"sort_direction",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getMessage" controls:@"messages" httpMethod:@"POST" data:data];
        
        [self hideTableOnSearch];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalMessages"];
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
    [btnReadUnRead setHidden:YES];
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
 * @Description These method are used for compose button
 * @Modified Date 27/07/2015
 */
#pragma mark Delete Record
-(IBAction)btnCompose:(UIButton*)sender {
    [self performSegueWithIdentifier:@"composeMsg" sender:nil];
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
            confermationMessage = @"Do you want to archive the message(s)?";
            break;
        case 1:
            confermationMessage = @"Do you want to archive the message(s)?";
            break;
        case 2:
            confermationMessage = @"Do you want to permanently delete the message(s)?";
            break;
        case 3:
            confermationMessage = @"Do you want to permanently delete the message(s)?";
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
            confermationMessage = @"Do you want to archive the message(s)?";
            break;
        case 1:
            confermationMessage = @"Do you want to archive the message(s)?";
            break;
        case 2:
            confermationMessage = @"Do you want to permanently delete the message(s)?";
            break;
        case 3:
            confermationMessage = @"Do you want to permanently delete the message(s)?";
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
        //For delete Referrals
        NSString *listingPage = @"";
        int value = checkModule.intValue;
        switch (value) {
            case 0:
                listingPage = @"inbox";
                break;
            case 1:
                listingPage = @"sent";
                break;
            case 2:
                listingPage = @"inboxArchive";
                break;
            case 3:
                listingPage = @"sentArchive";
                break;
                
            default:
                break;
        }
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:listingPage,@"listPage",deleteIdString,@"deleteId",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"deleteMessage" controls:@"messages" httpMethod:@"POST" data:data];
        
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
    [btnReadUnRead setHidden:YES];
    [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
    [btnSelectAll setTag:0];
    
    NSString *searchStr =[userDefaultManager valueForKey:@"searchText"];
    NSString *shortDesStr = [userDefaultManager valueForKey:@"filter"];
    NSString *shortDataStr = [userDefaultManager valueForKey:@"filter_name"];
    int value1 = checkModule.intValue;
    switch (value1) {
        case 0:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self inboxMessage];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 1:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self sendMessage];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 2:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self inboxArchiveMessage];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 3:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self sendArchiveMessage];
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
        NSDictionary *reaMsgDict = [recordArray objectAtIndex:0];
        NSString *readStr = @"";
        switch (sender.tag) {
            case 0:
                readStr = [NSString stringWithFormat:@"%@",[reaMsgDict valueForKey:@"is_read"]];
                if ([readStr isEqualToString:@"1"]) {
                    [btnReadUnRead setBackgroundImage:self.unReadMsgImage forState:UIControlStateNormal];
                }
                else {
                    [btnReadUnRead setBackgroundImage:self.readMsgImage forState:UIControlStateNormal];
                }
                [btnDelete setHidden:NO];
                [btnReadUnRead setHidden:NO];
                [btnSelectAll setImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
                [self populateSelected];
                [self.tableView reloadData];
                [sender setTag:1];
                break;
            case 1:
                [btnDelete setHidden:YES];
                [btnReadUnRead setHidden:YES];
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
            if ([self.delegateMessageClass respondsToSelector:@selector(callHomeScreen)]) {
                [self.delegateMessageClass callHomeScreen];
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
 * @Description These method are used for update list like as read and unread from another class.
 * @Modified Date 18/08/2015
 */
#pragma mark Check Read And Unread Message
-(void)updateStatusReadUnread {
    NSMutableDictionary *dummyDict = [[NSMutableDictionary alloc] init];
    dummyDict = [[recordArray objectAtIndex:saveIndex] mutableCopy];
    NSString *readStr = [NSString stringWithFormat:@"%@",[dummyDict valueForKey:@"is_read"]];
    if ([readStr isEqualToString:@"0"]) {
        [dummyDict setObject:@"1" forKey:@"is_read"];
        [recordArray replaceObjectAtIndex:saveIndex withObject:dummyDict];
        [btnDelete setHidden:YES];
        [btnReadUnRead setHidden:YES];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
        [self populateUnSelected];
        [self.tableView reloadData];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for update list of messgaes as read and unread.
 * @Modified Date 25/09/2015
 */
#pragma mark Set Read And Unread Status
-(IBAction)setMessageAsReadUnread:(UIButton *)sender {
    
    //For Send Message Id
    NSString *messageIdString = @"";
    for (int i = 0; i<recordArray.count; i++) {
        BOOL selected = [[selectedRowForDeleteArray objectAtIndex:i] boolValue];
        if (selected) {
            if (CheckStringForNull(messageIdString)) {
                messageIdString = [[recordArray objectAtIndex:i] valueForKey:@"id"];
            }
            else {
                NSString *idString = [NSString stringWithFormat:@",%@",[[recordArray objectAtIndex:i] valueForKey:@"id"]];
                messageIdString = [messageIdString stringByAppendingString:idString];
            }
        }
    }
    
    //For send Message Status
    NSString *messageStatusStr = @"";
    if ([btnReadUnRead currentBackgroundImage] == self.readMsgImage) {
        messageStatusStr = @"read";
    }
    else {
        messageStatusStr = @"unread";
    }
    
    NSString *checkModule = [userDefaultManager valueForKey:@"module"];
    if (isNetworkAvailable) {
        //For delete Referrals
        NSString *listingPage = @"";
        int value = checkModule.intValue;
        switch (value) {
            case 0:
                listingPage = @"inbox";
                break;
            case 1:
                listingPage = @"sent";
                break;
            case 2:
                listingPage = @"inboxArchive";
                break;
            case 3:
                listingPage = @"sentArchive";
                break;
                
            default:
                break;
        }
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:listingPage,@"listPage",messageIdString,@"messageId",messageStatusStr,@"messageStatus",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"changeMessageStatus" controls:@"messages" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            
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
    [btnReadUnRead setHidden:YES];
    [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
    [btnSelectAll setTag:0];
    
    NSString *searchStr =[userDefaultManager valueForKey:@"searchText"];
    NSString *shortDesStr = [userDefaultManager valueForKey:@"filter"];
    NSString *shortDataStr = [userDefaultManager valueForKey:@"filter_name"];
    int value1 = checkModule.intValue;
    switch (value1) {
        case 0:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self inboxMessage];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 1:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self sendMessage];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 2:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self inboxArchiveMessage];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 3:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self sendArchiveMessage];
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
 * @Parm none
 * @Description These method are used for get update of referral from came back to detail page.
 * @Modified Date 20/08/2015
 */
#pragma mark get update referral from came back to detail page
-(void)updateStatusReadUnreadGetUpdateFromServer {
    [self updateReferralList];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for get update of referral from came back to Edit referral page.
 * @Modified Date 08/09/2015
 */
#pragma mark get update referral from came back to Edit referral page
-(void)updateReceivedReferralFromEditReferral {
    [self updateReferralList];
}
-(void)updateReferralList {
    
    NSString *checkModule = [userDefaultManager valueForKey:@"module"];
    recordArray = [[NSMutableArray alloc] init];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    [btnDelete setHidden:YES];
    [btnReadUnRead setHidden:YES];
    [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
    [btnSelectAll setTag:0];
    
    NSString *searchStr =[userDefaultManager valueForKey:@"searchText"];
    NSString *shortDesStr = [userDefaultManager valueForKey:@"filter"];
    NSString *shortDataStr = [userDefaultManager valueForKey:@"filter_name"];
    int value1 = checkModule.intValue;
    switch (value1) {
        case 0:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self inboxMessage];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 1:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self sendMessage];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 2:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self inboxArchiveMessage];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 3:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self sendArchiveMessage];
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
#pragma mark - Navigation
// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    
    if ([segue.identifier isEqualToString:@"detailMessage"]) {
        MessageDetailViewController *messageDetailViewController = segue.destinationViewController;
        messageDetailViewController.delegateMessDetail = self;
        messageDetailViewController.messageIDStr = messageIDStr;
    }
}



@end

