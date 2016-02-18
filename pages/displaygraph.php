<?php 
/*
*Project: IoT-Connected-valves-for-irrigation-of-greenhouse
*File name: displaygraph.php
*Author: Jayant Solanki
*this is the page called in AJAX mode, displaying graph for particular sensor/valve
*/
session_start();
include_once 'settings/iotdb.php';

?>

<?php
$macid=$_GET["q"];
$type=$_GET["type"];
if(isset($_GET['q']))
{
	//echo $macid;
	$_SESSION["devId"] = $macid;
	$jsonArray = array();
	if($type=='temperature'){//battery

		mysql_select_db($dbname) or die(mysql_error());
		$query="SELECT * FROM feeds WHERE feeds.device_id='$macid'Order by id DESC limit 30"; //device id similar to macid
		$feeds=mysql_query($query);
		//initialize the array to store the processed data
		
		//check if there is any data returned by the SQL Query
		//echo mysql_num_rows($feeds);
		if (mysql_num_rows($feeds) > 0) {
		  //Converting the results into an associative array
		  while($row=mysql_fetch_assoc($feeds)) {
		    $jsonArrayItem = array();
		   	$datetime = new DateTime($row['created_at']);
		   	$mdhms = explode('-',$datetime->format('H'));
		    $jsonArrayItem['label'] = $mdhms;
		    
		    $jsonArrayItem['value'] = $row['temp_value'];
		    //append the above created object into the main array.
		    array_push($jsonArray, $jsonArrayItem);
		  }
		}
		
	}
	else if($type=='humidity'){

		mysql_select_db($dbname) or die(mysql_error());
		$query="SELECT * FROM feeds WHERE feeds.device_id='$macid'Order by id DESC limit 30"; //device id similar to macid
		$feeds=mysql_query($query);
		//initialize the array to store the processed data
		
		//check if there is any data returned by the SQL Query
		//echo mysql_num_rows($feeds);
		if (mysql_num_rows($feeds) > 0) {
		  //Converting the results into an associative array
		  while($row=mysql_fetch_assoc($feeds)) {
		    $jsonArrayItem = array();
		   	$datetime = new DateTime($row['created_at']);
		   	$mdhms = explode('-',$datetime->format('H'));
		    $jsonArrayItem['label'] = $mdhms;
		    
		    $jsonArrayItem['value'] = $row['humidity_value'];
		    //append the above created object into the main array.
		    array_push($jsonArray, $jsonArrayItem);
		  }
		}
	}
	else if($type=='moisture'){
		
		mysql_select_db($dbname) or die(mysql_error());
		$query="SELECT * FROM feeds WHERE feeds.device_id='$macid'Order by id DESC limit 30"; //device id similar to macid
		$feeds=mysql_query($query);
		//initialize the array to store the processed data
		
		//check if there is any data returned by the SQL Query
		//echo mysql_num_rows($feeds);
		if (mysql_num_rows($feeds) > 0) {
		  //Converting the results into an associative array
		  while($row=mysql_fetch_assoc($feeds)) {
		    $jsonArrayItem = array();
		   	$datetime = new DateTime($row['created_at']);
		   	$mdhms = explode('-',$datetime->format('H'));
		    $jsonArrayItem['label'] = $mdhms;
		    
		    $jsonArrayItem['value'] = $row['moist_value'];
		    //append the above created object into the main array.
		    array_push($jsonArray, $jsonArrayItem);
		  }
		}
	}
	else{//battery

		mysql_select_db($dbname) or die(mysql_error());
		$query="SELECT * FROM feeds WHERE feeds.device_id='$macid'Order by id DESC limit 30"; //device id similar to macid
		$feeds=mysql_query($query);
		//initialize the array to store the processed data
		
		//check if there is any data returned by the SQL Query
		//echo mysql_num_rows($feeds);
		if (mysql_num_rows($feeds) > 0) {
		  //Converting the results into an associative array
		  while($row=mysql_fetch_assoc($feeds)) {
		    $jsonArrayItem = array();
		   	$datetime = new DateTime($row['created_at']);
		   	$mdhms = explode('-',$datetime->format('H'));
		    $jsonArrayItem['label'] = $mdhms;
		    
		    $jsonArrayItem['value'] = $row['battery_value'];
		    //append the above created object into the main array.
		    array_push($jsonArray, $jsonArrayItem);
		  }
		}
	}
	//set the response content type as JSON
	header('Content-type: application/json');
	//output the return value of json encode using the echo function. 
	echo json_encode($jsonArray);

}
?>




