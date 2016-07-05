<?php
session_start();
//require_once 'dbcontroller.php';

ini_set('include_path', '/opt/lampp/htdocs/twitterbots/google-api-php-client-master/src');

//Google API PHP Library includes
require_once 'Google/Client.php';
require_once '/opt/lampp/htdocs/twitterbots/login-with-google-using-php/src/auth/Oauth2.php';

// Fill CLIENT ID, CLIENT SECRET ID, REDIRECT URI from Google Developer Console
$client_id = '6593163340-d658h0cpu1c45d829ce0ffkug5ta4d86.apps.googleusercontent.com';
$client_secret = 'nWcx0OhP-RbaMfECg4oxggBn';
$redirect_uri = 'http://localhost/twitterbots';
$simple_api_key = 'AIzaSyB82zdBMsrXRJ4FqFeLnis5jCTLoZLj_1c';

//Create Client Request to access Google API
$client = new Google_Client();
$client->setApplicationName("PHP Google OAuth Login Example");
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->setDeveloperKey($simple_api_key);
$client->addScope("https://www.googleapis.com/auth/userinfo.email");

//Send Client Request
$objOAuthService = new Google_Service_Oauth2($client);

//Logout
if (isset($_REQUEST['logout'])) {
    unset($_SESSION['access_token']);
    $client->revokeToken();
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL)); //redirect user back to page
}

//Authenticate code from Google OAuth Flow
//Add Access Token to Session
if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

//Set Access Token to make Request
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
}

//Get User Data from Google Plus
//If New, Insert to Database
if ($client->getAccessToken()) {
    $userData = $objOAuthService->userinfo->get();
    if(!empty($userData)) {
        $objDBController = new DBController();
        $existing_member = $objDBController->getUserByOAuthId($userData->id);
        if(empty($existing_member)) {
            $objDBController->insertOAuthUser($userData);
        }
    }
    $_SESSION['access_token'] = $client->getAccessToken();
} else {
    $authUrl = $client->createAuthUrl();
}
require_once("loginpageview.php");
?>

<HTML>
<HEAD>

    <style>
        .box {font-family: Arial, sans-serif;background-color: #F1F1F1;border:0;width:340px;webkit-box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.3);box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.3);margin: 0 auto 25px;text-align:center;padding:10px 0px;}
        .box img{padding: 10px 0px;}
        .box a{color: #427fed;cursor: pointer;text-decoration: none;}
        .heading {text-align:center;padding:10px;font-family: 'Open Sans', arial;color: #555;font-size: 18px;font-weight: 400;}
        .circle-image{width:100px;height:100px;-webkit-border-radius: 50%;border-radius: 50%;}
        .welcome{font-size: 16px;font-weight: bold;text-align: center;margin: 10px 0 0;min-height: 1em;}
        .oauthemail{font-size: 14px;}
        .logout{font-size: 13px;text-align: right;padding: 5px;margin: 20px 5px 0px 5px;border-top: #CCCCCC 1px solid;}
        .logout a{color:#8E009C;}
    </style>
</HEAD>
<BODY>
<div class="heading">PHP Google OAuth 2.0 Login</div>
<div class="box">
    <div>
        <!-- Show Login if the OAuth Request URL is set -->
        <?php if (isset($authUrl)): ?>
            <img src="images/user.png" width="100px" size="100px" /><br/>
            <a class='login' href='<?php echo $authUrl; ?>'><img class='login' src="images/sign-in-with-google.png" width="250px" size="54px" /></a>
            <!-- Show User Profile otherwise-->
        <?php else: ?>
            <img class="circle-image" src="<?php echo $userData["picture"]; ?>" width="100px" size="100px" /><br/>
            <p class="welcome">Welcome <a href="<?php echo $userData["link"]; ?>" /><?php echo $userData["name"]; ?></a>.</p>
            <p class="oauthemail"><?php echo $userData["email"]; ?></p>
            <div class='logout'><a href='?logout'>Logout</a></div>
        <?php endif ?>
    </div>
</div>
</BODY>
</HTML>
</HTML>
