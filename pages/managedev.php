
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
$deviceSetting=$_GET['deviceSetting'];
$deviceData=$_POST['deviceData'];
$editgrp=$_GET['editGrp'];
$updategrpName=$_GET['updategrpName'];
$updategrpId=$_GET['updategrpId'];
$editSensor=$_GET['editSensor'];
$updatesensorName=$_GET['updatesensorName'];
$updatesensorId=$_GET['updatesensorId'];
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
if($editgrp!=null or $editSensor!=null)//editing the groupname or sensor name
{
	mysql_select_db($dbname) or die(mysql_error());
	if($editgrp!=null){
		$query="SELECT name FROM groups WHERE id='$editgrp'";
		$groupname=mysql_query($query);
		$gname=mysql_fetch_assoc($groupname);
		$name=$gname['name'];	
		echo "<label>&nbsp;Group Name</label>&nbsp;<input type='text' id='gname' name='gname' placeholder='name the group' value='$name' required/> <button class='btn btn-danger' type='button' onclick='updateName($editgrp, 1)'>Update</button>";
	}
	if($editSensor!=null){
		$query="SELECT name FROM sensors WHERE id='$editSensor'";
		$sensorname=mysql_query($query);
		$sname=mysql_fetch_assoc($sensorname);
		$name=$sname['name'];	
		echo "<label>&nbsp;Device type</label>&nbsp;<input type='text' id='sname' name='sname' placeholder='name the device type' value='$name' required/> <button class='btn btn-danger' type='button' onclick='updateName($editSensor, 0)'>Update</button>";
	}
}
if($updategrpName!=null or $updatesensorName!=null)//editing the groupname
{
	mysql_select_db($dbname) or die(mysql_error());
	if($updategrpName!=null){//updating group
		$query = "UPDATE groups SET groups.name = '$updategrpName' WHERE groups.id = '$updategrpId'"; //updating group name
		if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
			echo "UPDATE failed: $query<br/>".mysql_error()."<br/><br/>";
		$query="SELECT * FROM groups"; //displaying groups
		$results=mysql_query($query);
		if (mysql_num_rows($results) > 0) 
			{   $i=1;
			  echo "</br></br><big>Groups available</big><hr/>";     
			  while($row=mysql_fetch_assoc($results)) 
			  {   //$id=$row['id'];
			      $id=$row['id'];
			      $group=$row['name'];
			      
			      
			      echo "<span id='grp".$id."'><strong class='text-info'>".$i.".</strong>&nbsp; &nbsp; <big class=''>$group </big>&nbsp; &nbsp;<a class='text-muted glyphicon glyphicon-pencil' data-toggle='tooltip' title='Edit group name' href='javascript:editName($id, 1)'></a>&nbsp; &nbsp;<a class='text-danger glyphicon glyphicon-remove-circle' data-toggle='tooltip' title='Delete' href="."javascript:del('$group')"."></a></span><hr>";
			      $i++;
			      
			      
			  }
			}
		else
			{
			  echo "</br><div class='notice'><b>No groups created yet.</b></div>";
			}
	}
	if($updatesensorName!=null){//updating group
		$query = "UPDATE sensors SET sensors.name = '$updatesensorName' WHERE sensors.id = '$updatesensorId'"; //updating group name
		if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
			echo "UPDATE failed: $query<br/>".mysql_error()."<br/><br/>";
		$query="SELECT * FROM sensors"; //displaying groups
        $results=mysql_query($query);
        if (mysql_num_rows($results) > 0) 
	        {   $i=1;
	            echo "</br></br><big>Device Types available</big><hr/>";        
	            while($row=mysql_fetch_assoc($results)) 
	            {   //$id=$row['id'];
	                $id=$row['id'];
	                $sensor=$row['name'];
	                echo "<span id='sens".$id."'><strong class='text-info'>".$i.".</strong>&nbsp; &nbsp; <big class=''>$sensor </big>&nbsp; &nbsp;<a class='text-muted glyphicon glyphicon-pencil' data-toggle='tooltip' title='Edit device type' href='javascript:editName($id, 0)'></a>&nbsp; &nbsp;<a class='text-danger glyphicon glyphicon-remove-circle' data-toggle='tooltip' title='Delete' href="."javascript:dels('$sensor')"."></a></span><hr>";
	                $i++;
	                
	                
	            }
	        }
	    else
	        {
	            echo "</br><div class='notice'><b>No Sensors added yet.</b></div>";
	        }

	}

}


if($deviceId!=null and $switchId!=null)//show update fields for selected swtich
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

	if($gid!=null and $dname!=null and ($updatedev!=0 and $updateswi!=0)){//update only if both fields are not empty
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

		display();
	}
	else{
		echo $updatedev." switch ".$updateswi." groupid ".$gid." dname ".$dname;
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

if($deviceSetting!=null){
	mysql_select_db($dbname) or die(mysql_error());
	$query = "SELECT * FROM devices WHERE deviceId='$deviceSetting'";
	$device=mysql_query($query);
	$dev=mysql_fetch_assoc($device);
	$deviceId=$dev['deviceId'];
	$deviceName=$dev['name'];
	$deviceDesc=$dev['description'];
	$type=$dev['type'];
	$switches=$dev['switches'];
	$regionId=$dev['regionId'];
	$groupId=$dev['groupId'];
	$latitude=$dev['latitude'];
	$longitude=$dev['longitude'];
	$elevation=$dev['elevation'];
	$status=$dev['status'];
	$field1=$dev['field1'];
	$field2=$dev['field2'];
	$field3=$dev['field3'];
	$field4=$dev['field4'];
	$field5=$dev['field5'];
	$field6=$dev['field6'];
	$created=$dev['created_at'];
	$updated=$dev['updated_at'];
	$query="SELECT name FROM groups WHERE id=$groupId";
	$grps=mysql_query($query);
	$grp=mysql_fetch_assoc($grps);
	$groupName=$grp['name'];

	echo"
		<div class='modal-body' id='deviceInfo'>
			<div class='alert alert-info'>";
			if($status==1)
				echo"<strong>New Device! </strong>";
			echo"
				Device created on $created, last updated on $updated
			</div>
			<div class='row'>
				<fieldset class='form-group'>
					<label>Name:</label>
					$deviceName
				</fieldset>
				<fieldset class='form-group'>
					<label>Region:</label>
					$deviceDesc
				</fieldset>
				<fieldset class='form-group'>
					<label>Group:</label>
					$groupName
				</fieldset>
				<fieldset class='form-group'>
					<label>Switches:</label>
					$switches
				</fieldset>
				<fieldset class='form-group'>
					<label>Description:</label>
					$deviceDesc
				</fieldset>
				<fieldset class='form-group'>";
				if($longitude!=null and $latitude!=null)
				echo"
				<big class='text-info'>Device Location</big>
				<label class='btn btn-primary badge' onclick="."togglemap()".">Map</label>
				<fieldset class='form-group'>
					<label>elevation:</label>
					$elevation
				</fieldset>
				<div id='map' style='width: 100%; height: 250px; display: none;' >
					<iframe height='100%' src='http://maps.google.com/maps?q=$latitude,$longitude&z=12&output=embed'></iframe>
				</div>";
				if($field1!=''||$field2!=''||$field3!=''||$field4!=''||$field5!=''||$field6!=''){
					echo"
					<big class='text-info'>Field lists</big>
					<div class='form-inline'>";
					if($field1!='')
						echo"
		                  <fieldset class='form-group'>
		                    <label for='field1'>Field 1</label>
		                    $field1
		                  </fieldset>";
		            if($field2!='')
						echo"
		                  <fieldset class='form-group'>
		                    <label >Field 2</label>
		                    $field2
		                  </fieldset>";
					if($field3!='')
						echo"
		                  <fieldset class='form-group'>
		                    <label >Field 3</label>
		                    $field3
		                  </fieldset>";
					if($field4!='')
						echo"
		                  <fieldset class='form-group'>
		                    <label >Field 4</label>
		                    $field4
		                  </fieldset>";
					if($field5!='')
						echo"
		                  <fieldset class='form-group'>
		                    <label >Field 5</label>
		                    $field5
		                  </fieldset>";
					if($field6!='')	
						echo"
		                  <fieldset class='form-group'>
		                    <label >Field 6</label>
		                    $field6
		                  </fieldset>";
					echo"
	                </div>";
	            }
				echo"
			</div>
		</div>
		";
	echo"
		<div class='modal-body' id='editDevice' style='display: none;'>
                                    <big class='text-danger'>Device Details</big>
                                    <hr/>
                                    <fieldset class='form-group'>
                                      <label for='devicename'>Device Name</label>
                                      <input type='text' class='form-control' id='devicename' placeholder='Device Name' value='$deviceName'>
                                      <small class='text-muted'>A good name, not the Pokemon type, creativity welcomed</small>
                                    </fieldset>
                                    <fieldset class='form-group'>
                                      <label for='regionid'>Select Region</label>
                                      <select class='form-control' id='regionid'>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                      </select>
                                      <small class='text-muted'>Experimental! Choose the place where the device is located</small>
                                    </fieldset>
                                    <fieldset class='form-group'>
                                      <label for='groupid'>Select Group</label>
                                      <select class='form-control' id='groupid'>";
	$groupquery="SELECT * FROM groups"; //displaying groups
	$gresults=mysql_query($groupquery);	
	if (mysql_num_rows($gresults) > 0) 
		{
		echo "<option disabled='disabled' value=''>Choose</option>";
		while($row=mysql_fetch_assoc($gresults)) 
			{	//$id=$row['id'];
				$group=$row['name'];
				$id=$row['id'];
				if($groupId==$id)
					echo "<option selected='selected' value='$id'>$group</option>";
				else 
					echo "<option value='$id'>$group</option>";

				$i++;
			
			
			}
		}
	else
		{
			echo "<option disabled='disabled' value=''>Create a group first </option>";
		}
     echo"
                                      </select>
                                      <small class='text-muted'>Choose a group category for the Device</small>
                                    </fieldset>
                                    <fieldset class='form-group'>
                                      <label for='deviceDesc'>Description</label>
                                      <textarea class='form-control' id='deviceDesc' rows='3' >$deviceDesc</textarea>
                                      <small class='text-muted'>Tell us more about the device</small>
                                    </fieldset>
                                    <big class='text-danger'>Device Location</big>
                                    <hr/>
                                    <div class='form-inline'>
                                        
                                      <fieldset class='form-group'>
                                        <label for='latitude'>Latitude</label>
                                        <input type='text' class='form-control' id='latitude' placeholder='Enter Latitude' value='$latitude'>
                                      </fieldset>
                                      <fieldset class='form-group'>
                                        <label for='longitude'>Longitude</label>
                                        <input type='text' class='form-control' id='longitude' placeholder='Enter Longitude' value='$longitude'>
                                      </fieldset>
                                    </div>
                                    <fieldset class='form-group'>
                                      <label for='elevation'>Elevation</label>
                                      <input type='text' class='form-control' id='elevation' placeholder='Enter elevation' value='$elevation'>
                                    </fieldset>
                                    <big class='text-danger'>Fields for the Sensors connected to the device</big>
                                    <small class='text-muted'>Like temperature, moisture, or humidity sensors</small>
                                    <hr/>
                                    <div class='form-inline'>
                                      <fieldset class='form-group'>
                                        <label for='field1'>Field 1</label>
                                        <input type='text' class='form-control' id='field1' placeholder='primary battery Value' value='$field1'>
                                      </fieldset>
                                      <fieldset class='form-group'>
                                        <label for='field2'>Field 2</label>
                                        <input type='text' class='form-control' id='field2' placeholder='secondary battery Value' value='$field2'>
                                      </fieldset>
                                      <fieldset class='form-group'>
                                        <label for='field3'>Field 3</label>
                                        <input type='text' class='form-control' id='field3' placeholder='packet number' value='$field3'>
                                      </fieldset>
                                      <fieldset class='form-group'>
                                        <label for='field4'>Field 4</label>
                                        <input type='text' class='form-control' id='field4' placeholder='Enter elevation' value='$field4'>
                                      </fieldset>
                                      <fieldset class='form-group'>
                                        <label for='field5'>Field 5</label>
                                        <input type='text' class='form-control' id='field5' placeholder='Enter elevation' value='$field5'>
                                      </fieldset>
                                      <fieldset class='form-group'>
                                        <label for='field6'>Field 6</label>
                                        <input type='text' class='form-control' id='field6' placeholder='Enter elevation' value='$field6'>
                                      </fieldset>
                                    </div>
                                    <button class='btn btn-primary' onclick="."saveData('$deviceId')".">Submit</button>
                              </div>";
}

if($deviceData!=null){//for device updating
	$deviceId=$_POST['deviceId'];
	$name=$_POST['name'];
	$regionId=$_POST['regionId'];
	$groupId=$_POST['groupId'];
	$deviceInfo=$_POST['deviceInfo'];
	$latitude=$_POST['latitude'];
	$longitude=$_POST['longitude'];
	$elevation=$_POST['elevation'];
	$field1=$_POST['field1'];
	$field2=$_POST['field2'];
	$field3=$_POST['field3'];
	$field4=$_POST['field4'];
	$field5=$_POST['field5'];
	$field6=$_POST['field6'];
	if($field1=='')
		$field1=null;
	if($field2=='')
		$field2=null;
	if($field3=='')
		$field3=null;
	if($field4=='')
		$field4=null;
	if($field5=='')
		$field5=null;
	if($field6=='')
		$field6=null;

	if($name!='' || $groupId!='' || $deviceInfo!='' || $latitude!='' || $longitude!='' || $elevation!='')
		$status=0;
	else
		$status=1;
	mysql_select_db($dbname) or die(mysql_error());
	$query="UPDATE devices SET devices.name='$name', description='$deviceInfo', groupId='$groupId', latitude='$latitude', longitude='$longitude', elevation='$elevation', field1='$field1', field2='$field2', field3='$field3', field4='$field4', field5='$field5', field6='$field6',status=$status, updated_at=now() WHERE devices.deviceId='$deviceId'";
		
	if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
		echo "Update failed: $query".mysql_error();
	else
		echo "<div class='text-center alert alert-success'><strong>Device Data updated</strong></div>";
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
                $status="<span data-toggle='tooltip' title='New Device' class='text-info fa fa-circle-o-notch fa-spin'></span>";
              if($switchId==0 and $dstatus==1)
                $status="<span data-toggle='tooltip' title='New Device' class='text-info fa fa-circle-o-notch fa-spin'></span>";
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
               &nbsp; &nbsp;<a class='text-muted glyphicon glyphicon-pencil' data-toggle='tooltip' title='Edit Switch' href="."javascript:edit('$deviceId','$switchId')"."></a>
               &nbsp; &nbsp;";
               if($switchId!=0){//delete switches
                 echo"
                 <a class='text-danger glyphicon glyphicon-remove-circle' data-toggle='tooltip' title='Delete' href="."javascript:ddel('$deviceId','$switchId')"."></a>";
               }
               echo"
               &nbsp; &nbsp;<big><a class='text-danger glyphicon glyphicon-trash' data-toggle='tooltip' title='Remove device and its switches' href="."javascript:ddel('$deviceId',0)"."></a></big>";
              echo"
              &nbsp; &nbsp;<a class='text-danger glyphicon glyphicon-wrench' data-toggle='tooltip' title='Device Settings' href="."javascript:deviceSetting('$deviceId')"."></a>
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

