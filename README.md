# CloudBackup, the simpler plugin

This project consists of two separate parts, a plugin and a server-side component. At this point
the only backup destinations that are supported are:
- GoogleDrive
- Dropbox
- Box

Since the plugin uses [rclone](https://rclone.org/#providers) as the backend, it is possible to expand this
to other providers. If you're interested in requesting a new destination, especially if you have the drive to 
make a *pull request*, please file an [issue](https://github.com/EldonMcGuinness/UnraidCloudBackup/issues/new?assignees=EldonMcGuinness&labels=Backend+Support+Request&projects=&template=request-for-new-backend-destination-support.md&title=%5BBackend+Support+Request%5D+-+PROVIDER+HERE).

## Currently a Beta
This project is currently a beta product, which means I'm looking for testers! If you are interested, you can install the plugin using the following URI until I am happy it is a finished product and have it submitted to the Unraid CA.

# Two, parts?
My original goal was to get this working with an oAuth2 provider, so the need for a server to have a [FQDN](https://en.wikipedia.org/wiki/Fully_qualified_domain_name) arose. To that end, the auth_server folder in this project contains a very simple php/js based app that allows you to complete the handshake. I am currently hosting an instance of it that everyone is free to use at this time, I'm hoping to move ti to a github page in the future.

### Using the provided server
You should be able to complete the oAuth2 handshake using the server I am hosting through github, you only need to register your own app with your oAuth2 based provider. This is free and not terribly difficult. You do need to put [ https://eldonmcguinness.github.io/UnraidCloudBackup/callback ] as a valid *Callback URI*.


### Can I host my own
If you want to, you are free to fork the project and change the server values as you see fit and host your own instance of the server-side
portion.