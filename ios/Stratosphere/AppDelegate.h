//
//  AppDelegate.h
//  Stratosphere
//
//  Created by Yahui Han on 11/26/13.
//  Copyright (c) 2013 stratos. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface AppDelegate : UIResponder <UIApplicationDelegate>

@property (nonatomic, strong) NSString *username;
@property (nonatomic, strong) NSString *password;
@property (nonatomic) int userid;
@property (nonatomic) bool internetConnected;
@property (strong, nonatomic) UIWindow *window;

-(void) clearInfo;
- (void) writeToFile;
@end
