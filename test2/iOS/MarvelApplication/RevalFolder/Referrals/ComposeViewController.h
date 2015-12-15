//
//  ComposeViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/29/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "SelectTeamMemberViewController.h"

@interface ComposeViewController : UIViewController <selectTeamMemberDelegate> {

    __weak IBOutlet UIButton *btnNext;
    NSMutableArray *teamMemberArray;
}
@property (nonatomic , strong) NSDictionary *dictReferMe;
@property (nonatomic , strong) NSMutableArray *recordArr;
@property (weak, nonatomic) IBOutlet UIImageView *imgControlBG;
@property (weak, nonatomic) IBOutlet UIImageView *imgBgTextField;
@property (weak, nonatomic) IBOutlet UITextField *textField;

@end
