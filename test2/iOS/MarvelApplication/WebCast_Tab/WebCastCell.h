//
//  WebCastCell.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 12/2/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "AsyncImageView.h"

@interface WebCastCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UIImageView *imgSoundWaves;
@property (weak, nonatomic) IBOutlet AsyncImageView *imgThumNil;
@property (weak, nonatomic) IBOutlet UILabel *lblVideoTitle;
@property (weak, nonatomic) IBOutlet UILabel *lblVideoDate;

@end
