//
//  SocialMediaDetailViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 11/26/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import "SocialMediaDetailViewController.h"
#import "SocialMediaCell.h"

@interface SocialMediaDetailViewController ()

@end

@implementation SocialMediaDetailViewController
@synthesize checkConditionString;
@synthesize socialMediaDelegate;

- (void)viewDidLoad {
    
    tableView.tableFooterView = [[UIView alloc] initWithFrame:CGRectZero];
    tableView.scrollEnabled = NO;
    
    btnSave.layer.cornerRadius = 5.0f;
    self.selectedImage = [UIImage imageNamed:@"Selected.png"];
    self.unSelectedImage = [UIImage imageNamed:@"Unselected.png"];
    
    selectedRowArray = [[NSMutableArray alloc] init];
    socialMediaArray = [[NSMutableArray alloc] init];
    
    UIImage *image = [UIImage imageNamed:@"twitterSocialMedia"];
    
    if ([checkConditionString isEqualToString:@"facebook"] || [checkConditionString isEqualToString:@"linkedin"]) {
        
        lblTopTitle.text = @"CONFIGURE POSTS";
        
        if ([checkConditionString isEqualToString:@"facebook"]){
            image = [UIImage imageNamed:@"facebookSocialMedia"];
            commonString = @"facebook";
            
            NSString *substrOne = @"Just sent a referral to username via FoxHopr";
            NSString *subTitleStringOne = [NSString stringWithFormat:@"Automatically posts: \"%@\"",substrOne];
            [socialMediaArray addObject:[NSDictionary dictionaryWithObjectsAndKeys:@"When you send a referral.",@"title",subTitleStringOne,@"sub_title",@"0",@"value",@"fbReferralSend",@"key", nil]];
            
            NSString *substrtwo = @"Just sent a message to username via FoxHopr";
            NSString *subTitleStringtwo = [NSString stringWithFormat:@"Automatically posts: \"%@\"",substrtwo];
            [socialMediaArray addObject:[NSDictionary dictionaryWithObjectsAndKeys:@"When you send a message.",@"title",subTitleStringtwo,@"sub_title",@"0",@"value",@"fbMessageSend",@"key", nil]];
            
            NSString *substrThree = @"Just set up a calendar event with username via FoxHopr";
            NSString *subTitleStringThree = [NSString stringWithFormat:@"Automatically posts: \"%@\"",substrThree];
            [socialMediaArray addObject:[NSDictionary dictionaryWithObjectsAndKeys:@"When you send an event invitation.",@"title",subTitleStringThree,@"sub_title",@"0",@"value",@"fbInviteSend",@"key", nil]];
        }
        else {
            image = [UIImage imageNamed:@"linkedInSocialMedia"];
            commonString = @"linkedin";
            
            NSString *substrOne = @"Just sent a referral to username via FoxHopr";
            NSString *subTitleStringOne = [NSString stringWithFormat:@"Automatically posts: \"%@\"",substrOne];
            [socialMediaArray addObject:[NSDictionary dictionaryWithObjectsAndKeys:@"When you send a referral.",@"title",subTitleStringOne,@"sub_title",@"0",@"value",@"linkedinReferralSend",@"key", nil]];
            
            NSString *substrtwo = @"Just sent a message to username via FoxHopr";
            NSString *subTitleStringtwo = [NSString stringWithFormat:@"Automatically posts: \"%@\"",substrtwo];
            [socialMediaArray addObject:[NSDictionary dictionaryWithObjectsAndKeys:@"When you send a message.",@"title",subTitleStringtwo,@"sub_title",@"0",@"value",@"linkedinMessageSend",@"key", nil]];
            
            NSString *substrThree = @"Just set up a calendar event with username via FoxHopr";
            NSString *subTitleStringThree = [NSString stringWithFormat:@"Automatically posts: \"%@\"",substrThree];
            [socialMediaArray addObject:[NSDictionary dictionaryWithObjectsAndKeys:@"When you send an event invitation.",@"title",subTitleStringThree,@"sub_title",@"0",@"value",@"linkedinInviteSend",@"key", nil]];
        }
    }
    else if ([checkConditionString isEqualToString:@"twitter"]) {
        
        commonString = @"twitter";
        lblTopTitle.text = @"CONFIGURE TWEETS";
        
        NSString *substrOne = @"Just sent a referral to @username via @FoxHopr";
        NSString *subTitleStringOne = [NSString stringWithFormat:@"Automatically tweets: \"%@\"",substrOne];
        [socialMediaArray addObject:[NSDictionary dictionaryWithObjectsAndKeys:@"When you send a referral.",@"title",subTitleStringOne,@"sub_title",@"0",@"value",@"tweetReferralSend",@"key", nil]];
        
        NSString *substrtwo = @"Just sent a message to @username via @FoxHopr";
        NSString *subTitleStringtwo = [NSString stringWithFormat:@"Automatically tweets: \"%@\"",substrtwo];
        [socialMediaArray addObject:[NSDictionary dictionaryWithObjectsAndKeys:@"When you send a message.",@"title",subTitleStringtwo,@"sub_title",@"0",@"value",@"tweetMessageSend",@"key", nil]];
        
        NSString *substrThree = @"Just set up a calendar event with @username via @FoxHopr";
        NSString *subTitleStringThree = [NSString stringWithFormat:@"Automatically tweets: \"%@\"",substrThree];
        [socialMediaArray addObject:[NSDictionary dictionaryWithObjectsAndKeys:@"When you send an event invitation.",@"title",subTitleStringThree,@"sub_title",@"0",@"value",@"tweetInviteSend",@"key", nil]];
    }
    [imgField setImage:image];
    [self populateSelectAndUnSelecte];
    
    [self getSocialMediaRecord];
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Save Suggestion
 * @Modified Date 01/12/2015
 */
#pragma mark Save Suggestion
- (void)getSocialMediaRecord {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(getSocialMediaRecordDelay) withObject:nil afterDelay:0.1];
}
-(void)getSocialMediaRecordDelay {
    
    //for Facebook / Twitter / LinkedIn
    NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:@"list",@"mode",commonString,@"list",nil];
    
    NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                       options:NSJSONWritingPrettyPrinted error:nil];
    NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
    
    NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
    
    if (isNetworkAvailable) {
        NSDictionary *parsedJSONResult =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"social" controls:@"businessOwners" httpMethod:@"POST" data:data];
        
        if (parsedJSONResult != nil) {
            NSDictionary *dict = [parsedJSONResult valueForKey:@"result"];
            NSString *stringOne = @"";
            NSString *stringTwo = @"";
            NSString *stringThree = @"";
            NSString *keyStrOne = @"";
            NSString *keyStrTwo = @"";
            NSString *keyStrThree = @"";
            
            //For Facebook
            if ([commonString isEqualToString:@"facebook"]) {
                stringOne = [NSString stringWithFormat:@"%@",[dict valueForKey:@"fbReferralSend"]];
                stringTwo = [NSString stringWithFormat:@"%@",[dict valueForKey:@"fbMessageSend"]];
                stringThree = [NSString stringWithFormat:@"%@",[dict valueForKey:@"fbInviteSend"]];
                NSString *saveBtnEnableStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"facebook"]];
                if ([saveBtnEnableStr isEqualToString:@"0"]) {
                    [btnSave setUserInteractionEnabled:NO];
                    btnSave.alpha = 0.5f;
                }
                keyStrOne = @"fbReferralSend";
                keyStrTwo = @"fbMessageSend";
                keyStrThree = @"fbInviteSend";
            }
            //For Twitter
            else if ([commonString isEqualToString:@"twitter"]) {
                stringOne = [NSString stringWithFormat:@"%@",[dict valueForKey:@"tweetReferralSend"]];
                stringTwo = [NSString stringWithFormat:@"%@",[dict valueForKey:@"tweetMessageSend"]];
                stringThree = [NSString stringWithFormat:@"%@",[dict valueForKey:@"tweetInviteSend"]];
                NSString *saveBtnEnableStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"twitter"]];
                if ([saveBtnEnableStr isEqualToString:@"0"]) {
                    [btnSave setUserInteractionEnabled:NO];
                    btnSave.alpha = 0.5f;
                }
                keyStrOne = @"tweetReferralSend";
                keyStrTwo = @"tweetMessageSend";
                keyStrThree = @"tweetInviteSend";
            }
            //For LinkedIn
            else if ([commonString isEqualToString:@"linkedin"]) {
                stringOne = [NSString stringWithFormat:@"%@",[dict valueForKey:@"linkedinReferralSend"]];
                stringTwo = [NSString stringWithFormat:@"%@",[dict valueForKey:@"linkedinMessageSend"]];
                stringThree = [NSString stringWithFormat:@"%@",[dict valueForKey:@"linkedinInviteSend"]];
                NSString *saveBtnEnableStr = [NSString stringWithFormat:@"%@",[dict valueForKey:@"linkedin"]];
                if ([saveBtnEnableStr isEqualToString:@"0"]) {
                    [btnSave setUserInteractionEnabled:NO];
                    btnSave.alpha = 0.5f;
                }
                keyStrOne = @"linkedinReferralSend";
                keyStrTwo = @"linkedinMessageSend";
                keyStrThree = @"linkedinInviteSend";
            }
            
            for (int i = 0; i<socialMediaArray.count; i++) {
                if (i == 0) {
                    NSString *string1 = [NSString stringWithFormat:@"%@",[[socialMediaArray objectAtIndex:i] valueForKey:@"title"]];
                    NSString *string2 = [NSString stringWithFormat:@"%@",[[socialMediaArray objectAtIndex:i] valueForKey:@"sub_title"]];
                    NSDictionary *dictionary = [NSDictionary dictionaryWithObjectsAndKeys:string1,@"title",string2,@"sub_title",stringOne,@"value",keyStrOne,@"key", nil];
                    [socialMediaArray replaceObjectAtIndex:0 withObject:dictionary];
                }
               else if (i == 1) {
                    NSString *string1 = [NSString stringWithFormat:@"%@",[[socialMediaArray objectAtIndex:i] valueForKey:@"title"]];
                    NSString *string2 = [NSString stringWithFormat:@"%@",[[socialMediaArray objectAtIndex:i] valueForKey:@"sub_title"]];
                    NSDictionary *dictionary = [NSDictionary dictionaryWithObjectsAndKeys:string1,@"title",string2,@"sub_title",stringTwo,@"value",keyStrTwo,@"key", nil];
                    [socialMediaArray replaceObjectAtIndex:1 withObject:dictionary];
                }
               else {
                   NSString *string1 = [NSString stringWithFormat:@"%@",[[socialMediaArray objectAtIndex:i] valueForKey:@"title"]];
                   NSString *string2 = [NSString stringWithFormat:@"%@",[[socialMediaArray objectAtIndex:i] valueForKey:@"sub_title"]];
                   NSDictionary *dictionary = [NSDictionary dictionaryWithObjectsAndKeys:string1,@"title",string2,@"sub_title",stringThree,@"value",keyStrThree,@"key", nil];
                   [socialMediaArray replaceObjectAtIndex:2 withObject:dictionary];
               }
            }
        }
    }
    else {
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
   
    [self populateSelectAndUnSelecte];
    [tableView reloadData];
    [[AppDelegate currentDelegate] removeLoading];
}


/*
 * @Auther Deepak chauhan
 * @Parm none
 * @Description These method are used for insert bool value in array according to list
 * @Modified Date 26/11/2015
 */
#pragma mark populate UnSelected
- (void)populateSelectAndUnSelecte {
    NSMutableArray *array = [[NSMutableArray alloc] initWithCapacity:[socialMediaArray count]];
    for (int i=0; i < [socialMediaArray count]; i++) {
        NSString *string = [NSString stringWithFormat:@"%@",[[socialMediaArray objectAtIndex:i] valueForKey:@"value"]];
        if ([string isEqualToString:@"0"]) {
            [array addObject:[NSNumber numberWithBool:NO]];
        }
        else {
            [array addObject:[NSNumber numberWithBool:YES]];
        }
    }
    selectedRowArray = array;
}

#pragma marl - UITableView Data Source
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return [socialMediaArray count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView1 cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    static NSString *PlaceholderCellIdentifier = @"socialMediaCell";
    
    SocialMediaCell*cell = [tableView1 dequeueReusableCellWithIdentifier:PlaceholderCellIdentifier];
    cell.backgroundColor = [UIColor clearColor];
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
    if (cell == nil) {
        cell = [[SocialMediaCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:PlaceholderCellIdentifier];
    }
    if (socialMediaArray.count) {
        NSDictionary *dict = [socialMediaArray objectAtIndex:indexPath.row];
        
        cell.lblTitle.text = [dict valueForKey:@"title"];
        
        cell.lblSubTitle.text = [dict valueForKey:@"sub_title"];
        
        [cell.btnSelect setTag:indexPath.row];
        [cell.btnSelect addTarget:self action:@selector(btnSelectOption:) forControlEvents:UIControlEventTouchUpInside];
        NSNumber *selected = [selectedRowArray objectAtIndex:[indexPath row]];
        UIImage *image = ([selected boolValue]) ? self.selectedImage : self.unSelectedImage;
        [cell.btnSelect setBackgroundImage:image forState:UIControlStateNormal];
    }
    
    return cell;
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description These method are used for select Option for Enable posts or tweets
 * @Modified Date 26/11/2015
 */
#pragma mark Selected Option
-(void)btnSelectOption:(UIButton *)sender {
    int index = (int)sender.tag;
    BOOL selected = [[selectedRowArray objectAtIndex:index] boolValue];
    [selectedRowArray replaceObjectAtIndex:index withObject:[NSNumber numberWithBool:!selected]];
    [tableView reloadData];
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {

}

- (CGFloat)tableView:(UITableView *)aTableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    return 70;
}


/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Set Post Or Tweets
 * @Modified Date 04/08/2015
 */
#pragma mark Set Post Or Tweets
-(IBAction)savePostAndTweetsSocialMedia:(id)sender {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(savePostAndTweetsSocialMediaDelay) withObject:nil afterDelay:0.1];
}
-(void)savePostAndTweetsSocialMediaDelay {
    
    NSMutableArray *enablePostOrTweetsArray = [[NSMutableArray alloc] init];
    for (int i = 0; i<selectedRowArray.count; i++) {
        BOOL selected = [[selectedRowArray objectAtIndex:i] boolValue];
        if (selected) {
            NSString *valueString = [[socialMediaArray objectAtIndex:i] valueForKey:@"key"];
            [enablePostOrTweetsArray addObject:valueString];
        }
    }
    
    if (isNetworkAvailable) {
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:commonString,@"list",enablePostOrTweetsArray,@"updateFields",commonString,@"updateSocial",@"edit",@"mode",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"social" controls:@"businessOwners" httpMethod:@"POST" data:data];
        
        [[AppDelegate currentDelegate] removeLoading];
       
        if (parsedJSONToken != nil) {
            NSString *messageString = [parsedJSONToken valueForKey:@"message"];
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:messageString delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            [self.navigationController popViewControllerAnimated:NO];
            if ([self.socialMediaDelegate respondsToSelector:@selector(updateSocialMediaInfo)]) {
                [self.socialMediaDelegate updateSocialMediaInfo];
            }
        }
    }
    else {
        [[AppDelegate currentDelegate] removeLoading];
        UIAlertView *networkAlert = [[UIAlertView alloc] initWithTitle:@"" message:netWorkNotAvilable delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [networkAlert show];
    }
}



/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for back screen
 * @Modified Date 26/11/2015
 */
#pragma mark Back Screen
- (IBAction)backScreen:(id)sender {
    [self.navigationController popViewControllerAnimated:NO];
    if ([self.socialMediaDelegate respondsToSelector:@selector(updateSocialMediaInfo)]) {
        [self.socialMediaDelegate updateSocialMediaInfo];
    }
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
