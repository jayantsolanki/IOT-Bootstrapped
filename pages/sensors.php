<?php 
/*
*Project: IoT-Connected-valves-for-irrigation-of-greenhouse
*File name: sensors.php
*Author: Jayant Solanki
*this is the page called in AJAX mode, displaying relevent devices graph
*/
session_start();
include_once 'settings/iotdb.php';
error_reporting(-1); //for suppressing errors and notices

?>

<?php
$grp=$_GET["grp"];
if(isset($_GET['grp']))
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
			$query="SELECT name FROM groups WHERE id='$grp'";//getting group name
			$grps=mysql_query($query);
			$rows=mysql_fetch_assoc($grps);
			$name=$rows['name'];
			$query="SELECT name FROM sensors WHERE id='$sense'";//getting sensor name
			$grps=mysql_query($query);
			$rows=mysql_fetch_assoc($grps);
			$sensor=$rows['name'];
			$jsonArrayItem['deviceName'] = $dname;
			$jsonArrayItem['deviceId'] = $macid;
			$jsonArrayItem['switchCount'] = $switches;
			$jsonArrayItem['type'] = $sensor;
			$jsonArrayItem['typeId'] = $sense;//for getting the id of the devicetype
			$jsonArrayItem['groupName'] = $name;
			if($switches==0){
				if($row['field1']=='b'){
					$jsonArrayItem['field1'] = 'b';
					$jsonArrayItem['field3'] = 'battery';
				}
				if($row['field1']=='bm'){
					$jsonArrayItem['field1'] = 'bm';
					$jsonArrayItem['field3'] = 'battery';
					$jsonArrayItem['field4'] = 'moisture';
				}
				if($row['field1']=='bthm'){
					$jsonArrayItem['field1'] = 'bthm';
					$jsonArrayItem['field3'] = 'battery';
					$jsonArrayItem['field4'] = 'temperature';
					$jsonArrayItem['field5'] = 'humidity';
					$jsonArrayItem['field6'] = 'moisture';
				}
			}
			if($switches==1){
				$jsonArrayItem['field2'] = 'Pbattery';
				$jsonArrayItem['field3'] = 'Sbattery';
			}
			$feedfetch="SELECT field1, field2, field3, field4, field5, field6, created_at FROM feeds WHERE feeds.device_id='$macid' order by feeds.id desc limit 1";
			$feedres=mysql_query($feedfetch);
			$feed=mysql_fetch_assoc($feedres);
			$jsonArrayItem['Pbatvalue']=$feed['field2'];
			if($switches==1)//esp with 1 valve as secondary battery too
				$jsonArrayItem['Sbatvalue']=$feed['field3'];
			if($sense==2){//device type is sensor
				$jsonArrayItem['Pbatvalue']=$feed['field3'];
				if($feed['field1']=='bm')
					$jsonArrayItem['moistValue']=$feed['field4'];
				else if($feed['field1']=='bthm'){
					$jsonArrayItem['tempValue']=$feed['field4'];
					$jsonArrayItem['humidValue']=$feed['field5'];
					$jsonArrayItem['moistValue']=$feed['field6'];
				}

			}
			$phpdate=strtotime($feed['created_at']);
			$feedTime=date( 'h:i A jS M ', $phpdate );
			$jsonArrayItem['feedTime']=$feedTime;

			$seenquery="Select status, created_At from deviceStatus where deviceStatus.deviceId='$macid' order by deviceStatus.id desc limit 1";//getting last seen status
			$seenresult=mysql_query($seenquery);
			$seenfetch=mysql_fetch_assoc($seenresult);
			$seen=$seenfetch['created_At'];
			$phpdate=strtotime($seen);
			$seen=date( 'h:i A jS M ', $phpdate );
			$jsonArrayItem['seen']=$seen;
			$jsonArrayItem['status']=$seenfetch['status'];
			//$devType=$batfetch['device_type'];
			
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




