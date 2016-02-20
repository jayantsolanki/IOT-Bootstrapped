<?php 
/*
*Project:IoT-Connected-valves-for-irrigation-of-greenhouse
*File name: valveControl.php
*Author: Jayant Solanki
*This is the valve control page of the website, which will basically show the maunal control options
*for different sensors
*/
session_start();
include_once 'settings/iotdb.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Valve Control</title>

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="../dist/css/bootstrap-switch.min.css">
    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
     <link rel="stylesheet" type="text/css" href="../dist/css/bootstrap-fullscreen-select.css" />

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

<body onload='showgrp(1)'>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include_once "layouts/navigation.php";?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-center">IOT Based Valve control</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="header" >
                      <h2 class="text-center">Valve Controls</h2>
                    </div>
                      <div class=" col-md-6 content">
                        <label class="text text-info">Select group</label>
                        <div class="row">
                            <select class='mobileSelect form-control' id='chartselect' name='chartselect' >
                                <option selected="true" disabled='disabled'>Choose</option>
                            <ul class="dropdown-menu">
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
                                            if($id==1)
                                              echo " <option selected='selected' value='$id'>$group</option>";
                                              else
                                              echo " <option value='$id'>$group</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="row">
                          <hr>
                          <div class="" id='controls'>

                          </div>
                         </div>

                      </div><!-- end of content div -->
                        
                    </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <footer class="footers">
        <?php
        include_once "app.php";
        ?>
    </footer>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
     <script type="text/javascript" src="../dist/js/bootstrap-fullscreen-select.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <script src="../dist/js/bootstrap-switch.min.js"></script>
     <script>
        $('.mobileSelect').mobileSelect({
        title: 'Select a Group',
            buttonSave: 'Done',
            buttonClear: 'Clear',
            buttonCancel: 'Cancel',
            padding: {
                'top': '20%',
                'left': '20%',
                'right': '20%',
                'bottom': '20%'
            },
            animation: 'scale',
            animationSpeed: 400,
            theme: 'white',
            onOpen: function () {
            },
            onClose: function () {
                if($('.mobileSelect').val()!=null)
                     showgrp($('.mobileSelect').val());
            },
            style: 'btn-info'
        });
        $('.mobileSelect').mobileSelect();
    </script>
    <script type='text/javascript'>
/*
 *
 * Function Name: showgrp(grp)
 * Input: grp, stores group id
 * Output: returns the sensors under the group id
 * Logic: It is a AJAX call
 * Example Call: showgrp(34)
 *
 */
function showgrp(grp)
{
    
if (grp=='')
  {
  document.getElementById('controls').innerHTML='';
  return;
  } 
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
      document.getElementById('controls').innerHTML="<span class='push-5'><img src='images/ajax.gif'/></span>";
      }
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById('controls').innerHTML=xmlhttp.responseText;
        $('.BSswitch').bootstrapSwitch('state') //loading buttons
          $('#TheCheckBox').on('switchChange.bootstrapSwitch', function () {
            //var mac=$('#TheCheckBox').val();
            update($('#TheCheckBox').val());
        });
    }
  }
xmlhttp.open('GET','control.php?grp='+grp,true);
//alert(grp);
xmlhttp.send();
}
</script>
<script type='text/javascript'>
/*
 *
 * Function Name: update(str)
 * Input: str, stores group id, duration, stores the time in minutes
 * Output: send ON/OFF signal to the esp
 * Logic: It is a AJAX call
 * Example Call: update(12-14-AA-54-76-BB)
 *
 */
function update(str)
{
  //alert(str);
var duration=document.getElementById('duration').value;
//alert(duration);
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
    document.getElementById(str).innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open('GET','com.php?q='+str+'&duration='+duration,true);
xmlhttp.send();
}
</script>
<script type='text/javascript'>
/*
 *
 * Function Name: updateall(str)
 * Input: str, stores 0/1, duration, stores the time in minutes, gid, stores group id
 * Output: send ON/OFF signal to all esp of one group
 * Logic: It is a AJAX call
 * Example Call: updateall(1)
 *
 */
function updateall(str)
{
var gid=document.getElementById('groups').value;
var duration=document.getElementById('duration').value;

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
      var items = document.getElementsByClassName('item');
      for (var i = 0; i < items.length; ++i) 
        {
                var item = items[i];  
                item.innerHTML = "Switching....";
            }
      }
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    var items = document.getElementsByClassName('item');
    for (var i = 0; i < items.length; ++i) 
        {
                var item = items[i];  
                item.innerHTML = xmlhttp.responseText;
            }
    
   

    }
  }
xmlhttp.open('GET','com.php?q='+str+'&gid='+gid+'&duration='+duration,true);
xmlhttp.send();
}
</script>
<script type="text/javascript">
$('.BSswitch').bootstrapSwitch('state')
$('#TheCheckBox').on('switchChange.bootstrapSwitch', function () {
  alert(2);
  });
</script>

</body>

</html>
