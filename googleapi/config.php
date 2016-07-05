<?php
session_start();
include_once("src/Google_Client.php");
include_once("src/contrib/Google_Oauth2Service.php");
######### edit details ##########
$clientId = '649667848227-uetg3tj4t415k7iakukd7kl8tbqgkm8j.apps.googleusercontent.com'; //Google CLIENT ID
$clientSecret = 'uXVPk-SOD8M-2vMcwf0GOV-3'; //Google CLIENT SECRET
$redirectUrl = 'http://localhost/twitterbots/Bragadeesh/index.php';  //return url (url to script)
$homeUrl = 'http://localhost/twitterbots/Bragadeesh';  //return to home

##################################

$gClient = new Google_Client();
$gClient->setApplicationName('Login to codexworld.com');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectUrl);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>