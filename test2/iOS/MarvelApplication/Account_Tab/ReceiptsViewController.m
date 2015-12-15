//
//  ReceiptsViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 11/18/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import "ReceiptsViewController.h"
#import "ReceiptsBillingTableViewCell.h"
#import "BillingPDFReaderViewController.h"

@interface ReceiptsViewController ()

@end

@implementation ReceiptsViewController

- (void)viewDidLoad {
    
    [noRecordView setHidden:YES];
    [self.tableView setHidden:NO];
    
    self.tableView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    
    [self billingReceipts];
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 18/11/2015
 */
#pragma mark Back Screen
- (IBAction)backScreen:(id)sender {
    [self.navigationController popViewControllerAnimated:NO];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used Billing Receipts
 * @Modified Date 18/21/2015
 */
#pragma mark Billing Receiptas
-(void)billingReceipts {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(billingReceiptsDelay) withObject:nil afterDelay:0.1];
}
-(void)billingReceiptsDelay {
    receiptsArray = [[NSMutableArray alloc] init];
    if (isNetworkAvailable) {
        NSDictionary *parsedJSONResult =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"receipts" controls:@"businessOwners" httpMethod:@"POST" data:nil];
        
        if (parsedJSONResult != nil) {
            NSArray *array = [parsedJSONResult valueForKey:@"result"];
            receiptsArray = [[NSMutableArray alloc] initWithArray:array];
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    
    if (!receiptsArray.count) {
        [self.tableView setHidden:YES];
        [noRecordView setHidden:NO];
    }
    [self.tableView reloadData];
    [[AppDelegate currentDelegate] removeLoading];
}

#pragma marl - UITableView Data Source
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return [receiptsArray count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView1 cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    static NSString *PlaceholderCellIdentifier = @"receiptsCellBilling";
    
    ReceiptsBillingTableViewCell*cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
    cell.backgroundColor = [UIColor clearColor];
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
    if (cell == nil) {
        cell = [[ReceiptsBillingTableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
    }
    if (receiptsArray.count) {
        NSString *invoiceDateString = [[receiptsArray objectAtIndex:indexPath.row] valueForKey:@"invoice_date"];
        invoiceDateString = ConvertDateStringFormat(invoiceDateString) ;
        cell.lblInvoiceDate.text = invoiceDateString;
        
        NSString *purchaseDateString = [[receiptsArray objectAtIndex:indexPath.row] valueForKey:@"purchase"];
        purchaseDateString = ConvertDateStringFormat(purchaseDateString) ;
        cell.lblPurchaseDate.text = purchaseDateString;
        
        NSString *memberShipString = [[[receiptsArray objectAtIndex:indexPath.row] valueForKey:@"group_type"] uppercaseString];
        cell.lblMemberShip.layer.cornerRadius = 5.0f;
        cell.lblMemberShip.clipsToBounds = YES;
        if ([memberShipString isEqualToString:@"LOCAL"]) {
            cell.lblMemberShip.backgroundColor = [UIColor colorWithRed:217.0/255 green:214.0/255 blue:47.0/255 alpha:1];
        }
        else {
            cell.lblMemberShip.backgroundColor = [UIColor colorWithRed:113.0/255 green:160.0/255 blue:209.0/255 alpha:1];
        }
        cell.lblMemberShip.text = memberShipString;
        
        [cell.btnViewReceipts setTag:indexPath.row];
    }
    
    return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for View Receipts
 * @Modified Date 24/11/2015
 */
#pragma mark View Receipts
-(IBAction)viewReceipts:(UIButton *)sender {
    pdfURLString = [[receiptsArray objectAtIndex:sender.tag] valueForKey:@"pdfUrl"];
    [self performSegueWithIdentifier:@"viewBillingReceipts" sender:nil];
}

- (CGFloat)tableView:(UITableView *)aTableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    return 80;
}


- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
    if ([segue.identifier isEqualToString:@"viewBillingReceipts"]) {
        BillingPDFReaderViewController *billingPDFReaderViewController = segue.destinationViewController;
        billingPDFReaderViewController.pdfUrlString = pdfURLString;
    }
}


@end
