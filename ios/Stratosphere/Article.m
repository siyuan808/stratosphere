//
//  Article.m
//  Stratosphere
//
//  Created by Yahui Han on 12/3/13.
//  Copyright (c) 2013 stratos. All rights reserved.
//

#import "Article.h"

@implementation Article

- (id) initWithDictionary:(NSDictionary *)data {
    self = [super init];
    if (self) {
        self.urlid = [[data objectForKey:@"urlid"] intValue];
        self.url = [data objectForKey:@"url"];
        self.is_readable = [[data objectForKey:@"is_readable"] boolValue];
        self.title = [data objectForKey:@"title"];
    }
    return self;
}

@end
