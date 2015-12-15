//
//  ConnectionManager.m
//  OpenSchoolEport
//
//  Created by Rahul Tamrakar on 4/26/13.
//  Copyright (c) 2013 A3Logics(I) Ltd. All rights reserved.
//
//

#import "ConnectionManager.h"

@implementation ConnectionManager


@synthesize delegate;
@synthesize serviceURLHost;
@synthesize hashKey;

#pragma mark Singleton Methods
static ConnectionManager *sharedManager = nil;

+ (ConnectionManager *) sharedManager {
    static dispatch_once_t onceToken;
    dispatch_once(&onceToken, ^{
        sharedManager = [[self alloc] init];
    });
    return sharedManager;
}

- (id)init {
    if (self = [super init]) {
        netWorkNotAvailableAlert=nil;
    }
    return self;
}

/*
 * @Auther Deepak chauhan
 * @Parm alertView , buttonIndex
 * @Description These method are used for Called when a button is clicked. The view will be automatically dismissed after this call returns
 * @Modified Date 9/07/2015
 */
#pragma mark Alert Delegate
- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {
    if (alertView==netWorkNotAvailableAlert) {
        netWorkNotAvailableAlert=nil;
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm paramStr , method , data
 * @Description These method are used for Send Synchronous Request With Data
 * @Modified Date 9/07/2015
 */
#pragma mark Send Synchronous Request With Data
-(id)sendSynchronousRequestLogin:(NSString*)paramStr methodHeader:(NSString *)methodHeader controls:(NSString *)control httpMethod:(NSString*)method data:(NSData*)data{
    
     NSString *deviceTokenStr = [userDefaultManager valueForKey:@"DeviceTokenKey"];
    
    if (!isNetworkAvailable) {
        if (netWorkNotAvailableAlert==nil) {
            netWorkNotAvailableAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [netWorkNotAvailableAlert show];
        }
        return nil;
    }
    
    NSMutableURLRequest *request = [[NSMutableURLRequest alloc] init];
    paramStr = [paramStr stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    NSURL *url = [NSURL URLWithString:[NSString stringWithFormat:@"%@%@",serviceURLHost,paramStr]];
    [request setURL:url];
    [request setCachePolicy:NSURLRequestUseProtocolCachePolicy];
    [request setValue:hashKey forHTTPHeaderField:@"HASHKEY"];
    [request setValue:control forHTTPHeaderField:@"CONTROL"];
    [request setValue:methodHeader forHTTPHeaderField:@"METHOD"];
    [request setValue:deviceTokenStr forHTTPHeaderField:@"DeviceId"];
    [request setValue:deviceTokenStr forHTTPHeaderField:@"DeviceToken"];
    [request setValue:@"ios" forHTTPHeaderField:@"DeviceType"];
    [request setHTTPMethod:method];
    [request setHTTPBody:data];
    [request setTimeoutInterval:600.0f];
    
    NSError *requestError=nil;
    NSURLResponse *response;
    
    NSData *responseData = [NSURLConnection sendSynchronousRequest:request returningResponse:&response error:&requestError];
    
    if (requestError) {
        return nil;
    }
    id jsonObject = [self parseResponse:responseData];
    return jsonObject;
}

/*
 * @Auther Deepak chauhan
 * @Parm paramStr , method , data
 * @Description These method are used for Send Synchronous Request With Data
 * @Modified Date 9/07/2015
 */
#pragma mark Send Synchronous Request With Notification
-(id)sendSynchronousRequestNotification:(NSString*)paramStr methodHeader:(NSString *)methodHeader controls:(NSString *)control httpMethod:(NSString*)method data:(NSData*)data{
    
    NSString *userIdStr = [userDefaultManager valueForKey:@"user_id"];
    NSString *deviceTokenStr = [userDefaultManager valueForKey:@"DeviceTokenKey"];
    NSString *groupIdStr = [userDefaultManager valueForKey:@"group_id"];
    
    
    NSMutableURLRequest *request = [[NSMutableURLRequest alloc] init];
    paramStr = [paramStr stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    NSURL *url = [NSURL URLWithString:[NSString stringWithFormat:@"%@%@",serviceURLHost,paramStr]];
    [request setURL:url];
    [request setCachePolicy:NSURLRequestUseProtocolCachePolicy];
    [request setValue:hashKey forHTTPHeaderField:@"HASHKEY"];
    [request setValue:control forHTTPHeaderField:@"CONTROL"];
    [request setValue:groupIdStr forHTTPHeaderField:@"groupId"];
    [request setValue:methodHeader forHTTPHeaderField:@"METHOD"];
    [request setValue:deviceTokenStr forHTTPHeaderField:@"DeviceId"];
    [request setValue:deviceTokenStr forHTTPHeaderField:@"DeviceToken"];
    [request setValue:@"ios" forHTTPHeaderField:@"DeviceType"];
    [request setValue:userIdStr forHTTPHeaderField:@"UserId"];
    [request setHTTPMethod:method];
    [request setHTTPBody:data];
    [request setTimeoutInterval:600.0f];
    
    NSError *requestError=nil;
    NSURLResponse *response;
    
    NSData *responseData = [NSURLConnection sendSynchronousRequest:request returningResponse:&response error:&requestError];
    
    if (requestError) {
        return nil;
    }
    id jsonObject = [self parseResponseNotification:responseData];
    return jsonObject;
}
/*
 * @Auther Deepak chauhan
 * @Parm jsonData
 * @Description This method are used for Convert String into URLString
 * @Modified Date 9/07/2015
 */
#pragma mark Get response after parsing
-(id)parseResponseNotification:(NSData*)jsonData {
    NSError *jsonError = nil;
    
    id jsonObject = [NSJSONSerialization JSONObjectWithData:jsonData options:kNilOptions error:&jsonError];
    NSDictionary *dictionary = jsonObject;
    NSString *stringCode = [NSString stringWithFormat:@"%@",[dictionary valueForKey:@"code"]];
    if ([stringCode isEqualToString:@"200"]) {
        return  jsonObject;
    }
    return nil;
}



/*
 * @Auther Deepak chauhan
 * @Parm paramStr , method , data
 * @Description These method are used for Send Synchronous Request With Data
 * @Modified Date 9/07/2015
 */
#pragma mark Send Synchronous Request With Data
-(id)sendSynchronousRequest:(NSString*)paramStr methodHeader:(NSString *)methodHeader controls:(NSString *)control httpMethod:(NSString*)method data:(NSData*)data{
 
    NSString *userIdStr = [userDefaultManager valueForKey:@"user_id"];
    NSString *deviceTokenStr = [userDefaultManager valueForKey:@"DeviceTokenKey"];
    NSString *groupIdStr = [userDefaultManager valueForKey:@"group_id"];
    
    if (!isNetworkAvailable) {
        if (netWorkNotAvailableAlert==nil) {
            netWorkNotAvailableAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [netWorkNotAvailableAlert show];
        }
        return nil;
    }
    
    NSMutableURLRequest *request = [[NSMutableURLRequest alloc] init];
    paramStr = [paramStr stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    NSURL *url = [NSURL URLWithString:[NSString stringWithFormat:@"%@%@",serviceURLHost,paramStr]];
    [request setURL:url];
    [request setCachePolicy:NSURLRequestUseProtocolCachePolicy];
    [request setValue:hashKey forHTTPHeaderField:@"HASHKEY"];
    [request setValue:control forHTTPHeaderField:@"CONTROL"];
    [request setValue:groupIdStr forHTTPHeaderField:@"groupId"];
    [request setValue:methodHeader forHTTPHeaderField:@"METHOD"];
    [request setValue:deviceTokenStr forHTTPHeaderField:@"DeviceId"];
    [request setValue:deviceTokenStr forHTTPHeaderField:@"DeviceToken"];
    [request setValue:@"ios" forHTTPHeaderField:@"DeviceType"];
    [request setValue:userIdStr forHTTPHeaderField:@"UserId"];
    [request setHTTPMethod:method];
    [request setHTTPBody:data];
    [request setTimeoutInterval:600.0f];
    
    NSError *requestError=nil;
    NSURLResponse *response;
    
    NSData *responseData = [NSURLConnection sendSynchronousRequest:request returningResponse:&response error:&requestError];
    
    if (requestError) {
        return nil;
    }
    id jsonObject = [self parseResponse:responseData];
    return jsonObject;
}

/*
 * @Auther Deepak chauhan
 * @Parm paramStr , method
 * @Description These method are used for Send Synchronous Request With Data
 * @Modified Date 9/07/2015
 */
#pragma mark Send Synchronous Request With out Data
-(id)sendSynchronousRequest:(NSString*)paramStr methodHeader:(NSString *)methodHeader controls:(NSString *)control httpMethod:(NSString*)method{
    return [self sendSynchronousRequest:paramStr methodHeader:methodHeader controls:control httpMethod:method data:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm paramStr , method
 * @Description These method are used for AsynchronousRequest
 * @Modified Date 9/07/2015
 */
#pragma mark AsynchronousRequest
-(void)sendAsynchronousRequest:(NSString*)paramStr httpMethod:(NSString*)method{
    
    NSMutableURLRequest *request = [[NSMutableURLRequest alloc] init];
    [request setURL:[NSURL URLWithString:[NSString stringWithFormat:@"%@%@",serviceURLHost,paramStr]]];
    [request setCachePolicy:NSURLRequestReturnCacheDataElseLoad];
    [request setTimeoutInterval:3000.0f];
    [request setHTTPMethod:method];
    
    connection=[[NSURLConnection alloc] initWithRequest:request delegate:self];
    
    receivedData = [NSMutableData data];
}

- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response {
     [receivedData setLength:0];
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data {
    [receivedData appendData:data];
}

- (void)connectionDidFinishLoading:(NSURLConnection *)connection {
    if ([delegate respondsToSelector:@selector(receivedResponse:)]) {
        [delegate receivedResponse:receivedData];
    }
}

- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error {
    if ([delegate respondsToSelector:@selector(receivedResponse:)]) {
        [delegate receivedResponse:nil];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm jsonData
 * @Description This method are used for Convert String into URLString
 * @Modified Date 9/07/2015
 */
#pragma mark Get response after parsing
-(id)parseResponse:(NSData*)jsonData {
    NSError *jsonError = nil;

    id jsonObject = [NSJSONSerialization JSONObjectWithData:jsonData options:kNilOptions error:&jsonError];
    NSDictionary *dictionary = jsonObject;
    NSString *stringCode = [NSString stringWithFormat:@"%@",[dictionary valueForKey:@"code"]];
    NSString *stringMessage = [NSString stringWithFormat:@"%@",[dictionary valueForKey:@"message"]];
    if ([stringCode isEqualToString:@"200"]) {
        return  jsonObject;
    }
    if ([stringCode isEqualToString:@"404"]) {
        if (!CheckStringForNull(stringMessage)) {
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:stringMessage delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            if ([jsonObject objectForKey:@"groupSelected"]) {
                return jsonObject;
            }
            if ([jsonObject objectForKey:@"webcastExist"]) {
                return jsonObject;
            }
        }
        return nil;
    }
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:stringMessage delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
    [alert show];
    
    return nil;
}

/*
 * @Auther Deepak chauhan
 * @Parm title , message 
 * @Description This method are used for Convert String into URLString
 * @Modified Date 9/07/2015
 */
#pragma mark Show Alert
-(void)showAlert:(NSString*)title Message:(NSString *)message {

    
}

/*
 * @Auther Deepak chauhan
 * @Parm str
 * @Description This method are used for Convert String into URLString
 * @Modified Date 9/07/2015
 */
#pragma mark Encode string as URL
-(NSString *)encodeStringToUrlString:(NSString *)str{
    NSString *primaryGenreNameStr = (NSString *)CFBridgingRelease(CFURLCreateStringByAddingPercentEscapes(NULL,(CFStringRef)str,NULL,(CFStringRef)@"!*'();:@&=+$,/?%#[]",kCFStringEncodingUTF8 ));
    return primaryGenreNameStr;
}


-(void)setBoarderColorGray:(UIView *)sender {
    [self setGradientColor4Button:sender cornerRadius:5.0f borderWidth:1 borderColor:[UIColor lightGrayColor] topGradientColor:[UIColor clearColor] bottomGradientColor:[UIColor clearColor]];
}
-(void)setBoarderColorClear:(UIView *)sender {
    [self setGradientColor4Button:sender cornerRadius:5.0f borderWidth:1 borderColor:[UIColor clearColor] topGradientColor:[UIColor clearColor] bottomGradientColor:[UIColor clearColor]];
}
-(void)setBoarderColorRed:(UIView*)sender {
    [self setGradientColor4Button:sender cornerRadius:0.0f borderWidth:1 borderColor:[UIColor redColor] topGradientColor:[UIColor clearColor] bottomGradientColor:[UIColor clearColor]];
}

- (void)setGradientColor4Button:(UIView*)sender cornerRadius:(CGFloat)cornerRadius borderWidth:(CGFloat)borderWidth borderColor:(UIColor*)borderColor topGradientColor:(UIColor*)topColor bottomGradientColor:(UIColor*)bottomColor {
    
    CALayer *buttonLayer = sender.layer;
    [buttonLayer setMasksToBounds:YES];
    [buttonLayer setCornerRadius:cornerRadius];
    [buttonLayer setBorderWidth:borderWidth];
    [buttonLayer setBorderColor:borderColor.CGColor];
    [buttonLayer setMasksToBounds:YES];
}

@end
