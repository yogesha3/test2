//
//  Constants.h
//  OpenSchoolEport
//
//  Created by Rahul Tamrakar on 4/26/13.
//  Copyright (c) 2013 A3Logics(I) Ltd. All rights reserved.
//com.m2e.
//domainbuilders@hotmail.com
//Palm0011

#ifndef OpenSchoolEport_Constants_h
#define OpenSchoolEport_Constants_h

#define isNetworkAvailable [[NSUserDefaults standardUserDefaults] boolForKey:@"isNetworkAvailable"]
#define userDefaultManager [NSUserDefaults standardUserDefaults]

//Local server
//#define RegisterURL  @"http://10.10.11.152/foxhopr/api/"//@"http://10.10.11.152/foxhopr_testing/api/"//
//#define appkey      @"bf1f135e2cf4440ddd4b4c209d9bb319206a406a"

//Live Server
//#define RegisterURL  @""
//#define appkey      @""

//Demo Server
#define RegisterURL      @"http://demotest.a3logics.com/foxhopr_testing/api/"//http://103.231.222.21/foxhopr_testing/api/
#define ShareRegisterURL @"http://demotest.a3logics.com/foxhopr_testing"//@"http://localhost/foxhopr"//
#define appkey           @"bf1f135e2cf4440ddd4b4c209d9bb319206a406a"

//For Colour
#define BoarderColour       [UIColor colorWithRed:225.0/255.0 green:225.0/255.0 blue:225.0/255.0 alpha:1]
#define orangeColour        [UIColor colorWithRed:241.0/255.0 green:90.0/255.0 blue:43.0/255.0 alpha:1]
#define blackCancelBTN      [UIColor colorWithRed:79.0/255.0 green:76.0/255.0 blue:85.0/255.0 alpha:1]
#define blackColour         [UIColor colorWithRed:34.0/255.0 green:31.0/255.0 blue:33.0/255.0 alpha:1]
#define placeHolderColor    [UIColor colorWithRed:163.0/255 green:163.0/255 blue:163.0/255 alpha:1]

//For Message
#define netWorkNotAvilable  @"Please check your internet connectivity."
#define recordPerPage  @"20"
#endif
