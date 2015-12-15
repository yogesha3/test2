//
//  ConnectionManager.h
//  OpenSchoolEport
//
//  Created by Rahul Tamrakar on 4/26/13.
//  Copyright (c) 2013 A3Logics(I) Ltd. All rights reserved.
//

#import <Foundation/Foundation.h>

@protocol ConnectionManagerDelegate <NSObject>

@optional
-(void)receivedResponse:(id)response ;

@end

@interface ConnectionManager : NSObject{
    NSURLConnection *connection;
    NSMutableData *receivedData;
    id<ConnectionManagerDelegate>delegate;
    
    UIAlertView *netWorkNotAvailableAlert;
}

@property(nonatomic,strong) id<ConnectionManagerDelegate> delegate;
@property(nonatomic,strong) NSString *serviceURLHost;
@property(nonatomic,strong) NSString *hashKey;

//Method for Singleton Object
+ (ConnectionManager *) sharedManager;
//For set the gradient color
-(void)setBoarderColorRed:(UIView*)sender;
-(void)setBoarderColorGray:(UIView*)sender;
-(void)setBoarderColorClear:(UIView *)sender;
//For get responce
-(id)parseResponse:(NSData*)jsonData;
//Convert string into url string
-(NSString *)encodeStringToUrlString:(NSString *)str;
-(id)sendSynchronousRequest:(NSString*)paramStr methodHeader:(NSString *)methodHeader controls:(NSString *)control httpMethod:(NSString*)method;
-(id)sendSynchronousRequestNotification:(NSString*)paramStr methodHeader:(NSString *)methodHeader controls:(NSString *)control httpMethod:(NSString*)method data:(NSData*)data;
-(id)sendSynchronousRequest:(NSString*)paramStr methodHeader:(NSString *)methodHeader controls:(NSString *)control httpMethod:(NSString*)method data:(NSData*)data;
-(id)sendSynchronousRequestLogin:(NSString*)paramStr methodHeader:(NSString *)methodHeader controls:(NSString *)control httpMethod:(NSString*)method data:(NSData*)data;
-(void)sendAsynchronousRequest:(NSString*)paramStr httpMethod:(NSString*)method;

@end
