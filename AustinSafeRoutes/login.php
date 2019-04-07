<?php
    session_start();
    require_once "facebookConfig.php";
    require_once "googleConfig.php";
    
    // if (isset($_SESSION['access_token'])) {
    //     header('Location: saveRoutes.php');
    //     exit();
    // }
    
    // set page to redirect user to when they log in
    // login should redirect user to saveRoutes.php
    $redirectURL = "https://asolinge.create.stedwards.edu/AustinSafeRoutes/fb-redirect.php";
    
    // permissions are data that we have access through via facebook login
    // we only need access to email
    $permissions = ['email'];
    
    // facebook loginURL will be called when facebook login button is clicked
    $loginURL = $helper->getLoginUrl($redirectURL, $permissions);
    
    // google login url
    $googleLoginURL = $googleClient->createAuthUrl();
    
    // if logout button is clicked, 
    // post request for facebookLogout is submitted
    // log user out of facebook and clear session data
    if(isset($_POST['logout']) and $_SESSION['facebookLoggedIn']){
        $fbLogoutUrl = $helper->getLogoutUrl($_SESSION['access_token'], "https://asolinge.create.stedwards.edu/AustinSafeRoutes");    
        $_SESSION = array();    //clear session array
        session_destroy();
        header("Location: $fbLogoutUrl");
        exit();
    }
    else if(isset($_POST['logout']) and $_SESSION['googleLoggedIn']){ // otherwise log user out of google
        unset($_SESSION['access_token']);
        $googleClient->revokeToken();
        session_destroy();
        header("Location: https://asolinge.create.stedwards.edu/AustinSafeRoutes");
        exit();
    }
    
    if(isset($_POST['googleLogout'])) {
        // google logout logic here
    }

    
    

?>
<!DOCTYPE html>
<html>

<head>
  <!-- BASICS -->
  <meta charset="utf-8">
  <title>Austin SafeRoutes - Plan for your commute</title>
  <style>
    #map{
      height:500px;
      width:100%;
    }
  </style>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link rel="stylesheet" type="text/css" href="js/rs-plugin/css/settings.css" media="screen"> -->
  <link rel="stylesheet" type="text/css" href="css/isotope.css" media="screen">
  <link rel="stylesheet" href="css/flexslider.css" type="text/css">
  <link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Serif:400,400italic,700|Open+Sans:300,400,600,700">

  <link rel="stylesheet" href="css/style.css">
  <!-- skin -->
  <link rel="stylesheet" href="skin/default.css">
  <!-- =======================================================
    Theme Name: Vlava
    Theme URL: https://bootstrapmade.com/vlava-free-bootstrap-one-page-template/
    Author: BootstrapMade.com
    Author URL: https://bootstrapmade.com
  ======================================================= -->

</head>

<body>
  
  <section id="header" class="appear"></section>
  <div class="navbar navbar-fixed-top" role="navigation" data-0="line-height:100px; height:100px; background-color:rgba(0,0,0,0.3);" data-300="line-height:60px; height:60px; background-color:rgba(5, 42, 62, 1);">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      	  <span class="fa fa-bars color-white"></span>
        </button>
        <div class="navbar-logo">
          <a href="index.php">Austin Safe Routes</a>
        </div>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav" data-0="margin-top:20px;" data-300="margin-top:5px;">
          <li class="active"><a href="index.php">Home</a></li>
          <?php  
            // if user is not logged in, display 'Sign In'
            if (!isset($_SESSION['access_token'])) {
                echo "<li class='active'><a href='#section-login'>Log In</a>";
            }
            else {
                echo "<li class='active'><a href='#section-login'>Log Out</a>";
            }
          ?>
          <li class="active"><a href="saveRoutes.php">Save Routes</a></li>
          <li class="active"><a href="myRoutes.php">My Routes</a></li>
          <li><a href="#section-contact">Contact</a></li>
          <?php  
            // if user is logged in, display user picture
            if (isset($_SESSION['access_token'])) {
                echo "<li class='active'><img src=". $_SESSION['userPicture']." width='50' height='50'></li>";
            }
          ?>
        </ul>
      </div>
      <!--/.navbar-collapse -->
    </div>
  </div>

  <section id="intro">
    <div class="intro-content">
      <h2>Austin SafeRoutes</h2>
      <div>
        <?php  
            // if user is not logged in, display log in nav button
            if (!isset($_SESSION['access_token'])) {
                echo "<a href='#section-services' class='btn-get-started scrollto'>Log In</a>";
            }
        ?>
      </div>
    </div>
  </section>
  
  <section id="section-services" class="section pad-bot30 bg-white">
    <!--facebook login button-->
    <div class="container">
      <div class="row">
          <div class="col-md-offset-5">
              <form>
                  <input type="button" onclick="window.location = '<?php echo $loginURL ?>';" value="Log In With Facebook" class="btn btn-primary">
              </form>
          </div>
      </div>
    </div>
    <br>
    <!--google login button-->
    <div class="container">
      <div class="row">
          <div class="col-md-offset-5">
              <form>
                  <input type="button" onclick="window.location = '<?php echo $googleLoginURL ?>';" value="Log In With Google" class="btn btn-danger">
              </form>
          </div>
      </div>
    </div>
    <br>
    
    <!--facebook logout button-->
    <div class="container">
      <div class="row">
          <div class="col-md-offset-5">
              <form method="post">
                  <input type="submit" name="logout" value="Log Out" class="btn btn-primary">
              </form>
          </div>
      </div>
    </div>
  </section>
  <!-- services -->
  <!-- contact -->
  <section id="section-contact" class="section appear clearfix">
    <div class="container">

      <div class="row mar-bot40">
        <div class="col-md-offset-3 col-md-6">
          <div class="section-header">
            <h2 class="section-heading animated" data-animation="bounceInUp">Contact us</h2>
            <p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet consectetur, adipisci velit, sed quia non numquam.</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="cform" id="contact-form">
            <div id="sendmessage">Your message has been sent. Thank you!</div>
            <div id="errormessage"></div>
            <form action="" method="post" class="contactForm">

              <div class="field your-name form-group">
                <input type="text" name="name" placeholder="Your Name" class="cform-text" size="40" data-rule="minlen:4" data-msg="Please enter at least 4 chars">
                <div class="validation"></div>
              </div>
              <div class="field your-email form-group">
                <input type="text" name="email" placeholder="Your Email" class="cform-text" size="40" data-rule="email" data-msg="Please enter a valid email">
                <div class="validation"></div>
              </div>
              <div class="field subject form-group">
                <input type="text" name="subject" placeholder="Subject" class="cform-text" size="40" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject">
                <div class="validation"></div>
              </div>

              <div class="field message form-group">
                <textarea name="message" class="cform-textarea" cols="40" rows="10" data-rule="required" data-msg="Please write something for us"></textarea>
                <div class="validation"></div>
              </div>

              <div class="send-btn">
                <input type="submit" value="SEND MESSAGE" class="btn btn-theme">
              </div>

            </form>
          </div>
        </div>
        <!-- ./span12 -->
      </div>

    </div>
  </section>

  <section id="footer" class="section footer">
    <div class="container">
      <div class="row animated opacity mar-bot20" data-andown="fadeIn" data-animation="animation">
        <div class="col-sm-12 align-center">
          <ul class="social-network social-circle">
            <li><a href="#" class="icoRss" title="Rss"><i class="fa fa-rss"></i></a></li>
            <li><a href="#" class="icoFacebook" title="Facebook"><i class="fa fa-facebook"></i></a></li>
            <li><a href="#" class="icoTwitter" title="Twitter"><i class="fa fa-twitter"></i></a></li>
            <li><a href="#" class="icoGoogle" title="Google +"><i class="fa fa-google-plus"></i></a></li>
            <li><a href="#" class="icoLinkedin" title="Linkedin"><i class="fa fa-linkedin"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="row align-center mar-bot20">
        <ul class="footer-menu">
          <li><a href="index.php">Home</a></li>
          <li><a href="#">About us</a></li>
          <li><a href="privacyPolicy.html">Privacy policy</a></li>
          <li><a href="#">Get in touch</a></li>
        </ul>
      </div>
      <div class="row align-center copyright">
        <div class="col-sm-12">
          <p>Copyright &copy; All rights reserved</p>
        </div>
      </div>
      <div class="credits">
        <!--
          All the links in the footer should remain intact.
          You can delete the links only if you purchased the pro version.
          Licensing information: https://bootstrapmade.com/license/
          Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Vlava
        -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade.com</a>
      </div>
    </div>

  </section>
  <a href="#header" class="scrollup"><i class="fa fa-chevron-up"></i></a>

  <!-- Javascript Library Files -->
  <script src="js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
  <script src="js/jquery.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.isotope.min.js"></script>
  <script src="js/jquery.nicescroll.min.js"></script>
  <script src="js/fancybox/jquery.fancybox.pack.js"></script>
  <script src="js/skrollr.min.js"></script>
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.localScroll.min.js"></script>
  <script src="js/stellar.js"></script>
  <script src="js/jquery.appear.js"></script>
  <script src="js/jquery.flexslider-min.js"></script>
  <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8HeI8o-c1NppZA-92oYlXakhDPYR7XMY"></script> -->

  <!-- Contact Form JavaScript File -->
  <script src="contactform/contactform.js"></script>

  <!-- Template Main Javascript File -->
  <script src="js/main.js"></script>

</body>

</html>