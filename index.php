<?php

if (isset($_POST['submit'])) {

  $addressInput = !empty($_POST['addressInput']) ? $_POST['addressInput'] : NULL;

  if (isset($addressInput)) {
    require 'vendor/autoload.php';

    $geocoder = new \Geocoder\Geocoder();
    $adapter  = new \Geocoder\HttpAdapter\CurlHttpAdapter();

    $geocoder->registerProviders(array(
      new \Geocoder\Provider\GoogleMapsProvider($adapter),
      ));

    $provider = new \Geocoder\Provider\FreeGeoIpProvider($adapter);

    try {
      $geocode = $geocoder->geocode($addressInput);
    } catch (Exception $e) {
      echo $e->getMessage();
    }

  }

  if (isset($geocode)) {

    $param_baseurl   = 'http://api.nytimes.com/svc/events/v2/listings.sphp?';
    $param_latitude  = $geocode->getLatitude();
    $param_longitude = $geocode->getLongitude();
    $param_radius    = '1000';
    $param_limit     = '20';
    $param_apikey    = 'd3ce9f86fec65789fe095489f2cd1a7f:15:61046211';
    $params =
      $param_baseurl .
      'll=' . $param_latitude . ',' . $param_longitude .
      '&radius=' . $param_radius .
      '&limit=' . $param_limit .
      '&api-key=' . $param_apikey;

    $ch = curl_init( $params );
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    $response = curl_exec( $ch );
  }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <title>Eventr</title>
  <meta name="description" content="A web service for listing nearby events using your location provided by the NYTimes Events API">
  <meta name="author" content="Alex Ho">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href='//fonts.googleapis.com/css?family=Raleway:400,300,600' rel='stylesheet' type='text/css'>

  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/skeleton.css">
  <link rel="stylesheet" href="css/main.css">

  <link rel="icon" type="image/png" href="images/favicon.png" />

</head>
<body>

  <!-- Primary Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <div class="container">
  <nav class="navbar">
    <div class="container">
      <ul class="navbar-list">
        <li class="navbar-item"><a class="navbar-link" href="#home">Home</a></li>
        <li class="navbar-item"><a class="navbar-link" href="#about">About</a></li>
      </ul>
    </div>
  </nav>
    <div class="row">
      <div style="margin-top: 10%">
        <h4>Enter an address below or share your browser location</h4>
        <form name="submitForm" method="post" action="index.php">
          <div class="row">
            <div class="six columns">
              <label for="addressInput">Address</label>
              <input class="u-full-width" placeholder="620 Eighth Ave, New York, NY" name="addressInput" type="text">
            </div>
          </div>
          <input class="button-primary" name="submit" value="Submit" type="submit">
        </form>
      </div>
    </div>
  </div>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>
