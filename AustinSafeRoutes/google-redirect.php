<?php
    require_once "googleConfig.php";
    
    if (isset($_GET['code'])) {
        $token = $googleClient->fetchAccessTokenWithAuthCode($_GET['code']);
        $_SESSION['access_token'] = $token;
    }
    
    $oAuth = new Google_Service_Oauth2($googleClient);
    $userData = $oAuth->userinfo_v2_me->get();
    
    // echo "<pre>";
    // var_dump($userData);
    
    $_SESSION['userData'] = $userData;
    $_SESSION['userPicture'] = $userData['picture'];
    $_SESSION['googleLoggedIn'] = 1;
    
    header('Location: saveRoutes.php');
    exit();

    
?>