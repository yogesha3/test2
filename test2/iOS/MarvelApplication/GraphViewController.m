//
//  GraphViewController.m
//  MarvelApplication
//
//  Created by Deepak Chauhan on 7/8/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "GraphViewController.h"
#import "SWRevealViewController.h"
#import "PCPieChart.h"

@interface GraphViewController ()
@property (nonatomic) IBOutlet UIButton* revealButtonItem;
@end

@implementation GraphViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    matchString = @"";
    [self customSetup];
    
    //for check user are login or not
    NSString *checkLogin = [[NSUserDefaults standardUserDefaults] valueForKey:@"login"];
    if (![checkLogin isEqualToString:@"loginSuccess"]) {
        [self performSegueWithIdentifier:@"login" sender:nil];
    }    
    // Do any additional setup after loading the view.
}

-(void)viewWillAppear:(BOOL)animated {

    if (![matchString isEqualToString:@"logout"]) {
        //for check user are login or not
        NSString *checkLogin = [[NSUserDefaults standardUserDefaults] valueForKey:@"login"];
        if ([checkLogin isEqualToString:@"loginSuccess"]) {
            NSString *groupIdString = [userDefaultManager valueForKey:@"group_id"];
            if (CheckStringForNull(groupIdString)) {
                [self performSegueWithIdentifier:@"GroupSelectAfterLogin" sender:nil];
            }
            else {
                [self performSelector:@selector(designView) withObject:nil afterDelay:0.1];
            }
        }
    }

    [super viewWillAppear:animated];
}

/*
 * @Auther Deepak chauhan
 * @Parm nil
 * @Description This method are used for Logout User from group class
 * @Modified Date 28/10/2015
 */
#pragma mark Logout User From Group Class
-(void)logOutUserFromGroupClass {
    matchString = @"logout";
    [self logOut];
}

-(void)logOut {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(logOutDelay) withObject:nil afterDelay:0.1];
}
-(void)logOutDelay {
    if (isNetworkAvailable) {
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"logout" controls:@"users" httpMethod:@"POST" data:nil];
        
        if (parsedJSONToken != nil) {
            [self performSegueWithIdentifier:@"login" sender:nil];
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [[AppDelegate currentDelegate] removeLoading];
}


/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description This method are used for Design View
 * @Modified Date 9/07/2015
 */
#pragma mark Design View
-(void)designView {

    [self customSetup];
    
    arrayValue = [[NSMutableArray alloc] init];
    
    [arrayValue addObject:@[@{@"title":@"38",@"value":@"38",@"text":@"Quarantine Devices"},@{@"title":@"75",@"value":@"75",@"text":@"Pending Devices"},@{@"title":@"6",@"value":@"6",@"text":@"Retired Devices"}]];
    
    [arrayValue addObject:@[@{@"title":@"1231",@"value":@"1231",@"text":@"Low Risk"},@{@"title":@"52",@"value":@"52",@"text":@"Moderate Risk"},@{@"title":@"461",@"value":@"461",@"text":@"High Risk"},@{@"title":@"158",@"value":@"158",@"text":@"Scan Pending"},@{@"title":@"34",@"value":@"34",@"text":@"Unknown"},@{@"title":@"365",@"value":@"365",@"text":@"Malware"},@{@"title":@"33",@"value":@"33",@"text":@"Secured"}]];
    
    [arrayValue addObject:@[@{@"title":@"292",@"value":@"292",@"text":@"Accesses Personal Info"},@{@"title":@"222",@"value":@"222",@"text":@"Talk to Add Networks"},@{@"title":@"394",@"value":@"394",@"text":@"Connets Cloud Services"},@{@"title":@"761",@"value":@"761",@"text":@"No Privacy Policy"},@{@"title":@"1074",@"value":@"1074",@"text":@"Reconfigures Devices"},@{@"title":@"198",@"value":@"198",@"text":@"Send Location"},@{@"title":@"567",@"value":@"567",@"text":@"Communicated Insecurely"},@{@"title":@"156",@"value":@"156",@"text":@"Others"}]];
    
    [self designViewWithGraph];

}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description This method are used for Design Graph
 * @Modified Date 9/07/2015
 */
#pragma mark Design Graph
-(void)designViewWithGraph {

    int y = 0;
    
    [[self.scrollView subviews] makeObjectsPerformSelector:@selector(removeFromSuperview)];
    
    for (int i = 0; i<[arrayValue count]; i++) {
        
        UILabel *headingLBL = [[UILabel alloc] initWithFrame:CGRectMake(10, y, self.scrollView.frame.size.width-20, 30)];
        [headingLBL setBackgroundColor:[UIColor clearColor]];
        headingLBL.textColor = [UIColor colorWithRed:0.0/255.0 green:152.0/255.0 blue:186.0/255.0 alpha:1];
        if (i == 0) {
            headingLBL.text = @"Device Status:";
        }
        if (i == 1) {
            headingLBL.text = @"Risk Levels:";
        }
        if (i == 2) {
            headingLBL.text = @"App Behavior:";
        }
        
        headingLBL.adjustsFontSizeToFitWidth = YES;
        [headingLBL setFont:[UIFont fontWithName:@"HelveticaNeue-Bold" size:15.0f]];
        [self.scrollView addSubview:headingLBL];
        
        y = y+30;
        
        NSArray *array = [arrayValue objectAtIndex:i];
        
        int width = [self.scrollView bounds].size.width;
        
        PCPieChart *pieChart = [[PCPieChart alloc] initWithFrame:CGRectMake(([self.scrollView bounds].size.width-width)/2,y,width,300)];
        [pieChart setAutoresizingMask:UIViewAutoresizingFlexibleLeftMargin|UIViewAutoresizingFlexibleRightMargin|UIViewAutoresizingFlexibleTopMargin|UIViewAutoresizingFlexibleBottomMargin];
        [pieChart setDiameter:width/2];
        [pieChart setSameColorLabel:YES];
        [self.scrollView addSubview:pieChart];
        pieChart.titleFont = [UIFont fontWithName:@"HelveticaNeue" size:12];
        pieChart.percentageFont = [UIFont fontWithName:@"HelveticaNeue" size:12];
        
        //for show the Some Details
        UIView *optionView = [[UIView alloc] initWithFrame:CGRectMake(0, pieChart.frame.size.height, pieChart.frame.size.width, 20)];
        [optionView setBackgroundColor:[UIColor clearColor]];
        [pieChart addSubview:optionView];
        
        CALayer *bottomBorder = [CALayer layer];
        bottomBorder.borderColor = [UIColor lightGrayColor].CGColor;
        bottomBorder.borderWidth = 1;
        bottomBorder.frame = CGRectMake(0, optionView.frame.size.height-1, optionView.frame.size.width, 1.0);
        [optionView.layer addSublayer:bottomBorder];
        
        NSMutableArray *components = [NSMutableArray array];
        
        int yPosition = 0;
        float widthPoint = 5.0f;
        
        for (int i=0; i<[array count]; i++) {
            NSDictionary *item = [array objectAtIndex:i];
            
            NSString *textString = [item valueForKey:@"text"];
            
            CGRect textRect = [textString boundingRectWithSize:CGSizeMake(FLT_MAX, 20.0f)
                                                 options:NSStringDrawingUsesLineFragmentOrigin
                                              attributes:@{NSFontAttributeName:[UIFont systemFontOfSize:10.0f]}
                                                 context:nil];
            CGSize size = textRect.size;

            if (widthPoint+size.width+25 > self.view.frame.size.width) {
                widthPoint = 5.0;
                yPosition = yPosition+25;
            }
            
            UIView *showOptionView = [[UIView alloc] initWithFrame:CGRectMake(widthPoint, yPosition, size.width+25, 20)];
            [showOptionView setBackgroundColor:[UIColor clearColor]];
            [optionView addSubview:showOptionView];
            
            widthPoint = widthPoint+showOptionView.frame.size.width+5;
            
            UIImageView *colorView = [[UIImageView alloc] initWithFrame:CGRectMake(0, 2, 15, 15)];
            [showOptionView addSubview:colorView];
            
            UILabel *showStatusLBL = [[UILabel alloc] initWithFrame:CGRectMake(17, 0, size.width, 20)];
            [showStatusLBL setBackgroundColor:[UIColor clearColor]];
            [showStatusLBL setTextColor:[UIColor blackColor]];
            showStatusLBL.text = textString;
            showStatusLBL.adjustsFontSizeToFitWidth = YES;
            [showStatusLBL setFont:[UIFont fontWithName:@"HelveticaNeue" size:10.0f]];
            [showOptionView addSubview:showStatusLBL];
            
            optionView.frame = CGRectMake(0, pieChart.frame.size.height, pieChart.frame.size.width, yPosition+25);
            bottomBorder.frame = CGRectMake(0, optionView.frame.size.height-1, optionView.frame.size.width, 1.0);
            
            PCPieComponent *component = [PCPieComponent pieComponentWithTitle:[item objectForKey:@"title"] value:[[item objectForKey:@"value"] floatValue]];
            
            // for set colour
            switch (i) {
                case 0:
                    [component setColour:PCColorGreen];
                    colorView.backgroundColor = PCColorGreen;
                    break;
                case 1:
                    [component setColour:PCColorRed];
                    colorView.backgroundColor = PCColorRed;
                    break;
                case 2:
                    [component setColour:PCColorOrange];
                    colorView.backgroundColor = PCColorOrange;
                    break;
                case 3:
                    [component setColour:PCColorYellow];
                    colorView.backgroundColor = PCColorYellow;
                    break;
                case 4:
                    [component setColour:PCColorGray];
                    colorView.backgroundColor = PCColorGray;
                    break;
                case 5:
                    [component setColour:PCColorBlack];
                    colorView.backgroundColor = PCColorBlack;
                    break;
                case 6:
                    [component setColour:PCColorSkyBlue];
                    colorView.backgroundColor = PCColorSkyBlue;
                    break;
                case 7:
                    [component setColour:PCColorBlue];
                    colorView.backgroundColor = PCColorBlue;
                    break;
                case 8:
                    [component setColour:PCColorLightGray];
                    colorView.backgroundColor = PCColorLightGray;
                    break;
                    
                default:
                    break;
            }
            [components addObject:component];
        }
        
        [pieChart setComponents:components];
        y = y+310+yPosition+25;
        
        self.scrollView.contentSize = CGSizeMake(self.scrollView.frame.size.width, y);
        
    }
    

}

-(void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    
    if ([segue.identifier isEqualToString:@"login"]) {
        LoginViewController*loginViewController = segue.destinationViewController;
        if ([matchString isEqualToString:@"logout"]) {
            matchString = @"";
            loginViewController.checkloginOrLogoutStr = @"logout";
        }
        else {
            loginViewController.checkloginOrLogoutStr = @"";
        }
    }
    else if ([segue.identifier isEqualToString:@"GroupSelectAfterLogin"]) {
        GroupViewController *groupViewController = [segue destinationViewController];
        groupViewController.checkString = @"";
        groupViewController.groupDelegate = self;
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Reval View
 * @Modified Date 9/07/2015
 */
#pragma mark Custom Setup
- (void)customSetup
{
    SWRevealViewController *revealViewController = self.revealViewController;
    if ( revealViewController )
    {
        [self.revealButtonItem addTarget:self action:@selector( toggle) forControlEvents:UIControlEventTouchUpInside];
        [self.navigationController.navigationBar addGestureRecognizer:revealViewController.panGestureRecognizer];
    }
}

-(void)toggle {
    
    [self.view endEditing:YES];
    [self.revealViewController revealToggleAnimated:YES];
}


- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Change Device Orientation
 * @Modified Date 9/07/2015
 */
#pragma mark Set Change Device Orientation Method
- (BOOL)shouldAutorotate {
    
    UIInterfaceOrientation orientation = [[UIApplication sharedApplication] statusBarOrientation];
    
    if (orientation == UIInterfaceOrientationPortrait||orientation == UIInterfaceOrientationPortraitUpsideDown) {
        [self designViewWithGraph];
    }
    else {
        [self designViewWithGraph];
    }
    
    return YES;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    if (interfaceOrientation == UIInterfaceOrientationPortrait||interfaceOrientation == UIInterfaceOrientationPortraitUpsideDown) {
        [self designViewWithGraph];
    }
    else {
        [self designViewWithGraph];
    }
    
    return YES;
}

- (NSUInteger)supportedInterfaceOrientations {
    return (UIInterfaceOrientationPortrait | UIInterfaceOrientationPortraitUpsideDown | UIInterfaceOrientationLandscapeRight | UIInterfaceOrientationLandscapeLeft);
}

- (void)willAnimateRotationToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation duration:(NSTimeInterval)duration

{
    if (interfaceOrientation == UIInterfaceOrientationLandscapeRight||interfaceOrientation == UIInterfaceOrientationLandscapeLeft)
        
    {
        [self designViewWithGraph];
        // landscape
    }
    else
    {
        [self designViewWithGraph];
        //portrait
    }
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
