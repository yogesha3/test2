//
//  DemoTableControllerViewController.m
//  FPPopoverDemo
//
//  Created by Alvise Susmel on 4/13/12.
//  Copyright (c) 2012 Fifty Pixels Ltd. All rights reserved.
//

#import "DemoTableController.h"
#import "ReferralViewController.h"
#import "ContactViewController.h"

@interface DemoTableController ()

@end

@implementation DemoTableController
@synthesize delegate=_delegate;
@synthesize delegate1=_delegate1;
@synthesize checkString;
@synthesize checkFieldString;


- (void)viewDidLoad
{
    [super viewDidLoad];
    
    NSLog(@"%@",checkString);
    
    if ([checkString isEqualToString:@"contact"]) {
        array = @[@"Refer me"];
    }
    else if ([checkString isEqualToString:@"referral"]) {
        if ([checkFieldString isEqualToString:@"SUCCESS"]) {
            array = @[@"Add to contact",@"Request Review"];
        }
        else {
            array = @[@"Add to contact",@"Add to contact"];
        }
    }
    
    
    self.title = @"";
}


- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

#pragma mark - Table view data source

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return array.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *CellIdentifier = @"Cell";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    
    if(cell == nil)
    {
        cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleValue1 reuseIdentifier:CellIdentifier];
        
        UILabel *titleLbl = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, 170, cell.frame.size.height)];
        [titleLbl setTag:100];
        [titleLbl setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
        [cell.contentView addSubview:titleLbl];
        
    }
    
    UILabel *lbl = (UILabel *)[cell viewWithTag:100];
    
    tableView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    lbl.text = [NSString stringWithFormat:@"%@",[array objectAtIndex:indexPath.row]];
    lbl.textAlignment = NSTextAlignmentCenter;
    
    return cell;
}


#pragma mark - Table view delegate

-(void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    if([self.delegate respondsToSelector:@selector(selectedTableRow:)])
    {
        [self.delegate selectedTableRow:indexPath.row];
    }
    if([self.delegate1 respondsToSelector:@selector(selectedTableRow:)])
    {
        [self.delegate1 selectedTableRow:indexPath.row];
    }
}




@end
