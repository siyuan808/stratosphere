//
//  ViewController.h
//  Stratosphere
//
//  Created by Yahui Han on 11/26/13.
//  Copyright (c) 2013 stratos. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController <NSURLConnectionDelegate>

@property (weak, nonatomic) IBOutlet UILabel *errorLabel;
@property (weak, nonatomic) IBOutlet UITextField *userName;
@property (weak, nonatomic) IBOutlet UITextField *password;

- (IBAction)loging:(id)sender;
@end
