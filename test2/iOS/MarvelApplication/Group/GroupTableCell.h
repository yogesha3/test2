//
//  GroupTableCell.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 10/15/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface GroupTableCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UILabel *lblDate;
@property (weak, nonatomic) IBOutlet UILabel *lblAddress;
@property (weak, nonatomic) IBOutlet UILabel *lblGroupType;
@property (weak, nonatomic) IBOutlet UILabel *lblGroupNumber;
@end
