
//  ReferralViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/20/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "TeamViewController.h"
#import "TeamTableCell.h"
#import "UITableView+DragLoad.h"
#import "ComposeViewController.h"

#define partnerNameSpecial        @"0123456789~`@#$%^&*()_+={}[]|;'<>?,₹€£/:\""

@interface TeamViewController () <UITableViewDragLoadDelegate>
@property (nonatomic) IBOutlet UIButton* revealButtonItem;
@end

@implementation TeamViewController
@synthesize delegateTeamClass;
@synthesize lblNotiCount;

- (void)viewDidLoad {
    
    [userDefaultManager setObject:@"" forKey:@"team"];
    [userDefaultManager synchronize];
    
    lblNotiCount.layer.cornerRadius = 5.0f;
    lblNotiCount.clipsToBounds = YES;
    
    [btnSelectAll setUserInteractionEnabled:YES];
    [btnSearchMore setUserInteractionEnabled:YES];
    
    //For Invite Partner
    [viewInviteFriend setHidden:YES];
    
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
    
    contantScroll.contentSize = CGSizeMake(400, 50);
    self.tableView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    
    [self.tableView setDragDelegate:self refreshDatePermanentKey:@"FriendList"];
    self.tableView.showLoadMoreView = YES;
    
    //self.scrollView.contentSize = CGSizeMake(640, self.scrollView.frame.size.height);
    
    [userDefaultManager setObject:@"0" forKey:@"module"];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    [self currentTeamMemberList];

    shortArrayOne = [NSArray arrayWithObjects:@{@"value":@"Added On (Newest First)"},
                     @{@"value":@"Added On (Oldest First)"},
                     @{@"value":@"Company (A to Z)"},
                     @{@"value":@"Company (Z to A)"},
                     @{@"value":@"Name (A to Z)"},
                     @{@"value":@"Name (Z to A)"},nil];
    
    shortArrayTwo = [NSArray arrayWithObjects:@{@"value":@"Added On (Newest First)"},
                     @{@"value":@"Added On (Oldest First)"},
                     @{@"value":@"Company (A to Z)"},
                     @{@"value":@"Company (Z to A)"},
                     @{@"value":@"Name (A to Z)"},
                     @{@"value":@"Name (Z to A)"},nil];
    
    shortArray = shortArrayOne;
    
    //For send invitation
    scrollInviteFriend.contentSize = CGSizeMake(scrollInviteFriend.frame.size.width, 470);
    imgBgInviteFriend.layer.cornerRadius = 5.0f;
    btnSendInvitation.layer.cornerRadius = 5.0f;
    imgGoalOne.layer.cornerRadius = 5.0f;
    imgGoalTwo.layer.cornerRadius = 5.0f;
    imgGoalThree.layer.cornerRadius = 5.0f;
    imgGoalFour.layer.cornerRadius = 5.0f;
    imgGoalFive.layer.cornerRadius = 5.0f;
    imgGoalSix.layer.cornerRadius = 5.0f;
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

// Called when the view is about to made visible. Default does nothing
-(void)viewWillAppear:(BOOL)animated {
    
    NSString *checStr = [userDefaultManager valueForKey:@"team"];
    if ([checStr isEqualToString:@"teamTab"]) {
        [userDefaultManager setObject:@"" forKey:@"team"];
        [userDefaultManager synchronize];
        [self updateTeamRecord];
    }
    
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
 * @Description These method are used for Current Team Member List
 * @Modified Date 30/09/2015
 */
#pragma mark Current Team Member List
- (void)currentTeamMemberList {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(currentTeamMemberListDelay) withObject:nil afterDelay:0.1];
}
-(void)currentTeamMemberListDelay {
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:@"current",@"listPage",pageCountStr,@"page_no",recordPerPage,@"record_per_page",@"",@"search_filter",@"",@"sort_data",@"",@"sort_direction",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getTeamList" controls:@"teams" httpMethod:@"POST" data:data];
        
        [self hideTable];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalMembers"];
            [userDefaultManager setObject:pageNoString forKey:@"page_no"];
            [userDefaultManager setObject:totalRecString forKey:@"totalRecord"];
            NSArray *array = [parsedJSONToken valueForKey:@"result"];
            for (NSDictionary *dictionary in array){
                [recordArray addObject:dictionary];
            }
        }
        [btnReferral setHidden:YES];
        [btnMessage setHidden:YES];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
    }
    else {
        [btnReferral setHidden:YES];
        [btnMessage setHidden:YES];
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
- (void)previousTeamMemberList {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(previousTeamMemberListDelay) withObject:nil afterDelay:0.1];
}
- (void)previousTeamMemberListDelay {
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        
        //For Previous Team Member List
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:@"previous",@"listPage",pageCountStr,@"page_no",recordPerPage,@"record_per_page",@"",@"search_filter",@"",@"sort_data",@"",@"sort_direction",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getTeamList" controls:@"teams" httpMethod:@"POST" data:data];
        
        [self hideTable];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalMembers"];
            [userDefaultManager setObject:pageNoString forKey:@"page_no"];
            [userDefaultManager setObject:totalRecString forKey:@"totalRecord"];
            NSArray *array = [parsedJSONToken valueForKey:@"result"];
            for (NSDictionary *dictionary in array){
                [recordArray addObject:dictionary];
            }
        }
        [btnReferral setHidden:YES];
        [btnMessage setHidden:YES];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
    }
    else {
        [btnReferral setHidden:YES];
        [btnMessage setHidden:YES];
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
                        [self currentTeamMemberList];
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
                        [self previousTeamMemberList];
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
    
    static NSString *PlaceholderCellIdentifier = @"TeamTableCell";
    
    TeamTableCell*cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
    cell.backgroundColor = [UIColor clearColor];
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
    if (cell == nil) {
        cell = [[TeamTableCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
    }
    if (recordArray.count) {
        NSDictionary *dict = [recordArray objectAtIndex:indexPath.row];
        
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
            default:
                break;
        }
        
        //For First Name
        NSString *firstNameStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"fname"]];
        if (CheckStringForNull(firstNameStr)) {
            firstNameStr = @"";
        }
        else {
            firstNameStr = [firstNameStr stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                 withString:[[firstNameStr substringToIndex:1] capitalizedString]];
        }
        //For Last Name
        NSString *lastNameStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"lname"]];
        if (CheckStringForNull(lastNameStr)) {
            lastNameStr = @"";
        }
        else {
            lastNameStr = [NSString stringWithFormat:@" %@",[dict valueForKey:@"lname"]];
            lastNameStr = [lastNameStr stringByReplacingCharactersInRange:NSMakeRange(0,2)
                                                               withString:[[lastNameStr substringToIndex:2] capitalizedString]];
        }
        
        firstNameStr = [firstNameStr stringByAppendingString:lastNameStr];
        
        cell.lblName.text = firstNameStr;
        
        //For Profission
        NSString *profissionStr = @"";
        NSString *profession = [NSString stringWithFormat:@"%@",[dict valueForKey:@"profession_name"]];
        if (CheckStringForNull(profession)) {
            profissionStr = @"NA ";
        }
        else {
            profissionStr = [NSString stringWithFormat:@"%@ @ ",[dict valueForKey:@"profession_name"]];
            profissionStr = [profissionStr stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                               withString:[[profissionStr substringToIndex:1] capitalizedString]];
        }
        //For Company
        NSString *CompanyStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"company"]];
        if (CheckStringForNull(CompanyStr)) {
            CompanyStr = @"NA";
        }
        else {
            CompanyStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"company"]];
        }
        
        profissionStr = [profissionStr stringByAppendingString:CompanyStr];
        
        NSMutableAttributedString * string = [[NSMutableAttributedString alloc] initWithString:profissionStr];
        if (!CheckStringForNull(profession)) {
            [string addAttribute:NSForegroundColorAttributeName value:orangeColour range:NSMakeRange(profession.length+3,CompanyStr.length)];
        }
        
        cell.lblProfection.attributedText  = string;
        
        //For State
        NSString *StateStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"state_name"]];
        if (CheckStringForNull(StateStr)) {
            StateStr = @"NA, ";
        }
        else {
            StateStr = [NSString stringWithFormat:@"%@, ",[dict valueForKey:@"state_name"]];
        }
        
        //For Country
        NSString *countryStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"country_name"]];
        if (CheckStringForNull(countryStr)) {
            countryStr = @"NA";
        }
        else {
            countryStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"country_name"]];
        }
        StateStr = [StateStr stringByAppendingString:countryStr];
        cell.lblAddressName.text = StateStr;
        
        //For Select Row
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
    teamMemberIDStr = [dictReco valueForKey:@"member_id"];
    [self performSegueWithIdentifier:@"team_Detail" sender:nil];
    saveIndex = (int)indexPath.row;
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
    }
}

- (UITableViewCellEditingStyle)tableView:(UITableView *)tableView editingStyleForRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSString *checkModule = [userDefaultManager valueForKey:@"module"];
    int value = checkModule.intValue;
    switch (value) {
        case 0:
            return UITableViewCellEditingStyleNone;
            break;
        case 1:
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
        case 0://For Current Team Member
            if (!CheckDeviceFunction()) {
                [contantScroll setContentOffset:CGPointMake(0,0) animated:NO];
            }
            [viewInviteFriend setHidden:YES];
            btnSelectAll.alpha = 1.0;
            btnSearchMore.alpha = 1.0;
            [btnSelectAll setUserInteractionEnabled:YES];
            [btnSearchMore setUserInteractionEnabled:YES];
            shortArray = shortArrayOne;
            [userDefaultManager setObject:@"0" forKey:@"module"];
            [userDefaultManager setObject:@"1" forKey:@"page_no"];
            [self currentTeamMemberList];
            [btnReceived setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
            [btnSent setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            break;
        case 1://For Current Previous Team Member
            if (!CheckDeviceFunction()) {
                [contantScroll setContentOffset:CGPointMake(0,0) animated:NO];
            }
            [viewInviteFriend setHidden:YES];
            btnSelectAll.alpha = 1.0;
            btnSearchMore.alpha = 1.0;
            [btnSelectAll setUserInteractionEnabled:YES];
            [btnSearchMore setUserInteractionEnabled:YES];
            shortArray = shortArrayTwo;
            [userDefaultManager setObject:@"1" forKey:@"module"];
            [userDefaultManager setObject:@"1" forKey:@"page_no"];
            [self previousTeamMemberList];
            [btnReceived setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnSent setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
            [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            break;
        case 2://For invite Partner
            [self getGoal];
            [viewInviteFriend setHidden:NO];
            [self hideTableAndNoRecordView];
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
                [contantScroll setContentOffset:CGPointMake(80,0) animated:NO];
            }
            break;
            
        default:
            break;
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Get goal
 * @Modified Date 23/07/2015
 */
#pragma mark Get Goal
-(void)getGoal {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(getGoalDelay) withObject:nil afterDelay:0.1];
}
-(void)getGoalDelay {

    [btnSendInvitation setTitle:@"SET GOALS" forState:UIControlStateNormal];
    
    goalDict = [[NSDictionary alloc] init];
    
    [textFieldGoalOne setUserInteractionEnabled:YES];
    [textFieldGoalThree setUserInteractionEnabled:YES];
    [textFieldGoalFive setUserInteractionEnabled:YES];
    
    if (isNetworkAvailable) {
        NSDictionary *parsedJSONResult =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"goals" controls:@"teams" httpMethod:@"POST" data:nil];
        
        if (parsedJSONResult != nil) {
            goalDict = [parsedJSONResult valueForKey:@"result"];
            
            //For Group Goal
            NSString *groupGoalString = [NSString stringWithFormat:@"%@",[goalDict valueForKey:@"group_goals"]];
            if (CheckStringForNull(groupGoalString)) {
                groupGoalString = @"0";
            }
            textFieldGoalOne.text = groupGoalString;
            //For Group Member Goal
            NSString *groupTeamGoalString = [NSString stringWithFormat:@"%@",[goalDict valueForKey:@"group_member_goals"]];
            if (CheckStringForNull(groupTeamGoalString)) {
                groupTeamGoalString = @"0";
            }
            textFieldGoalThree.text = groupTeamGoalString;
            //For Group Individual Goal
            NSString *groupIndividualGoalString = [NSString stringWithFormat:@"%@",[goalDict valueForKey:@"individual_goals"]];
            if (CheckStringForNull(groupIndividualGoalString)) {
                groupIndividualGoalString = @"0";
            }
            textFieldGoalFive.text = groupIndividualGoalString;
            
            //For Actual Group
            NSString *actualGoalString = [NSString stringWithFormat:@"%@",[goalDict valueForKey:@"actual_group_goals"]];
            textFieldGoalTwo.text = actualGoalString;
            //For Actual Group Goal
            NSString *actualGroupGoalString = [NSString stringWithFormat:@"%@",[goalDict valueForKey:@"actual_individual_goals"]];
            textFieldGoalFour.text = actualGroupGoalString;
            //For Actual Individual Goal
            NSString *actualIndividualGoalString = [NSString stringWithFormat:@"%@",[goalDict valueForKey:@"actual_individual_goals"]];
            textFieldGoalSix.text = actualIndividualGoalString;
            
            //For Member Type
            NSString *setEditGoalString = [[NSString stringWithFormat:@"%@",[goalDict valueForKey:@"set_edit_key"]] uppercaseString];
            if ([setEditGoalString isEqualToString:@"UPDATE"]) {
                [btnSendInvitation setTitle:@"UPDATE GOALS" forState:UIControlStateNormal];
            }
            
            //For Member Type
            NSString *memberTypeString = [[NSString stringWithFormat:@"%@",[goalDict valueForKey:@"member_type"]] uppercaseString];
            if ([memberTypeString isEqualToString:@"PARTICIPANT"]) {
                [textFieldGoalOne setUserInteractionEnabled:NO];
                [textFieldGoalThree setUserInteractionEnabled:NO];
                [textFieldGoalFive setUserInteractionEnabled:YES];
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
 * @Description These method are used for Update Goal
 * @Modified Date 23/07/2015
 */
#pragma mark Update Goal
-(IBAction)upDateGoals:(id)sender {
    [self makeEmptyFieldRed];
    BOOL isFilled = [self checkAllFieldAreFilled];
    if (!isFilled) {
        return;
    }
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(updateGoalDelay) withObject:nil afterDelay:0.1];
}
-(void)updateGoalDelay {
    
    [self.view endEditing:YES];
    
    //For Group Goals
    NSString *groupGoalString = textFieldGoalOne.text;
    //For Group Member Goals
    NSString *groupMemberGoalString = textFieldGoalThree.text;
    //For Group Individual Goals
    NSString *groupIndividualGoalString = textFieldGoalFive.text;
    
    NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:@"edit",@"mode",groupGoalString,@"group_goals",groupMemberGoalString,@"group_member_goals",groupIndividualGoalString,@"individual_goals",nil];
    
    NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                       options:NSJSONWritingPrettyPrinted error:nil];
    NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
    
    NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
    
    if (isNetworkAvailable) {
        NSDictionary *parsedJSONResult =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"goals" controls:@"teams" httpMethod:@"POST" data:data];
        
        if (parsedJSONResult != nil) {
            UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:[parsedJSONResult valueForKey:@"message"] delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [networkAlert show];
            [self getGoalDelay];
            return;
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [[AppDelegate currentDelegate] removeLoading];
}

// return NO to not change text
- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string {

    if (!isNumericKeyWord(string)) {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:@"Only numeric digits allowed." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
        return NO;
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
    
    // for Goal One
    NSString *OneString = textFieldGoalOne.text;
    if (CheckStringForNull(OneString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        
        return NO;
    }
    if (!isNumericKeyWord(OneString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Only numeric digits allowed." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        
        return NO;
    }
    if (OneString.length > 5) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Please enter no more than 5 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        
        return NO;
    }
    // for Goal Two
    NSString *twoString = textFieldGoalThree.text;
    if (CheckStringForNull(twoString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        
        return NO;
    }
    if (!isNumericKeyWord(twoString) ) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Only numeric digits allowed." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        
        return NO;
    }
    if (twoString.length > 5) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Please enter no more than 5 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        
        return NO;
    }
    // for Goal Three
    NSString *threeString = textFieldGoalFive.text;
    if (CheckStringForNull(threeString)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        
        return NO;
    }
    if (!isNumericKeyWord(threeString) ) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Only numeric digits allowed." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        
        return NO;
    }
    if (threeString.length > 5) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Please enter no more than 5 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
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
    
    // for Goal One
    NSString *OneString = textFieldGoalOne.text;
    if (!isNumericKeyWord(OneString) || OneString.length>5 || CheckStringForNull(OneString)) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgGoalOne];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgGoalOne];
    }
    // for Goal Two
    NSString *twoString = textFieldGoalThree.text;
    if (!isNumericKeyWord(twoString) || twoString.length>5 || CheckStringForNull(twoString )) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgGoalThree];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgGoalThree];
    }

    // for Goal Three
    NSString *threeString = textFieldGoalFive.text;
    if (!isNumericKeyWord(threeString) || threeString.length>5 || CheckStringForNull(threeString)) {
        [[ConnectionManager sharedManager] setBoarderColorRed:imgGoalFive];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:imgGoalFive];
    }

    return YES;
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
        [btnReferral setHidden:NO];
        [btnMessage setHidden:NO];
        [btnSelectAll setTag:0];
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        if (![selectedRowForDeleteArray containsObject:[NSNumber numberWithBool:NO]]) {
            [btnSelectAll setImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
            [btnSelectAll setTag:1];
        }
    }
    else {
        [btnReferral setHidden:YES];
        [btnMessage setHidden:YES];
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
        
        //Hear we set the selected image for filter
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
                if ([lbl.text isEqualToString:@"Added On (Newest First)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"created";
                    preSelectStr = @"Added On (Newest First)";
                }
                else if ([lbl.text isEqualToString:@"Added On (Oldest First)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"created";
                    preSelectStr = @"Added On (Oldest First)";
                }
                else if ([lbl.text isEqualToString:@"Name (A to Z)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"fname";
                    preSelectStr = @"Name (A to Z)";
                }
                else if ([lbl.text isEqualToString:@"Name (Z to A)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"fname";
                    preSelectStr = @"Name (Z to A)";
                }
                else if ([lbl.text isEqualToString:@"Company (A to Z)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"company";
                    preSelectStr = @"Company (A to Z)";
                }
                else if ([lbl.text isEqualToString:@"Company (Z to A)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"company";
                    preSelectStr = @"Company (Z to A)";
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
        NSString *listPageString = @"";
        
        NSString *checkModule = [userDefaultManager valueForKey:@"module"];
        int value = checkModule.intValue;
        switch (value) {
            case 0:
                listPageString = @"current";
                if (CheckStringForNull(referralNameStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                    [self cancelSearch];
                    [self currentTeamMemberListDelay];
                    return;
                }
                break;
            case 1:
                listPageString = @"previous";
                if (CheckStringForNull(referralNameStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                    [self cancelSearch];
                    [self previousTeamMemberListDelay];
                    return;
                }
                break;
                
            default:
                break;
        }
       
        //For Filter Current And Previoys Member
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:listPageString,@"listPage",pageCountStr,@"page_no",recordPerPage,@"record_per_page",referralNameStr,@"search_filter",shortDataStr,@"sort_data",shortDesStr,@"sort_direction",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getTeamList" controls:@"teams" httpMethod:@"POST" data:data];
        
        [self hideTableOnSearch];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalMembers"];
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
    [btnReferral setHidden:YES];
    [btnMessage setHidden:YES];
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
 * @Description These method are used for Send Referral And Message
 * @Modified Date 13/10/2015
 */
#pragma mark Send Referral And Message
-(IBAction)SendreferralAndMessage:(UIButton *)sender {
    
    memberRecordArray = [[NSMutableArray alloc] init];
    for (int i = 0; i<recordArray.count; i++) {
        BOOL selected = [[selectedRowForDeleteArray objectAtIndex:i] boolValue];
        if (selected) {
            [memberRecordArray addObject:[recordArray objectAtIndex:i]];
         }
    }
    
    if (sender.tag == 100) {
        [self performSegueWithIdentifier:@"teamSendMessage" sender:nil];
    }
    else {
        [self performSegueWithIdentifier:@"teamSendReferral" sender:nil];
    }
    
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
                [btnReferral setHidden:NO];
                [btnMessage setHidden:NO];
                [btnSelectAll setImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
                [self populateSelected];
                [self.tableView reloadData];
                [sender setTag:1];
                break;
            case 1:
                [btnReferral setHidden:YES];
                [btnMessage setHidden:YES];
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
            if ([self.delegateTeamClass respondsToSelector:@selector(callHomeScreen)]) {
                [self.delegateTeamClass callHomeScreen];
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
 * @Description These method are used for Update Team Member from ComposeMessageViewController class.
 * @Modified Date 13/10/2015
 */
#pragma mark Update Team Member
-(void)updateTeamRecord {
    
    NSString *checkModule = [userDefaultManager valueForKey:@"module"];
    recordArray = [[NSMutableArray alloc] init];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    [btnReferral setHidden:YES];
    [btnMessage setHidden:YES];
    [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
    [btnSelectAll setTag:0];
    
    NSString *searchStr =[userDefaultManager valueForKey:@"searchText"];
    NSString *shortDesStr = [userDefaultManager valueForKey:@"filter"];
    NSString *shortDataStr = [userDefaultManager valueForKey:@"filter_name"];
    int value1 = checkModule.intValue;
    switch (value1) {
        case 0:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self currentTeamMemberList];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 1:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self previousTeamMemberList];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
            
        default:
            break;
    }
}


#pragma mark - Navigation
// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    
    if ([segue.identifier isEqualToString:@"team_Detail"]) {
        TeamMemberDetailViewController *teamMemberDetailViewController = segue.destinationViewController;
        teamMemberDetailViewController.memberIDStr = teamMemberIDStr;
    }
    if ([segue.identifier isEqualToString:@"teamSendMessage"]) {
        ComposeMessageViewController *composeMessageViewController = segue.destinationViewController;
        composeMessageViewController.recordArr = memberRecordArray;
        composeMessageViewController.teamDelegate = self;
    }
    if ([segue.identifier isEqualToString:@"teamSendReferral"]) {
        ComposeViewController *composeViewController = segue.destinationViewController;
        composeViewController.recordArr = memberRecordArray;
    }
}



@end

