<?php 
/*
*Project:IoT-Connected-valves-for-irrigation-of-greenhouse
*File name: sensorData.php
*Author: Jayant Solanki
*This is the sensor data page of the website, which will basically show graphical view of the data gathered from various sensors
*/
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

    <title>Sensors Data</title>

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
                        <h1 class="page-header">Sensors Data</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class=" col-md-12 content">
                        <label class="text text-info">Select group</label>
                        <div class="row">
                            <div class="col-md-4 btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                              Click here <span class="caret"></span>
                            </button>
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
                                            echo "<li class='text-center list-group-item'><a href='javascript:showgrp($id)'>$group</a></li>";
                                        }
                                    }
                                ?>
                            </ul>
                          </div>
                         <div class="well col-md-7 col-md-offset-1" id='charts'>
                                    <cite>Select group and the device for the Chart</cite>
                          </div>
                         </div>
                         <div class="row">
                            <div class="well col-md-4" id='controls'>
                                    <cite>Select group and the device for the Chart</cite>
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
        xmlhttp.open('GET','sensors.php?grp='+grp,true);
        //alert(grp);
        xmlhttp.send();
        }
    </script>

</body>

</html>
