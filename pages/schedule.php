<?php 
/*
*Project: IoT-Connected-valves-for-irrigation-of-greenhouse
*File name: schedule.php
*Author: Jayant Solanki
*display options to user for time scheduling
*options are, period, duration and frequency
*/
include_once 'settings/iotdb.php';?>
<?php
// date_default_timezone_set('Asia/Kolkata');//setting IST
// date_default_timezone_set('Asia/Kolkata');//setting EDT
//echo "Time is ".date('Hi');?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Scheduler</title>

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <noscript>
        Your browser doesnt support javascript
    </noscript>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include_once "layouts/navigation.php";?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-info">Scheduler</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                        <div class="col-md-5">
                            <div class="header text-danger" >
                              <h2>Add Schedule</h2>
                            </div>
                            <div class="row">
                                <div class='col-md-12'>
                                    <label class='text-muted'>Choose Group</label>
                                    <select class="form-control" id="grps">
                                        <option selected="true" disabled='disabled'>Choose</option>
                                        <?php
                                        // mysqli_select_db($dbname) or die(mysqli_error());
                                        $query="SELECT id,name FROM groups order by name asc"; //displaying groups
                                        $results=mysqli_query($con, $query);
                                        if (mysqli_num_rows($results) > 0) 
                                            {       
                                                while($row=mysqli_fetch_assoc($results)) 
                                                {   //$id=$row['id'];
                                                    $group=$row['name'];
                                                    $id=$row['id'];
                                                    echo " <option value='$id'>$group</option>";
                                                }
                                            }

                                        ?>

                                    </select>
                                </div>
                             </div>
                             <div class='row'>
                                <div class='choose col-md-12'>
                                    <label class='text-muted'>Type of Schedule</label>
                                    <select class=" form-control" id="time">
                                    <option selected="true" disabled='disabled'>Choose</option>
                                      <option value="period">Period</option>
                                      <option value="duration">Duration</option>
                                      <option value="frequency">Frequency</option>
                                    </select>
                                </div>
                            </div>
                            <div class='row'>
                                <div id='period' class="time col-md-12">
                                    <hr/>
                                    <fieldset  class="form-group form-inline">
                                    <label class='text-danger'>Start:(hhmm)</label>

                                    <?php
                                    echo "Hrs:<select class='form-control' id='starth' name='starth'>";
                                    $i=0; 
                                    while($i<24)
                                    {
                                    echo "<option value='$i'>$i</option>";
                                    $i++;
                                    } 
                                    echo "</select>";
                                    echo " Mins:<select class='form-control' id='startm' name='startm'>";
                                    $j=0; 
                                    while($j<60)
                                    {
                                    echo "<option value='$j'>$j</option>";
                                    $j=$j+1;
                                    } 
                                    echo "</select>";
                                    ?>
                                    <hr/>
                                    <label class='text-warning'>Stop:(hhmm)</label>
                                    <?php
                                    echo "Hrs:<select class='form-control' id='stoph' name='stoph'>";
                                    $i=0; 
                                    while($i<24)
                                    {
                                    echo "<option value='$i'>$i</option>";
                                    $i++;
                                    } 
                                    echo "</select>";
                                    echo " Mins:<select class='form-control' id='stopm' name='stopm'>";
                                    $j=0; 
                                    while($j<60)
                                    {
                                    echo "<option value='$j'>$j</option>";
                                    $j=$j+1;
                                    } 
                                    echo "</select>";
                                    ?>
                                    
                                    </fieldset>
                                    <button class="btn btn-danger" id='button' type='button' onclick='period()' value='add'>Add</button>
                                    
                                </div>
                                <div id='duration' class="time col-md-12">
                                    <hr/>
                                    <fieldset  class="form-group form-inline">
                                    <label class='text-danger'>Start:(hhmm)</label>
                                    <?php
                                    echo "Hrs:<select class='form-control' id='dstarth' name='starth'>";
                                    $i=0; 
                                    while($i<24)
                                    {
                                    echo "<option value='$i'>$i</option>";
                                    $i++;
                                    } 
                                    echo "</select>";
                                    echo " Mins:<select class='form-control' id='dstartm' name='startm'>";
                                    $j=0; 
                                    while($j<60)
                                    {
                                    echo "<option value='$j'>$j</option>";
                                    $j=$j+1;
                                    } 
                                    echo "</select>";
                                    ?>
                                    <hr/>
                                    <label class='text-warning'>Duration:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php

                                    echo "Mins:<select class='form-control' id='dduration' name='duration'>";
                                    $j=10; 
                                    while($j<60)
                                    {
                                    echo "<option value='$j'>$j</option>";
                                    $j=$j+1;
                                    } 
                                    echo "</select>";
                                    ?>
                                    </fieldset>
                                    <button class="btn btn-danger" id='button' type='button' onclick='duration()' value='add'>Add</button>
                                </div>
                                <div id='frequency' class="time col-md-12">
                                    <hr/>
                                    <fieldset  class="form-group form-inline">
                                        <label class='text-danger'>Start:(hhmm)</label>
                                    <?php
                                    echo "Hrs:<select class='form-control' id='fstarth' name='starth'>";
                                    $i=0; 
                                    while($i<24)
                                    {
                                    echo "<option value='$i'>$i</option>";
                                    $i++;
                                    } 
                                    echo "</select>";
                                    echo " Mins:<select class='form-control' id='fstartm' name='startm'>";
                                    $j=0; 
                                    while($j<60)
                                    {
                                    echo "<option value='$j'>$j</option>";
                                    $j=$j+1;
                                    } 
                                    echo "</select>";
                                    echo "<hr/><label class='text-warning'>Duration:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>";
                                    echo "Mins:<select class='form-control' id='fduration' name='duration'>";
                                    $j=5; 
                                    while($j<60)
                                    {
                                    echo "<option value='$j'>$j</option>";
                                    $j=$j+1;
                                    } 
                                    echo "</select>";

                                    ?><hr/>
                                    <label class='text-info'>Repeat every:(hh)</label>
                                    <?php

                                    echo "Hrs:<select class='form-control' id='repeath' name='repeath'>";
                                    $i=4; 
                                    while($i<=12)
                                    {
                                    echo "<option value='$i'>$i</option>";
                                    $i=$i+4;
                                    } 
                                    echo "</select>";
                                    ?></fieldset>
                                    <button class="btn btn-danger" id='button' type='button' onclick='frequency()' value='add'>Add</button>
                                </div><!-- time ends here -->
                            </div><!-- row ends here -->
                        </div>
                        <div class="col-md-7">
                            <div class='' id='display'>
                            <?php display($con);?>
                            </div><!-- display -->
                        </div>
                        <div class='row'>
                            
                        </div><!-- row ends here -->
                        <?php


                        /*
                         *
                         * Function Name: display($con)
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
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    <footer class="footers">
        <?php
        include_once "app.php";
        ?>
    </footer>
    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <script type='text/javascript'>
$(document).ready(function () {
  $('.choose').hide();
  $('#grps').change(function () {
    $('.choose').show();
    
  })
});
</script>
<script type='text/javascript'>
$(document).ready(function () {
  $('.time').hide();
  //$('#period').show();
  $('#time').change(function () {
    $('.time').hide();
    $('#'+$(this).val()).show();
  })
});
</script>
<script type='text/javascript'>
/*
 *
 * Function Name: del(str)
 * Input: str, task id, for deletion
 * Output: deletes the selected task
 * Logic: It is a AJAX call
 * Example Call: del(12)
 *
 */
function del(str)
{
if(confirm('Confirm Delete'))
    {
        if (window.XMLHttpRequest)
          {
          xmlhttp=new XMLHttpRequest();
          }
        else
          {
          xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
          }
        xmlhttp.onreadystatechange=function()
          {
            if (xmlhttp.readyState==3 && xmlhttp.status==200)
              {
              document.getElementById(str).innerHTML="Switching ....";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
            document.getElementById("display").innerHTML=xmlhttp.responseText;
            }
          }
        xmlhttp.open('GET','timetasker.php?del='+str,true);
        xmlhttp.send();
    }
}
</script>
<script type='text/javascript'>
/*
 *
 * Function Name: period()
 * Input: -
 * Output: updates tasks table with start and stop time for a group, in period format
 * Logic: It is a AJAX call
 * Example Call: period()
 *
 */
function period()
{
var grp=document.getElementById("grps").value;
var starth=document.getElementById("starth").value;
var startm=document.getElementById("startm").value;
var stoph=document.getElementById("stoph").value;
var stopm=document.getElementById("stopm").value;
if (window.XMLHttpRequest)
  {
  xmlhttp=new XMLHttpRequest();
  }
else
  {
  xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
  }
xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==3 && xmlhttp.status==200)
      {
      document.getElementById(str).innerHTML="Switching ....";
      }
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("display").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open('GET','timetasker.php?grp='+grp+'&starth='+starth+'&startm='+startm+'&stoph='+stoph+'&stopm='+stopm,true);
xmlhttp.send();
}
</script>
<script type='text/javascript'>
/*
 *
 * Function Name: duration()
 * Input: -
 * Output: updates tasks table with start and stop time for a group, in duration format
 * Logic: It is a AJAX call
 * Example Call: duration()
 *
 */
function duration()
{
var grp=document.getElementById("grps").value;
var starth=document.getElementById("dstarth").value;
var startm=document.getElementById("dstartm").value;
var duration=document.getElementById("dduration").value;

if (window.XMLHttpRequest)
  {
  xmlhttp=new XMLHttpRequest();
  }
else
  {
  xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
  }
xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==3 && xmlhttp.status==200)
      {
      document.getElementById(str).innerHTML="Switching ....";
      }
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("display").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open('GET','timetasker.php?grp='+grp+'&starth='+starth+'&startm='+startm+'&duration='+duration,true);
xmlhttp.send();
}
</script>
<script type='text/javascript'>
/*
 *
 * Function Name: frequency()
 * Input: -
 * Output: updates tasks table with start and stop time for a group, in frequency format
 * Logic: It is a AJAX call
 * Example Call: frequency()
 *
 */
function frequency()
{
var grp=document.getElementById("grps").value;
var starth=document.getElementById("fstarth").value;
var startm=document.getElementById("fstartm").value;
var duration=document.getElementById("fduration").value;
var repeath=document.getElementById("repeath").value;

if (window.XMLHttpRequest)
  {
  xmlhttp=new XMLHttpRequest();
  }
else
  {
  xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
  }
xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==3 && xmlhttp.status==200)
      {
      document.getElementById(str).innerHTML="Switching ....";
      }
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("display").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open('GET','timetasker.php?grp='+grp+'&starth='+starth+'&startm='+startm+'&repeath='+repeath+'&duration='+duration,true);
xmlhttp.send();
}
</script>

</body>

</html>
