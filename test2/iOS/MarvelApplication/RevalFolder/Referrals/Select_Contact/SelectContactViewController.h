//
//  SelectContactViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/31/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>
@protocol selectContactDelegate <NSObject>

-(void)selectedContact:(NSDictionary *)dict;

@end
@interface SelectContactViewController : UIViewController {

    __weak IBOutlet UIButton *btnDone;
    NSArray *recordArray;
    id <selectContactDelegate> DelegateContact;
    NSMutableArray *selectedArray;
    int index;
    NSMutableArray *totalRecordArray;
    __weak IBOutlet UIView *viewNoRecord;
    __weak IBOutlet UILabel *lblNoRecord;
}
@property (nonatomic, strong) UIImage *selectedImage;
@property (nonatomic, strong) UIImage *unSelectedImage;
@property (nonatomic , strong) id <selectContactDelegate> DelegateContact;
@property (weak, nonatomic) IBOutlet UITableView *tableView;
@property (weak, nonatomic) IBOutlet UISearchBar *searchBar;
@property (nonatomic , strong) NSDictionary *dictContact;

@end
