<?php // callback.php

require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = '8G/19OV1PFe3LtbbUIK1YhzQBtigpfk2ncxGA+Ul3yzDhRKEl2O6rJUj/RmMMe93lojH6foXpZ5Ruw6CpN6+/klIWoQT3dIGI3iMl6aYvd8qYjcpSMpC4/WsYi6OaSUhf+UADvVyOBdHvIPpN8dL5QdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {

    // Loop through each event
    foreach ($events['events'] as $event) {

        // Reply only when message sent is in 'text' format
        if ($event['type'] == 'message' && $event['message']['type'] == 'text') {

            $line_user_id = $event['source']['userId'];

            if ($event['message']['text'] == 'เวลาละหมาด') {
                $text = 'เวลาละหมาดของวันที่ 14 กรกฎาคม 2561 คือ...';
            }
            if ($event['message']['text'] == 'ร้านอาหารฮาลาล') {
                $text = 'ร้านอาหารฮาลาลใกล้เคียงคือ';
            }
            if ($event['message']['text'] == 'ช้อปปิ้ง') {
                $text = 'ต้องการช้อปปิ้งอะไรดีครับ';
            }

            // Get text sent
            //$text = $event['source']['userId'];

            // Get replyToken
            $replyToken = $event['replyToken'];

            $profile = getUserProfile($access_token, $line_user_id);

            // Build message to reply back
            $messages = [
                'type' => 'text',
                'text' => $profile->displayName
            ];
            /*
            $messages = [
                'type' => 'buttons',
                'text' => 'แชร์โลเคชั่น',
                'actions' => array(
                                "type"=> "postback",
                                "label"=> "Buy",
                                "data"=> "action=buy&itemid=123"
                                )
                ];

            */
            // Make a POST Request to Messaging API to reply to sender
            $url = 'https://api.line.me/v2/bot/message/reply';

            $data = [
                'replyToken' => $replyToken,
                'messages' => [ $messages ],
            ];

            $post = json_encode($data);
            $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $result = curl_exec($ch);
            curl_close($ch);

            echo $result . "\r\n";
        }
    }
}

function getUserProfile($access_token, $user_id){

    $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

    $ch = curl_init('https://api.line.me/v2/bot/profile/'.$user_id);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;

}
