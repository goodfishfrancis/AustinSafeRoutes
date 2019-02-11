<? ob_start(); header('Cache-Control: no-store, no-cache, must-revalidate');

	$data = $_REQUEST['mapdata'];
	$routeName = $_REQUEST['routeName'];
	
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
    
    function insertMapDirData($conn, $data, $routeName)
    {
        $sql = "INSERT INTO mapdir (mapdir_data, route_name) VALUES('$data', '$routeName')";
        if (mysqli_query($conn, $sql)) {
            echo "New mapdir created successfully";
        }
        else {
            echo "ERROR: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
    
    function returnMapDirData($conn, $routeName)
    {
        $sql = "SELECT mapdir_data FROM mapdir WHERE route_name = "."'".$routeName."'";
        $result = $conn->query($sql);
    }
    
    $conn = dbConnect();
	
	if($_REQUEST['command']=='save')
	{
		
// 		$query = "INSERT INTO mapdir (mapdir_data) VALUES('$data')";
// 		if(mysql_query($query))die('bien');
// 		die(mysql_error());
        insertMapDirData($conn, $data, $routeName);
	}
	
	if($_REQUEST['command']=='fetch')
	{
// 		$query = "select value from mapdir";
// 		if(!($res = mysql_query($query)))die(mysql_error());		
// 		$rs = mysql_fetch_array($res,1);
// 		die($rs['value']);	
		returnMapDirData($conn, $routeName);
	}
	
	$conn->close();
?>










