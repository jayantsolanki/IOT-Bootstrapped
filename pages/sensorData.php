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
    <style>
    /* .modal-fullscreen */

    .modal-fullscreen {
      background: transparent;
    }
    .modal-fullscreen .modal-content {
      background: transparent;
      border: 0;
      -webkit-box-shadow: none;
      box-shadow: none;
    }
    .modal-backdrop.modal-backdrop-fullscreen {
      background: #000000;
    }
    .modal-backdrop.modal-backdrop-fullscreen.in {
      opacity: .80;
      filter: alpha(opacity=80);
    }

    /* .modal-fullscreen size: we use Bootstrap media query breakpoints */

    .modal-fullscreen .modal-dialog {
      margin: 0;
      margin-right: auto;
      margin-left: auto;
      width: 100%;
    }
    @media (min-width: 768px) {
      .modal-fullscreen .modal-dialog {
        width: 750px;
      }
    }
    @media (min-width: 992px) {
      .modal-fullscreen .modal-dialog {
        width: 970px;
      }
    }
    @media (min-width: 1200px) {
      .modal-fullscreen .modal-dialog {
         width: 1170px;
      }
    }

    </style>

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
                               <!--  <option selected="true" disabled='disabled'>Choose</option> -->
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

                            <!-- Modal fullscreen -->
                            <div class="modal modal-fullscreen fade" id="modal-fullscreen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                              
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <h4 class="modal-title text text-danger text-center" id="myModalLabel">For Device <span id="devId"><?php if($_SESSION["devId"]!=null) echo $_SESSION["devId"];?></span></h4>
                                      </div>
                                      <ul class="nav nav-tabs" role="tablist">
                                        <li name='battery' role="presentation">
                                        <a class="text text-danger" id="batval" href="javascript:showgraphBattery('<?php echo $_SESSION['devId'];?>')">Battery</a></li>
                                        <li name="temperature" role="presentation">
                                        <a class="text text-danger" href="javascript:showgraphTemp('temperature')">Temperature</a></li>
                                        <li name='humidity' role="presentation">
                                        <a  class="text text-danger"href="javascript:showgraphHumid('humidity')">Humidity</a></li>
                                        <li  name='moisture' role="presentation">
                                        <a  class="text text-danger"href="javascript:showgraphMoist('moisture')">Moisture</a></li>
                                    </ul>
                                      <div class="modal-body" id="dumps" style="height: 500px;  background-color: #222222;">
                                       
                                        ...
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                
                            </div>
                            
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
     <script type="text/javascript" src="../dist/js/bootstrap-fullscreen-select.js"></script>
     <script src="../bower_components/amcharts3/amcharts/amcharts.js"></script>
     <script src="../bower_components/amcharts3/amcharts/serial.js"></script>
     <script src="../bower_components/amcharts3/amcharts/themes/dark.js"></script>
     
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <script>
          $(".modal-fullscreen").on('show.bs.modal', function () {

        setTimeout( function() {
          $(".modal-backdrop").addClass("modal-backdrop-fullscreen");
        }, 0);
      });
      $(".modal-fullscreen").on('hidden.bs.modal', function () {
        $(".modal-backdrop").addClass("modal-backdrop-fullscreen");
      });

    </script>
    <script>
      var ws=null;
      $(function() { //websocket
          //var wscon=null;
          ws = new WebSocket("ws://10.129.28.118:8181");
          ws.onopen = function(e) {
            console.log('Connection to server opened');
          }
              //var valElem = $('#sss');
                 
          ws.onclose = function(e) {
            console.log("Connection closed");
          }

          function disconnect() {
            ws.close();
          }
      });
    </script>
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
        function renderChartBattery(data, custom, type, devId)
        {         
         //alert(data);
         AmCharts.addInitHandler( function( chart ) {
           
             var dataPoint = chart.dataProvider[ chart.dataProvider.length - 1 ];
             var graph = chart.graphs[0];
              graph.bulletField = "bullet";
              dataPoint.bullet = "round";

         },[ "serial" ]);
         var chartData = JSON.parse(data); //return json object, converts json string into json objects
          var chart = AmCharts.makeChart("dumps",
        {
          "type": "serial",
          "addClassNames": true,
          //"classNamePrefix": "amcharts", // Default value
          "theme": "dark",
          "marginTop": 86,
          "marginRight": 40,
          "marginLeft": 86,
          "autoMarginOffset": 20,
          "dataDateFormat": "YYYY-MM-DD-JJ-NN",
          "precision":0,
          "mouseWheelZoomEnabled": true,
          "valueAxes": [{
              "title":custom.yAxisName,
              "unit": custom.unit,
              "id": "v1",
              "axisAlpha": 0,
              "position": "left",
              "ignoreAxisWidth":true
          }],
          "balloon": {
              "borderThickness": 1,
              "shadowAlpha": 0
          },
          "graphs": [{
              "id": "g2",
              "title": custom.title,
              //"type": "smoothedLine",
              "classNameField": "bulletClass",
              "balloon":{
                "drop":true,
                "adjustBorderColor":false,
                "color":"#ffffff"
              },
              //"bullet": "round",
              "bulletBorderColor": "#786c56",
              "bulletBorderAlpha": 1,
              "bulletBorderThickness": 2,
              "bulletColor": "#ff0000",
              "showBalloon": true,
              "animationPlayed": true,
              "lineThickness": 2,
              "title": "red line",
              "useLineColorForBulletBorder": true,
              "valueField": "value",
              "valueAxis": "v1",
              "balloonText": "<span style='font-size:18px;'>[[value]]"+custom.unit+"</span>"
          }],
          "chartScrollbar": {
              "graph": "g2",
              "oppositeAxis":false,
              "offset":30,
              "scrollbarHeight": 80,
              "backgroundAlpha": 0,
              "selectedBackgroundAlpha": 0.1,
              "selectedBackgroundColor": "#888888",
              "graphFillAlpha": 0,
              "graphLineAlpha": 0.5,
              "selectedGraphFillAlpha": 0,
              "selectedGraphLineAlpha": 1,
              "autoGridCount":true,
              "color":"#AAAAAA"
          },
          "chartCursor": {
              "pan": true,
              "valueLineEnabled": true,
              "valueLineBalloonEnabled": true,
              "cursorAlpha":1,
              "cursorColor":"#258cbb",
              "categoryBalloonDateFormat":"JJ:NN, DD MMM",
              "limitToGraph":"g1",
              "valueLineAlpha":0.2
          },
          "valueScrollbar":{
            "oppositeAxis":true,
            "scrollbarHeight":10
          },
          "categoryField": "label",
          "categoryAxis": {
              "title":custom.id,
              "parseDates": true,
              "equalSpacing" : true,
              "minPeriod":"mm",
              "periodValue": "Average",
              "dashLength": 1,
              "minorGridEnabled": true
          },
          "export": {
              "enabled": true
          },
          "dataProvider": chartData
        }
      );
      chart.addListener("rendered", zoomChart);
      zoomChart();
      function zoomChart() {
          chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
      }
      function time_format(d) {
          year=d.getFullYear();
          month=format_two_digits(d.getMonth());
          date=format_two_digits(d.getDate());
          hours = format_two_digits(d.getHours());
          minutes = format_two_digits(d.getMinutes());
          return year+"-"+month+"-"+date+"-"+hours + "-" + minutes;
      }
      function format_two_digits(n) {
           return n < 10 ? '0' + n : n;
      }

      $(function() { //websocket
        if(ws!=null){//sending data via websocket
            ws.onmessage = function(e) {
              var d = new Date();
              var formatted_time = time_format(d);
             //alert(2);
              var chartpoint = JSON.parse(e.data);
              //alert(3);
              //valElem.html(e.data);
              if(type=='battery')
              {
                if(devId==chartpoint['deviceId']){
                    chart.dataProvider.push({
                    label: formatted_time,
                    value: chartpoint['batValue']
                    });
                    chart.validateData();
                    zoomChart();
                }
                //alert(chartpoint['batValue']);
              }
              else if(type=='temperature')
              {
                //alert(chartpoint['tempValue']);
                if(devId==chartpoint['deviceId']){
                    chart.dataProvider.push({
                    label: formatted_time,
                    value: chartpoint['tempValue']
                    });
                    chart.validateData();
                    zoomChart();
                }
                
              }
              else if(type=='humidity')
              {
                if(devId==chartpoint['deviceId']){
                    chart.dataProvider.push({
                    label: formatted_time,
                    value: chartpoint['humidityValue']
                    });
                    chart.validateData();
                    zoomChart();
                }
              }
              else if(type=='moisture')
              {
                if(devId==chartpoint['deviceId']){
                    chart.dataProvider.push({
                    label: formatted_time,
                    value: chartpoint['moistValue']
                    });
                    chart.validateData();
                    zoomChart();
                }
              }
              
            }
          }
      });
    }
    </script>
    <style type="text/css">
       

      .lastBullet{
        -webkit-animation: am-pulsating 1s ease-out infinite;
        animation: am-pulsating 1s ease-out infinite;
      }
      @-webkit-keyframes am-pulsating {
        0% {
          stroke-opacity: 1;
          stroke-width: 0px;
        }
        100% {
          stroke-opacity: 0;
          stroke-width: 50px;
        }
      }
      @keyframes am-pulsating {
        0% {
          stroke-opacity: 1;
          stroke-width: 0px;
        }
        100% {
          stroke-opacity: 0;
          stroke-width: 50px;
        }
      }
    </style>
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
              document.getElementById('dumps').innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                $('ul.nav-tabs li.active').removeClass('active');
                document.getElementsByName("battery")[0].setAttribute("class","active");
                document.getElementById('devId').innerHTML=str;
                document.getElementById("batval").setAttribute("href","javascript:showgraphBattery('"+str+"')");
                var custom = {
                    "title": "Battery Values in last 24 hours",
                    "id": "Battery Chart",
                    "yAxisName": "Battery in mV ",
                    "unit":" mV"
                  };
                  renderChartBattery(xmlhttp.responseText, custom, 'battery',str);
                  jQuery("#modal-fullscreen").modal('show');
                
                //alert(xmlhttp.responseText);
                //document.getElementById("dump").innerHTML=xmlhttp.responseText;

            
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
              document.getElementById('dumps').innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                $('ul.nav-tabs li.active').removeClass('active');
            document.getElementsByName("temperature")[0].setAttribute("class","active");
            var custom = {
                    "title": "Temperature Value",
                    "id": "Temperature Chart",
                    "yAxisName": "Temperature in °C ",
                    "unit":" °C"
                  };   
             
            renderChartBattery(xmlhttp.responseText,custom, 'temperature', devid);
            jQuery("#modal-fullscreen").modal('show');
            //document.getElementById("dump").innerHTML=xmlhttp.responseText;
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
              document.getElementById('dumps').innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                $('ul.nav-tabs li.active').removeClass('active');
                document.getElementsByName("humidity")[0].setAttribute("class","active");
                var custom = {
                    "title": "Humidity Values in last 24 hours",
                    "id": "Humidity Chart",
                    "yAxisName": "Humidity in % ",
                    "unit":" %"
                  };
                renderChartBattery(xmlhttp.responseText,custom, 'humidity',devid);
                jQuery("#modal-fullscreen").modal('show');
                //document.getElementById("dump").innerHTML=xmlhttp.responseText;
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
              document.getElementById('dumps').innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                $('ul.nav-tabs li.active').removeClass('active');
                document.getElementsByName("moisture")[0].setAttribute("class","active");
                var custom = {
                    "title": "Moisture Values in last 24 hours",
                    "id": "Moisture Chart",
                    "yAxisName": "Moisture in %",
                    "unit":" %"
                  };   
                 
                renderChartBattery(xmlhttp.responseText, custom, 'moisture',devid);
                jQuery("#modal-fullscreen").modal('show');
                //document.getElementById("dump").innerHTML=xmlhttp.responseText;
            }
          }
            xmlhttp.open('GET','displaygraph.php?type='+str+'&q='+devid,true);
            xmlhttp.send();
            }
    </script>



</body>

</html>
