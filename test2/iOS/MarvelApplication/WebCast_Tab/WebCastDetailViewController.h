//
//  WebCastDetailViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 12/7/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MediaPlayer/MediaPlayer.h>

@protocol WebCastDetailDelegate <NSObject>
-(void)updateWebCastRecord;
-(void)updateWebCastRecordWebCastVideoNoExist;
@end

@interface WebCastDetailViewController : UIViewController {

    __weak IBOutlet TPKeyboardAvoidingScrollView *scrollViewFirst;
    MPMoviePlayerViewController *player;
    NSURL *_urlToLoad;
    //For Controll
    __weak IBOutlet UIView *viewControll;
    __weak IBOutlet UIScrollView *scrollControll;
    __weak IBOutlet UIButton *btnNowPlaying;
    __weak IBOutlet UIButton *btnUpNext;
    __weak IBOutlet UIButton *btnComment;
    
    //For Now Playing
    __weak IBOutlet UIView *viewNowPlaying;
    __weak IBOutlet UIScrollView *scrollNowPlaying;
    __weak IBOutlet UILabel *lblVideoTitle;
    __weak IBOutlet UIImageView *imgDateIcon;
    __weak IBOutlet UILabel *lblDate;
    __weak IBOutlet UIImageView *imgSeprate;
    __weak IBOutlet UILabel *lblHeading;
    __weak IBOutlet UITextView *textViewDetail;
    
    NSMutableArray *recordArray;
    
    // For Comment
    __weak IBOutlet UIView *viewComment;
     __weak IBOutlet TPKeyboardAvoidingScrollView *scrollViewComment;
    __weak IBOutlet UILabel *lblNoComments;
    __weak IBOutlet UIImageView *imgCommentBG;
    __weak IBOutlet UITextView *textViewComment;
    __weak IBOutlet UIButton *btnPOstComment;
    NSTimer *commentTimer;
    NSMutableArray *commentArray;
    
}
@property (nonatomic , weak) id<WebCastDetailDelegate>webCastDelegate;
@property (weak, nonatomic) IBOutlet UITableView *tableView;
@property (weak, nonatomic) IBOutlet UITableView *tableViewComment;
@property (nonatomic , strong) NSDictionary *dataDict;
@end
