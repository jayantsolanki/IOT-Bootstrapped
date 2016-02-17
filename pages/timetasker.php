<?php
/*
*Project: eYSIP_2015_IoT-Connected-valves-for-irrigation-of-greenhouse
*Team members: Jayant Solanki, Kevin D'Souza
*File name: timetasker.php
*Author: Jayant Solanki
*It is called in ajax mode, performing time entries for tasks scheduled.
*/
include_once 'iotdb.php';
mysql_select_db($dbname) or die(mysql_error());
//echo "Hello".$_GET['stoph'];
if(isset($_GET['grp']))
{
	
	$grp=$_GET['grp'];
	$starth = $_GET['starth'];
	$startm = $_GET['startm'];
	$stoph = $_GET['stoph'];
	$stopm = $_GET['stopm'];
	$frequency =$_GET['frequency'];
	$duration =$_GET['duration'];
	$repeath=$_GET['repeath'];
	
	if($starth==24) //normalising time,, 24 is same as 00,, in 2400 and 0000
		$starth=0;
	if($stoph==24)
		$stoph=0;
	$start=$starth*100+$startm;
	$stop=$stoph*100+$stopm;
	

	if($duration!=NULL and $repeath==NULL) //for setting duration without repeat
	{
		$stop=$starth*100 + normalize($startm,$duration);

		if($stop>2400)
			$stop=$stop-2400;
	}

	if($repeath!=NULL) //for setting time with repetitions, 4/8/12 hrs
	{	$i=$start;
		
		while($i<=2400) //time should be greater than 2400, military format
		{	
			$query="SELECT name FROM groups WHERE id='$grp'";
			$grps=mysql_query($query);
			$grp=mysql_fetch_assoc($grps);
			$name=$grp['name'];
			$start=$i;
			$stop=$start+$duration;
			if($stop>2400)
				break;
			if($stop==2400) //marks stop time as 0000
				$stop=0;
			$query="INSERT INTO tasks VALUES". "(DEFAULT,'$name', NULL,'$start','$stop', '1','1')";
			//if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))		
			//	echo "INSERT failed: $query<br/>".mysql_error()."<br/><br/>";
			//echo $query;
			if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
				echo "INSERT failed: $query<br/>".mysql_error()."<br/><br/>";
			
				
			
			$i=$i+$repeath*100;		
		}
		echo "</br></br><span class='success'><b>New Time schedule added</b></span>";	
	}
		if($repeath==NULL) // for setting period
			if($start==$stop) //start time cannot be equal to stop time
			{
				echo"<span class='error'>Start time and stop time cannot be same</span>";	
			}
			elseif($start>=$stop) //start cannot be greater than stop time
			{
				echo"<span class='error'>Start time cannot be greater than stop time </span>";	
			}
			else
			{
				$query="SELECT name FROM groups WHERE id='$grp'";
				$grps=mysql_query($query);
				$grp=mysql_fetch_assoc($grps);
				$name=$grp['name'];
				$query="INSERT INTO tasks VALUES". "(DEFAULT,'$name',NULL,'$start','$stop', '1','1')";
			//if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))		
			//	echo "INSERT failed: $query<br/>".mysql_error()."<br/><br/>";
			//echo $query;
				if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
					echo "INSERT failed: $query<br/>".mysql_error()."<br/><br/>";
				else
					echo "</br></br><span class='success'><b>New Time schedule added</b></span>";
			}
		
}


if(isset($_GET['del'])) //deleting the entry
{

	$del = $_GET['del'];

	$query = "DELETE FROM tasks WHERE id='$del'";

	if(!mysql_query($query,mysql_connect($dbhost, $dbuser, $dbpass)))
	echo "INSERT failed: $query<br/><div class='error'>".mysql_error()."</div><br/><br/>";

}


display();

 ?>  
<?php
 /*
 *
 * Function Name: nomarlize(startm,duration)
 * Input: $ startm for stroing start time minutes, and duration for storing minutes of running esp sensors
 * Output: returns normalize form of minutes, like,, 0630+60=0730, not 0690
 * Logic: as soon as mintues passes 60, extra 1 is added to hour and remaining minutes are added into it
 * 
 *
 */
 
function normalize($startm,$duration)
{
	$tot=$startm+$duration;
	if ($tot>=60)
		{
			$tot=$tot-60;
			$tot=100+$tot;
			return $tot;
		}
	return $tot;


}


 /*
 *
 * Function Name: display()
 * Input: -
 * Output: display the scheduled tasks
 * Logic: fetches tasks from tasks table
 * 
 *
 */
function display()
{
	$dbname='IOT'; //this bottleneck is gving me pain...
	mysql_select_db($dbname) or die(mysql_error());
	$query="SELECT * FROM tasks"; //displaying scheduled tasks
	$results=mysql_query($query);
	if (mysql_num_rows($results) > 0) 
	{	$i=1;
		echo "</br></br><h2>Scheduled Tasks</h2>";		
		while($row=mysql_fetch_assoc($results)) 
		{	$id=$row['id'];
			$start=$row['start'];
			$stop=$row['stop']; //online offline or new, 1, 0, 2
			$item=$row['item'];
			if($start>=2100 or $start<=300)
				$status="<span style='color:#FF0000;font-weight:bold;'>Shouldn't water plants during night</span>";
			else
				$status='';
			
			
			echo "<span style='color:#3B5998;font-weight:normal;'><b>Task ".$i."</b>&nbsp; &nbsp;<b>Item:</b> $item&nbsp; &nbsp; Start time : $start &nbsp; &nbsp; Stop time : $stop </span>&nbsp;<a href='javascript:del($id)'><b>DELETE</b></a> &nbsp; $status<hr>";
			$i++;
		
		
		}
	}
	else
	{
		echo "</br><div class='notice'><b>No Tasks scheduled yet.</b></div>";
	}

}
?>
