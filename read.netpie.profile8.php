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
  $profile_uri = $ini_array['profile8'];
  $param = $ini_array['param'];
  $appkey = $ini_array['key'];
  $appsecret = $ini_array['secret'];

  $profile_array = parse_ini_file("/home/pi/thingscontrol/profile8.conf");
  $profile0 = $profile_array['profile8'];

  //$uri_ = $uri . $app . $topic . $profile . $param . $key . ":" . $secret ;


	$uri_start = $uri . $app  . $topic;
	$uri_end = $appkey . ":" . $appsecret;

  $uri_profile = $uri_start . $profile_uri . $param . $uri_end;
  //echo $uri_profile . "\n";

  $fp = fsockopen("udp://8.8.8.8", 53, $errno, $errstr);
  if (!$fp) {
    echo "ERROR: $errno - $errstr<br />\t" . date("r") . "\n";
    exit(1);
  }


  $profile = "";

  $profile8 = array
  (
    array("Sun"=>"F", "Mon"=>"F", "Tue"=>"F", "Wed"=>"F", "Thu"=>"F", "Fri"=>"F", "Sat"=>"F", "Enable"=>"F", "HH"=>"00", "MM"=>"00", "OnTimer"=>"00", "OnTimerHour"=>"00", "Repeat"=>"1", "Day"=>"01", "Month"=>"12", "Output"=>"0"),
    array("Sun"=>"F", "Mon"=>"F", "Tue"=>"F", "Wed"=>"F", "Thu"=>"F", "Fri"=>"F", "Sat"=>"F", "Enable"=>"F", "HH"=>"00", "MM"=>"00", "OnTimer"=>"00", "OnTimerHour"=>"00", "Repeat"=>"1", "Day"=>"01", "Month"=>"12", "Output"=>"0"),
    array("Sun"=>"F", "Mon"=>"F", "Tue"=>"F", "Wed"=>"F", "Thu"=>"F", "Fri"=>"F", "Sat"=>"F", "Enable"=>"F", "HH"=>"00", "MM"=>"00", "OnTimer"=>"00", "OnTimerHour"=>"00", "Repeat"=>"1", "Day"=>"01", "Month"=>"12", "Output"=>"0"),
    array("Sun"=>"F", "Mon"=>"F", "Tue"=>"F", "Wed"=>"F", "Thu"=>"F", "Fri"=>"F", "Sat"=>"F", "Enable"=>"F", "HH"=>"00", "MM"=>"00", "OnTimer"=>"00", "OnTimerHour"=>"00", "Repeat"=>"1", "Day"=>"01", "Month"=>"12", "Output"=>"0"),
    array("Sun"=>"F", "Mon"=>"F", "Tue"=>"F", "Wed"=>"F", "Thu"=>"F", "Fri"=>"F", "Sat"=>"F", "Enable"=>"F", "HH"=>"00", "MM"=>"00", "OnTimer"=>"00", "OnTimerHour"=>"00", "Repeat"=>"1", "Day"=>"01", "Month"=>"12", "Output"=>"0"),
    array("Sun"=>"F", "Mon"=>"F", "Tue"=>"F", "Wed"=>"F", "Thu"=>"F", "Fri"=>"F", "Sat"=>"F", "Enable"=>"F", "HH"=>"00", "MM"=>"00", "OnTimer"=>"00", "OnTimerHour"=>"00", "Repeat"=>"1", "Day"=>"01", "Month"=>"12", "Output"=>"0"),
    array("Sun"=>"F", "Mon"=>"F", "Tue"=>"F", "Wed"=>"F", "Thu"=>"F", "Fri"=>"F", "Sat"=>"F", "Enable"=>"F", "HH"=>"00", "MM"=>"00", "OnTimer"=>"00", "OnTimerHour"=>"00", "Repeat"=>"1", "Day"=>"01", "Month"=>"12", "Output"=>"0"),
    array("Sun"=>"F", "Mon"=>"F", "Tue"=>"F", "Wed"=>"F", "Thu"=>"F", "Fri"=>"F", "Sat"=>"F", "Enable"=>"F", "HH"=>"00", "MM"=>"00", "OnTimer"=>"00", "OnTimerHour"=>"00", "Repeat"=>"1", "Day"=>"01", "Month"=>"12", "Output"=>"0")
  );

  $var_name = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat","Enable","HH", "MM", "OnTimer", "OnTimerHour", "Repeat", "Day", "Month", "Output");
  $var_weekday = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");

  //for (;;) {
  		echo "weekday " . date('w') . "\t" . date("r") . "\n";
  		$dayofweek = date('w');

      $isProfileUpdated = 0;


      // GET topic from netpie read "8profile"
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
      echo "Last updated : " . $profile_lastUpdated . "\n";

      if(strcmp($profile, $profile0) == 0) {
        $isProfileUpdated = 0;
        echo "Profile does not change.\n";
      }
      else {  // profile changed.
        $profile0 = $profile;
        $isProfileUpdated = 1;
        $file = '/home/pi/thingscontrol/profile8.conf';
        $current = "profile8 = \"" . $profile . "\"";
        file_put_contents($file, $current);
      }

      if ($isProfileUpdated == 1) {
        $extractProfile = array("","","","","","","","");

        for ($row=0; $row < 8; $row++) { // 8 profiles.
          // 16 variables 22 characters 8 profiles.
          // FFFFFFF F 0909 1000 1 3112 0
          // <day><enable><hh mm><mm hh><repeat><day month><output>
          // (Week day: Sun Mon Tue Wed Thu Fri Sat:0-6,F=disable) (Enable: F=disable,T=Timer,1=on,0=off) (start time: HHMM=hour,minute) (On timer: min,hour) (repeat=1, once=0) (day,month) (output relay)

          $extractProfile[$row] = substr($profile,$row*22,22); // 22 column per profile.
          $index = 0;
          foreach($var_name as $VariableName) {
            if ($index < 8) {
          	 $GLOBALS[$VariableName] = substr($extractProfile[$row],$index++,1);
             $profile8[$row][$VariableName] = $GLOBALS[$VariableName];
            }
            else if ($index >= 8 && $index < 16){
              $GLOBALS[$VariableName] = substr($extractProfile[$row],$index,2);
              $index = $index + 2;
              $profile8[$row][$VariableName] = $GLOBALS[$VariableName];
            }
            else if ($index == 16) {
              $GLOBALS[$VariableName] = substr($extractProfile[$row],$index++,1);
              $profile8[$row][$VariableName] = $GLOBALS[$VariableName];
            }
            else if ($index >= 17 && $index < 21){
              $GLOBALS[$VariableName] = substr($extractProfile[$row],$index,2);
              $index = $index + 2;
              $profile8[$row][$VariableName] = $GLOBALS[$VariableName];
            }
            else {
              $GLOBALS[$VariableName] = substr($extractProfile[$row],$index,1);
              $index = $index + 1;
              $profile8[$row][$VariableName] = $GLOBALS[$VariableName];
            }
          }
        }

        foreach ($profile8 as $key) {
          foreach($var_name as $val)
            echo $key[$val] . " ";
          echo "\n";
        }
        unset($key);

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

        }
        else {
          $out = shell_exec("/usr/bin/crontab -r");
          echo $out;
        }
        foreach ($profile8 as $key) {
          if (strcmp($key['Enable'], "F") != 0) {
            $min = (int) $key['OnTimer'];
            $min_hour = (int) $key['OnTimerHour'] * 60;
            $ontimer =  $min + $min_hour;
            $output = $key['Output'];

            if(strcmp($key['Enable'], "T") == 0) {
              // timer mode call thingsontimer with timer (minute)
              if(strcmp($key['Repeat'], "1") == 0) {
                $set_weekday = "";

                foreach ($var_weekday as $weekday) {
                  if(strcmp($key[$weekday],"F") != 0) {
                    $set_weekday = $set_weekday . $key[$weekday] . ",";
                  }
                }
                if ($set_weekday == "") {
            			// user enable start time but all weekday is disabled.
            			echo "All weekday is disabled.\n";
            		}
                else {
                  //delete "," at the end of string.
                  $set_weekday = substr($set_weekday, 0, strlen($set_weekday)-1) . "\t";
                  echo $key['MM'] . " " . $key['HH'] .  " * * " . $set_weekday . "/home/pi/thingscontrol/bin/thingsontimer " . $ontimer . " " . $output . " >> /var/log/thingsontimer.log 2>&1\n";
                  $time = $key['MM'] . " " . $key['HH'] .  " * * " . $set_weekday . "/home/pi/thingscontrol/bin/thingsontimer " . $ontimer . " " . $output . " >> /var/log/thingsontimer.log 2>&1\n";
                }

              }
              else {
                echo $key['MM'] . " " . $key['HH'] . " " . $key['Day'] . " " . $key['Month'] . " * " . "/home/pi/thingscontrol/bin/thingsontimer " . $ontimer . " " . $output . " >> /var/log/thingsontimer.log 2>&1\n";
                $time = $key['MM'] . " " . $key['HH'] . " " . $key['Day'] . " " . $key['Month'] . " * " . "/home/pi/thingscontrol/bin/thingsontimer " . $ontimer . " " . $output . " >> /var/log/thingsontimer.log 2>&1\n";
              }
              if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

              }
              else {
                  $cron = "(crontab -l 2>/dev/null; echo \"" . $time . "\") | crontab - ";
                  $out = shell_exec($cron);
            			echo $out;
              }

            }
            else if (strcmp($key['Enable'], "1") == 0) {
              // on mode call thinson at time.
              if(strcmp($key['Repeat'], "1") == 0) {

                $set_weekday = "";
                foreach ($var_weekday as $weekday) {
                  if(strcmp($key[$weekday],"F") != 0) {
                    $set_weekday = $set_weekday . $key[$weekday] . ",";
                  }
                }
                if ($set_weekday == "") {
                  // user enable start time but all weekday is disabled.
                  echo "All weekday is disabled.\n";
                }
                else {
                  //delete "," at the end of string.
                  $set_weekday = substr($set_weekday, 0, strlen($set_weekday)-1) . "\t";
                  echo $key['MM'] . " " . $key['HH'] .  " * * " . $set_weekday . "/home/pi/thingscontrol/bin/thingson " . $output . " >> /var/log/thingsontimer.log 2>&1\n";
                  $time = $key['MM'] . " " . $key['HH'] .  " * * " . $set_weekday . "/home/pi/thingscontrol/bin/thingson " . $output . " >> /var/log/thingsontimer.log 2>&1\n";
                }

              }
              else {
                echo $key['MM'] . " " . $key['HH'] . " " . $key['Day'] . " " . $key['Month'] . " * " . "/home/pi/thingscontrol/bin/thingson " . $output . " >> /var/log/thingsontimer.log 2>&1\n";
                $time = $key['MM'] . " " . $key['HH'] . " " . $key['Day'] . " " . $key['Month'] . " * " . "/home/pi/thingscontrol/bin/thingson " .$output . " >> /var/log/thingsontimer.log 2>&1\n";
              }
              if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

              }
              else {
                  $cron = "(crontab -l 2>/dev/null; echo \"" . $time . "\") | crontab - ";
                  $out = shell_exec($cron);
                  echo $out;
              }
            }
            else if (strcmp($key['Enable'], "0") == 0) {
                // off mode, call thingsoff at time.
                if(strcmp($key['Repeat'], "1") == 0) {

                  $set_weekday = "";
                  foreach ($var_weekday as $weekday) {
                    if(strcmp($key[$weekday],"F") != 0) {
                      $set_weekday = $set_weekday . $key[$weekday] . ",";
                    }
                  }
                  if ($set_weekday == "") {
                    // user enable start time but all weekday is disabled.
                    echo "All weekday is disabled.\n";
                  }
                  else {
                    //delete "," at the end of string.
                    $set_weekday = substr($set_weekday, 0, strlen($set_weekday)-1) . "\t";
                    echo $key['MM'] . " " . $key['HH'] .  " * * " . $set_weekday . "/home/pi/thingscontrol/bin/thingsoff " . $output . " >> /var/log/thingsontimer.log 2>&1\n";
                    $time = $key['MM'] . " " . $key['HH'] .  " * * " . $set_weekday . "/home/pi/thingscontrol/bin/thingsoff " . $output . " >> /var/log/thingsontimer.log 2>&1\n";
                  }

                }
                else {
                  echo $key['MM'] . " " . $key['HH'] . " " . $key['Day'] . " " . $key['Month'] . " * " . "/home/pi/thingscontrol/bin/thingsoff " . $output . " >> /var/log/thingsontimer.log 2>&1\n";
                  $time = $key['MM'] . " " . $key['HH'] . " " . $key['Day'] . " " . $key['Month'] . " * " . "/home/pi/thingscontrol/bin/thingsoff " . $output . " >> /var/log/thingsontimer.log 2>&1\n";
                }
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

                }
                else {
                    $cron = "(crontab -l 2>/dev/null; echo \"" . $time . "\") | crontab - ";
                    $out = shell_exec($cron);
                    echo $out;
                }
            }

          }
        }
      }


      //sleep(5);

  //} infinite loop

?>
