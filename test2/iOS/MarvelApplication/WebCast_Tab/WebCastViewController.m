//
//  WebCastViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 12/2/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import "WebCastViewController.h"
#import "WebCastCell.h"
#import "UITableView+DragLoad.h"

@interface WebCastViewController () <UITableViewDragLoadDelegate>
@property (nonatomic) IBOutlet UIButton* revealButtonItem;
@end

@implementation WebCastViewController
@synthesize delegateWebCastClass;

- (void)viewDidLoad {
    
    flag = false;
    [tranningVideoView setHidden:YES];
    
    self.tableView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    
    [self.tableView setDragDelegate:self refreshDatePermanentKey:@"FriendList"];
    self.tableView.showLoadMoreView = YES;
    
    viewBottom.layer.borderWidth = 1.0f;
    viewBottom.layer.borderColor = BoarderColour.CGColor;
    
    [self customSetup];
    
    //For WebCast Module
    recordArray = [[NSMutableArray alloc] init];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    [self webCastList];
    
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(updateBoolValue) name:@"training" object:nil];

    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(updateTrainingVideo) name:@"TrainingVideoPaus" object:nil];

    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Reval View
 * @Modified Date 1/12/2015
 */
#pragma mark Custom Setup
- (void)customSetup
{
    SWRevealViewController *revealViewController = self.revealViewController;
    if ( revealViewController )
    {
        [self.revealButtonItem addTarget:self action:@selector( toggle) forControlEvents:UIControlEventTouchUpInside];
        [self.navigationController.navigationBar addGestureRecognizer:revealViewController.panGestureRecognizer];
    }
}

-(void)toggle {
    if (flag) {
        [self moviePlayPause];
    }
    flag = !flag;
    [self.view endEditing:YES];
    [self.revealViewController revealToggleAnimated:YES];
}

-(void)updateBoolValue {
    flag = !flag;
}

-(void)updateTrainingVideo {
    if (flag) {
        [self moviePlayPause];
        flag = false;
    }
}

// Called when the view is about to made visible. Default does nothing
-(void)viewWillAppear:(BOOL)animated {
    
    [self setNotificationValue];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(setNotificationValue) name:@"referralsCount" object:nil];
    [super viewWillAppear:animated];
}

//Called when the view is dismissed, covered or otherwise hidden. Default does nothing
-(void)viewWillDisappear:(BOOL)animated {
    [[NSNotificationCenter defaultCenter] removeObserver:self name:@"training" object:nil];
    [[NSNotificationCenter defaultCenter] removeObserver:self name:@"referralsCount" object:nil];
    [super viewWillDisappear:animated];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description Here we set the referrals count
 * @Modified Date 1/12/2015
 */
#pragma mark Show Notification Count
-(void)setNotificationValue {
    NSString *notiCountStr =[[AppDelegate currentDelegate] notificationTotalCount];
    if (notiCountStr.integerValue > 99) {
        [self.lblNotiCount setText:@"99+"];
    }
    else {
        if ([notiCountStr isEqualToString:@"0"]) {
            [self.lblNotiCount setText:@""];
        }
        else {
            [self.lblNotiCount setText:notiCountStr];
        }
    }
    
    NSString *userRoleString = [[AppDelegate currentDelegate] notificationReferralsCount];
    if ([userRoleString isEqualToString:@"PARTICIPANT"]) {
        [btnTranningVideo setUserInteractionEnabled:NO];
    }
    else {
        [btnTranningVideo setUserInteractionEnabled:YES];
    }
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for View Notification List
 * @Modified Date 1/12/2015
 */
#pragma mark Get View Notification List
-(IBAction)viewNotificationList:(id)sender {
    if (!CheckStringForNull(self.lblNotiCount.text)) {
        [self performSegueWithIdentifier:@"notification" sender:nil];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used For Select Diffrent Module
 * @Modified Date 1/12/2015
 */
#pragma mark Select Diffrent Module
-(IBAction)selectDiffrentModule:(UIButton *)sender {
    
    flag = false;
    [[NSNotificationCenter defaultCenter] removeObserver:self name:MPMoviePlayerPlaybackDidFinishNotification object:nil];
    
     if (sender.tag == 1) {
         if (player) {
             [timer invalidate];
             timer = nil;
             [player.moviePlayer pause];
             [player.view removeFromSuperview];
             player = nil;
         }
         [tranningVideoView setHidden:YES];
         [btnWebcast setBackgroundImage:[UIImage imageNamed:@"SelectModule"] forState:UIControlStateNormal];
         [btnTranningVideo setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
         self.lblTopTitle.text = @"WEBCAST";
         recordArray = [[NSMutableArray alloc] init];
         [userDefaultManager setObject:@"1" forKey:@"page_no"];
         [self webCastList];
     }
     else {
         flag = true;
         [self.tableView setHidden:YES];
         [norecordView setHidden:YES];
         [tranningVideoView setHidden:NO];
         [btnTranningVideo setBackgroundImage:[UIImage imageNamed:@"SelectModule"] forState:UIControlStateNormal];
         [btnWebcast setBackgroundImage:[UIImage imageNamed:@""] forState:UIControlStateNormal];
         self.lblTopTitle.text = @"TRAINING VIDEO";
         [self performSelector:@selector(getTrainingVideoLink) withObject:nil afterDelay:0.1];
         
     }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Update web cast record from WebCastDetailViewController Class
 * @Modified Date 08/12/2015
 */
#pragma mark WebCast Video
-(void)updateWebCastRecord {
    //For Update WebCast Information From WebCastDetailViewController
    recordArray = [[NSMutableArray alloc] init];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    [self webCastList];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Update web cast record from WebCastDetailViewController Class while webcast video does not exist
 * @Modified Date 08/12/2015
 */
#pragma mark WebCast Video
-(void)updateWebCastRecordWebCastVideoNoExist {
    //For Update WebCast Information From WebCastDetailViewController
    recordArray = [[NSMutableArray alloc] init];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    [self performSelector:@selector(webCastList) withObject:nil afterDelay:0.1];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Get Tranning Video Link
 * @Modified Date 04/12/2015
 */
#pragma mark Get Training Video Link
-(void)getTrainingVideoLink {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(getTrainingVideoLinkDelay) withObject:nil afterDelay:0.1];
}
-(void)getTrainingVideoLinkDelay {
    
    NSString *linkString = @"";
    
    if (isNetworkAvailable) {
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"trainingVideo" controls:@"businessOwners" httpMethod:@"POST" data:nil];
        
        if (parsedJSONToken != nil) {
            linkString = [NSString stringWithFormat:@"%@",[parsedJSONToken valueForKey:@"result"]];
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    
    [self tranningVideoViewDesign:linkString];
    
    [[AppDelegate currentDelegate] removeLoading];

}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Design Tranning Video
 * @Modified Date 04/12/2015
 */
#pragma mark Training Video
- (void)tranningVideoViewDesign:(NSString *)linkStr {
    
    if (player) {
        [timer invalidate];
        timer = nil;
        [player.moviePlayer pause];
        [player.view removeFromSuperview];
        player = nil;
    }
    
    player = [[MPMoviePlayerViewController alloc] initWithContentURL:[NSURL URLWithString:linkStr]];
    player.view.frame = CGRectMake (0,0,tranningVideoView.frame.size.width,tranningVideoView.frame.size.height);
    [player.view setBackgroundColor:[UIColor whiteColor]];
    player.moviePlayer.fullscreen = YES;
    [player.moviePlayer prepareToPlay];
    [player.moviePlayer setRepeatMode:MPMovieRepeatModeNone];
    [player.moviePlayer setControlStyle:MPMovieControlStyleNone];
    [player.moviePlayer play];
    
    playBtn = [[UIButton alloc] initWithFrame:CGRectMake(player.view.frame.size.width/2-25, player.view.frame.size.height/2-25, 50, 50)];
    [playBtn setHidden:YES];
    [playBtn setBackgroundImage:[UIImage imageNamed:@"playTrainingVideo"] forState:UIControlStateNormal];
    [playBtn addTarget:self action:@selector(moviePlayPlay) forControlEvents:UIControlEventTouchUpInside];
    [player.view addSubview:playBtn];
    
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(movieFinishedCallback:) name:MPMoviePlayerPlaybackDidFinishNotification object:player.moviePlayer];
    
    //---play partial screen---
    [tranningVideoView addSubview:player.view];
    
    timer = [NSTimer scheduledTimerWithTimeInterval:15 //Time in seconds
                                     target:self
                                   selector:@selector(moviePlayPause) //Method called when the timer is completed.
                                   userInfo:nil
                                    repeats:YES];
}

-(void)moviePlayPause {
    if (timer) {
        [timer invalidate];
        timer = nil;
    }
    [player.moviePlayer pause];
    [playBtn setHidden:NO];
}

-(void)moviePlayPlay {
    flag = true;
    if (!timer) {
        timer = [NSTimer scheduledTimerWithTimeInterval:15 //Time in seconds
                                                 target:self
                                               selector:@selector(moviePlayPause) //Method called when the timer is completed.
                                               userInfo:nil
                                                repeats:YES];
    }
    [playBtn setHidden:YES];
    [player.moviePlayer play];
}

-(void)movieFinishedCallback:(id)sender {
    if (timer) {
        [timer invalidate];
        timer = nil;
    }
    [playBtn setHidden:YES];
    if (player.moviePlayer && flag) {
        [self watchCompleteVideo];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Thank you for watching the training video." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Get Tranning Video Link
 * @Modified Date 04/12/2015
 */
#pragma mark Get Training Video Link
-(void)watchCompleteVideo {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(watchCompleteVideoDelay) withObject:nil afterDelay:0.1];
}
-(void)watchCompleteVideoDelay {
    
    if (isNetworkAvailable) {
        
        //for Watch Complete Video
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:@"watched",@"trainingVideo",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"trainingVideo" controls:@"businessOwners" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {

        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [[AppDelegate currentDelegate] removeLoading];
    
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
        
        [self hideTable];
        
        if (parsedJSONToken != nil) {
            [self showTable];
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
 * @Description These method are used for Hide Table
 * @Modified Date 31/07/2015
 */
#pragma mark hideTable
-(void)hideTable {
    [norecordView setHidden:NO];
    [self.tableView setHidden:YES];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Show table
 * @Modified Date 31/07/2015
 */
#pragma mark showTable
-(void)showTable {
    [norecordView setHidden:YES];
    [self.tableView setHidden:NO];
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
    return [recordArray count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView1 cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
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
        
        NSDictionary *dict = [recordArray objectAtIndex:indexPath.row];
        
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

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
   
    dataDict = [recordArray objectAtIndex:indexPath.row];
    [self performSegueWithIdentifier:@"webcastDetail" sender:nil];
}

- (CGFloat)tableView:(UITableView *)aTableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    return 60;
}



/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used For go to dashboard From Suggestion Class
 * @Modified Date 1/12/2015
 */
#pragma mark Go To Home Screen From Suggestion Class
-(IBAction)GoToHomeScreen:(id)sender {
    
    flag = false;
    if (player) {
        [timer invalidate];
        timer = nil;
        [player.moviePlayer pause];
        [player.view removeFromSuperview];
        player = nil;
    }
    
    if ([self.delegateWebCastClass respondsToSelector:@selector(callHomeScreen)]) {
        [self.delegateWebCastClass callHomeScreen];
    }
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    
    if ([segue.identifier isEqualToString:@"webcastDetail"]) {
        WebCastDetailViewController *webCastDetailViewController = segue.destinationViewController;
        webCastDetailViewController.webCastDelegate = self;
        webCastDetailViewController.dataDict =  dataDict;
    }
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}


@end
