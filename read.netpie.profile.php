<?php
/*
    Things Control. Control anything you want.
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


*/
        //include('./httpful.phar');

        include "httpful.phar";
		$uri = "https://api.netpie.io/topic";
		$app = "/ThingsControl";
		$topic = "/seal";
		$appkey = "SvZc5fyI9gpRaTv";
		$appsecret = "tdyE0XGekaIi1orjaeHjBXGn2";

		$uri_start = $uri . $app  . $topic;
		$uri_end = $appkey . ":" . $appsecret;

    $uri_profile = $uri_start . "/profile?auth=" . $uri_end;

    $uri_HH = $uri_start . "/HH?auth=" . $uri_end;
		$uri_MM = $uri_start . "/MM?auth=" . $uri_end;
    $uri_HH2 = $uri_start . "/HH2?auth=" . $uri_end;
		$uri_MM2 = $uri_start . "/MM2?auth=" . $uri_end;
    $uri_HH3 = $uri_start . "/HH3?auth=" . $uri_end;
		$uri_MM3 = $uri_start . "/MM3?auth=" . $uri_end;
		$uri_Enable = $uri_start . "/Enable?auth=" . $uri_end;
		$uri_Enable2 = $uri_start . "/Enable2?auth=" . $uri_end;
		$uri_Enable3 = $uri_start . "/Enable3?auth=" . $uri_end;

		$uri_OnTimer = $uri_start . "/OnTimer?auth=" . $uri_end;

		$uri_Sun = $uri_start . "/Sun?auth=" . $uri_end;
		$uri_Mon = $uri_start . "/Mon?auth=" . $uri_end;
		$uri_Tue = $uri_start . "/Tue?auth=" . $uri_end;
		$uri_Wed = $uri_start . "/Wed?auth=" . $uri_end;
		$uri_Thu = $uri_start . "/Thu?auth=" . $uri_end;
		$uri_Fri = $uri_start . "/Fri?auth=" . $uri_end;
		$uri_Sat = $uri_start . "/Sat?auth=" . $uri_end;

		echo "weekday " . date('w') . "\n";
		$weekday = date('w');

    $profile = "";
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

		$array_uri = array($uri_HH, $uri_MM, $uri_HH2, $uri_MM2, $uri_HH3, $uri_MM3, $uri_Enable, $uri_Enable2, $uri_Enable3, $uri_OnTimer);
		$var_time = array($HH, $MM, $HH2, $MM2, $HH3, $MM3, $Enable, $Enable2, $Enable3, $OnTimer);
		$var_name = array("HH","MM","HH2","MM2","HH3","MM3","Enable","Enable2","Enable3","OnTimer");
		$lastUpdated = [];

    $try = 0;
    do {
      if ($try > 2)
        break;
      $response = \Httpful\Request::get($uri_profile)->send();
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
      $Enable3 = "false";
    }
    $OnTimer = substr($profile,22,2);

    echo $HH . ":" . $MM . " " . $HH2 . ":" . $MM2 . " " . $HH3 . ":" . $MM3 . "\n";
    echo $Enable . " " . $Enable2 . " " . $Enable3 . "\n";
    echo $OnTimer . "\n";
    //exit;

/*
		$i_var_time = 0;
		foreach($array_uri as $uri) {
			$try = 0;
			do {
				if ($try > 2)
					break;
				$response = \Httpful\Request::get($uri)->send();
				echo ".";
				sleep(1);
				$try++;

			} while (json_decode($response->code, true) != 200);
			$result = json_decode($response->body, true);
			$var_time[$i_var_time] = $result[0]['payload'];
			$lastUpdated[$i_var_time] = date('r', $result[0]['lastUpdated']);
			$i_var_time++;
		}
		echo "\n";
*/


		//$i_var_time = 0;
		//foreach($var_name as $VariableName) {
		//	$GLOBALS[$VariableName] = $var_time[$i_var_time++];
		//	echo $var_name[$i_var_time-1] . " = " . $GLOBALS[$VariableName] . "\t| Last Updated " . $lastUpdated[$i_var_time-1] . "\n";
		//}
		//echo "HH = " . $HH . "\n";
		//echo "MM = " . $MM . "\n";


        //echo $response . "\n" ;

		//$response = \Httpful\Request::get($uri_Enable)->send();
		//$result = json_decode($response->body, true);
		//echo "Enable = " . $result[0]['payload'] . "\n";
		//$Enable = $result[0]['payload'];
		//sleep(1);
        //echo $response . "\n" ;


		$Sun = "false";
		$Mon = "false";
		$Tue = "false";
		$Wed = "false";
		$Thu = "false";
		$Fri = "false";
		$Sat = "false";

		$array_weekday = array($uri_Sun, $uri_Mon, $uri_Tue, $uri_Wed, $uri_Thu, $uri_Fri, $uri_Sat);
		$weekday = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
		$var_weekday = array($Sun,$Mon,$Tue,$Wed,$Thu,$Fri,$Sat);


    for ($i = 0; $i < 7; $i++)
    {
        if (strcmp(substr($profile, $i, 1), "F") == 0) {
          $var_weekday[$i] = "false";
        }
        else {
          $var_weekday[$i] = substr($profile, $i, 1);
        }
        echo $var_weekday[$i] . "\n";
    }

    /*
		$i = 0;
		foreach($array_weekday as $value) {
			$try = 0;
			do {
				if ($try > 2)
					break;
				$response = \Httpful\Request::get($value)->send();
				sleep(1);

				$try++;

			} while (json_decode($response->code, true) != 200);
			$result = json_decode($response->body, true);
			$var_weekday[$i] = $result[0]['payload'];
			$lastUpdated[$i] = date('r', $result[0]['lastUpdated']);

			echo $weekday[$i] . " = " . $var_weekday[$i] . "\t| " . $lastUpdated[$i] . "\n";
			//echo $response . "\n" ;
			$i++;
		}
    */

		$set_weekday = "";
		for ($i = 0; $i < 7; $i++) {
			if ($var_weekday[$i] != "false") {
					$set_weekday = $set_weekday . $var_weekday[$i] . ",";
			}
		}

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
				echo $cron_weekday . "/home/pi/bin/thingson " . $GLOBALS["OnTimer"] . "\n";
				$time1 = $cron_weekday . "/home/pi/bin/thingson " . $GLOBALS["OnTimer"] . "\n";

			}

			if ($Enable2 == "true") {
				$cron_weekday = "";
				$cron_mm2_hh2 = $MM2 . " " . $HH2 . " * * ";
				$cron_weekday = $cron_mm2_hh2 . $set_weekday;
				echo $cron_weekday . "/home/pi/bin/thingson " . $GLOBALS["OnTimer"] . "\n";
				$time2 = $cron_weekday . "/home/pi/bin/thingson " . $GLOBALS["OnTimer"] . "\n";

			}
			if ($Enable3 == "true") {
				$cron_weekday = "";
				$cron_mm3_hh3 = $MM3 . " " . $HH3 . " * * ";
				$cron_weekday = $cron_mm3_hh3 . $set_weekday;
				echo $cron_weekday . "/home/pi/bin/thingson " . $GLOBALS["OnTimer"] . "\n";
				$time3 = $cron_weekday . "/home/pi/bin/thingson " . $GLOBALS["OnTimer"] . "\n";

			}

      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        exit;
      }
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

			// (crontab -l 2>/dev/null; echo "*/5 * * * * /path/to/job -with args") | crontab -
		}




?>
