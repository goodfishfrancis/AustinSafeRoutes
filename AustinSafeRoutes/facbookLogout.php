<?php
    session_start();
    require_once "facebookConfig.php";
    
    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['logout']))
    {
        facbookLogout();
    }

    function facebookLogout() {
        $fbLogoutUrl = $helper->getLogoutUrl($_SESSION['access_token'], "https://asolinge.create.stedwards.edu/AustinSafeRoutes");    
        //session_destory();  
        header("Location: $fbLogoutUrl");
        exit();
    }

?>