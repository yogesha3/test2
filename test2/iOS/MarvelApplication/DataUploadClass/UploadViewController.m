//
//  UploadViewController.m
//

#import "UploadViewController.h"
#import "ASIFormDataRequest.h"
#import <MobileCoreServices/MobileCoreServices.h>
#import "AppDelegate.h"
// Private stuff
@interface UploadViewController ()
- (void)uploadFailed:(ASIHTTPRequest *)theRequest;
- (void)uploadFinished:(ASIHTTPRequest *)theRequest;
@end

@implementation UploadViewController
@synthesize delegate;


-(id)initWithServiceName:(NSString*)sName dataArray:(NSArray*)dataArr methodHeader:(NSString *)methodHeader1 controls:(NSString *)control1 {
    
    self = [super init];
    if (self!=nil) {
        serviceName = sName;
        methodHeader = methodHeader1;
        control = control1;
        postArray=[NSArray arrayWithArray:dataArr];
    }
    return self;
}

/**
 *
 * *************** Send content to server *****************
 * @author Deepak Kumar.
 *
 **/
-(void)sendContantToServer {
    
    [request1 clearDelegatesAndCancel];
    request1=[ASIFormDataRequest requestWithURL:[NSURL URLWithString:[NSString stringWithFormat:@"%@%@",RegisterURL,serviceName]]];
    
    int dataCount = [[postArray lastObject] intValue];
    int otherCount = [postArray count] - dataCount-1;
    int i;
    for (i=0; i<otherCount; ) {
        [request1 setPostValue:[postArray objectAtIndex:i] forKey:[postArray objectAtIndex:i+1]];
        i+=2;
    }
    [request1 setTimeOutSeconds:3000];
    
#if __IPHONE_OS_VERSION_MAX_ALLOWED >= __IPHONE_4_0
#endif
    [request1 setShouldContinueWhenAppEntersBackground:YES];
    [request1 setDelegate:self];
    
    NSString *userIdStr = [userDefaultManager valueForKey:@"user_id"];
    NSString *deviceTokenStr = [userDefaultManager valueForKey:@"DeviceTokenKey"];
    
    [request1 addRequestHeader:@"HASHKEY" value:appkey];
    [request1 addRequestHeader:@"CONTROL" value:control];
    [request1 addRequestHeader:@"METHOD" value:methodHeader];
    [request1 addRequestHeader:@"DeviceId" value:deviceTokenStr];
    [request1 addRequestHeader:@"DeviceToken" value:deviceTokenStr];
    [request1 addRequestHeader:@"DeviceType" value:@"123"];
    [request1 addRequestHeader:@"UserId" value:userIdStr];
    
    [request1 setUploadProgressDelegate:self];
    [request1 setDownloadProgressDelegate:self];
    request1.shouldAttemptPersistentConnection = NO;
    [request1 setDidFailSelector:@selector(uploadFailed:)];
    [request1 setDidFinishSelector:@selector(uploadFinished:)];
    
    int index = 0;
    
    for (int heavyDataCount = i; heavyDataCount < [postArray count]-1; heavyDataCount++) {
        [request1 setFile:[postArray objectAtIndex:heavyDataCount] forKey:[NSString stringWithFormat:@"fileUpload%i",index]];
        index = index+1;
    }
    
    [request1 startAsynchronous];
    
}

// Called when the request receives some data - bytes is the length of that data
- (void)request:(ASIHTTPRequest *)request didReceiveBytes:(long long)bytes{
    NSLog(@"didReceiveBytes %lld | %lld",request.postLength,bytes);
}

// Called when the request sends some data
// The first 32KB (128KB on older platforms) of data sent is not included in this amount because of limitations with the CFNetwork API
// bytes may be less than zero if a request needs to remove upload progress (probably because the request needs to run again)
- (void)request:(ASIHTTPRequest *)request didSendBytes:(long long)bytes{
    NSLog(@"didSendBytes %lld | %lld",request.postLength,bytes);
}

// Called when a request needs to change the length of the content to download
- (void)request:(ASIHTTPRequest *)request incrementDownloadSizeBy:(long long)newLength{
    
}

// Called when a request needs to change the length of the content to upload
// newLength may be less than zero when a request needs to remove the size of the internal buffer from progress tracking
- (void)request:(ASIHTTPRequest *)request incrementUploadSizeBy:(long long)newLength {
    //NSLog(@"incrementUploadSizeBy %lld | %lld",request.postLength,newLength);
}

/**
 *
 * *************** Upload failed *****************
 * @author Deepak Kumar.
 *
 **/
- (void)uploadFailed:(ASIHTTPRequest *)theRequest {
   
    [[AppDelegate currentDelegate] removeLoading];
    if ([self.delegate respondsToSelector:@selector(receiveUploadResponseDataFromServer:)]) {
        [self.delegate receiveUploadResponseDataFromServer:nil];
    }
    [request1 clearDelegatesAndCancel];
    request1 = nil;
}

/**
 *
 * *************** Upload finished *****************
 * @author Deepak Kumar.
 *
 **/
- (void)uploadFinished:(ASIHTTPRequest *)theRequest {
    
    [[AppDelegate currentDelegate] removeLoading];
    if ([self.delegate respondsToSelector:@selector(receiveUploadResponseDataFromServer:)]) {
        [self.delegate receiveUploadResponseDataFromServer:theRequest.responseData];
    }
    [request1 clearDelegatesAndCancel];
    request1 = nil;
    [[AppDelegate currentDelegate] removeTempFiles];
}

@end
