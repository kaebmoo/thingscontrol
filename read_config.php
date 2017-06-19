
<?php

  //define('BIRD', 'Dodo bird');

  // Parse without sections
  $ini_array = parse_ini_file("thingscontrol.conf");
  print_r($ini_array);

  // Parse with sections
  //$ini_array = parse_ini_file("thingscontrol.conf", true);
  //print_r($ini_array);

  echo $ini_array['key'] . "\n";
  echo $ini_array['secret'] . "\n";
  echo $ini_array['param'] . "\n";
  echo $ini_array['uri'] . "\n";
  echo $ini_array['app_id'] . "\n";
  echo $ini_array['id'] . "\n";
  echo $ini_array['profile'] . "\n";
  $uri = $ini_array['uri'];
  $app_id = $ini_array['app_id'];
  $id = $ini_array['id'];
  $profile = $ini_array['profile'];
  $param = $ini_array['param'];
  $key = $ini_array['key'];
  $secret = $ini_array['secret'];

  $uri_ = $uri . $app_id . $id . $profile . $param . $key . ":" . $secret ;
  echo $uri_ . "\n";

?>
