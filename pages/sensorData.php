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
<html lang="en" ng-app="IOT-App">

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
    <link rel="stylesheet" href="../bower_components/angularjs-datetime-picker/angularjs-datetime-picker.css" />
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

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include_once "layouts/navigation.php";?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid" ng-controller="devicesChart">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-info">Sensors Data</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row" id="devCharts">
                    <div class=" col-md-12 content">
                        <label class="text text-info">Select group</label>
                        <div class="row">
                            <div class="col-md-6">
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
                            </select><span class="pull-right"><button id="back" class="btn btn-primary" ng-click="showDevice()" style="display:none;">Back</button></span></br></br>
                            <div class="row">
                              <div class="col-md-6">
                                <fieldset class='form-group'>
                                  <label for='results'>Results</label>
                                  <input type='text' class='form-control' id='results' ng-model=count placeholder='Total Reading' value='{{count}}'>
                                </fieldset>
                                <fieldset class='form-group'>
                                  <label for='color'>Chart Color</label>
                                  <input type='text' class='form-control' id='color' ng-model=background_color placeholder='Color of the Chart' value='{{background_color}}'>
                                </fieldset>
                              </div>
                              <div class="col-md-6">
                                <fieldset class='form-group'>
                                  <label for='results'>Results</label><br>
                                  <select ng-model=theme class='form-control' name='chartselect' >
                                    <option value="dark">Dark</option>
                                    <option value="light">Light</option>
                                  </select>
                                </fieldset>
                                <fieldset class='form-group'>
                                  <label for='color'>Starting Date</label>
                                  <input type="text" datetime-picker datetime-picker date-format="yyyy-MM-dd HH:mm" year="2016" month="4" day="1" hour="23" minute="59" ng-model=startDate class="form-control floating-label" placeholder="Start Date" value="{{startDate}}">
                                </fieldset>
                              </div>
                            </div>
                            <strong>Group Selected <big class ="label label-primary">{{devices[0].groupName}}</big></strong><hr/>
                          </div>
                         </div>
                         <div class="row">
                            <div>
                              <div class="row" ng-repeat="device in devices">
                                  <div class="dev" class="col-md-5">
                                     <blockquote>
                                        <p><strong class="text text-info">Name:</strong> {{device.deviceName}}</p>
                                        <p><strong class="text text-info">Device Id:</strong> {{device.deviceId}}</p>
                                        <p><strong class="text text-info">Type:</strong> {{device.type}}</p>
                                        <!-- <p><strong class="text text-info">Status:</strong> {{device.action}}</p> -->
                                        <p>
                                          <span ng-if="device.switchCount==1">
                                            <button class="btn btn-default dev" ng-click="showGraph(1,device.deviceId,'battery')">Visualise</button>
                                          </span>
                                          <span ng-if="device.switchCount==0"><!-- for sensors data -->
                                            <span ng-if="device.field1=='b'">
                                              <button class="btn btn-default dev" ng-click="showGraph('b',device.deviceId,'battery')">Visualise</button>
                                            </span>
                                            <span ng-if="device.field1=='bm'">
                                              <button class="btn btn-default dev" ng-click="showGraph('bm',device.deviceId,'battery')">Visualise</button>
                                            </span>
                                            <span ng-if="device.field1=='bthm'">
                                              <button class="btn btn-default dev" ng-click="showGraph('bthm',device.deviceId,'battery')">Visualise</button>
                                            </span>
                                          </span>
                                        </p>
                                    </blockquote>
                                  </div><!-- end inner div -->
                                  <div class="chartMenu" ng-if="deviceId==device.deviceId">

                                          <span ng-if="device.switchCount==0"><!-- for sensors data -->
                                            <span ng-if="device.field1=='b'">
                                              <ul class="nav nav-tabs" role="tablist">
                                                  <li name='battery' role="presentation"  ng-class="{active:isSelected(1)}">
                                                  <a class="text text-danger" ng-click="showGraph('b',device.deviceId,'battery')">Battery</a></li>
                                              </ul>
                                            </span>
                                            <span ng-if="device.field1=='bm'">
                                               <ul class="nav nav-tabs" role="tablist">
                                                  <li name='battery' role="presentation" ng-class="{active:isSelected(2)}">
                                                  <a class="text text-danger" ng-click="showGraph('bm',device.deviceId,'battery')">Battery</a></li>
                                                  <li name="Moisture" role="presentation" ng-class="{active:isSelected(3)}">
                                                  <a class="text text-danger" ng-click="showGraph('bm',device.deviceId,'moist')">Moisture</a></li>
                                              </ul>
                                            </span>
                                            <span ng-if="device.field1=='bthm'">
                                               <ul class="nav nav-tabs" role="tablist">
                                                  <li name='battery' role="presentation" ng-class="{active:isSelected(4)}">
                                                  <a class="text text-danger" ng-click="showGraph('bthm',device.deviceId,'battery')">Battery</a></li>
                                                  <li name="temperature" role="presentation" ng-class="{active:isSelected(5)}">
                                                  <a class="text text-danger" ng-click="showGraph('bthm',device.deviceId,'temp')">Temperature</a></li>
                                                  <li name='humidity' role="presentation" ng-class="{active:isSelected(6)}">
                                                  <a class="text text-danger" ng-click="showGraph('bthm',device.deviceId,'humid')">Humidity</a></li>
                                                  <li  name='moisture' role="presentation" ng-class="{active:isSelected(7)}">
                                                  <a class="text text-danger" ng-click="showGraph('bthm',device.deviceId,'moist')">Moisture</a></li>
                                              </ul>
                                            </span>
                                          </span>
                                          <span ng-if="device.switchCount==1"><!-- for ESP data , single switch-->
                                              <ul class="nav nav-tabs" role="tablist">
                                                  <li name='Pbattery' role="presentation" ng-class="{active:isSelected(8)}">
                                                  <a class="text text-danger" ng-click="showGraph('1',device.deviceId,'battery')">Primary Battery</a></li>
                                                  <li name="Sbattery" role="presentation" ng-class="{active:isSelected(9)}">
                                                  <a class="text text-danger" ng-click="showGraph('2',device.deviceId,'battery')">Secondary Battery</a></li>
                                              </ul>
                                          </span> 
                                          <div class="pagination-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<big><span ng-if="device.deviceName!=''"><span class="text-info">Device Name:</span>{{device.deviceName}}</span> <span class="text-info">Device Id:</span>{{device.deviceId}}</big></div>
                                  </div>
                            </div><!-- loop ends here -->
                              <div class='row' id="chartDisplay" style="display:none;">
                                  <div class="col-md-10 col-md-offset-1 app-font family-light size-biggerer" id="chart"  style="height:500px;">
                                    Chart will be displayed here
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
    <!-- AngularJs -->
    <script src="../bower_components/angular/angular.min.js"></script>
    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../bower_components/angularjs-datetime-picker/angularjs-datetime-picker.min.js"></script>
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
       var app = angular.module('IOT-App',['angularjs-datetime-picker']);
       app.controller('devicesChart', function($scope, $http) {
            var devices=null;
            var deviceId=null;
            var dataPoints=null;
            var groupId=null;
            var custom=null;
            var tab=null;
            var count='';
            var theme="dark";
            var startDate='';
            var background_color="#525263";
           /* $http.get("dd.php")
            .then(function(response) {
                $scope.devices = response.data;                
            });*/
            $scope.devices = devices;
            $scope.deviceId = deviceId;
            $scope.tab=tab;
            $scope.count=count;
            $scope.startDate=startDate;
            $scope.dataPoints=dataPoints;
            $scope.custom=custom;
            $scope.theme=theme;
            $scope.groupId = groupId;
            $scope.background_color=background_color;
            $scope.groupId = 1;//default

            $scope.selectGroup = function() {
                $http.get("sensors.php?grp="+$scope.groupId)//calling dd.php for retrieving the data
                .then(function(response) {
                    $scope.devices = response.data;
                    $("#chartDisplay").fadeOut(100);
                    //alert(JSON.stringify($scope.devices));
                });
                
            }
            $scope.showDevice=function() {
                    $($scope.device).fadeOut(100);
                    $("#chartDisplay").fadeOut(100);
                    $(".chartMenu").fadeOut(100);
                    $("#back").fadeOut(100);
                    $('#demo').attr('id','page-wrapper');
                    $(".dev").fadeIn(500);
                    $(".navigationIOT").fadeIn(500);

                  }
            $scope.showChart=function(deviceId) {
                    alert($('#datetimepicker3').val());
                    //alert(deviceId)
                    $scope.deviceId = deviceId;
                    $(".navigationIOT").fadeOut(100);
                    $(".dev").fadeOut(100);
                    //$(".chartmenu").fadeOut(100);
                   // $('#wrapper').addClass('col-md-12');
                    $('#page-wrapper').attr('id','demo');
                    //$('#page-wrapper').addClass('col-md-12');
                    $(deviceId).fadeIn(500); 
                    //$("#chart").fadeIn(500);
                    $("#back").fadeIn(500);
                    $(".chartMenu").fadeIn(500);
                    $("#chartDisplay").slideDown("slow");
                  }
            $scope.isSelected = function (checkTab) {
               return ($scope.tab === checkTab);
            }
            $scope.showGraph = function(deviceType, deviceId, feed) {//getting graph ofr a particular field
              //alert(deviceType+' '+feed+' '+deviceId);
               $http.get("displaygraph.php?deviceType="+deviceType+"&deviceId="+deviceId+"&feed="+feed+"&count="+ $scope.count+"&startDate="+$scope.startDate)//calling dd.php for retrieving the data
                .then(function(response) {
                    $scope.dataPoints = response.data;
                    if(deviceType!=1 && deviceType!=2){//for sensors
                      if(feed=='moist'){
                          if(deviceType=='bm')
                            $scope.tab=3;
                          if(deviceType=='bthm')
                            $scope.tab=7;
                          $scope.custom = {
                            "title": "Moisture Values",
                            "id": "Moisture Chart",
                            "yAxisName": "Moisture in adc Value",
                            "unit":"  "
                          };
                      }
                      if(feed=='battery'){
                          if(deviceType=='b')
                            $scope.tab=1;
                          if(deviceType=='bm')
                            $scope.tab=2;
                          if(deviceType=='bthm')
                            $scope.tab=4;
                          $scope.custom = {
                            "title": "Battery Values",
                            "id": "Battery Chart",
                            "yAxisName": "Battery in adc Value",
                            "unit":"  "
                          };
                      }
                      if(feed=='temp'){
                          if(deviceType=='bthm')
                            $scope.tab=5;
                          $scope.custom = {
                            "title": "Temperature Values",
                            "id": "Temperature Chart",
                            "yAxisName": "Temperature in adc Value",
                            "unit":" "
                          };
                      }
                      if(feed=='humid'){
                          if(deviceType=='bthm')
                            $scope.tab=6;
                          $scope.custom = {
                            "title": "Humidity Values",
                            "id": "Humidity Chart",
                            "yAxisName": "Humidity in adc Value",
                            "unit":" "
                          };
                      }
                    }
                    else{
                      if(deviceType==1)
                      {
                        $scope.tab=8;
                        $scope.custom = {
                          "title": "Primary Battery Values",
                          "id": "Primary Battery Chart",
                          "yAxisName": "battery Values in adc",
                          "unit":" "
                        };
                      }
                      if(deviceType==2)
                      {
                        $scope.tab=9;
                        $scope.custom = {
                          "title": "Secondary Battery Values",
                          "id": "Secondary Battery Chart",
                          "yAxisName": "battery Values in adc",
                          "unit":" "
                        };
                      }
                    }


                    $scope.makechart(deviceId, $scope.dataPoints, $scope.custom);
                    $scope.showChart(deviceId);
                    //alert(JSON.stringify($scope.dataPoints));
                });
                
            }
            $scope.makechart=function(deviceId, dataPoints, chartCustom){
            //alert(JSON.stringify($scope.deviceActivities));
              AmCharts.addInitHandler( function( chart ) {
            if(chart.dataProvider.length!=0){

             var dataPoint = chart.dataProvider[ chart.dataProvider.length - 1 ];
             var graph = chart.graphs[0];
             graph.bulletField = "bullet";
             dataPoint.bullet = "round";
           }

               },[ "serial" ]);
               //var chartData = JSON.parse(data); //return json object, converts json string into json objects
                var chart = AmCharts.makeChart("chart",
                  {
                    "type": "serial",
                    "addClassNames": true,
                    //"classNamePrefix": "amcharts", // Default value
                    "theme": $scope.theme,
                    "marginTop": 86,
                    "marginRight": 40,
                    "marginLeft": 86,
                    "fontFamily": "Helvetica",
                    "fontSize": 12,
                    "backgroundColor": $scope.background_color,
                    //"dataDateFormat":"YYYY-MM-DD JJ:NN:SS",
                    "backgroundAlpha": 5,
                    "autoMarginOffset": 20,
                    "dataDateFormat": "YYYY-MM-DD-JJ-NN",
                    "precision":0,
                    "mouseWheelZoomEnabled": true,
                    "valueAxes": [{
                        "title":chartCustom.yAxisName,
                        "unit": chartCustom.unit,
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
                        "title": chartCustom.title,
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
                        "balloonText": "<span style='font-size:18px;'>[[value]]"+chartCustom.unit+"</span>"
                    }],
                    "chartScrollbar": {
                        "graph": "g2",
                        "title": chartCustom.title,
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
                        "title":chartCustom.id,
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
                    "dataProvider": dataPoints
                  }
                );//var chart ends here
  
           }
            $scope.selectGroup();//calling first group initially

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
                if($('.mobileSelect').val()!=null){
                     //showgrp($('.mobileSelect').val());
                      angular.element(document.getElementById('devCharts')).scope().groupId=$('.mobileSelect').val();
                      angular.element(document.getElementById('devCharts')).scope().selectGroup();
                   }
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
        function renderChartBattery(id, data, custom, type, devId)
        {         
         //alert(data);
         
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



</body>

</html>
