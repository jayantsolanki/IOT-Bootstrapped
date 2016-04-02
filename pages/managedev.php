
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
$ddeldev=$_GET['ddeldev'];
$ddelswi=$_GET['ddelswi'];
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
	mysql_select_db($dbname) or die(mysql_error());
	$query="SELECT name FROM devices WHERE deviceId='$deviceId'";
	$devnames=mysql_query($query);
	$devrow=mysql_fetch_assoc($devnames);
	$name=$devrow['name'];	
	echo "<label>&nbsp;Name</label>&nbsp;<input type='text' id='dname' name='dname' placeholder='name the device' value='$name' required/> ".groups($switchId)."<button class='btn btn-danger' id='$deviceId' type='button' onclick="."update('$deviceId','$switchId')".">Update</button> <button class='btn btn-info' id='$deviceId' type='button' onclick="."update(0,0)".">Cancel</button>";
}

if($updatedev!=null and $updateswi!=null)//perform the updation task
{
	
	mysql_select_db($dbname) or die(mysql_error());
	if($updatedev==0 and $updateswi==0){
		display();
	}
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
	/*echo "
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
         </span>";*/
        display();

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
if($ddeldev!=null and $ddelswi!=null) //for deleting selected device
{
	if($ddeldev!=0 and $ddelswi==0){//delete the device and its related switches
		$query = "DELETE FROM devices WHERE devices.deviceId='$ddeldev'";
		if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
		echo "Deletion failed: $query<br/><div class='alert alert-danger'>".mysql_error()."</div><br/><br/>";
		$query = "DELETE FROM switches WHERE switches.deviceId='$ddeldev'";//deleting all concerned device switches
		if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
		echo "Deletion failed: $query<br/><div class='alert alert-danger'>".mysql_error()."</div><br/><br/>";
	}	
	if($ddeldev!=0 and $ddelswi!=0){//delete the switches
		$query = "DELETE FROM switches WHERE switches.deviceId='$ddeldev' and switches.switchId=$ddelswi";
		if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))//deleting concerned device switch
		echo "Deletion failed: $query<br/><div class='alert alert-danger'>".mysql_error()."</div><br/><br/>";
	}	
	display();
}
 /*
 *
 * Function Name: groups()
 * Input: -
 * Output: displays the selection menu for group
 * 
 *
 */

function groups($switchId)
{
$dbname='IOT';
mysql_select_db($dbname) or die(mysql_error());
$query="SELECT * FROM groups"; //displaying groups
$results=mysql_query($query);
if($switchId!=0)
	echo "<label>Choose Group for the Switch $switchId</label>";	
else 
	echo "<label>Choose Group for the Device</label>";
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

 /*
 *
 * Function Name: sensors()
 * Input: -
 * Output: displays the device list
 * 
 *
 */
function display(){
      
      $query="SELECT devices.name as name,devices.groupId as dgroupId, devices.type as type, devices.status as status, devices.deviceId as deviceId, switches.switchId as switchId, switches.groupId as sgroupId, switches.newSwitch as newSwitch, switches.created_at as created_at FROM devices left join switches on switches.deviceId=devices.deviceId where devices.switches>=0"; //displaying groups
      $results=mysql_query($query);
      if (mysql_num_rows($results) > 0) 
      {   $i=1;    
          while($row=mysql_fetch_assoc($results)) 
          {   
              $deviceId=$row['deviceId'];//for switches
              $switchId=$row['switchId'];//for switches
              $deviceName=$row['name'];//for devices
              $sgroupId=$row['sgroupId'];//for switches
              $dgroupId=$row['dgroupId']; //for devices
              $sstatus=$row['newSwitch'];
              $dstatus=$row['status'];
              $type=$row['type'];
              $created_at=$row['created_at'];
              //$group=$row['name'];
              

              $query="SELECT name FROM sensors WHERE id=$type";
              $typename=mysql_query($query);
              $typerow=mysql_fetch_assoc($typename);
              $type=$typerow['name'];
              $status=null;
              if($switchId==null){//if it is a switchless device like sensor node
                $groupId=$dgroupId;

                $switchId=0;
                //if($dstatus==1)
                  //$status="<span data-toggle='tooltip' title='New Device' class='text-info fa fa-cog fa-spin fa-2x'></span>";
              }
              else
                $groupId=$sgroupId;
              $query="SELECT name FROM groups WHERE id=$groupId";
              $grps=mysql_query($query);
              $grp=mysql_fetch_assoc($grps);
              $name=$grp['name'];
              if($switchId!=0 and $sstatus==1)
                $status="<span data-toggle='tooltip' title='New Device' class='text-info fa fa-cog fa-spin fa-2x'></span>";
              if($switchId==0 and $dstatus==1)
                $status="<span data-toggle='tooltip' title='New Device' class='text-info fa fa-cog fa-spin fa-2x'></span>";
              echo "
              <span id='".$deviceId."".$switchId."'><strong class='text-info'>".$i.".</strong>&nbsp; &nbsp; 
              <big><strong>Name:</strong> <span class='text-danger'>$deviceName</span></big>
              <big><strong>Device:</strong> <span class='text-muted'>$deviceId";
              if($switchId!=0)
                echo"/<big class='text-danger'data-toggle='tooltip' title='Switch $switchId' >$switchId</big>";
              echo"</span></big> &nbsp;
              <big><strong>Type:</strong> <span class='text-danger'>$type</span></big>";
              
              echo"
              <big><strong>Group:</strong> <span class='text-danger'>$name</span></big>
               &nbsp; &nbsp;<a class='text-muted glyphicon glyphicon-pencil' data-toggle='tooltip' title='Edit' href="."javascript:edit('$deviceId','$switchId')"."></a>
               &nbsp; &nbsp;";
               if($switchId!=0){//delete switches
                 echo"
                 <a class='text-danger glyphicon glyphicon-remove-circle' data-toggle='tooltip' title='Delete' href="."javascript:ddel('$deviceId','$switchId')"."></a>";
               }
               echo"
               &nbsp; &nbsp;<big><a class='text-danger glyphicon glyphicon-remove' data-toggle='tooltip' title='Remove device and its switches' href="."javascript:ddel('$deviceId',0)"."></a></big>";
              echo"
               </span>&nbsp;<strong><big>$status</big></strong><br/><hr/></span>";
              $i++;
          }
      }
      else
      {
          echo "</br><div class='notice'><b>No devices added yet.</b></div>";
      }
    }


?>

