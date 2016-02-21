<?php 
/*
*Project: IoT-Connected-valves-for-irrigation-of-greenhouse
*File name: saveConfig.php
*Author: Jayant Solanki
*this is the page called in AJAX mode, saves global variables
*/
session_start();
include_once 'settings/iotdb.php';

$val=$_GET["q"];
if(isset($_GET['q']))
{
	mysql_select_db($dbname) or die(mysql_error());
	$sql = "UPDATE global_variables SET value='$val' WHERE variable_name='mqtt'";

	if ($feeds=mysql_query($sql)) {
	    //echo "Record updated successfully";
	} else {
	    echo "<span class='text txt-danger'>Error updating record: " . $feeds."</span>";
	}
	echo $val;
}

?>
