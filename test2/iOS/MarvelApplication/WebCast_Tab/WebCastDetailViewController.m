//
//  WebCastDetailViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 12/7/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import "WebCastDetailViewController.h"
#import "HCYoutubeParser.h"
#import "WebCastCell.h"
#import "UITableView+DragLoad.h"

@interface WebCastDetailViewController () <UITableViewDragLoadDelegate>

@end

@implementation WebCastDetailViewController
@synthesize dataDict;
@synthesize webCastDelegate;

- (void)viewDidLoad {
    
    [[AppDelegate currentDelegate] addLoading];
    
     //For WebCast Module
    self.tableView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    [self.tableView setDragDelegate:self refreshDatePermanentKey:@"FriendList"];
    self.tableView.showLoadMoreView = YES;
    recordArray = [[NSMutableArray alloc] init];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    [self.tableView setHidden:YES];
    
    //For Comment
    if (!CheckDeviceFunction()) {
        if (CheckDeviceiPhoneFiveOriPhoneFour()) {
            scrollViewComment.contentSize = CGSizeMake(scrollViewComment.frame.size.width, scrollViewComment.frame.size.height+200);
            self.tableViewComment.frame = CGRectMake(self.tableViewComment.frame.origin.x, self.tableViewComment.frame.origin.y, self.tableViewComment.frame.size.width, self.tableViewComment.frame.size.height+180);
        }
    }
    self.tableViewComment.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    [viewComment setHidden:YES];
    btnPOstComment.layer.cornerRadius = 5.0f;
    imgCommentBG.layer.cornerRadius = 5.0f;
    imgCommentBG.layer.borderWidth = 1.0f;
    imgCommentBG.layer.borderColor = [UIColor lightGrayColor].CGColor;
    
    
    //For Now Playing
    [viewNowPlaying setHidden:NO];
    [self playVideoDetail];
    
    NSString *linkString = [NSString stringWithFormat:@"%@",[dataDict valueForKey:@"link"]];
    [self callYouTubeURL:linkString];


    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(moviePlayerDidExit)
                                                 name:MPMoviePlayerPlaybackDidFinishNotification
                                               object:nil];
    
    
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

-(void)takePlay {
    
    if (player) {
        [player.moviePlayer pause];
        [player.view removeFromSuperview];
        player = nil;
    }
    player = [[MPMoviePlayerViewController alloc] init ];
    if (CheckDeviceFunction()) {
        player.view.frame = CGRectMake (0,0,self.view.frame.size.width,414);
    }
    else {
        player.view.frame = CGRectMake (0,0,self.view.frame.size.width,180);
    }
    player.moviePlayer.fullscreen = YES;
    [player.moviePlayer prepareToPlay];
    [player.moviePlayer setRepeatMode:MPMovieRepeatModeOne];
    [player.moviePlayer setControlStyle:MPMovieControlStyleEmbedded];
    [player.moviePlayer play];
    [scrollViewFirst addSubview:player.view];
}

// Called when the view is dismissed, covered or otherwise hidden. Default does nothing
- (void)viewWillDisappear:(BOOL)animated {
    if (commentTimer) {
        [commentTimer invalidate];
        commentTimer=nil;
    }
    [super viewWillDisappear:animated];
}


-(void)playbackStateChange:(NSNotification*)notification {
    [[AppDelegate currentDelegate] removeLoading];
    [[NSNotificationCenter defaultCenter] removeObserver:self name:MPMoviePlayerLoadStateDidChangeNotification object:nil];
}
/**
 *
 * *************** Movie player exit *****************
 * @author Deepak Kumar.
 *
 **/
- (void)moviePlayerDidExit {
    
    [[NSNotificationCenter defaultCenter] removeObserver:self name:MPMoviePlayerPlaybackDidFinishNotification object:nil];
    
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(moviePlayerDidExit)
                                                 name:MPMoviePlayerPlaybackDidFinishNotification
                                               object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(playbackStateChange:)
                                                 name:MPMoviePlayerLoadStateDidChangeNotification
                                               object:player.moviePlayer];
    
    
    NSString *linkString = [NSString stringWithFormat:@"%@",[dataDict valueForKey:@"link"]];
    [self callYouTubeURL:linkString];
}

- (void)callYouTubeURL:(NSString *)urlLink
{
    
    NSURL *url = [NSURL URLWithString:urlLink];
    
    [HCYoutubeParser thumbnailForYoutubeURL:url thumbnailSize:YouTubeThumbnailDefaultHighQuality completeBlock:^(UIImage *image, NSError *error) {
        
        if (!error) {
            
            [HCYoutubeParser h264videosWithYoutubeURL:url completeBlock:^(NSDictionary *videoDictionary, NSError *error) {
                
                NSDictionary *qualities = videoDictionary;
                
                NSString *URLString = nil;
                if ([qualities objectForKey:@"small"] != nil) {
                    URLString = [qualities objectForKey:@"small"];
                }
                else if ([qualities objectForKey:@"live"] != nil) {
                    URLString = [qualities objectForKey:@"live"];
                }
                else {
                    [[[UIAlertView alloc] initWithTitle:@"Error" message:@"Couldn't find youtube video" delegate:nil cancelButtonTitle:@"Close" otherButtonTitles: nil] show];
                    return;
                }
                _urlToLoad = [NSURL URLWithString:URLString];
                
                
                [self urlLoadintoPlayer];
                
            }
             ];}
        else {
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[error localizedDescription] delegate:nil cancelButtonTitle:@"Dismiss" otherButtonTitles:nil];
            [alert show];
        }
    }];
}

-(void)urlLoadintoPlayer {
    [self takePlay];
    player.moviePlayer.contentURL = _urlToLoad;
    [player.moviePlayer play];
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(playbackStateChange:)
                                                 name:MPMoviePlayerLoadStateDidChangeNotification
                                               object:player.moviePlayer];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 07/12/2015
 */
#pragma mark Back Screen
-(IBAction)backScreen:(id)sender {
    if (commentTimer) {
        [commentTimer invalidate];
        commentTimer=nil;
    }
    [self.navigationController popViewControllerAnimated:NO];
    if ([self.webCastDelegate respondsToSelector:@selector(updateWebCastRecord)]) {
        [self.webCastDelegate updateWebCastRecord];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used For Select Diffrent Module
 * @Modified Date 07/12/2015
 */
#pragma mark Select Diffrent Module
-(IBAction)selectDiffrentModule:(UIButton *)sender {
    
    if (commentTimer) {
        [commentTimer invalidate];
        commentTimer=nil;
    }
    scrollViewFirst.contentSize = CGSizeMake(scrollViewFirst.frame.size.width, scrollViewFirst.frame.size.height);
    
    if (sender.tag == 1) {//webcastCellDetail
        [viewComment setHidden:YES];
        [self.tableView setHidden:YES];
        [viewNowPlaying setHidden:NO];
        [btnNowPlaying setBackgroundImage:[UIImage imageNamed:@"SelectModule"] forState:UIControlStateNormal];
        [btnUpNext setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
        [btnComment setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
        [self playVideoDetail];
    }
    else if (sender.tag == 2) {
        [self.tableView setHidden:NO];
        [viewNowPlaying setHidden:YES];
        [viewComment setHidden:YES];
        [btnNowPlaying setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
        [btnUpNext setBackgroundImage:[UIImage imageNamed:@"SelectModule"] forState:UIControlStateNormal];
        [btnComment setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
        
        recordArray = [[NSMutableArray alloc] init];
        [userDefaultManager setObject:@"1" forKey:@"page_no"];
        [self webCastList];
    }
    else {
        [self updatecomment];
        [self performSelector:@selector(tableViewScrollPositionTop) withObject:nil afterDelay:0.1];
        [self getMessageComment];
        
        [viewComment setHidden:NO];
        [self.tableView setHidden:YES];
        [viewNowPlaying setHidden:YES];
        [btnNowPlaying setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
        [btnUpNext setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
        [btnComment setBackgroundImage:[UIImage imageNamed:@"SelectModule"] forState:UIControlStateNormal];
    }
}


/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Playing Video
 * @Modified Date 24/10/2015
 */
#pragma mark Play video Detail
-(void)playVideoDetail {

    //For Title
    lblVideoTitle.frame = CGRectMake(lblVideoTitle.frame.origin.x, 5, scrollNowPlaying.frame.size.width-20, lblVideoTitle.frame.size.height);
    NSString *titleString = [NSString stringWithFormat:@"%@",[dataDict valueForKey:@"title"]];
    lblVideoTitle.text = titleString;
    [lblVideoTitle sizeToFit];
    
    //For Date
    NSString *dateString = [NSString stringWithFormat:@"%@",[dataDict valueForKey:@"created"]];
    dateString = ConvertDateStringFormatWebCast(dateString);
    NSArray *array = [dateString componentsSeparatedByString:@"@"];
    NSString *strOne = [array objectAtIndex:0];
    NSString *strTwo = [array objectAtIndex:1];
    NSMutableAttributedString * string =
    [[NSMutableAttributedString alloc] initWithString:dateString];
    [string addAttribute:NSForegroundColorAttributeName value:[UIColor lightGrayColor] range:NSMakeRange(0,strOne.length)];
    [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(0,strOne.length)];
    [string addAttribute:NSForegroundColorAttributeName value:orangeColour range:NSMakeRange(strOne.length,1)];
    [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(strOne.length,1)];
    [string addAttribute:NSForegroundColorAttributeName value:[UIColor lightGrayColor] range:NSMakeRange(strOne.length+1,strTwo.length)];
    [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(strOne.length+1,strTwo.length)];
    
    lblDate.attributedText = string;
    
    imgDateIcon.frame = CGRectMake(imgDateIcon.frame.origin.x, lblVideoTitle.frame.origin.y+lblVideoTitle.frame.size.height+8, imgDateIcon.frame.size.width, imgDateIcon.frame.size.height);
    lblDate.frame = CGRectMake(lblDate.frame.origin.x, lblVideoTitle.frame.origin.y+lblVideoTitle.frame.size.height+5, lblDate.frame.size.width, lblDate.frame.size.height);
    imgSeprate.frame = CGRectMake(imgSeprate.frame.origin.x, lblDate.frame.origin.y+lblDate.frame.size.height+5, imgSeprate.frame.size.width, imgSeprate.frame.size.height);
    lblHeading.frame = CGRectMake(lblHeading.frame.origin.x, imgSeprate.frame.origin.y+imgSeprate.frame.size.height+5, lblHeading.frame.size.width, lblHeading.frame.size.height);
    textViewDetail.frame = CGRectMake(textViewDetail.frame.origin.x, lblHeading.frame.origin.y+lblHeading.frame.size.height+5, scrollNowPlaying.frame.size.width-20, textViewDetail.frame.size.height);
    
    //For Description
    NSString *descriptionString = [NSString stringWithFormat:@"%@",[dataDict valueForKey:@"description"]];
    textViewDetail.text = descriptionString;
    textViewDetail.backgroundColor = [UIColor clearColor];
    textViewDetail.contentInset = UIEdgeInsetsMake(-7.0,0.0,0,0.0);
    [textViewDetail sizeToFit];
    
    scrollNowPlaying.contentSize = CGSizeMake(scrollNowPlaying.frame.size.width, textViewDetail.frame.origin.y+textViewDetail.frame.size.height+20);

}


/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Get Video List For Web Cast
 * @Modified Date 09/09/2015
 */
#pragma mark WebCast
- (void)webCastList {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(webCastListDelay) withObject:nil afterDelay:0.1];
}
-(void)webCastListDelay {
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:pageCountStr,@"page_no",recordPerPage,@"record_per_page",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"webcast" controls:@"events" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalWebcast"];
            [userDefaultManager setObject:pageNoString forKey:@"page_no"];
            [userDefaultManager setObject:totalRecString forKey:@"totalRecord"];
            NSArray *array = [parsedJSONToken valueForKey:@"result"];
            for (NSDictionary *dictionary in array){
                [recordArray addObject:dictionary];
            }
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [self.tableView reloadData];
    [[AppDelegate currentDelegate] removeLoading];
}


/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Finish load more
 * @Modified Date 9/07/2015
 */
#pragma mark Finish Load More
- (void)finishLoadMoreFunction {
    [self.tableView finishLoadMore];
    if (isNetworkAvailable) {
        NSString *pageString = [userDefaultManager valueForKey:@"page_no"];
        NSString *totalRecString = [userDefaultManager valueForKey:@"totalRecord"];
        if (recordArray.count < totalRecString.intValue) {
            int pageCount = pageString.intValue;
            pageCount = pageCount+1;
            NSString *pageCountStr = [NSString stringWithFormat:@"%d",pageCount];
            [userDefaultManager setObject:pageCountStr forKey:@"page_no"];
            [self webCastList];
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
}

- (void)dragTableDidTriggerLoadMore:(UITableView *)tableView
{
    //send load more request(generally network request) here
    
    [self performSelector:@selector(finishLoadMoreFunction) withObject:nil afterDelay:2];
}

- (void)dragTableLoadMoreCanceled:(UITableView *)tableView
{
    //cancel load more request(generally network request) here
    
    [NSObject cancelPreviousPerformRequestsWithTarget:self selector:@selector(finishLoadMore) object:nil];
}


#pragma marl - UITableView Data Source
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    if (tableView == self.tableView) {
        return [recordArray count];
    }
    return [commentArray count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView1 cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (tableView1 == self.tableView) {
        static NSString *PlaceholderCellIdentifier = @"webcastcell";
        
        WebCastCell*cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
        cell.backgroundColor = [UIColor clearColor];
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        if (cell == nil) {
            cell = [[WebCastCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
        }
        
        if (recordArray.count) {
            
            cell.imgThumNil.image = nil;
            cell.imgThumNil.crossfadeDuration = 0.0f;
            
            NSString *videoIdStr  = [NSString stringWithFormat:@"%@",[dataDict valueForKey:@"id"]];
            
            NSDictionary *dict = [recordArray objectAtIndex:indexPath.row];
            
            NSString *videoIdStr2  = [NSString stringWithFormat:@"%@",[dict valueForKey:@"id"]];
            if ([videoIdStr isEqualToString:videoIdStr2]) {
                [cell.imgSoundWaves setHidden:NO];
            }
            else {
                [cell.imgSoundWaves setHidden:YES];
            }
            
            //For Title
            NSString *titleString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"title"]];
            cell.lblVideoTitle.text = titleString;
            
            //For Date
            NSString *dateString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"created"]];
            dateString = ConvertDateStringFormatWebCast(dateString) ;
            cell.lblVideoDate.text = dateString;
            
            //For Thumbnil Image
            NSString *thumbnilString = [NSString stringWithFormat:@"%@",[dict valueForKey:@"thumbnail"]];
            cell.imgThumNil.imageURL = [NSURL URLWithString:thumbnilString];
            
        }
        
        return cell;

    }
    else {
    
        static NSString *PlaceholderCellIdentifier = @"PlaceholderCell";
        
        UITableViewCell *cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
        
        if (cell == nil)
        {
            cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
            cell.backgroundColor = [UIColor clearColor];
            cell.selectionStyle = UITableViewCellSelectionStyleNone;
            
            AsyncImageView *iconImageView = [[AsyncImageView alloc] initWithFrame:CGRectMake(10, 10, 30, 30)];
            [iconImageView setBackgroundColor:[UIColor clearColor]];
            [iconImageView setTag:1];
            iconImageView.layer.cornerRadius = 15.0f;
            [cell.contentView addSubview:iconImageView];
            
            UILabel *lblComment = [[UILabel alloc] initWithFrame:CGRectMake(50, 0, self.tableViewComment.frame.size.width-55, 30)];
            [lblComment setBackgroundColor:[UIColor clearColor]];
            [lblComment setTextColor:[UIColor lightGrayColor]];
            [lblComment setNumberOfLines:0];
            [lblComment setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
            [lblComment setTag:2];
            [cell.contentView addSubview:lblComment];
            
            UILabel *lblDateComment = [[UILabel alloc] initWithFrame:CGRectMake(50, lblComment.frame.size.height+5, cell.frame.size.width-55, 20)];
            [lblDateComment setBackgroundColor:[UIColor clearColor]];
            [lblDateComment setTextColor:[UIColor lightGrayColor]];
            [lblDateComment setFont:[UIFont fontWithName:@"Graphik-Regular" size:12.0f]];
            [lblDateComment setTag:3];
            [cell.contentView addSubview:lblDateComment];
        }
        
        NSDictionary *dict = [commentArray objectAtIndex:indexPath.row];
        //For Image
        NSString *imageURLStr = [dict valueForKey:@"commented_by_profile_image"];
        AsyncImageView *commentImage = (AsyncImageView*)[cell viewWithTag:1];
        [commentImage setImageURL:[NSURL URLWithString:imageURLStr]];
        commentImage.layer.cornerRadius = 15.0f;
        //For User
        NSString *nameStr = [dict valueForKey:@"commented_by"];
        NSString *commentedStr = [NSString stringWithFormat:@"%@ %@",[dict valueForKey:@"commented_by"],[dict valueForKey:@"comments"]];
        UILabel *lblCommen = (UILabel*)[cell viewWithTag:2];
        NSMutableAttributedString * stringVal = [[NSMutableAttributedString alloc] initWithString:commentedStr];
        [stringVal addAttribute:NSForegroundColorAttributeName value:orangeColour range:NSMakeRange(0,nameStr.length)];
        [stringVal addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(0,nameStr.length)];
        [stringVal addAttribute:NSForegroundColorAttributeName value:[UIColor lightGrayColor] range:NSMakeRange(nameStr.length,commentedStr.length-nameStr.length)];
        [stringVal addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(nameStr.length,commentedStr.length-nameStr.length)];
        lblCommen.attributedText = stringVal;
        
        
        //For Date
        UILabel *lblSetDate = (UILabel*)[cell viewWithTag:3];
        
        CGSize size = [self MaxHeighForTextInRow:commentedStr width:cell.frame.size.width-55];
        lblCommen.frame = CGRectMake(50, 0, self.tableViewComment.frame.size.width-55, size.height+5);
        lblSetDate.frame = CGRectMake(50, lblCommen.frame.size.height+5, cell.frame.size.width-55, 20);
        
        NSString *dateStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"created"]];
        if (CheckStringForNull(dateStr)) {
            dateStr = @"";
        }
        else {
            dateStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"created"]];
            dateStr = ConvertDateStringFormatWebCast(dateStr);
        }
        NSString *finalDateStr = @"Posted on ";
        finalDateStr = [finalDateStr stringByAppendingString:dateStr];
        lblSetDate.text = finalDateStr;
        
        return cell;

    }
    
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {

    if (tableView == self.tableView) {
        [[NSNotificationCenter defaultCenter] removeObserver:self name:MPMoviePlayerLoadStateDidChangeNotification object:nil];
        [[AppDelegate currentDelegate] addLoading];
        dataDict = [recordArray objectAtIndex:indexPath.row];
        [self moviePlayerDidExit];
        [self.tableView reloadData];
    }
}

- (CGFloat)tableView:(UITableView *)aTableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    if (aTableView == self.tableView) {
        return 60;
    }
    else {
        NSDictionary *dict = [commentArray objectAtIndex:indexPath.row];
        NSString *commentedStr = [NSString stringWithFormat:@"%@ %@",[dict valueForKey:@"commented_by"],[dict valueForKey:@"comments"]];
        CGSize size = [self MaxHeighForTextInRow:commentedStr width:self.tableViewComment.frame.size.width-55];

        return (size.height+35);
    }
    return 0;
}

//For calculate the string Height And Width
#pragma mark Calculate the Height And Width
-(CGSize)MaxHeighForTextInRow:(NSString *)RowText width:(float)UITextviewWidth {
    
    CGSize constrainedSize = CGSizeMake(UITextviewWidth, CGFLOAT_MAX);
    
    NSDictionary *attributesDictionary = [NSDictionary dictionaryWithObjectsAndKeys:
                                          [UIFont fontWithName:@"Graphik-Regular" size:15.0], NSFontAttributeName,
                                          nil];
    
    NSMutableAttributedString *string = [[NSMutableAttributedString alloc] initWithString:RowText attributes:attributesDictionary];
    
    CGRect requiredHeight = [string boundingRectWithSize:constrainedSize options:NSStringDrawingUsesLineFragmentOrigin context:nil];
    
    if (requiredHeight.size.width > UITextviewWidth) {
        requiredHeight = CGRectMake(0, 0, UITextviewWidth, requiredHeight.size.height);
    }
    
    return requiredHeight.size;
}


#pragma mark Comments
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Start Timer
 * @Modified Date 08/12/2015
 */
#pragma mark Start Timer
-(void)getMessageComment {
    if (commentTimer) {
        [commentTimer invalidate];
        commentTimer=nil;
    }
    commentTimer = [NSTimer scheduledTimerWithTimeInterval:5 target:self selector:@selector(updatecomment) userInfo:nil repeats:YES];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for tableView Scroll Position Top
 * @Modified Date 08/12/2015
 */
#pragma mark tableViewScrollPosition
-(void)tableViewScrollPositionTop {
    if (commentArray.count>0) {
        NSIndexPath *indexPath = [NSIndexPath indexPathForRow:0 inSection:0];
        [self.tableViewComment scrollToRowAtIndexPath:indexPath
                                     atScrollPosition:UITableViewScrollPositionTop
                                             animated:YES];
        [self.tableViewComment reloadData];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Update Notification
 * @Modified Date 08/12/2015
 */
#pragma mark Update Comment
-(void)updatecomment {
    
    if (netWorkNotAvilable) {
        
        //For Get Comment List
        NSString *detailPageStr = [NSString stringWithFormat:@"%@",[dataDict valueForKey:@"id"]];
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:detailPageStr,@"webcastId",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequestNotification:@"" methodHeader:@"webcastCommentDetail" controls:@"events" httpMethod:@"POST" data:data];
        
        commentArray = [[NSMutableArray alloc] init];
        [self.tableViewComment setHidden:YES];
        if (parsedJSONToken != nil) {
            commentArray = [parsedJSONToken valueForKey:@"result"];
        }
    }
    
    if (commentArray.count) {
        [lblNoComments setHidden:YES];
        [self.tableViewComment setHidden:NO];
    }
    else {
        [lblNoComments setHidden:NO];
        [self.tableViewComment setHidden:YES];
    }
    
    [self.tableViewComment reloadData];
}


/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Send comment
 * @Modified Date 08/12/2015
 */
#pragma mark Send Comment
- (IBAction)sendComment:(id)sender {
    [[AppDelegate currentDelegate] addLoading];
    [self.view endEditing:YES];
    [self performSelector:@selector(sendCommentDelay) withObject:nil afterDelay:0.1];
}
- (void)sendCommentDelay {
    if (isNetworkAvailable) {
        
        NSString *commentString = [textViewComment text];
        
        if (CheckStringForNull(commentString)) {
            [[AppDelegate currentDelegate] removeLoading];
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Comment field cannot be left blank." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            return;
        }
        if (commentString.length > 300){
            [[AppDelegate currentDelegate] removeLoading];
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Only 300 characters allowed." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            return;
            
        }
        
        NSString *detailPageStr = [NSString stringWithFormat:@"%@",[dataDict valueForKey:@"id"]];
    
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:detailPageStr,@"webcastId",commentString,@"comment",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"webcastAddComment" controls:@"events" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            
            NSString *stringCode = [NSString stringWithFormat:@"%@",[parsedJSONToken valueForKey:@"code"]];
            if ([stringCode isEqualToString:@"404"]) {
                [[AppDelegate currentDelegate] removeLoading];
                if (commentTimer) {
                    [commentTimer invalidate];
                    commentTimer=nil;
                }
                [self.navigationController popViewControllerAnimated:NO];
                if ([self.webCastDelegate respondsToSelector:@selector(updateWebCastRecordWebCastVideoNoExist)]) {
                    [self.webCastDelegate updateWebCastRecordWebCastVideoNoExist];
                }
                return;
            }
            
            textViewComment.text = @"";
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:[parsedJSONToken valueForKey:@"message"] delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            
            [self updatecomment];
            if (commentArray.count) {
                NSIndexPath *indexPath = [NSIndexPath indexPathForRow:0 inSection:0];
                [self.tableViewComment scrollToRowAtIndexPath:indexPath
                                 atScrollPosition:UITableViewScrollPositionTop
                                         animated:YES];
            }
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [[AppDelegate currentDelegate] removeLoading];
}


- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
