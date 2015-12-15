//
//  ReferralViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/20/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "ReferralViewController.h"
#import "ReferralCell.h"
#import "UITableView+DragLoad.h"
#import "ComposeViewController.h"

@interface ReferralViewController () <UITableViewDragLoadDelegate>
@property (nonatomic) IBOutlet UIButton* revealButtonItem;
@end

@implementation ReferralViewController
@synthesize delegateOne;
@synthesize lblNotiCount;
@synthesize checkReferMeDict;

- (void)viewDidLoad {
    
    lblNotiCount.layer.cornerRadius = 5.0f;
    lblNotiCount.clipsToBounds = YES;
    
    [userDefaultManager setObject:@"" forKey:@"searchText"];
    [userDefaultManager setObject:@"" forKey:@"filter"];
    [userDefaultManager setObject:@"" forKey:@"filter_name"];
    [userDefaultManager setObject:@"" forKey:@"pre_Select"];
    
    recordArray = [[NSMutableArray alloc] init];
    [self customSetup];
    
    self.selectedImage = [UIImage imageNamed:@"Selected.png"];
    self.unSelectedImage = [UIImage imageNamed:@"Unselected.png"];
    botomView.layer.borderWidth = 1.0f;
    botomView.layer.borderColor = BoarderColour.CGColor;
    
    contantScroll.contentSize = CGSizeMake(530, 50);
    self.tableView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    
    [self.tableView setDragDelegate:self refreshDatePermanentKey:@"FriendList"];
    self.tableView.showLoadMoreView = YES;
   
    //self.scrollView.contentSize = CGSizeMake(640, self.scrollView.frame.size.height);
    
    [userDefaultManager setObject:@"0" forKey:@"module"];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    [self receivedReferral];
    
    shortArrayOne = [NSArray arrayWithObjects:@{@"value":@"Date (Newest First)"},
                  @{@"value":@"Date (Oldest First)"},
                  @{@"value":@"From (A to Z)"},
                  @{@"value":@"From (Z to A)"},
                  @{@"value":@"Referral (A to Z)"},
                  @{@"value":@"Referral (Z to A)"},
                  @{@"value":@"Status (A to Z)"},
                  @{@"value":@"Status (Z to A)"},
                  @{@"value":@"Value (High to Low)"},
                  @{@"value":@"Value (Low to High)"},nil];
    
    shortArrayTwo = [NSArray arrayWithObjects:@{@"value":@"Date (Newest First)"},
                     @{@"value":@"Date (Oldest First)"},
                     @{@"value":@"To (A to Z)"},
                     @{@"value":@"To (Z to A)"},
                     @{@"value":@"Referral (A to Z)"},
                     @{@"value":@"Referral (Z to A)"},
                     @{@"value":@"Status (A to Z)"},
                     @{@"value":@"Status (Z to A)"},
                     @{@"value":@"Value (High to Low)"},
                     @{@"value":@"Value (Low to High)"},nil];
    
    shortArray = shortArrayOne;
    
    if (checkReferMeDict.count) {
        [self performSegueWithIdentifier:@"compose" sender:nil];
    }
    
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
 * @Description These method are used for received Referral's
 * @Modified Date 31/07/2015
 */
#pragma mark Received Referral's
- (void)receivedReferral {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(receivedReferralDelay) withObject:nil afterDelay:0.1];
}
-(void)receivedReferralDelay {
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:pageCountStr,@"page_no",recordPerPage,@"record_per_page",@"received",@"list_page",@"",@"referral_name",@"",@"sender_name",@"",@"status",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getReferral" controls:@"referrals" httpMethod:@"POST" data:data];
        
        [self hideTable];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalReferrals"];
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
 * @Description These method are used for Send Referral's
 * @Modified Date 31/07/2015
 */
#pragma mark Send Referral's
- (void)sendReferral {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(sendReferralDelay) withObject:nil afterDelay:0.1];
}
- (void)sendReferralDelay {
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        //For login
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:pageCountStr,@"page_no",recordPerPage,@"record_per_page",@"sent",@"list_page",@"",@"referral_name",@"",@"sender_name",@"",@"status",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getReferral" controls:@"referrals" httpMethod:@"POST" data:data];
        
        [self hideTable];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalReferrals"];
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
 * @Description These method are used for received Archive Referral's
 * @Modified Date 31/07/2015
 */
#pragma mark Received Archive Referral's
- (void)receivedArchiveReferral {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(receivedArchiveReferralDelay) withObject:nil afterDelay:0.1];
}
- (void)receivedArchiveReferralDelay {
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        //For login
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:pageCountStr,@"page_no",recordPerPage,@"record_per_page",@"archiveReceived",@"list_page",@"",@"referral_name",@"",@"sender_name",@"",@"status",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getReferral" controls:@"referrals" httpMethod:@"POST" data:data];
        
        [self hideTable];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalReferrals"];
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
 * @Description These method are used for Send Archive Referral's
 * @Modified Date 31/07/2015
 */
#pragma mark Send Archive Referral's
- (void)sendArchiveReferral {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(sendArchiveReferralDelay) withObject:nil afterDelay:0.1];
}
- (void)sendArchiveReferralDelay {
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        //For login
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:pageCountStr,@"page_no",recordPerPage,@"record_per_page",@"archiveSent",@"list_page",@"",@"referral_name",@"",@"sender_name",@"",@"status",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getReferral" controls:@"referrals" httpMethod:@"POST" data:data];
        
        [self hideTable];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalReferrals"];
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
                        [self receivedReferral];
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
                        [self sendReferral];
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
                        [self receivedArchiveReferral];
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
                        [self sendArchiveReferral];
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
    
    static NSString *PlaceholderCellIdentifier = @"ReferralCell";
    
     ReferralCell*cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
    cell.backgroundColor = [UIColor clearColor];
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
    if (cell == nil) {
        cell = [[ReferralCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
    }
    
    [cell.btnMore setHidden:YES];
    
    if (recordArray.count) {
        
        NSDictionary *dict = [recordArray objectAtIndex:indexPath.row];
        
        NSString *checkModule = [userDefaultManager valueForKey:@"module"];
        BOOL flag;
        int value = checkModule.intValue;
        switch (value) {
            case 0:
                flag = true;
                cell.btnMore.tag = indexPath.row;
                [cell.btnMore setHidden:NO];
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
        
        if (flag) {
            
            cell.lblTitle.frame  = CGRectMake(cell.lblTitle.frame.origin.x, 1, cell.lblTitle.frame.size.width, cell.lblTitle.frame.size.height);
            
            cell.imgUser.frame  = CGRectMake(cell.imgUser.frame.origin.x, 55, cell.imgUser.frame.size.width, cell.imgUser.frame.size.height);
            
            cell.lblUserName.frame  = CGRectMake(cell.lblUserName.frame.origin.x, 48, cell.lblUserName.frame.size.width, cell.lblUserName.frame.size.height);
            
            cell.imgCalender.frame  = CGRectMake(cell.imgCalender.frame.origin.x, 55, cell.imgCalender.frame.size.width, cell.imgCalender.frame.size.height);
            
            cell.lblDate.frame  = CGRectMake(cell.lblDate.frame.origin.x, 48, cell.lblDate.frame.size.width, cell.lblDate.frame.size.height);
            
            cell.lblStatus.hidden = NO;
            cell.lblValue.hidden = NO;
            cell.lblSeprator.hidden = NO;
            
            NSMutableAttributedString * string = nil;
            NSString *statStr  = [dict valueForKey:@"referral_status"];
            if (!CheckStringForNull(statStr)) {
                NSString *statusStr = [NSString stringWithFormat:@"Status: %@",statStr.capitalizedString];
                string = [[NSMutableAttributedString alloc] initWithString:statusStr];
                [string addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:NSMakeRange(0,8)];
                [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(0,8)];
                [string addAttribute:NSForegroundColorAttributeName value:[UIColor lightGrayColor] range:NSMakeRange(8,statusStr.length-8)];
                [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:12.0] range:NSMakeRange(8,statusStr.length-8)];
            }
            else {
                string = [[NSMutableAttributedString alloc] initWithString:@"Status: "];
                [string addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:NSMakeRange(0,8)];
                [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(0,8)];
            }
            
            cell.lblStatus.attributedText = string;
            cell.lblStatus.adjustsFontSizeToFitWidth = YES;
            
            NSString *valStr  = [dict valueForKey:@"monetary_value"];
            NSMutableAttributedString * stringVal = nil;
            if (!CheckStringForNull(valStr)) {
                
                NSNumberFormatter *formatter = [NSNumberFormatter new];
                [formatter setNumberStyle:NSNumberFormatterDecimalStyle]; // this line is important!
                NSNumber *myNumber = [formatter numberFromString:valStr];
                NSString *valueString = [formatter stringFromNumber:myNumber];
                NSString *valueStr = [NSString stringWithFormat:@"Value: $%@",valueString.capitalizedString];
                stringVal = [[NSMutableAttributedString alloc] initWithString:valueStr];
                [stringVal addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:NSMakeRange(0,7)];
                [stringVal addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(0,7)];
                [stringVal addAttribute:NSForegroundColorAttributeName value:[UIColor lightGrayColor] range:NSMakeRange(7,valueStr.length-7)];
                [stringVal addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:12.0] range:NSMakeRange(7,valueStr.length-7)];
                
            }
            else {
                stringVal = [[NSMutableAttributedString alloc] initWithString:@"Value: $0"];
                [stringVal addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:NSMakeRange(0,7)];
                [stringVal addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(0,7)];
                [stringVal addAttribute:NSForegroundColorAttributeName value:[UIColor lightGrayColor] range:NSMakeRange(7,2)];
                [stringVal addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:12.0] range:NSMakeRange(7,2)];
                
            }
            cell.lblValue.attributedText = stringVal;
            cell.lblValue.adjustsFontSizeToFitWidth = YES;
        }
        else {
            cell.lblStatus.hidden = YES;
            cell.lblValue.hidden = YES;
            cell.lblSeprator.hidden = YES;
            
            cell.lblTitle.frame  = CGRectMake(cell.lblTitle.frame.origin.x, 11, cell.lblTitle.frame.size.width, cell.lblTitle.frame.size.height);
            
            cell.imgUser.frame  = CGRectMake(cell.imgUser.frame.origin.x, 45, cell.imgUser.frame.size.width, cell.imgUser.frame.size.height);
            
            cell.lblUserName.frame  = CGRectMake(cell.lblUserName.frame.origin.x, 38, cell.lblUserName.frame.size.width, cell.lblUserName.frame.size.height);
            
            cell.imgCalender.frame  = CGRectMake(cell.imgCalender.frame.origin.x, 45, cell.imgCalender.frame.size.width, cell.imgCalender.frame.size.height);
            
            cell.lblDate.frame  = CGRectMake(cell.lblDate.frame.origin.x, 38, cell.lblDate.frame.size.width, cell.lblDate.frame.size.height);
        }
        
        NSString *firstNameStr = [NSString stringWithFormat:@"%@",[[dict valueForKey:@"first_name"] capitalizedString]];
        NSString *lastNameStr = [NSString stringWithFormat:@" %@",[[dict valueForKey:@"last_name"] capitalizedString]];
        firstNameStr = [firstNameStr stringByAppendingString:lastNameStr];
        cell.lblTitle.text = firstNameStr;
        
        NSString *readStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"is_read"]];
        if ([readStr isEqualToString:@"0"]) {
            [cell.lblTitle setFont:[UIFont fontWithName:@"Graphik-Bold" size:15.0]];
        }
        else {
            [cell.lblTitle setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0]];
        }
        
        NSString *fromToStr  = @"From";
        if (!flag) {
            fromToStr = @"To";
        }
        
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
    referralIDStr = [dictReco valueForKey:@"id"];
    [self performSegueWithIdentifier:@"detailReferral" sender:nil];
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
 * @Description These method are used for Show more button functionality
 * @Modified Date 14/12/2015
 */
#pragma mark Show More Button
-(IBAction)showMoreButton:(UIButton *)sender {
    
    referralIndex = sender.tag;
    
    NSDictionary *dict = [recordArray objectAtIndex:sender.tag];
    NSString *statStr  = [[dict valueForKey:@"referral_status"] uppercaseString];
    
    SAFE_ARC_RELEASE(popover); popover=nil;
    
    //the controller we want to present as a popover
    DemoTableController *controller = [[DemoTableController alloc] initWithStyle:UITableViewStylePlain];
    controller.delegate = self;
    controller.checkString = @"referral";
    controller.checkFieldString = statStr;
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
    
    NSLog(@"SELECTED ROW %d",rowNum);
    [popover dismissPopoverAnimated:YES];
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
            [self receivedReferral];
            [btnReceived setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
            [btnSent setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnSentArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            break;
        case 1://For Sent record
            shortArray = shortArrayTwo;
            [userDefaultManager setObject:@"1" forKey:@"module"];
            [userDefaultManager setObject:@"1" forKey:@"page_no"];
            [self sendReferral];
            [btnReceived setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnSent setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
            [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnSentArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            break;
        case 2://For Receiver Archive
            shortArray = shortArrayOne;
            [userDefaultManager setObject:@"2" forKey:@"module"];
            [userDefaultManager setObject:@"1" forKey:@"page_no"];
            [self receivedArchiveReferral];
            [btnReceived setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnSent setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            [btnReceivedArchive setBackgroundImage:[UIImage imageNamed:@"SelectModule.png"] forState:UIControlStateNormal];
            [btnSentArchive setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
            break;
        case 3://For Sent Archive
            shortArray = shortArrayTwo;
            [userDefaultManager setObject:@"3" forKey:@"module"];
            [userDefaultManager setObject:@"1" forKey:@"page_no"];
            [self sendArchiveReferral];
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
                
                if ([lbl.text isEqualToString:@"Referral (A to Z)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"first_name";
                    preSelectStr = @"Referral (A to Z)";
                }
                else if ([lbl.text isEqualToString:@"Referral (Z to A)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"first_name";
                    preSelectStr = @"Referral (Z to A)";
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
                else if ([lbl.text isEqualToString:@"To (A to Z)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"fname";
                    preSelectStr = @"To (A to Z)";
                }
                else if ([lbl.text isEqualToString:@"To (Z to A)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"fname";
                    preSelectStr = @"To (Z to A)";
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
                else if ([lbl.text isEqualToString:@"Status (A to Z)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"referral_status";
                    preSelectStr = @"Status (A to Z)";
                }
                else if ([lbl.text isEqualToString:@"Status (Z to A)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"referral_status";
                    preSelectStr = @"Status (Z to A)";
                }
                else if ([lbl.text isEqualToString:@"Value (High to Low)"]) {
                    filterStr = @"DESC";
                    filterNameStr = @"monetary_value";
                    preSelectStr = @"Value (High to Low)";
                }
                else if ([lbl.text isEqualToString:@"Value (Low to High)"]) {
                    filterStr = @"ASC";
                    filterNameStr = @"monetary_value";
                    preSelectStr = @"Value (Low to High)";
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
                listPage = @"received";
                if (CheckStringForNull(referralNameStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                    [self cancelSearch];
                    [self receivedReferralDelay];
                    return;
                }
                break;
            case 1:
                listPage = @"sent";
                if (CheckStringForNull(referralNameStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                    [self cancelSearch];
                    [self sendReferralDelay];
                    return;
                }
                break;
            case 2:
                listPage = @"archiveReceived";
                if (CheckStringForNull(referralNameStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                    [self cancelSearch];
                    [self receivedArchiveReferralDelay];
                    return;
                }
                break;
            case 3:
                listPage = @"archiveSent";
                if (CheckStringForNull(referralNameStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                    [self cancelSearch];
                    [self sendArchiveReferralDelay];
                    return;
                }
                break;
            default:
                break;
        }
        
        //For login
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:pageCountStr,@"page_no",recordPerPage,@"record_per_page",listPage,@"list_page",referralNameStr,@"referral_name",shortDataStr,@"sort_data",shortDesStr,@"sort_direction",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getReferral" controls:@"referrals" httpMethod:@"POST" data:data];
        
        [self hideTableOnSearch];
        
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalReferrals"];
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
 * @Description These method are used for compose button
 * @Modified Date 27/07/2015
 */
#pragma mark Compose referral's
-(IBAction)btnCompose:(UIButton*)sender {
    checkReferMeDict = nil;
    [self performSegueWithIdentifier:@"compose" sender:nil];
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
            confermationMessage = @"Do you want to archive the referral(s)?";
            break;
        case 1:
            confermationMessage = @"Do you want to archive the referral(s)?";
            break;
        case 2:
            confermationMessage = @"Do you want to permanently delete the referral(s)?";
            break;
        case 3:
            confermationMessage = @"Do you want to permanently delete the referral(s)?";
            break;
            
        default:
            break;
    }

    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:confermationMessage delegate:self cancelButtonTitle:nil otherButtonTitles:@"OK",@"Cancel", nil];
    alert.tag = 100;
    [alert show];
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

#pragma mark Delete Single Record
-(void)deleteSingleRecord:(int)index {
    deleteIdString = @"";
    deleteIdString = [[recordArray objectAtIndex:index] valueForKey:@"id"];
    NSString *confermationMessage = @"";
    NSString *checkModule = [userDefaultManager valueForKey:@"module"];
    int value = checkModule.intValue;
    switch (value) {
        case 0:
            confermationMessage = @"Do you want to archive the referral(s)?";
            break;
        case 1:
            confermationMessage = @"Do you want to archive the referral(s)?";
            break;
        case 2:
            confermationMessage = @"Do you want to permanently delete the referral(s)?";
            break;
        case 3:
            confermationMessage = @"Do you want to permanently delete the referral(s)?";
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
                listingPage = @"received";
                break;
            case 1:
                listingPage = @"sent";
                break;
            case 2:
                listingPage = @"archiveReceived";
                break;
            case 3:
                listingPage = @"archiveSent";
                break;
                
            default:
                break;
        }

        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:listingPage,@"listPage",deleteIdString,@"deleteId",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"deleteReferral" controls:@"referrals" httpMethod:@"POST" data:data];
        
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
                [self receivedReferral];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 1:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self sendReferral];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 2:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self receivedArchiveReferral];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 3:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self sendArchiveReferral];
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
            if ([self.delegateOne respondsToSelector:@selector(callHomeScreen)]) {
                [self.delegateOne callHomeScreen];
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
        [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
        [self populateUnSelected];
        [self.tableView reloadData];
    }
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
    [btnSelectAll setImage:[UIImage imageNamed:@"UnselectedBlack.png"] forState:UIControlStateNormal];
    [btnSelectAll setTag:0];
    
    NSString *searchStr =[userDefaultManager valueForKey:@"searchText"];
    NSString *shortDesStr = [userDefaultManager valueForKey:@"filter"];
    NSString *shortDataStr = [userDefaultManager valueForKey:@"filter_name"];
    int value1 = checkModule.intValue;
    switch (value1) {
        case 0:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self receivedReferral];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 1:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self sendReferral];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 2:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self receivedArchiveReferral];
            }
            else {
                [self doneSearchLoadMore];
            }
            break;
        case 3:
            if (CheckStringForNull(searchStr) && CheckStringForNull(shortDesStr) && CheckStringForNull(shortDataStr)) {
                [self sendArchiveReferral];
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
 
    if ([segue.identifier isEqualToString:@"detailReferral"]) {
        ReferralsDetailViewController *referralsDetailViewController = segue.destinationViewController;
        referralsDetailViewController.delegateDetail = self;
        referralsDetailViewController.referralIDStr = referralIDStr;
    }
    //For Refer Me From contactViewController
    if ([segue.identifier isEqualToString:@"compose"]) {
        ComposeViewController *composeViewController = segue.destinationViewController;
        composeViewController.dictReferMe = checkReferMeDict;
    }
}


@end
