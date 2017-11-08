//
//  AppDelegate.m
//  FindParking
//
//  Created by Maozhenyu on 17/3/25.
//  Copyright © 2017年 Zhener-Maozhenyu. All rights reserved.
//

#import "AppDelegate.h"
AppDelegate* GB;
@implementation CustomAlertView
{
    BOOL FINISH;
    NSInteger _buttonindex;
}

-(NSString*)GetText:(NSInteger)i
{
    return [self textFieldAtIndex:i].text;
}
- (void) alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex{
    FINISH=true;
    _buttonindex=buttonIndex;
}
-(NSInteger)RunModal
{
    self.delegate=self;
    if([NSThread isMainThread] )
        [self show];
    else
        InMain([self show];)
        while(!FINISH)
        {
            [[NSRunLoop currentRunLoop] runMode:NSDefaultRunLoopMode beforeDate:[NSDate distantFuture]];
        }
    return _buttonindex;
}
@end


@interface AppDelegate ()

@end

@implementation AppDelegate

-(NSDictionary*)POST:(NSString*) urladd postdata:(NSString*) pdata
{
    NSURL *url = [NSURL URLWithString:[[NSString alloc] initWithFormat:@"http://192.168.0.2:801/%@",urladd]];
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:url];
   // [request addValue:Cookie forHTTPHeaderField:@"Cookie"];
    
    [request setHTTPMethod:@"POST"];
    [request setHTTPBody:[pdata dataUsingEncoding:NSUTF8StringEncoding]];
    //[request addValue:rfr forHTTPHeaderField:@"Referer"];
    
    
    NSURLResponse *response;
    NSError *error;
    NSData *data = [NSURLConnection sendSynchronousRequest:request returningResponse:&response error:&error];
    
    NSDictionary *rspheaders = [(NSHTTPURLResponse *)response allHeaderFields];
    if (error == nil) {
        NSString* aStr;
        aStr = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
        //aStr=[aStr substringWithRange:[result rangeAtIndex:1]];
        //NSData *data= [aStr dataUsingEncoding:NSUTF8StringEncoding];
        NSDictionary *callback = [NSJSONSerialization JSONObjectWithData:data options:NSJSONReadingMutableLeaves error:&error];
        
        return callback;
    }
    return nil;
    
   /* NSURLSession *session = [NSURLSession sharedSession];
    NSURLSessionDataTask *dataTask = [session dataTaskWithRequest:request completionHandler:^(NSData * _Nullable data, NSURLResponse * _Nullable response, NSError * _Nullable error) {
        NSLog(@"URL:%@ StatusCode:%ld",url,(long)((NSHTTPURLResponse *)response).statusCode);
           }];
    [dataTask resume];*/
}

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {
    GB=self;
    NSUserDefaults *userDefaults = [NSUserDefaults standardUserDefaults];
    NSString* object = [userDefaults stringForKey:@"UserSession"];
    GB.email=[userDefaults stringForKey:@"UEmail"];
    if([object length]!=0)
    {
        GB.Usersession=object;
    }
    else
        GB.Usersession=@"";
    
    // Override point for customization after application launch.
    return YES;
}


- (void)applicationWillResignActive:(UIApplication *)application {
    // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
    // Use this method to pause ongoing tasks, disable timers, and invalidate graphics rendering callbacks. Games should use this method to pause the game.
}


- (void)applicationDidEnterBackground:(UIApplication *)application {
    // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later.
    // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
}


- (void)applicationWillEnterForeground:(UIApplication *)application {
    // Called as part of the transition from the background to the active state; here you can undo many of the changes made on entering the background.
}


- (void)applicationDidBecomeActive:(UIApplication *)application {
    // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
}


- (void)applicationWillTerminate:(UIApplication *)application {
    // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
}


@end
