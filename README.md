# thingscontrol
Things Control - control anything as you want.

use netpie.io rest api from this https://github.com/netpieio/microgear-restapi

use C, PHP to PUT GET topic from netpie service.

use cURL (https://github.com/curl/curl) and json-c (https://github.com/json-c/json-c) to call rest api and parse json aray. code is test on raspberry pi 3 and pi zero with automation phat.

you can set profile or 8 profiles by android app (create from app inventor) the thingscontrol app is set profile and send it to netpie. on the other side (raspberry pi) I create script to run infinite loop to read profile from netpie and create cronjob (php) to turn on/off relay by calling command in /bin (you can use gpio command from wiringPi instead C program). and onoff_relay.sh is script run for check status if the status is "ON" (user press switch in thingscontrol mobile app) the script is call command to turn on relay (switch on) or the other hand is turn off. 

[thingscontrol]

thingscontrol.conf - configuration file (uri, app id, topic, key, secret . . .)

read.netpie.profile - read timer profile (topic = profile) from netpie. the timer profile is day of week, start time (3 profiles, enable/disable), on time (minute 1-59) in pattern 0123456TTTHHMMHHMMHHMM01 ; 0123456 is day of week (Sun, Mon, Tue, Wed, Thu, Fri, Sat) int on enable and F for disable, TTT is start time enable or disable (F), HHMM is star time, and 01 is on timer (minute). and generate crontab for schedule timer on raspberry pi.

read.netpie.profile8 - read timer profile (8 profiles support). disable, timer, on, off, repeat, or once. FFFFFFF F 0909 1000 1 3112 <day 0-6><enable F,T,0,1><hhmm><mmhh><repeat/once><day month>

read.netpie.status.php - read topic status from netpie if "ON" or "1" turn on relay, else if "OFF" or "0" turn off relay.

[src]

thingsontimer.c - on and off relay board with delay time and update status (on/off) to netpie use with status-update.c // status_update("ON") or status_update("OFF");

thingson.c - on relay board and update status (on) to netpie use with status-update.c // status_update("ON");

thingsoff.c - off relay board and update status (off) to netpie use with status-update.c // status_update("OFF");

status-update.c - update status (topic = status)

httpful.phar - rest api for PHP


[example]

getnetpie.c - get value from netpie

http-put.c - put value to netpie

read_config.php - read configuration from file

read.netpie.data.php - read value from netpie


[hardware]

raspberry pi zero w

automation phat

adafruit PiRTC PCF8523



ภาษาไทยอ่านที่นี่ https://github.com/kaebmoo/thingscontrol/wiki 
