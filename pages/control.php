<?php 
/*
*Project: eYSIP_2015_IoT-Connected-valves-for-irrigation-of-greenhouse
*Team members: Jayant Solanki, Kevin D'Souza
*File name: control.php
*Author: Jayant Solanki
*this is the page called in AJAX mode, displaying all the manual switch controls for esp modules
*/
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
	echo "<h4>Valves grouped under <label class='badge'>".$gname."</label></h4>";
	$query="SELECT * FROM devices WHERE devices.group=$grp and devices.type='1'";
	$results=mysql_query($query);

	//echo " <button id='1' type='button' onclick='updateall(this.value)' value='1'>Switch all ON</button>";
	//echo " <button id='0' type='button' onclick='updateall(this.value)' value='0'>Switch all OFF</button>";
	echo "</br></br><label class=''>Duration:(mm)</label></br>";
	echo "<div class='row'>";
	echo "<div class='col-xs-5'>";
	echo "Mins:<select class='form-control' id='duration' name='duration'>";
	$j=1; 
	while($j<=60)
	{
	echo "<option value='$j'>$j</option>";
	$j=$j+1;
	} 
	echo "</select>";
	echo "</div>";
	echo "</div>";

	if (mysql_num_rows($results) > 0) 
	{	$i=0;
		
		echo "<table class='table table-striped'><tbody>";
		while($row=mysql_fetch_assoc($results)) 
		{	
			$i++;
			$macid=$row['macid'];
			$action=$row['action'];
			$name=$row['name'];
			$type=$row['type'];
			$query="SELECT name FROM sensors WHERE id='$type'";
			$sens=mysql_query($query);
			$sen=mysql_fetch_assoc($sens);
			$sname=$sen['name'];
			if($action==1) //changing into user readable form
				$action='OFF';
			else
				$action='ON';
			
			$seenquery="Select status from deviceStatus where deviceStatus.deviceId='$macid' order by deviceStatus.id desc limit 1";//getting last seen status
			$seenresult=mysql_query($seenquery);
			$seenfetch=mysql_fetch_assoc($seenresult);
			$status=$seenfetch['status']; //online offline or new, 1, 0, 2
			if($status==0) //offline
				$status="<span class='label label-danger'>OFFLINE</span>";
			elseif($status==1) //online
				$status="<span class='label label-success'>ONLINE</span>";
			elseif($status==2) //new device
				$status="<span class='label label-info'>New Device Found</span>";
				echo "
			<tr>
			<td><strong class='text text-info'>".$i.". $sname sensor: $name </strong>&nbsp; &nbsp;<strong class='text text-info'>MacId:</b> $macid &nbsp;</td>";
			
			echo "<td><button class='item btn btn-primary' id='$macid' type='button'  onclick='update(this.value)' value='$macid'>Switch ".$action."</button></td>
			<td class='$macid'>$status</td>
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




