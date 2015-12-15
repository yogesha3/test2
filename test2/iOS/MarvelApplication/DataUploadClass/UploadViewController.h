//
//  UploadViewController.h
//

#import <UIKit/UIKit.h>

#import "ASIHTTPRequest.h"

@class ASIFormDataRequest;

@protocol UploadViewControllerDelegate <NSObject>
-(void)receiveUploadResponseDataFromServer:(NSData*)responseData;
@end

@interface UploadViewController : NSObject <ASIHTTPRequestDelegate,ASIProgressDelegate> {	
    id<UploadViewControllerDelegate> delegate;
	ASIFormDataRequest *request1;
    NSString *serviceName;
    NSString *control;
    NSString *methodHeader;
     NSArray *postArray;
    BOOL sendAttempt;
}
@property(nonatomic, strong)id<UploadViewControllerDelegate> delegate;


-(void)sendContantToServer;
-(id)initWithServiceName:(NSString*)sName dataArray:(NSArray*)dataArr methodHeader:(NSString *)methodHeader controls:(NSString *)control;

@end
