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

$activity = $decoded->activity;

$carouselInner=''; 
$carouselIndicators='';
foreach ($images as $key=>$img){

    if($key == 0){
      $carouselInner .= '<div class="carousel-item active">';
      $carouselIndicators .= '<button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="'. $key .'" class="active" aria-current="true" aria-label="Slide '. $key + 1 .'"></button>';
    }
    else{
      $carouselInner .= '<div class="carousel-item">';
      $carouselIndicators .= ' <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="'. $key .'" aria-label="Slide '. $key + 1 .'"></button>';
    }

    $carouselInner .= '<img src="'. $images[$key]->urls[3]->resource .'" class="d-block w-100 img-fluid" alt="..."></div>';

}

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
                    <?php echo $carouselIndicators;?>
                  </div>
                  <div class="carousel-inner">
                      <?php echo $carouselInner;?>
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
        <div class="row">
          <div class="col pt-2">
              <nav id="navbar-example2" class="navbar navbar-light px-3 justify-content-center">
                <!-- <a class="navbar-brand" href="#">Navbar</a> -->
                <ul class="nav nav-pills">
                  <li class="nav-item">
                    <a class="nav-link" href="#scrollspyHeading1">Description</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#scrollspyHeading2">Prices</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#scrollspyHeading3">Details</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#scrollspyHeading4">Cancellations</a>
                  </li>
                </ul>
              </nav>
              <div data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-offset="0" class="scrollspy-example bg-light" tabindex="0">
                <h4 id="scrollspyHeading1"></h4>
                <p class="p-2"><?php echo $activity->content->description;?></p>
                <h4 id="scrollspyHeading2">Prices</h4>
                <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                <h4 id="scrollspyHeading3">Third heading</h4>
                <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                <h4 id="scrollspyHeading4">Fourth heading</h4>
                <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                
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