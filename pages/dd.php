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
$deviceActivity=$_GET['deviceActivity'];
if($grp!=null){
	display($grp);
}
if($deviceId!=null){

	$jsonArray=array();
	$query="SELECT * FROM switches WHERE switches.deviceId='$deviceId'";
	$results=mysqli_query($con, $query);
	if (mysqli_num_rows($results) > 0) 
	{	
		while($row=mysqli_fetch_assoc($results)) 
		{	
			$deviceId=$row['deviceId'];
			$switchId=$row['switchId'];
			$action=$row['action'];
			$created=$row['created_at'];
			$phpdate=strtotime($created);
			$created=date( 'h:i A jS M ', $phpdate );
			$actionSince=$row['updated_at'];
			$phpdate=strtotime($actionSince);
			$actionSince=date( 'h:i A jS M ', $phpdate );
			//$status=$row['status']; //online offline or new, 1, 0, 2
			//$seen=$row['seen'];
			$grp=$row['groupId'];//group in which it belongs
			//$dname=$row['name'];
			//$sense=$row['type'];//device type id
			//$switches=$row['switches'];
			$newSwitch=$row['newSwitch'];
			$query="SELECT name FROM groups WHERE id='$grp'";
			$grps=mysqli_query($con, $query);
			$rows=mysqli_fetch_assoc($grps);
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

if($deviceActivity!=null){
	$grpupId=$_GET['grpId'];
	$jsonArray = array();
	$query="SELECT * FROM devices WHERE groupId=$grpupId";
	$results=mysqli_query($con, $query);
	if (mysqli_num_rows($results) > 0) 
	{	
		while($row=mysqli_fetch_assoc($results)) 
		{	
			$deviceId=$row['deviceId'];
			$deviceType=$row['type'];
			if($deviceType==1)
				$devStatus="Select * FROM (SELECT * FROM deviceStatus WHERE deviceId='$deviceId' order by id desc limit 15) as temp order by id asc";
			else
				$devStatus="Select * FROM (SELECT * FROM deviceStatus WHERE deviceId='$deviceId' order by id desc limit 100) as temp order by id asc";
			$Statusresults=mysqli_query($devStatus);
			$length=mysqli_num_rows($Statusresults);
			if ($length > 0) 
			{	
				$jsonArrayItem['category'] = $deviceId;
				$segments = array();//array for segments
				$timeRows[]=null;
				$i=0;
				while ($timeRows[$i] = mysqli_fetch_assoc($Statusresults)){
					$i++;
				}
				for($i=0; $i<$length;$i++)
				{
					$status=$timeRows[$i]['status'];
					$segmentArrayItem['start'] = $timeRows[$i]['created_At'];
					if($i<$length-1)
						$stopTime =$timeRows[$i+1]['created_At'];
					if($i==$length-1)
						$stopTime =date('Y-m-d H:i:s');;
					//$duration=strtotime($stopTime)-strtotime($segmentArrayItem['start']);
					$segmentArrayItem['end']=$stopTime;
					if($status==1){
						$segmentArrayItem['color'] = "#FFFFC0";
						$segmentArrayItem['task'] = "Online";
					}
					if($status==0){
						$segmentArrayItem['color'] = "#FF5F33";
						$segmentArrayItem['task'] = "Offline";
					}
					$phpdate=strtotime($segmentArrayItem['start']);
					$startF=date( 'H:i:s jS M ', $phpdate );
					$phpdate=strtotime($segmentArrayItem['end']);
					$stopF=date( 'H:i:s jS M ', $phpdate );
					$segmentArrayItem['duration']=$startF." to ".$stopF;
					if($i==$length-1)
						$segmentArrayItem['duration']="since ".$startF;
					//$startTime=$Statusrows['created_at'];

					//$i=$i+1;
					array_push($segments, $segmentArrayItem);

				}
				$jsonArrayItem['segments'] = $segments;
				array_push($jsonArray, $jsonArrayItem);
			}

			
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
	$results=mysqli_query($con, $query);
	if (mysqli_num_rows($results) > 0) 
	{	
		while($row=mysqli_fetch_assoc($results)) 
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
			$batquery="SELECT field2, field3, created_at FROM feeds WHERE feeds.device_id='$macid' order by feeds.id desc limit 1";
			$batresult=mysqli_query($con, $batquery);
			$batfetch=mysqli_fetch_assoc($batresult);
			$Pbatvalue=$batfetch['field2'];
			if($sense==2)
				$Pbatvalue=$batfetch['field3'];
			$Sbatvalue=$batfetch['field3'];
			//$devType=$batfetch['device_type'];
			$batTime=$batfetch['created_at'];
			$query="SELECT name FROM groups WHERE id='$grp'";//getting group name
			$grps=mysqli_query($con, $query);
			$rows=mysqli_fetch_assoc($grps);
			$name=$rows['name'];
			$query="SELECT name FROM sensors WHERE id='$sense'";//getting sensor name
			$grps=mysqli_query($con, $query);
			$rows=mysqli_fetch_assoc($grps);
			$sensor=$rows['name'];
			$seenquery="Select status, created_At from deviceStatus where deviceStatus.deviceId='$macid' order by deviceStatus.id desc limit 1";//getting last seen status
			$seenresult=mysqli_query($con, $seenquery);
			$seenfetch=mysqli_fetch_assoc($seenresult);
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
			$phpdate=strtotime($seen);
			$seen=date( 'h:i A jS M ', $phpdate );
			$jsonArrayItem['seen'] = $seen;
			$jsonArrayItem['groupName'] = $name;
			$jsonArrayItem['type'] = $sensor;
			$jsonArrayItem['typeId'] = $sense;//for getting the id of the devicetype
			$jsonArrayItem['PbatValue'] = $Pbatvalue;
			if($switches==1 && $Sbatvalue!=null){//if switch ==1 then it has secondary battery
				$jsonArrayItem['SbatValue'] = $Sbatvalue;
				//$jsonArrayItem['devType'] = true;
			}
			else
				$jsonArrayItem['devType'] = false;
			$phpdate=strtotime($batTime);
			$batTime=date( 'h:i A jS M ', $phpdate );
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

