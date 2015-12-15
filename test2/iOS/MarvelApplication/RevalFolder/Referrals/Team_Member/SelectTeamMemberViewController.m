//
//  SelectTeamMemberViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/31/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "SelectTeamMemberViewController.h"
#import "TeamMemberCell.h"

@interface SelectTeamMemberViewController ()

@end

@implementation SelectTeamMemberViewController

@synthesize DelegateMember;
@synthesize preSelectArray;
@synthesize titleString;

- (void)viewDidLoad {
    
    lblTopTitle.text = titleString;
    tableView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    // Add a bottomBorder.
    CALayer *bottomBorder = [CALayer layer];
    bottomBorder.frame = CGRectMake(15.0f, selectAllView.frame.size.height-1, selectAllView.frame.size.width, 1.0f);
    bottomBorder.backgroundColor = BoarderColour.CGColor;
    [selectAllView.layer addSublayer:bottomBorder];
    
    self.selectedImage = [UIImage imageNamed:@"Selected.png"];
    self.unSelectedImage = [UIImage imageNamed:@"Unselected.png"];
    btnDone.layer.cornerRadius = 5.0f;
    
    totalRecordArray = [[NSMutableArray alloc] init];
    [self getCurrentTeamMember];
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Get Current Team Member
 * @Modified Date 3/08/2015
 */
#pragma mark Get Current Team Member's
- (void)getCurrentTeamMember {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(getCurrentTeamMemberDelay) withObject:nil afterDelay:0.1];
}
-(void)getCurrentTeamMemberDelay {

    if (isNetworkAvailable) {
        
        NSData* data = nil;
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"teamMemberList" controls:@"users" httpMethod:@"POST" data:data];
        recordArray = [[NSArray alloc] init];
        [self hideTable];
        if (parsedJSONToken != nil) {
            [self showTable];
            [totalRecordArray addObjectsFromArray:[parsedJSONToken valueForKey:@"result"]];
            recordArray = totalRecordArray;
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }

    [self performSelector:@selector(getPreviousTeamMemberDelay) withObject:nil afterDelay:0.1];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Get Previous Team Member
 * @Modified Date 25/09/2015
 */
#pragma mark Get Previous Team Member's
-(void)getPreviousTeamMemberDelay {
    
    if (isNetworkAvailable) {
        
        NSData* data = nil;
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"previousMemberList" controls:@"users" httpMethod:@"POST" data:data];

        if (parsedJSONToken != nil) {
            [self showTable];
           
            NSArray *previousArray = [parsedJSONToken valueForKey:@"result"];
            [totalRecordArray addObjectsFromArray:previousArray];

            recordArray = totalRecordArray;
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [self populateUnSelected];
    if (recordArray.count) {
        for (int i = 0; i<preSelectArray.count; i++) {
            NSDictionary *dict = [preSelectArray objectAtIndex:i];
            NSString *idStr = [dict valueForKey:@"list_user_id"];
            for (int j = 0; j<recordArray.count; j++) {
                NSDictionary *dictTwo = [recordArray objectAtIndex:j];
                NSString *idSecStr = [dictTwo valueForKey:@"list_user_id"];
                if ([idSecStr isEqualToString:idStr]) {
                    [selectedRowForDeleteArray replaceObjectAtIndex:j withObject:[NSNumber numberWithBool:YES]];
                }
            }
        }
    }
    if (![selectedRowForDeleteArray containsObject:[NSNumber numberWithBool:NO]]) {
        [btnSelectAll setBackgroundImage:[UIImage imageNamed:@"Selected.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:1];
    }
    [tableView reloadData];
    [[AppDelegate currentDelegate] removeLoading];
}


/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Hide Table
 * @Modified Date 07/08/2015
 */
#pragma mark hideTable
-(void)hideTable {
    [selectAllView setHidden:YES];
    [noResultView setHidden:NO];
    [lblNoRecord setText:@"No record found"];
    [tableView setHidden:YES];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Show table
 * @Modified Date 07/08/2015
 */
#pragma mark showTable
-(void)showTable {
    [selectAllView setHidden:NO];
    [tableView setHidden:NO];
    [lblNoRecord setText:@"No record found"];
    [noResultView setHidden:YES];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Hide Table ON Search
 * @Modified Date 07/08/2015
 */
#pragma mark hideTable ON Search
-(void)hideTableOnSearch {
    [selectAllView setHidden:YES];
    [noResultView setHidden:NO];
    [lblNoRecord setText:@"No results found"];
    [tableView setHidden:YES];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Hide Table ON Search
 * @Modified Date 07/08/2015
 */
#pragma mark showTable ON Search
-(void)showTableOnSearch {
    [selectAllView setHidden:NO];
    [noResultView setHidden:YES];
    [lblNoRecord setText:@"No results found"];
    [tableView setHidden:NO];
}


#pragma marl - UITableView Data Source
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    BOOL flagOne = true;
    BOOL flagTwo = true;
    indexMatchOne = -1;
    indexMatchTwo = -1;
    for (int i = 0; i<recordArray.count; i++) {
        NSDictionary *checkDict = [recordArray objectAtIndex:i];
        NSString *messageTypeStr = [checkDict valueForKey:@"member_type"];
        if (flagOne) {
            if ([messageTypeStr isEqualToString:@"current"]) {
                flagOne = false;
                indexMatchOne = i;
            }
        }
        if (flagTwo) {
            if ([messageTypeStr isEqualToString:@"previous"]) {
                flagTwo = false;
                indexMatchTwo = i;
            }
        }
    }
    return recordArray.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView1 cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    static NSString *PlaceholderCellIdentifier = @"Cell";
    
    TeamMemberCell*cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
    cell.backgroundColor = [UIColor clearColor];
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
    if (cell == nil) {
        cell = [[TeamMemberCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
    }

    if (indexMatchOne == indexPath.row) {
        
        cell.lblName.frame = CGRectMake(cell.lblName.frame.origin.x, 45, cell.lblName.frame.size.width, cell.lblName.frame.size.height);
        cell.btnSelect.frame = CGRectMake(cell.btnSelect.frame.origin.x, 45, cell.btnSelect.frame.size.width, cell.btnSelect.frame.size.height);
        cell.lblTitle.hidden = NO;
        cell.lblTitle.text = @"  Current";

    }
    else if (indexMatchTwo == indexPath.row) {
        
        cell.lblName.frame = CGRectMake(cell.lblName.frame.origin.x, 45, cell.lblName.frame.size.width, cell.lblName.frame.size.height);
        cell.btnSelect.frame = CGRectMake(cell.btnSelect.frame.origin.x, 45, cell.btnSelect.frame.size.width, cell.btnSelect.frame.size.height);
        cell.lblTitle.hidden = NO;
        cell.lblTitle.text = @"  Previous";

    }
    else {
        cell.lblName.frame = CGRectMake(cell.lblName.frame.origin.x, 15, cell.lblName.frame.size.width, cell.lblName.frame.size.height);
        cell.btnSelect.frame = CGRectMake(cell.btnSelect.frame.origin.x, 15, cell.btnSelect.frame.size.width, cell.btnSelect.frame.size.height);
        cell.lblTitle.hidden = YES;
    }
    
    NSDictionary *dict = [recordArray objectAtIndex:indexPath.row];
    NSString *fromLastNameStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"lname"]];
    if (!CheckStringForNull(fromLastNameStr)) {
        fromLastNameStr = [fromLastNameStr stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                           withString:[[fromLastNameStr substringToIndex:1] capitalizedString]];
    }
    NSString *fromFirstNameStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"fname"]];
    if (!CheckStringForNull(fromFirstNameStr)) {
        fromFirstNameStr = [NSString stringWithFormat:@" %@",[dict valueForKey:@"fname"]];
        fromFirstNameStr = [fromFirstNameStr stringByReplacingCharactersInRange:NSMakeRange(0,2)
                                                                     withString:[[fromFirstNameStr substringToIndex:2] capitalizedString]];
    }

    fromLastNameStr = [fromLastNameStr stringByAppendingString:fromFirstNameStr];
    cell.lblName.text = fromLastNameStr;
    
    [cell.btnSelect setTag:indexPath.row];
    [cell.btnSelect addTarget:self action:@selector(btnSelectRow:) forControlEvents:UIControlEventTouchUpInside];
    NSNumber *selected = [selectedRowForDeleteArray objectAtIndex:[indexPath row]];
    UIImage *image = ([selected boolValue]) ? self.selectedImage : self.unSelectedImage;
    [cell.btnSelect setBackgroundImage:image forState:UIControlStateNormal];
    
    return cell;
}


- (void)tableView:(UITableView *)tableView1 didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    
    BOOL selected = [[selectedRowForDeleteArray objectAtIndex:indexPath.row] boolValue];
    [selectedRowForDeleteArray replaceObjectAtIndex:indexPath.row withObject:[NSNumber numberWithBool:!selected]];
    [tableView reloadData];
    if ([selectedRowForDeleteArray containsObject:[NSNumber numberWithBool:YES]]) {
        [btnSelectAll setTag:0];
        [btnSelectAll setBackgroundImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
        if (![selectedRowForDeleteArray containsObject:[NSNumber numberWithBool:NO]]) {
            [btnSelectAll setBackgroundImage:[UIImage imageNamed:@"Selected.png"] forState:UIControlStateNormal];
            [btnSelectAll setTag:1];
        }
    }
    else {
        [btnSelectAll setBackgroundImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
    }
}

- (CGFloat)tableView:(UITableView *)aTableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (indexPath.row == indexMatchOne) {
        return 90;
    }
    else {
        if (indexMatchTwo == indexPath.row) {
            return 90;
        }
    }

    return 60;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for select Row for delete
 * @Modified Date 31/07/2015
 */
#pragma mark Selected Row
-(void)btnSelectRow:(UIButton*)sender {
    int index = (int)sender.tag;
    BOOL selected = [[selectedRowForDeleteArray objectAtIndex:index] boolValue];
    [selectedRowForDeleteArray replaceObjectAtIndex:index withObject:[NSNumber numberWithBool:!selected]];
    [tableView reloadData];
    if ([selectedRowForDeleteArray containsObject:[NSNumber numberWithBool:YES]]) {
        [btnSelectAll setTag:0];
        [btnSelectAll setBackgroundImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
        if (![selectedRowForDeleteArray containsObject:[NSNumber numberWithBool:NO]]) {
            [btnSelectAll setBackgroundImage:[UIImage imageNamed:@"Selected.png"] forState:UIControlStateNormal];
            [btnSelectAll setTag:1];
        }
    }
    else {
        [btnSelectAll setBackgroundImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
    }
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Select all record
 * @Modified Date 31/07/2015
 */
#pragma mark Select All Record
-(IBAction)btnSelectAll:(UIButton*)sender {
    switch (sender.tag) {
        case 0:
            [btnSelectAll setBackgroundImage:[UIImage imageNamed:@"Selected.png"] forState:UIControlStateNormal];
            [self populateSelected];
            [tableView reloadData];
            [sender setTag:1];
            break;
        case 1:
            [btnSelectAll setBackgroundImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
            [self populateUnSelected];
            [tableView reloadData];
            [sender setTag:0];
            break;
        default:
            break;
    }
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Select Done Team member
 * @Modified Date 31/07/2015
 */
#pragma mark Done
-(IBAction)btnDone:(UIButton*)sender {
    
    if (![selectedRowForDeleteArray containsObject:[NSNumber numberWithBool:YES]]) {
        NSString *messageStr = @"No record found.";
        if (selectedRowForDeleteArray.count) {
            messageStr = @"Please select at least one record.";
        }
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:messageStr delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return;
    }
    NSMutableArray *arraySelectMember = [[NSMutableArray alloc] init];
    for (int i = 0; i<selectedRowForDeleteArray.count; i++) {
        BOOL selected = [[selectedRowForDeleteArray objectAtIndex:i] boolValue];
        if (selected) {
            [arraySelectMember addObject:[recordArray objectAtIndex:i]];
        }
    }
    
    if ([self.DelegateMember respondsToSelector:@selector(selectedTeamMember:)]) {
        [self.DelegateMember selectedTeamMember:arraySelectMember];
    }
    [self.navigationController popViewControllerAnimated:NO];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are called when keyboard search button pressed
 * @Modified Date 27/07/2015
 */
#pragma mark searchBarSearchButtonClicked
- (void)searchBarSearchButtonClicked:(UISearchBar *)searchBar1 {
    [self.view endEditing:YES];
    NSString *text = searchBar1.text;
    NSString *searchText = [text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
    
    NSArray *array = [searchText componentsSeparatedByString:@" "];
    NSString *firstName = searchText;
    NSString *lastName = searchText;
    NSPredicate *predicate = nil;
    
    if ([array count] > 1) {
        firstName = array[0];
        lastName = array[1];
        predicate = [NSPredicate predicateWithFormat:@"(fname CONTAINS[cd] %@ AND lname CONTAINS[cd] %@) OR (fname CONTAINS[cd] %@ AND lname CONTAINS[cd] %@)", firstName, lastName, firstName, lastName];
    } else {
        predicate = [NSPredicate predicateWithFormat:@"fname CONTAINS[cd] %@ OR lname CONTAINS[cd] %@", firstName, lastName];
    }
    
    NSArray *filteredArray = [totalRecordArray filteredArrayUsingPredicate:predicate];
    recordArray = [[NSArray alloc]initWithArray:filteredArray];
    [self showTableOnSearch];
    if (recordArray.count<1) {
        [self hideTableOnSearch];
    }
    [btnSelectAll setBackgroundImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
    [btnSelectAll setTag:0];
    [self populateUnSelected];
    [tableView reloadData];
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are called when cancel button pressed
 * @Modified Date 27/07/2015
 */
#pragma mark searchBarCancelButtonClicked
- (void)searchBarCancelButtonClicked:(UISearchBar *)searchBar1 {
    searchBar.text = @"";
    [self showAllRecord];
}
#pragma mark textDidChange
- (void)searchBar:(UISearchBar *)searchBar1 textDidChange:(NSString *)searchText{
   if (CheckStringForNull(searchText)) {
       [self showAllRecord];
   }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are called when after search user want to see the all team member
 * @Modified Date 04/08/2015
 */
#pragma mark Show All Record
-(void)showAllRecord {
        [self.view endEditing:YES];
        recordArray = [[NSArray alloc]initWithArray:totalRecordArray];
        [btnSelectAll setBackgroundImage:[UIImage imageNamed:@"Unselected.png"] forState:UIControlStateNormal];
        [btnSelectAll setTag:0];
        [self populateUnSelected];
        [tableView reloadData];
        [self showTable];
        if (recordArray.count<1) {
            [self hideTable];
        }
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
 * @Parm none
 * @Description These method are used for insert bool value in array according to list
 * @Modified Date 31/07/2015
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
 * @Modified Date 31/07/2015
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
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
