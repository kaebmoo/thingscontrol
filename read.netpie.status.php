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
  $ini_array = parse_ini_file("thingscontrol.conf");
  $uri = $ini_array['uri'];
  $app = $ini_array['app_id'];
  $topic = $ini_array['id'];
  $status_uri = $ini_array['status'];
  $param = $ini_array['param'];
  $appkey = $ini_array['key'];
  $appsecret = $ini_array['secret'];


	$uri_start = $uri . $app  . $topic;
	$uri_end = $appkey . ":" . $appsecret;

  $uri_profile = $uri_start . $status_uri . $param . $uri_end;
  //echo $uri_profile . "\n";

  $fp = fsockopen("udp://8.8.8.8", 53, $errno, $errstr);
  if (!$fp) {
    echo "ERROR: $errno - $errstr<br />\t" . date("r") . "\n";
    exit(1);
  }



      // GET topic from netpie read "status" if status = "ON" turn on relay, status = "OFF" turn off relay.
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
      $status = $result[0]['payload'];
      $status_lastUpdated = date('r', $result[0]['lastUpdated']);
      echo "\n";
      echo $status . "\n";
      echo "Last updated : " . $status_lastUpdated . "\n";

      if( (strcmp($status, "ON") == 0) || (strcmp($status, "1") == 0)) {
        echo "Relay ON\n";
        $out = shell_exec("/home/pi/thingscontrol/bin/thingson");
        echo $out;
      }
      else if ( (strcmp($status, "OFF") == 0) || (strcmp($status, "0") == 0) ) {
        echo "Relay OFF\n";
        $out = shell_exec("/home/pi/thingscontrol/bin/thingsoff");
        echo $out;
      }



?>
