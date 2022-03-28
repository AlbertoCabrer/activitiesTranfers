<?php

// Obtenet las actividades segun los parametros insertados
        if(isset($_POST['submit'])) 
        {
        
            $apikey = '78a90bc985f29dc1dc8acb3ae2359642';
            $secret = 'd9658e68eb';
            $date = time();
            
            $token = $apikey.$secret.$date;
            $Xsignature = hash('sha256', $token, false );

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
                                {"type": "hotel", "value": "'.$_POST['hotel'].'"}
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
            if(isset($decoded->activities)){
                $activities = $decoded->activities;
            }       
            else{
                $activities= []; 
            } 
            
            //Obtener lista de Categorias
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.test.hotelbeds.com/activity-content-api/3.0/segments/'.$_POST['language'].'',
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
                'Cookie: BIGipServer~Public~pool_transfer-cache-api_int=rd1o00000000000000000000ffff0ade3075o8080'
            ),
            ));
            $response = curl_exec($curl);
            if ($response === false) {
                $info = curl_getinfo($curl);
                curl_close($curl);
                die('Error al consumir el servicio web categorias. Informacion Adicional: ' . var_export($info));
            }

            curl_close($curl);

            $decoded = json_decode($response);
            if(isset($decoded->segmentationGroups[0]->segments)){
                $categories = $decoded->segmentationGroups[0]->segments;
            }       
            else{
                $categories= []; 
            } 
            // echo "<PRE>";
            // print_r($categories);
            // echo "</PRE>";
            // die();


        }



        $apikeyH = '77f737c122315d4c9292521b53b467c8';
        $secretH = 'd9658e68eb';
        $date = time();

        $tokenH = $apikeyH.$secretH.$date;
        $XsignatureH = hash('sha256', $tokenH, false );


    // Obtener lista de Hoteles
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.test.hotelbeds.com//transfer-cache-api/1.0/hotels?fields=ALL&language=es&countryCodes=ES&destinationCodes=TFS',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Api-key: '.$apikeyH.'',
                'X-Signature: '.$XsignatureH.'',
                'Accept: application/json',
                'Accept-Encoding: gzip',
                'Cookie: BIGipServer~Public~pool_activity-cache-api_int=rd1o00000000000000000000ffff0ade3050o8080; BIGipServer~Public~pool_transfer-api_int=rd1o00000000000000000000ffff0ade302bo8080; BIGipServer~Public~pool_transfer-cache-api_int=rd1o00000000000000000000ffff0ade3075o8080'
            ),
        ));

        $response = curl_exec($curl);
        if ($response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('Error al consumir el servicio web hotels. Informacion Adicional: ' . var_export($info));
        }

        curl_close($curl);

        $hotels = json_decode($response);

        $hotelsSort = PHPArrayObjectSorter( $hotels,'city');


    
         //Funtion para ordenar array
        function PHPArrayObjectSorter($array,$sortBy,$direction='asc')
        {
            $sortedArray=array();
            $tmpArray=array();
            foreach($array as $obj)
            {
                $tmpArray[]=$obj->$sortBy;
            }
            if($direction=='asc'){
                asort($tmpArray);
            }else{
                arsort($tmpArray);
            }

            foreach($tmpArray as $k=>$tmp){
                $sortedArray[]=$array[$k];
            }

            return $sortedArray;

        }

// echo "<PRE>";
// print_r($activities);
// echo "</PRE>";
// die();
 

?>

<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Activities</title>

            <!-- CSS only -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        </head>
        
		<body>

        <div class="container">

        <div class="row">
            <div class="col p-3">
                <a href="index.php">Home</a>
                <a href="transfers.php">Transfers</a>
            </div>   
        </div>

            <div class="row ">
                <div class="col p-5">
                <div class="card">
                    <div class="card-header">
                        Activities Seach 
                    </div>
                    <div class="card-body">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <fieldset>
                                <div class="row justify-content-md-center">
                                    <div class="col-2">
                                        <label  class="form-label">Language</label>
                                        <select class="form-control" aria-label="Default select example" name="language">
                                            <option value="en" <?php if(isset($_POST['language']) && $_POST['language'] == 'en'){echo 'selected';};?> >English</option>
                                            <option value="es" <?php if(isset($_POST['language']) && $_POST['language'] == 'es'){echo 'selected';};?> >Spanish</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label  class="form-label">From</label>
                                        <select class="form-control" aria-label="Default select example" id="hotelsFrom" name="hotel">
                                            <?php foreach($hotelsSort as $key=>$hotel):?>
                                                <option value="<?php echo $hotelsSort[$key]->code;?>" <?php if(isset($_POST['hotel']) && $hotelsSort[$key]->code == $_POST['hotel']){echo 'selected';};?> ><?php echo $hotelsSort[$key]->city.' |'.$hotelsSort[$key]->name;?></option>
                                            <?php endforeach?>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label  class="form-label">From</label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control datepicker" name="from" value="<?php if(isset($_POST['from'])){echo $_POST['from'];} else{echo date('Y-m-d');};?>">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <label  class="form-label">To</label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control datepicker" name = "to" value="<?php  if(isset($_POST['to'])){echo $_POST['to'];} else{echo date('Y-m-d');} ;?>">
                                        </div>
                                    </div>
                                    
                                   
                                </div>
                                <hr>
                        </fieldset>
                        <button type="submit" name="submit" value="Submit Form" class="btn btn-primary">Seach</button>
                    </form>
                    </div>
                </div>
                
            </div>

            <div class="row bg-light">
                <?php if(isset($activities) && count($activities) > 0){?>
                <div class="col col-md-4 pt-3">
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                               Categories
                            </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                            <div class="accordion-body">
                                <?php foreach($categories as $key=>$category):?>
                                    <div class="form-check">
                                        <input class="form-check-input category" type="checkbox" value="<?php echo $categories[$key]->code ;?>" id="category<?php echo $categories[$key]->code ;?>">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            <?php echo $categories[$key]->name ;?>
                                        </label>
                                    </div>
                                <?php endforeach?>    
                            </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                Accordion Item #2
                            </button>
                            </h2>
                            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                            <div class="accordion-body">
                                <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                Accordion Item #3
                            </button>
                            </h2>
                            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                            <div class="accordion-body">
                                <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                            </div>
                        </div>
                    </div>                           
                </div>
                
                <div class="col pt-3">
                    <div class="row" id="cardActivity">  
                        <?php foreach ($activities as $key=>$activity){?>
                            <div class="col col-md-6 col-lg-4">
                                <div class="card shadow mb-3">
                                    <img src="<?php echo $activities[$key]->content->media->images[0]->urls[4]->resource?>" class="card-img-top" alt="...">
                                    <div class="card-body"  style="height: 10rem;">
                                        <h5 class="card-title"><?php echo $activities[$key]->name; ?></h5>
                                        <p class="card-text text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                            <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                                            <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                            </svg>
                                            <?php if(isset($activities[$key]->content->location->startingPoints[0]->meetingPoint->city))
                                                { 
                                                    echo $activities[$key]->content->location->startingPoints[0]->meetingPoint->city.', '.$activities[$key]->content->location->startingPoints[0]->meetingPoint->country->name;
                                                } 
                                                else{ 
                                                    echo "Varios destinos";
                                            }?>
                                        </p>
                                        <a href="activityDetails.php?code=<?php echo $activities[$key]->code; ?>&lang=<?php echo $_POST['language'];?>&from=<?php echo $_POST['from'];?>&to=<?php echo $_POST['to'];?>" class="stretched-link"></a>
                                    </div>
                                    <div class="text-end p-2">
                                        <h5 class="text-danger fw-bold"><?php echo $activities[$key]->amountsFrom[0]->amount .' '. $activities[$key]->currency; ?></h5>
                                    </div>
                                </div>
                            </div>    
                        <?php } ?>
                    </div>    
                    
                <?php }
                else{
                    if(isset($activities) && count($activities) <= 0){?> 
                       <div class="alert alert-warning" role="alert">
                         Revice los parametros insertados
                       </div>  
                <?php }}?>        

            </div>

            <!-- <div class="row">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                        </li>
                    </ul>
                </nav>
            </div> -->
        </div>    
        
        <!-- JavaScript Bundle with Popper -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>                        
        <script src="js/app.js"></script>   
        
       
    </body>
</html>