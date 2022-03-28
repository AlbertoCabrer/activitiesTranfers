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
  CURLOPT_URL => 'https://api.test.hotelbeds.com/activity-api/3.0/activities/details/full',
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
// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.test.hotelbeds.com/activity-content-api/3.0/activities/'.$_GET['lang'].'/'.$_GET['code'].'',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'Api-key: '.$apikey.'',
//     'X-Signature: '.$Xsignature.'',
//     'Accept: application/json',
//     'Accept-Encoding: gzip',
//     'Cookie: BIGipServer~Public~pool_activity-cache-api_int=rd1o00000000000000000000ffff0ade3050o8080; BIGipServer~Public~pool_transfer-api_int=rd1o00000000000000000000ffff0ade302bo8080; BIGipServer~Public~pool_transfer-cache-api_int=rd1o00000000000000000000ffff0ade3075o8080'
//   ),
// ));

$response = curl_exec($curl);
if ($response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('Error al consumir el servicio web. Informacion Adicional: ' . var_export($info));
}

curl_close($curl);

$decoded = json_decode($response);
if(isset($decoded->activity->content->media->images)){
    $images = $decoded->activity->content->media->images;
}       
else{
   $images= []; 
} 

// echo "<PRE>";
// print_r($images);
// echo "</PRE>";
// die();


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activity Details</title>

   <!-- CSS only -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

  <div class="container">
        <div class="row">
            <div class="col p-3">
                <a href="activities.php">Activities</a>
            </div>   
        </div>

        <div class="row">
            <div class="col-12 col-md-9">
                <h2 class="fw-light"><?php echo $decoded->activity->name;?></h2>
            </div>
            <div class="col-12 col-md-3 align-self-end">
               <h6 class="text-muted fw-light text-end">Desde</h6>
               <h3 class="text-danger fw-bold text-end"> <?php echo $decoded->activity->amountsFrom[0]->amount.' '.$decoded->activity->currency; ?></h3>
            </div>
            
        </div>
       
        <div class="row justify-content-center">
          <div class="col">
              <div id="carouselExampleControls" class="carousel slide carousel-fad" data-bs-ride="carousel">
                  <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="3" aria-label="Slide 4"></button>
                  </div>
                  <div class="carousel-inner">
                      <?php foreach ($images as $key=>$img):?>
                            <div class="carousel-item <?php if($key == 0): echo 'active'; endif?>">
                                <img src="<?php echo $images[$key]->urls[3]->resource;?>" class="d-block w-100 img-fluid" alt="...">
                            </div>
                      <?php endforeach ?>
                  </div>
                  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                  </button>
              </div>
          </div>
        </div>  

        
        
  </div>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>                        
    <script src="js/app.js"></script>   
</body>
</html>