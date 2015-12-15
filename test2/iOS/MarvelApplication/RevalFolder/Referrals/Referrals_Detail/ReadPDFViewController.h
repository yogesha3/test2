//
//  ReadPDFViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 8/17/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ReadPDFViewController : UIViewController {

    __weak IBOutlet UIWebView *webView;
    
}

@property (nonatomic , strong) NSString *docURLString;

@end
