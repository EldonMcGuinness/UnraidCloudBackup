<?php

//https://fs001.massmedia.stream/plugins/CloudBackup/callback.php#access_token=sl.Bvvy8DPuFu0OMVhrQm9S81vAAhOMa3G3py0tsh1p-7xAyfvf8raQW84MhHesohwF3hp88IMoc_KoQo9lh7pF2LWNtz1y0wVxgb2Jk9yLCmqPc2QL7vRXmI2JOI8OIuYQ8QApy2tMXcIhgfc&token_type=bearer&expires_in=14400&scope=account_info.read+file_requests.read+file_requests.write+files.content.read+files.content.write+files.metadata.read+files.metadata.write&uid=200653773&account_id=dbid%3AAAA78cXHRlq7c2aIHO0iuu7mBVGa0btEBJA&state=3260a6ca-bd5d-431e-8c12-3ad64c77ac8f
#Pull some default data
libxml_use_internal_errors(true); # Suppress any warnings from xml errors.

$scriptName = "CloudBackup";
$docroot = $docroot ?? $_SERVER['DOCUMENT_ROOT'] ?: '/usr/local/emhttp';
$pluginRoot = "$docroot/plugins/$scriptName";

// Include DB tools
include_once( "./plugins/$scriptName/includes/rclone.php" );

function createExpiryTimestamp( $add ){
  $startTime = new DateTimeImmutable();
  $interval = DateInterval::createFromDateString($add);
  $endTime = $startTime->add($interval);

  return $endTime->format('Y-m-d\TH:i:s.uP');
}

// Get the provider name
$oAuthProvider = $_GET['oAuthProvider'];

// Get the oAuth data
$oAuthName = $_GET['oAuthName'];
$oAuthClientId = $_GET['oAuthClientId'];
$oAuthClientSecret = $_GET['oAuthClientSecret'];
$oAuthToken = [];

// Parse the query string and separate data
$oAuthToken = tokenParser( $_GET );

function tokenParser( $token ){
  $formatedToken = [];

  $formatedToken["access_token"] = $token['access_token'];
  $formatedToken["token_type"] = $token['token_type'];
  $formatedToken["refresh_token"] = $token['refresh_token'];
  $formatedToken["expiry"] = createExpiryTimestamp('1440 second');
  $formatedToken = json_encode($formatedToken);

return $formatedToken;

}

Rclone::load();

// Add the provider to the rclone config
Rclone::addProvider($oAuthName, [
  'type' => $oAuthProvider,
  'client_id' => $oAuthClientId,
  'client_secret' => $oAuthClientSecret,
  'token' => $oAuthToken
]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Unraid Cloud Backup oAuth2 Client</title>
  <style type="text/css">
  html {
    background: white;
    font-family: "Open Sans", sans-serif;
    font-optical-sizing: auto;
    font-weight: 300;
    font-style: normal;
    font-variation-settings: "wdth" 100;
    text-align: center;
  }

  h1{
    
  }

  .container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  img{
    height:192px;
    width:192px;
  }

  button{
    width: 150px;
    height: 40px;
    border-radius: 8px;
    border: solid 1px #ddd;
    box-shadow: #333 0px 2px 4px;
    cursor: pointer;
    text-shadow: 0px 1px 0px #3333331f;
  }

  button:active{
    box-shadow: inset #333 0px 2px 4px;
  }
  </style>
  <script type="text/javascript">

  </script>
</head>

<body class="container">

  <div>
		<img src="/plugins/<?=$scriptName?>/images/CloudBackup.png" />
	</div>
  <h1>oAuth2 Client</h1>
  <p>
    Authorization Complete!<br/>
    You can close this window if it does not close on its own.
  </p>
  <button onclick="javascript:window.close();">Close</button>    
</body>

</html>