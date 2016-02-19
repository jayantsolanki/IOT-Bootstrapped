<?php 
/*
*Project:IoT-Connected-valves-for-irrigation-of-greenhouse
*File name: sensorData.php
*Author: Jayant Solanki
*This is the sensor data page of the website, which will basically show graphical view of the data gathered from various sensors
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

    <title>Sensors Data</title>

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
                            <div class="col-md-4">
                            <select class='mobileSelect form-control' id='chartselect' name='chartselect' >
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
                                            echo "<option value=$id>$group</option>";
                                        }
                                    }
                                ?>
                            </select>
                          </div>
                         </div>
                         <div class="row">
                            <div class="col-md-4" id='controls'>
                                    <cite>Select group and the device for the Chart</cite>
                          </div>

                         <div class="panel panel-info col-md-8">
                            <div class="panel-heading">
                                For Device <span id="devId"><?php if($_SESSION["devId"]!=null) echo $_SESSION["devId"];?></span>
                            </div>
                            <div class="panel-body">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li name='battery' role="presentation">
                                    <a id="batval" href="javascript:showgraphBattery('<?php echo $_SESSION['devId'];?>')">Battery</a></li>
                                    <li name="temperature" role="presentation">
                                    <a href="javascript:showgraphTemp('temperature')">Temperature</a></li>
                                    <li name='humidity' role="presentation">
                                    <a href="javascript:showgraphHumid('humidity')">Humidity</a></li>
                                    <li  name='moisture' role="presentation">
                                    <a href="javascript:showgraphMoist('moisture')">Moisture</a></li>
                                </ul>
                            </div>
                            <div id='chart-container'>
                                <cite>Select group and the device for the Chart</cite>
                            </div>
                            
                        </div>

                         </div>
                         <div id='dumps'>
                                <cite>Select group and the device for the Chart</cite>
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
     <script type="text/javascript" src="../dist/js/bootstrap-fullscreen-select.js"></script>
      <script type="text/javascript" src="../dist/js/fusioncharts.charts.js"></script>
       <script type="text/javascript" src="../dist/js/fusioncharts.js"></script>
       <script src="../dist/js/fusioncharts.theme.zune.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
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
    <script>
    /*
         *
         * Function Name: renderChart(data)
         * Input: grp, stores group id
         * Output: returns the sensors under the group id
         * Logic: It is a AJAX call
         * Example Call: showgrp(34)
         *
         */
        function renderChartBattery(data, chartProperties)
        {         
         var chartData = JSON.parse(data); //return json object, converts json string into json objects
        // alert(chartData[0].value);
          var apiChart = new FusionCharts({
                type: 'line',
                renderAt: 'chart-container',
                width: '700',
                height: '400',
                dataFormat: 'json',
                dataSource: {
                  "chart": chartProperties,
                  "data": chartData
                }
              });
              apiChart.render();
               // alert(1);
        $(function(){
          $("#background-btn").click(function(){
            modifyBackground();
          });
         
          $("#canvas-btn").click(function(){
            modifyCanvas();
          });
         
          $("#dataplot-btn").click(function(){
            modifyDataplot();
          });
         //alert(12);
         // apiChart.render();
        });
         
        function modifyBackground(){
          //to be implemented
        }
         
        function modifyCanvas(){
          //to be implemented
        }
         
        function modifyDataplot(){
          //to be implemented
        }
    }
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
              document.getElementById('controls').innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
            document.getElementById('controls').innerHTML=xmlhttp.responseText;
            //code for select ui
                $('.mobileSelect-2').mobileSelect({
                    title: 'Select a Feed',
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
                            //alert($('.mobileSelect-2').val())
                            //showgrp($('.mobileSelect').val());
                        },
                        style: 'btn-primary'
                    });
                $('.mobileSelect-2').mobileSelect();
                
                
                  
            }
          }
        xmlhttp.open('GET','sensors.php?grp='+grp,true);
        //alert(grp);
        xmlhttp.send();
        }
    </script>
    <script type='text/javascript'>
        /*
         *
         * Function Name: showgraphBattery(str)
         * Input: macid, or the device id
         * Output: return graph for battery
         * Logic: It is a AJAX call
         * Example Call: showgraph(12-14-AA-54-76-BB)
         *
         */
        function showgraphBattery(str)
        {
          //alert(str);
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
              document.getElementById('charts').innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                $('ul.nav-tabs li.active').removeClass('active');
                document.getElementsByName("battery")[0].setAttribute("class","active");
                var chartProperties = {
                    "caption": "Battery Value",
                    "xAxisName": "Time",
                    "yAxisName": "Battery Value in mV",
                    "rotatevalues": "1",
                    "theme": "zune",
                    "setAdaptiveYMin": "1",
                    "showValues": "1",
                    "drawAnchors": "1"
                  };   
             document.getElementById('devId').innerHTML=str;
             document.getElementById("batval").setAttribute("href","javascript:showgraphBattery('"+str+"')");
            renderChartBattery(xmlhttp.responseText,chartProperties);
            //alert(xmlhttp.responseText);
           document.getElementById("dump").innerHTML=xmlhttp.responseText;

            
            }
          }
        xmlhttp.open('GET','displaygraph.php?q='+str,true);
        xmlhttp.send();
        }
    </script>
    <script type='text/javascript'>
        /*
         *
         * Function Name: showgraphTemp(str)
         * Input: battery
         * Output: return graph temperature
         * Logic: It is a AJAX call
         * Example Call: showgraphTemp('temperature')
         *
         */
        function showgraphTemp(str)
        {
          //alert(str);
        //alert(duration);
        var devid=document.getElementById("devId").innerHTML;
        //alert(devid);
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
              document.getElementById('charts').innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                $('ul.nav-tabs li.active').removeClass('active');
            document.getElementsByName("temperature")[0].setAttribute("class","active");
            var chartProperties = {
                    "caption": "Temperature Value",
                    "xAxisName": "Time",
                    "yAxisName": "Temperature in °C ",
                    "rotatevalues": "1",
                    "theme": "zune",
                    "setAdaptiveYMin": "1",
                    "showValues": "1",
                    "drawAnchors": "1"
                  };   
             
            renderChartBattery(xmlhttp.responseText,chartProperties);
            document.getElementById("dump").innerHTML=xmlhttp.responseText;
            }
          }
            xmlhttp.open('GET','displaygraph.php?type='+str+'&q='+devid,true);
            xmlhttp.send();
            }
    </script>

     <script type='text/javascript'>
        /*
         *
         * Function Name: showgrapHumid(str)
         * Input: humidity
         * Output: return graph humidity
         * Logic: It is a AJAX call
         * Example Call: showgrapHumid('humidity')
         *
         */
        function showgraphHumid(str)
        {
          //alert(str);
        //alert(duration);
        var devid=document.getElementById("devId").innerHTML;
        //alert(devid);
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
              document.getElementById('charts').innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                $('ul.nav-tabs li.active').removeClass('active');
                document.getElementsByName("humidity")[0].setAttribute("class","active");
                var chartProperties = {
                        "caption": "Humidity Value",
                        "xAxisName": "Time",
                        "yAxisName": "Humidity Value in mV",
                        "rotatevalues": "1",
                        "theme": "zune",
                        "setAdaptiveYMin": "1",
                        "showValues": "1",
                        "drawAnchors": "1"
                      };   
                 
                renderChartBattery(xmlhttp.responseText,chartProperties);
                document.getElementById("dump").innerHTML=xmlhttp.responseText;
            }
          }
            xmlhttp.open('GET','displaygraph.php?type='+str+'&q='+devid,true);
            xmlhttp.send();
            }
    </script>

    <script type='text/javascript'>
        /*
         *
         * Function Name: showgraphMoist(str)
         * Input: moisture
         * Output: return graph moisture
         * Logic: It is a AJAX call
         * Example Call: showgraphMoist('moisture')
         *
         */
        function showgraphMoist(str)
        {
          //alert(str);
        //alert(duration);
        var devid=document.getElementById("devId").innerHTML;
        //alert(devid);
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
              document.getElementById('charts').innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                $('ul.nav-tabs li.active').removeClass('active');
                document.getElementsByName("moisture")[0].setAttribute("class","active");
                var chartProperties = {
                        "caption": "Moisture Value",
                        "xAxisName": "Time",
                        "yAxisName": "Moisture Value in #",
                        "rotatevalues": "1",
                        "theme": "zune",
                        "setAdaptiveYMin": "1",
                        "showValues": "1",
                        "drawAnchors": "1"
                      };   
                 
                renderChartBattery(xmlhttp.responseText,chartProperties);
                document.getElementById("dump").innerHTML=xmlhttp.responseText;
            }
          }
            xmlhttp.open('GET','displaygraph.php?type='+str+'&q='+devid,true);
            xmlhttp.send();
            }
    </script>



</body>

</html>
