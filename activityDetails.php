<?php

// $code = $_GET['code'];
// $code = $_GET['from'];
// $code = $_GET['to'];

 
$apikey = '78a90bc985f29dc1dc8acb3ae2359642';
$secret = 'd9658e68eb';
$date = time();

$token = $apikey.$secret.$date;
$Xsignature = hash('sha256', $token, false );

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/activity-api/3.0/activities/details',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "code": "'.$_GET['code'].'",
    "from": "'.$_GET['from'].'",
    "to": "'.$_GET['to'].'",
    "language": "'.$_GET['lang'].'",
    "paxes": [
      {"age": 30}
     ]
  }',
  CURLOPT_HTTPHEADER => array(
    'Api-key: '.$apikey.'',
    'X-Signature: '.$Xsignature.'',
    'Accept: application/json',
    'Accept-Encoding: gzip',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
if ($response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('Error al consumir el servicio web. Informacion Adicional: ' . var_export($info));
}

curl_close($curl);

$decoded = json_decode($response);



echo "<PRE>";
print_r($decoded);
echo "</PRE>";
die();
 


?>