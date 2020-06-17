<?php
// Get next hour forecast weather and push message if it going to rain
// Manual to add users
// 1. add user ID in $user_list
// 2. add user ID and Accuweather location ID in getLocation()

$user_list = array('U7cbafaedd599e8edd822e5e15476ddf8', 'U3b41f80c259f8efcc4ee03b193b0d29d');
foreach ($user_list as &$user) {
    $location_key = getLocation($user);
    $weather_url = getWeatherUrlIfRain($location_key);
  /*  if (!is_null($weather_url)) {
      pushMessage($user, $weather_url);
    }*/
    echo $user. '|' . $location_key . '|' . $weather_url;
    echo getMessageData($user, $weather_url);
    pushMessage('U7cbafaedd599e8edd822e5e15476ddf8', $weather_url);
}


function getWeatherUrlIfRain($location_key){
  $weather_apikey = 'AJiNGSulcjPJzSXq4F5OhmQmsSUaY7m0';
  $api_endpoint = 'http://dataservice.accuweather.com/forecasts/v1/hourly/1hour/';
  // current condition API 'http://dataservice.accuweather.com/currentconditions/v1/';

  $request_url = $api_endpoint . $location_key . '?apikey=' . $weather_apikey;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $request_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);
  curl_close($ch);
  $obj = json_decode($result, true);
  if ($obj[0]["HasPrecipitation"] || $obj[0]["PrecipitationProbability"] > 50) {
    return $obj[0]["MobileLink"];
  }
  else return $obj[0]["MobileLink"];
}

// Map location and user
function getLocation($user_id)
{
  if ($user_id == 'U7cbafaedd599e8edd822e5e15476ddf8') { return "318821"; } // Noey, Siam Square
  else if ($user_id == 'U3b41f80c259f8efcc4ee03b193b0d29d') { return "319847"; } // Por, Nonthaburi
  return "318849"; // Bangkok
}

function pushMessage($user_id, $url) {
  $access_token = 'tWEShrbn4QrklPwjlObTVPhhGo5AnJAL/YAkZB0OaaC1rLhITnIfRDNq3s0/pTyiBAwkj6ysNYk45abbUh/hBHvi+JC0GCME7kHXnM2J8lLhLC2sE3eiMlMzObRa0fNmiWvpuFd34l8nS6Mw6Xo7cwdB04t89/1O/w1cDnyilFU=';
  $url = 'https://api.line.me/v2/bot/message/push';

  $headers = array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json');
  $data = getMessageData($user_id, $url);

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


function getMessageData($user_id, $url){
  return '{"to": "'.$user_id.'",
    "messages":
    {"type":"flex",
    "altText": "Rain is coming",
    "contents":'. getContent($url) .'}]}';
}
function getContent($url){
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
                  "text": "Rain is coming",
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
            "uri": "'.$url.'"

          }
        }
      ]
    }
  }
  ';
}


?>
