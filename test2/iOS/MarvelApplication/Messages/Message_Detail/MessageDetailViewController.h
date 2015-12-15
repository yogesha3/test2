//
//  MessageDetailViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 9/22/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "UIPlaceHolderTextView.h"
@protocol messageDetailDelegate <NSObject>

-(void)updateStatusReadUnread;
-(void)updateStatusReadUnreadGetUpdateFromServer;

@end
@interface MessageDetailViewController : UIViewController {
    __weak IBOutlet TPKeyboardAvoidingScrollView *scrollView;
    __weak IBOutlet UIView *viewTop;
    __weak IBOutlet UIImageView *imgAttach;
    __weak IBOutlet UILabel *lblName;
    __weak IBOutlet UILabel *lblTeamMember;
    __weak IBOutlet UIButton *expendBtn;
    __weak IBOutlet UILabel *lblDate;
    __weak IBOutlet UIView *viewMessBody;
    __weak IBOutlet UILabel *lblSubject;
    __weak IBOutlet UIWebView *webView;
    __weak IBOutlet UITextView *textViewMessageBody;
    __weak IBOutlet UIView *viewAttach;
    __weak IBOutlet UILabel *lblAttach;
    __weak IBOutlet UIView *viewChat;
    __weak IBOutlet UILabel *lblNoComment;
    __weak IBOutlet UITableView *tableView;
    __weak IBOutlet UIImageView *imgTextView;
    __weak IBOutlet UIPlaceHolderTextView *textViewComment;
    __weak IBOutlet UIButton *btnPost;
    NSMutableArray *commentArray;
    NSArray *filearray;
    NSString *docViewURLString;
    NSTimer *commentTimer;
    NSString *sendMailTo;
    CGSize sizeTeam;
    
}
@property (nonatomic , strong) NSString *messageIDStr;
@property (nonatomic , weak) id<messageDetailDelegate>delegateMessDetail;
@end
