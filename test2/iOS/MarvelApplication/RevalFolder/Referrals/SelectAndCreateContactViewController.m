//
//  SelectAndCreateContactViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/29/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "SelectAndCreateContactViewController.h"
#import "NewContactViewController.h"

@interface SelectAndCreateContactViewController ()

@end

@implementation SelectAndCreateContactViewController
@synthesize selectTememberIdString;
@synthesize dictReferMe;

- (void)viewDidLoad {
    
    self.imgBgTextField.layer.cornerRadius = 5.0f;
    [self.textField setValue:placeHolderColor
                    forKeyPath:@"_placeholderLabel.textColor"];
    
    self.btnContactCreate.layer.cornerRadius = 5.0f;
    
    self.imgControlBG.layer.cornerRadius = 5.0f;
    
    self.lblOr.layer.cornerRadius = 20.0f;
    self.lblOr.clipsToBounds = YES;
    self.lblOr.layer.borderWidth = 5.0f;
    self.lblOr.layer.borderColor = [UIColor colorWithRed:79.0/255.0 green:76.0/255.0 blue:85.0/255.0 alpha:1].CGColor;
    
    btnNext.layer.cornerRadius = 5.0f;
    [btnNext setUserInteractionEnabled:NO];
    btnNext.alpha = 0.5;
    
    //For Refer Me From contactViewController
    if (dictReferMe.count) {
        [self selectedContact:dictReferMe];
    }
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}
/*
 * @Auther Deepak chauhan
 * @Parm array
 * @Description This method are used for get Exesting Contact from another class
 * @Modified Date 3/08/2015
 */
#pragma mark Receive Exesting Contact
-(void)selectedContact:(NSDictionary *)dict {
    
    contactDict = dict;
    [btnNext setUserInteractionEnabled:YES];
    btnNext.alpha = 1;
    
    NSString *fNameStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"first_name"]];
    NSString *lNameStr = [NSString stringWithFormat:@" %@",[dict valueForKey:@"last_name"]];
    fNameStr = [fNameStr stringByAppendingString:lNameStr];
    [self.btnContactCreate setTitleColor:[UIColor lightGrayColor] forState:UIControlStateNormal];
    [self.btnContactCreate setUserInteractionEnabled:NO];
    
    if ([fNameStr isEqualToString:@"Clear Selection"]) {
        self.textField.text = @"";
        self.textField.placeholder = @"Select an existing contact";
        [self.btnContactCreate setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
        [self.btnContactCreate setUserInteractionEnabled:YES];
        btnNext.alpha = 0.5;
        [btnNext setUserInteractionEnabled:NO];
    }
    else {
        self.textField.text = fNameStr;
        dictionary = dict;
    }
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

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Select Contact
 * @Modified Date 04/08/2015
 */
#pragma mark Select Contact
- (IBAction)selectContact:(id)sender {
    [self performSegueWithIdentifier:@"selectContact" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Create Contact
 * @Modified Date 04/08/2015
 */
#pragma mark Create Contact
- (IBAction)createContactBtn:(id)sender {
    identifyFunctionality = @"createContact";
    [self performSegueWithIdentifier:@"contactInfo" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Next Process
 * @Modified Date 29/07/2015
 */
#pragma mark Next Screen
- (IBAction)nextButton:(id)sender {
    identifyFunctionality = @"next";
    [self performSegueWithIdentifier:@"contactInfo" sender:nil];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    
    if ([segue.identifier isEqualToString:@"selectContact"]) {
        SelectContactViewController *selectContactViewController = segue.destinationViewController;
        selectContactViewController.DelegateContact = self;
        selectContactViewController.dictContact = contactDict;
    }
    if ([segue.identifier isEqualToString:@"contactInfo"]) {
        NewContactViewController *newContactViewController = segue.destinationViewController;
        newContactViewController.selectTememberIdString = selectTememberIdString;
        if ([identifyFunctionality isEqualToString:@"next"]) {
            newContactViewController.dictionaryContact = dictionary;
        }
        else {
            newContactViewController.dictionaryContact = nil;
        }
    }
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}


@end
