<?php
/*
*Project: eYSIP_2015_IoT-Connected-valves-for-irrigation-of-greenhouse
*Team members: Jayant Solanki, Kevin D'Souza
*File name: timetasker.php
*Author: Jayant Solanki
*It is called in ajax mode, performing time entries for tasks scheduled.
*/
include_once 'settings/iotdb.php';
// mysqli_select_db($dbname) or die(mysqli_error());
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
		//$duration=$duration+$startm;
		
		while($i<=2400) //time should be greater than 2400, military format
		{	
			$query="SELECT name FROM groups WHERE id='$grp'";
			/*$grps=mysqli_query($query);
			$grp=mysqli_fetch_assoc($grps);
			$name=$grp['name'];*/
			$start=$i;
			$stop=$start + normalize($startm,$duration)-$startm;
			if($stop>2400)
				break;
			if($stop==2400) //marks stop time as 0000
				$stop=0;
			$query="INSERT INTO tasks VALUES". "(DEFAULT,$grp, NULL,NULL,'$start','$stop', '1','1',2, NULL)";
			//if(!mysqli_query($query,mysqli_connect($dbhost, $dbuser, $dbpass)))		
			//	echo "INSERT failed: $query<br/>".mysqli_error()."<br/><br/>";
			//echo $query;
			if(!mysqli_query($con, $query))
				echo "INSERT failed: $query<br/>".mysqli_error()."<br/><br/>";
			
				
			
			$i=$i+$repeath*100;		
		}
		echo "</br></br><span class='alert alert-success'><b>New Time schedule added</b></span><br/>";	
	}
		if($repeath==NULL) // for setting period
			if($start==$stop) //start time cannot be equal to stop time
			{
				echo"</br></br><span class='alert alert-danger'>Start time and stop time cannot be same</span><br/>";	
			}
			elseif($start>=$stop) //start cannot be greater than stop time
			{
				echo"</br></br><span class='alert alert-danger'>Start time cannot be greater than stop time </span><br/>";	
			}
			else
			{
				/*$query="SELECT name FROM groups WHERE id='$grp'";
				$grps=mysqli_query($query);
				$grp=mysqli_fetch_assoc($grps);
				$name=$grp['name'];*/
				$query="INSERT INTO tasks VALUES". "(DEFAULT,$grp,NULL,NULL,'$start','$stop', '1','1',2, NULL)";
			//if(!mysqli_query($query,mysqli_connect($dbhost, $dbuser, $dbpass)))		
			//	echo "INSERT failed: $query<br/>".mysqli_error()."<br/><br/>";
			//echo $query;
				if(!mysqli_query($con, $query))
					echo "INSERT failed: $query<br/>".mysqli_error()."<br/><br/>";
				else
					echo "</br></br><span class='alert alert-success'><b>New Time schedule added</b></span><br/>";
			}
		
}


if(isset($_GET['del'])) //deleting the entry
{

	$del = $_GET['del'];

	$query = "DELETE FROM tasks WHERE id='$del'";

	if(!mysqli_query($con, $query))
	echo "Deletion failed: $query<br/><div class='alert alert-danger'>".mysqli_error()."</div><br/><br/>";

}


display($con);

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
function display($con)
{
    $dbname='IOT';
    // mysqli_select_db($dbname) or die(mysqli_error());
    $query="SELECT * FROM tasks"; //displaying scheduled tasks
    $results=mysqli_query($con, $query);
    if (mysqli_num_rows($results) > 0) 
    {   $i=1;
        echo "</br></br><h2>Scheduled Tasks&nbsp;<small class='text-muted pull-right'><span data-toggle='tooltip' title='Task currently running' class='text text-success fa fa-refresh fa-spin'></span>
        <span data-toggle='tooltip' title='Task currently stopped' class='text text-danger glyphicon glyphicon-ban-circle'></span>
        <span data-toggle='tooltip' title='Newly created Task' class='text text-info glyphicon glyphicon-info-sign'></span>
        <span data-toggle='tooltip' title='Task is disabled by the user' class='text text-warning glyphicon glyphicon-exclamation-sign'></span>
        <span class='text text-muted glyphicon glyphicon-warning-sign' data-toggle='tooltip' title='Should not water plants during night'></span></small></h2>";
        echo "<table class='table table-striped small'><tbody>";      
        while($row=mysqli_fetch_assoc($results)) 
        {   $id=$row['id'];
            $start=$row['start'];
            $stop=$row['stop']; //online offline or new, 1, 0, 2
            $groupId=$row['groupId'];
            $deviceId=$row['deviceId'];
            $switchId=$row['switchId'];
            $active=$row['active'];
            if($active==1)
                $active="<span data-toggle='tooltip' title='Task currently running' class='text text-success fa fa-refresh fa-spin'></span>";
            else if($active==0)
                $active="<span data-toggle='tooltip' title='Task currently stopped' class='text text-danger glyphicon glyphicon-ban-circle'></span>";
            else if($active==2)
                $active="<span data-toggle='tooltip' title='Newly created Task' class='text text-info glyphicon glyphicon-info-sign'></span>";
            else if($active=3)
                $active="<span data-toggle='tooltip' title='Task is disabled by the user' class='text text-warning glyphicon glyphicon-exclamation-sign'></span>";
            if($start>=2100 or $start<=300)
                $status="<a class='text text-muted glyphicon glyphicon-warning-sign' data-toggle='tooltip' title='Should not water plants during night' ><strong><big></big></strong></a>";
            else
                $status='';
            echo "<tr>
            <td><strong class='text-muted'>".$i.".</strong> $active</td>";
            if($groupId!=null){
                $grpq="SELECT name FROM groups where id=$groupId"; //getting group name
                $grpres=mysqli_query($con, $grpq);
                $grprow=mysqli_fetch_assoc($grpres);
                $grpname=$grprow['name'];
                echo"<td><b>Group:</b> $grpname</td>";
            }
            if($deviceId!=null){
                echo"<td><b>DeviceId:</b> $deviceId/<strong class='text-danger'>$switchId</strong> <span data-toggle='tooltip' title='Device manually switched on' class='badge'>M</span></td>";
            }
            if(strlen((string) $start)==3)
                echo"<td class='text-info'> Starts: <strong>0$start Hrs</strong<</td>";
            else if(strlen((string) $start)==2)
                echo"<td class='text-info'> Starts: <strong>00$start Hrs</strong<</td>";
            else if(strlen((string) $start)==1)
                echo"<td class='text-info'> Starts: <strong>000$start Hrs</strong<</td>";
            else
                echo"<td class='text-info'> Starts: <strong>$start Hrs</strong<</td>";
            
            if(strlen((string) $stop)==3)
                echo"<td class='text-warning'> Stops: <strong>0$stop Hrs</strong<</td>";
            else if(strlen((string) $stop)==2)
                echo"<td class='text-warning'> Stops: <strong>00$stop Hrs</strong<</td>";
            else if(strlen((string) $stop)==1)
                echo"<td class='text-warning'> Stops: <strong>000$stop Hrs</strong<</td>";
            else
                echo"<td class='text-warning'> Stops: <strong>$stop Hrs</strong<</td>";
            echo"
            <td><a class='text-danger glyphicon glyphicon-trash' data-toggle='tooltip' title='Delete' href='javascript:del($id)'></a> </td>
            <td>$status</td>
            </tr>";
            $i++;
        
        
        }
        echo "</tbody></table>";
    }
    else
    {
        echo "</br><div class='alert alert-danger'><b>No Tasks scheduled yet.</b></div>";
    }

}
?>
