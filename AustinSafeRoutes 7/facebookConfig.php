<?php
    session_start();
    require_once "Facebook/autoload.php";
    
    $fb = new \Facebook\Facebook([
        'app_id' => '338918216884566',
        'app_secret' => '51ba938e9c9c07d49a9e85641b19ad62',
        'default_graph_verion' => 'v2.10'
        
        ]);
        
        $helper = $fb->getRedirectLoginHelper();

?>