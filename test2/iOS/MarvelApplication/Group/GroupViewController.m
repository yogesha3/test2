//
//  GroupViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 10/15/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import "GroupViewController.h"
#import "GroupTableCell.h"
#import "UITableView+DragLoad.h"

@interface GroupViewController ()<UITableViewDragLoadDelegate>

@end

@implementation GroupViewController
@synthesize groupDelegate;
@synthesize checkString;

- (void)viewDidLoad {
    [super viewDidLoad];
    
    self.selectedImage = [UIImage imageNamed:@"Selected.png"];
    self.unSelectedImage = [UIImage imageNamed:@"Unselected.png"];
    
    btnApply.layer.cornerRadius = 5.0f;
    btnCancel.layer.cornerRadius = 5.0f;
    
    btnGlobalApply.layer.cornerRadius = 5.0f;
    btnGlobalCancel.layer.cornerRadius = 5.0f;
    
    scrollLocalFilter.contentSize = CGSizeMake(scrollLocalFilter.frame.size.width, btnMostMemberLocal.frame.origin.y+btnMostMemberLocal.frame.size.height+20);
    scrollGlobalFilter.contentSize = CGSizeMake(scrollGlobalFilter.frame.size.width, btnMostMemberGlobal.frame.origin.y+btnMostMemberGlobal.frame.size.height+20);
    
    [self setPlaceHolderColorAndBGColorFieldForLocalFilter];
    
    [viewLocalFilter setHidden:YES];
    [viewGlobalFilter setHidden:YES];
    
    botomView.layer.borderWidth = 1.0f;
    botomView.layer.borderColor = BoarderColour.CGColor;
    
    tableView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    
    [tableView setDragDelegate:self refreshDatePermanentKey:@"FriendList"];
    tableView.showLoadMoreView = YES;
    
    if ([checkString isEqualToString:@"down"]) {
        lblTopTitle.text = @"DOWNGRADE YOUR GROUP";
        [btnBack setHidden:NO];
        [btnLogOut setHidden:YES];
        [userDefaultManager setObject:@"local" forKey:@"filter_type"];
    }
    else if ([checkString isEqualToString:@"up"]){
        lblTopTitle.text = @"UPGRADE YOUR GROUP";
        [btnBack setHidden:NO];
        [btnLogOut setHidden:YES];
        [userDefaultManager setObject:@"global" forKey:@"filter_type"];
    }
    else {
        lblTopTitle.text = @"GROUP SELECTION";
        [btnBack setHidden:YES];
        [btnLogOut setHidden:NO];
        [userDefaultManager setObject:@"local" forKey:@"filter_type"];
    }
    
    
    milesArray = [NSArray arrayWithObjects:@{@"value":@"5 miles"},
                     @{@"value":@"10 miles"},
                     @{@"value":@"25 miles"},
                     @{@"value":@"50 miles"},nil];
    
    dayArray = [NSArray arrayWithObjects:@{@"value":@"Monday"},
                    @{@"value":@"Tuesday"},
                    @{@"value":@"Wednesday"},
                    @{@"value":@"Thursday"},
                    @{@"value":@"Friday"},
                    @{@"value":@"Saturday"},
                    @{@"value":@"Sunday"},nil];
    
    timeArray = [NSArray arrayWithObjects:@{@"value":@"12:00 AM - 04:00 AM"},
                  @{@"value":@"04:00 AM - 08:00 AM"},
                  @{@"value":@"08:00 AM - 12:00 PM"},
                  @{@"value":@"12:00 PM - 04:00 PM"},
                 @{@"value":@"04:00 PM - 08:00 PM"},
                 @{@"value":@"08:00 PM - 12:00 AM"},nil];
    
    [self populateDaySelected];
    [self populateTimeSelected];
}

// Called when the view is about to made visible. Default does nothing
-(void)viewWillAppear:(BOOL)animated {
    
    recordArray = [[NSMutableArray alloc] init];
    [userDefaultManager setObject:@"1" forKey:@"page_no"];
    [userDefaultManager setObject:@"0" forKey:@"totalRecord"];
    [userDefaultManager setObject:@"0" forKey:@"module"];
    //For get the Group Detail Information
    NSString *checkLogin = [[NSUserDefaults standardUserDefaults] valueForKey:@"checkG"];
    if ([checkLogin isEqualToString:@"instant"]) {
        [userDefaultManager setObject:@"" forKey:@"checkG"];
        [self getGroupDetail];
    }
    else {
        if ([checkString isEqualToString:@""]) {
            [self performSelector:@selector(getGroupDetail) withObject:nil afterDelay:3.5];
        }
        else {
            [self getGroupDetail];
        }
    }
    [super viewWillAppear:animated];
}

/*
 * @Auther Deepak chauhan
 * @Parm nil
 * @Description This method are used for Refresh Data
 * @Modified Date 28/10/2015
 */
#pragma mark Refresh Data
-(IBAction)refreshData:(id)sender {
    recordArray = [[NSMutableArray alloc] init];
    viewIdentify = [userDefaultManager valueForKey:@"filter_type"];
    [userDefaultManager setValue:@"1" forKey:@"page_no"];
    [self doneFilter];
}

/*
 * @Auther Deepak chauhan
 * @Parm nil
 * @Description This method are used for Logout User
 * @Modified Date 28/10/2015
 */
#pragma mark Logout User
-(IBAction)logOutUser:(id)sender {
    [self.navigationController popToRootViewControllerAnimated:NO];
    if ([self.groupDelegate respondsToSelector:@selector(logOutUserFromGroupClass)]) {
        [self.groupDelegate logOutUserFromGroupClass];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 19/11/2015
 */
#pragma mark Back Screen
- (IBAction)backScreen:(id)sender {
    [self.navigationController popViewControllerAnimated:NO];
}

/*
 * @Auther Deepak chauhan
 * @Parm nil
 * @Description This method are used for Placeholder And BG Color For Local
 * @Modified Date 15/10/2015
 */
#pragma mark Placeholder And BG Color For Local
-(void)setPlaceHolderColorAndBGColorFieldForLocalFilter {
    
    //For Local
    [scrollLocalFilter viewWithTag:1].layer.cornerRadius = 5.0f;
    [scrollLocalFilter viewWithTag:2].layer.cornerRadius = 5.0f;
    [scrollLocalFilter viewWithTag:3].layer.cornerRadius = 5.0f;
    [scrollLocalFilter viewWithTag:4].layer.cornerRadius = 5.0f;
    [scrollLocalFilter viewWithTag:5].layer.cornerRadius = 5.0f;
    [[scrollLocalFilter viewWithTag:6] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [[scrollLocalFilter viewWithTag:7] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [[scrollLocalFilter viewWithTag:8] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [[scrollLocalFilter viewWithTag:9] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [[scrollLocalFilter viewWithTag:10] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [viewLocalFilter viewWithTag:11].layer.cornerRadius = 5.0f;
    
    //For Global
    [viewGlobalFilter viewWithTag:18].layer.cornerRadius = 5.0f;
    [scrollGlobalFilter viewWithTag:19].layer.cornerRadius = 5.0f;
    [scrollGlobalFilter viewWithTag:20].layer.cornerRadius = 5.0f;
    [scrollGlobalFilter viewWithTag:21].layer.cornerRadius = 5.0f;
    [[scrollGlobalFilter viewWithTag:22] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [[scrollGlobalFilter viewWithTag:23] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];
    [[scrollGlobalFilter viewWithTag:24] setValue:placeHolderColor forKeyPath:@"_placeholderLabel.textColor"];

}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for get the list of Group
 * @Modified Date 30/09/2015
 */
#pragma mark Group List
-(void)getGroupDetail {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(getGroupDetailDelay) withObject:nil afterDelay:0.1];
}
-(void)getGroupDetailDelay {
    
    if (isNetworkAvailable) {
        
        NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
        NSString *findLocalGlobalStr = [userDefaultManager valueForKey:@"filter_type"];
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:findLocalGlobalStr,@"group",pageCountStr,@"page_no",recordPerPage,@"record_per_page",@"",@"milesfilter",@"",@"searchbylocation",@"",@"sorting",@"",@"time",@"",@"day",findLocalGlobalStr,@"list",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getGroups" controls:@"groups" httpMethod:@"POST" data:data];
        [self hideTable];
        if (parsedJSONToken != nil) {
            [self showTable];
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalGroups"];
            [userDefaultManager setObject:pageNoString forKey:@"page_no"];
            [userDefaultManager setObject:totalRecString forKey:@"totalRecord"];
            NSArray *array = [parsedJSONToken valueForKey:@"result"];
            for (NSDictionary *dictionary in array){
                [recordArray addObject:dictionary];
            }
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }

    [tableView reloadData];
    [[AppDelegate currentDelegate] removeLoading];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Selected Sorted By Local
 * @Modified Date 9/07/2015
 */
#pragma mark Shorted By Local
-(IBAction)ShortedByLocal:(UIButton *)sender {

    if (sender.tag == 100) {
        [btnMostMemberLocal setImage:self.selectedImage forState:UIControlStateNormal];
        [btnNewestLocal setImage:self.unSelectedImage forState:UIControlStateNormal];
    }
    else {
        [btnMostMemberLocal setImage:self.unSelectedImage forState:UIControlStateNormal];
        [btnNewestLocal setImage:self.selectedImage forState:UIControlStateNormal];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Selected Sorted By Global
 * @Modified Date 21/10/2015
 */
#pragma mark Shorted By Global
-(IBAction)ShortedByGlobal:(UIButton *)sender {
    
    if (sender.tag == 100) {
        [btnMostMemberGlobal setImage:self.selectedImage forState:UIControlStateNormal];
        [btnNewestGlobal setImage:self.unSelectedImage forState:UIControlStateNormal];
    }
    else {
        [btnMostMemberGlobal setImage:self.unSelectedImage forState:UIControlStateNormal];
        [btnNewestGlobal setImage:self.selectedImage forState:UIControlStateNormal];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for open picker to select Miles
 * @Modified Date 21/10/2015
 */
#pragma mark Select Miles
-(IBAction)selectmiles:(UIButton*)sender {
    
    [self.view endEditing:YES];
    
    if (prefrencePikerView) {
        [prefrencePikerView removeFromSuperview];
        prefrencePikerView=nil;
    }
    if (prefrenceToolBar) {
        [prefrenceToolBar removeFromSuperview];
        prefrenceToolBar=nil;
    }
    
    prefrencePikerView = [[UIPickerView alloc] initWithFrame:CGRectMake(0, self.view.frame.size.height+50, self.view.frame.size.width, 160)];
    prefrencePikerView.delegate = self;
    prefrencePikerView.showsSelectionIndicator = YES;
    [prefrencePikerView setBackgroundColor:[UIColor whiteColor]];
    [UIView beginAnimations:@"animateTableView" context:nil];
    [UIView setAnimationDuration:0.3];
    prefrencePikerView.frame = CGRectMake(0, self.view.frame.size.height-160, self.view.frame.size.width, 160);
    [UIView commitAnimations];
    [self.view addSubview:prefrencePikerView];
    
    prefrenceToolBar = [[UIToolbar alloc] initWithFrame:CGRectMake(0, self.view.frame.size.height, self.view.frame.size.width, 50)];
    prefrenceToolBar.barStyle = UIBarStyleBlack;
    prefrenceToolBar.translucent = YES;
    prefrenceToolBar.tintColor = nil;
    [UIView beginAnimations:@"animateTableView" context:nil];
    [UIView setAnimationDuration:0.3];
    prefrenceToolBar.frame = CGRectMake(0, self.view.frame.size.height-210, self.view.frame.size.width, 50);
    [UIView commitAnimations];
    [self.view addSubview:prefrenceToolBar];
    
    UIButton *itemSelectFromPicker = [UIButton buttonWithType:UIButtonTypeCustom];
    itemSelectFromPicker.frame = CGRectMake(0, 0, 70, 40);
    [itemSelectFromPicker setBackgroundColor:orangeColour];
    itemSelectFromPicker.layer.cornerRadius = 5;
    itemSelectFromPicker.clipsToBounds = YES;
    [itemSelectFromPicker setUserInteractionEnabled:YES];
    [itemSelectFromPicker.titleLabel setFont:[UIFont fontWithName:@"Graphik-Regular" size:20.0f]];
    [itemSelectFromPicker setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
    [itemSelectFromPicker setTitle:@"Select" forState:UIControlStateNormal];
    [itemSelectFromPicker addTarget:self action:@selector(doneButtonClick) forControlEvents:UIControlEventTouchUpInside];
    itemSelectFromPicker.showsTouchWhenHighlighted = YES;
    UIBarButtonItem* doneButton = [[UIBarButtonItem alloc] initWithCustomView:itemSelectFromPicker];
    
    UIBarButtonItem *EportSpaceBetween = [[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemFlexibleSpace target:nil action:nil];
    
    UIButton *cancelPickerButton = [UIButton buttonWithType:UIButtonTypeCustom];
    cancelPickerButton.frame = CGRectMake(0, 0, 80, 40);
    [cancelPickerButton setBackgroundColor:[UIColor clearColor]];
    [cancelPickerButton setBackgroundColor:[UIColor colorWithRed:0.8901960784 green:0.8980392157 blue:0.9137254902 alpha:1]];
    cancelPickerButton.layer.cornerRadius = 5;
    cancelPickerButton.clipsToBounds = YES;
    [cancelPickerButton setUserInteractionEnabled:YES];
    [cancelPickerButton setBackgroundColor:blackCancelBTN];
    [cancelPickerButton.titleLabel setFont:[UIFont fontWithName:@"Graphik-Regular" size:20.0f]];
    [cancelPickerButton setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
    [cancelPickerButton setTitle:@"Cancel" forState:UIControlStateNormal];
    [cancelPickerButton addTarget:self action:@selector(cancelCollection) forControlEvents:UIControlEventTouchUpInside];
    cancelPickerButton.showsTouchWhenHighlighted = YES;
    UIBarButtonItem* cancelButton = [[UIBarButtonItem alloc] initWithCustomView:cancelPickerButton];
    
    [prefrenceToolBar setItems:[NSArray arrayWithObjects:doneButton,EportSpaceBetween,cancelButton, nil]];
    
    if (milesArray.count) {
        NSString *text = [(UITextField *)[scrollLocalFilter viewWithTag:6] text];
        if (CheckStringForNull(text)) {
            selectMilesIndex = 0;
            saveMilesIndex = 0;
        }
        
        [prefrencePikerView selectRow:saveMilesIndex inComponent:0 animated:NO];
    }
}

-(void)cancelCollection {
    
    [UIView beginAnimations:@"animateTableView" context:nil];
    [UIView setAnimationDuration:0.3];
    [UIView setAnimationDelegate:self];
    prefrencePikerView.frame = CGRectMake(0, self.view.frame.size.height+50, self.view.frame.size.width, 160);
    [UIView commitAnimations];
    
    [UIView beginAnimations:@"animateTableView" context:nil];
    [UIView setAnimationDuration:0.3];
    prefrenceToolBar.frame = CGRectMake(0, self.view.frame.size.height, self.view.frame.size.width, 50);
    [UIView setAnimationDelegate:self];
    [UIView setAnimationDidStopSelector:@selector(removePicker)];
    [UIView commitAnimations];
    
}

-(void)doneButtonClick {
    
    saveMilesIndex = selectMilesIndex;
    if (milesArray.count) {
        NSString *milesStr = [[milesArray objectAtIndex:saveMilesIndex] valueForKey:@"value"];
        [(UITextField *)[scrollLocalFilter viewWithTag:6] setText:milesStr];
    }
    
    [UIView beginAnimations:@"animateTableView" context:nil];
    [UIView setAnimationDuration:0.3];
    [UIView setAnimationDelegate:self];
    prefrencePikerView.frame = CGRectMake(0, self.view.frame.size.height+50, self.view.frame.size.width, 160);
    [UIView commitAnimations];
    
    [UIView beginAnimations:@"animateTableView" context:nil];
    [UIView setAnimationDuration:0.3];
    prefrenceToolBar.frame = CGRectMake(0, self.view.frame.size.height, self.view.frame.size.width, 50);
    [UIView setAnimationDidStopSelector:@selector(removePicker)];
    [UIView commitAnimations];
}

-(void)removePicker {
    [prefrenceToolBar removeFromSuperview];
    [prefrencePikerView removeFromSuperview];
}


#pragma mark PICKER VIEW DELEGATE METHOD
- (NSInteger)numberOfComponentsInPickerView:(UIPickerView *)thePickerView {
    
    return 1;
}

- (NSInteger)pickerView:(UIPickerView *)thePickerView numberOfRowsInComponent:(NSInteger)component {
    
   return [milesArray count];
    
  }

- (NSString *)pickerView:(UIPickerView *)thePickerView titleForRow:(NSInteger)row forComponent:(NSInteger)component {
    
    
    return [[milesArray objectAtIndex:row] valueForKey:@"value"];
    
}

- (void)pickerView:(UIPickerView *)pickerView didSelectRow: (NSInteger)row inComponent:(NSInteger)component {
    
    selectMilesIndex = row;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for open picker to select Day
 * @Modified Date 21/10/2015
 */
#pragma mark Select Day
-(IBAction)selectDay:(UIButton*)sender {
    
        actualDaySelectedArray = [[NSMutableArray alloc] initWithArray:populateDayArray];
    
        if (dayTimeBGView != nil) {
            [dayTimeBGView removeFromSuperview];
        }
        dayTimeBGView = [[UIView alloc] initWithFrame:CGRectMake(0, 0, self.view.frame.size.width, self.view.frame.size.height)];
        dayTimeBGView.backgroundColor = [UIColor colorWithWhite:0.0 alpha:0.0];
        dayTimeBGView.opaque = NO;
        [self.view addSubview:dayTimeBGView];
        
        int Height = 350;
        if (CheckDeviceFunction()) {
            Height = 500;
        }
        
        dayTimeView = [[UIView alloc] initWithFrame:CGRectMake(0,dayTimeBGView.frame.size.height,dayTimeBGView.frame.size.width, Height)];
        dayTimeView.backgroundColor = [UIColor whiteColor];
        [UIView beginAnimations:@"animateTableView" context:nil];
        [UIView setAnimationDuration:0.3];
        dayTimeView.frame = CGRectMake(0, dayTimeBGView.frame.size.height-Height, dayTimeBGView.frame.size.width, Height);
        [UIView commitAnimations];
        [dayTimeBGView addSubview:dayTimeView];
        
        if (prefrenceToolBar) {
            [prefrenceToolBar removeFromSuperview];
            prefrenceToolBar=nil;
        }
        prefrenceToolBar = [[UIToolbar alloc] initWithFrame:CGRectMake(0,0,dayTimeBGView.frame.size.width, 50)];
        prefrenceToolBar.barStyle = UIBarStyleBlack;
        prefrenceToolBar.translucent = YES;
        prefrenceToolBar.tintColor = nil;
        [dayTimeView addSubview:prefrenceToolBar];
        
        UIButton *selectFilterBTN = [UIButton buttonWithType:UIButtonTypeCustom];
        selectFilterBTN.frame = CGRectMake(0, 10, 70, 30);
        [selectFilterBTN setBackgroundColor:orangeColour];
        selectFilterBTN.layer.cornerRadius = 5;
        selectFilterBTN.clipsToBounds = YES;
        [selectFilterBTN setUserInteractionEnabled:YES];
        [selectFilterBTN.titleLabel setFont:[UIFont fontWithName:@"Graphik-Regular" size:17.0f]];
        [selectFilterBTN setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
        [selectFilterBTN setTitle:@"APPLY" forState:UIControlStateNormal];
        [selectFilterBTN addTarget:self action:@selector(doneDayValue) forControlEvents:UIControlEventTouchUpInside];
        selectFilterBTN.showsTouchWhenHighlighted = YES;
        UIBarButtonItem* doneButton = [[UIBarButtonItem alloc] initWithCustomView:selectFilterBTN];
        
        UIBarButtonItem *EportSpaceBetween = [[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemFlexibleSpace target:nil action:nil];
        
        UIButton *cancelFilterBTN = [UIButton buttonWithType:UIButtonTypeCustom];
        cancelFilterBTN.frame = CGRectMake(0, 10, 80, 30);
        [cancelFilterBTN setBackgroundColor:[UIColor clearColor]];
        [cancelFilterBTN setBackgroundColor:[UIColor colorWithRed:0.8901960784 green:0.8980392157 blue:0.9137254902 alpha:1]];
        cancelFilterBTN.layer.cornerRadius = 5;
        cancelFilterBTN.clipsToBounds = YES;
        [cancelFilterBTN setTag:100];
        [cancelFilterBTN setUserInteractionEnabled:YES];
        [cancelFilterBTN setBackgroundColor:blackCancelBTN];
        [cancelFilterBTN.titleLabel setFont:[UIFont fontWithName:@"Graphik-Regular" size:17.0f]];
        [cancelFilterBTN setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
        [cancelFilterBTN setTitle:@"CANCEL" forState:UIControlStateNormal];
        [cancelFilterBTN addTarget:self action:@selector(cancelDayTimeBGView:) forControlEvents:UIControlEventTouchUpInside];
        cancelFilterBTN.showsTouchWhenHighlighted = YES;
        UIBarButtonItem* cancelButton = [[UIBarButtonItem alloc] initWithCustomView:cancelFilterBTN];
        
        [prefrenceToolBar setItems:[NSArray arrayWithObjects:doneButton,EportSpaceBetween,cancelButton, nil]];
        
        //For Scroll View
        scrollDayTime = [[TPKeyboardAvoidingScrollView alloc] initWithFrame:CGRectMake(0, 50, dayTimeView.frame.size.width, dayTimeView.frame.size.height-50)];
        [scrollDayTime setBackgroundColor:[UIColor clearColor]];
        [dayTimeView addSubview:scrollDayTime];
    
        int Y = 10;
        addDayArray = [[NSMutableArray alloc] init];
        for (int i = 0; i<dayArray.count; i++) {
            
            NSString *valueString = [[dayArray objectAtIndex:i] valueForKey:@"value"];
            
            UIImageView *imgSelect = [[UIImageView alloc] initWithFrame:CGRectMake(20, Y, 30, 30)];
            [imgSelect setBackgroundColor:[UIColor clearColor]];
            BOOL selected = [[populateDayArray objectAtIndex:i] boolValue];
            if (selected) {
                [imgSelect setImage:self.selectedImage];
            }
            else {
                [imgSelect setImage:self.unSelectedImage];
            }
            imgSelect.layer.cornerRadius = 15.0f;
            imgSelect.tag = i;
            imgSelect.clipsToBounds = YES;
            [scrollDayTime addSubview:imgSelect];
            
            UILabel *label = [[UILabel alloc] initWithFrame:CGRectMake(60, Y, scrollDayTime.frame.size.width-70, 30)];
            label.text = valueString;
            label.textAlignment = NSTextAlignmentLeft;
            label.textColor = [UIColor blackColor];
            [label setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
            [scrollDayTime addSubview:label];
            
            UIButton *btnSelect = [UIButton buttonWithType:UIButtonTypeCustom];
            btnSelect.frame = CGRectMake(20, Y, scrollDayTime.frame.size.width-40, 30);
            btnSelect.tag = i;
            [btnSelect setBackgroundColor:[UIColor clearColor]];
            [btnSelect addTarget:self action:@selector(selectDayFilterValue:) forControlEvents:UIControlEventTouchUpInside];
            [scrollDayTime addSubview:btnSelect];
            
            Y = Y+40;
            
            [addDayArray addObject:[NSDictionary dictionaryWithObjectsAndKeys:imgSelect,@"image",label,@"filter", nil]];
        }
        
        scrollDayTime.contentSize = CGSizeMake(scrollDayTime.frame.size.width, Y+30);
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for open picker to select Time
 * @Modified Date 21/10/2015
 */
#pragma mark Select Time
-(IBAction)selectTime:(UIButton*)sender {
    
    actualTimeSelectArray = [[NSMutableArray alloc] initWithArray:populateTimeArray];
    
    if (dayTimeBGView != nil) {
        [dayTimeBGView removeFromSuperview];
    }
    dayTimeBGView = [[UIView alloc] initWithFrame:CGRectMake(0, 0, self.view.frame.size.width, self.view.frame.size.height)];
    dayTimeBGView.backgroundColor = [UIColor colorWithWhite:0.0 alpha:0.0];
    dayTimeBGView.opaque = NO;
    [self.view addSubview:dayTimeBGView];
    
    int Height = 350;
    if (CheckDeviceFunction()) {
        Height = 500;
    }
    
    dayTimeView = [[UIView alloc] initWithFrame:CGRectMake(0,dayTimeBGView.frame.size.height,dayTimeBGView.frame.size.width, Height)];
    dayTimeView.backgroundColor = [UIColor whiteColor];
    [UIView beginAnimations:@"animateTableView" context:nil];
    [UIView setAnimationDuration:0.3];
    dayTimeView.frame = CGRectMake(0, dayTimeBGView.frame.size.height-Height, dayTimeBGView.frame.size.width, Height);
    [UIView commitAnimations];
    [dayTimeBGView addSubview:dayTimeView];
    
    if (prefrenceToolBar) {
        [prefrenceToolBar removeFromSuperview];
        prefrenceToolBar=nil;
    }
    prefrenceToolBar = [[UIToolbar alloc] initWithFrame:CGRectMake(0,0,dayTimeBGView.frame.size.width, 50)];
    prefrenceToolBar.barStyle = UIBarStyleBlack;
    prefrenceToolBar.translucent = YES;
    prefrenceToolBar.tintColor = nil;
    [dayTimeView addSubview:prefrenceToolBar];
    
    UIButton *selectFilterBTN = [UIButton buttonWithType:UIButtonTypeCustom];
    selectFilterBTN.frame = CGRectMake(0, 10, 70, 30);
    [selectFilterBTN setBackgroundColor:orangeColour];
    selectFilterBTN.layer.cornerRadius = 5;
    selectFilterBTN.clipsToBounds = YES;
    [selectFilterBTN setUserInteractionEnabled:YES];
    [selectFilterBTN.titleLabel setFont:[UIFont fontWithName:@"Graphik-Regular" size:17.0f]];
    [selectFilterBTN setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
    [selectFilterBTN setTitle:@"APPLY" forState:UIControlStateNormal];
    [selectFilterBTN addTarget:self action:@selector(doneTimeValue) forControlEvents:UIControlEventTouchUpInside];
    selectFilterBTN.showsTouchWhenHighlighted = YES;
    UIBarButtonItem* doneButton = [[UIBarButtonItem alloc] initWithCustomView:selectFilterBTN];
    
    UIBarButtonItem *EportSpaceBetween = [[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemFlexibleSpace target:nil action:nil];
    
    UIButton *cancelFilterBTN = [UIButton buttonWithType:UIButtonTypeCustom];
    cancelFilterBTN.frame = CGRectMake(0, 10, 80, 30);
    [cancelFilterBTN setBackgroundColor:[UIColor clearColor]];
    [cancelFilterBTN setBackgroundColor:[UIColor colorWithRed:0.8901960784 green:0.8980392157 blue:0.9137254902 alpha:1]];
    cancelFilterBTN.layer.cornerRadius = 5;
    [cancelFilterBTN setTag:101];
    cancelFilterBTN.clipsToBounds = YES;
    [cancelFilterBTN setUserInteractionEnabled:YES];
    [cancelFilterBTN setBackgroundColor:blackCancelBTN];
    [cancelFilterBTN.titleLabel setFont:[UIFont fontWithName:@"Graphik-Regular" size:17.0f]];
    [cancelFilterBTN setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
    [cancelFilterBTN setTitle:@"CANCEL" forState:UIControlStateNormal];
    [cancelFilterBTN addTarget:self action:@selector(cancelDayTimeBGView:) forControlEvents:UIControlEventTouchUpInside];
    cancelFilterBTN.showsTouchWhenHighlighted = YES;
    UIBarButtonItem* cancelButton = [[UIBarButtonItem alloc] initWithCustomView:cancelFilterBTN];
    
    [prefrenceToolBar setItems:[NSArray arrayWithObjects:doneButton,EportSpaceBetween,cancelButton, nil]];
    
    //For Scroll View
    scrollDayTime = [[TPKeyboardAvoidingScrollView alloc] initWithFrame:CGRectMake(0, 50, dayTimeView.frame.size.width, dayTimeView.frame.size.height-50)];
    [scrollDayTime setBackgroundColor:[UIColor clearColor]];
    [dayTimeView addSubview:scrollDayTime];
    
    int Y = 10;
    addTimeArray = [[NSMutableArray alloc] init];
    for (int i = 0; i<timeArray.count; i++) {
        
        NSString *valueString = [[timeArray objectAtIndex:i] valueForKey:@"value"];
        
        UIImageView *imgSelect = [[UIImageView alloc] initWithFrame:CGRectMake(20, Y, 30, 30)];
        [imgSelect setBackgroundColor:[UIColor clearColor]];
        
        BOOL selected = [[populateTimeArray objectAtIndex:i] boolValue];
        if (selected) {
            [imgSelect setImage:self.selectedImage];
        }
        else {
            [imgSelect setImage:self.unSelectedImage];
        }
        
        imgSelect.layer.cornerRadius = 15.0f;
        imgSelect.tag = i;
        imgSelect.clipsToBounds = YES;
        [scrollDayTime addSubview:imgSelect];
        
        UILabel *label = [[UILabel alloc] initWithFrame:CGRectMake(60, Y, scrollDayTime.frame.size.width-70, 30)];
        label.text = valueString;
        label.textAlignment = NSTextAlignmentLeft;
        label.textColor = [UIColor blackColor];
        [label setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
        [scrollDayTime addSubview:label];
        
        UIButton *btnSelect = [UIButton buttonWithType:UIButtonTypeCustom];
        btnSelect.frame = CGRectMake(20, Y, scrollDayTime.frame.size.width-40, 30);
        btnSelect.tag = i;
        [btnSelect setBackgroundColor:[UIColor clearColor]];
        [btnSelect addTarget:self action:@selector(selectTimeFilterValue:) forControlEvents:UIControlEventTouchUpInside];
        [scrollDayTime addSubview:btnSelect];
        
        [addTimeArray addObject:[NSDictionary dictionaryWithObjectsAndKeys:imgSelect,@"image",label,@"filter", nil]];
        
        Y = Y+40;
    }
    
    scrollDayTime.contentSize = CGSizeMake(scrollDayTime.frame.size.width, Y+30);

}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Day Time BG View Cancel
 * @Modified Date 21/10/2015
 */
#pragma mark Day Time BG View Cancel
-(void)cancelDayTimeBGView:(UIButton *)sender {
    if (sender.tag == 100) {
        populateDayArray = [[NSMutableArray alloc] initWithArray:actualDaySelectedArray];
    }
    else {
        populateTimeArray = [[NSMutableArray alloc] initWithArray:actualTimeSelectArray];
    }
    [dayTimeBGView removeFromSuperview];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Done Day Value
 * @Modified Date 21/10/2015
 */
#pragma mark Done Day Value
-(void)doneDayValue {
    NSString *dayString = @"";
    for (int i = 0; i<populateDayArray.count; i++) {
        BOOL selected = [[populateDayArray objectAtIndex:i] boolValue];
        if (selected) {
            if (CheckStringForNull(dayString)) {
                dayString = [NSString stringWithFormat:@"%@",[[dayArray objectAtIndex:i] valueForKey:@"value"]];
            }
            else {
                NSString *string = [NSString stringWithFormat:@",%@",[[dayArray objectAtIndex:i] valueForKey:@"value"]];
                dayString = [dayString stringByAppendingString:string];
            }
        }
    }
    if ([viewIdentify isEqualToString:@"local"]) {
        [(UITextField *)[scrollLocalFilter viewWithTag:9] setText:dayString];
    }
    else {
        [(UITextField *)[scrollGlobalFilter viewWithTag:23] setText:dayString];
    }
    [dayTimeBGView removeFromSuperview];
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Select Day Filter Value
 * @Modified Date 21/10/2015
 */
#pragma mark Select Day Filter Value
-(void)selectDayFilterValue:(UIButton *)sender {
    UIImageView *imgView = [[addDayArray objectAtIndex:sender.tag] valueForKey:@"image"];
    if (imgView.image == self.selectedImage) {
        imgView.image = self.unSelectedImage;
    }
    else {
        imgView.image = self.selectedImage;
    }
    BOOL selected = [[populateDayArray objectAtIndex:sender.tag] boolValue];
    [populateDayArray replaceObjectAtIndex:sender.tag withObject:[NSNumber numberWithBool:!selected]];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Done Time Value
 * @Modified Date 21/10/2015
 */
#pragma mark Done Time Value
-(void)doneTimeValue {
    NSString *timeString = @"";
    for (int i = 0; i<populateTimeArray.count; i++) {
        BOOL selected = [[populateTimeArray objectAtIndex:i] boolValue];
        if (selected) {
            if (CheckStringForNull(timeString)) {
                timeString = [NSString stringWithFormat:@"%@",[[timeArray objectAtIndex:i] valueForKey:@"value"]];
            }
            else {
                NSString *string = [NSString stringWithFormat:@",%@",[[timeArray objectAtIndex:i] valueForKey:@"value"]];
                timeString = [timeString stringByAppendingString:string];
            }
        }
    }
    if ([viewIdentify isEqualToString:@"local"]) {
        
        [(UITextField *)[scrollLocalFilter viewWithTag:10] setText:timeString];
    }
    else {
         [(UITextField *)[scrollGlobalFilter viewWithTag:24] setText:timeString];
    }
    [dayTimeBGView removeFromSuperview];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Select Time Filter Value
 * @Modified Date 21/10/2015
 */
#pragma mark Select Time Filter Value
-(void)selectTimeFilterValue:(UIButton *)sender {
    UIImageView *imgView = [[addTimeArray objectAtIndex:sender.tag] valueForKey:@"image"];
    if (imgView.image == self.selectedImage) {
        imgView.image = self.unSelectedImage;
    }
    else {
        imgView.image = self.selectedImage;
    }
    BOOL selected = [[populateTimeArray objectAtIndex:sender.tag] boolValue];
    [populateTimeArray replaceObjectAtIndex:sender.tag withObject:[NSNumber numberWithBool:!selected]];
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for insert bool value in array according to list
 * @Modified Date 21/10/2015
 */
#pragma mark populate Day Selected
- (void)populateDaySelected {
    NSMutableArray *array = [[NSMutableArray alloc] initWithCapacity:[dayArray count]];
    for (int i=0; i < [dayArray count]; i++)
        [array addObject:[NSNumber numberWithBool:NO]];
    populateDayArray = array;
}
/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for insert bool value in array according to list
 * @Modified Date 21/10/2015
 */
#pragma mark populate Time Selected
- (void)populateTimeSelected {
    NSMutableArray *array = [[NSMutableArray alloc] initWithCapacity:[timeArray count]];
    for (int i=0; i < [timeArray count]; i++)
        [array addObject:[NSNumber numberWithBool:NO]];
    populateTimeArray = array;
}

/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for Finish load more
 * @Modified Date 9/07/2015
 */
#pragma mark Finish Load More
- (void)finishLoadMore {
    [tableView finishLoadMore];
    if (isNetworkAvailable) {
        NSString *checkModule = [userDefaultManager valueForKey:@"module"];
        NSString *pageString = [userDefaultManager valueForKey:@"page_no"];
        NSString *totalRecString = [userDefaultManager valueForKey:@"totalRecord"];
        NSString *findLocalGlobalStr = [userDefaultManager valueForKey:@"filter_type"];
        int value = checkModule.intValue;
        switch (value) {
            case 0:
                if (recordArray.count < totalRecString.intValue) {
                    int pageCount = pageString.intValue;
                    pageCount = pageCount+1; 
                    NSString *pageCountStr = [NSString stringWithFormat:@"%d",pageCount];
                    [userDefaultManager setObject:pageCountStr forKey:@"page_no"];
                    
                    NSArray *aarrayTi = [userDefaultManager valueForKey:@"timesave"];
                    NSArray *aarrayDa = [userDefaultManager valueForKey:@"daysave"];
                    NSString *mileStr = [userDefaultManager valueForKey:@"mileSave"];
                    NSString *locatStr = [userDefaultManager valueForKey:@"locationSave"];
                    NSString *sortStr = [userDefaultManager valueForKey:@"sortSave"];
                    if (CheckStringForNull(mileStr) && CheckStringForNull(locatStr) && CheckStringForNull(sortStr) && !aarrayTi.count >0 && !aarrayDa.count>0) {
                        [self getGroupDetail];
                    }
                    else {
                        if ([findLocalGlobalStr isEqualToString:@"local"]) {
                            viewIdentify = @"local";
                        }
                        else {
                            viewIdentify = @"global";
                        }
                        [self doneFilter];
                    }
                }
                break;
                
            default:
                break;
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
}

- (void)dragTableDidTriggerLoadMore:(UITableView *)tableView
{
    //send load more request(generally network request) here
    
    [self performSelector:@selector(finishLoadMore) withObject:nil afterDelay:2];
}

- (void)dragTableLoadMoreCanceled:(UITableView *)tableView
{
    //cancel load more request(generally network request) here
    
    [NSObject cancelPreviousPerformRequestsWithTarget:self selector:@selector(finishLoadMore) object:nil];
}


#pragma marl - UITableView Data Source
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return recordArray.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView1 cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    static NSString *PlaceholderCellIdentifier = @"GroupTableCell";
    
    GroupTableCell*cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
    cell.backgroundColor = [UIColor clearColor];
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
    if (cell == nil) {
        cell = [[GroupTableCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
    }
    
    if (recordArray.count) {
        
        NSDictionary *dict = [recordArray objectAtIndex:indexPath.row];
        
        //For Show WeekDay And Time
        NSString *weekDayStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"meetingDate"]];
        NSString *weekDayFinalStr  = @"";
        if (CheckStringForNull(weekDayStr)) {
            weekDayFinalStr = @"";
            cell.lblDate.text = @"";
        }
        else {
            NSArray *arrayOne = [weekDayStr componentsSeparatedByString:@" "];
            NSString *finalStr = [arrayOne objectAtIndex:0];
            NSArray *arrayTwo = [finalStr componentsSeparatedByString:@"-"];
            
            if (arrayTwo.count<4) {
                NSDateComponents *comps = [[NSDateComponents alloc] init];
                [comps setDay:[[arrayTwo objectAtIndex:2] intValue]];
                [comps setMonth:[[arrayTwo objectAtIndex:1] intValue]];
                [comps setYear:[[arrayTwo objectAtIndex:0] intValue]];
                NSCalendar *gregorian = [[NSCalendar alloc]
                                         initWithCalendarIdentifier:NSGregorianCalendar];
                NSDate *date = [gregorian dateFromComponents:comps];
                NSDateComponents *weekdayComponents = [gregorian components:NSWeekdayCalendarUnit fromDate:date];
                int weekday = [weekdayComponents weekday];
                switch (weekday) {
                    case 1:
                        weekDayFinalStr = @"Sunday";
                        break;
                    case 2:
                        weekDayFinalStr = @"Monday";
                        break;
                    case 3:
                        weekDayFinalStr = @"Tuesday";
                        break;
                    case 4:
                        weekDayFinalStr = @"Wednesday";
                        break;
                    case 5:
                        weekDayFinalStr = @"Thursday";
                        break;
                    case 6:
                        weekDayFinalStr = @"Friday";
                        break;
                    case 7:
                        weekDayFinalStr = @"Saturday";
                        break;
                        
                    default:
                        break;
                }
            }
        }
        
        //For Calculate Time
        NSString *timeStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"meetingTime"]];
        NSString *finalTime = @"";
        if (CheckStringForNull(timeStr)) {
            finalTime = @"";
        }
        else {
            timeStr = [NSString stringWithFormat:@" %@",[dict valueForKey:@"meetingTime"]];
            timeStr = [weekDayStr stringByAppendingString:timeStr];
            finalTime = [NSString stringWithFormat:@" %@",[self stringDatelocalizedFromString:timeStr]];
            
        }
        NSString *finalDayAndTimeString = [weekDayFinalStr stringByAppendingString:finalTime];
        NSMutableAttributedString * string = nil;
        if (!CheckStringForNull(finalDayAndTimeString)) {
            string = [[NSMutableAttributedString alloc] initWithString:finalDayAndTimeString];
            [string addAttribute:NSForegroundColorAttributeName value:[UIColor darkGrayColor] range:NSMakeRange(0,weekDayFinalStr.length)];
            [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:15.0] range:NSMakeRange(0,weekDayFinalStr.length)];
            [string addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:NSMakeRange(weekDayFinalStr.length , finalTime.length)];
            [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Regular" size:17.0] range:NSMakeRange(weekDayFinalStr.length , finalTime.length)];
        }
        
        cell.lblDate.attributedText = string;

        //For Set Group Type
        cell.lblGroupType.layer.cornerRadius = 5.0f;
        cell.lblGroupType.clipsToBounds = YES;
        
        //For Set Group Name
        NSString *groupTypeStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"groupType"]];
        if (CheckStringForNull(groupTypeStr)) {
            groupTypeStr = @"";
        }
        else {
            groupTypeStr = [[NSString stringWithFormat:@"%@",[dict valueForKey:@"groupType"]] uppercaseString];
        }
        if ([groupTypeStr isEqualToString:@"LOCAL"]) {
            [cell.lblGroupType setBackgroundColor:[UIColor colorWithRed:217.0/255.0 green:214.0/255.0 blue:47.0/255.0 alpha:1]];
        }
        else {
            [cell.lblGroupType setBackgroundColor:[UIColor colorWithRed:102.0/255.0 green:144.0/255.0 blue:188.0/255.0 alpha:1]];
        }
        cell.lblGroupType.text = groupTypeStr;
        
        //For Set Gropu Number
         NSString *groupNumberStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"groupName"]];
        groupNumberStr = [groupNumberStr stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                   withString:[[groupNumberStr substringToIndex:1] capitalizedString]];
        cell.lblGroupNumber.text = groupNumberStr;
        
        //For Address
        NSString *addressStateStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"stateName"]];
        if (CheckStringForNull(addressStateStr)) {
            addressStateStr = @"";
        }
        else {
            addressStateStr = [NSString stringWithFormat:@"%@, ",[dict valueForKey:@"stateName"]];
        }
        NSString *addressCountryStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"countryName"]];
        if (CheckStringForNull(addressCountryStr)) {
            addressCountryStr = @"";
        }
        addressStateStr = [addressStateStr stringByAppendingString:addressCountryStr];
        cell.lblAddress.text = addressStateStr;

        //For Set User Group
        for (int i = 1; i<=20; i++) {
            UIImageView *imgView = (UIImageView *) [cell viewWithTag:i];
            
            NSString *memberStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"members"]];
            if (i <= memberStr.intValue) {
                if ([groupTypeStr isEqualToString:@"LOCAL"]) {
                    [imgView setImage:[UIImage imageNamed:@"localGroupUseHigIcon"]];
                }
                else {
                    [imgView setImage:[UIImage imageNamed:@"GlobalGroupUseHigIcon"]];
                }
            }
            else {
                [imgView setImage:[UIImage imageNamed:@"GroupUseIcon"]];
            }
        }

    }
    
    return cell;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for convert date and time into GMT
 * @Modified Date 15/10/2015
 */
#pragma mark Convert Date And Time
-(NSString *)stringDatelocalizedFromString:(NSString *)dateFromServer {
    NSDateFormatter *formatter=[[NSDateFormatter alloc]init];
    [formatter setDateFormat:@"yyyy-MM-dd HH:mm"];
    [formatter setTimeZone:[NSTimeZone timeZoneForSecondsFromGMT:(0)]];
    NSDate *date =[formatter dateFromString:dateFromServer];
    
    NSDateFormatter *dateFormatter1 = [[NSDateFormatter alloc] init];
    [dateFormatter1 setDateFormat:@"hh:mm a"];
    [dateFormatter1 setTimeZone:[NSTimeZone timeZoneForSecondsFromGMT:(0)]];
    NSString *formattedDateString = [dateFormatter1 stringFromDate:date];

    return formattedDateString;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    
    NSDictionary *dict = [recordArray objectAtIndex:indexPath.row];
    selectedGroupIdString = [dict valueForKey:@"id"];
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Do you want to join the group?" delegate:self cancelButtonTitle:nil otherButtonTitles:@"Ok",@"Cancel", nil];
    [alert show];
}

// Called when a button is clicked. The view will be automatically dismissed after this call returns
- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {

    if (buttonIndex == 0) {
        
        [self setGroupForLoginUser];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Set Group For Login User
 * @Modified Date 15/10/2015
 */
#pragma mark Set Group For Login User
-(void)setGroupForLoginUser {
    
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(setGroupForLoginUserDelay) withObject:nil afterDelay:0.1];
}
-(void)setGroupForLoginUserDelay {
    
    if (isNetworkAvailable) {
        
        NSString *listPageString  = @"";
        if ([checkString isEqualToString:@"down"]) {
            listPageString = @"global";
        }
        else if ([checkString isEqualToString:@"up"]){
            listPageString = @"local";
        }
        else {
            listPageString = @"groupSelect";
        }

        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:listPageString,@"listPage",selectedGroupIdString,@"groupId",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"selectGroup" controls:@"groups" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            NSString *responseCodeString = [parsedJSONToken valueForKey:@"code"];
            NSString *boolString = [parsedJSONToken valueForKey:@"groupSelected"];
            if ([responseCodeString isEqualToString:@"404"] && boolString.boolValue) {
                
                [[AppDelegate currentDelegate] removeLoading];
                recordArray = [[NSMutableArray alloc] init];
                [userDefaultManager setValue:@"1" forKey:@"page_no"];
                viewIdentify = [userDefaultManager valueForKey:@"filter_type"];
                [self doneFilter];
                return;
            }
            
            NSDictionary *dicct = [parsedJSONToken valueForKey:@"result"];
            NSString *groupIdString = [dicct valueForKey:@"group_id"];
            [userDefaultManager setObject:groupIdString forKey:@"group_id"];
            NSString *messageString = [parsedJSONToken valueForKey:@"message"];
            
            if ([checkString isEqualToString:@"down"]) {
                [self.navigationController popViewControllerAnimated:NO];
                if ([self.groupDelegate respondsToSelector:@selector(GoToHomeScreenFromGroupClass)]) {
                    [self.groupDelegate GoToHomeScreenFromGroupClass];
                }
            }
            else if ([checkString isEqualToString:@"up"]){
                [self.navigationController popViewControllerAnimated:NO];
                if ([self.groupDelegate respondsToSelector:@selector(GoToHomeScreenFromGroupClass)]) {
                    [self.groupDelegate GoToHomeScreenFromGroupClass];
                }
            }
            else {
                [self.navigationController popToRootViewControllerAnimated:NO];
            }
            
            UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:messageString delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [networkAlert show];
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
 * @Parm sender
 * @Description These method are used for Open Actiopn Sheet For Select Group Type
 * @Modified Date 15/10/2015
 */
#pragma mark Open Action Sheet
-(IBAction)OpenActionSheet:(id)sender {
    
    if (!CheckDeviceFunction()) {
        if (!checkSystemVersion()) {
            actionSheet = [[UIActionSheet alloc] initWithTitle:nil
                                                      delegate:self
                                             cancelButtonTitle:nil
                                        destructiveButtonTitle:nil
                                             otherButtonTitles:nil];
            
            [actionSheet showInView:self.view];
        }
    }
    
    UIView *viewBg = [[UIView alloc]init];
    viewBg.frame = CGRectMake(0, 0, self.view.frame.size.width, 320);
    viewBg.backgroundColor = [UIColor whiteColor];
    
    UIButton *localBtn = [UIButton buttonWithType:UIButtonTypeCustom];
    localBtn.frame = CGRectMake(10, 20, viewBg.frame.size.width-20, 40);
    localBtn.layer.cornerRadius = 5.0f;
    [localBtn addTarget:self action:@selector(openLocalViewFilter) forControlEvents:UIControlEventTouchUpInside];
    [localBtn setTitle:@"Local" forState:UIControlStateNormal];
    [localBtn.titleLabel setFont:[UIFont fontWithName:@"Graphik-Regular" size:20.0f]];
    [localBtn setBackgroundColor:blackCancelBTN];
    [localBtn setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
    [viewBg addSubview:localBtn];

    UIButton *globalBtn = [UIButton buttonWithType:UIButtonTypeCustom];
    globalBtn.frame = CGRectMake(10, 80, viewBg.frame.size.width-20, 40);
    globalBtn.layer.cornerRadius = 5.0f;
    [globalBtn addTarget:self action:@selector(openGlobalViewFilter) forControlEvents:UIControlEventTouchUpInside];
    [globalBtn setTitle:@"Global" forState:UIControlStateNormal];
    [globalBtn.titleLabel setFont:[UIFont fontWithName:@"Graphik-Regular" size:20.0f]];
    [globalBtn setBackgroundColor:blackCancelBTN];
    [globalBtn setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
    [viewBg addSubview:globalBtn];
    
    if ([checkString isEqualToString:@"down"]) {
        globalBtn.alpha = 0.5f;
        [globalBtn setUserInteractionEnabled:NO];
    }
    else if ([checkString isEqualToString:@"up"]){
        localBtn.alpha = 0.5f;
        [localBtn setUserInteractionEnabled:NO];
    }
    
    UIButton *cancelBtn = [UIButton buttonWithType:UIButtonTypeCustom];
    cancelBtn.frame = CGRectMake(10, 140, viewBg.frame.size.width-20, 40);
    cancelBtn.layer.cornerRadius = 5.0f;
    [cancelBtn addTarget:self action:@selector(dissmissActionSheet) forControlEvents:UIControlEventTouchUpInside];
    [cancelBtn setTitle:@"Cancel" forState:UIControlStateNormal];
    [cancelBtn.titleLabel setFont:[UIFont fontWithName:@"Graphik-Regular" size:20.0f]];
    [cancelBtn setBackgroundColor:orangeColour];
    [cancelBtn setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
    [viewBg addSubview:cancelBtn];
    
    if (!CheckDeviceFunction()) {
        if (checkSystemVersion()) {
            projectSelector = [UIAlertController alertControllerWithTitle:nil message:nil preferredStyle:UIAlertControllerStyleActionSheet];
            
            [projectSelector addAction:[UIAlertAction actionWithTitle:@"" style:UIAlertActionStyleDefault handler:^(UIAlertAction *action) {
            }]];
            
            [projectSelector.view addSubview:viewBg];
            [self presentViewController:projectSelector animated:NO completion:^{
                CGRect menuRect = projectSelector.view.frame;
                menuRect.origin.y -= 190;
                menuRect.size.height = 320;
                projectSelector.view.frame = menuRect;
                CGRect pickerRect = viewBg.frame;
                pickerRect.origin.y = 35;
                pickerRect.size.width = menuRect.size.width;
                viewBg.frame = pickerRect;
                
                localBtn.frame = CGRectMake(10, 20, viewBg.frame.size.width-20, 40);
                globalBtn.frame = CGRectMake(10, 80, viewBg.frame.size.width-20, 40);
                cancelBtn.frame = CGRectMake(10, 140, viewBg.frame.size.width-20, 40);
            }];
        }
        else {
            CGRect rect;
            //expand the action sheet
            rect = actionSheet.frame;
            rect.size.height +=200;
            rect.origin.y -= 200;
            actionSheet.frame = rect;
            
            //Displace all buttons
            for (UIView *view in actionSheet.subviews) {
                rect = view.frame;
                rect.origin.y += 200;
                view.frame = rect;
            }
            //Add the new view
            [actionSheet addSubview:viewBg];
        }
    }
    else {
        viewBg.frame = CGRectMake(0, 0, 320, 200);
        localBtn.frame = CGRectMake(10, 20, viewBg.frame.size.width-20, 40);
        globalBtn.frame = CGRectMake(10, 80, viewBg.frame.size.width-20, 40);
        cancelBtn.frame = CGRectMake(10, 140, viewBg.frame.size.width-20, 40);
        
        UIViewController *vc = [[UIViewController alloc] init];
        [vc setView:viewBg];
        if (popoverController!=nil) {
            popoverController = nil;
        }
        popoverController = [[UIPopoverController alloc]
                                                  initWithContentViewController:vc];
        popoverController.popoverContentSize = CGSizeMake(320, 200);
        [popoverController presentPopoverFromRect:botomView.frame inView:self.view
                         permittedArrowDirections:UIPopoverArrowDirectionAny animated:YES];

    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Cancel Action Sheet
 * @Modified Date 15/10/2015
 */
#pragma mark Cancel Action Sheet
-(void)dissmissActionSheet {
    [projectSelector dismissViewControllerAnimated:YES completion:nil];
    [actionSheet dismissWithClickedButtonIndex:0 animated:YES];
    [popoverController dismissPopoverAnimated:YES];
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Open Local Filter View
 * @Modified Date 15/10/2015
 */
#pragma mark Open Local Filter View
-(void)openLocalViewFilter {
    
    [self populateDaySelected];
    [self populateTimeSelected];
    
    if (popDayLocalArray.count) {
        populateDayArray = [[NSMutableArray alloc] initWithArray:popDayLocalArray];
    }
    if (popTimeLocalArray.count) {
        populateTimeArray = [[NSMutableArray alloc] initWithArray:popTimeLocalArray];
    }
    
    NSString *text = [(UITextField *)[scrollLocalFilter viewWithTag:6] text];
    if (CheckStringForNull(text)) {
        NSString *milesStr = [[milesArray objectAtIndex:saveMilesIndex] valueForKey:@"value"];
        [(UITextField *)[scrollLocalFilter viewWithTag:6] setText:milesStr];
    }
    
    viewIdentify = @"local";
    [actionSheet dismissWithClickedButtonIndex:0 animated:YES];
    [projectSelector dismissViewControllerAnimated:YES completion:nil];
    [popoverController dismissPopoverAnimated:YES];
    [viewGlobalFilter setHidden:YES];
    [viewLocalFilter setHidden:NO];
    
    [(UITextField *)[scrollLocalFilter viewWithTag:7] setText:[userDefaultManager valueForKey:@"city"]];
    [(UITextField *)[scrollLocalFilter viewWithTag:8] setText:[userDefaultManager valueForKey:@"zipcode"]];
    
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Open Global Filter View
 * @Modified Date 19/10/2015
 */
#pragma mark Open Global Filter View
-(void)openGlobalViewFilter {
    
    [self populateDaySelected];
    [self populateTimeSelected];
    
    if (popDayGlobalArray.count) {
         populateDayArray = [[NSMutableArray alloc] initWithArray:popDayGlobalArray];
    }
    if (popTimeGlobalArray.count) {
        populateTimeArray = [[NSMutableArray alloc] initWithArray:popTimeGlobalArray];
    }

    viewIdentify = @"global";
    [actionSheet dismissWithClickedButtonIndex:0 animated:YES];
    [projectSelector dismissViewControllerAnimated:YES completion:nil];
    [popoverController dismissPopoverAnimated:YES];
    [viewLocalFilter setHidden:YES];
    [viewGlobalFilter setHidden:NO];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Cancel Local Filter View
 * @Modified Date 15/10/2015
 */
#pragma mark Cancel Local Filter View
-(IBAction)cancelLocalFilterView:(id)sender {

    NSString *milesSaveString = [userDefaultManager valueForKey:@"mileSave"];
    if (!CheckStringForNull(milesSaveString)) {
        for (int i = 0; i<milesArray.count; i++) {
            NSString *dummySt = [[milesArray objectAtIndex:i] valueForKey:@"value"];
            NSArray *array = [dummySt componentsSeparatedByString:@" "];
            NSString *milesString = [array objectAtIndex:0];
            if ([milesSaveString isEqualToString:milesString]) {
                selectMilesIndex = i;
                saveMilesIndex = i;
                [(UITextField *)[scrollLocalFilter viewWithTag:6] setText:dummySt];
            }
        }
    }
    
    if (!popDayLocalArray.count) {
        [(UITextField *)[scrollLocalFilter viewWithTag:9] setText:@""];
        [self populateDaySelected];
    }
    else {
        NSString *dayString = @"";
        for (int i = 0; i<popDayLocalArray.count; i++) {
            BOOL selected = [[popDayLocalArray objectAtIndex:i] boolValue];
            if (selected) {
                if (CheckStringForNull(dayString)) {
                    dayString = [NSString stringWithFormat:@"%@",[[dayArray objectAtIndex:i] valueForKey:@"value"]];
                }
                else {
                    NSString *string = [NSString stringWithFormat:@",%@",[[dayArray objectAtIndex:i] valueForKey:@"value"]];
                    dayString = [dayString stringByAppendingString:string];
                }
            }
        }
        [(UITextField *)[scrollLocalFilter viewWithTag:9] setText:dayString];
    }
    if (!popTimeLocalArray.count) {
        [(UITextField *)[scrollLocalFilter viewWithTag:10] setText:@""];
        [self populateTimeSelected];
    }
    else {
        NSString *timeString = @"";
        for (int i = 0; i<popTimeLocalArray.count; i++) {
            BOOL selected = [[popTimeLocalArray objectAtIndex:i] boolValue];
            if (selected) {
                if (CheckStringForNull(timeString)) {
                    timeString = [NSString stringWithFormat:@"%@",[[timeArray objectAtIndex:i] valueForKey:@"value"]];
                }
                else {
                    NSString *string = [NSString stringWithFormat:@",%@",[[timeArray objectAtIndex:i] valueForKey:@"value"]];
                    timeString = [timeString stringByAppendingString:string];
                }
            }
        }
        [(UITextField *)[scrollLocalFilter viewWithTag:10] setText:timeString];
    }
    
    NSString *sortedString = [userDefaultManager valueForKey:@"sortSave"];
    if ([sortedString isEqualToString:@"total_member"]) {
        [btnMostMemberLocal setImage:self.selectedImage forState:UIControlStateNormal];
        [btnNewestLocal setImage:self.unSelectedImage forState:UIControlStateNormal];
    }
    else if ([sortedString isEqualToString:@"created"]) {
        [btnMostMemberLocal setImage:self.unSelectedImage forState:UIControlStateNormal];
        [btnNewestLocal setImage:self.selectedImage forState:UIControlStateNormal];
    }
    else {
        [btnMostMemberLocal setImage:self.unSelectedImage forState:UIControlStateNormal];
        [btnNewestLocal setImage:self.unSelectedImage forState:UIControlStateNormal];
    }

    
    
    
    [viewLocalFilter setHidden:YES];
    
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Cancel Global Filter View
 * @Modified Date 19/10/2015
 */
#pragma mark Cancel Global Filter View
-(IBAction)cancelGlobalFilterView:(id)sender {
    
    if (!popDayGlobalArray.count) {
        [(UITextField *)[scrollGlobalFilter viewWithTag:23] setText:@""];
        [self populateDaySelected];
    }
    else {
        NSString *dayString = @"";
        for (int i = 0; i<popDayGlobalArray.count; i++) {
            BOOL selected = [[popDayGlobalArray objectAtIndex:i] boolValue];
            if (selected) {
                if (CheckStringForNull(dayString)) {
                    dayString = [NSString stringWithFormat:@"%@",[[dayArray objectAtIndex:i] valueForKey:@"value"]];
                }
                else {
                    NSString *string = [NSString stringWithFormat:@",%@",[[dayArray objectAtIndex:i] valueForKey:@"value"]];
                    dayString = [dayString stringByAppendingString:string];
                }
            }
        }
        [(UITextField *)[scrollGlobalFilter viewWithTag:23] setText:dayString];
    }
    if (!popTimeGlobalArray.count) {
        [(UITextField *)[scrollGlobalFilter viewWithTag:24] setText:@""];
        [self populateTimeSelected];
    }
    else {
        NSString *timeString = @"";
        for (int i = 0; i<popTimeGlobalArray.count; i++) {
            BOOL selected = [[popTimeGlobalArray objectAtIndex:i] boolValue];
            if (selected) {
                if (CheckStringForNull(timeString)) {
                    timeString = [NSString stringWithFormat:@"%@",[[timeArray objectAtIndex:i] valueForKey:@"value"]];
                }
                else {
                    NSString *string = [NSString stringWithFormat:@",%@",[[timeArray objectAtIndex:i] valueForKey:@"value"]];
                    timeString = [timeString stringByAppendingString:string];
                }
            }
        }
        [(UITextField *)[scrollGlobalFilter viewWithTag:24] setText:timeString];
    }
    
    NSString *locationString = [userDefaultManager valueForKey:@"locationSave"];
    [(UITextField *)[scrollGlobalFilter viewWithTag:22] setText:locationString];
    
    NSString *sortedString = [userDefaultManager valueForKey:@"sortSave"];
    if ([sortedString isEqualToString:@"total_member"]) {
        [btnMostMemberGlobal setImage:self.selectedImage forState:UIControlStateNormal];
        [btnNewestGlobal setImage:self.unSelectedImage forState:UIControlStateNormal];
    }
    else if ([sortedString isEqualToString:@"created"]) {
        [btnMostMemberGlobal setImage:self.unSelectedImage forState:UIControlStateNormal];
        [btnNewestGlobal setImage:self.selectedImage forState:UIControlStateNormal];
    }
    else {
        [btnMostMemberGlobal setImage:self.unSelectedImage forState:UIControlStateNormal];
        [btnNewestGlobal setImage:self.unSelectedImage forState:UIControlStateNormal];
    }
    
    [viewGlobalFilter setHidden:YES];
    [self.view endEditing:YES];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for get Filtered record from sewrver
 * @Modified Date 21/10/2015
 */
#pragma mark Filtered record from sewrver
-(IBAction)sendFilterOnServer:(id)sender {
    [self.view endEditing:YES];
    recordArray = [[NSMutableArray alloc] init];
    [userDefaultManager setValue:@"1" forKey:@"page_no"];
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(sendFilterOnServerDelay) withObject:nil afterDelay:0.1];
}
-(void)doneFilter {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(sendFilterOnServerDelay) withObject:nil afterDelay:0.1];
}
-(void)sendFilterOnServerDelay {

    NSString *pageCountStr = [userDefaultManager valueForKey:@"page_no"];
    
    NSDictionary *dictionary;
    
    if ([viewIdentify isEqualToString:@"local"]) {// For Local Filter
        [btnMostMemberGlobal setImage:self.unSelectedImage forState:UIControlStateNormal];
        [btnNewestGlobal setImage:self.unSelectedImage forState:UIControlStateNormal];
        [(UITextField *)[scrollGlobalFilter viewWithTag:22] setText:@""];
        [(UITextField *)[scrollGlobalFilter viewWithTag:23] setText:@""];
        [(UITextField *)[scrollGlobalFilter viewWithTag:24] setText:@""];
        popDayGlobalArray = [[NSMutableArray alloc] init];
        popTimeGlobalArray = [[NSMutableArray alloc] init];
        
        popDayLocalArray = [[NSMutableArray alloc] initWithArray:populateDayArray];
        popTimeLocalArray = [[NSMutableArray alloc] initWithArray:populateTimeArray];
        
        [userDefaultManager setObject:@"local" forKey:@"filter_type"];
        NSString *milesString = @"";
        NSString *locationString = @"";
        NSString *sortingString = @"";
        NSMutableArray *daySendArray = [[NSMutableArray alloc] init];
        NSMutableArray *timeSendArray = [[NSMutableArray alloc] init];
        //For Miles
        milesString = [(UITextField *)[scrollLocalFilter viewWithTag:6] text];
        if (CheckStringForNull(milesString)) {
            milesString = @"";
        }
        else {
            NSArray *array = [milesString componentsSeparatedByString:@" "];
            milesString = [array objectAtIndex:0];
        }
        
        //For sorting
        if (btnMostMemberLocal.imageView.image == self.selectedImage) {
            sortingString = @"total_member";
        }
        else if (btnNewestLocal.imageView.image == self.selectedImage) {
            sortingString = @"created";
        }
        
        //For day
        NSString *dayString = [(UITextField *)[scrollLocalFilter viewWithTag:9] text];
        if (!CheckStringForNull(dayString)) {
            NSArray *array = [dayString componentsSeparatedByString:@","];
            for (int i = 0; i<array.count; i++) {
                NSString *dayySendStr = [array objectAtIndex:i];
                [daySendArray addObject:dayySendStr];
            }
        }
        
        // For Time
        NSString *timeeString = [(UITextField *)[scrollLocalFilter viewWithTag:10] text];
        if (!CheckStringForNull(timeeString)) {
            NSArray *array = [timeeString componentsSeparatedByString:@","];
            for (int i = 0; i<array.count; i++) {
                NSString *fillTimeStr = @"";
                dateFormateCheck = false;
                NSString *timmSendStr = [array objectAtIndex:i];
                NSArray *arr = [timmSendStr componentsSeparatedByString:@"-"];
                for (int j = 0; j<arr.count; j++) {
                    NSString *str = [arr objectAtIndex:j];
                    if (j>0) {
                        dateFormateCheck = true;
                    }
                    NSString *tiStr = [self stringDateGMTFromString:str];
                    if (CheckStringForNull(fillTimeStr)) {
                        fillTimeStr = [NSString stringWithFormat:@"%@ - ",tiStr];
                    }
                    else {
                        fillTimeStr = [fillTimeStr stringByAppendingString:tiStr];
                    }
                }
                [timeSendArray addObject:fillTimeStr];
            }
        }
        
        [userDefaultManager setObject:timeSendArray forKey:@"timesave"];
        [userDefaultManager setObject:daySendArray forKey:@"daysave"];
        [userDefaultManager setObject:milesString forKey:@"mileSave"];
        [userDefaultManager setObject:locationString forKey:@"locationSave"];
        [userDefaultManager setObject:sortingString forKey:@"sortSave"];

        dictionary = [NSDictionary dictionaryWithObjectsAndKeys:@"local",@"group",pageCountStr,@"page_no",recordPerPage,@"record_per_page",milesString,@"milesfilter",locationString,@"searchbylocation",sortingString,@"sorting",timeSendArray,@"time",daySendArray,@"day",@"local",@"list",nil];
    }
    else {// For Global Filter
        [btnMostMemberLocal setImage:self.unSelectedImage forState:UIControlStateNormal];
        [btnNewestLocal setImage:self.unSelectedImage forState:UIControlStateNormal];
        [(UITextField *)[scrollLocalFilter viewWithTag:6] setText:@""];
        [(UITextField *)[scrollLocalFilter viewWithTag:9] setText:@""];
        [(UITextField *)[scrollLocalFilter viewWithTag:10] setText:@""];
        popDayLocalArray = [[NSMutableArray alloc] init];
        popTimeLocalArray = [[NSMutableArray alloc] init];
        
        popDayGlobalArray = [[NSMutableArray alloc] initWithArray:populateDayArray];
        popTimeGlobalArray = [[NSMutableArray alloc] initWithArray:populateTimeArray];

        [userDefaultManager setObject:@"global" forKey:@"filter_type"];
        NSString *milesString = @"";
        NSString *locationString = @"";
        NSString *sortingString = @"";
        NSMutableArray *daySendArray = [[NSMutableArray alloc] init];
        NSMutableArray *timeSendArray = [[NSMutableArray alloc] init];
        
        //For Location
        locationString = [(UITextField *)[scrollGlobalFilter viewWithTag:22] text];
        
        //For sorting
        if (btnMostMemberGlobal.imageView.image == self.selectedImage) {
            sortingString = @"total_member";
        }
        else if (btnNewestGlobal.imageView.image == self.selectedImage) {
            sortingString = @"created";
        }

        //For day
        NSString *dayString = [(UITextField *)[scrollGlobalFilter viewWithTag:23] text];
        if (!CheckStringForNull(dayString)) {
            NSArray *array = [dayString componentsSeparatedByString:@","];
            for (int i = 0; i<array.count; i++) {
                NSString *dayySendStr = [array objectAtIndex:i];
                [daySendArray addObject:dayySendStr];
            }
        }
        
        // For Time
        NSString *timeeString = [(UITextField *)[scrollGlobalFilter viewWithTag:24] text];
        if (!CheckStringForNull(timeeString)) {
            NSArray *array = [timeeString componentsSeparatedByString:@","];
            for (int i = 0; i<array.count; i++) {
                NSString *fillTimeStr = @"";
                dateFormateCheck = false;
                NSString *timmSendStr = [array objectAtIndex:i];
                NSArray *arr = [timmSendStr componentsSeparatedByString:@"-"];
                for (int j = 0; j<arr.count; j++) {
                    NSString *str = [arr objectAtIndex:j];
                    if (j>0) {
                        dateFormateCheck = true;
                    }
                    NSString *tiStr = [self stringDateGMTFromString:str];
                    if (CheckStringForNull(fillTimeStr)) {
                        fillTimeStr = [NSString stringWithFormat:@"%@ - ",tiStr];
                    }
                    else {
                        fillTimeStr = [fillTimeStr stringByAppendingString:tiStr];
                    }
                }
                [timeSendArray addObject:fillTimeStr];
            }
        }
        [userDefaultManager setObject:timeSendArray forKey:@"timesave"];
        [userDefaultManager setObject:daySendArray forKey:@"daysave"];
        [userDefaultManager setObject:milesString forKey:@"mileSave"];
        [userDefaultManager setObject:locationString forKey:@"locationSave"];
        [userDefaultManager setObject:sortingString forKey:@"sortSave"];
        
        dictionary = [NSDictionary dictionaryWithObjectsAndKeys:@"global",@"group",pageCountStr,@"page_no",recordPerPage,@"record_per_page",milesString,@"milesfilter",locationString,@"searchbylocation",sortingString,@"sorting",timeSendArray,@"time",daySendArray,@"day",@"global",@"list",nil];
    }
    
    if (isNetworkAvailable) {
        
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:dictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"getGroups" controls:@"groups" httpMethod:@"POST" data:data];
        
        [self hideTableOnFilter];
        
        if (parsedJSONToken != nil) {
            
            [self showTableOnFilter];
            
            NSString *pageNoString = [parsedJSONToken valueForKey:@"page_no"];
            NSString *totalRecString = [parsedJSONToken valueForKey:@"totalGroups"];
            [userDefaultManager setObject:pageNoString forKey:@"page_no"];
            [userDefaultManager setObject:totalRecString forKey:@"totalRecord"];
            NSArray *array = [parsedJSONToken valueForKey:@"result"];
            for (NSDictionary *dictionary in array){
                [recordArray addObject:dictionary];
            }
         }
        [viewLocalFilter setHidden:YES];
        [viewGlobalFilter setHidden:YES];
        [self populateDaySelected];
        [self populateTimeSelected];
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [tableView reloadData];
    [[AppDelegate currentDelegate] removeLoading];
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for convert date and time into GMT
 * @Modified Date 15/10/2015
 */
#pragma mark Convert Date And Time
-(NSString *)stringDateGMTFromString:(NSString *)dateFromServer {
    NSDateFormatter *formatter=[[NSDateFormatter alloc]init];
    [formatter setDateFormat:@"hh:mm a"];
    [formatter setTimeZone:[NSTimeZone timeZoneForSecondsFromGMT:(0)]];
    NSDate *date =[formatter dateFromString:dateFromServer];
    
    NSCalendar *gregorian = [[NSCalendar alloc] initWithCalendarIdentifier:NSGregorianCalendar];
    NSDateComponents *offsetComponents = [[NSDateComponents alloc] init];
    [offsetComponents setMinute:-1];
    NSDate *endOfWorldWar3 = [gregorian dateByAddingComponents:offsetComponents toDate:date options:0];
    
    NSDateFormatter *dateFormatter1 = [[NSDateFormatter alloc] init];
    [dateFormatter1 setDateFormat:@"HH:mm"];
    [dateFormatter1 setTimeZone:[NSTimeZone timeZoneForSecondsFromGMT:(0)]];
    NSString *formattedDateString = @"";
    if (dateFormateCheck) {
        formattedDateString = [dateFormatter1 stringFromDate:endOfWorldWar3];
    }
    else {
        formattedDateString = [dateFormatter1 stringFromDate:date];
    }
    
    return formattedDateString;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Hide Table On Filter
 * @Modified Date 15/10/2015
 */
#pragma mark Hide Table On Filter
-(void)hideTableOnFilter {
    [tableView setHidden:YES];
    [viewNoRecord setHidden:NO];
    lblNoRecord.text = @"No suitable groups available";
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Show Table On Filter
 * @Modified Date 15/10/2015
 */
#pragma mark Show Table On Filter
-(void)showTableOnFilter {
    [tableView setHidden:NO];
    [viewNoRecord setHidden:YES];
    lblNoRecord.text = @"No suitable groups available";
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Hide Table On Filter
 * @Modified Date 15/10/2015
 */
#pragma mark Hide Table On Filter
-(void)hideTable {
    [tableView setHidden:YES];
    [viewNoRecord setHidden:NO];
    lblNoRecord.text = @"No suitable groups available";
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Show Table On Filter
 * @Modified Date 15/10/2015
 */
#pragma mark Show Table On Filter
-(void)showTable {
    [tableView setHidden:NO];
    [viewNoRecord setHidden:YES];
    lblNoRecord.text = @"No suitable groups available";
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
