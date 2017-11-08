//
//  ViewController.h
//  FindParking
//
//  Created by Maozhenyu on 17/3/25.
//  Copyright © 2017年 Zhener-Maozhenyu. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController
{
    
    IBOutlet UITextView* LogTextView;
    IBOutlet UITextField* Email_Text;
    IBOutlet UITextField* Password_Text;
    IBOutlet UIButton* LoginButton;
}
@end
@interface RegUserController : UIViewController<UITextFieldDelegate>
{
    
    IBOutlet UITextField* Email_Text;
    IBOutlet UITextField* Password_Text;
    IBOutlet UITextField* PlateST_Text;
    IBOutlet UITextField* PlateNB_Text;
}
@end
