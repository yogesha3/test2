//
//  TeamTableCell.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 10/8/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface TeamTableCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UIButton *btnSelectRow;
@property (weak, nonatomic) IBOutlet UILabel *lblName;
@property (weak, nonatomic) IBOutlet UIImageView *imgProfession;
@property (weak, nonatomic) IBOutlet UILabel *lblProfection;
@property (weak, nonatomic) IBOutlet UIImageView *imgAddress;
@property (weak, nonatomic) IBOutlet UILabel *lblAddressName;


@end
