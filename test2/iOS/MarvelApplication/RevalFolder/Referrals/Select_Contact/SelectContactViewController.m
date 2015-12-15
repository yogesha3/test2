//
//  SelectContactViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/31/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "SelectContactViewController.h"
#import "ContactCell.h"
@interface SelectContactViewController ()

@end

@implementation SelectContactViewController
@synthesize DelegateContact;
@synthesize selectedImage , unSelectedImage;
@synthesize dictContact;

- (void)viewDidLoad {
    
    [super viewDidLoad];
    
    btnDone.layer.cornerRadius = 5.0f;
    
    self.selectedImage = [UIImage imageNamed:@"Selected.png"];
    self.unSelectedImage = [UIImage imageNamed:@"Unselected.png"];

    self.tableView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    
    [self receivedContact];
    // Do any additional setup after loading the view.
}


/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Get Contact List
 * @Modified Date 3/08/2015
 */
#pragma mark Received Referral's
- (void)receivedContact {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(receivedContactDelay) withObject:nil afterDelay:0.1];
}
-(void)receivedContactDelay {
    
    if (isNetworkAvailable) {
        
        NSData* data = nil;
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"contactList" controls:@"users" httpMethod:@"POST" data:data];
        
        recordArray = [[NSArray alloc] init];
        totalRecordArray = [[NSMutableArray alloc] init];
        [self hideTable];
        if (parsedJSONToken != nil) {
            totalRecordArray = [[parsedJSONToken valueForKey:@"result"] mutableCopy];
            if (totalRecordArray.count) {
                [self showTable];
            }
            NSDictionary *dict = @{@"first_name":@"Clear",@"last_name":@"Selection"};
            [totalRecordArray insertObject:dict atIndex:0];
            recordArray = totalRecordArray;
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [self populateSelected];
    
    if (recordArray.count) {
            NSString *idStr = [dictContact valueForKey:@"id"];
            for (int j = 0; j<recordArray.count; j++) {
                NSDictionary *dictTwo = [recordArray objectAtIndex:j];
                NSString *idSecStr = [dictTwo valueForKey:@"id"];
                if ([idSecStr isEqualToString:idStr]) {
                    [selectedArray replaceObjectAtIndex:j withObject:[NSNumber numberWithBool:YES]];
                }
            }
    }
    [self.tableView reloadData];
    [[AppDelegate currentDelegate] removeLoading];
    
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Show table
 * @Modified Date 31/07/2015
 */
#pragma mark showTable
-(void)showTable {
    [viewNoRecord setHidden:YES];
    [lblNoRecord setText:@"No record found"];
    [self.tableView setHidden:NO];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Hide table
 * @Modified Date 31/07/2015
 */
#pragma mark HideTable
-(void)hideTable {
    [viewNoRecord setHidden:NO];
    [lblNoRecord setText:@"No record found"];
    [self.tableView setHidden:YES];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Show Table On Search
 * @Modified Date 11/08/2015
 */
#pragma mark ShowTable On Search
-(void)showTableOnSearch {
    [viewNoRecord setHidden:YES];
    [lblNoRecord setText:@"No results found"];
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
    [viewNoRecord setHidden:NO];
    [lblNoRecord setText:@"No results found"];
    [self.tableView setHidden:YES];
}


/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for insert bool value in array according to list
 * @Modified Date 09/10/2015
 */
#pragma mark populate Selected & UnSelect
- (void)populateSelected {
    NSMutableArray *array = [[NSMutableArray alloc] initWithCapacity:[recordArray count]];
    for (int i=0; i < [recordArray count]; i++)
        [array addObject:[NSNumber numberWithBool:NO]];
    selectedArray = array;
}


#pragma marl - UITableView Data Source
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return recordArray.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView1 cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    static NSString *PlaceholderCellIdentifier = @"ContactCell";
    
    ContactCell*cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
    cell.backgroundColor = [UIColor clearColor];
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
    if (cell == nil) {
        cell = [[ContactCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
    }
    
    NSDictionary *dict = [recordArray objectAtIndex:indexPath.row];
    NSString *firstNameStr = @"";
    NSString *lastNameStr = @"";
    
    firstNameStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"first_name"]];
    if (!CheckStringForNull(firstNameStr)) {
        firstNameStr = [firstNameStr stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                   withString:[[firstNameStr substringToIndex:1] capitalizedString]];
    }
    lastNameStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"last_name"]];
    if (!CheckStringForNull(lastNameStr)) {
        lastNameStr = [NSString stringWithFormat:@" %@",[dict valueForKey:@"last_name"]];
        lastNameStr = [lastNameStr stringByReplacingCharactersInRange:NSMakeRange(0,2)
                                                                   withString:[[lastNameStr substringToIndex:2] capitalizedString]];
    }
    firstNameStr = [firstNameStr stringByAppendingString:lastNameStr];

    cell.lblTitle.text = firstNameStr;
    
    NSNumber *selected = [selectedArray objectAtIndex:[indexPath row]];
    UIImage *image = ([selected boolValue]) ? self.selectedImage : self.unSelectedImage;
    [cell.imgSelectAndUnSelect setImage:image];

    
    return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    
    BOOL selected = [[selectedArray objectAtIndex:indexPath.row] boolValue];
        for (int i = 0; i<selectedArray.count; i++) {
            if (i == indexPath.row) {
                if (selected) {
                    return;
                }
                [selectedArray replaceObjectAtIndex:indexPath.row withObject:[NSNumber numberWithBool:YES]];
                
            }
            else {
                [selectedArray replaceObjectAtIndex:i withObject:[NSNumber numberWithBool:NO]];
            }
        }
    index = (int)indexPath.row;
    [self.tableView reloadData];
 
}

- (CGFloat)tableView:(UITableView *)aTableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    return 60;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are called User select the Contact
 * @Modified Date 09/10/2015
 */
#pragma mark Select Contact
- (IBAction)SelectContact:(id)sender {
    
    if (recordArray.count) {
        NSDictionary *dict = [recordArray objectAtIndex:index];
        if ([self.DelegateContact respondsToSelector:@selector(selectedContact:)]) {
            [self.DelegateContact selectedContact:dict];
        }
        [self.navigationController popViewControllerAnimated:NO];

    }
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
    
    NSString *firstName = searchText;
    NSString *lastName = searchText;
    NSPredicate *predicate = nil;
    if (totalRecordArray.count) {
        [totalRecordArray removeObjectAtIndex:0];
    }
    predicate = [NSPredicate predicateWithFormat:@"first_name CONTAINS[cd] %@ OR last_name CONTAINS[cd] %@", firstName, lastName];
    
    NSArray *filteredArray = [totalRecordArray filteredArrayUsingPredicate:predicate];
    NSMutableArray *arr = [[NSMutableArray alloc] initWithArray:filteredArray];
    NSDictionary *dict = @{@"first_name":@"Clear",@"last_name":@"Selection"};
    [arr insertObject:dict atIndex:0];

    
    recordArray = [[NSArray alloc]initWithArray:arr];
    [self showTableOnSearch];
    if (filteredArray.count<1) {
        [self hideTableOnSearch];
    }
    [self populateSelected];
    [self.tableView reloadData];
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are called when cancel button pressed
 * @Modified Date 27/07/2015
 */
#pragma mark searchBarCancelButtonClicked
- (void)searchBarCancelButtonClicked:(UISearchBar *)searchBar {
    searchBar.text = @"";
    [self.view endEditing:YES];
    [self receivedContact];
}

#pragma mark textDidChange
- (void)searchBar:(UISearchBar *)searchBar1 textDidChange:(NSString *)searchText{
    if (CheckStringForNull(searchText)) {
        [self receivedContact];
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
