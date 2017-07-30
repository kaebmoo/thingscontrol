#!/bin/bash
while true
do
  php /home/pi/thingscontrol/read.netpie.profile.php
  sleep 5
  break
done
exit 0
