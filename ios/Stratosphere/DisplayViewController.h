//
//  DisplayViewController.h
//  Stratosphere
//
//  Created by Yahui Han on 12/3/13.
//  Copyright (c) 2013 stratos. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "Article.h"
#import "AppDelegate.h"

@interface DisplayViewController : UIViewController

@property (nonatomic, retain) Article *article;
@property (weak, nonatomic) IBOutlet UIWebView *webView;
@property (weak, nonatomic) IBOutlet UILabel *notReadableLabel;
@property (weak, nonatomic) IBOutlet UIButton *notReadableButton;

- (IBAction)viewOriginal:(id)sender;

@end
