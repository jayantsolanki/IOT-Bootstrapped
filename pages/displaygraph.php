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
	$query="SELECT switches, type FROM devices WHERE deviceId='$macid'"; //Fetching extra details about the device
	$devDetails=mysql_query($query);
	$devRow=mysql_fetch_assoc($devDetails);
	
	$_SESSION["devId"] = $macid;
	$_SESSION["type"] = 'temperature';
	$_SESSION["deviceType"] = $devRow['type'];
	$_SESSION["switches"] = $devRow['switches'];
	if($_SESSION["switches"]==1)
		$_SESSION["type"] = 'Sbattery';
	$jsonArray = array();
	if($type=='temperature' or $type=='Sbattery'){//temperature or secondary battery

		mysql_select_db($dbname) or die(mysql_error());
		if($type=='temperature')
			$query="(SELECT DATE_FORMAT(created_at, '%Y-%m-%d-%H-%i') as created_at , field4 as value FROM feeds WHERE feeds.device_id= '$macid' and feeds.field4<4096 )"; //device id similar to macid
		if($type=='Sbattery')
			$query="(SELECT DATE_FORMAT(created_at, '%Y-%m-%d-%H-%i') as created_at , field3 as value FROM feeds WHERE feeds.device_id= '$macid' and feeds.field4<4096 )"; //device id similar to macid
		$feeds=mysql_query($query);
		//echo mysql_num_rows($feeds);
		//initialize the array to store the processed data
		
		//check if there is any data returned by the SQL Query
		//echo mysql_num_rows($feeds);
		$i=0;
		if (mysql_num_rows($feeds) > 0) {
		  //Converting the results into an associative array
		  while($row=mysql_fetch_assoc($feeds)) {
		    $jsonArrayItem = array();
		   /*	$datetime = new DateTime($row['created_at']);
		   	$mdhms = explode('-',$datetime->format('H'));*/
		    $jsonArrayItem['label'] = $row['created_at'];
		    
		    $jsonArrayItem['value'] = $row['value'];
		    if($i==mysql_num_rows($feeds)-1)
		    	$jsonArrayItem['bulletClass'] = 'lastBullet';
		    //append the above created object into the main array.
		    array_push($jsonArray, $jsonArrayItem);
			$i++;
		  }
		}
		
	}
	else if($type=='humidity'){

		$_SESSION["type"] = 'humidity';
		mysql_select_db($dbname) or die(mysql_error());
		$query="(SELECT DATE_FORMAT(created_at, '%Y-%m-%d-%H-%i') as created_at ,humidity_value FROM feeds WHERE feeds.device_id= '$macid' and feeds.field4<4096 )"; //device id similar to macid
		$feeds=mysql_query($query);
		//initialize the array to store the processed data
		
		//check if there is any data returned by the SQL Query
		//echo mysql_num_rows($feeds);
		$i=0;
		if (mysql_num_rows($feeds) > 0) {
		  //Converting the results into an associative array
		  while($row=mysql_fetch_assoc($feeds)) {
		    $jsonArrayItem = array();
		   	/*	$datetime = new DateTime($row['created_at']);
		   	$mdhms = explode('-',$datetime->format('H'));*/
		    $jsonArrayItem['label'] = $row['created_at'];
		    
		    $jsonArrayItem['value'] = $row['humidity_value'];
		    //append the above created object into the main array.
		    if($i==mysql_num_rows($feeds)-1)
		    	$jsonArrayItem['bulletClass'] = 'lastBullet';
		    array_push($jsonArray, $jsonArrayItem);
		    $i++;
		  }
		}
	}
	else if($type=='moisture'){
		
		$_SESSION["type"] = 'moisture';
		mysql_select_db($dbname) or die(mysql_error());
		$query="(SELECT DATE_FORMAT(created_at, '%Y-%m-%d-%H-%i') as created_at ,moist_value FROM feeds WHERE feeds.device_id= '$macid' and feeds.field4<4096)"; //device id similar to macid
		$feeds=mysql_query($query);
		//initialize the array to store the processed data
		
		//check if there is any data returned by the SQL Query
		//echo mysql_num_rows($feeds);
		$i=0;
		if (mysql_num_rows($feeds) > 0) {
		  //Converting the results into an associative array
		  while($row=mysql_fetch_assoc($feeds)) {
		    $jsonArrayItem = array();
		   	/*	$datetime = new DateTime($row['created_at']);
		   	$mdhms = explode('-',$datetime->format('H'));*/
		    $jsonArrayItem['label'] = $row['created_at'];
		    
		    $jsonArrayItem['value'] = $row['moist_value'];
		    //append the above created object into the main array.
		    if($i==mysql_num_rows($feeds)-1)
		    	$jsonArrayItem['bulletClass'] = 'lastBullet';
		    array_push($jsonArray, $jsonArrayItem);
		    $i++;
		  }
		}
	}
	else{//battery

		$_SESSION["type"] = 'battery';
		mysql_select_db($dbname) or die(mysql_error());
		if($_SESSION["switches"]==0)
			$query="(SELECT DATE_FORMAT(created_at, '%Y-%m-%d-%H-%i') as created_at ,field3 as value FROM feeds WHERE feeds.device_id= '$macid' and feeds.field4<4096)"; //device id similar to macid
		if($_SESSION["switches"]==1)
			$query="(SELECT DATE_FORMAT(created_at, '%Y-%m-%d-%H-%i') as created_at ,field2 as value FROM feeds WHERE feeds.device_id= '$macid' and feeds.field4<4096 )"; //device id similar to macid
		$feeds=mysql_query($query);
		//initialize the array to store the processed data
		
		//check if there is any data returned by the SQL Query
		//echo mysql_num_rows($feeds);
		$i=0;
		if (mysql_num_rows($feeds) > 0) {
		  //Converting the results into an associative array
		  while($row=mysql_fetch_assoc($feeds)) {
		    $jsonArrayItem = array();
		   	/*	$datetime = new DateTime($row['created_at']);
		   	$mdhms = explode('-',$datetime->format('H'));*/
		    $jsonArrayItem['label'] = $row['created_at'];
		    
		    $jsonArrayItem['value'] = $row['value'];
		    //append the above created object into the main array.
		    if($i==mysql_num_rows($feeds)-1)
		    	$jsonArrayItem['bulletClass'] = 'lastBullet';
		    array_push($jsonArray, $jsonArrayItem);
		    $i++;
		  }
		}
	}
	//set the response content type as JSON
	header('Content-type: application/json');
	//output the return value of json encode using the echo function. 
	echo json_encode($jsonArray);

}
?>




