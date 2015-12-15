//
//  NotificationView.h
//  PawnOGram
//
//  Created by Yogesh Rana on 02/09/14.
//  Copyright (c) 2014 Sandeep Singh. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface NotificationView : UIView
+ (id)sharedView;
-(void)showMessage:(NSDictionary *)userinfo;
-(void)jumpApplicationToViewWithInfo:(NSDictionary *)userInfo;
-(void)TappedOnBanner:(UITapGestureRecognizer *)gesture;

@property(nonatomic, retain)NSDictionary *notifInfo;

@end
