<?php
session_set_cookie_params(300);
session_start();
$_SESSION['oAuthCode'] = $_GET['code'];

 class OAuth {
	private $provider,
			$authUri,
			$tokenUri,
			$authParameter,
			$clientId,
			$clientSecret,
			$tokenParameter;

	private $providers = [
		'dropbox',
		'googledrive',
		'box',
	];

	private $authUris = [
		'box' => 'https://account.box.com/api/oauth2/authorize',
		'dropbox' => 'https://www.dropbox.com/oauth2/authorize',
		'googledrive' => 'https://accounts.google.com/o/oauth2/auth',
	];

	private $tokenUris = [
		'box' => 'https://api.box.com/oauth2/token',
		'dropbox' => 'https://www.dropbox.com/oauth2/token',
		'googledrive' => 'https://accounts.google.com/o/oauth2/token',
	];

	private $authParameters = [
		'box' => [
			'client_id' => null,
			'redirect_uri' => 'https://cloudbackup.progressivethink.in/UnraidCloudBackup/callback', // The URL where you is redirected back, and where you perform run the callback() function,
			'response_type' => 'code',
			'access_type' => 'offline',
			'scope' => 'root_readwrite',
		],
		'googledrive' => [
			'client_id' => null,
			'redirect_uri' => 'https://cloudbackup.progressivethink.in/UnraidCloudBackup/callback', // The URL where you is redirected back, and where you perform run the callback() function,
			'response_type' => 'code',
			'scope' => 'https://www.googleapis.com/auth/drive',
			'access_type' => 'offline',
		],
		'dropbox' => [
			'client_id' => null,
			'redirect_uri' => 'https://cloudbackup.progressivethink.in/UnraidCloudBackup/callback', // The URL where you is redirected back, and where you perform run the callback() function,
			'token_access_type' => 'offline',
			'response_type' => 'code',
		]
	];

	private $tokenParameters = [
		'box' => [
			'grant_type' => 'authorization_code',
			'code' => null,
			'redirect_uri' => 'https://cloudbackup.progressivethink.in/UnraidCloudBackup/callback',
			'client_id' => null,
			'client_secret' => null,
		],
		'googledrive' => [
			'grant_type' => 'authorization_code',
			'code' => null,
			'redirect_uri' => 'https://cloudbackup.progressivethink.in/UnraidCloudBackup/callback',
			'client_id' => null,
			'client_secret' => null,
		],
		'dropbox' => [
			'grant_type' => 'authorization_code',
			'code' => null,
			'redirect_uri' => 'https://cloudbackup.progressivethink.in/UnraidCloudBackup/callback',
			'client_id' => null,
			'client_secret' => null,
		]
	];

	function __construct($provider, $clientId, $clientSecret = null, $code = null){
		
		// Check if this is a valid provider
		if (
			gettype($provider) == "string" &&
			in_array(strtolower($provider), $this->providers)
		){
			$this->provider = strtolower($provider);
		
		} else {
			throw new Exception('Invalid Provider');
		
		}
	
		// Populate the needed URIs
		$this->authUri = $this->authUris[ $this->provider ];
		$this->tokenUri = $this->tokenUris[ $this->provider ];
		$this->authParameter = $this->authParameters[ $this->provider ];
		$this->authParameter['client_id'] = $clientId;
		//$this->authParameter['client_secret'] = $clientSecret;
		//$this->authParameter['code'] = $code;
		$this->tokenParameter = $this->tokenParameters[ $this->provider ];
		$this->tokenParameter['client_id'] = $clientId;
		$this->tokenParameter['client_secret'] = $clientSecret;
		$this->tokenParameter['code'] = $code;
		$this->clientId = $clientId;
		$this->clientSecret = $clientSecret;
	}


	private function objToQuery( $params ) {
		$query = "";

		foreach ( $params as $key => $val ){
			if ( $query !== "" ){
				$query .= "&";
			}

			$query .= $key . "=" . $val;

		}

		return $query;

	}

	public function authCall() {
		$auth = $this->authUri . '?' . $this->objToQuery($this->authParameter);
		return $auth;
	}

	public function tokenCall() {
		return [
			$this->tokenUri,
			$this->tokenParameter
		];
	}
}

$oAuth = new OAuth( $_SESSION['oAuthProvider'], $_SESSION['oAuthClientId'], $_SESSION['oAuthClientSecret'], $_SESSION['oAuthCode'] );

[$curlURL, $curlParams] = $oAuth->tokenCall();

$defaults = array(
	CURLOPT_URL => $curlURL, 
	CURLOPT_POST => true,
	CURLOPT_POSTFIELDS => $curlParams,
	CURLOPT_RETURNTRANSFER => true,

);

$ch = curl_init();

curl_setopt_array($ch, $defaults);

//TODO add error checking
$result = curl_exec($ch);

curl_close($ch);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<title>UnRaid Cloud Backup oAuth2 Client Callback</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../css/style.css"></style>
</head>

<body class="container">
	<div>
		<img src="../images/server-backup-icon-256.png" />
	</div>
  	<h1>oAuth2 Client</h1>
	Please wait while we fetch your token!
	<div id="debug"></div>
</body>
<script type="text/javascript" src="querystring.js"></script>
<script type="text/javascript">
let hash = window.location.hash.replace('#','?');

function objToQuery( params ) {
	let query = "";

	for ( let key in params ){
    	if ( query !== "" ){
        	query += "&";
        }
    	query += key + "=" + params[key];
    }

	return query;
}

function checkHash( hash ){
	if ( hash === "" ){
		return false;
	}

	return true;
}

window.location.href = "<?=$_SESSION["oAuthCallback"]?>?" + objToQuery(<?=$result?>) + "&oAuthProvider=<?=$_SESSION["oAuthProvider"]?>&oAuthName=<?=$_SESSION["oAuthName"]?>&oAuthClientId=<?=$_SESSION["oAuthClientId"]?>&oAuthClientSecret=<?=$_SESSION["oAuthClientSecret"]?>";



</script>
</html>
<?php
session_unset();
session_destroy();
session_write_close();
$_SESSION = array();
setcookie(session_name(),'',0,'/');

?>