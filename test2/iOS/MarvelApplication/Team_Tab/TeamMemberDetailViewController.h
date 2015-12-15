//
//  TeamMemberDetailViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 10/8/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//


#import <UIKit/UIKit.h>
#import <MessageUI/MessageUI.h>


@interface TeamMemberDetailViewController : UIViewController <MFMailComposeViewControllerDelegate> {
    NSDictionary *recordDict;
    __weak IBOutlet UIImageView *imgProfile;
    __weak IBOutlet UIImageView *imgStarOne;
    __weak IBOutlet UIImageView *imgStarTwo;
    __weak IBOutlet UIImageView *imgStarThree;
    __weak IBOutlet UIImageView *imgStarFour;
    __weak IBOutlet UIImageView *imgStarFive;
    __weak IBOutlet UILabel *lblReview;
    __weak IBOutlet UIView *viewOne;
    __weak IBOutlet UIView *viewTwo;
    __weak IBOutlet UIView *viewThree;
    __weak IBOutlet UIView *viewFour;
    __weak IBOutlet UIImageView *imgSkyp;
    __weak IBOutlet UILabel *lblSkyp;
    __weak IBOutlet UIImageView *imgTimezon;
    __weak IBOutlet UIImageView *imgPinAddress;
    __weak IBOutlet UIScrollView *scrollView;
    __weak IBOutlet UIButton *btnFacebook;
    __weak IBOutlet UIButton *btnTwitter;
    __weak IBOutlet UIButton *btnLinkedin;

}
@property (nonatomic , strong) NSString *memberIDStr;
@property (weak, nonatomic) IBOutlet UILabel *lblName;
@property (weak, nonatomic) IBOutlet UILabel *lblProfession;
@property (weak, nonatomic) IBOutlet UILabel *lblEmail;
@property (weak, nonatomic) IBOutlet UILabel *lblWebTwo;
@property (weak, nonatomic) IBOutlet UILabel *lblWeb;
@property (weak, nonatomic) IBOutlet UILabel *lblMobile;
@property (weak, nonatomic) IBOutlet UILabel *lblPhone;
@property (weak, nonatomic) IBOutlet UILabel *lbltimeJon;
@property (weak, nonatomic) IBOutlet UILabel *lblAddress;
@property (weak, nonatomic) IBOutlet UIImageView *imgSeprator;

@end
