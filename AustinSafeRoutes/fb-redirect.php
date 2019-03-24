<?php
    require_once "facebookConfig.php";
    
    try {
        $accessToken = $helper->getAccessToken();
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    
    if (!$accessToken) {
        header('Location: login.php');
        exit();
    }
    
    // Logged in
    // echo '<h3>Access Token</h3>';
    // var_dump($accessToken->getValue());
    
    // The OAuth 2.0 client handler helps us manage access tokens
    $oAuth2Client = $fb->getOAuth2Client();
    
    // Get the access token metadata from /debug_token
    $tokenMetadata = $oAuth2Client->debugToken($accessToken);
    // echo '<h3>Metadata</h3>';
    // var_dump($tokenMetadata);
    
    // Validation (these will throw FacebookSDKException's when they fail)
    $tokenMetadata->validateAppId('338918216884566'); // Replace {app-id} with your app id
    // If you know the user ID this access token belongs to, you can validate it here
    //$tokenMetadata->validateUserId('123');
    $tokenMetadata->validateExpiration();
    
    if (!$accessToken->isLongLived()) {
      // Exchanges a short-lived access token for a long-lived one
      try {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
      } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
        exit;
      }
    
      echo '<h3>Long-lived</h3>';
      var_dump($accessToken->getValue());
    }
    
    $response = $fb->get("/me?fields=id, first_name, last_name, email, picture.type(small)", $accessToken);
    $userData = $response->getGraphNode()->asArray();
    // echo "<pre>";
    // var_dump($userData);
    
    // save necessary data to session
    // and redirect user to saveRoutes.php
    $_SESSION['userData'] = $userData;
    $_SESSION['access_token'] = (string) $accessToken;
    $_SESSION['userPicture'] = $userData['picture']['url'];
    $_SESSION['facebookLoggedIn'] = 1;
    header('Location: saveRoutes.php');
    exit();
?>