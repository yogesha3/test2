/**
 * Copyright (c) 2011 Muh Hon Cheng
 * Created by honcheng on 28/4/11.
 * 
 * Permission is hereby granted, free of charge, to any person obtaining 
 * a copy of this software and associated documentation files (the 
 * "Software"), to deal in the Software without restriction, including 
 * without limitation the rights to use, copy, modify, merge, publish, 
 * distribute, sublicense, and/or sell copies of the Software, and to 
 * permit persons to whom the Software is furnished to do so, subject 
 * to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be 
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT 
 * WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR 
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT 
 * SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE 
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, 
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR 
 * IN CONNECTION WITH THE SOFTWARE OR 
 * THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 * @author 		Muh Hon Cheng <honcheng@gmail.com>
 * @copyright	2011	Muh Hon Cheng
 * @version
 * 
 */

#import <UIKit/UIKit.h>
#import "PCPieChart.h"

@interface PCPieComponent : NSObject
@property (nonatomic, assign) float value, startDeg, endDeg;
@property (nonatomic, strong) UIColor *colour;
@property (nonatomic, copy) NSString *title;
- (id)initWithTitle:(NSString*)title value:(float)value;
+ (id)pieComponentWithTitle:(NSString*)title value:(float)value;
@end

#define PCColorGreen [UIColor colorWithRed:69.0/255.0 green:204.0/255.0 blue:51.0/255.0 alpha:1.0]
#define PCColorRed [UIColor colorWithRed:248.0/255.0 green:39/255.0 blue:37/255.0 alpha:1.0]
#define PCColorOrange [UIColor colorWithRed:251.0/255.0 green:153.0/255.0 blue:36.0/255.0 alpha:1.0]
#define PCColorYellow [UIColor colorWithRed:254.0/255.0 green:246.0/255.0 blue:50.0/255.0 alpha:1.0]
#define PCColorGray [UIColor colorWithRed:148.0/255.0 green:148.0/255.0 blue:148.0/255.0 alpha:1.0]
#define PCColorBlack [UIColor colorWithRed:0.0/255.0 green:0.0/255.0 blue:0.0/255.0 alpha:1.0]
#define PCColorSkyBlue [UIColor colorWithRed:51.0/255.0 green:204.0/255.0 blue:253.0/255.0 alpha:1.0]
#define PCColorBlue [UIColor colorWithRed:23.0/255.0 green:79.0/255.0 blue:253.0/255.0 alpha:1.0]
#define PCColorLightGray [UIColor colorWithRed:238.0/255.0 green:238.0/255.0 blue:238.0/255.0 alpha:1.0]

#define PCColorDefault [UIColor colorWithRed:0.5 green:0.5 blue:0.5 alpha:1.0]

@interface PCPieChart : UIView
@property (nonatomic, assign) int diameter;
@property (nonatomic, strong) NSMutableArray *components;
@property (nonatomic, strong) UIFont *titleFont, *percentageFont;
@property (nonatomic, assign) BOOL showArrow, sameColorLabel;
@property (nonatomic, assign, getter = hasOutline) BOOL outline;
@end
