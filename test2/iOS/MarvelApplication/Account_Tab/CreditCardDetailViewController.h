//
//  CreditCardDetailViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 11/18/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
@protocol CreditCardDetailDelegate <NSObject>

-(void)updateBillingInfo;

@end

@interface CreditCardDetailViewController : UIViewController<UIPickerViewDelegate> {

    __weak IBOutlet UILabel *lblPreviousCard;
    __weak IBOutlet UIImageView *imgBG;
    __weak IBOutlet TPKeyboardAvoidingScrollView *scrollView;
    __weak IBOutlet UIImageView *cardNumberBG;
    __weak IBOutlet UITextField *textFieldCardNumber;
    __weak IBOutlet UIImageView *imgCVC;
    __weak IBOutlet UITextField *textFieldCvc;
    __weak IBOutlet UIImageView *imgExpireMonth;
    __weak IBOutlet UITextField *textFieldExpireMonth;
    __weak IBOutlet UIImageView *imgExpireYear;
    __weak IBOutlet UITextField *textFieldExpireYear;
    __weak IBOutlet UIImageView *imgCardHolderName;
    __weak IBOutlet UITextField *textFieldCardHolderName;
    __weak IBOutlet UIButton *btnSave;
    
    UIPickerView *prefrencePikerView;
    UIToolbar *prefrenceToolBar;
    NSString *checkMonthYearString;
    NSMutableArray *yearArray;
    NSArray *monthArray;
    
    int index , monthIndex , yearIndex;
}
@property (nonatomic , weak) id<CreditCardDetailDelegate>PaymentDelegate;
@property (nonatomic , strong) NSString *previousCardString;

@end
