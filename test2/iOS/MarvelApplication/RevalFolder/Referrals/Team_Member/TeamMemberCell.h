//
//  TeamMemberCell.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/31/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface TeamMemberCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UILabel *lblName;
@property (weak, nonatomic)IBOutlet UIButton *btnSelect;
@property (weak, nonatomic)IBOutlet UILabel *lblTitle;
@end
