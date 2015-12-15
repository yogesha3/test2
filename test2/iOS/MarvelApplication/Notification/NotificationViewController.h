//
//  NotificationViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 8/13/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface NotificationViewController : UIViewController {

    __weak IBOutlet UITableView *tableView;
    NSMutableArray *items;
}

@end
