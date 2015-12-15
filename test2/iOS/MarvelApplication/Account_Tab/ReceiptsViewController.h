//
//  ReceiptsViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 11/18/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ReceiptsViewController : UIViewController {

    __weak IBOutlet UIView *noRecordView;
    NSMutableArray *receiptsArray;
    NSString *pdfURLString;
    __weak IBOutlet UIButton *btnViewReceipts;
}
@property (weak, nonatomic) IBOutlet UITableView *tableView;

@end
