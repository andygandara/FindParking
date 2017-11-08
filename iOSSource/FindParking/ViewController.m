//
//  ViewController.m
//  FindParking
//
//  Created by Maozhenyu on 17/3/25.
//  Copyright © 2017年 Zhener-Maozhenyu. All rights reserved.
//

#import "ViewController.h"
#import "AppDelegate.h"
@interface ViewController ()

@end

@implementation ViewController
void MessageBoxShow(NSString* text,NSString* title)
{
    [[[CustomAlertView alloc] initWithTitle:title message:text delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil] RunModal];
}

-(void)touchesBegan:(NSSet *)touches withEvent:(UIEvent *)event{
    [Password_Text resignFirstResponder];
    [Email_Text resignFirstResponder];
}
- (BOOL)textFieldShouldReturn:(UITextField*)textField {
    BOOL retValue = NO;
    // see if we're on the username or password fields
    if (textField == Email_Text)//当是 “手机号码”输入框时
    {
        [Password_Text becomeFirstResponder];// “会员密码”输入框 作为 键盘的第一 响应者，光标 进入此输入
    }
    else if(textField==Password_Text)
    {
        [self LoginButton:textField];
        [Password_Text becomeFirstResponder];// “会员密码”输入框 作为 键盘的第一 响应者，光标 进入此输入
    }
    return retValue;
}
-(void)GotoMain
{
    dispatch_async(dispatch_get_global_queue(DISPATCH_QUEUE_PRIORITY_DEFAULT, 0), ^{
    [NSThread sleepForTimeInterval:1.0f];
    
    InMain(
           UIStoryboard *s = [UIStoryboard storyboardWithName:@"MainView" bundle:nil];
           GB.nav=[s instantiateViewControllerWithIdentifier:@"MainViewRootH"];
           [self presentViewController:GB.nav animated:YES completion:nil];)
    });
}


- (void)viewDidLoad {
    NSDictionary* result=[GB POST:@"appapi/testlogin" postdata:SF(@"usersession=%@",GB.Usersession)];
    if([result[@"errno"] intValue]==1900)
    {
        GB.plate=result[@"platenumber"];
        [self GotoMain];
        return;
    }
    [super viewDidLoad];
}


- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}

-(IBAction)CancelButton:(id)sender
{
    CustomAlertView *alertView = [[CustomAlertView alloc]initWithTitle:@"Confirm Exit?" message:@"Are you sure?" delegate:self cancelButtonTitle:@"Yes" otherButtonTitles:@"No", nil];
    if([alertView RunModal]==0)
    {
        [UIView animateWithDuration:0.5f animations:^{
            self.view.alpha= 0;
            self.view.frame = CGRectMake(0, self.view.bounds.size.width, self.view.frame.size.width, 0);
            
        } completion:^(BOOL finished) {
            exit(0);
        }];
        
    }

    }

-(IBAction)LoginButton:(id)sender
{
    if([Email_Text.text isEqual:@""] || [Password_Text.text isEqual:@""] )
    {
        MessageBoxShow(@"You left blanks",@"Fail");
        return;
    }
    NSDictionary* result=[GB POST:@"appapi/userlogin" postdata:SF(@"usersession=%@&email=%@&password=%@",GB.Usersession,Email_Text.text,Password_Text.text)];
    int error=[result[@"errno"] intValue];
    if(error==0)
    {
        GB.Usersession=result[@"sessionid"];
        GB.plate=result[@"platenumber"];
        GB.email=Email_Text.text;
        NSUserDefaults *userDefaults = [NSUserDefaults standardUserDefaults];
        [userDefaults setObject:GB.Usersession forKey:@"UserSession" ];
        [userDefaults setObject:GB.email forKey:@"UEmail" ];
        [[NSUserDefaults standardUserDefaults] synchronize];
        [self GotoMain];
        
    }else{
        MessageBoxShow(result[@"errmsg"],@"Authorization Failed");
    }

}

@end

@implementation RegUserController
-(void)touchesBegan:(NSSet *)touches withEvent:(UIEvent *)event{
    [Password_Text resignFirstResponder];
    [Email_Text resignFirstResponder];
    [PlateNB_Text resignFirstResponder];
    [PlateST_Text resignFirstResponder];
}
- (BOOL)textFieldShouldReturn:(UITextField*)textField {
    BOOL retValue = NO;
    // see if we're on the username or password fields
    if (textField == Email_Text)//当是 “手机号码”输入框时
    {
        [Password_Text becomeFirstResponder];// “会员密码”输入框 作为 键盘的第一 响应者，光标 进入此输入
    }
    else if (textField == Password_Text)//当是 “手机号码”输入框时
    {
        [PlateST_Text becomeFirstResponder];// “会员密码”输入框 作为 键盘的第一 响应者，光标 进入此输入
    }
    else if(textField==PlateST_Text)
    {
        [PlateNB_Text becomeFirstResponder];
    }
    else{
        [PlateNB_Text resignFirstResponder];
        [self RegButton:textField];
    }
    return retValue;
}


-(IBAction)CancelReg:(id)sender
{
    [self dismissViewControllerAnimated:true completion:nil];
}

-(IBAction)RegButton:(id)sender
{
    if([Email_Text.text isEqual:@""] || [Password_Text.text isEqual:@""] ||[PlateNB_Text.text isEqual:@""]||[PlateST_Text.text isEqual:@""])
    {
        MessageBoxShow(@"You left blanks",@"Fail");
        return;
    }//34cvor0neehi13h180l4t4jtk6
    NSDictionary* result=[GB POST:@"appapi/userreg" postdata:SF(@"usersession=%@&email=%@&password=%@&platestate=%@&platenumber=%@",GB.Usersession,Email_Text.text,Password_Text.text,PlateST_Text.text,PlateNB_Text.text)];
    int error=[result[@"errno"] intValue];
    if(error==0)
    {
        MessageBoxShow(@"Success! You can now Login!",@"Success");
        GB.Usersession=result[@"sessionid"];
        NSUserDefaults *userDefaults = [NSUserDefaults standardUserDefaults];
        [userDefaults setObject:GB.Usersession forKey:@"UserSession" ];
        [[NSUserDefaults standardUserDefaults] synchronize];
        [self dismissViewControllerAnimated:true completion:nil];
        
    }else{
        MessageBoxShow(result[@"errmsg"],@"Failed");
    }
}
@end
