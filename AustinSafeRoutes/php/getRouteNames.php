<?php
function getRouteNames(){
    
    // Create a connection handle to the local database
    $db = ADONewConnection('mysql');  
    
    // Open a connection -- pass in the localhost, username, 
    //password and database name 
    $db->PConnect('localhost',      // mysQL on the local machine
    'asolinge_dbUser',             // DB Username
    'alexander1985',                     // Password
    'asolinge_AustinSafeRoutes');           // Database name
    
    // query database for the username & password
    $rs = $db->Execute('SELECT route_name FROM mapdir');
    
    // Make sure we have results 
    if ($rs == false) 
    { 
        
        print 
            ' select failed \n '; 
    }  
    
    else
    {
    
        while (!$rs->EOF) 
        { 
            $routeName = $rs->fields['route_name']; 
            print "                      <option value="."'".$routeName."'".">".$routeName."</option> \n";
            $rs->MoveNext();
           
        }
    }   
}
?>
