//
//  ContactDetailViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 9/30/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MessageUI/MessageUI.h>
#import "AddPartnersViewController.h"

@protocol ContactDetailDelegate <NSObject>

-(void)updateContactAfterEditContact;

@end


@interface ContactDetailViewController : UIViewController <MFMailComposeViewControllerDelegate , AddPartnersDelegate> {
    NSDictionary *recordDict;
    __weak IBOutlet UIView *viewOne;
    __weak IBOutlet UIView *viewTwo;
}
@property (nonatomic , weak) id<ContactDetailDelegate>delegateEditContact;
@property (nonatomic , strong) NSString *contactIDStr;
@property (weak, nonatomic) IBOutlet UILabel *lblName;
@property (weak, nonatomic) IBOutlet UILabel *lblProfession;
@property (weak, nonatomic) IBOutlet UILabel *lblEmail;
@property (weak, nonatomic) IBOutlet UILabel *lblWeb;
@property (weak, nonatomic) IBOutlet UILabel *lblMobile;
@property (weak, nonatomic) IBOutlet UILabel *lblPhone;
@property (weak, nonatomic) IBOutlet UILabel *lblAddress;
@property (weak, nonatomic) IBOutlet UIImageView *imgSeprator;

@end
