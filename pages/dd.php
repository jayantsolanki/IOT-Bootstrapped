<?php
/*
*Project: IoT-Connected-valves-for-irrigation-of-greenhouse
*File name: dd.php
*Author: Jayant Solanki
*It is called in JAX mode by devdis.php for displaying devices information
*/
include 'settings/iotdb.php';
include 'settings/mqttsetting.php';
require(__DIR__ . '/spMQTT.class.php');
spMQTTDebug::Enable();
$grp=$_GET["grp"]; //grp is the group id received
$bat=$_GET["bat"]; //bat  is group id received fro battery status
if($grp!=NULL)
{
	display($grp);
}
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
	$dbname='IOT';
	mysql_select_db($dbname) or die(mysql_error());
	//echo "<button id='bat' type='button' onclick='checkbat(this.value)' value='$grp'>Check Battery status</button></br></br>";
	$query="SELECT * FROM devices WHERE devices.group=$grp";
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	$i=1;
		
		while($row=mysql_fetch_assoc($results)) 
		{	

			$macid=$row['macid'];
			$action=$row['action'];
			$battery=$row['battery'];
			$status=$row['status']; //online offline or new, 1, 0, 2
			$seen=$row['seen'];
			$grp=$row['group'];//group in which it belongs
			$dname=$row['name'];
			$sense=$row['type'];
			$batquery="SELECT battery_value, created_at FROM feeds WHERE feeds.device_id='$macid' order by feeds.id desc limit 1";
			$batresult=mysql_query($batquery);
			$batfetch=mysql_fetch_assoc($batresult);
			$batvalue=$batfetch['battery_value'];
			$battime=$batfetch['created_at'];
			$query="SELECT name FROM groups WHERE id='$grp'";
			$grps=mysql_query($query);
			$rows=mysql_fetch_assoc($grps);
			$name=$rows['name'];
			$query="SELECT name FROM sensors WHERE id='$sense'";
			$grps=mysql_query($query);
			$rows=mysql_fetch_assoc($grps);
			$sensor=$rows['name'];
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
			if($status==0) //offline
				$status="<b><span class='label label-danger''>OFFLINE</span></b>";
			elseif($status==1) //online
				$status="<b><span class='label label-success'>ONLINE</span></b>";
			elseif($status==2) //new device
				$status="<span class='label label-info'><b>New Device Found</b></span>";
			echo "<div class='row list-group'>";	
			echo "
			<div class='list-group-item col-md-5'>
			<h4 style='color:#3B5998;font-weight:normal;'><b>".$i.". Name:</b>$dname :<span id='$macid'>".$status."</span></h4><b style='color:#3B5998;font-weight:normal;'>Group: $name</b></br><b style='color:#3B5998;font-weight:normal;'>Type: $sensor</b></br><b style='color:#3B5998;font-weight:normal;'>Device ID</b> :<span style='color:#3B5998;font-weight:normal;'> ".$macid. "</span></br> <b style='color:#3B5998;font-weight:normal;'>Battery status : </b> ".$batvalue." mV <strong class='text text-info'>Updated</strong> on $battime</br><b style='color:#3B5998;font-weight:normal;'>Last updated : </b>$seen</span>
			</div>";
			$i++;
			echo "
			<div class='col-md-3'>
			</div>
			</div>
			";
		
		
		}
	}
	else
		{
			echo "<h3>No Devices added yet</h3>";
		}
	
}
?>

