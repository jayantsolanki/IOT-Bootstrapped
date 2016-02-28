<?php 
/*
*Project:IoT-Connected-valves-for-irrigation-of-greenhouse
*File name: deviceStatus.php
*Author: Jayant Solanki
*It is for displaying devices information
*/
include_once 'settings/iotdb.php';
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1); //for suppressing errors and notices
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Devices Status</title>

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

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
                        <h1 class="page-header">Devices Status</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                  <div class="panel panel-info">
                    <div class="panel panel-heading">
                          <h4 class='text text-center'>Registered Devices</h4>
                    </div>
                        <div class="panel panel-body">

                          <b>Select group</b> <select name='groups' class='mobileSelect'>
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
                                      if($id==1)
                                      echo " <option selected='selected' value='$id'>$group</option>";
                                      else
                                      echo " <option value='$id'>$group</option>";
                                  }
                              }
                          ?>
                          </select>&nbsp; &nbsp;</br></br>
                          <div id='dev'>

                          </div>
                        </div><!-- ending panel body -->
                      </div><!-- ending panel -->
                      
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
     <script type="text/javascript" src="../dist/js/bootstrap-fullscreen-select.js"></script>
     <script>
      var ws=null;
      $(function() { //websocket
          //var wscon=null;
          ws = new WebSocket("ws://10.129.28.118:8180");
          ws.onopen = function(e) {
            console.log('Connection to server opened');
          }
              //var valElem = $('#sss');
                 
          ws.onmessage = function(e) {
            var response = JSON.parse(e.data);
            //alert(3);
           // valElem.html(e.data);
            //alert(status.deviceId+' '+status.status);
            if(response.status==0)
               document.getElementById(response.deviceId).innerHTML="<span class='label label-danger'>OFFLINE</span>";
            else if(response.status==1)
               document.getElementById(response.deviceId).innerHTML="<span class='label label-success'>ONLINE</span>";
            
                   
          }
          ws.onclose = function(e) {
            console.log("Connection closed");
          }

          function disconnect() {
            ws.close();
          }
      });
    </script
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
          document.getElementById('dev').innerHTML='';
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
              document.getElementById('dev').innerHTML="<span class='push-5'><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
            document.getElementById('dev').innerHTML=xmlhttp.responseText;
            }
          }
        xmlhttp.open('GET','dd.php?grp='+grp,true);
        //alert(grp);
        xmlhttp.send();
        }
        </script>
       
</body>

</html>
