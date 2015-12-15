//
//  SendReferralViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/29/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "SendReferralViewController.h"

@interface SendReferralViewController ()

@end

@implementation SendReferralViewController
@synthesize dataDictionary;

- (void)viewDidLoad {
    
    heavyDataPathArray = [[NSMutableArray alloc] init];
    showAttechedArray = [[NSMutableArray alloc] init];
    imgBG.layer.cornerRadius = 5.0f;
    btnNext.layer.cornerRadius = 5.0f;
    imgBgTextView.layer.cornerRadius = 5.0f;
    [textView setPlaceholder:@"Message from Sender"];
    imgBGAttach.layer.cornerRadius = 5.0f;
    
    scrollView.contentSize = CGSizeMake(self.view.frame.size.width, scrollView.frame.size.height);
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
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
 * @Description This method are used for take attachement from library and camera
 * @Modified Date 30/07/2015
 */
#pragma mark Back Screen
- (IBAction)attachButton:(id)sender {
    [self.view endEditing:YES];
    UIActionSheet *actionSheet = [[UIActionSheet alloc] initWithTitle:@"Select option" delegate:self cancelButtonTitle:@"Cancel" destructiveButtonTitle:nil otherButtonTitles:
                            @"Image from Camera",
                            @"Image from Library",
                            nil];
    actionSheet.tag = 1;
    [actionSheet showInView:self.view];
}

- (void)actionSheet:(UIActionSheet *)popup clickedButtonAtIndex:(NSInteger)buttonIndex {
    
    switch (popup.tag) {
        case 1: {
            switch (buttonIndex) {
                case 0:
                    [self dismissViewControllerAnimated:NO completion:nil];
                    [self TakeImageFromCamera];
                    break;
                case 1:
                    [self dismissViewControllerAnimated:NO completion:nil];
                    [self takeImageFromLibrary];
                    break;
                default:
                    break;
            }
            break;
        }
        default:
            break;
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for take attachement from camera
 * @Modified Date 30/07/2015
 */
#pragma mark TAKE PICTURE FROM CAMERA
- (void)TakeImageFromCamera {
    if ([UIImagePickerController isSourceTypeAvailable:UIImagePickerControllerSourceTypeCamera]) {
        
        if (imagePicker!=nil) {
            imagePicker=nil;
        }
        
        imagePicker = [[UIImagePickerController alloc] init];
        imagePicker.sourceType =  UIImagePickerControllerSourceTypeCamera;
        imagePicker.delegate = self;
        imagePicker.allowsEditing = NO;
        
        [self presentViewController:imagePicker animated:YES completion:nil];
    }
    else {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Sorry" message:@"Camera not avilable." delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return;
    }
    
}
/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for take attachement from library
 * @Modified Date 30/07/2015
 */
#pragma mark Take Image From Library
-(void)takeImageFromLibrary{
    
    if (imagePicker!=nil) {
        imagePicker=nil;
    }
    
    imagePicker = [[UIImagePickerController alloc] init];
    imagePicker.sourceType = UIImagePickerControllerSourceTypePhotoLibrary;
    imagePicker.delegate = self;
    imagePicker.mediaTypes = [NSArray arrayWithObjects:(NSString *) kUTTypeImage, nil];
    
    if (!CheckDeviceFunction()) {
        [self presentViewController:imagePicker animated:YES completion:NO];
        return;
    }
    
    if (popOver!=nil) {
        popOver=nil;
    }
    popOver = [[UIPopoverController alloc] initWithContentViewController:imagePicker];
    popOver.delegate = self;
    int w = 320;
    CGRect pickerFrame = CGRectMake(220, 400, w, 400);
    [popOver setPopoverContentSize:pickerFrame.size animated:NO];
    [popOver presentPopoverFromRect:btnAttach.frame inView:scrollView permittedArrowDirections:UIPopoverArrowDirectionUp animated:YES];
    
}
// The picker does not dismiss itself; the client dismisses it in these callbacks.
// The delegate will receive one or the other, but not both, depending whether the user
// confirms or cancels.
- (void) imagePickerController:(UIImagePickerController *)picker didFinishPickingMediaWithInfo:(NSDictionary *)info {
    
    [[AppDelegate currentDelegate] addLoading];
    
    UIImage *image = [info objectForKey:@"UIImagePickerControllerOriginalImage"];
    image=[image fixOrientation];
    image = imageCompressReduseSize(image);
    //FOR IMAGE NAME
    heavyDataName = [[NSMutableString alloc] init];
    CFUUIDRef theUUID = CFUUIDCreate(kCFAllocatorDefault);
    if (theUUID) {
        [heavyDataName appendString:CFBridgingRelease(CFUUIDCreateString(kCFAllocatorDefault, theUUID))];
        CFRelease(theUUID);
    }
    [heavyDataName appendString:@".jpg"];
    //FOR IMAGE
    NSData *heavyData = UIImageJPEGRepresentation(image, 0.8);
    //FOR IMAGE SIZE
    int bytes = [heavyData length];
    float a = bytes/1024;
    float b = a/1024;
    if (b>10.0) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@""
                                                        message:@"File size too large." delegate:nil cancelButtonTitle:@"OK"
                                              otherButtonTitles:nil, nil];
        [alert show];
        [imagePicker dismissViewControllerAnimated:YES completion:nil];
        [[AppDelegate currentDelegate] removeLoading];
        return;
    }
    maxVideoSizeUpload = b+maxVideoSizeUpload;
    if (maxVideoSizeUpload > 10.0) {
        
        maxVideoSizeUpload = maxVideoSizeUpload-b;
        
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@""
                                                        message:@"Attachments cannot exceed 10 MB." delegate:nil cancelButtonTitle:@"OK"
                                              otherButtonTitles:nil, nil];
        [alert show];
        [imagePicker dismissViewControllerAnimated:YES completion:nil];
        [[AppDelegate currentDelegate] removeLoading];
        return;
    }
    
    [imagePicker dismissViewControllerAnimated:YES completion:nil];
    [showAttechedArray addObject:[NSDictionary dictionaryWithObjectsAndKeys:heavyDataName,@"name",heavyData,@"data",nil]];
    
    NSString *path = [[NSHomeDirectory() stringByAppendingPathComponent:@"Documents"] stringByAppendingPathComponent:heavyDataName];
    [heavyData writeToFile:path atomically:NO];
    [heavyDataPathArray addObject:path];
    heavyData = nil;
    
    [self showAttachment];
    [[AppDelegate currentDelegate] removeLoading];
}

/*
 * @Auther Deepak chauhan
 * @Parm nil
 * @Description This method are used for display Attachment
 * @Modified Date 30/07/2015
 */
#pragma mark show attachment
-(void)showAttachment {
    
    [btnAttach setUserInteractionEnabled:YES];
    btnAttach.alpha = 1;
    if (showAttechedArray.count>=5) {
        [btnAttach setUserInteractionEnabled:NO];
        btnAttach.alpha = 0.5;
    }
    
    int y = 5;
    int myScroll = y;
    [[scrollAttach subviews]
     makeObjectsPerformSelector:@selector(removeFromSuperview)];
    
    for (int p = 0; p<[showAttechedArray count]; p++) {
        
        NSString *DocumentTypeString = [NSString stringWithFormat:@"%@",[[showAttechedArray objectAtIndex:p] valueForKey:@"name"]];
        
        UILabel *AttechedFileLavel = [[UILabel alloc] initWithFrame:CGRectMake(5 , y , scrollAttach.frame.size.width-40, 30)];
        AttechedFileLavel.textColor = [UIColor whiteColor];
        [AttechedFileLavel setTextAlignment:NSTextAlignmentLeft];
        [AttechedFileLavel setText:DocumentTypeString];
        [AttechedFileLavel setFont:[UIFont boldSystemFontOfSize:12.0]];
        AttechedFileLavel.backgroundColor = [UIColor clearColor];
        [scrollAttach addSubview:AttechedFileLavel];
        
        UIButton *AttectmentDeletButton = [UIButton buttonWithType:UIButtonTypeCustom];
        [AttectmentDeletButton addTarget:self action:@selector(deleteAttachment:) forControlEvents:UIControlEventTouchUpInside];
        [AttectmentDeletButton setBackgroundColor:[UIColor clearColor]];
        [AttectmentDeletButton setTag:p];
        AttectmentDeletButton.frame = CGRectMake(scrollAttach.frame.size.width-30 , y+2 , 25 , 25);
        [AttectmentDeletButton setBackgroundImage:[UIImage imageNamed:@"Delete_Attach"] forState:UIControlStateNormal];
        [scrollAttach addSubview:AttectmentDeletButton];
        
        y = y+30;
        myScroll = myScroll+30;
        scrollAttach.contentSize = CGSizeMake(scrollAttach.frame.size.width, myScroll);
    }
}
/*
 * @Auther Deepak chauhan
 * @Parm nil
 * @Description This method are used for Delete Attachment
 * @Modified Date 30/07/2015
 */
#pragma mark Delete attachment
-(void)deleteAttachment:(UIButton*)sender {
    indexAttach = (int)sender.tag;
    [showAttechedArray removeObjectAtIndex:indexAttach];
    [heavyDataPathArray removeObjectAtIndex:indexAttach];
    [self showAttachment];    
}



/*
 * @Auther Deepak chauhan
 * @Parm nil
 * @Description This method are Called For send referral's
 * @Modified Date 30/07/2015
 */
#pragma mark Send Referral's
-(IBAction)sendReferrals:(UIButton *)sender {

    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(sendReferralDelay) withObject:nil afterDelay:0.1];
}
-(void)sendReferralDelay {

    NSString *commentString = [textView text];
    if (commentString.length >500) {
        [[AppDelegate currentDelegate] removeLoading];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@""
                                                        message:@"Message can have maximum 500 characters." delegate:nil cancelButtonTitle:@"OK"
                                              otherButtonTitles:nil, nil];
        [alert show];
        return;
    }
    
    [dataDictionary setObject:commentString forKey:@"message"];
    
    if (isNetworkAvailable) {
            //For First Name
            NSString *fNameString = [dataDictionary valueForKey:@"first_name"];
            fNameString = [fNameString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(fNameString)) {
                fNameString = @"";
            }
            //For Last Name
            NSString *lNameString = [dataDictionary valueForKey:@"last_name"];
            lNameString = [lNameString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(lNameString)) {
                lNameString = @"";
            }
            //For Company Name
            NSString *companyNameString = [dataDictionary valueForKey:@"company"];
            companyNameString = [companyNameString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(companyNameString)) {
                companyNameString = @"";
            }
            //For Job Name
            NSString *jobTitleString = [dataDictionary valueForKey:@"job_title"];
            jobTitleString = [jobTitleString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(jobTitleString)) {
                jobTitleString = @"";
            }
            //For Email Name
            NSString *emailString = [dataDictionary valueForKey:@"email"];
            if (CheckStringForNull(emailString)) {
                emailString = @"";
            }
            //For Website Name
            NSString *webSiteString = [dataDictionary valueForKey:@"website"];
            if (CheckStringForNull(webSiteString)) {
                webSiteString = @"";
            }
            //For Address Name
            NSString *addressString = [dataDictionary valueForKey:@"address"];
            addressString = [addressString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(addressString)) {
                addressString = @"";
            }
            //For Country
            NSString *CountryString = [dataDictionary valueForKey:@"country_id"];
            if (CheckStringForNull(CountryString)) {
                CountryString = @"";
            }
            //For State
            NSString *stateString = [dataDictionary valueForKey:@"state_id"];
            if (CheckStringForNull(stateString)) {
                stateString = @"";
            }
            //For City Name
            NSString *cityString = [dataDictionary valueForKey:@"city"];
            cityString = [cityString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(cityString)) {
                cityString = @"";
            }
            //For Zip Name
            NSString *zipString = [dataDictionary valueForKey:@"zip"];
            zipString = [zipString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(zipString)) {
                zipString = @"";
            }
            //For Office Phone Number
            NSString *officePhoneString = [dataDictionary valueForKey:@"office_phone"];
            if (CheckStringForNull(officePhoneString)) {
                officePhoneString = @"";
            }
            //For Phone Number
            NSString *phoneString = [dataDictionary valueForKey:@"mobile"];
            if (CheckStringForNull(phoneString)) {
                phoneString = @"";
            }
            //For Comment
            NSString *commentString = [dataDictionary valueForKey:@"message"];
            if (CheckStringForNull(phoneString)) {
                phoneString = @"";
            }
            //For Team Member
            NSString *teamMemberString = [dataDictionary valueForKey:@"teamMembers"];
            if (CheckStringForNull(teamMemberString)) {
                phoneString = @"";
            }
            //For contact Id
             NSString *contactList = [dataDictionary valueForKey:@"contact_id"];
            if (CheckStringForNull(contactList)) {
                contactList = @"";
            }
            
            NSMutableArray *tempHeavyDataArray = [NSMutableArray arrayWithObjects:contactList,@"contact_id",fNameString,@"first_name",lNameString,@"last_name",companyNameString,@"company",jobTitleString,@"job_title",emailString,@"email",webSiteString,@"website",addressString,@"address",CountryString,@"country_id",stateString,@"state_id",cityString,@"city",zipString,@"zip",officePhoneString,@"office_phone",phoneString,@"mobile",teamMemberString,@"teamMembers",commentString,@"message",nil];
            for (id obj in heavyDataPathArray) {
                [tempHeavyDataArray addObject:obj];
            }
            [tempHeavyDataArray addObject:[NSString stringWithFormat:@"%lu",(unsigned long)[heavyDataPathArray count]]];
            
            tempUpload = [[UploadViewController alloc] initWithServiceName:@"" dataArray:tempHeavyDataArray methodHeader:@"sendReferral" controls:@"referrals"];
            tempUpload.delegate=self;
            [tempUpload sendContantToServer];
        return;
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
    [[AppDelegate currentDelegate] removeLoading];

}

/*
 * @Auther Deepak chauhan
 * @Parm responseData
 * @Description This method are used for get the received response from UploadViewController Class
 * @Modified Date 07/08/2015
 */
#pragma mark Receive Upload Response DataFromServer
-(void)receiveUploadResponseDataFromServer:(NSData *)responseData {
    
    if (responseData!=nil) {
        NSMutableDictionary *responseDict = (NSMutableDictionary*)[[ConnectionManager sharedManager] parseResponse:responseData];
        if (responseDict!=nil) {
            NSString *statusCode = [responseDict valueForKey:@"code"];
            NSString *message = [responseDict valueForKey:@"message"];
            if ([statusCode isEqualToString:@"200"]) {
                
                for (NSString *pathStr in heavyDataPathArray) {
                    [[NSFileManager defaultManager] removeItemAtPath:pathStr error:nil];
                }
                [heavyDataPathArray removeAllObjects];
                UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:message delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
                [networkAlert show];
                [userDefaultManager setObject:@"teamTab" forKey:@"team"];
                [userDefaultManager synchronize];
                [self.navigationController popToRootViewControllerAnimated:NO];
            }
        }
    }
}

// The picker does not dismiss itself; the client dismisses it in these callbacks.
// The delegate will receive one or the other, but not both, depending whether the user
// confirms or cancels.
-(void)imagePickerControllerDidCancel:(UIImagePickerController *) picker{
    [imagePicker dismissViewControllerAnimated:YES completion:nil];
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
