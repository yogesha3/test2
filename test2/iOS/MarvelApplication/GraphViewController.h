//
//  GraphViewController.h
//  MarvelApplication
//
//  Created by Deepak Chauhan on 7/8/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "GroupViewController.h"

@interface GraphViewController : UIViewController <GroupClassDelegate> {

    NSMutableArray *arrayValue;
    NSString *matchString;
}
@property (weak, nonatomic) IBOutlet UIScrollView *scrollView;
@end
