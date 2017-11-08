# Andres Gandara

#Libraries
from smbus import SMBus
import RPi.GPIO as GPIO
import time
import requests
import json
import math
import backlight
import screen

url = "http://192.168.0.2:801/deviceapi"
#GPIO Mode (BOARD / BCM)
GPIO.setmode(GPIO.BOARD)

#set GPIO Pins
GPIO_TRIGGER = 38
GPIO_ECHO = 37

#set GPIO direction (IN / OUT)
GPIO.setup(GPIO_TRIGGER, GPIO.OUT)
GPIO.setup(GPIO_ECHO, GPIO.IN)

# init screen+
class Display(object):
    backlight = None
    screen = None

    def __init__(self, bus):
        self.backlight = backlight.Backlight(bus, 0x62)
        self.screen    = screen.Screen(bus, 0x3e)

    def write(self, text):
        self.screen.write(text)

    def color(self, r, g, b):
        self.backlight.set_color(r, g, b)

    def move(self, col, row):
        self.screen.setCursor(col, row)
    def clear(self):
        self.screen.clear()
#end init

def distance():
    # set Trigger to HIGH
    GPIO.output(GPIO_TRIGGER, True)

    #set Trigger after 0.01ms to LOW
    time.sleep(0.00001)
    GPIO.output(GPIO_TRIGGER, False)

    StartTime = time.time()
    StopTime = time.time()

    # save StartTime
    while GPIO.input(GPIO_ECHO) == 0:
        StartTime = time.time()

    # save time of arrival
    while GPIO.input(GPIO_ECHO) == 1:
        StopTime = time.time()

    # time difference between start and arrival
    TimeElapsed = StopTime - StartTime
    # multiply with the sonic speed (34300 cm/s)
    # and divide by 2 to account for there and back distance
    distance = (TimeElapsed * 34300) / 2

    return distance;

def post(v,st):
       data={'posid':1,'platenumber':v,'status':st}
       r = requests.post(url = url, data = data)
       res = r.text
       data=json.loads(res)
       print(data)
       return data
def getPlateNumber(v):
    color= "CA-7TR1111"
    return color
if __name__ == '__main__':
    timecounting =0
    reservedby = ""
    d = Display(SMBus(1))
    try:
        while True:
            dist = distance()
            if dist < 8:
                print("Parking space occupied For %s"%timecounting)
                if timecounting < 2:
                    post('CA-TR7125',1)
                d.clear()
                d.move(5, 0)
                d.write("In Use")
                d.color(255,0,0)
                d.move(0, 1)
                d.write("Period: "+str(time.strftime("%H:%M:%S",time.localtime(timecounting))))
                timecounting+=1
            else:
                if timecounting == 0: # Not a car leaving sit
                    hbdata = post('',0)
                    if hbdata["errno"] == 100:
                        d.clear()
                        d.move(4, 0)
                        d.write("Reserved")
                        d.color(255,255,0)
                        d.move(0, 1)
                        d.write("By: "+hbdata["platenumber"]+"     ")

                    else:
                        d.clear()
                        d.move(0, 0)
                        d.write("["+hbdata["posname"]+"] Available")
                        d.color(0,255,0)
                        d.move(0, 1)
                        d.write("#1 in innovation")  
                else: 
                     print("Parking space open(Car leaving) %s"%timecounting)
                     hbdata = post('CA-TR7125',0)
                     if hbdata["uid"] != '':
                        d.clear()
                        d.move(0, 0)
                        d.write("Guest-Please Pay!")
                        d.move(0, 1)
                        d.write("Code: "+str(hbdata["uid"]))
                        d.color(0,0,255)
                        time.sleep(4)
                        
                     timecounting = 0
            print("Measured Distance = %.1f cm\n" % dist)
            time.sleep(1)

            # Reset by pressing CTRL + C
    except KeyboardInterrupt:
        print("Measurement stopped by User")
        GPIO.cleanup()
