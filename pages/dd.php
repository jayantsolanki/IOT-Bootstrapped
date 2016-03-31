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
$bat=$_GET["bat"]; //bat  is group id received fro battery status

display($grp);
/*if($bat!=NULL)
{
	//
	mysql_select_db($dbname) or die(mysql_error());
	$query="SELECT * FROM devices WHERE devices.group='$bat'";

	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{
	
		while($row = mysql_fetch_assoc($results))
		{
			$macid=$row['macid'];
				
				command($macid,2);	//publish for getting bat status
							
				$query = "UPDATE devices SET battery ='3', status='0' WHERE macid='$macid'"; //updating battery status in device table and also changing new device status,, initially keeping status as offline and bat unavailable
				//echo "</br>".$query;
				if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
					echo "UPDATE failed: $query<br/>".mysql_error()."<br/><br/>";
				
				
			
			
           	    	
		}
	}
	

	display($bat);
}*/
 /*
 *
 * Function Name: command($macid,$action)
 * Input: $ macid for macid, and $action for defining 0/1 for OFF/ON commands
 * Output: publish battery status commands to esp device.
 * each msg has macid, which will enable the script to generate a macid based topic(esp/macid)
 * Logic: msg format is 0, 1, 2, for OFF, ON and battery status.
 * 
 *
 */
/*function command($macid,$action) //for sending mqtt commands
	{
		//$mqtt->setAuth('sskaje', '123123');
		include 'settings/mqttsetting.php';
		$mqtt = new spMQTT($mqttaddress);
		$connected = $mqtt->connect();
		if (!$connected) 
			{
			    die(" <span class='text text-danger'>Mosca MQTT Server is Offline\n</span>");
			}

		$mqtt->ping();

		$msg = str_repeat($action, 1);

		//echo "</br>esp/valve/".$macid;
		$mqtt->publish('esp/'.$macid, $msg, 0, 1, 0, 1);
		//echo "</br>Success";
	}*/
 /*
 *
 * Function Name: display()
 * Input: -
 * Output: display devices under a group
 * Logic: fetches devices from devices table where group = group id
 * 
 *
 */
function display($grp)
{
	$jsonArray = array();//creating a json response
	//echo "<button id='bat' type='button' onclick='checkbat(this.value)' value='$grp'>Check Battery status</button></br></br>";
	//$query="SELECT * FROM devices";
	$query="SELECT * FROM devices WHERE devices.group=$grp";
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	
		while($row=mysql_fetch_assoc($results)) 
		{	
			$jsonArrayItem = array();
			$macid=$row['macid'];
			$action=$row['action'];
			$battery=$row['battery'];
			//$status=$row['status']; //online offline or new, 1, 0, 2
			//$seen=$row['seen'];
			$grp=$row['group'];//group in which it belongs
			$dname=$row['name'];
			$sense=$row['type'];//sensor type id
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
			if($action==1)
			$jsonArrayItem['action'] = 'Running';
			if($action==0)
			$jsonArrayItem['action'] = 'Idle';	
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
			array_push($jsonArray, $jsonArrayItem);
			/*if($battery==1) //changing into user readable form
				$batterymsg="<span class='label label-success' ><b>Healthy</b></span>";
			elseif($battery==2)
				$batterymsg="<span class='label label-danger'><b>Status unavailable</b></span>";
			elseif($battery==3)
				$batterymsg="<span class='label label-warning''><b>Checking status...</b></span>";
			elseif ($battery==0)
				$batterymsg="<span class='label label-danger''><b>Replace battery</b></span>";
			*/
			/*if($action==1) //changing into user readable form
				$action="<b><span style='color: #FFAA00;'>Device is ON</b></span>";
			elseif($action==0)
				$action="<b><span style='color: #AA6600;'>Device is OFF</span></b>";
			else 
			{
				if($action<260)
				    $action="<b><span style='color: #AA6600;'>Soil is wet</span></b>";
				else
				    $action="<b><span style='color: #AA6600;'>Soil is dry</span></b>";
				
			}*/
			
		
		
		}
		header('Content-type: application/json');
		//output the return value of json encode using the echo function. 
		echo json_encode($jsonArray);
		
	}
	else
		{
			echo "<h3>No Devices added yet</h3>";
		}
	
}
?>

