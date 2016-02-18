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
	
	mysql_select_db($dbname) or die(mysql_error());
	$query="SELECT name FROM groups WHERE id='$grp'";
	$grps=mysql_query($query);
	$rows=mysql_fetch_assoc($grps);
	$gname=$rows['name'];
	echo "<h4>Valves grouped under <label class='badge'>".$gname."</label><br/></h4>";
	$query="SELECT * FROM devices WHERE devices.group=$grp";
	$results=mysql_query($query);
	
	if (mysql_num_rows($results) > 0) 
	{	$i=0;
		
		echo "<table class='table table-striped'><tbody>";
		while($row=mysql_fetch_assoc($results)) 
		{	
			$i++;
			$macid=$row['macid'];
			$action=$row['action'];
			$status=$row['status']; //online offline or new, 1, 0, 2
			$name=$row['name'];
			$type=$row['type'];
			$query="SELECT name FROM sensors WHERE id='$type'";
			$sens=mysql_query($query);
			$sen=mysql_fetch_assoc($sens);
			$sname=$sen['name'];
			// getting feeds
			echo "
			<tr>
			<td ><strong class='text text-info'>".$i.". $sname Sensor: $name </strong>&nbsp; <strong class='text text-info'>Device Id:</strong> $macid</td>
			<td ><button type='button' class='btn btn-primary' value=$macid onclick='showgraphBattery(this.value)'>Show graph</button>
			</td>
			</tr>";
			
		
		}
		echo "</tbody></table>";
	}
	else
	{
	echo "<label class='text text-danger'><h3>No Devices added yet</h3></label>";
	}

}
?>




