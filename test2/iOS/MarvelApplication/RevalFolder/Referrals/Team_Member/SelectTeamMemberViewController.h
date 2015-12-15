//
//  SelectTeamMemberViewController.h
//  FoxHoprApplication
//
//  Created by Deepak Chauhan on 7/31/15.
//  Copyright (c) 2015 Deepak Chauhan. All rights reserved.
//

#import <UIKit/UIKit.h>

@protocol selectTeamMemberDelegate <NSObject>

-(void)selectedTeamMember:(NSMutableArray *)array;

@end

@interface SelectTeamMemberViewController : UIViewController {

     __weak IBOutlet UILabel *lblTopTitle;
    __weak IBOutlet UIView *noResultView;
    __weak IBOutlet UILabel *lblNoRecord;
    
    __weak IBOutlet UIButton *btnDone;
    __weak IBOutlet UITableView *tableView;
    __weak IBOutlet UIButton *btnSelectAll;
    __weak IBOutlet UISearchBar *searchBar;
    __weak IBOutlet UIView *selectAllView;
    
    NSArray *recordArray;
    NSMutableArray *selectedRowForDeleteArray;
    NSMutableArray *totalRecordArray;
    id <selectTeamMemberDelegate> DelegateMember;
    int indexMatchOne;
    int indexMatchTwo;
}
@property (nonatomic , strong) NSString *titleString;
@property (nonatomic , strong) NSMutableArray *preSelectArray;
@property (nonatomic , strong) id <selectTeamMemberDelegate> DelegateMember;
@property (nonatomic, strong) UIImage *selectedImage;
@property (nonatomic, strong) UIImage *unSelectedImage;

@end
