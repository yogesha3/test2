//
//  NotificationViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 8/13/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "NotificationViewController.h"

@interface NotificationViewController ()

@end

@implementation NotificationViewController

- (void)viewDidLoad {
    
    [self removeTotalCountOnViewNotification];
    
    items = [[NSMutableArray alloc] init];
    
    tableView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    tableView.allowsSelection = NO;
    
    //For Referrals
    NSString * referralsCount = [[AppDelegate currentDelegate] notificationReferralsCountView];
    if (![referralsCount isEqualToString:@"0"]) {
        NSString *referralNotiStr = @"You have received a new referral";
        if (referralsCount.intValue > 1) {
            referralNotiStr = [NSString stringWithFormat:@"You have received %@ new referral(s)",referralsCount];
        }
        [items addObject:referralNotiStr];
    }
    //For Messages
    NSString * messagesCount = [[AppDelegate currentDelegate] notificationMessageCountView];
    if (![messagesCount isEqualToString:@"0"]) {
        NSString *referralNotiStr = @"You have received a new message";
        if (messagesCount.intValue > 1) {
            referralNotiStr = [NSString stringWithFormat:@"You have received %@ new message(s)",messagesCount];
        }
        [items addObject:referralNotiStr];
    }
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}


-(void)removeTotalCountOnViewNotification {
    if (isNetworkAvailable) {
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"readTotal" controls:@"app" httpMethod:@"POST" data:nil];
        
        if (parsedJSONToken != nil) {
            
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 13/08/2015
 */
#pragma mark Back Screen
- (IBAction)backScreen:(id)sender {
    [self.navigationController popViewControllerAnimated:NO];
}

#pragma marl - UITableView Data Source
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return [items count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView1 cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    static NSString *PlaceholderCellIdentifier = @"PlaceholderCell";
    
    UITableViewCell *cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
    
    if (cell == nil)
    {
        cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
        cell.backgroundColor = [UIColor clearColor];
        //cell.selectionStyle = UITableViewCellSelectionStyleNone;
        
        UIView *bgColorView = [[UIView alloc] initWithFrame:CGRectMake(0, 0, 400, 50)];
        bgColorView.backgroundColor = [UIColor whiteColor];
        [cell setSelectedBackgroundView:bgColorView];
        
        UILabel *LBL = [[UILabel alloc] initWithFrame:CGRectMake(10, 5, tableView.frame.size.width-20, 40)];
        [LBL setBackgroundColor:[UIColor clearColor]];
        [LBL setTextColor:[UIColor blackColor]];
        [LBL setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
        [LBL setTag:2];
        [cell.contentView addSubview:LBL];
    }
    UILabel *lbl = (UILabel*)[cell viewWithTag:2];
    
    NSString *titleString = [items objectAtIndex:indexPath.row];
    lbl.text = titleString;
    
    return cell;
    
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    
}

- (CGFloat)tableView:(UITableView *)aTableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    return 50;
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
