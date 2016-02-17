<?php // iotdb.php
$dbhost  = 'localhost';    
$dbname  = 'IOT'; 
$dbuser  = 'root';    
$dbpass  = 'jayant123';    

mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());
?>
