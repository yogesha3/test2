//
//  UIPlaceHolderTextView.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/30/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface UIPlaceHolderTextView : UITextView

@property (nonatomic, retain) NSString *placeholder;
@property (nonatomic, retain) UIColor *placeholderColor;

-(void)textChanged:(NSNotification*)notification;

@end
