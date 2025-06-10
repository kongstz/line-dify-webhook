<?php
$accessToken = 'YOUR_LINE_CHANNEL_ACCESS_TOKEN';

$input = json_decode(file_get_contents('php://input'), true);
$replyToken = $input['events'][0]['replyToken'];
$userText = $input['events'][0]['message']['text'];

$difyURL = 'https://udify.app/chat/your-chat-id';
$response = file_get_contents($difyURL . '?question=' . urlencode($userText));
$answer = json_decode($response, true)['answer'] ?? 'ขออภัย ระบบไม่สามารถตอบได้';

$replyData = [
    'replyToken' => $replyToken,
    'messages' => [['type' => 'text', 'text' => $answer]],
];
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $accessToken,
];

$ch = curl_init('https://api.line.me/v2/bot/message/reply');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($replyData));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_exec($ch);
curl_close($ch);
?>
