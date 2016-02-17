<?php 
/*
*Project: eYSIP_2015_IoT-Connected-valves-for-irrigation-of-greenhouse
*Team members: Jayant Solanki, Kevin D'Souza
*File name: control.php
*Author: Jayant Solanki
*this is the page called in AJAX mode, displaying all the manual switch controls for esp modules
*/
include_once 'iotdb.php';
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1); //for suppressing errors and notices

?>

<?php
$grp=$_GET["grp"];
if(isset($_GET['grp']))
{
	echo " <button id='1' type='button' onclick='updateall(this.value)' value='1'>Switch all ON</button>";
	echo " <button id='0' type='button' onclick='updateall(this.value)' value='0'>Switch all OFF</button>";
	echo "</br></br><span style='color:#3B5998;font-weight:normal;'>Duration:(mm)</span></br>";

	echo "Mins:<select id='duration' name='duration'>";
	$j=1; 
	while($j<=60)
	{
	echo "<option value='$j'>$j</option>";
	$j=$j+1;
	} 
	echo "</select><hr>";
	

	mysql_select_db($dbname) or die(mysql_error());
	$query="SELECT name FROM groups WHERE id='$grp'";
	$grps=mysql_query($query);
	$rows=mysql_fetch_assoc($grps);
	$gname=$rows['name'];
	
	$query="SELECT * FROM devices WHERE devices.group=$grp";
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	$i=0;
		
			
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
			if($action==1) //changing into user readable form
				$action='OFF';
			else
				$action='ON';
			
		
			if($status==0) //offline
				$status="<span style='color: #FF0000;'>Device offline, please check..</span>";
			elseif($status==1) //online
				$status="<span style='color: #00CC00;'>ONLINE</span>";
			elseif($status==2) //new device
				$status="<span style='color: #0088FF;'>New Device Found</span>";

			echo "<b style='color:#3B5998;font-weight:bold;'>".$i.". $sname sensor: $name </b>&nbsp; &nbsp;<bstyle='color:#3B5998;font-weight:bold;'>Group:</b> $gname &nbsp;<span style='color:#3B5998;font-weight:normal;'><button class='item' id='$macid' type='button' onclick='update(this.value)' value='$macid'>Switch ".$action."</button> ".$status."<hr>";
			
		
		
		}
	}
	else
	{
	echo "<h3>No Devices added yet</h3>";
	}
}
?>



