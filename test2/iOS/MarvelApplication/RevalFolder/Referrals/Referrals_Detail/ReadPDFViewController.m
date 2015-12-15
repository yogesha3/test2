//
//  ReadPDFViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 8/17/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "ReadPDFViewController.h"

@interface ReadPDFViewController ()

@end

@implementation ReadPDFViewController
@synthesize docURLString;

- (void)viewDidLoad {
    
    NSURL *targetURL = [NSURL URLWithString:docURLString];
    NSURLRequest *request = [NSURLRequest requestWithURL:targetURL];
    [webView loadRequest:request];
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 17/08/2015
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
