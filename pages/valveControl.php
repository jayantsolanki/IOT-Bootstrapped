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
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header text-info">Valve Control</h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
                      <div class=" col-md-6 content">
                        <cite id='server'></cite>
                        <label class="text text-info">Select group</label>
                        <div class="row">
                            <select class='mobileSelect form-control' id='chartselect' name='chartselect' >
                                <option selected="true" disabled='disabled'>Choose</option>
                            <ul class="dropdown-menu">
                              <?php 
                                //mysqli_select_db($dbname) or die(mysqli_error());
                                $query="SELECT * FROM groups"; //displaying groups
                                $results=mysqli_query($con,$query);
                                if (mysqli_num_rows($results) > 0) 
                                    {       
                                        while($row=mysqli_fetch_assoc($results)) 
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
                          <div class="" id='ss'>

                          </div>
                          <div class="" id='sss'>

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
    <!-- bootstrap switch -->
    <script src="../dist/js/bootstrap-switch.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <script>
      var ws=null;
      $(function() { //websocket
          //var wscon=null;
          ws = new WebSocket("ws://socket.k-yantra.org");//changer later for production release
          ws.onopen = function(e) {
            console.log('Connection to server opened');
          }
              //var valElem = $('#sss');
                 
          ws.onmessage = function(e) {
            var response = JSON.parse(e.data);
            //alert(3);
           // valElem.html(e.data);
            //alert(status.deviceId+' '+status.status);
            if(response.action==1){
              document.getElementsByClassName(response.deviceId+response.switchId)[0].innerHTML="<span data-toggle='tooltip' title='Switch currently running' class='text text-success fa fa-refresh fa-spin'></span>";
              document.getElementById(response.deviceId+response.switchId).innerHTML='Switch OFF';//changed to switchId for respective valves
            }
            else if(response.action==0){
              document.getElementsByClassName(response.deviceId+response.switchId)[0].innerHTML="<span data-toggle='tooltip' title='Switch currently stopped' class='text text-danger glyphicon glyphicon-ban-circle'></span>";
              document.getElementById(response.deviceId+response.switchId).innerHTML='Switch ON';
            }
            if(response.status==0){
              var resp=document.getElementsByClassName(response.deviceId);
              var i=0;
              while (resp.length) {
                resp[i].innerHTML = "<span class='label label-danger'>OFFLINE</span>";
                i++;
              }
            }
            else if(response.status==1){
              var resp=document.getElementsByClassName(response.deviceId);
              var i=0;
              while (resp.length) {
                resp[i].innerHTML = "<span class='label label-success'>ONLINE</span>";
                i++;
              }
            }
            
                   
          }
          ws.onerror = function(e) {
            document.getElementById('server').innerHTML="<span class='label label-danger'>MQTT Server connection unavailable, please refresh the page, if problem persists, contact Jay</span>";
            console.log("Connection error");
          }
          ws.onclose = function(e) {
            console.log("Connection closed");
          }
          setInterval(function () {
              if (ws.readyState != 1) {
                  document.getElementById('server').innerHTML="<span class='label label-warning'>No connection to MQTT server, retrying to connect, if problem persists, contact Jay</span>";
                  ws = new WebSocket("ws://socket.k-yantra.org");
              }
              if (ws.readyState == 1) {
                document.getElementById('server').innerHTML="";
              }
          }, 1000);
          function disconnect() {
            ws.close();
          }
      });
    </script
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
function update(deviceId, switchId)
{
  //alert(deviceId+switchId);
  var action=document.getElementById(deviceId+switchId).innerHTML;
  //alert(action);
  var payload;
  //alert(action);
  //alert(action);
  if(ws.readyState == 1){//sending data via websocket
      //if(ws.readyState == 1) {
      if(action=='Switch OFF'){
        payload=0;                
      }
      if(action=='Switch ON'){
        payload=1;
      }
      var jsonS={
           "deviceId":deviceId,
           "switchId":switchId,
           "payload":payload
           };
      ws.send(JSON.stringify(jsonS));
      var duration=document.getElementById('duration').value;//mysql and mqtt are going separately, one via ajax and other via websocket
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
            document.getElementById(deviceId+switchId).innerHTML="Switching ....";
            document.getElementById(deviceId+switchId).disabled = true;
            }
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
          {
            if(payload==0)
              document.getElementById(deviceId+switchId).innerHTML='Switch ON';
            else if(payload==1)
              document.getElementById(deviceId+switchId).innerHTML='Switch OFF';
              document.getElementsByClassName(deviceId+switchId)[0].innerHTML=xmlhttp.response;//setting action status
              document.getElementById(deviceId+switchId).disabled = false;
          }
        }
      xmlhttp.open('GET','com.php?devId='+deviceId+'&switchId='+switchId+'&duration='+duration,true);//modified for the switch ids
      xmlhttp.send();
  }//end of outer if
  else
  {
    document.getElementById(deviceId+switchId).innerHTML="Connection Error";
  }
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


</body>

</html>
