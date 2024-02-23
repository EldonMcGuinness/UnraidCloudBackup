<?php
session_set_cookie_params(300);
session_start();

$_SESSION['oAuthName'] = base64_decode($_GET['oAuthName']);
$_SESSION['oAuthCallback'] = base64_decode($_GET['oAuthCallback']);
$_SESSION['oAuthSource'] = base64_decode($_GET['oAuthSource']);
$_SESSION['oAuthProvider'] = base64_decode($_GET['oAuthProvider']);
$_SESSION['oAuthClientId'] = base64_decode($_GET['oAuthClientId']);
$_SESSION['oAuthClientSecret'] = base64_decode($_GET['oAuthClientSecret']);
$_SESSION['oAuthCode'] = null;

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<title>UnRaid Cloud Backup oAuth2 Client</title>
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
  	<p>
    	Only continue if the server you want to link has the following URL:<br/>
		<b><?=$_SESSION['oAuthSource']?></b>
  	</p>
    <div id="debug"></div>
  	<button onclick="javascript:init();"><b>Authenticate</b></button>    
</body>
<script type="text/javascript">


class OAuth {
	#provider;
	#authUri;
	#tokenUri;
	#clientId;
	#clientSecret;
	#authParameter;
	#tokenParameter;

	#providers = [
		'dropbox',
		'googledrive',
		'box',
	
	];

	#authUris = {
		dropbox: 'https://www.dropbox.com/oauth2/authorize',
		googledrive: 'https://accounts.google.com/o/oauth2/v2/auth',
		box: 'https://account.box.com/api/oauth2/authorize',

	};

	#tokenUris = {
		dropbox: 'https://www.dropbox.com/oauth2/token',
		googledrive: 'https://oauth2.googleapis.com/token',
		box: 'https://api.box.com/oauth2/token',

	};
	
	#authParameters = {
		box: {
			client_id: null,
			redirect_uri: 'https://cloudbackup.progressivethink.in/UnraidCloudBackup/callback', // The URL where you is redirected back, and where you perform run the callback() function,
			response_type: 'code',
			access_type: 'offline',
			scope: 'root_readwrite',
		},
		googledrive: {
			client_id: null,
			redirect_uri: 'https://cloudbackup.progressivethink.in/UnraidCloudBackup/callback', // The URL where you is redirected back, and where you perform run the callback() function,
			response_type: 'code',
			scope: 'https://www.googleapis.com/auth/drive',
			access_type: 'offline',
		},
		dropbox: {
			client_id: null,
			redirect_uri: 'https://cloudbackup.progressivethink.in/UnraidCloudBackup/callback', // The URL where you is redirected back, and where you perform run the callback() function,
			token_access_type: "offline",
			response_type: 'code',
		}
	};

	#tokenParameters = {
		box: {
			grant_type: 'authorization_code',
			code: null,
			redirect_uri: 'https://cloudbackup.progressivethink.in/UnraidCloudBackup/callback',
			client_id: null,
			client_secret: null,
		},
		googledrive: {
			grant_type: 'authorization_code',
			code: null,
			redirect_uri: 'https://cloudbackup.progressivethink.in/UnraidCloudBackup/callback',
			client_id: null,
			client_secret: null,
		},
		dropbox: {
			grant_type: 'authorization_code',
			code: null,
			redirect_uri: 'https://cloudbackup.progressivethink.in/UnraidCloudBackup/callback',
			client_id: null,
			client_secret: null,
		}
	};

	constructor(provider, clientId, clientSecret = null, code = null) {

		// Check if this is a valid provider
		if (
			typeof(provider) == "string" &&
			Object.values(this.#providers).includes(provider.toLowerCase())
		){
			this.#provider = provider.toLowerCase();
		
		} else {
			throw Error('Invalid Provider: ' + provider);
		
		}
		
		// Populate the needed URIs
		this.#authUri = this.#authUris[ this.#provider ];
		this.#tokenUri = this.#tokenUris[ this.#provider ];
		this.#authParameter = this.#authParameters[ this.#provider ];
		this.#authParameter.client_id = clientId;
		//this.#authParameter.client_secret = clientSecret;
		//this.#authParameter.code = code;
		this.#tokenParameter = this.#tokenParameters[ this.#provider ];
		this.#tokenParameter.client_id = clientId;
		this.#tokenParameter.client_secret = clientSecret;
		this.#tokenParameter.code = code;
		this.#clientId = clientId;
		this.#clientSecret = clientSecret;

	}

	#objToQuery( params ) {
		let query = "";

		for ( let key in params ){
			if ( query !== "" ){
				query += "&";
			}

			query += key + "=" + params[key];

		}

		return query;

	}

	authCall() {
		let auth = this.#authUri + '?' + this.#objToQuery(this.#authParameter);
		return auth;

	}

	tokenCall() {
		return [
			$this.#tokenUri,
			$this.#tokenParameter
		];
	}

}

let oAuth = new OAuth('<?=$_SESSION['oAuthProvider']?>', '<?=$_SESSION['oAuthClientId']?>');

function init() {
	//document.getElementById('debug').innerText = oAuth.authCall();
	window.location.replace( oAuth.authCall() );

}

</script>
</html>