<?php
// Get current weather
$weather_apikey = 'AJiNGSulcjPJzSXq4F5OhmQmsSUaY7m0';
$weather_url = 'http://dataservice.accuweather.com/currentconditions/v1/';
$location_key = '318821'; //Siam Square
$request_url = $weather_url . $location_key . '?apikey=' . $weather_apikey;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $request_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result, true);
if ($obj[0]["HasPrecipitation"]) {
  $weather_link = $obj[0]["MobileLink"];
  $message = "It's raining now\n". $weather_link;
  pushMessage($message);
}

function pushMessage($message) {
  $access_token = 'tWEShrbn4QrklPwjlObTVPhhGo5AnJAL/YAkZB0OaaC1rLhITnIfRDNq3s0/pTyiBAwkj6ysNYk45abbUh/hBHvi+JC0GCME7kHXnM2J8lLhLC2sE3eiMlMzObRa0fNmiWvpuFd34l8nS6Mw6Xo7cwdB04t89/1O/w1cDnyilFU=';
  $url = 'https://api.line.me/v2/bot/message/push';

  $headers = array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json');
  $data = '{"to": "U7cbafaedd599e8edd822e5e15476ddf8",';
  $data .= '"messages":[';
  $data .= '{"type":"text",';
  $data .= '"text":"'.$message.'"}]}';


  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  curl_close($ch);
}

?>
