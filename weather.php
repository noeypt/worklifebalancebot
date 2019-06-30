<?php
// Get current weather and push message if it is raining
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
  pushMessage( getFlexMessage() );
}


function pushMessage($flex_message) {
  $access_token = 'tWEShrbn4QrklPwjlObTVPhhGo5AnJAL/YAkZB0OaaC1rLhITnIfRDNq3s0/pTyiBAwkj6ysNYk45abbUh/hBHvi+JC0GCME7kHXnM2J8lLhLC2sE3eiMlMzObRa0fNmiWvpuFd34l8nS6Mw6Xo7cwdB04t89/1O/w1cDnyilFU=';
  $url = 'https://api.line.me/v2/bot/message/push';

  $headers = array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json');
  $data = '{"to": "U7cbafaedd599e8edd822e5e15476ddf8",';
  $data .= '"messages":[';
  $data .= '{"type":"flex",';
  $data .= '"altText": "It is raining now",';
  $data .= '"contents":'.$flex_message.'}]}';


  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  curl_close($ch);

  echo $result;
}

function getFlexMessage(){
  return '
  {
    "type": "bubble",
    "body": {
      "type": "box",
      "layout": "vertical",
      "spacing": "md",
      "contents": [
        {
          "type": "box",
          "layout": "baseline",
          "spacing": "md",
          "contents": [
            {
              "type": "text",
              "text": "Weather notification",
              "weight": "bold",
              "size": "xl",
              "flex": 0
            }
          ]
        },
        {
          "type": "box",
          "layout": "horizontal",
          "spacing": "md",
          "margin": "xl",
          "contents": [
            {
              "type": "box",
              "layout": "vertical",
              "flex": 0,
              "contents": [
                {
                  "type": "image",
                  "url": "https://developer.accuweather.com/sites/default/files/12-s.png",
                  "aspectRatio": "1:1",
                  "size": "sm",
                  "gravity": "bottom"
                }
              ]
            },
            {
              "type": "box",
              "layout": "vertical",
              "flex": 1,
              "spacing": "xs",
              "contents": [
                {
                  "type": "spacer",
                  "size": "sm"
                },
                {
                  "type": "text",
                  "text": "It is raining now",
                  "color": "#111111",
                  "wrap": true,
                  "gravity": "top",
                  "size": "md"
                }
              ]
            }
          ]
        }
      ]
    },
    "footer": {
      "type": "box",
      "layout": "vertical",
      "spacing": "md",
      "margin": "xl",
      "contents": [
        {
          "type": "button",
          "style": "primary",
          "color": "#00B900",
          "action": {
            "type": "uri",
            "label": "See details",
            "uri": "http://m.accuweather.com/en/th/siam-square/318821/current-weather/318821?lang=en-us"

          }
        }
      ]
    }
  }
  ';
}

?>
