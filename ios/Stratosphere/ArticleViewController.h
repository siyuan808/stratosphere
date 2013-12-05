//
//  ArticleViewController.h
//  Stratosphere
//
//  Created by Yahui Han on 12/3/13.
//  Copyright (c) 2013 stratos. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ArticleViewController : UIViewController <UITableViewDelegate, UITableViewDataSource, NSURLConnectionDelegate>

@property (strong, nonatomic) IBOutlet UITableView *articleTable;

@end
