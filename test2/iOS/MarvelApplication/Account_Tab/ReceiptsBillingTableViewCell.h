//
//  ReceiptsBillingTableViewCell.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 11/18/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ReceiptsBillingTableViewCell : UITableViewCell
    

@property (weak, nonatomic) IBOutlet UILabel *lblInvoiceDate;
@property (weak, nonatomic) IBOutlet UILabel *lblPurchaseDate;
@property (weak, nonatomic) IBOutlet UILabel *lblMemberShip;
@property (weak, nonatomic) IBOutlet UIImageView *imgPDF;
@property (weak, nonatomic) IBOutlet UIButton *btnViewReceipts;
@end
