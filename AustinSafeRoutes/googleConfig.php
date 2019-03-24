<?php
    session_start();
    require_once "google-api-php-client-2.2.2/vendor/autoload.php";
    
    $googleClient = new Google_Client();
    $googleClient->setClientId("246604600792-7c2g8dqn0onrjf1qnec253dp08chd8tl.apps.googleusercontent.com");
    $googleClient->setClientSecret("ZPKNAc4haOIyLs-el0WNfV4R");
    $googleClient->setApplicationName("Austin SafeRoutes");
    $googleClient->setRedirectUri("https://asolinge.create.stedwards.edu/AustinSafeRoutes/google-redirect.php");
    $googleClient->setScopes("email");
?>