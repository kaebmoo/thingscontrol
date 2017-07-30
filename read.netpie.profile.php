<?php
/*
    Things Control. Control anything as you want.
    Copyright (C) 2017  Pornthep Nivatyakul

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

    ThingsControl  Copyright (C) 2017  Pornthep Nivatyakul, kaebmoo@gmail.com, seal@ogonan.com
    This program comes with ABSOLUTELY NO WARRANTY;
    This is free software, and you are welcome to redistribute it
    under certain conditions.

    run every minute
    * * * * * /home/pi/thingscontrol/getprofile.sh

*/
        //include('./httpful.phar');

  include "httpful.phar";
  include "libs.php";
  /*
	$uri = "https://api.netpie.io/topic";
	$app = "/ThingsControl";
	$topic = "/seal";
	$appkey = "key";
	$appsecret = "secret";
  */

  // Parse without sections
  $ini_array = parse_ini_file("/home/pi/thingscontrol/thingscontrol.conf");
  $uri = $ini_array['uri'];
  $app = $ini_array['app_id'];
  $topic = $ini_array['id'];
  $profile = $ini_array['profile'];
  $param = $ini_array['param'];
  $appkey = $ini_array['key'];
  $appsecret = $ini_array['secret'];

  $profile_array = parse_ini_file("/home/pi/thingscontrol/profile.conf");
  $profile0 = $profile_array['profile'];

  //$uri_ = $uri . $app . $topic . $profile . $param . $key . ":" . $secret ;


	$uri_start = $uri . $app  . $topic;
	$uri_end = $appkey . ":" . $appsecret;

  $uri_profile = $uri_start . $profile . $param . $uri_end;
  //echo $uri_profile . "\n";

  $fp = fsockopen("udp://8.8.8.8", 53, $errno, $errstr);
  if (!$fp) {
    echo "ERROR: $errno - $errstr<br />\t" . date("r") . "\n";
    exit(1);
  }


  //for (;;) {
  		echo "weekday " . date('w') . "\t" . date("r") . "\n";
  		$dayofweek = date('w');

      $profile = "";
      $isProfileUpdated = 0;
      $HH = 0;
  		$MM = 0;
  		$HH2 = 0;
  		$MM2 = 0;
  		$HH3 = 0;
  		$MM3 = 0;
  		$Enable = "false";
  		$Enable2 = "false";
  		$Enable3 = "false";
  		$OnTimer = 5;


      $try = 0;
      do {
        if ($try > 2) {
          echo "\n" . "Error code = " . json_decode($response->code, true) . "\t" . date("r") . "\n";
          exit(2);
        }
        try {
          $response = \Httpful\Request::get($uri_profile)->send();
        }
        catch(Exception $e) {
          echo 'Message: ' . $e->getMessage() . "\t" . date("r") . "\n";
          exit(1);
        }
        echo ".";
        sleep(1);
        $try++;

      } while (json_decode($response->code, true) != 200);


      $result = json_decode($response->body, true);
      $profile = $result[0]['payload'];
      $profile_lastUpdated = date('r', $result[0]['lastUpdated']);
      echo "\n";
      echo $profile . "\n";
      echo $profile_lastUpdated . "\n";

      if(strcmp($profile, $profile0) == 0) {
        $isProfileUpdated = 0;
        echo "Profile does not change.\n";
      }
      else {  // profile changed.
        $profile0 = $profile;
        $isProfileUpdated = 1;
        $file = '/home/pi/thingscontrol/profile.conf';
        $current = "profile = \"" . $profile . "\"";
        $ok = file_put_contents($file, $current);
        echo $ok . "bytes";
        echo "\n";
      }

      if ($isProfileUpdated == 1) {
        $HH = substr($profile, 10, 2);
        $MM = substr($profile, 12, 2);
        $HH2 = substr($profile, 14, 2);
        $MM2 = substr($profile, 16, 2);
	      $HH3 = substr($profile, 18, 2);
        $MM3 = substr($profile, 20, 2);

        if (strcmp(substr($profile,7,1), "T") == 0) {
          $Enable = "true";
        }
        else {
          $Enable = "false";
        }
        if (strcmp(substr($profile,8,1), "T") == 0) {
          $Enable2 = "true";
        }
        else {
          $Enable2 = "false";
        }
        if (strcmp(substr($profile,9,1), "T") == 0) {
          $Enable3 = "true";
        }
        else {
          $HH3 = substr($profile, 18, 2);
          $Enable3 = "false";
        }
        $OnTimer = substr($profile,22,2);

        echo $HH . ":" . $MM . " " . $HH2 . ":" . $MM2 . " " . $HH3 . ":" . $MM3 . "\n";
        echo $Enable . " " . $Enable2 . " " . $Enable3 . "\n";
        echo $OnTimer . "\n";


    		$Sun = "false";
    		$Mon = "false";
    		$Tue = "false";
    		$Wed = "false";
    		$Thu = "false";
    		$Fri = "false";
    		$Sat = "false";

    		$var_weekday = array($Sun,$Mon,$Tue,$Wed,$Thu,$Fri,$Sat);


        for ($i = 0; $i < 7; $i++)
        {
            if (strcmp(substr($profile, $i, 1), "F") == 0) {
              $var_weekday[$i] = "false";
            }
            else {
              $var_weekday[$i] = substr($profile, $i, 1);
            }
            //echo $var_weekday[$i] . "\n";
        }

    		$set_weekday = "";
    		for ($i = 0; $i < 7; $i++) {
    			if ($var_weekday[$i] != "false") {
    					$set_weekday = $set_weekday . $var_weekday[$i] . ",";
    			}
    		}
        //unset($var_weekday);

    		if ($set_weekday == "") {
    			// user enable start time but all weekday is disabled.
    			echo "All weekday is disabled.\n";
    		}
    		else {
    			$time1 = "";
    			$time2 = "";
    			$time3 = "";

    			//delete "," at the end of string.
    			$set_weekday = substr($set_weekday, 0, strlen($set_weekday)-1) . "\t";
    			// if start time is enalbed. we set the weekday in crontab.

    			if ($Enable == "true") {
    				$cron_weekday = "";
    				//echo $MM . " " . $HH . " * * ";
    				$cron_mm_hh = $MM . " " . $HH . " * * ";
    				$cron_weekday = $cron_mm_hh . $set_weekday;
    				echo $cron_weekday . "/home/pi/thingscontrol/bin/thingsontimer " . $GLOBALS["OnTimer"] . " >> /var/log/thingsontimer.log 2>&1\n";
    				$time1 = $cron_weekday . "/home/pi/thingscontrol/bin/thingsontimer " . $GLOBALS["OnTimer"] . " >> /var/log/thingsontimer.log 2>&1\n";

    			}

    			if ($Enable2 == "true") {
    				$cron_weekday = "";
    				$cron_mm2_hh2 = $MM2 . " " . $HH2 . " * * ";
    				$cron_weekday = $cron_mm2_hh2 . $set_weekday;
    				echo $cron_weekday . "/home/pi/thingscontrol/bin/thingsontimer " . $GLOBALS["OnTimer"] . " >> /var/log/thingsontimer.log 2>&1\n";
    				$time2 = $cron_weekday . "/home/pi/thingscontrol/bin/thingsontimer " . $GLOBALS["OnTimer"] . " >> /var/log/thingsontimer.log 2>&1\n";

    			}
    			if ($Enable3 == "true") {
    				$cron_weekday = "";
    				$cron_mm3_hh3 = $MM3 . " " . $HH3 . " * * ";
    				$cron_weekday = $cron_mm3_hh3 . $set_weekday;
    				echo $cron_weekday . "/home/pi/thingscontrol/bin/thingsontimer " . $GLOBALS["OnTimer"] . " >> /var/log/thingsontimer.log 2>&1\n";
    				$time3 = $cron_weekday . "/home/pi/thingscontrol/bin/thingsontimer " . $GLOBALS["OnTimer"] . " >> /var/log/thingsontimer.log 2>&1\n";

    			}

          if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

          }
          else {
          /* crontab -r */
      			$out = shell_exec("/usr/bin/crontab -r");
      			echo $out;
      			$cron1 = "(crontab -l 2>/dev/null; echo \"" . $time1 . "\") | crontab - ";
      			$cron2 = "(crontab -l 2>/dev/null; echo \"" . $time2 . "\") | crontab - ";
      			$cron3 = "(crontab -l 2>/dev/null; echo \"" . $time3 . "\") | crontab - ";
      			$out = shell_exec($cron1);
      			echo $out;
      			$out = shell_exec($cron2);
      			echo $out;
      			$out = shell_exec($cron3);
      			echo $out;
          }
    			// (crontab -l 2>/dev/null; echo "*/5 * * * * /path/to/job -with args") | crontab -
    		} // weekday enable
      } // check isProfileUpdated
      //sleep(5);

  //} // infinite loop


?>
