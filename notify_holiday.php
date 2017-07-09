<?php

// constants
$_cr = '%0D%0A';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$event = json_decode($content, true);
$event_name = $event['description'];
$start_date = $event['start__dateTime_pretty'];
$end_date = $event['end__dateTime_pretty'];

// todo: parse country/tag from description

// create message
$message = 'Tomorrow is '. $event_name . $cr;
$message .= 'From: ' . $start_date . $cr;
$message .= 'To: ' . $end_date;

$access_token = 'tWEShrbn4QrklPwjlObTVPhhGo5AnJAL/YAkZB0OaaC1rLhITnIfRDNq3s0/pTyiBAwkj6ysNYk45abbUh/hBHvi+JC0GCME7kHXnM2J8lLhLC2sE3eiMlMzObRa0fNmiWvpuFd34l8nS6Mw6Xo7cwdB04t89/1O/w1cDnyilFU=';
$url = 'https://api.line.me/v2/bot/message/push'; 

$headers = array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json');
$data = '{"to": "U7cbafaedd599e8edd822e5e15476ddf8",';
$data .= '"messages":[';
$data .= '{"type":"text",';
$data .= '"text":"' . $message . '"}]}';


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
curl_close($ch);

echo $result;

?>
