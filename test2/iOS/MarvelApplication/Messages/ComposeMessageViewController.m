//
//  ComposeMessageViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 9/9/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import "ComposeMessageViewController.h"
#define specialCharacter  @"~`@#$%^&*()_+={}[]|;'<>?,/₹€£:\""
@interface ComposeMessageViewController ()

@end

@implementation ComposeMessageViewController
@synthesize recordArr;
@synthesize teamDelegate;

- (void)viewDidLoad {
    
    heavyDataPathArray = [[NSMutableArray alloc] init];
    showAttechedArray = [[NSMutableArray alloc] init];
    
    self.imgBgTextField.layer.cornerRadius = 5.0f;
    self.imgBgSubTextField.layer.cornerRadius = 5.0f;
    self.imgBgMsgTextView.layer.cornerRadius = 5.0f;
    self.imgControlBG.layer.cornerRadius = 5.0f;
    self.imgAttachDocBG.layer.cornerRadius = 5.0f;
    
    [self.textField setValue:placeHolderColor
                  forKeyPath:@"_placeholderLabel.textColor"];
    [self.subTextField setValue:placeHolderColor
                  forKeyPath:@"_placeholderLabel.textColor"];
    [msgTextView setPlaceholder:@"Message..."];
    
    [btnNext setUserInteractionEnabled:NO];
    btnNext.alpha = 0.5;
    btnNext.layer.cornerRadius = 5.0f;
    
    scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, btnNext.frame.origin.y+btnNext.frame.size.height+30);
    
    //From Team tab user will send the message
    if (recordArr.count) {
        [self selectedTeamMember:recordArr];
    }
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 09/09/2015
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
#pragma mark Attach Screen
- (IBAction)attachButton:(id)sender {
    [self.view endEditing:YES];
    [scrollView setContentOffset:CGPointMake(0,0) animated:NO];
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
    [popOver presentPopoverFromRect:btnAttach.frame inView:scrollAttach permittedArrowDirections:UIPopoverArrowDirectionUp animated:YES];
    
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

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for Take Team Member
 * @Modified Date 09/09/2015
 */
#pragma mark team Member
-(IBAction)btnTeamMember:(UIButton*)sender {
    [self performSegueWithIdentifier:@"composeTeamMember" sender:nil];
}

/*
 * @Auther Deepak chauhan
 * @Parm array
 * @Description This method are used for get Team member from SelectTeamMemberViewController class
 * @Modified Date 09/09/2015
 */
#pragma mark Received Team Member From SelectTeamMemberViewController Class
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
 * @Description These method are used for Compose Message
 * @Modified Date 09/09/2015
 */
#pragma mark Compose Message
-(IBAction)composeMessage:(id)sender {

    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(composeMessageDelay) withObject:nil afterDelay:0.1];
}

-(void)composeMessageDelay {
    
    [self makeEmptyFieldRed];
    BOOL isFilled = [self checkAllFieldAreFilled];
    if (!isFilled) {
        [[AppDelegate currentDelegate] removeLoading];
        return;
    }
    if (isNetworkAvailable) {
        
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
        
        NSString *subjectStr = [self.subTextField text];
        NSString *messageStr = [msgTextView text];
        
        NSMutableArray *tempHeavyDataArray = [NSMutableArray arrayWithObjects:subjectStr,@"subject",teamMemberIdStr,@"teamMembers",messageStr,@"content",@"message",@"messageType",nil];
        for (id obj in heavyDataPathArray) {
            [tempHeavyDataArray addObject:obj];
        }
        [tempHeavyDataArray addObject:[NSString stringWithFormat:@"%d",[heavyDataPathArray count]]];
        
        tempUpload = [[UploadViewController alloc] initWithServiceName:@"" dataArray:tempHeavyDataArray methodHeader:@"composeMessage" controls:@"messages"];
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
 * @Modified Date 10/09/2015
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
                 if ([self.teamDelegate respondsToSelector:@selector(updateTeamRecord)]) {
                     [self.teamDelegate updateTeamRecord];
                 }
                [self.navigationController popToRootViewControllerAnimated:NO];
            }
        }
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for change the boarder color of Required Field
 * @Modified Date 04/08/2015
 */
#pragma mark Required Field
-(BOOL)makeEmptyFieldRed {
    
    //For Subject
    NSString *subjectStr = [self.subTextField text];
    NSString *subjectTrStr = [subjectStr stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:specialCharacter] invertedSet]];
    if (!CheckStringForNull(subjectTrStr) || subjectStr.length>65 || CheckStringForNull(subjectStr)) {
        [[ConnectionManager sharedManager] setBoarderColorRed:self.imgBgSubTextField];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:self.imgBgSubTextField];
    }
    
    //For Message
    NSString *messageStr = [msgTextView text];
    if (messageStr.length>5000  || CheckStringForNull(messageStr)) {
        [[ConnectionManager sharedManager] setBoarderColorRed:self.imgBgMsgTextView];
    } else {
        [[ConnectionManager sharedManager] setBoarderColorClear:self.imgBgMsgTextView];
    }
   
    return YES;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for check Required Field
 * @Modified Date 04/08/2015
 */
#pragma mark Required Field
-(BOOL)checkAllFieldAreFilled {

    //For Subject
    NSString *subjectStr = [self.subTextField text];
    if (CheckStringForNull(subjectStr)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (subjectStr.length>65 && !CheckStringForNull(subjectStr)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Subject can have maximum 65 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    subjectStr = [subjectStr stringByTrimmingCharactersInSet:[[NSCharacterSet characterSetWithCharactersInString:specialCharacter] invertedSet]];
    if (!CheckStringForNull(subjectStr)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Subject can contain alphanumeric characters, space, period and hyphen." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    
    //For Message
    NSString *messageStr = [msgTextView text];
    if (CheckStringForNull(messageStr)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Highlighted field(s) are required." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }
    if (messageStr.length>5000 && !CheckStringForNull(messageStr)) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Message can have maximum 5000 characters." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        return NO;
    }

    return YES;
}

#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    if ([segue.identifier isEqualToString:@"composeTeamMember"]) {
        SelectTeamMemberViewController *selectTeamMemberViewController = segue.destinationViewController;
        selectTeamMemberViewController.DelegateMember = self;
        selectTeamMemberViewController.titleString = @"MESSAGES";
        selectTeamMemberViewController.preSelectArray = teamMemberArray;
    }
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}


@end
