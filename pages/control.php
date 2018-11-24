<?php 
/*
*Project: eYSIP_2015_IoT-Connected-valves-for-irrigation-of-greenhouse
*Team members: Jayant Solanki, Kevin D'Souza
*File name: control.php
*Author: Jayant Solanki
*this is the page called in AJAX mode, displaying all the manual switch controls for esp modules
*/
include_once 'settings/iotdb.php';
include_once 'settings/mqttsetting.php'; //environmental variable for mqtt address and websocket
error_reporting(-1); //for suppressing errors and notices

?>

<?php
$grp=$_GET["grp"];
if(isset($_GET['grp']))
{
	
	//mysqli_select_db($dbname) or die(mysqli_error());
	// $query="SELECT name FROM groups WHERE id='$grp'";
	// $grps=mysqli_query($con,$query);
	// $rows=mysqli_fetch_assoc($grps);
	// $gname=$rows['name'];
	echo "<h4>You chose <label class='text text-success'>".$grp."</label></h4>";
	// $query="SELECT * FROM switches WHERE switches.groupId=$grp";
	$query="SELECT * FROM switches WHERE switches.groupId in (SELECT id from groups where name=\'$grp\'')";
	$results=mysqli_query($con,$query);

	//echo " <button id='1' type='button' onclick='updateall(this.value)' value='1'>Switch all ON</button>";
	//echo " <button id='0' type='button' onclick='updateall(this.value)' value='0'>Switch all OFF</button>";
	echo "<label class=''>Duration:(mm)</label></br>";
	echo "<div class='row'>";
	echo "<div class='col-md-6'>";
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

	if (mysqli_num_rows($results) > 0) 
	{	$i=0;
		
		echo "<table class='table table-striped'><tbody>";
		while($row=mysqli_fetch_assoc($results)) 
		{	
			$i++;
			$macid=$row['deviceId'];
			$switchId=$row['switchId'];
			$action=$row['action'];
			$devquery="SELECT name, type, switches FROM devices WHERE deviceId='$macid'";
			$devs=mysqli_query($con,$devquery);
			$dev=mysqli_fetch_assoc($devs);
			$name=$dev['name'];
			$type=$dev['type'];//valve, sensor
			$switches=$dev['switches'];

			$query="SELECT name FROM sensors WHERE id='$type'";
			$sens=mysqli_query($con,$query);
			$sen=mysqli_fetch_assoc($sens);
			$sname=$sen['name'];
			if($action==1){ //changing into user readable form
				$active="<span data-toggle='tooltip' title='Switch currently running' class='text text-success fa fa-refresh fa-spin'></span>";
				$action='OFF';
			}
			else{
				$active="<span data-toggle='tooltip' title='Switch currently stopped' class='text text-danger glyphicon glyphicon-ban-circle'></span>";
				$action='ON';
			}
			
			$seenquery="Select status from deviceStatus where deviceStatus.deviceId='$macid' order by deviceStatus.id desc limit 1";//getting last seen status
			$seenresult=mysqli_query($con,$seenquery);
			$seenfetch=mysqli_fetch_assoc($seenresult);
			$status=$seenfetch['status']; //online offline or new, 1, 0, 2
			if($status==0) //offline
				$status="<span class='label label-danger'>OFFLINE</span>";
			elseif($status==1) //online
				$status="<span class='label label-success'>ONLINE</span>";
			elseif($status==2) //new device
				$status="<span class='label label-info'>New Device Found</span>";
				echo "
			<tr>
			<td>
				<table class='table table-striped'>
					<tr><td>SwitchId#$switchId</td>
						<td class='text-info'>$name</td>
					</tr>
					<!--<tr>
						<td>Type:</td>
						<td class='text-info'>$switches switches $sname</td>
					</tr>-->
					<tr>
						<td>DeviceId</td>
						<td class='text-info'>$macid</td>
					</tr>
					<!--<tr>
						<td>SwitchId</td>
						<td class='text-info'>$switchId</td>
					</tr>-->
				</table>
			</td>";
			
			
			echo "<td >
					<span class='".$macid."".$switchId."'>".$active."</span>
					<button class='item btn btn-warning' id='".$macid."".$switchId."' type='button'  onclick=update('$macid','$switchId') value='$macid'>Switch ".$action."</button>
					<br/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<big class='$macid'>$status</big>
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




