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
//mysql_select_db($dbname) or die(mysql_error());//manual on/off
$query="SELECT * FROM switches where"."(deviceId='$q' and switchId=$s)";
$results=mysqli_query($con, $query);



	if (mysqli_num_rows($results) > 0) 
	{
		while($row = mysqli_fetch_assoc($results))
		{
			$macid=$row['deviceId'];
			$action=$row['action'];
			//$status=$row['status'];
			if($action==0)//checking valve is off or not
			{
				//command($macid,1);	//switch ON	
				$active="<span data-toggle='tooltip' title='Switch currently running' class='text text-success fa fa-refresh fa-spin'></span>";		
				echo $active; //update button status
				$query = "UPDATE switches SET action ='1', updated_at=now() WHERE deviceId='$macid' and switchId=$s"; //updating action status in device table
				//echo "</br>".$query;
				if(!mysqli_query($con, $query))
					echo "UPDATE failed: $query<br/>".mysqli_error(true)."<br/><br/>";
				$query="INSERT INTO tasks VALUES". "(DEFAULT,NULL,'$macid','$s','$start','$stop', '0','0',1 ,NULL)"; //changed here for switches, last zero is for manual task identification
				if(!mysqli_query($con, $query))
					echo "INSERT failed: $query<br/>".mysqli_error(true)."<br/><br/>";
				
			}
			else
			{
				//command($macid,0);	//Switch off
				$active="<span data-toggle='tooltip' title='Switch currently stopped' class='text text-danger glyphicon glyphicon-ban-circle'></span>";
				echo $active;
				$query = "UPDATE switches SET action ='0', updated_at=now() WHERE deviceId='$macid' and switchId=$s"; //updating action status in device table 
				//echo "</br>".$query;
				if(!mysqli_query($con, $query))
					echo "UPDATE failed: $query<br/>".mysqli_error(true)."<br/><br/>";
				$query= "DELETE FROM tasks where deviceId='$macid' and switchId=$s";//possible collision with sql query running in mosca server
				if(!mysqli_query($con, $query))
					echo "UPDATE failed: $query<br/>".mysqli_error(true)."<br/><br/>";
				
			}
           	    	
		}
	}
	

}
else //switching whole group on/off
{
//mysql_select_db($dbname) or die(mysql_error());

$query="SELECT name FROM groups WHERE id='$gid'";
$grps=mysqli_query($con, $query);
$grp=mysqli_fetch_assoc($grps);
$name=$grp['name'];

$query="SELECT * FROM devices where devices.group='$gid'";
$results=mysqli_query($con, $query);
	if (mysqli_num_rows($results) > 0) 
	{
		while($row = mysqli_fetch_assoc($results))
		{
				$macid=$row['macid'];
				command($macid,$q);	//switch 
				//echo "Switch OFF"; //update button status
		}
	
	if($q==1)
		{

		echo "Switch OFF";
		$query="INSERT INTO tasks VALUES". "(DEFAULT,'$name',NULL,NULL,'$start','$stop', '0','0',2 ,NULL)";
			if(!mysqli_query($con, $query))
				echo "INSERT failed: $query<br/>".mysqli_error(true)."<br/><br/>";
		}
	else 
		{
		echo "Switch ON";
			
		}
	$update="UPDATE devices SET action='$q', updated_at=now() WHERE devices.group='$gid'"; //this is for updating running status off devices

	//echo "</br>".$query;
	if(!mysqli_query($con, $update))
	echo "UPDATE failed: $query<br/>".mysqli_error(true)."<br/><br/>";
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

