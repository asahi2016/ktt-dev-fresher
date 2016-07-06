<?php
define("CLIENT_API_KEY", '649667848227-uetg3tj4t415k7iakukd7kl8tbqgkm8j.apps.googleusercontent.com');
define("CLIENT_SECRET_KEY", 'uXVPk-SOD8M-2vMcwf0GOV-3');
define("REDIRECT_URL", 'http://localhost/twitterbots/Bragadeesh/index.php');
define("HOME_URL", 'http://localhost/twitterbots/Bragadeesh');
define("DOCUMENT_ROOT_DIR" , '/opt/lampp/htdocs/twitterbots');


include_once("/opt/lampp/htdocs/twitterbots/googleapi/config.php");
include_once("/opt/lampp/htdocs/twitterbots/googleapi/includes/functions.php");

//print_r($_GET);die;
//unset($_SESSION['token']);
if(isset($_REQUEST['code'])){
	$gClient->authenticate();
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($redirectUrl, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
	$gClient->setAccessToken($_SESSION['token']);
}

if ($gClient->getAccessToken()) {
	$userProfile = $google_oauthV2->userinfo->get();

	//DB Insert
	$gUser = new Users();
	$gUser->checkUser('google',$userProfile['id'],$userProfile['given_name'],$userProfile['family_name'],$userProfile['email'],$userProfile['gender'],$userProfile['locale'],$userProfile['link'],$userProfile['picture']);
	$_SESSION['google_data'] = $userProfile; // Storing Google User Data in Session
	header("location: account.php");
	$_SESSION['token'] = $gClient->getAccessToken();
} else {
	$authUrl = $gClient->createAuthUrl();
}

if(isset($authUrl)) {
	echo '<a href="'.$authUrl.'"><img src="/opt/lampp/htdocs/twitterbots/Bragadeesh/glogin.png" alt=""/></a>';
} else {
	echo '<a href="logout.php?logout">Logout</a>';
}

?>