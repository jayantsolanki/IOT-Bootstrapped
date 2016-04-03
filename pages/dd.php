<?php
/*
*Project: IoT-Connected-valves-for-irrigation-of-greenhouse
*File name: dd.php
*Author: Jayant Solanki
*It is called in JAX mode by devdis.php for displaying devices information
*/
include 'settings/iotdb.php';
include 'settings/mqttsetting.php';
$grp=$_GET["grp"]; //grp is the group id received
$deviceId=$_GET["deviceId"]; //bat  is group id received fro battery status
if($grp!=null){
	display($grp);
}
if($deviceId!=null){

	$jsonArray=array();
	$query="SELECT * FROM switches WHERE switches.deviceId='$deviceId'";
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	
		while($row=mysql_fetch_assoc($results)) 
		{	
			$deviceId=$row['deviceId'];
			$switchId=$row['switchId'];
			$action=$row['action'];
			$created=$row['created_at'];
			$actionSince=$row['updated_at'];
			//$status=$row['status']; //online offline or new, 1, 0, 2
			//$seen=$row['seen'];
			$grp=$row['groupId'];//group in which it belongs
			//$dname=$row['name'];
			//$sense=$row['type'];//device type id
			//$switches=$row['switches'];
			$newSwitch=$row['newSwitch'];
			$query="SELECT name FROM groups WHERE id='$grp'";
			$grps=mysql_query($query);
			$rows=mysql_fetch_assoc($grps);
			$gname=$rows['name'];

			$jsonArrayItem['deviceId'] = $deviceId;
			$jsonArrayItem['switchId'] = $switchId;
			if($newSwitch==1)
				$jsonArrayItem['newSwitch'] = true;
			if($newSwitch==0)
				$jsonArrayItem['newSwitch'] = false;
			$jsonArrayItem['groupName'] = $gname;
			$jsonArrayItem['action'] = $action;
			$jsonArrayItem['created'] = $created;
			$jsonArrayItem['actionSince'] = $actionSince;

			array_push($jsonArray, $jsonArrayItem);
		}
		header('Content-type: application/json');
		//output the return value of json encode using the echo function. 
		echo json_encode($jsonArray);
		
	}
}
function display($grp)
{
	$jsonArray = array();//creating a json response
	//echo "<button id='bat' type='button' onclick='checkbat(this.value)' value='$grp'>Check Battery status</button></br></br>";
	//$query="SELECT * FROM devices";
	$query="SELECT * FROM devices WHERE devices.groupId=$grp";
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	
		while($row=mysql_fetch_assoc($results)) 
		{	
			$jsonArrayItem = array();
			$macid=$row['deviceId'];
			//$status=$row['status']; //online offline or new, 1, 0, 2
			//$seen=$row['seen'];
			$grp=$row['groupId'];//group in which it belongs
			$dname=$row['name'];
			$sense=$row['type'];//device type id
			$switches=$row['switches'];
			$newDevice=$row['status'];
			$created=$row['created_at'];
			$batquery="SELECT device_type, battery_value, temp_value, created_at FROM feeds WHERE feeds.device_id='$macid' order by feeds.id desc limit 1";
			$batresult=mysql_query($batquery);
			$batfetch=mysql_fetch_assoc($batresult);
			$Pbatvalue=$batfetch['battery_value'];
			$Sbatvalue=$batfetch['temp_value'];
			$devType=$batfetch['device_type'];
			$batTime=$batfetch['created_at'];
			$query="SELECT name FROM groups WHERE id='$grp'";
			$grps=mysql_query($query);
			$rows=mysql_fetch_assoc($grps);
			$name=$rows['name'];
			$query="SELECT name FROM sensors WHERE id='$sense'";
			$grps=mysql_query($query);
			$rows=mysql_fetch_assoc($grps);
			$sensor=$rows['name'];
			$seenquery="Select status, created_At from deviceStatus where deviceStatus.deviceId='$macid' order by deviceStatus.id desc limit 1";//getting last seen status
			$seenresult=mysql_query($seenquery);
			$seenfetch=mysql_fetch_assoc($seenresult);
			$seen=$seenfetch['created_At'];
			$status=$seenfetch['status']; //online offline or new, 1, 0, 2

			$jsonArrayItem['deviceName'] = $dname;
			$jsonArrayItem['deviceId'] = $macid;
			$jsonArrayItem['switchCount'] = $switches;
			if($newDevice==1)
			$jsonArrayItem['newDevice'] = true;
			if($newDevice==0)
			$jsonArrayItem['newDevice'] = false;	
			if($status==1)
				$jsonArrayItem['status'] = true;
			if($status==0)
				$jsonArrayItem['status'] = false;
			$jsonArrayItem['seen'] = $seen;
			$jsonArrayItem['groupName'] = $name;
			$jsonArrayItem['type'] = $sensor;
			$jsonArrayItem['PbatValue'] = $Pbatvalue;
			if($devType==1 && $Sbatvalue!=null){
				$jsonArrayItem['SbatValue'] = $Sbatvalue;
				$jsonArrayItem['devType'] = true;
			}
			else
				$jsonArrayItem['devType'] = false;
			$jsonArrayItem['batTime'] = $batTime;
			$jsonArrayItem['created'] = $created;
			array_push($jsonArray, $jsonArrayItem);
		}
		header('Content-type: application/json');
		//output the return value of json encode using the echo function. 
		echo json_encode($jsonArray);
		
	}
	else
		{
			$jsonArray=array();
			echo json_encode($jsonArray);
			//echo "<h3>No Devices added yet</h3>";
		}
	
}
?>

