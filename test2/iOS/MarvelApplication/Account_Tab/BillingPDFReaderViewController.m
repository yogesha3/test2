//
//  BillingPDFReaderViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 11/18/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import "BillingPDFReaderViewController.h"

@interface BillingPDFReaderViewController ()

@end

@implementation BillingPDFReaderViewController
@synthesize pdfUrlString;

- (void)viewDidLoad {
    
    [self performSelector:@selector(viewPDF) withObject:nil afterDelay:0.1f];
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

-(void)viewPDF {
    [[AppDelegate currentDelegate] addLoading];
    NSURL *targetURL = [NSURL URLWithString:pdfUrlString];
    NSURLRequest *request = [NSURLRequest requestWithURL:targetURL];
    [webView loadRequest:request];
    [webView stringByEvaluatingJavaScriptFromString: @"document.body.style.fontFamily = 'GothamRounded-Bold'"];
    [self performSelector:@selector(removeLoading) withObject:nil afterDelay:3.0f];
}
-(void)removeLoading {
    [[AppDelegate currentDelegate] removeLoading];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 18/11/2015
 */
#pragma mark Back Screen
- (IBAction)backScreen:(id)sender {
    [self.navigationController popViewControllerAnimated:NO];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
