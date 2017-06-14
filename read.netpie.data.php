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

		/*
		$HH = $var_time[0];
		$MM = $var_time[1];
		$HH2 = $var_time[2];
		$MM2 = $var_time[3];
		$HH3 = $var_time[4];
		$MM3 = $var_time[5];
		$Enable = $var_time[6];
		$Enable2 = $var_time[7];
		$Enable3 = $var_time[8];
		$OnTimer = $var_time[9];
		*/
		
		$i_var_time = 0;
		foreach($var_name as $VariableName) {
			$GLOBALS[$VariableName] = $var_time[$i_var_time++];
			echo $var_name[$i_var_time-1] . " = " . $GLOBALS[$VariableName] . "\t| Last Updated " . $lastUpdated[$i_var_time-1] . "\n";
		}
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
			$titme3 = "";
			
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
		

		
		/*
		$response = \Httpful\Request::get($uri_Sun)->send();
		$result = json_decode($response->body, true);
		echo "Sun = " . $result[0]['payload'] . "\n";
		$Sun = $result[0]['payload'];
		sleep(1);
        echo $response . "\n" ;
		
		$response = \Httpful\Request::get($uri_Mon)->send();
		$result = json_decode($response->body, true);
		echo "Mon = " . $result[0]['payload'] . "\n";
		$Mon = $result[0]['payload'];
		sleep(1);
        echo $response . "\n" ;
		*/
		
        //var_dump($response);

        //echo $response->body . "\n";
        //$result = json_decode($response->body, true);
        //print_r($result); 

        
?>