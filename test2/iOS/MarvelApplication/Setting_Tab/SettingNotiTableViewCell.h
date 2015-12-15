//
//  SettingNotiTableViewCell.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 10/20/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface SettingNotiTableViewCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UIButton *btnSelectNotiType;
@property (weak, nonatomic) IBOutlet UILabel *lblNotiName;
@property (weak, nonatomic) IBOutlet UILabel *lblNotiDetail;

@end
