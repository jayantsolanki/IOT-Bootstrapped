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
?>