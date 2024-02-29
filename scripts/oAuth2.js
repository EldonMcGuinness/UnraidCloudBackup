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
		'onedrive',
	
	];

	// Auth urls for the providers
	#authUris = {
		dropbox: 'https://www.dropbox.com/oauth2/authorize',
		googledrive: 'https://accounts.google.com/o/oauth2/auth',
		box: 'https://account.box.com/api/oauth2/authorize',
		onedrive: 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize',
		//onedrive: 'https://login.live.com/oauth20_authorize.srf',

	};

	// Token urls for the providers
	#tokenUris = {
		dropbox: 'https://www.dropbox.com/oauth2/token',
		googledrive: 'https://oauth2.googleapis.com/token',
		box: 'https://api.box.com/oauth2/token',
		onedrive: 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
		//onedrive: 'https://login.live.com/oauth20_token.srf',

	};
	
	// Parameters for the providers auth calls
	#authParameters = {
		box: {
			client_id: null,
			redirect_uri: 'https://eldonmcguinness.github.io/UnraidCloudBackup/callback', // The URL where you is redirected back, and where you perform run the callback() function,
			response_type: 'code',
			access_type: 'offline',
			scope: 'root_readwrite',
		},
		onedrive: {
			client_id: null,
			redirect_uri: 'https://eldonmcguinness.github.io/UnraidCloudBackup/callback', // The URL where you is redirected back, and where you perform run the callback() function,
			response_type: 'code',
			scope: 'offline_access%20User.Read%20Files.Read%20Files.Read.All%20Files.ReadWrite%20Files.ReadWrite.All',
		},
		googledrive: {
			client_id: null,
			redirect_uri: 'https://eldonmcguinness.github.io/UnraidCloudBackup/callback', // The URL where you is redirected back, and where you perform run the callback() function,
			response_type: 'code',
			scope: 'https://www.googleapis.com/auth/drive',
			access_type: 'offline',
		},
		dropbox: {
			client_id: null,
			redirect_uri: 'https://eldonmcguinness.github.io/UnraidCloudBackup/callback', // The URL where you is redirected back, and where you perform run the callback() function,
			token_access_type: "offline",
			response_type: 'code',
		}
	};

	// Parameters for the providers token calls
	#tokenParameters = {
		box: {
			grant_type: 'authorization_code',
			code: null,
			redirect_uri: 'https://eldonmcguinness.github.io/UnraidCloudBackup/callback',
			client_id: null,
			//prompt: 'consent',
			client_secret: null,
		},
		onedrive: {
			grant_type: 'authorization_code',
			code: null,
			redirect_uri: 'https://eldonmcguinness.github.io/UnraidCloudBackup/callback',
			client_id: null,
			//prompt: 'consent',
			client_secret: null,
		},
		googledrive: {
			grant_type: 'authorization_code',
			code: null,
			redirect_uri: 'https://eldonmcguinness.github.io/UnraidCloudBackup/callback',
			client_id: null,
			prompt: 'select_account consent',
			//approval_prompt: 'force',
			client_secret: null,
		},
		dropbox: {
			grant_type: 'authorization_code',
			code: null,
			redirect_uri: 'https://eldonmcguinness.github.io/UnraidCloudBackup/callback',
			client_id: null,
			//prompt: 'consent',
			client_secret: null,
		}
	};

	// Constructor
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
		this.#tokenParameter = this.#tokenParameters[ this.#provider ];
		this.#tokenParameter.client_id = clientId;
		this.#tokenParameter.client_secret = clientSecret;
		this.#tokenParameter.code = code;
		this.#clientId = clientId;
		this.#clientSecret = clientSecret;

	}

	// Convert an object to a query string
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

	// Get the auth URI
	authCall() {
		let auth = this.#authUri + '?' + this.#objToQuery(this.#authParameter);
		return auth;

	}

	// Get the token URI
	tokenCall() {
		return [
			this.#tokenUri,
			this.#tokenParameter
		];
	}

}