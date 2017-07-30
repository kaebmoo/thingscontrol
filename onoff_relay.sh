#!/bin/bash
while true
do
  php /home/pi/thingscontrol/read.netpie.status.php
  sleep 5
  break
done
exit 0

# update status every 5 second.
# /home/pi/thingscontrol/bin/update.netpie.status 2
