//
//  ArticleViewController.m
//  Stratosphere
//
//  Created by Yahui Han on 12/3/13.
//  Copyright (c) 2013 stratos. All rights reserved.
//

#import "ViewController.h"
#import "AppDelegate.h"
#import "ArticleViewController.h"
#import "Article.h"
#import "DisplayViewController.h"

@interface ArticleViewController ()
@property (nonatomic, strong) NSMutableArray *articles;

// get data from server
- (void)getArticleRemotely;

- (void)logout;
@end

@implementation ArticleViewController

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
    self.navigationItem.title = @"Stratosphere";
    
    UIBarButtonItem *barButton = [[UIBarButtonItem alloc] initWithTitle:@"logout" style:UIBarButtonItemStyleBordered target:self action:@selector(logout)];
    self.navigationItem.leftBarButtonItem = barButton;
        
    self.articles = [[NSMutableArray alloc] init];
    [self getArticleRemotely];
	// Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void) getArticleRemotely {
    AppDelegate *appDel =(AppDelegate *) [[UIApplication sharedApplication] delegate];
    NSString *url =[NSString stringWithFormat:@"http://ec2-67-202-55-42.compute-1.amazonaws.com/mobile/user.php?userid=%d", appDel.userid];
    NSLog(@"%@",url);
    NSMutableURLRequest *req = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:url]];
    [req setHTTPMethod:@"GET"];
    NSURLConnection *connection = [[NSURLConnection alloc] initWithRequest:req delegate:self];
    [connection start];
}

- (void)logout {
    AppDelegate *appDel =(AppDelegate *) [[UIApplication sharedApplication] delegate];
    [appDel clearInfo];
    appDel.window.rootViewController =  [self.storyboard instantiateViewControllerWithIdentifier:@"login_view_controller"];
}

#pragma - Tableview delegate --

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)sectionIndex
{
    return self.articles.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSString *cellIdentifier = @"ArticleCell";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:cellIdentifier];
    if (cell == nil) {
        cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:cellIdentifier];
    }
    
    cell.textLabel.text = [(Article * )[self.articles objectAtIndex:indexPath.row] title];
    
    return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    Article *article = [self.articles objectAtIndex:indexPath.row];
    NSLog(@"urlid: %d, isreadable: %d, url: %@", article.urlid, article.is_readable, article.url);
    DisplayViewController *displayVCL = (DisplayViewController *) [self.storyboard instantiateViewControllerWithIdentifier:@"display_view_controller"];
    displayVCL.article = article;
    [self.navigationController pushViewController:displayVCL animated:YES];
}

#pragma - NSURLConnection delegate -
-(void) connection:(NSURLConnection *)connection didReceiveData:(NSData *)data {
    NSError *error;
//    NSLog(@"receive data: %@", [NSString stringWithUTF8String:[data bytes]]);
    NSArray *jsonArray = [NSJSONSerialization JSONObjectWithData:data options:kNilOptions error:&error];
    if (error != nil) {
        NSLog(@"json parse error!");
    } else {
//        NSLog(@"array size: %d", [jsonArray count]);
        for (NSDictionary *dic in jsonArray) {
            Article *article = [[Article alloc] initWithDictionary:dic];
            [self.articles addObject:article];
        }
    }
    [self.articleTable reloadData];
}
@end
