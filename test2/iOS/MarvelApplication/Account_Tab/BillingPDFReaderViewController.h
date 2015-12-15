//
//  BillingPDFReaderViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 11/18/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface BillingPDFReaderViewController : UIViewController {

    __weak IBOutlet UIWebView *webView;
    
}
@property (nonatomic , strong) NSString *pdfUrlString;
@end
