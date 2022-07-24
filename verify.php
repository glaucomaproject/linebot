<?php
$access_token = 'vJdzjrUAxybexlcIhApBv8hS0XZHICcF7poHIcSGaVGgHK4/xiYkWs1FCyl9LQRy3Mwh9MIwmvZg/n/0pRxDZLDsCES76NChzi3eAeEhiP0HczzHv/L2SI6eInqZeSkr68e/zwRVeuwD8EGY5d2yfQdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;