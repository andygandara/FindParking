//
//  MainViewController.h
//  FindParking
//
//  Created by Maozhenyu on 17/3/25.
//  Copyright © 2017年 Zhener-Maozhenyu. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "AppDelegate.h"
@interface OptionController : UITableViewController
{
   IBOutlet UILabel* Email;
    IBOutlet UITextField* plate;
}
@end
@interface MainViewController : UIViewController<UIWebViewDelegate>
{
    IBOutlet UIWebView* webView;
}
@end
