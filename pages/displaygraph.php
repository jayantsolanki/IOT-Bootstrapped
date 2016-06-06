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
$deviceId=$_GET["deviceId"];
$feed=$_GET["feed"];
$deviceType=$_GET["deviceType"];
$count=$_GET["count"];
$startDate=$_GET["startDate"];
$yAxisLimit=$_GET["yAxisLimit"];
if(isset($_GET['feed']))
{
	//echo $macid;
	if($count==null)
		$count='';
	else
		$count='LIMIT '.$count;
	if($startDate==null)
		$startDate='';
	else
		$startDate="and created_at >= '$startDate'";
	if($yAxisLimit==null)
		$yAxisLimit='';
	else
		$yAxisLimit="'$yAxisLimit'";
	$jsonArray = array();
	if($feed=='temp'){//temperature or secondary battery
		$query="(SELECT DATE_FORMAT(created_at, '%Y-%m-%d-%H-%i') as created_at , field4 as value FROM feeds WHERE feeds.device_id= '$deviceId' and feeds.field4<$yAxisLimit $startDate $count)"; //device id similar to macid

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
	else if($feed=='humid'){

		mysql_select_db($dbname) or die(mysql_error());
		if($deviceType=='bthm')//humidity bthm
			$query="(SELECT DATE_FORMAT(created_at, '%Y-%m-%d-%H-%i') as created_at ,field5 FROM feeds WHERE feeds.device_id= '$deviceId' and feeds.field5<$yAxisLimit $startDate $count)"; //device id similar to macid
		//if($deviceType=='bthm')//hub battery
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
	else if($feed=='moist'){
		
		mysql_select_db($dbname) or die(mysql_error());
		if($deviceType=='bm')//moisture bm
			$query="(SELECT DATE_FORMAT(created_at, '%Y-%m-%d-%H-%i') as created_at ,field4 as value FROM feeds WHERE feeds.device_id= '$deviceId' and feeds.field4<$yAxisLimit $startDate $count)"; //device id similar to macid
		if($deviceType=='bthm')//moisture bthm
			$query="(SELECT DATE_FORMAT(created_at, '%Y-%m-%d-%H-%i') as created_at ,field6 as value FROM feeds WHERE feeds.device_id= '$deviceId' and feeds.field6<$yAxisLimit $startDate $count)"; //device id similar to macid
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
	else if($feed=='battery'){//battery
		mysql_select_db($dbname) or die(mysql_error());
		if($deviceType==1)//primary
			$query="(SELECT DATE_FORMAT(created_at, '%Y-%m-%d-%H-%i') as created_at ,field2 as value FROM feeds WHERE feeds.device_id= '$deviceId' and feeds.field2<$yAxisLimit $startDate $count)"; //device id similar to macid
		if($deviceType==2)//secondary
			$query="(SELECT DATE_FORMAT(created_at, '%Y-%m-%d-%H-%i') as created_at ,field3 as value FROM feeds WHERE feeds.device_id= '$deviceId' and feeds.field3<$yAxisLimit $startDate $count)"; //device id similar to macid
		if($deviceType=='b')//hub battery
			$query="(SELECT DATE_FORMAT(created_at, '%Y-%m-%d-%H-%i') as created_at ,field3 as value FROM feeds WHERE feeds.device_id= '$deviceId' and feeds.field3<$yAxisLimit $startDate $count)"; //device id similar to macid
		if($deviceType=='bm' or $deviceType=='bthm')//bm, bthm
			$query="(SELECT DATE_FORMAT(created_at, '%Y-%m-%d-%H-%i') as created_at ,field3 as value FROM feeds WHERE feeds.device_id= '$deviceId' and feeds.field3<$yAxisLimit $startDate $count)"; //device id similar to macid
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




