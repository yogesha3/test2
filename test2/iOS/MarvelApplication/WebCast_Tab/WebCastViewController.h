//
//  WebCastViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 12/2/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MediaPlayer/MediaPlayer.h>
#import "WebCastDetailViewController.h"

@protocol WebCastViewControllerDelegate <NSObject>

-(void)callHomeScreen;

@end


@interface WebCastViewController : UIViewController <WebCastDetailDelegate> {

    __weak IBOutlet UIView *norecordView;
    __weak IBOutlet UIView *viewBottom;
    __weak IBOutlet UIScrollView *contentScroll;
    __weak IBOutlet UIButton *btnWebcast;
    __weak IBOutlet UIButton *btnTranningVideo;
    
    NSMutableArray *recordArray;
    __weak IBOutlet UIView *tranningVideoView;
    MPMoviePlayerViewController *player;
    UIButton *playBtn;
    NSTimer *timer;
    BOOL flag;
    NSDictionary *dataDict;
}
@property (weak, nonatomic) IBOutlet UILabel *lblTopTitle;
@property (weak, nonatomic) IBOutlet UITableView *tableView;
@property (nonatomic , weak) id<WebCastViewControllerDelegate>delegateWebCastClass;
@property (weak, nonatomic) IBOutlet UILabel *lblNotiCount;
@end
