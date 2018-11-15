<?php //mqtt settings
require 'settings/iotdb.php';
//mysql_select_db($dbname) or die(mysql_error());
$query="SELECT * FROM global_variables where variable_name='mqtt'";
$results=mysqli_query($con, $query);
$mqttaddress=null;
if (mysqli_num_rows($results) > 0) 
{
	while($row = mysqli_fetch_assoc($results))
	{
		$mqttaddress=$row['value'];
	}
}
//$mqttaddress='tcp://10.129.28.181:1880/';

?>
