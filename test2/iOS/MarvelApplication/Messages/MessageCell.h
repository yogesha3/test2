//
//  MessageCell.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 9/9/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface MessageCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UIImageView *attachImg;
@property (weak, nonatomic) IBOutlet UIButton *btnSelectRow;
@property (weak, nonatomic) IBOutlet UILabel *lblTitle;
@property (weak, nonatomic) IBOutlet UIImageView *imgUser;
@property (weak, nonatomic) IBOutlet UILabel *lblUserName;
@property (weak, nonatomic) IBOutlet UIImageView *imgCalender;
@property (weak, nonatomic) IBOutlet UILabel *lblDate;

@end
