//
//  SelectAndCreateContactViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/29/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "SelectContactViewController.h"

@interface SelectAndCreateContactViewController : UIViewController <selectContactDelegate> {

    __weak IBOutlet UIButton *btnNext;
    NSDictionary *dictionary;
    NSString *identifyFunctionality;
    NSDictionary *contactDict;
}
@property (nonatomic , strong) NSDictionary *dictReferMe;
@property (nonatomic , strong) NSString *selectTememberIdString;
@property (weak, nonatomic) IBOutlet UIImageView *imgControlBG;
@property (weak, nonatomic) IBOutlet UIImageView *imgBgTextField;
@property (weak, nonatomic) IBOutlet UITextField *textField;
@property (weak, nonatomic) IBOutlet UIButton *btnContactCreate;
@property (weak, nonatomic) IBOutlet UILabel *lblOr;

@end
