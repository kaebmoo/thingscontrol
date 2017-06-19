# thingscontrol
Things Control - control anything you want.

netpie.io rest api example
from this api https://github.com/netpieio/microgear-restapi
this is C, PHP example to PUT GET topic from netpie service.
the example use cURL (https://github.com/curl/curl) and json-c (https://github.com/json-c/json-c) to call rest api and parse json aray.
code is test on raspberry pi 3.

[thingscontrol]
thingscontrol.conf - configuration file (uri, app id, topic, key, secret . . .)
read.netpie.profile - read timer profile (topic = profile) from netpie. the timer profile is day of week, start time (3 profiles, enable/disable), on time (minute 1-59) in pattern 0123456TTTHHMMHHMMHHMM01 ; 0123456 is day of week (Sun, Mon, Tue, Wed, Thu, Fri, Sat) int on enable and F for disable, TTT is start time enable or disable (F), HHMM is star time, and 01 is on timer (minute). and generate crontab for schedule timer on raspberry pi.

thingson.c - on/off relay board and update status (on/off) to netpie use with status-update.c // status_update("ON");
status-update.c - update status (topic = status)
httpful.phar - rest api for PHP

[example]
getnetpie.c - get value from netpie
http-put.c - put value to netpie
read_config.php - read configuration from file
read.netpie.data.php - read value from netpie
