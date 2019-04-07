<? ob_start(); header('Cache-Control: no-store, no-cache, must-revalidate');
    
	$data = $_REQUEST['mapdata'];
	$routeName = $_REQUEST['routeName'];
// 	$userPhone = $_REQUEST['userPhone'];
	$userEmail = $_REQUEST['userEmail'];

	
	function dbConnect()
    {
    	$servername = "localhost";
    	$username = "asolinge_dbUser";
    	$password = "alexander1985";
    	$dbname = "asolinge_AustinSafeRoutes";
    
    	$conn = new mysqli($servername, $username, $password, $dbname);
    	// Check connection
    	if ($conn->connect_error) {
    	    die("Connection failed: " . $conn->connect_error);
    	} 
    	return $conn;
    }
    
    function insertMapDirData($conn, $data, $routeName, $userEmail)
    {
		error_log("test");
        $sql = "INSERT INTO mapdir (mapdir_data, route_name, email) VALUES('$data', '$routeName', '$userEmail')";
        if (mysqli_query($conn, $sql)) {
            echo "New mapdir created successfully";
        }
        else {
            echo "ERROR: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
    
    function returnMapDirData($conn, $routeName)
    {
		syslog(LOG_DEBUG, "SELECT mapdir_data FROM mapdir WHERE route_name = "."'".$routeName."'");
		
        $sql ="SELECT mapdir_data FROM mapdir WHERE route_name = "."'".$routeName."'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			
            return $row["mapdir_data"];
		  } else {
			return "null";
		  }
		
		
    }
    
    $conn = dbConnect();
	
	if($_REQUEST['command']=='save')
	{
		
// 		$query = "INSERT INTO mapdir (mapdir_data) VALUES('$data')";
// 		if(mysql_query($query))die('bien');
// 		die(mysql_error());
        insertMapDirData($conn, $data, $routeName, $userEmail);
	}
	
	if($_REQUEST['command']=='fetch')
	{
        
   
	  print_r(returnMapDirData($conn, $routeName));
	}
	
	$conn->close();
?>