//
//  ViewController.m
//  Stratosphere
//
//  Created by Yahui Han on 11/26/13.
//  Copyright (c) 2013 stratos. All rights reserved.
//

#import "ViewController.h"
#import "AppDelegate.h"
#import "ArticleViewController.h"

@interface ViewController ()
-(void) loginWithUsername: (NSString *) username password: (NSString *) password;
-(void) displayNextPage;
@end

@implementation ViewController

- (void)viewDidLoad
{
    [super viewDidLoad];
	// Do any additional setup after loading the view, typically from a nib.
    AppDelegate *appDel =(AppDelegate *) [[UIApplication sharedApplication] delegate];
    if (appDel.username != (id)[NSNull null] && appDel.username.length != 0) {
        if (appDel.internetConnected) {
            // have internect connection
                [self loginWithUsername:appDel.username password:appDel.password];
        } else {
            // no internet connection
            [self displayNextPage];
        }
    }
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (IBAction)loging:(id)sender {
    
    NSString *username = self.userName.text;
    NSString *password = self.password.text;
    [self loginWithUsername:username password:password];
}

- (void)loginWithUsername:(NSString *)username password:(NSString *)password {
    if (username == (id)[NSNull null] || username.length == 0  ||
        password == (id)[NSNull null] || password.length == 0) {
        [self.errorLabel setHidden:NO];
    } else {
        [self.errorLabel setHidden:YES];
        // Create the request.
        NSURL *url = [NSURL URLWithString:@"http://ec2-67-202-55-42.compute-1.amazonaws.com/mobile/login.php"];
        NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:url
                                                               cachePolicy:NSURLRequestUseProtocolCachePolicy
                                                           timeoutInterval:10.0];
        
        // Specify that it will be a POST request
        request.HTTPMethod = @"POST";
        
        // This is how we set header fields
        //        [request setValue:@"application/xml; charset=utf-8" forHTTPHeaderField:@"Content-Type"];
        
        // Convert your data and set your request's HTTPBody property
        NSString *stringData = [NSString stringWithFormat:@"username=%@&password=%@", username, password];
        //        NSLog(@"%@", stringData);
        NSData *requestBodyData = [stringData dataUsingEncoding:NSUTF8StringEncoding];
        [request setHTTPBody:requestBodyData];
        
        NSURLResponse *response;
        NSError *error;
        // send request
        NSData *responseData = [NSURLConnection sendSynchronousRequest:request returningResponse:&response error:&error];
        NSString *responseString = [[NSString alloc] initWithData:responseData encoding:NSUTF8StringEncoding];
        if(!error)
        {
            NSLog(@"%@", responseString);
            if ([responseString intValue] >= 0) {
                NSLog(@"success");
                AppDelegate *appDel =(AppDelegate *) [[UIApplication sharedApplication] delegate];
                appDel.userid = [responseString intValue];
                appDel.username = username;
                appDel.password = password;
                [appDel writeToFile];
                [self displayNextPage];
            } else {
                [self.errorLabel setHidden:NO];
                NSLog(@"unsuccess");
            }
        } else {
            UIAlertView *view = [[UIAlertView alloc] initWithTitle:@"Error" message:@"Connect to server error! please try again!" delegate:self cancelButtonTitle:@"OK" otherButtonTitles: nil];
            [view show];
        }
    }
}

- (void)displayNextPage {
    AppDelegate *appDel =(AppDelegate *) [[UIApplication sharedApplication] delegate];
    ArticleViewController *avc =(ArticleViewController *) [self.storyboard instantiateViewControllerWithIdentifier:@"article_view_controller"];
    UINavigationController *mainNav = [[UINavigationController alloc] initWithRootViewController:avc];
    appDel.window.rootViewController = mainNav;
    //    [self performSegueWithIdentifier:@"Initial-Sliding" sender:nil];
}
@end
