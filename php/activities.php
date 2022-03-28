<?php


    if( isset($_POST['activities']) ) {
        $apikey = '78a90bc985f29dc1dc8acb3ae2359642';
        $secret = 'd9658e68eb';
        $date = time();
        
        $token = $apikey.$secret.$date;
        $Xsignature = hash('sha256', $token, false );
       

        $seg = '';
        if( is_array($_POST['activities']) && count($_POST['activities']) != 0){
            foreach($_POST['activities'] as $segm){
                $seg .='{"type": "segment", "value": "'. $segm.'"},' ; 
            }
        }
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.test.hotelbeds.com/activity-api/3.0/activities',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "filters": [
                {
                    "searchFilterItems": [ 
                        {"type": "hotel", "value": "'.$_POST['hotel'].'"},
                        '.$seg.'
                        {"type": "destination", "value": "TFS"}
                        
                    
                    ]
                
                }
        ],
        "from": "'.$_POST['from'].'",
        "to": "'.$_POST['to'].'",
        "language": "'.$_POST['language'].'",
        "pagination": {
            "itemsPerPage": 99,
            "page": 1
        },
        "order": "NAME"
        }',
        CURLOPT_HTTPHEADER => array(
            'Api-key: '.$apikey.'',
            'X-Signature: '.$Xsignature.'',
            'Accept: application/json',
            'Accept-Encoding: gzip',
            'Content-Type: application/json',
            'Cookie: BIGipServer~Public~pool_transfer-cache-api_int=rd1o00000000000000000000ffff0ade3075o8080'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        // echo  json_encode($response);
        echo $response;

    }  
    else {
        echo json_encode(array('success' => 0));
    }  

?>
