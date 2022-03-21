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
                                {"type": "country", "value": "ES"},
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
                                    <div class="col-1">
                                        <label class="form-label">Country</label>
                                        <input type="text" name="country" class="form-control" placeholder="España" readonly>
                                    </div>
                                    <div class="col-2">
                                        <label  class="form-label">Destination</label>
                                        <input type="text" name="destination" class="form-control" placeholder="Sta. Cruz de Tenerife" readonly>
                                    </div>
                                    <div class="col-2">
                                        <label  class="form-label">Language</label>
                                        <select class="form-control" aria-label="Default select example" name="language">
                                            <option value="en" <?php if(isset($_POST['language']) && $_POST['language'] == 'en'){echo 'selected';};?> >English</option>
                                            <option value="es" <?php if(isset($_POST['language']) && $_POST['language'] == 'es'){echo 'selected';};?> >Spanish</option>
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

            <div class="row">
                <?php if(isset($activities) && count($activities) > 0){?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Activity</th>
                                <th scope="col">Modality</th>
                                <th scope="col">Price</th>
                                <th scope="col">Description</th>
                                <th scope="col"></th>
                                
                                
                            </tr>
                        </thead>  
                        <tbody>
                            <?php						  						
                                foreach ($activities as $key=>$activity) 
                                {
                                    $duration = $activities[$key]->modalities[0]->duration;
                            ?>
                                <tr>
                                    <td><?php echo $activities[$key]->name; ?></td>
                                    <td><?php echo $activities[$key]->modalities[0]->name; ?></td>
                                    <td><?php echo $activities[$key]->amountsFrom[0]->amount; ?></td>
                                    <td><?php echo $activities[$key]->content->description; ?></td>
                                    
                                    <td><a href="activityDetails.php?code=<?php echo $activities[$key]->code; ?>&lang=<?php echo $_POST['language'];?>&from=<?php echo $_POST['from'];?>&to=<?php echo $_POST['to'];?>">Details</a></td>
                                    
                            </tr>	
                            <?php } ?>				  
                        </tbody> 
                    </table> 
                <?php }
                else{
                    if(isset($activities) && count($activities) <= 0){?> 
                       <div class="alert alert-warning" role="alert">
                         Revice los parametros insertados
                       </div>  
                <?php }}?>    
            </div>    
        </div>    
        
        <!-- JavaScript Bundle with Popper -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>                        
        <script src="js/app.js"></script>                        
    </body>
</html>