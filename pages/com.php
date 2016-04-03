<?php
/*
*Project: IoT-Connected-valves-for-irrigation-of-greenhouse
*Author: Jayant Solanki
*sends manual on/off command to esp devices, and also sets timeout for them
*/
require 'settings/iotdb.php';
require(__DIR__ . '/spMQTT.class.php');
date_default_timezone_set('Asia/Kolkata');//setting IST
spMQTTDebug::Enable();
$q=$_GET["devId"]; //q is the macid received
$s=$_GET["switchId"]; //q is the macid received
$gid=$_GET['gid'];
$duration=$_GET['duration'];
$starth=date('H');
$startm=date('i');
$start=$starth*100+$startm;
$stop=$starth*100+normalize($startm,$duration);
if($stop>=2400)
	$stop=$stop-2400;
if($q!=0 and $q!=1 )//individual on/off
{
//echo "Hello World".$q;
mysql_select_db($dbname) or die(mysql_error());//manual on/off
$query="SELECT * FROM switches where"."(deviceId='$q' and switchId=$s)";
$results=mysql_query($query);



	if (mysql_num_rows($results) > 0) 
	{
		while($row = mysql_fetch_assoc($results))
		{
			$macid=$row['deviceId'];
			$action=$row['action'];
			//$status=$row['status'];
			if($action==0)//checking valve is off or not
			{
				//command($macid,1);	//switch ON			
				//echo "Switch OFF"; //update button status
				$query = "UPDATE switches SET action ='1', updated_at=now() WHERE deviceId='$macid' and switchId=$s"; //updating action status in device table
				//echo "</br>".$query;
				if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
					echo "UPDATE failed: $query<br/>".mysql_error()."<br/><br/>";
				$query="INSERT INTO tasks VALUES". "(DEFAULT,NULL,'$macid','$s','$start','$stop', '0','0')"; //changed here for switches, last zero is for manual task identification
				if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
					echo "INSERT failed: $query<br/>".mysql_error()."<br/><br/>";
				
			}
			else
			{
				//command($macid,0);	//Switch off
				//echo "Switch ON";
				$query = "UPDATE switches SET action ='0', updated_at=now() WHERE deviceId='$macid' and switchId=$s"; //updating action status in device table 
				//echo "</br>".$query;
				if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
					echo "UPDATE failed: $query<br/>".mysql_error()."<br/><br/>";
				$query= "DELETE FROM tasks where deviceId='$macid' and switchId=$s";//possible collision with sql query running in mosca server
				if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
					echo "UPDATE failed: $query<br/>".mysql_error()."<br/><br/>";
				
			}
           	    	
		}
	}
	

}
else //switching whole group on/off
{
mysql_select_db($dbname) or die(mysql_error());

$query="SELECT name FROM groups WHERE id='$gid'";
$grps=mysql_query($query);
$grp=mysql_fetch_assoc($grps);
$name=$grp['name'];

$query="SELECT * FROM devices where devices.group='$gid'";
$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{
		while($row = mysql_fetch_assoc($results))
		{
				$macid=$row['macid'];
				command($macid,$q);	//switch 
				//echo "Switch OFF"; //update button status
		}
	
	if($q==1)
		{

		echo "Switch OFF";
		$query="INSERT INTO tasks VALUES". "(DEFAULT,'$name',NULL,'$start','$stop', '0','0')";
			if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
				echo "INSERT failed: $query<br/>".mysql_error()."<br/><br/>";
		}
	else 
		{
		echo "Switch ON";
			
		}
	$update="UPDATE devices SET action='$q', updated_at=now() WHERE devices.group='$gid'"; //this is for updating running status off devices

	//echo "</br>".$query;
	if(!mysql_query($update,mysql_connect($dbhost, $dbuser, $dbpass)))
	echo "UPDATE failed: $query<br/>".mysql_error()."<br/><br/>";
	}

}
 /*
 *
 * Function Name: command($macid,$action)
 * Input: $ macid for macid, and $action for defining 0/1 for OFF/ON commands
 * Output: publish ON/OFF commands to esp device.
 * each msg has macid, which will enable the script to generate a macid based topic(esp/macid)
 * Logic: msg format is 0, 1, 2, for OFF, ON and battery status.
 * 
 *
 */
function command($macid,$action) //for sending mqtt commands
{

include 'settings/mqttsetting.php';
$mqtt = new spMQTT($mqttaddress);
$connected = $mqtt->connect();
if (!$connected) {
    die("<span class='text text-danger'> Mosca MQTT Server is Offline\n</span>");
}

$mqtt->ping();

$msg = str_repeat($action, 1);

//echo "</br>esp/valve/".$macid;
$mqtt->publish('esp/'.$macid, $msg, 0, 1, 1, 1);
//echo "</br>Success";
}
function normalize($startm,$duration)
{
	$tot=$startm+$duration;
	if ($tot>=60)
		{
			$tot=$tot-60;
			$tot=100+$tot;
			return $tot;
		}
	return $tot;


}
?>

