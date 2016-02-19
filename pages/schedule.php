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
date_default_timezone_set('Asia/Kolkata');//setting IST
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
                        <h1 class="page-header">Scheduler</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                       <div  id="main">
                            <div class="header" >
                              <h2>Add Schedule</h2>
                            </div>

                            <div class="content">
                        <b>Choose Group</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select class="col-md-2 form-control" id="grps">
                        <option selected="true" disabled='disabled'>Choose</option>
                        <?php
                        mysql_select_db($dbname) or die(mysql_error());
                        $query="SELECT * FROM groups"; //displaying groups
                        $results=mysql_query($query);
                        if (mysql_num_rows($results) > 0) 
                            {       
                                while($row=mysql_fetch_assoc($results)) 
                                {   //$id=$row['id'];
                                    $group=$row['name'];
                                    $id=$row['id'];
                                    echo " <option value='$id'>$group</option>";
                                }
                            }

                        ?>

                        </select>
                         </div>
                        <div class='choose'>
                        <b>Type of Schedule</b>&nbsp;&nbsp;&nbsp;<select class="col-xs-5 form-control" id="time">
                        <option selected="true" disabled='disabled'>Choose</option>
                          <option value="period">Period</option>
                          <option value="duration">Duration</option>
                          <option value="frequency">Frequency</option>
                        </select>
                        <div id='period' class="time">
                        <pre>
                        </br>
                        <span style='color:#3B5998;font-weight:normal;'>Start time:(hhmm)</span>

                        <?php
                        echo "Hrs:<select class='form-control' id='starth' name='starth'>";
                        $i=0; 
                        while($i<=24)
                        {
                        echo "<option value='$i'>$i</option>";
                        $i++;
                        } 
                        echo "</select>";
                        echo " Mins:<select class='form-control' id='startm' name='startm'>";
                        $j=0; 
                        while($j<=60)
                        {
                        echo "<option value='$j'>$j</option>";
                        $j=$j+5;
                        } 
                        echo "</select>";
                        ?>

                        </br><span style='color:#3B5998;font-weight:normal;
                            '>Stop time:(hhmm)</span></br>
                        <?php
                        echo "Hrs:<select class='form-control' id='stoph' name='stoph'>";
                        $i=0; 
                        while($i<=24)
                        {
                        echo "<option value='$i'>$i</option>";
                        $i++;
                        } 
                        echo "</select>";
                        echo " Mins:<select class='form-control' id='stopm' name='stopm'>";
                        $j=0; 
                        while($j<=60)
                        {
                        echo "<option value='$j'>$j</option>";
                        $j=$j+5;
                        } 
                        echo "</select>";
                        ?>
                        </br>
                        <input type='submit' name='submit' onclick='period()' value='Submit' />
                        </pre>
                        </div>
                        <div id='duration' class="time">
                        <pre>


                        <span style='color:#3B5998;font-weight:normal;'>Start time:(hhmm)</span></br>
                        <?php
                        echo "Hrs:<select class='form-control' id='dstarth' name='starth'>";
                        $i=0; 
                        while($i<=24)
                        {
                        echo "<option value='$i'>$i</option>";
                        $i++;
                        } 
                        echo "</select>";
                        echo " Mins:<select id='dstartm' name='startm'>";
                        $j=0; 
                        while($j<=60)
                        {
                        echo "<option value='$j'>$j</option>";
                        $j=$j+5;
                        } 
                        echo "</select>";
                        ?>
                        </br></br><span style='color:#3B5998;font-weight:normal;'>Duration:(mm)</span></br>
                        <?php

                        echo "Mins:<select class='form-control' id='dduration' name='duration'>";
                        $j=5; 
                        while($j<=60)
                        {
                        echo "<option value='$j'>$j</option>";
                        $j=$j+5;
                        } 
                        echo "</select>";
                        ?>
                        </br>
                        <input type='submit' name='submit' onclick='duration()' value='Submit' />

                        </pre>
                        </div>
                        <div id='frequency' class="time">
                        <pre>


                        <span style='color:#3B5998;font-weight:normal;'>Start time:(hhmm)</span></br>
                        <?php
                        echo "Hrs:<select class='form-control' id='fstarth' name='starth'>";
                        $i=0; 
                        while($i<=24)
                        {
                        echo "<option value='$i'>$i</option>";
                        $i++;
                        } 
                        echo "</select>";
                        echo "</br></br><span style='color:#3B5998;font-weight:normal;'>Duration:(mm)</span></br>";
                        echo "Mins:<select class='form-control' id='fduration' name='duration'>";
                        $j=5; 
                        while($j<=60)
                        {
                        echo "<option value='$j'>$j</option>";
                        $j=$j+5;
                        } 
                        echo "</select>";

                        ?>
                        </br></br><span style='color:#3B5998;font-weight:normal;'>Repeat after every :(hh)</span></br>
                        <?php

                        echo "Hrs:<select id='repeath' name='repeath'>";
                        $i=4; 
                        while($i<=12)
                        {
                        echo "<option value='$i'>$i</option>";
                        $i=$i+4;
                        } 
                        echo "</select>";
                        ?>
                        </br>
                        <input type='submit' name='submit' onclick='frequency()' value='Submit' />

                        </pre>
                        </div>
                        </div>
                        <div class='row'>
                        <div class='col-md-6' id='display'>
                        <?php display();?>
                        </div>
                         </div>
                          </div><!-- end of content div -->
                        <?php
                        include_once "app.php";?>
                            </div><!-- end of main div -->
                        </div><!-- end of layout -->
                           
                        <div class="push"></div>
                        <?php //include_once "footer.php";?></div>
                        <?php


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
                            $dbname='IOT';
                            mysql_select_db($dbname) or die(mysql_error());
                            $query="SELECT * FROM tasks"; //displaying scheduled tasks
                            $results=mysql_query($query);
                            if (mysql_num_rows($results) > 0) 
                            {   $i=1;
                                echo "</br></br><h2>Scheduled Tasks</h2>";
                                echo "<table class='table table-striped'><tbody>";      
                                while($row=mysql_fetch_assoc($results)) 
                                {   $id=$row['id'];
                                    $start=$row['start'];
                                    $stop=$row['stop']; //online offline or new, 1, 0, 2
                                    $item=$row['item'];
                                    if($start>=2100 or $start<=300)
                                        $status="<span class='label label-warning'>Shouldn't water plants during night</span>";
                                    else
                                        $status='';
                                    echo "<tr>
                                    <td><b>Task ".$i."</b></td>
                                    <td><b>Item:</b> $item</td>
                                    <td> Start time : $start</td>
                                    <td> Stop time : $stop</td>
                                    <td><a class='label label-danger' href='javascript:del($id)'><b>DELETE</b></a> </td>
                                    <td> $status</td>
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
                    <!-- /.col-lg-12 -->
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
xmlhttp.open('GET','timetasker.php?grp='+grp+'&starth='+starth+'&repeath='+repeath+'&duration='+duration,true);
xmlhttp.send();
}
</script>

</body>

</html>
