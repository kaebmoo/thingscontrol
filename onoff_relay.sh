#!/bin/bash
while true
do
  php /home/pi/thingscontrol/read.netpie.status.php
  sleep 2
done

# update status every 2 second.
# /home/pi/thingscontrol/bin/update.netpie.status 2
