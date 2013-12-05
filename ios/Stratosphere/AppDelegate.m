//
//  AppDelegate.m
//  Stratosphere
//
//  Created by Yahui Han on 11/26/13.
//  Copyright (c) 2013 stratos. All rights reserved.
//

#import "AppDelegate.h"
#import "Reachability.h"
#import <SystemConfiguration/SystemConfiguration.h>
#import <unistd.h>

@implementation AppDelegate
@synthesize internetConnected, username, password, userid;

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions
{
    // Override point for customization after application launch.
    [NSThread sleepForTimeInterval:1.0];
    [self.window makeKeyAndVisible];

    Reachability *reachability = [Reachability reachabilityForInternetConnection];
    NetworkStatus networkStatus = [reachability currentReachabilityStatus];
    internetConnected = !(networkStatus == NotReachable);
    
    NSString *userInfoJsonFilePath = [[NSBundle mainBundle] pathForResource:@"userinfo" ofType:@"json"];
    NSData *data = [NSData dataWithContentsOfFile:userInfoJsonFilePath];
    NSDictionary *user = [NSJSONSerialization JSONObjectWithData:data options:kNilOptions error:NULL];
    self.username = [user objectForKey:@"username"];
    self.password = [user objectForKey:@"password"];
    self.userid = [[user objectForKey:@"userid"] intValue];
    NSLog(@"get from json: username: %@, password: %@, userid: %d", self.username, self.password, self.userid);
    return YES;
}
							
- (void)applicationWillResignActive:(UIApplication *)application
{
    // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
    // Use this method to pause ongoing tasks, disable timers, and throttle down OpenGL ES frame rates. Games should use this method to pause the game.
}

- (void)applicationDidEnterBackground:(UIApplication *)application
{
    // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later. 
    // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
    //save username and password into file
}

- (void)applicationWillEnterForeground:(UIApplication *)application
{
    // Called as part of the transition from the background to the inactive state; here you can undo many of the changes made on entering the background.
   
}

- (void)applicationDidBecomeActive:(UIApplication *)application
{
    // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
}

- (void)applicationWillTerminate:(UIApplication *)application
{
    // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
}

- (void) clearInfo {
    self.userid = -1;
    self.username = nil;
    self.password = nil;
    [self writeToFile];
}

- (void) writeToFile {

    NSString *filepath = [[NSBundle mainBundle] pathForResource:@"userinfo" ofType:@"json"];
    NSFileHandle *filehandle = [NSFileHandle fileHandleForWritingAtPath:filepath];
    NSString *info = [NSString stringWithFormat:@"{\"username\":\"%@\", \"password\":\"%@\", \"userid\":%d}", username, password, userid];
    if (userid == -1) {
        info = @"";
    }
    [filehandle writeData:[info dataUsingEncoding:NSUTF8StringEncoding]];
    [filehandle closeFile];
    NSLog(@"%@", info);
    
    NSFileHandle *rh = [NSFileHandle fileHandleForReadingAtPath:filepath];
    if (rh == nil) {
        NSLog(@"open error");
    }
    NSString *read =  [[NSString alloc] initWithData: [rh readDataToEndOfFile]
                                            encoding: NSUTF8StringEncoding];
    [rh closeFile];
    NSLog(@"Read back: %@", read);
}

@end
