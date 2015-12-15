//
//  ContactDetailViewController.m
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 9/30/15.
//  Copyright © 2015 Deepak Chauhan. All rights reserved.
//

#import "TeamMemberDetailViewController.h"

@interface TeamMemberDetailViewController ()

@end

@implementation TeamMemberDetailViewController
@synthesize memberIDStr;

- (void)viewDidLoad {
    [super viewDidLoad];
    
    //For get the contact detail from server
    [self teamMemberDetail];
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
 * @Parm none
 * @Description These method are used for get Contact detail
 * @Modified Date 05/10/2015
 */
#pragma mark Get Contact Detail
- (void)teamMemberDetail {
    [[AppDelegate currentDelegate] addLoading];
    [self performSelector:@selector(teamMemberDetailDelay) withObject:nil afterDelay:0.1];
}
- (void)teamMemberDetailDelay {
    if (isNetworkAvailable) {
        
        NSDictionary *tokenDictionary = [NSDictionary dictionaryWithObjectsAndKeys:memberIDStr,@"memberId",nil];
        
        NSData *jsonData = [NSJSONSerialization dataWithJSONObject:tokenDictionary
                                                           options:NSJSONWritingPrettyPrinted error:nil];
        NSString *urlString = [[NSString alloc] initWithData:jsonData encoding:NSUTF8StringEncoding];
        
        NSData* data = [urlString dataUsingEncoding:NSUTF8StringEncoding];
        
        NSDictionary *parsedJSONToken =(NSDictionary*) [[ConnectionManager sharedManager] sendSynchronousRequest:@"" methodHeader:@"memberDetail" controls:@"teams" httpMethod:@"POST" data:data];
        
        if (parsedJSONToken != nil) {
            
            recordDict = [parsedJSONToken valueForKey:@"result"];
            
            //For First Name
            NSString *firstNameString = [recordDict valueForKey:@"fname"];
            if (CheckStringForNull(firstNameString)) {
                firstNameString = @"";
            }
            else {
                firstNameString = [firstNameString stringByReplacingCharactersInRange:NSMakeRange(0,1)
                                                                   withString:[[firstNameString substringToIndex:1] capitalizedString]];
            }
            //For Last Name
            NSString *lastNameString = [recordDict valueForKey:@"lname"];
            if (CheckStringForNull(lastNameString)) {
                lastNameString = @"";
            }
            else {
                lastNameString = [NSString stringWithFormat:@" %@",[recordDict valueForKey:@"lname"]];
                lastNameString = [lastNameString stringByReplacingCharactersInRange:NSMakeRange(0,2)
                                                                           withString:[[lastNameString substringToIndex:2] capitalizedString]];
            }
            firstNameString = [firstNameString stringByAppendingString:lastNameString];
            self.lblName.text = firstNameString;
            [self.lblName sizeToFit];
            
            self.lblProfession.frame = CGRectMake(self.lblProfession.frame.origin.x, self.lblName.frame.origin.y+self.lblName.frame.size.height, self.lblProfession.frame.size.width, self.lblProfession.frame.size.height);
            
            //For Profession
            NSString *professionString = [recordDict valueForKey:@"profession_name"];
            if (CheckStringForNull(professionString)) {
                professionString = @"NA";
            }
            else {
                professionString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"profession_name"]];
            }
            
            //For Company
            NSString *coumpanyString = [recordDict valueForKey:@"company"];
            coumpanyString = [coumpanyString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(coumpanyString)) {
                coumpanyString = @", NA";
            }
            else {
                coumpanyString = [NSString stringWithFormat:@", %@",[recordDict valueForKey:@"company"]];
            }
            professionString = [professionString stringByAppendingString:coumpanyString];
            
            if ([professionString isEqualToString:@"NA, NA"]) {
                professionString = @"NA";
                self.lblProfession.textColor = [UIColor darkGrayColor];
            }
            else {
                self.lblProfession.textColor = orangeColour;
            }
            
            self.lblProfession.text = professionString;
            [self.lblProfession sizeToFit];
            
            viewOne.frame = CGRectMake(viewOne.frame.origin.x, viewOne.frame.origin.y, viewOne.frame.size.width, self.lblProfession.frame.origin.y+self.lblProfession.frame.size.height+30);
            
            viewTwo.frame = CGRectMake(viewTwo.frame.origin.x, viewOne.frame.origin.y+viewOne.frame.size.height, viewTwo.frame.size.width, viewTwo.frame.size.height);

            //For User Profile Image
            imgProfile.layer.cornerRadius = 25.0f;
            imgProfile.clipsToBounds = YES;
            NSString *profileUrlStr = [recordDict valueForKey:@"member_profile_image"];
            if (!CheckStringForNull(profileUrlStr)) {
                [imgProfile setImage:[UIImage imageWithData:[NSData dataWithContentsOfURL:[NSURL URLWithString:profileUrlStr]]]];
            }
            
            //For fill Star
            NSString *ratingValStr = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"rating"]];
            NSString *totalSt = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"totalReview"]];
            NSString *totalReviewStr  = [NSString stringWithFormat:@"%@\nreview(s)",totalSt];
            NSMutableAttributedString * string = [[NSMutableAttributedString alloc] initWithString:totalReviewStr];
            [string addAttribute:NSForegroundColorAttributeName value:orangeColour range:NSMakeRange(0,totalSt.length)];
            [string addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"Graphik-Bold" size:12.0] range:NSMakeRange(0,totalSt.length)];
            lblReview.attributedText = string;

            if (!CheckStringForNull(ratingValStr)) {
                int starVal = (int)ratingValStr.intValue;
                switch (starVal) {
                    case 0:
                        [imgStarOne setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarTwo setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarThree setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarFour setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                        break;
                    case 1:
                        [imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarTwo setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarThree setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarFour setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                         break;
                    case 2:
                        [imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarTwo setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarThree setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarFour setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                         break;
                    case 3:
                        [imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarTwo setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarThree setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarFour setImage:[UIImage imageNamed:@"Blank-star"]];
                        [imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                        break;
                    case 4:
                        [imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarTwo setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarThree setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarFour setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarFive setImage:[UIImage imageNamed:@"Blank-star"]];
                          break;
                    case 5:
                        [imgStarOne setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarTwo setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarThree setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarFour setImage:[UIImage imageNamed:@"Full-star"]];
                        [imgStarFive setImage:[UIImage imageNamed:@"Full-star"]];
                        break;
                        
                    default:
                        break;
                }
            }
            
            
            //For Email
            NSString *emailString = [recordDict valueForKey:@"email"];
            if (CheckStringForNull(emailString)) {
                self.lblEmail.textColor = [UIColor darkGrayColor];
                emailString = @"NA";
            }
            else {
                self.lblEmail.textColor = orangeColour;
            }
            self.lblEmail.text = emailString;
            
            //For Web Link
            NSString *webLinkString = [recordDict valueForKey:@"website"];
            if (CheckStringForNull(webLinkString)) {
                self.lblWeb.textColor = [UIColor darkGrayColor];
                webLinkString = @"NA";
            }
            else {
                self.lblWeb.textColor = orangeColour;
            }
            self.lblWeb.text = webLinkString;
            
            //For Web Link Two
            NSString *webLinkTwoString = [recordDict valueForKey:@"website1"];
            if (CheckStringForNull(webLinkTwoString)) {
                self.lblWebTwo.textColor = [UIColor darkGrayColor];
                webLinkTwoString = @"NA";
            }
            else {
                self.lblWebTwo.textColor = orangeColour;
            }
            self.lblWebTwo.text = webLinkTwoString;
            
            //For Mobile Number
            NSString *mobileNumString = [recordDict valueForKey:@"mobile"];
            if (CheckStringForNull(mobileNumString)) {
                mobileNumString = @"NA";
            }
            self.lblMobile.text = mobileNumString;
            
            //For Office Number
            NSString *officeNumString = [recordDict valueForKey:@"office_phone"];
            if (CheckStringForNull(officeNumString)) {
                officeNumString = @"NA";
            }
            self.lblPhone.text = officeNumString;
            
            //For Skyp
            NSString *skypIdStr  = [recordDict valueForKey:@"skype_id"];
            if (CheckStringForNull(skypIdStr)) {
                [imgSkyp setHidden:YES];
                [lblSkyp setHidden:YES];
                imgTimezon.frame = CGRectMake(imgTimezon.frame.origin.x, imgTimezon.frame.origin.y-36, imgTimezon.frame.size.width, imgTimezon.frame.size.height);
                self.lbltimeJon.frame = CGRectMake(self.lbltimeJon.frame.origin.x, self.lbltimeJon.frame.origin.y-36, self.lbltimeJon.frame.size.width, self.lbltimeJon.frame.size.height);
                imgPinAddress.frame = CGRectMake(imgPinAddress.frame.origin.x, imgPinAddress.frame.origin.y-36, imgPinAddress.frame.size.width, imgPinAddress.frame.size.height);
                self.lblAddress.frame = CGRectMake(self.lblAddress.frame.origin.x, self.lblAddress.frame.origin.y-36, self.lblAddress.frame.size.width, self.lblAddress.frame.size.height);
                self.imgSeprator.frame = CGRectMake(self.imgSeprator.frame.origin.x, self.imgSeprator.frame.origin.y-36, self.imgSeprator.frame.size.width, self.imgSeprator.frame.size.height);
                viewTwo.frame = CGRectMake(viewTwo.frame.origin.x, viewTwo.frame.origin.y, viewTwo.frame.size.width, viewTwo.frame.size.height-36);
                viewThree.frame = CGRectMake(viewThree.frame.origin.x, viewThree.frame.origin.y-36, viewThree.frame.size.width, viewThree.frame.size.height);
                viewFour.frame = CGRectMake(viewFour.frame.origin.x, viewFour.frame.origin.y-36, viewFour.frame.size.width, viewFour.frame.size.height);
            }
            else {
                [imgSkyp setHidden:NO];
                [lblSkyp setHidden:NO];
                lblSkyp.text = skypIdStr;

            }
            
            //For GMT Location
            NSString *timeJonString = [recordDict valueForKey:@"timezone_id"];
            if (CheckStringForNull(timeJonString)) {
                timeJonString = @"NA";
            }
            self.lbltimeJon.text = timeJonString;
            [self.lbltimeJon sizeToFit];
            
            imgPinAddress.frame = CGRectMake(imgPinAddress.frame.origin.x, self.lbltimeJon.frame.origin.y+self.lbltimeJon.frame.size.height+19, imgPinAddress.frame.size.width, imgPinAddress.frame.size.height);
            self.lblAddress.frame = CGRectMake(self.lblAddress.frame.origin.x, self.lbltimeJon.frame.origin.y+self.lbltimeJon.frame.size.height+16, self.lblAddress.frame.size.width, self.lblAddress.frame.size.height);

            //For Address
            NSString *addressString = [recordDict valueForKey:@"address"];
            addressString = [addressString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            addressString = [addressString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(addressString)) {
                addressString = @"";
            }
            else {
                addressString = [NSString stringWithFormat:@"%@\n",[recordDict valueForKey:@"address"]];
            }
            //For City
            NSString *cityString = [recordDict valueForKey:@"city"];
            cityString = [cityString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
            if (CheckStringForNull(cityString)) {
                cityString = @"";
            }
            else {
                cityString = [NSString stringWithFormat:@"%@, ",[recordDict valueForKey:@"city"]];
            }
            //For Zip
            NSString *zipString = [recordDict valueForKey:@"zipcode"];
            if (CheckStringForNull(zipString)) {
                zipString = @"";
            }
            else {
                zipString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"zipcode"]];
            }
            //For State
            NSString *stateString = [recordDict valueForKey:@"state_name"];
            if (CheckStringForNull(stateString)) {
                stateString = @"";
            }
            else {
                if (CheckStringForNull(zipString)) {
                    stateString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"state_name"]];
                }
                else {
                    stateString = [NSString stringWithFormat:@", %@",[recordDict valueForKey:@"state_name"]];
                }
            }
             zipString = [zipString stringByAppendingString:stateString];
            //For Country
            NSString *countryString = [recordDict valueForKey:@"country_name"];
            if (CheckStringForNull(countryString)) {
                countryString = @"";
            }
            else {
                if (CheckStringForNull(zipString)) {
                    countryString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"country_name"]];
                }
                else {
                    countryString = [NSString stringWithFormat:@", %@",[recordDict valueForKey:@"country_name"]];
                }
            }
            zipString = [zipString stringByAppendingString:countryString];
            cityString = [cityString stringByAppendingString:zipString];
            addressString = [addressString stringByAppendingString:cityString];
            
            self.lblAddress.text = addressString;
            [self.lblAddress sizeToFit];
            
            self.imgSeprator.frame = CGRectMake(self.imgSeprator.frame.origin.x, self.lblAddress.frame.origin.y+self.lblAddress.frame.size.height+30, self.imgSeprator.frame.size.width, self.imgSeprator.frame.size.height);
            viewTwo.frame = CGRectMake(viewTwo.frame.origin.x, viewTwo.frame.origin.y, viewTwo.frame.size.width, self.imgSeprator.frame.origin.y+10);
            viewThree.frame = CGRectMake(viewThree.frame.origin.x, viewTwo.frame.origin.y+viewTwo.frame.size.height, viewThree.frame.size.width, viewThree.frame.size.height);
            viewFour.frame = CGRectMake(viewFour.frame.origin.x, viewThree.frame.origin.y+viewThree.frame.size.height, viewFour.frame.size.width, viewFour.frame.size.height);

            scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewThree.frame.origin.y+viewThree.frame.size.height);
            
            float height = 5.0;
            //For About Me
            NSString *aboutMeString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"aboutme"]];
            if (!CheckStringForNull(aboutMeString)) {
                UILabel *label = [[UILabel alloc] initWithFrame:CGRectMake(50, height, viewFour.frame.size.width-100, 30)];
                [label setFont:[UIFont fontWithName:@"Graphik-Bold" size:15.0f]];
                [label setTextAlignment:NSTextAlignmentCenter];
                [label setText:@"About Me"];
                [label setTextColor:[UIColor blackColor]];
                [viewFour addSubview:label];
                
                UILabel *label2 = [[UILabel alloc] initWithFrame:CGRectMake(10, height+30, viewFour.frame.size.width-20, 30)];
                [label2 setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
                [label2 setText:aboutMeString];
                [label2 setNumberOfLines:0];
                [label2 setTextAlignment:NSTextAlignmentJustified];
                [label2 sizeToFit];
                [label2 setTextColor:[UIColor darkGrayColor]];
                [viewFour addSubview:label2];
                
                UIImageView *sepratorImgView = [[UIImageView alloc] initWithFrame:CGRectMake(10, label2.frame.origin.y+label2.frame.size.height+5, viewFour.frame.size.width-20, 2)];
                [sepratorImgView setImage:[UIImage imageNamed:@"sepratorLine"]];
                [viewFour addSubview:sepratorImgView];
                
                viewFour.frame = CGRectMake(viewFour.frame.origin.x, viewThree.frame.origin.y+viewThree.frame.size.height, viewFour.frame.size.width, label2.frame.origin.y+label2.frame.size.height+15);

                height  = label2.frame.origin.y+label2.frame.size.height+15;
                
                scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewFour.frame.origin.y+viewFour.frame.size.height);
            }
            
            //For company Description
            NSString *compDescString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"business_description"]];
            if (!CheckStringForNull(compDescString)) {
                UILabel *label = [[UILabel alloc] initWithFrame:CGRectMake(50, height, viewFour.frame.size.width-100, 30)];
                [label setFont:[UIFont fontWithName:@"Graphik-Bold" size:15.0f]];
                [label setText:@"Company Description"];
                [label setTextAlignment:NSTextAlignmentCenter];
                [label setTextColor:[UIColor blackColor]];
                [viewFour addSubview:label];
                
                UILabel *label2 = [[UILabel alloc] initWithFrame:CGRectMake(10, height+30, viewFour.frame.size.width-20, 30)];
                [label2 setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
                [label2 setText:compDescString];
                [label2 setNumberOfLines:0];
                [label2 setTextAlignment:NSTextAlignmentJustified];
                [label2 sizeToFit];
                [label2 setTextColor:[UIColor darkGrayColor]];
                [viewFour addSubview:label2];
                
                UIImageView *sepratorImgView = [[UIImageView alloc] initWithFrame:CGRectMake(10, label2.frame.origin.y+label2.frame.size.height+5, viewFour.frame.size.width-20, 2)];
                [sepratorImgView setImage:[UIImage imageNamed:@"sepratorLine"]];
                [viewFour addSubview:sepratorImgView];

                viewFour.frame = CGRectMake(viewFour.frame.origin.x, viewThree.frame.origin.y+viewThree.frame.size.height, viewFour.frame.size.width, label2.frame.origin.y+label2.frame.size.height+15);
                
                height  = label2.frame.origin.y+label2.frame.size.height+15;
                
                scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewFour.frame.origin.y+viewFour.frame.size.height);
            }
            //For Services
            NSString *servicesString = [NSString stringWithFormat:@"%@",[recordDict valueForKey:@"services"]];
            if (!CheckStringForNull(servicesString)) {
                UILabel *label = [[UILabel alloc] initWithFrame:CGRectMake(50, height, viewFour.frame.size.width-100, 30)];
                [label setFont:[UIFont fontWithName:@"Graphik-Bold" size:15.0f]];
                [label setText:@"Services"];
                [label setTextAlignment:NSTextAlignmentCenter];
                [label setTextColor:[UIColor blackColor]];
                [viewFour addSubview:label];
                
                UILabel *label2 = [[UILabel alloc] initWithFrame:CGRectMake(10, height+30, viewFour.frame.size.width-20, 30)];
                [label2 setFont:[UIFont fontWithName:@"Graphik-Regular" size:15.0f]];
                [label2 setText:servicesString];
                [label2 setNumberOfLines:0];
                [label2 setTextAlignment:NSTextAlignmentJustified];
                [label2 sizeToFit];
                [label2 setTextColor:[UIColor darkGrayColor]];
                [viewFour addSubview:label2];
                
                viewFour.frame = CGRectMake(viewFour.frame.origin.x, viewThree.frame.origin.y+viewThree.frame.size.height, viewFour.frame.size.width, label2.frame.origin.y+label2.frame.size.height+15);
                
                height  = label2.frame.origin.y+label2.frame.size.height+15;
                
                scrollView.contentSize = CGSizeMake(scrollView.frame.size.width, viewFour.frame.origin.y+viewFour.frame.size.height);
            }
            
            //For Facebook
            NSString *facebookIdString = [recordDict valueForKey:@"facebook_profile_id"];
            [btnFacebook setUserInteractionEnabled:YES];
            [btnFacebook setAlpha:1.0f];
            if (CheckStringForNull(facebookIdString)) {
                [btnFacebook setUserInteractionEnabled:NO];
                [btnFacebook setAlpha:0.5f];
            }
            //For Twitter
            NSString *twitterIdString = [recordDict valueForKey:@"twitter_profile_id"];
            [btnTwitter setUserInteractionEnabled:YES];
            [btnTwitter setAlpha:1.0f];
            if (CheckStringForNull(twitterIdString)) {
                [btnTwitter setUserInteractionEnabled:NO];
                [btnTwitter setAlpha:0.5f];
            }
            //For Linked IN
            NSString *linkedInIdString = [recordDict valueForKey:@"linkedin_profile_id"];
            [btnLinkedin setUserInteractionEnabled:YES];
            [btnLinkedin setAlpha:1.0f];
            if (CheckStringForNull(linkedInIdString)) {
                [btnLinkedin setUserInteractionEnabled:NO];
                [btnLinkedin setAlpha:0.5f];
            }
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
 * @Description This method are used for Open nativ app for facebook
 * @Modified Date 12/10/2015
 */
#pragma mark Open Facebook
-(IBAction)OpenFacebook:(id)sender {
    
    NSString *facebookIdString = [recordDict valueForKey:@"facebook_profile_id"];
    if (!CheckStringForNull(facebookIdString)) {
        NSArray *arrayYouTube = [facebookIdString componentsSeparatedByString:@"//"];
        NSString *metchString = @"";
        if (arrayYouTube.count > 1) {
            NSString *typeString  = [arrayYouTube objectAtIndex:0];
            if ([typeString isEqualToString:@"http:"] || [typeString isEqualToString:@"https:"]) {
                metchString = @"";
            }
            else {
                metchString = @"http://";
            }
        }
        else {
            metchString = @"http://";
        }
        metchString = [metchString stringByAppendingString:facebookIdString];
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:metchString]];
    }

}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Open nativ app for Twitter
 * @Modified Date 12/10/2015
 */
#pragma mark Open Twitter
-(IBAction)OpenTwitter:(id)sender {
    NSString *twitterString = [recordDict valueForKey:@"twitter_profile_id"];
    if (!CheckStringForNull(twitterString)) {
        NSArray *arrayTwitter = [twitterString componentsSeparatedByString:@"//"];
        NSString *metchString = @"";
        if (arrayTwitter.count > 1) {
            NSString *typeString  = [arrayTwitter objectAtIndex:0];
            if ([typeString isEqualToString:@"http:"] || [typeString isEqualToString:@"https:"]) {
                metchString = @"";
            }
            else {
                metchString = @"http://";
            }
        }
        else {
            metchString = @"http://";
        }
        metchString = [metchString stringByAppendingString:twitterString];
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:metchString]];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Open nativ app for Linked In
 * @Modified Date 12/10/2015
 */
#pragma mark Open Linked In
-(IBAction)OpenLinkedIn:(id)sender {
    NSString *linkedInString = [recordDict valueForKey:@"linkedin_profile_id"];
     if (!CheckStringForNull(linkedInString)) {
         NSArray *arrayLinkedIn = [linkedInString componentsSeparatedByString:@"//"];
         NSString *metchString = @"";
         if (arrayLinkedIn.count > 1) {
             NSString *typeString  = [arrayLinkedIn objectAtIndex:0];
             if ([typeString isEqualToString:@"http:"] || [typeString isEqualToString:@"https:"]) {
                 metchString = @"";
             }
             else {
                 metchString = @"http://";
             }
         }
         else {
             metchString = @"http://";
         }
         metchString = [metchString stringByAppendingString:linkedInString];
         [[UIApplication sharedApplication] openURL:[NSURL URLWithString:metchString]];
     }
}


/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Open web site link on browser
 * @Modified Date 05/10/2015
 */
#pragma mark Redirect On Browser
-(IBAction)redirectOnBrowser:(UIButton *)sender {
    NSString *webLinkStr = @"";
    if (sender.tag == 200) {
        webLinkStr =  [self.lblWeb text];
    }
    else {
        webLinkStr =  [self.lblWebTwo text];
    }
   
    if (![webLinkStr isEqualToString:@"NA"]) {
        NSArray *arrayYouTube = [webLinkStr componentsSeparatedByString:@"//"];
        NSString *metchString = @"";
        if (arrayYouTube.count > 1) {
            NSString *typeString  = [arrayYouTube objectAtIndex:0];
            if ([typeString isEqualToString:@"http:"] || [typeString isEqualToString:@"https:"]) {
                metchString = @"";
            }
            else {
                metchString = @"http://";
            }
        }
        else {
            metchString = @"http://";
        }
        metchString = [metchString stringByAppendingString:webLinkStr];
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:metchString]];
    }
}

/*
 * @Auther Deepak chauhan
 * @Parm sender
 * @Description This method are used for Send mail
 * @Modified Date 06/10/2015
 */
#pragma mark Send Mail
-(IBAction)sendMail:(id)sender {
    NSString *mailStr = [self.lblEmail text];
    if (![mailStr isEqualToString:@"NA"]) {
        
        if (CheckStringForNull(mailStr)) {
            mailStr = @"";
            return;
        }
        NSArray *toRecipents = [NSArray arrayWithObject:mailStr];
        
        if ([MFMailComposeViewController canSendMail])
        {
            MFMailComposeViewController *mail = [[MFMailComposeViewController alloc] init];
            mail.mailComposeDelegate = self;
            [mail setSubject:@""];
            [mail setMessageBody:@"" isHTML:NO];
            [mail setToRecipients:toRecipents];
            
            [self presentViewController:mail animated:YES completion:NULL];
        }
        else
        {
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Email client is not configured. Please configure your email account." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
        }
    }
}

/*!
 @method     mailComposeController:didFinishWithResult:error:
 @abstract   Delegate callback which is called upon user's completion of email composition.
 @discussion This delegate callback will be called when the user completes the email composition.  How the user chose
 to complete this task will be given as one of the parameters to the callback.  Upon this call, the client
 should remove the view associated with the controller, typically by dismissing modally.
 @param      controller   The MFMailComposeViewController instance which is returning the result.
 @param      result       MFMailComposeResult indicating how the user chose to complete the composition process.
 @param      error        NSError indicating the failure reason if failure did occur.  This will be <tt>nil</tt> if
 result did not indicate failure.
 */
#pragma mark mailComposeController
- (void) mailComposeController:(MFMailComposeViewController *)controller didFinishWithResult:(MFMailComposeResult)result error:(NSError *)error
{
    switch (result)
    {
        case MFMailComposeResultCancelled:
            NSLog(@"Mail cancelled");
            break;
        case MFMailComposeResultSaved:
            NSLog(@"Mail saved");
            break;
        case MFMailComposeResultSent:
            NSLog(@"Mail sent");
            break;
        case MFMailComposeResultFailed:
            NSLog(@"Mail sent failure: %@", [error localizedDescription]);
            break;
        default:
            break;
    }
    
    // Close the Mail Interface
    [self dismissViewControllerAnimated:YES completion:NULL];
}


- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}


@end
