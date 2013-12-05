//
//  DisplayViewController.m
//  Stratosphere
//
//  Created by Yahui Han on 12/3/13.
//  Copyright (c) 2013 stratos. All rights reserved.
//

#import "DisplayViewController.h"

@interface DisplayViewController ()

@end

@implementation DisplayViewController
@synthesize article;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
	// Do any additional setup after loading the view.
    self.navigationItem.title = self.article.title;
    UIBarButtonItem *barButton = [[UIBarButtonItem alloc] initWithTitle:@"Back" style:UIBarButtonItemStyleBordered target:self action:@selector(moveBack)];
    self.navigationItem.leftBarButtonItem = barButton;
    
    if (article.is_readable) {
        AppDelegate *appDel =(AppDelegate *) [[UIApplication sharedApplication] delegate];
        NSURL *url = [NSURL URLWithString:[NSString stringWithFormat:@"https://s3.amazonaws.com/ec2-67-202-55-42-stratos-userid-%d/%d.html", appDel.userid, article.urlid]];
        NSURLRequest *req = [NSURLRequest requestWithURL:url];
        [self.webView loadRequest:req];
    } else {
        [self.webView setHidden:YES];
        [self.notReadableButton setHidden:NO];
        [self.notReadableLabel setHidden:NO];
    }
}

- (void)moveBack {
    [self.navigationController popViewControllerAnimated:YES];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (IBAction)viewOriginal:(id)sender {
     [[UIApplication sharedApplication] openURL:[NSURL URLWithString:article.url]];
}
@end
