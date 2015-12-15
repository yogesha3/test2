//
//  EditReffralsViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 9/8/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
@protocol editReffralsDelegate <NSObject>

-(void)updateReferralFromEdit;

@end

@interface EditReffralsViewController : UIViewController <UIPickerViewDelegate> {

    __weak IBOutlet UIButton *btnNext;
    __weak IBOutlet TPKeyboardAvoidingScrollView *scrollView;
    __weak IBOutlet UIImageView *imgControllBG;
    __weak IBOutlet UIImageView *imgFirstBG;
    __weak IBOutlet UITextField *textFirstName;
    __weak IBOutlet UIImageView *imgLastBG;
    __weak IBOutlet UITextField *textLastName;
    __weak IBOutlet UIImageView *imgCompanyBG;
    __weak IBOutlet UITextField *textCompany;
    __weak IBOutlet UIImageView *imgJobTitlBG;
    __weak IBOutlet UITextField *textJobTitle;
    __weak IBOutlet UIImageView *imgEmailBG;
    __weak IBOutlet UITextField *textEmail;
    __weak IBOutlet UIImageView *imgWebsiteBG;
    __weak IBOutlet UITextField *textWebSite;
    __weak IBOutlet UIImageView *imgAddressBg;
    __weak IBOutlet UITextField *textAddress;
    __weak IBOutlet UIImageView *imgSelectCouBG;
    __weak IBOutlet UITextField *textSelectCountry;
    __weak IBOutlet UIButton *btnChooseCountry;
    __weak IBOutlet UIImageView *imgSelectStateBG;
    __weak IBOutlet UITextField *textState;
    __weak IBOutlet UIButton *btnChooseState;
    __weak IBOutlet UIImageView *imgCityBG;
    __weak IBOutlet UITextField *textCity;
    __weak IBOutlet UIImageView *imgZipBG;
    __weak IBOutlet UITextField *textZip;
    __weak IBOutlet UIImageView *imgOfficePhoneBG;
    __weak IBOutlet UITextField *textOfficePhone;
    __weak IBOutlet UIImageView *imgMobileBG;
    __weak IBOutlet UITextField *textPhone;
    __weak IBOutlet UIImageView *imgStatusBG;
    __weak IBOutlet UITextField *textStatus;
    __weak IBOutlet UIImageView *imgValueBG;
    __weak IBOutlet UITextField *textValue;
    
    NSDictionary *dictionaryEditReferral;
    UIPickerView *prefrencePikerView;
    UIToolbar *prefrenceToolBar;
    NSArray *statusArray;
    int selectStatusIndex;
    int preSelectStatusIndex;

    id<editReffralsDelegate>delegateEditReferral;
}
@property (nonatomic , strong) id<editReffralsDelegate>delegateEditReferral;
@property (nonatomic , strong) NSDictionary *editRefDict;

@end
