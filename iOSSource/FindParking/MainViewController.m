//
//  MainViewController.m
//  FindParking
//
//  Created by Maozhenyu on 17/3/25.
//  Copyright © 2017年 Zhener-Maozhenyu. All rights reserved.
//

#import "MainViewController.h"
#include "MapViewController.h"
@interface MainViewController ()

@end
@implementation OptionController

-(IBAction)Exit:(id)sender
{
    [UIView animateWithDuration:0.5f animations:^{
        self.view.alpha= 0;
        self.view.frame = CGRectMake(0, self.view.bounds.size.width, self.view.frame.size.width, 0);
        
    } completion:^(BOOL finished) {
        exit(0);
    }];
}
-(IBAction)ExitLogout:(id)sender
{
    GB.Usersession=@"";
    NSUserDefaults *userDefaults = [NSUserDefaults standardUserDefaults];
    [userDefaults setObject:@"" forKey:@"UserSession" ];
    [[NSUserDefaults standardUserDefaults] synchronize];
    [self dismissViewControllerAnimated:true completion:nil];
}
- (void)viewDidLoad {
    Email.text=GB.email;
    plate.text=GB.plate;
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}
@end
@implementation MainViewController
- (BOOL)webView:(UIWebView *)webView shouldStartLoadWithRequest:(NSURLRequest *)request navigationType:(UIWebViewNavigationType)navigationType
{
    NSURL *url = [request URL];
    if([[url scheme] isEqualToString:@"parking"]) {
        //处理JavaScript和Objective-C交互
        if([[url host] isEqualToString:@"gotomap"])
        {
            MapViewController* map=[[MapViewController alloc]init];
            [self.navigationController showViewController:map sender:nil];
        }
        return NO;
    }
    return YES;
}
-(IBAction)returnback:(id)sender
{
    [webView goBack];
}
- (void)viewDidLoad {
    self.navigationItem.backBarButtonItem = [[UIBarButtonItem alloc] initWithTitle:@"Main" style:UIBarButtonItemStylePlain target:nil action:nil];
    
    NSURL* url = [NSURL URLWithString:@"http://192.168.0.2:801/appapi/avalparking"];//创建URL
    NSURLRequest* request = [NSURLRequest requestWithURL:url];//创建NSURLRequest
    [webView loadRequest:request];//加载
    
    
    [super viewDidLoad];
    // Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
