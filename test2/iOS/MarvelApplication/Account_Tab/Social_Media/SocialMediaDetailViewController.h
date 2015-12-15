//
//  SocialMediaDetailViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 11/26/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
@protocol SocialMediaDetailDelegate <NSObject>

-(void)updateSocialMediaInfo;

@end
@interface SocialMediaDetailViewController : UIViewController {

    __weak IBOutlet UIImageView *imgField;
    __weak IBOutlet UIButton *btnSave;
    __weak IBOutlet UITableView *tableView;
    __weak IBOutlet UILabel *lblTopTitle;
    
    NSMutableArray *socialMediaArray;
    NSMutableArray *selectedRowArray;
    NSString *commonString;
}
@property (nonatomic, strong) UIImage *selectedImage;
@property (nonatomic, strong) UIImage *unSelectedImage;
@property (nonatomic , weak) id<SocialMediaDetailDelegate>socialMediaDelegate;
@property (nonatomic , strong)NSString *checkConditionString;
@end
