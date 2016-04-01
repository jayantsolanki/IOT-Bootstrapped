
<?php
/*
*Project: IoT-Connected-valves-for-irrigation-of-greenhouse
*File name: managedev.php
*Author: Jayant Solanki
*It is called in AJAX mode, performing administrator work for the user i.e., adding removing
* sensors, groups, editing devices, alotting them groups and type of sensor
*/
include 'settings/iotdb.php';
$q=$_GET["q"]; //q is the group name received
$deviceId=$_GET['editdev'];
$switchId=$_GET['editswi'];
$id=$_GET['id'];
$updatedev=$_GET['updatedev'];
$updateswi=$_GET['updateswi'];
$gid=$_GET['gid'];
$del=$_GET['del'];
$dels=$_GET['dels'];
$ddel=$_GET['ddel'];
$dname=$_GET['dname'];
$sensor=$_GET['sensor'];
//$sentyp=$_GET['sentyp'];
if($q!=null)
{
	
	mysql_select_db($dbname) or die(mysql_error());
	$query="SELECT * FROM groups where"."(name='$q')";
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{
	echo "<div class='alert alert-danger'>Enter Unique group name</div>";
	}
	else
	{
		$query="INSERT into groups VALUES(DEFAULT,'$q')";
		
		if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
			echo "INSERT failed: $query".mysql_error()."<br/>";
		else
			echo "<div class='alert alert-success'><strong>'$q' added</strong></div>";
		
	}	
	

	$query="SELECT * FROM groups"; //displaying groups
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	$i=1;
		echo "</br></br><h2>Groups available</h2>";		
		while($row=mysql_fetch_assoc($results)) 
		{	//$id=$row['id'];
			$group=$row['name'];
		
		
			echo "<strong class='text-info'>".$i.".</strong>&nbsp; &nbsp; <big class=''>$group </big>&nbsp; &nbsp;<a class='text-danger glyphicon glyphicon-remove-circle' data-toggle='tooltip' title='Delete' href="."javascript:del('$group')"."></a><hr>";
			$i++;
		
		
		}
	}
	else
	{
		echo "</br><div class='notice'><b>No groups created yet.</b></div>";
	}
	

}
if($sensor!=null)
{
	
	mysql_select_db($dbname) or die(mysql_error());
	$query="SELECT * FROM sensors where"."(name='$sensor')";
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{
	echo "<div class='alert alert-danger'>Enter Unique sensor name</div>";
	}
	else
	{
		$query="INSERT into sensors VALUES(DEFAULT,'$sensor')";
		
		if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
			echo "INSERT failed: $query".mysql_error()."<br/>";
		else
			echo "<div class='alert alert-success'><strong>'$sensor' added</strong></div>";
		
	}	
	

	$query="SELECT * FROM sensors"; //displaying groups
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	$i=1;
		echo "</br></br><h2>Device Types available</h2>";		
		while($row=mysql_fetch_assoc($results)) 
		{	//$id=$row['id'];
			$sensor=$row['name'];
		
		
			echo "<strong class='text-info'>".$i.".</strong>&nbsp; &nbsp; <big class=''>$sensor </big>&nbsp; &nbsp;<a class='text-danger glyphicon glyphicon-remove-circle' data-toggle='tooltip' title='Delete' href="."javascript:dels('$sensor')"."></a><hr>";
			$i++;
		
		
		}
	}
	else
	{
		echo "</br><div class='notice'><b>No Sensors added yet.</b></div>";
	}
	

}
if($deviceId!=null and $switchId!=null)//update group and sensor type for selected deviceS
{

echo "<label>&nbsp;Name</label>&nbsp;<input type='text' id='dname' name='dname' placeholder='name the device' required/> ".groups()."<button class='btn btn-danger' id='$deviceId' type='button' onclick="."update('$deviceId','$switchId')".">Update</button>";

}

if($updatedev!=null and $updateswi!=null)//perform the updation task
{
	
	mysql_select_db($dbname) or die(mysql_error());
	if($gid!=null and $dname!=null){//update only if both fields are not empty
		$query="SELECT name FROM groups WHERE id='$gid'";
		$grps=mysql_query($query);
		$grp=mysql_fetch_assoc($grps);
		$name=$grp['name'];
		if($updateswi==0){//simply the device
			$query = "UPDATE devices SET devices.groupId = '$gid', devices.status=0, devices.name='$dname' WHERE devices.deviceId = '$updatedev'"; //updating item such as sensors which dont have switches
			if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
			echo "UPDATE failed: $query<br/>".mysql_error()."<br/><br/>";
		}
		else{//updating the switch and device
			$query = "UPDATE switches SET switches.groupId = '$gid', switches.newSwitch=0 WHERE switches.deviceId = '$updatedev' and switches.switchId=$updateswi"; //updating switche with group Id
			if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
				echo "UPDATE failed: $query<br/>".mysql_error()."<br/><br/>";
			//updating the name
			$query = "UPDATE devices SET devices.status=0, devices.name='$dname' WHERE devices.deviceId = '$updatedev'"; //updating item such as sensors which dont have switches
					
			if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
				echo "UPDATE failed: $query<br/>".mysql_error()."<br/><br/>";		
		}

		
	}
	else{
		if($updateswi==0)
			$query="SELECT devices.name as dname, groups.name as name FROM groups inner join devices on devices.groupid=groups.id WHERE devices.deviceId='$deviceId'";
		else
			$query="SELECT devices.name as dname, groups.name as name FROM groups inner join switches on switches.groupid=groups.id inner join devices on devices.deviceId = switches.deviceId WHERE switches.deviceId='$deviceId' and switches.switchId=$switchId";
		$grps=mysql_query($query);
		$grp=mysql_fetch_assoc($grps);
		$name=$grp['name'];
		$dname=$grp['name'];
	}
	echo "
        <span id='".$updatedev."".$updateswi."'><strong class='text-info'>Updated</strong>&nbsp; &nbsp; 
        <big><strong>Name:</strong> <span class='text-danger'>$dname</span></big>
        <big><strong>Device:</strong> <span class='text-muted'>$updatedev";
    if($updateswi!=0)
        echo"/<big class='text-danger'data-toggle='tooltip' title='Switch $updateswi' >$updateswi</big>";
    echo"</span></big> &nbsp;
         <big><strong>Type:</strong> <span class='text-danger'>$type</span></big>";
    echo"
        <big><strong>Group:</strong> <span class='text-danger'>$name</span></big>
         &nbsp; &nbsp;<a class='text-muted glyphicon glyphicon-pencil' data-toggle='tooltip' title='Edit' href="."javascript:edit('$updatedev','$updateswi')"."></a>
         &nbsp; &nbsp;<a class='text-danger glyphicon glyphicon-remove-circle' data-toggle='tooltip' title='Delete' href="."javascript:ddel('$updatedev','$updateswi')"."></a></span> 
         </span>";

	//echo " <span id='$update' style='color:#3B5998;font-weight:normal;'><b></b><b>MAC id:</b> $update &nbsp; &nbsp;<b>Group:</b> $name&nbsp; &nbsp; <a href="."javascript:edit('$update')".">edit</a></span>";
	
	
}

if($del!=null)//perform deletion task for group
{
	
	$query = "DELETE FROM groups WHERE name='$del'";

	if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
	echo "Deletion failed: $query<br/><div class='alert alert-danger'>".mysql_error()."</div><br/><br/>";
	else
	{
		echo "<div class='alert alert-success'><strong>'$del' deleted</strong></div>";		
	}
	$query="SELECT * FROM groups"; //displaying groups
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	$i=1;
		echo "</br></br><h2>Groups available</h2>";		
		while($row=mysql_fetch_assoc($results)) 
		{	//$id=$row['id'];
			$group=$row['name'];
		
		
			echo "<strong class='text-info'>".$i.".</strong>&nbsp; &nbsp; <big class=''>$group </big>&nbsp; &nbsp;<a class='text-danger glyphicon glyphicon-remove-circle' data-toggle='tooltip' title='Delete' href="."javascript:del('$group')"."></a><hr>";
			$i++;
		
		
		}
	}
	else
	{
		echo "</br><div class='alert alert-info'><b>No groups created yet.</b></div>";
	}
	
	
}
if($dels!=null) //for deleting selected sensor type
{
	
	$query = "DELETE FROM sensors WHERE name='$dels'";

	if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
	echo "Deletion failed: $query<br/><div class='alert alert-danger'>".mysql_error()."</div><br/><br/>";
	else
	{
		echo "<div class='alert alert-success'><strong>'$dels' deleted</strong></div>";		
	}
	$query="SELECT * FROM sensors"; //displaying groups
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	$i=1;
		echo "</br></br><h2>Device Types available</h2>";		
		while($row=mysql_fetch_assoc($results)) 
		{	//$id=$row['id'];
			$sensor=$row['name'];
		
		
			echo "<strong class='text-info'>".$i.".</strong>&nbsp; &nbsp; <big class=''>$sensor </big>&nbsp; &nbsp;<a class='text-danger glyphicon glyphicon-remove-circle' data-toggle='tooltip' title='Delete' href="."javascript:dels('$sensor')"."></a><hr>";
			$i++;
		
		
		}
	}
	else
	{
		echo "</br><div class='notice'><b>No Sensor added yet.</b></div>";
	}
	
	
}
if($ddel!=null) //for deleting selected device
{
	
	$query = "DELETE FROM devices WHERE devices.macid='$ddel'";
	if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
	echo "Deletion failed: $query<br/><div class='alert alert-danger'>".mysql_error()."</div><br/><br/>";
	else
	{
		echo "<div class='alert alert-success'><strong>'$ddel' deleted</strong></div>";		
	}
	$query="SELECT * FROM devices"; //displaying groups
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	$i=1;
		echo "</br></br><h2>Items available</h2>";		
		while($row=mysql_fetch_assoc($results)) 
		{	$macid=$row['macid'];
			$group=$row['group'];
			//$group=$row['name'];
			$query="SELECT name FROM groups WHERE id='$group'";
			$grps=mysql_query($query);
			$grp=mysql_fetch_assoc($grps);
			$name=$grp['name'];
			if($name=='')
			 	$name="<span class='label label-info'><b>New Device Found</b></span>";
			echo "<strong class='text-info'>".$i.".</strong>&nbsp; &nbsp; <big id='$macid'><strong>Device id:</strong> <span class='text-info'>$macid</span></big> &nbsp; &nbsp;<big><strong>Group:</strong> <span class='text-danger'>$name</span></big> &nbsp; &nbsp;<a class='text-muted glyphicon glyphicon-pencil' data-toggle='tooltip' title='Edit' href="."javascript:edit('$macid')"."></a>&nbsp; &nbsp;<a class='text-danger glyphicon glyphicon-remove-circle' data-toggle='tooltip' title='Delete' href="."javascript:ddel('$macid')"."></a></span><hr>";
			$i++;
			
			
		}
	}
	else
	{
		echo "</br><div class='notice'><b>No devices added yet.</b></div>";
	}
	
	
}
 /*
 *
 * Function Name: groups()
 * Input: -
 * Output: displays the selection menu for group
 * 
 *
 */

function groups()
{
$dbname='IOT';
mysql_select_db($dbname) or die(mysql_error());
$query="SELECT * FROM groups"; //displaying groups
$results=mysql_query($query);
echo "<label>Choose Group for the Switch</label>";	
echo "<select lass='form-control' id='groupadd'>";	
if (mysql_num_rows($results) > 0) 
	{
	echo "<option selected='true' disabled='disabled' value=0>Choose</option>";
	while($row=mysql_fetch_assoc($results)) 
		{	//$id=$row['id'];
			$group=$row['name'];
			$id=$row['id'];
		
			echo "<option value='$id'>$group </option>";
			$i++;
		
		
		}
	}
else
	{
		echo "<option value=''>Create a group first </option>";
	}
echo "</select>";

}
 /*
 *
 * Function Name: sensors()
 * Input: -
 * Output: displays the selection menu for group
 * 
 *
 */
function sensors()
{
$dbname='IOT';
mysql_select_db($dbname) or die(mysql_error());
$query="SELECT * FROM sensors"; //displaying groups
$results=mysql_query($query);
echo "<label>Choose Type</label>";
echo "<select lass='form-control' id='sensoradd'>";	
if (mysql_num_rows($results) > 0) 
	{
	echo "<option selected='true' disabled='disabled'>Choose</option>";
	while($row=mysql_fetch_assoc($results)) 
		{	//$id=$row['id'];
			$name=$row['name'];
			$id=$row['id'];
		
			echo "<option value='$id'>$name </option>";
			$i++;
		
		
		}
	}
else
	{
		echo "<option value=''>Add a sensor first </option>";
	}
echo "</select>";

}

?>

