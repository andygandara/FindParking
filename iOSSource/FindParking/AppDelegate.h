//
//  AppDelegate.h
//  FindParking
//
//  Created by Maozhenyu on 17/3/25.
//  Copyright © 2017年 Zhener-Maozhenyu. All rights reserved.
//

#import <UIKit/UIKit.h>
#define InMain(z) (dispatch_async(dispatch_get_main_queue(), ^{z}));
#define SF(...)([NSString stringWithFormat:__VA_ARGS__])

@interface CustomAlertView : UIAlertView
//@property (assign) NSString* mode;
//@property (assign) NSString* data;
//@property (assign) anyID clientID;
//@property (assign) uint64 channelID;
-(NSString*)GetText:(NSInteger)i;
-(NSInteger)RunModal;
@end

@interface AppDelegate : UIResponder <UIApplicationDelegate>

-(NSDictionary*)POST:(NSString*) urladd postdata:(NSString*) data;
@property (strong, nonatomic) UIWindow *window;
@property (strong,nonatomic) NSString* Usersession;
@property (strong,nonatomic) NSString* email;
@property (strong,nonatomic) NSString* plate;
@property UINavigationController *nav;
@end
extern AppDelegate *GB;
