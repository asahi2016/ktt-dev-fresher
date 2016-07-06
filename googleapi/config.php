<?php
session_start();

include_once(DOCUMENT_ROOT_DIR. "/googleapi/src/Google_Client.php");
include_once(DOCUMENT_ROOT_DIR ."/googleapi/src/contrib/Google_Oauth2Service.php");
######### edit details ##########
$clientId = CLIENT_API_KEY; //Google CLIENT ID
$clientSecret = CLIENT_SECRET_KEY; //Google CLIENT SECRET
$redirectUrl = REDIRECT_URL;  //return url (url to script)
$homeUrl = HOME_URL;  //return to home

##################################

$gClient = new Google_Client();
$gClient->setApplicationName('Login to codexworld.com');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectUrl);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>