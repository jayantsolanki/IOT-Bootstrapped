<?php // iotdb.php
$dbhost  = 'localhost';    
$dbname  = 'IOT'; 
$dbuser  = 'root';    
$dbpass  = 'jayant123';    

// mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error());
$con = ($GLOBALS["___mysqli_ston"] = mysqli_connect( $dbhost, $dbuser, $dbpass, $dbname) or die(mysqli_error(true));
// mysql_select_db($dbname) or die(mysql_error());
//mysqli_select_db($dbname) or die(mysqli_error(true));
?>
