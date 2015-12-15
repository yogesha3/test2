//
//  ForGotPassViewController.h
//  MarvelApplication
//
//  Created by Deepak Chauhan on 7/9/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ForGotPassViewController : UIViewController {

    __weak IBOutlet UIImageView *imgEmailBG;
    __weak IBOutlet UITextField *textEmail;
    
}
@property (weak, nonatomic) IBOutlet UILabel *lblTitle;

@end
