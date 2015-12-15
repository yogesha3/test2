//
//  ComposeViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/29/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "ComposeViewController.h"
#import "SelectAndCreateContactViewController.h"

@interface ComposeViewController ()

@end

@implementation ComposeViewController
@synthesize recordArr;
@synthesize dictReferMe;

- (void)viewDidLoad {
    
    self.imgBgTextField.layer.cornerRadius = 5.0f;
    self.imgControlBG.layer.cornerRadius = 5.0f;
    
    [self.textField setValue:placeHolderColor
                  forKeyPath:@"_placeholderLabel.textColor"];
    
    [btnNext setUserInteractionEnabled:NO];
    btnNext.alpha = 0.5;
    btnNext.layer.cornerRadius = 5.0f;
    
    //From Team tab user will send the Referral
    if (recordArr.count) {
        [self selectedTeamMember:recordArr];
    }

    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

-(void)viewWillAppear:(BOOL)animated {

    
    [super viewWillAppear:animated];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Compose Functionality
 * @Modified Date 29/07/2015
 */
#pragma mark Search Btn
-(IBAction)btnselectAndCreateContact:(UIButton*)sender {
    [self performSegueWithIdentifier:@"selectAndCreateContact" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Take Team Member
 * @Modified Date 29/07/2015
 */
#pragma mark team Member
-(IBAction)btnTeamMember:(UIButton*)sender {
    [self performSegueWithIdentifier:@"teamMember" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm array
 * @Description This method are used for get Team member from another class
 * @Modified Date 3/08/2015
 */
#pragma mark Received Team Member
-(void)selectedTeamMember:(NSMutableArray *)array {
    [btnNext setUserInteractionEnabled:YES];
    btnNext.alpha = 1.0;
    if (array.count > 1) {
        self.textField.text = [NSString stringWithFormat:@"Team Member Selected (%lu)",(unsigned long)array.count];
    }
    else {
        NSDictionary *dict = [array objectAtIndex:0];
        NSString *fromLastNameStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"lname"]];
        NSString *fromFirstNameStr = [NSString stringWithFormat:@" %@",[dict valueForKey:@"fname"]];
        fromLastNameStr = [fromLastNameStr stringByAppendingString:fromFirstNameStr];
        self.textField.text = fromLastNameStr;
    }
    teamMemberArray = array;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 9/07/2015
 */
#pragma mark Back Screen
- (IBAction)backScreen:(id)sender {
    [self.navigationController popViewControllerAnimated:NO];
}

#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
    
    if ([segue.identifier isEqualToString:@"teamMember"]) {
        SelectTeamMemberViewController *selectTeamMemberViewController = segue.destinationViewController;
        selectTeamMemberViewController.DelegateMember = self;
        selectTeamMemberViewController.titleString = @"REFERRALS";
        selectTeamMemberViewController.preSelectArray = teamMemberArray;
    }
    else if ([segue.identifier isEqualToString:@"selectAndCreateContact"]){
        NSString *teamMemberIdStr = @"";
        for (int i = 0; i<teamMemberArray.count; i++) {
            if (i == 0) {
                teamMemberIdStr = [NSString stringWithFormat:@"%@",[[teamMemberArray objectAtIndex:i] valueForKey:@"user_id"]];
            }
            else {
                NSString *memberIdStr = [NSString stringWithFormat:@",%@",[[teamMemberArray objectAtIndex:i] valueForKey:@"user_id"]];
                teamMemberIdStr = [teamMemberIdStr stringByAppendingString:memberIdStr];
            }
        }
        SelectAndCreateContactViewController *selectAndCreateContactViewController = segue.destinationViewController;
        selectAndCreateContactViewController.dictReferMe = dictReferMe;//For Refer Me From contactViewController
        selectAndCreateContactViewController.selectTememberIdString = teamMemberIdStr;
    }
}


@end
