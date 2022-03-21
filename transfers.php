<?php


    $apikey = '77f737c122315d4c9292521b53b467c8';
    $secret = 'd9658e68eb';
    $date = time();
    
    $token = $apikey.$secret.$date;
    $Xsignature = hash('sha256', $token, false );

    //Obtener Lista de Terminales aereas

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.test.hotelbeds.com//transfer-cache-api/1.0/locations/terminals?fields=ALL&language=es&codes=TFS',
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
        'X-Signature: e900af91d32659b0bee7eb65d40a1953727cf90f7270ae89aa546a4a7914b19a',
        'Cookie: BIGipServer~Public~pool_activity-cache-api_int=rd1o00000000000000000000ffff0ade3050o8080; BIGipServer~Public~pool_transfer-api_int=rd1o00000000000000000000ffff0ade302bo8080; BIGipServer~Public~pool_transfer-cache-api_int=rd1o00000000000000000000ffff0ade3075o8080'
    ),
    ));

    $response = curl_exec($curl);
    if ($response === false) {
        $info = curl_getinfo($curl);
        curl_close($curl);
        die('Error al consumir el servicio web terminals. Informacion Adicional: ' . var_export($info));
    }

    curl_close($curl);

    $terminals = json_decode($response);

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
            'Api-key: '.$apikey.'',
            'X-Signature: '.$Xsignature.'',
            'X-Signature: f032cda227df14a5d3508ee3517f53999272603d60509664d7e552ca0b8a9cf1',
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


// Obtener las Rutas de Transporte a partir del Hotel seleccionado    
    if(isset($_POST['submit'])){

        $date = substr($_POST['dateFrom'], 0, 19);
        if(isset($_POST['roundtrip']) == 'on'){
            $date=$date.'/'.substr($_POST['dateTo'], 0, 19);
        }

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.test.hotelbeds.com//transfer-api/1.0/availability/'.$_POST['language'].'/'.'from/'.$_POST['typeFrom'].'/'.$_POST['from'].'/'.'to/'.$_POST['typeTo'].'/'.$_POST['to'].'/'.$date.'/'.$_POST['adults'].'/'.$_POST['childrens'].'/'.$_POST['infants'].'',
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
        if ($response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('Error al consumir el servicio web. Informacion Adicional: ' . var_export($info));
        }

        curl_close($curl);

        $decoded = json_decode($response);
        if(isset($decoded->services)){
            $route = $decoded->search;
            $transfers = $decoded->services;
        }       
        else{
            $transfers= []; 
        } 

    // echo "<PRE>";
    // print_r($transfers);
    // echo "</PRE>";
    // die();
 

    } 
       






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
 

?>


<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Transfers</title>

            <!-- CSS only -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet"> -->

            <!-- Tempus Dominus Styles -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempus-dominus/6.0.0-beta2/css/tempus-dominus.css" integrity="sha512-nYyjqNXfY5IWOHku56FIiRntoIbMTDH//ZKcmW6KE4uTPvnLtKdz/UcwfluGSaDc0ALkuQbNUYngjDtQKMsN7Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
           
            

        </head>
		<body>

        <div class="container">

        <div class="row">
            <div class="col">
                <a href="index.php">Home</a>
                <a href="activities.php">Activities</a>
                <a href="routesportfolio.php">ROUTES Portfolio</a>
            </div>
        </div>

            <div class="row">
                <div class="col p-5">
                <div class="card">
                    <div class="card-header">
                        Transfers Seach 
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <fieldset>
                                    <div class="row">
                                        <div class="col-2">
                                            <label  class="form-label">Language</label>
                                            <select class="form-control" aria-label="Default select example" name="language">
                                                <option value="en" <?php if(isset($_POST['language']) && $_POST['language'] == 'en'){echo 'selected';};?> >English</option>
                                                <option value="es" <?php if(isset($_POST['language']) && $_POST['language'] == 'es'){echo 'selected';};?> >Spanish</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-2">
                                            <label class="form-label">Adults(+18)</label>
                                            <input type="number" name="adults" class="form-control"  min="0" max="30" value='0'>
                                        </div>
                                        <div class="col-2">
                                            <label  class="form-label">Children(4~17)</label>
                                            <input type="number" name="childrens" class="form-control" min="0" max="30" value='0'>
                                        </div>
                                        <div class="col-2">
                                            <label  class="form-label">Infants(0~3)</label>
                                            <input type="number" name="infants" class="form-control" min="0" max="30" value='0'>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-1">
                                            <label  class="form-label">Types</label>
                                            <select class="form-control" aria-label="Default select example" name="typeFrom" id="typeFrom"> 
                                                <option value="IATA">Airport</option>
                                                <option value="ATLAS" selected>Hotel</option>
                                                <option value="PORT">Port</option>
                                               
                                            </select>
                                        </div>
                                        <div class="col-5">
                                            <label  class="form-label">From</label>
                                            <select class="form-control" aria-label="Default select example" id="hotelsFrom" name="from">
                                                <?php foreach($hotelsSort as $key=>$hotel):?>
                                                    <option value="<?php echo $hotelsSort[$key]->code;?>"><?php echo $hotelsSort[$key]->city.' |'.$hotelsSort[$key]->name;?></option>
                                                <?php endforeach?>
                                            </select>
                                            <select class="form-control d-none" aria-label="Default select example" id="terminalFrom">
                                                <?php foreach($terminals as $key=>$terminal):?>
                                                    <option value="<?php echo $terminals[$key]->code;?>"><?php echo $terminals[$key]->content->description;?></option>
                                                <?php endforeach?>
                                            </select>
                                            <select class="form-control d-none" aria-label="Default select example" id="portFrom">
                                                <option value="PTFS">Tenerife, Puerto De Tenerife Sur</option>
                                                <option value="PTFN">Tenerife, Puerto De Tenerife Norte</option>
                                            </select>
                                        </div>

                                        <div class="col-1">
                                            <label  class="form-label">Types</label>
                                            <select class="form-control" aria-label="Default select example" name="typeTo" id="typeTo">
                                                <option value="IATA" selected>Airport</option>
                                                <option value="ATLAS">Hotel</option>
                                                <option value="PORT">Port</option>
                                            </select>
                                        </div>
                                        <div class="col-5">
                                            <label  class="form-label">To</label>
                                            <select class="form-control d-none" aria-label="Default select example" id="hotelsTo">
                                                <?php foreach($hotelsSort as $key=>$hotel):?>
                                                    <option value="<?php echo $hotelsSort[$key]->code;?>"><?php echo $hotelsSort[$key]->city.' |'.$hotelsSort[$key]->name;?></option>
                                                <?php endforeach?>
                                            </select>
                                            <select class="form-control" aria-label="Default select example" id="terminalTo"  name="to">
                                                <?php foreach($terminals as $key=>$terminal):?>
                                                    <option value="<?php echo $terminals[$key]->code;?>"><?php echo $terminals[$key]->content->description;?></option>
                                                <?php endforeach?>
                                            </select>
                                            <select class="form-control d-none" aria-label="Default select example" id="portTo">
                                                <option value="PTFS">Tenerife, Puerto De Tenerife Sur</option>
                                                <option value="PTFN">Tenerife, Puerto De Tenerife Norte</option>
                                            </select>
                                        </div>

                                        
                                    </div>
                                    <div class="row justify-content-left">
                                        <div class="col-2 py-3">          
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="oneway" id="flexRadioDefault1">
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    One way
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="roundtrip" id="flexRadioDefault2">
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    Round trip
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-3 d-none" id="dateFrom">
                                            <label  class="form-label">From</label>
                                            <div class='input-group' id='datetimepicker1' data-td-target-input='nearest' data-td-target-toggle='nearest'>
                                                <input id='datetimepicker1Input' type='text' class='form-control' data-td-target='#datetimepicker1' name="dateFrom"/>
                                                <span class='input-group-text' data-td-target='#datetimepicker1' data-td-toggle='datetimepicker'>
                                                    <span class='fas fa-calendar'></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-3 d-none" id="dateTo">
                                            <label  class="form-label">To</label>
                                            <div class='input-group' id='datetimepicker2' data-td-target-input='nearest' data-td-target-toggle='nearest'>
                                                <input id='datetimepicker1Input' type='text' class='form-control' data-td-target='#datetimepicker2' name="dateTo"/>
                                                <span class='input-group-text' data-td-target='#datetimepicker2' data-td-toggle='datetimepicker'>
                                                    <span class='fas fa-calendar'></span>
                                                </span>
                                            </div>
                                        </div>  
                                    
                                    </div>
                                    <hr>
                                <button type="submit" name="submit" value="Submit Form" class="btn btn-primary">Seach</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
                
            </div>
                                                      
            <div class="row">
                <?php if(isset($transfers) && count($transfers) > 0){?>
                    <div class="col">
                        <h4><?php echo 'From: '.$route->from->description.'  To: '.$route->to->description?></h4>
                    </div> 
                    <div class="row">
                        <div class="col">                                   
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Vehicle</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Description</th>
                                            <th scope="col"></th>
                                            
                                            
                                        </tr>
                                    </thead>  
                                    <tbody>
                                        <?php						  						
                                            foreach ($transfers as $key=>$transfer) 
                                            {
                                            
                                        ?>
                                            <tr>
                                                <td><?php echo $transfers[$key]->vehicle->name; ?></td>
                                                <td><?php echo $transfers[$key]->category->name; ?></td>
                                                <td><?php echo $transfers[$key]->price->totalAmount; ?></td>
                                                <td><?php echo $transfers[$key]->content->transferRemarks[0]->description; ?></td>
                                                <td><a href="">Details</a></td>
                                        </tr>	
                                        <?php } ?>				  
                                    </tbody> 
                                </table> 
                            <?php }
                            else{
                                if(isset($transfers) && count($transfers) <= 0){?> 
                                <div class="alert alert-warning" role="alert">
                                    Revice los parametros insertados
                                </div>  
                        </div>
                    </div>      
                <?php }}?>    
            </div>

                
        </div>    
        
        <!-- JavaScript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
        
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
         -->

        <!-- Popperjs -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
        <!-- Tempus Dominus JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tempus-dominus/6.0.0-beta2/js/tempus-dominus.js" integrity="sha512-kJJK0IU5vz3SXPCFsYEAdl8U6TPb5KMhQCbwuJgY7yti4gX7sNOh09T0UV7Ol2+OtVEnLi0NLfpIG080jU8tng==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        
        <script src="js/app.js"></script>   

        <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/solid.min.js'
        integrity='sha512-Qc+cBMt/4/KXJ1F6nNQahXIsgPygHM4S2XWChoumV8qkpZ9oO+gBDBEpOxgbkQQ/6DlHx6cUxa5nBhEbuiR8xw=='
        crossorigin='anonymous' referrerpolicy='no-referrer'></script>
<script defer='' src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/brands.min.js'
        integrity='sha512-vefaKmSAX3XohXhN50vLfnK12TPIO+4uRpHjXVkX726CqbicEiAQGRzsMTE+EpLkBk4noUcUYu6AQ5af2vfRLA=='
        crossorigin='anonymous' referrerpolicy='no-referrer'></script>
<script defer='' src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/fontawesome.min.js'
        integrity='sha512-KCwrxBJebca0PPOaHELfqGtqkUlFUCuqCnmtydvBSTnJrBirJ55hRG5xcP4R9Rdx9Fz9IF3Yw6Rx40uhuAHR8Q=='
        crossorigin='anonymous' referrerpolicy='no-referrer'></script>

       
    </body>
</html>