<?php
/*
*Project: IoT-Connected-valves-for-irrigation-of-greenhouse
*File name: dd.php
*Author: Jayant Solanki
*It is called in JAX mode by devdis.php for displaying devices information
*/
include 'settings/iotdb.php';
$groups=$_GET["groups"]; //get groups
$actions=$_GET["actions"]; //get action
$name=$_GET["name"]; //get name
$reactJS=$_GET["reactJS"]; //get name
if($groups!=null){

	$jsonArray=array();
	$query="SELECT * FROM groups";
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	
		while($row=mysql_fetch_assoc($results)) 
		{	
			$groupName=$row['name'];
			$groupId=$row['id'];
			$jsonArrayItem['text'] = $groupName;
			$jsonArrayItem['value'] = $groupId;
			array_push($jsonArray, $jsonArrayItem);
		}
		header('Content-type: application/json');
		//output the return value of json encode using the echo function. 
		echo json_encode($jsonArray);
		
	}
}
if($actions!=null){

	$jsonArray=array();
	$query="SELECT * FROM actionReact";
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	
		while($row=mysql_fetch_assoc($results)) 
		{	
			$actionName=$row['actionName'];
			$actionId=$row['id'];
			$jsonArrayItem['text'] = $actionName;
			$jsonArrayItem['value'] = $actionId;
			array_push($jsonArray, $jsonArrayItem);
		}
		header('Content-type: application/json');
		//output the return value of json encode using the echo function. 
		echo json_encode($jsonArray);
		
	}
}
if($name!=null){

	$jsonArray=array();
	$flag=true;
	/*if(true)
	{
		$jsonArrayItem['type'] = 'group';
		$jsonArrayItem['value'] = $_GET["group"];
		array_push($jsonArray, $jsonArrayItem);
		$jsonArrayItem['type'] = 'field';
		$jsonArrayItem['value'] = $_GET["field"];
		array_push($jsonArray, $jsonArrayItem);
		$jsonArrayItem['type'] = 'actionSelect';
		$jsonArrayItem['value'] = $_GET["actionSelect"];
		array_push($jsonArray, $jsonArrayItem);
		$jsonArrayItem['type'] = 'conditionCase';
		$jsonArrayItem['value'] = $_GET["conditionCase"];
		array_push($jsonArray, $jsonArrayItem);
		$jsonArrayItem['type'] = 'conditionVal';
		$jsonArrayItem['value'] = $_GET["conditionVal"];
		array_push($jsonArray, $jsonArrayItem);

	}
*/
	if(!isset($_GET["group"]) || $_GET["group"]==null || $_GET["group"] == 'undefined'){
		$jsonArrayItem['type'] = 'Error';
		$jsonArrayItem['value'] ='Group name missing';
		array_push($jsonArray, $jsonArrayItem);
		$flag=false;
	}
	else{
		$group=$_GET["group"];		
	}
	if(!isset($_GET["field"]) || $_GET["field"]==null || $_GET["field"]=='undefined'){
		$jsonArrayItem['type'] = 'Error';
		$jsonArrayItem['value'] = 'Field name missing';
		array_push($jsonArray, $jsonArrayItem);
		$flag=false;
	}
	else{
		$field=$_GET["field"];
	}
	if(!isset($_GET["actionSelect"]) || $_GET["actionSelect"]==null || $_GET["actionSelect"]=='undefined'){
		$jsonArrayItem['type'] = 'Error';
		$jsonArrayItem['value'] = 'Action name missing';
		array_push($jsonArray, $jsonArrayItem);
		$flag=false;
	}
	else{
		$actionSelect=$_GET["actionSelect"];
	}
	if($field!='Online/Offline' && (!isset($_GET["conditionCase"]) || $_GET["conditionCase"]==null || $_GET["conditionCase"]=='undefined')){
		$jsonArrayItem['type'] = 'Error';
		$jsonArrayItem['value'] = 'Condition case name missing';
		array_push($jsonArray, $jsonArrayItem);
		$flag=false;
	}
	else{
		$conditionCase=$_GET["conditionCase"];
	}
	if($field!='Online/Offline' && (!isset($_GET["conditionVal"]) || $_GET["conditionVal"]=='null' || $_GET["conditionVal"]=='undefined' || !intval($_GET["conditionVal"] ))){
		$jsonArrayItem['type'] = 'Error';
		$jsonArrayItem['value'] = 'Condition Value name missing';
		array_push($jsonArray, $jsonArrayItem);
		$flag=false;
	}
	else{
		$conditionVal=$_GET["conditionVal"];
	}
	if($field!='Online/Offline')
		$query="INSERT INTO reactJS (name, groupId, fieldId, conditionCase, conditionValue, actionId) VALUES ('$name',$group, '$field', '$conditionCase','$conditionVal',$actionSelect)";
	else
		$query="INSERT INTO reactJS (name, groupId, fieldId, actionId) VALUES ('$name',$group, '$field',$actionSelect)";
	//if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))		
	//	echo "INSERT failed: $query<br/>".mysql_error()."<br/><br/>";
	//echo $query;
	if($flag){
		if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass))){
			$jsonArrayItem['type'] = 'mysql Insert failed';
			$jsonArrayItem['value'] = mysql_error();
			array_push($jsonArray, $jsonArrayItem);
		}
		else{
			$jsonArrayItem['type'] = 'Success';
			$jsonArrayItem['value'] = 'Action Created';
			array_push($jsonArray, $jsonArrayItem);
		}
	}
	
	header('Content-type: application/json');
	//output the return value of json encode using the echo function. 
	echo json_encode($jsonArray);
}
if($reactJS!=null){

	$jsonArray=array();
	$query="SELECT * FROM reactJS";
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	
		while($row=mysql_fetch_assoc($results)) 
		{	
			$id=$row['id'];
			$name=$row['name'];
			$groupId=$row['groupId'];
			$fieldId=$row['fieldId'];
			$conditionCase=$row['conditionCase'];
			$conditionValue=$row['conditionValue'];
			$actionId=$row['actionId'];
			$activated=$row['activated'];
			$createdAt=$row['created_at'];
			//
			$grpq="SELECT name FROM groups where id=$groupId"; //getting group name
            $grpres=mysql_query($grpq);
            $grprow=mysql_fetch_assoc($grpres);
            $groupName=$grprow['name'];
            //
            $actionq="SELECT actionName FROM actionReact where id=$actionId"; //getting group name
            $actionRes=mysql_query($actionq);
            $actionRow=mysql_fetch_assoc($actionRes);
            $actionName=$actionRow['actionName'];

			$jsonArrayItem['id'] = $id;
			$jsonArrayItem['name'] = $name;
			$jsonArrayItem['groupName'] = $groupName;
			$jsonArrayItem['field'] = $fieldId;
			$jsonArrayItem['conditionCase'] = $conditionCase;
			$jsonArrayItem['conditionValue'] = $conditionValue;
			$jsonArrayItem['actionName'] = $actionName;
			$jsonArrayItem['activated'] = $activated;
			$jsonArrayItem['createdAt'] = $createdAt;
			array_push($jsonArray, $jsonArrayItem);
		}
		header('Content-type: application/json');
		//output the return value of json encode using the echo function. 
		echo json_encode($jsonArray);
		
	}
}
if(isset($_GET['del'])) //deleting the entry
{

	$del = $_GET['del'];
	$jsonArray=array();
	$query = "DELETE FROM reactJS WHERE id='$del'";

	if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
	{
		$jsonArrayItem['type'] = 'mysql Insert failed';
		$jsonArrayItem['value'] = mysql_error();
		array_push($jsonArray, $jsonArrayItem);
	}
	else{
		$jsonArrayItem['type'] = 'Success';
		$jsonArrayItem['value'] = "Task deleted";
		array_push($jsonArray, $jsonArrayItem);
	}
	header('Content-type: application/json');
	//output the return value of json encode using the echo function. 
	echo json_encode($jsonArray);

}
if(isset($_GET['notif'])) //deleting the entry
{

	$jsonArray=array();
	$query="SELECT * FROM deviceNotif";
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	
		while($row=mysql_fetch_assoc($results)) 
		{	
			$jsonArrayItem = array();
			$id=$row['id'];
			$deviceId=$row['deviceId'];
			$field1=$row['field1'];
			$field2=$row['field2'];
			$field3=$row['field3'];
			$field4=$row['field4'];
			$field5=$row['field5'];
			$field6=$row['field6'];
			$field1changeDate=$row['field1changeDate'];
			$field2changeDate=$row['field2changeDate'];
			$field3changeDate=$row['field3changeDate'];
			$field4changeDate=$row['field4changeDate'];
			$field5changeDate=$row['field5changeDate'];
			$field6changeDate=$row['field6changeDate'];
			$createdAt=$row['created_at'];

			$deviceq="SELECT type, switches, field1 FROM devices where deviceId='$deviceId'"; //getting group name
            $deviceRes=mysql_query($deviceq);
            $deviceRow=mysql_fetch_assoc($deviceRes);
            $deviceType=$deviceRow['type'];
            $switchCount=$deviceRow['switches'];
            if($switchCount==0){
				if($deviceRow['field1']=='b'){
					$jsonArrayItem['sensorType'] = 'b';
				}
				if($deviceRow['field1']=='bm'){
					$jsonArrayItem['sensorType'] = 'bm';
				}
				if($row['field1']=='bthm'){
					$deviceRow['sensorType'] = 'bthm';
				}
			}
			$feedfetch="SELECT field1, field2, field3, field4, field5, field6, created_at FROM feeds WHERE feeds.device_id='$deviceId' order by feeds.id desc limit 1";
			$feedres=mysql_query($feedfetch);
			$feed=mysql_fetch_assoc($feedres);
			
			if($deviceType==1 and $switchCount==1){//esp with 1 valve as secondary battery too
				$jsonArrayItem['Sbatvalue']=$feed['field3'];
				$jsonArrayItem['Pbatvalue']=$feed['field2'];
			}
			if($deviceType==2 and $switchCount==0){//device type is sensor
				$jsonArrayItem['Pbatvalue']=$feed['field3'];
				if($feed['field1']=='bm')
					$jsonArrayItem['moistValue']=$feed['field4'];
				else if($feed['field1']=='bthm'){
					$jsonArrayItem['tempValue']=$feed['field4'];
					$jsonArrayItem['humidValue']=$feed['field5'];
					$jsonArrayItem['moistValue']=$feed['field6'];
				}
			}
            $jsonArrayItem['deviceId'] = $deviceId;
			$jsonArrayItem['field1'] = $field1;
			$jsonArrayItem['field2'] = $field2;
			$jsonArrayItem['field3'] = $field3;
			$jsonArrayItem['field4'] = $field4;
			$jsonArrayItem['field5'] = $field5;
			$jsonArrayItem['field6'] = $field6;
			$jsonArrayItem['field1changeDate'] = $field1changeDate;
			$jsonArrayItem['field2changeDate'] = $field2changeDate;
			$jsonArrayItem['field3changeDate'] = $field3changeDate;
			$jsonArrayItem['field4changeDate'] = $field4changeDate;
			$jsonArrayItem['field5changeDate'] = $field5changeDate;
			$jsonArrayItem['field6changeDate'] = $field6changeDate;
			$jsonArrayItem['switchCount'] = $switchCount;
			$jsonArrayItem['deviceType'] = $deviceType;
			$jsonArrayItem['createdAt'] = $createdAt;
			array_push($jsonArray, $jsonArrayItem);
		}
		header('Content-type: application/json');
		//output the return value of json encode using the echo function. 
		echo json_encode($jsonArray);
		
	}

}
?>