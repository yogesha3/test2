//
//  NotificationView.m
//  PawnOGram
//
//  Created by Yogesh Rana on 02/09/14.
//  Copyright (c) 2014 Sandeep Singh. All rights reserved.
//

#import "NotificationView.h"
#import "AppDelegate.h"
//#import "FanCelebProfileViewController.h"
//#import "LandingPageViewController.h"
@implementation NotificationView
@synthesize notifInfo;
- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        // Initialization code
    }
    return self;
}
+ (id)sharedView
{
    static NotificationView* theSignleton = nil;
    
    @synchronized([NotificationView class])
    {
        if (theSignleton == nil)
        {
            /**
             *
             * creating banner view.
             * @author Yogesh Singh.
             *
             **/
            
            
            theSignleton = [[NotificationView alloc] init];
            
            UIView *bgV=[[UIView alloc]initWithFrame:CGRectMake(0, 0, [UIScreen mainScreen].bounds.size.width, 44)];
            bgV.tag=141414;
            bgV.backgroundColor=[UIColor blackColor];
            bgV.alpha=0.7;
            [theSignleton addSubview:bgV];
            
            
            UILabel *labelMsg=[[UILabel alloc]initWithFrame:CGRectMake(10, 5, 250, 35)];
            labelMsg.tag=112233;
            labelMsg.font=[UIFont systemFontOfSize:12];
            labelMsg.numberOfLines=4;
            labelMsg.textColor=[UIColor whiteColor];
            [theSignleton addSubview:labelMsg];
            
            UIButton *closeBtn=[UIButton buttonWithType:UIButtonTypeRoundedRect];
            
            closeBtn.frame=CGRectMake(270, 10, 50, 20);
            
            [closeBtn setTitle:@"Close" forState:UIControlStateNormal];
            closeBtn.tag=112244;
            [closeBtn.titleLabel setFont:[UIFont systemFontOfSize:13]];
            [closeBtn setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
            [theSignleton addSubview:closeBtn];
            
        }
    }
    return theSignleton;
}
-(void)showMessage:(NSDictionary *)userinfo
{
    [self setBackgroundColor:[UIColor whiteColor]];
    notifInfo=userinfo;
    UILabel *lblMsg=(UILabel *)[self viewWithTag:112233];
    
    
    self.frame=CGRectMake(0, -44, [UIScreen mainScreen].bounds.size.width, 44);
    [[self viewWithTag:141414] setFrame:CGRectMake(0, 0, [UIScreen mainScreen].bounds.size.width, 44)];
    
    if (CheckDeviceFunction()) {
        [[self viewWithTag:112244] setFrame:CGRectMake(708, 10, 50, 20)];
        lblMsg.frame = CGRectMake(10, 5, 500, 35);
    }
    else {
        lblMsg.frame=CGRectMake(10, 3, 250, 38);
        [[self viewWithTag:112244] setFrame:CGRectMake(265, 10, 50, 30)];
    }
    
    lblMsg.text=[[userinfo objectForKey:@"aps" ] objectForKey:@"alert"];
    UIButton *closeBtn=(UIButton *)[self viewWithTag:112244];
    [closeBtn addTarget:self action:@selector(closeBtnTapped) forControlEvents:UIControlEventTouchUpInside];
    //UIView *bgV=[self viewWithTag:141414];
    //UITapGestureRecognizer *tap=[[UITapGestureRecognizer alloc]initWithTarget:self action:@selector(TappedOnBanner:)];
    //[bgV addGestureRecognizer:tap];
    
    
    AppDelegate *delegate=[AppDelegate currentDelegate];
    UINavigationController *navi=(UINavigationController *)[[delegate window] rootViewController];
    [navi.view addSubview:self];
    [navi.view bringSubviewToFront:self];
    
    [UIView animateWithDuration:0.4
                          delay:0.0
                        options: UIViewAnimationOptionCurveEaseOut
                     animations:^{
                         self.frame=CGRectMake(0, 0, self.frame.size.width, self.frame.size.height);
                         
                         [self performSelector:@selector(hideWithAnimation) withObject:nil afterDelay:4.0];
                     }
                     completion:^(BOOL finished){
                         
                     }];
}

-(void)jumpApplicationToViewWithInfo:(NSDictionary *)userInfo;
{

    notifInfo=userInfo;

}

-(void)hideWithAnimation
{
    
     [UIView animateWithDuration:0.4
     delay:0.0
     options: UIViewAnimationOptionCurveEaseOut
     animations:^{
     self.frame=CGRectMake(0, -44, self.frame.size.width, self.frame.size.height);
     
     }
     completion:^(BOOL finished){
     
     [self removeFromSuperview];
     
     }];
}
-(void)closeBtnTapped
{
    
    [UIView animateWithDuration:0.2
                          delay:0.0
                        options: UIViewAnimationOptionCurveEaseOut
                     animations:^{
                         self.frame=CGRectMake(0, -44, self.frame.size.width, self.frame.size.height);
                                              }
                     completion:^(BOOL finished){
                         [self removeFromSuperview];
                     }];
    
}

-(void)TappedOnBanner:(UITapGestureRecognizer *)gesture
{
    /*
    self.frame=CGRectMake(0, -44, [UIScreen mainScreen].bounds.size.width, 44);
    int notCatID=[[[notifInfo objectForKey:@"aps"] objectForKey:@"type"] intValue];
  
   if(notCatID==5 || notCatID==16 || notCatID==6 || notCatID==49)
   {
       
   UIStoryboard  *storyboard = [UIStoryboard storyboardWithName:@"Main" bundle:[NSBundle mainBundle]];

                AppDelegate *delegate=[AppDelegate currentDelegate];
                MFSideMenuContainerViewController *RootVC=(MFSideMenuContainerViewController *)[[delegate window] rootViewController];
                UINavigationController *navi= RootVC.centerViewController;
                
       [RootVC.menuContainerViewController setMenuState:MFSideMenuStateClosed];

                
                 LandingPageViewController *landing=[storyboard instantiateViewControllerWithIdentifier:@"LandingPageViewController"];
                
                FanCelebProfileViewController *celebPro=[storyboard instantiateViewControllerWithIdentifier:@"FanCelebProfileViewController"];
                
                [[ConnectionManager sharedManager] addLoading];
                NSString *urlStr = [NSString stringWithFormat:@"celebrity/index/%@",[[notifInfo objectForKey:@"aps"] objectForKey:@"redirect_id"]];
                NSData *responseData = [[ConnectionManager sharedManager] sendSynchronousRequest:urlStr httpMethod:@"GET" data:nil];
                [[ConnectionManager sharedManager] removeLoading];

                if (responseData == nil) {
                    return;
                }
                NSError *e = nil;
                NSDictionary *jsonDict = [NSJSONSerialization JSONObjectWithData: responseData options: NSJSONReadingMutableContainers error: &e];
                if (!jsonDict) {
                    NSLog(@"Error parsing JSON: %@", e);
                } else {
                    if ([[jsonDict valueForKey:@"code"] integerValue] == 200) {
                        NSArray *arrCauses = [jsonDict valueForKey:@"data"];
                        NSDictionary *userdict=[arrCauses objectAtIndex:0];
                        celebPro.dicCelebrity=userdict;
                        if (![[NSUserDefaults standardUserDefaults] boolForKey:@"isUserSignIn"]) {
                            [navi setViewControllers:@[landing] animated:YES];

                        }
                        else
                        {
                            [navi setViewControllers:@[landing,celebPro] animated:YES];

                        }
                        

                    }
                }
    }
     */
}

@end
