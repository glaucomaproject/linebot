<?php
$access_token = 'B9bYs1bvVrykn7StFNhqxxIu20urWqMSvuHADMOY1d8vHYeiM+gHnfwkTYvFXYG6x4FLE1LTK3AJux5/lUokMoLGF/zvHAQ0IXGq0i/RUGSyuIxC5jx8yY6jI/3OHEF5c5B3fgerqVrayq07Z8A3FgdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;