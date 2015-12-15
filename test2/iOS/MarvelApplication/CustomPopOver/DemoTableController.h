//
//  DemoTableControllerViewController.h
//  FPPopoverDemo
//
//  Created by Alvise Susmel on 4/13/12.
//  Copyright (c) 2012 Fifty Pixels Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>

@class ReferralViewController;
@class ContactViewController;

@interface DemoTableController : UITableViewController {

    NSArray *array;
}
@property (nonatomic , strong) NSString *checkString;
@property (nonatomic , strong) NSString *checkFieldString;
@property(nonatomic,assign) ReferralViewController *delegate;
@property(nonatomic,assign) ContactViewController *delegate1;
@end
