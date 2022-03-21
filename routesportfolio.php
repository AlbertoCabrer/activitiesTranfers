<?php

$apikey = '77f737c122315d4c9292521b53b467c8';
$secret = 'd9658e68eb';
$date = time();

$token = $apikey.$secret.$date;
$Xsignature = hash('sha256', $token, false );

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com//transfer-cache-api/1.0/routes?fields=ALL&destinationCode=TFS&offset=1&limit=100',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Api-key: '.$apikey.'',
    'X-Signature: '.$Xsignature.'',
    'Accept: application/json',
    'Accept-Encoding: gzip',
    'Cookie: BIGipServer~Public~pool_activity-cache-api_int=rd1o00000000000000000000ffff0ade3050o8080; BIGipServer~Public~pool_transfer-api_int=rd1o00000000000000000000ffff0ade302bo8080; BIGipServer~Public~pool_transfer-cache-api_int=rd1o00000000000000000000ffff0ade3075o8080'
  ),
));

$response = curl_exec($curl);
curl_close($curl);

$decoded = json_decode($response);


    echo "<PRE>";
    print_r($decoded);
    echo "</PRE>";
    die();

?>