//
//  Article.h
//  Stratosphere
//
//  Created by Yahui Han on 12/3/13.
//  Copyright (c) 2013 stratos. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Article : NSObject

@property (nonatomic) int urlid;
@property (nonatomic, retain) NSString *url;
@property (nonatomic) Boolean is_readable;
@property (nonatomic, retain) NSString *title;

- (id) initWithDictionary: (NSDictionary *) data;
@end
