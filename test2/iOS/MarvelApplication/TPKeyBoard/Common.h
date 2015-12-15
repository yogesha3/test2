//
//  Common.h
//  MoviApplication
//
//  Created by A3logics on 6/26/14.
//  Copyright (c) 2014 Deepak kumar. All rights reserved.

#import<UIKit/UIKit.h>
#import "Constants.h"
#import "AppDelegate.h"

#ifndef EngNet_Common_h
#define EngNet_Common_h


static inline NSData* encodeDictionary(NSDictionary*dictionary) {
    NSMutableArray *parts = [[NSMutableArray alloc] init];
    for (NSString *key in dictionary) {
        NSString *encodedValue = [[dictionary objectForKey:key] stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
        NSString *encodedKey = [key stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
        NSString *part = [NSString stringWithFormat: @"%@=%@", encodedKey, encodedValue];
        [parts addObject:part];
    }
    NSString *encodedDictionary = [parts componentsJoinedByString:@"&"];
    return [encodedDictionary dataUsingEncoding:NSUTF8StringEncoding];
}

static inline BOOL CheckDeviceFunction() {
    
    if ( UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPad ) {
        return YES;
    }
    else {
        return NO;
    }
}
static inline NSString* uniqueIDForDevice() {
    
        NSString* uniqueIdentifier = nil;
        
        if( [UIDevice instancesRespondToSelector:@selector(identifierForVendor)] ) { // >=iOS 7
            uniqueIdentifier = [[[UIDevice currentDevice] identifierForVendor] UUIDString];
        }
        else { //<=iOS6, Use UDID of Device
            CFUUIDRef uuid = CFUUIDCreate(NULL);
            //uniqueIdentifier = ( NSString*)CFUUIDCreateString(NULL, uuid);- for non- ARC
            uniqueIdentifier = ( NSString*)CFBridgingRelease(CFUUIDCreateString(NULL, uuid));// for ARC
            CFRelease(uuid);
        }
        return uniqueIdentifier;
}
/**
 *
 * Image Compresion
 * @param thumbnail
 * @result image
 * @author Deepak Kumar.
 *
 **/
#pragma mark Image Compresion
static inline UIImage* imageCompressReduseSize(UIImage *thumbnail)
{
    CGSize itemSize;
    float MAX_SIZE=2000.0f;
    if (thumbnail.size.width>MAX_SIZE || thumbnail.size.height>MAX_SIZE) {
        if (thumbnail.size.width>thumbnail.size.height) {
            float ratio=thumbnail.size.height/thumbnail.size.width;
            itemSize = CGSizeMake(MAX_SIZE, MAX_SIZE*ratio);
        }
        else{
            float ratio=thumbnail.size.width/thumbnail.size.height;
            itemSize = CGSizeMake(MAX_SIZE*ratio, MAX_SIZE);
        }
        
        UIGraphicsBeginImageContextWithOptions(itemSize, NO, 0.0);
        [thumbnail drawInRect:CGRectMake(0, 0, itemSize.width, itemSize.height)];
        thumbnail = UIGraphicsGetImageFromCurrentImageContext();
        UIGraphicsEndImageContext();
    }
    return thumbnail;
}

/**
 *
 * Image Compresion
 * @param thumbnail
 * @result image
 * @author Deepak Kumar.
 *
 **/
#pragma mark Image Compresion
static inline UIImage* imageCompressReduseSizeProfile(UIImage *thumbnail)
{
    CGSize itemSize;
    float MAX_SIZE=150.0f;
    if (thumbnail.size.width>MAX_SIZE || thumbnail.size.height>MAX_SIZE) {
        if (thumbnail.size.width>thumbnail.size.height) {
            float ratio=thumbnail.size.height/thumbnail.size.width;
            itemSize = CGSizeMake(MAX_SIZE, MAX_SIZE*ratio);
        }
        else{
            float ratio=thumbnail.size.width/thumbnail.size.height;
            itemSize = CGSizeMake(MAX_SIZE*ratio, MAX_SIZE);
        }
        
        UIGraphicsBeginImageContextWithOptions(itemSize, NO, 0.0);
        [thumbnail drawInRect:CGRectMake(0, 0, itemSize.width, itemSize.height)];
        thumbnail = UIGraphicsGetImageFromCurrentImageContext();
        UIGraphicsEndImageContext();
    }
    return thumbnail;
}


static inline BOOL validateEmail (NSString*emailString)
{
    NSString *regExPattern = @"^[A-Z0-9._%+-]+@[A-Z0-9.-]+\\.[A-Z]{2,4}$";
    NSRegularExpression *regEx = [[NSRegularExpression alloc] initWithPattern:regExPattern options:NSRegularExpressionCaseInsensitive error:nil];
    NSUInteger regExMatches = [regEx numberOfMatchesInString:emailString options:0 range:NSMakeRange(0, [emailString length])];
    if (regExMatches == 0) {
        return NO;
    }
    else
        return YES;
}

static inline BOOL CheckDeviceiPhoneFiveOriPhoneFour() {
    
    CGSize result = [[UIScreen mainScreen] bounds].size;
    if(result.height == 480) {
        return YES;
    }
    if(result.height == 568) {
        return NO;
    }
    return NO;
}

static inline BOOL checkSystemVersion(){

    if([[[UIDevice currentDevice] systemVersion] floatValue] >=8.0){
    
        return YES;
    }
    return NO;
}

/**
 *
 * Convert date from one formate to another formate
 * @author Deepak Kumar.
 *
 **/
static inline NSString* ConvertDateStringFormat(NSString*dateStr) {
    
    NSArray *arrDateTime = [dateStr componentsSeparatedByString:@" "];
    NSString *tempStr = [NSString stringWithFormat:@"%@",[arrDateTime objectAtIndex:0]];
    
    NSDateFormatter *formatter1 = [[NSDateFormatter alloc] init];
    formatter1.dateFormat = @"yyyy-MM-dd"; // date format
    
    NSDate *dt=[formatter1 dateFromString:tempStr];
    
    NSDateFormatter *formatter = [[NSDateFormatter alloc] init];
    formatter.dateFormat = @"MMM dd, yyyy"; // date format
    
    NSString *dateStrFinal = [formatter stringFromDate:dt];
    
    return dateStrFinal;
}

/**
 *
 * Convert date from one formate to another formate
 * @author Deepak Kumar.
 *
 **/
static inline NSString* ConvertDateStringFormatWebCast(NSString*dateStr) {
    
    NSArray *arrDateTime = [dateStr componentsSeparatedByString:@" "];
    NSString *tempStr = [NSString stringWithFormat:@"%@",[arrDateTime objectAtIndex:0]];
    
    NSDateFormatter *formatter1 = [[NSDateFormatter alloc] init];
    formatter1.dateFormat = @"yyyy-MM-dd"; // date format
    
    NSDate *dt=[formatter1 dateFromString:tempStr];
    
    NSDateFormatter *formatter = [[NSDateFormatter alloc] init];
    formatter.dateFormat = @"MMM dd, yyyy"; // date format
    
    NSString *dateStrFinal = [formatter stringFromDate:dt];
    
    NSDateFormatter *formatterWebCast=[[NSDateFormatter alloc]init];
    [formatterWebCast setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
    [formatterWebCast setTimeZone:[NSTimeZone timeZoneForSecondsFromGMT:(0)]];
    NSDate *dateTime =[formatterWebCast dateFromString:dateStr];
    
    NSDateFormatter *dateFormatter1 = [[NSDateFormatter alloc] init];
    [dateFormatter1 setDateFormat:@"hh:mm a"];
    [dateFormatter1 setTimeZone:[NSTimeZone timeZoneForSecondsFromGMT:(0)]];
    NSString *formattedDateString = [dateFormatter1 stringFromDate:dateTime];
    formattedDateString = [NSString stringWithFormat:@" @ %@",formattedDateString];
    
    dateStrFinal = [dateStrFinal stringByAppendingString:formattedDateString];
    
    return dateStrFinal;
}

/**
 *
 * Convert date from one formate to another formate With Time
 * @author Deepak Kumar.
 *
 **/
static inline NSString* ConvertDateStringFormatWithTime(NSString*dateStr) {
    
    NSDateFormatter *formatter1 = [[NSDateFormatter alloc] init];
    formatter1.dateFormat = @"yyyy-MM-dd HH:mm:ss"; // date format
    
    NSDate *dt=[formatter1 dateFromString:dateStr];
    
    NSDateFormatter *formatter = [[NSDateFormatter alloc] init];
    formatter.dateFormat = @"MMM dd, yyyy HH:mm a"; // date format
    
    NSString *dateStrFinal = [formatter stringFromDate:dt];
    
    return dateStrFinal;
}

//For check only numeric value.
static inline BOOL isNumericKeyWord(NSString*inputString){
    BOOL isValid = NO;
    NSCharacterSet *alphaNumbersSet = [NSCharacterSet decimalDigitCharacterSet];
    NSCharacterSet *stringSet = [NSCharacterSet characterSetWithCharactersInString:inputString];
    isValid = [alphaNumbersSet isSupersetOfSet:stringSet];
    return isValid;
}

// check the string is blank or null *************************
static inline BOOL CheckStringForNull(NSString*str) {
    if (str == nil ||
        [str isEqual:[NSNull null]] ||
        [str isEqualToString:@"<null>"] ||
        [str isEqualToString:@"(null)"] ||
        [str isEqualToString:@" (null)"] ||
        [str isEqualToString:@"(null) "] ||
        [str isEqualToString:@" (null) "] ||
        [str isEqualToString:@""] ||
        [str isEqualToString:@"(\n)"] ) {
        return YES;
    }else {
        return NO;
    }
}
//Remove orientation information from UIImage
@interface UIImage (fixOrientation)

- (UIImage *)fixOrientation;

@end

@implementation UIImage (fixOrientation)

- (UIImage *)fixOrientation {
    
    // No-op if the orientation is already correct
    if (self.imageOrientation == UIImageOrientationUp) return self;
    
    // We need to calculate the proper transformation to make the image upright.
    // We do it in 2 steps: Rotate if Left/Right/Down, and then flip if Mirrored.
    CGAffineTransform transform = CGAffineTransformIdentity;
    
    switch (self.imageOrientation) {
        case UIImageOrientationDown:
        case UIImageOrientationDownMirrored:
            transform = CGAffineTransformTranslate(transform, self.size.width, self.size.height);
            transform = CGAffineTransformRotate(transform, M_PI);
            break;
            
        case UIImageOrientationLeft:
        case UIImageOrientationLeftMirrored:
            transform = CGAffineTransformTranslate(transform, self.size.width, 0);
            transform = CGAffineTransformRotate(transform, M_PI_2);
            break;
            
        case UIImageOrientationRight:
        case UIImageOrientationRightMirrored:
            transform = CGAffineTransformTranslate(transform, 0, self.size.height);
            transform = CGAffineTransformRotate(transform, -M_PI_2);
            break;
        case UIImageOrientationUp:
        case UIImageOrientationUpMirrored:
            break;
    }
    switch (self.imageOrientation) {
        case UIImageOrientationUpMirrored:
        case UIImageOrientationDownMirrored:
            transform = CGAffineTransformTranslate(transform, self.size.width, 0);
            transform = CGAffineTransformScale(transform, -1, 1);
            break;
            
        case UIImageOrientationLeftMirrored:
        case UIImageOrientationRightMirrored:
            transform = CGAffineTransformTranslate(transform, self.size.height, 0);
            transform = CGAffineTransformScale(transform, -1, 1);
            break;
        case UIImageOrientationUp:
        case UIImageOrientationDown:
        case UIImageOrientationLeft:
        case UIImageOrientationRight:
            break;
    }
    
    // Now we draw the underlying CGImage into a new context, applying the transform
    // calculated above.
    CGContextRef ctx = CGBitmapContextCreate(NULL, self.size.width, self.size.height,
                                             CGImageGetBitsPerComponent(self.CGImage), 0,
                                             CGImageGetColorSpace(self.CGImage),
                                             CGImageGetBitmapInfo(self.CGImage));
    CGContextConcatCTM(ctx, transform);
    switch (self.imageOrientation) {
        case UIImageOrientationLeft:
        case UIImageOrientationLeftMirrored:
        case UIImageOrientationRight:
        case UIImageOrientationRightMirrored:
            // Grr...
            CGContextDrawImage(ctx, CGRectMake(0,0,self.size.height,self.size.width), self.CGImage);
            break;
            
        default:
            CGContextDrawImage(ctx, CGRectMake(0,0,self.size.width,self.size.height), self.CGImage);
            break;
    }
    
    // And now we just create a new UIImage from the drawing context
    CGImageRef cgimg = CGBitmapContextCreateImage(ctx);
    UIImage *img = [UIImage imageWithCGImage:cgimg];
    CGContextRelease(ctx);
    CGImageRelease(cgimg);
    return img;
}

@end


#endif

