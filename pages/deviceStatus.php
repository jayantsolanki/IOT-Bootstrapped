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
<html lang="en" ng-app="IOT-App">

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

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include_once "layouts/navigation.php";?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div id="devIds" class="container-fluid" ng-controller="devicesStatus">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-info">Devices Status</h1>
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
                          <!-- <option selected="true" disabled='disabled'>Choose</option> -->
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
                                        echo " <option selected='selected' value=$id>$group</option>";
                                      else
                                        echo " <option value='$id'>$group</option>";
                                  }
                              }
                          ?>
                          </select>&nbsp; &nbsp; <span id='battery'><button id='batterycheck' class='btn btn-success' onclick="checkbattery()">Check Battery</button></span><span class="pull-right"><button id="activity" class="btn btn-primary" ng-click="showChart()">Connection Activity</button>&nbsp;<button id="back" class="btn btn-primary" ng-click="showDevice()" style="display:none;">Back</button></span></br></br>
                          <strong>Group Selected <big class ="label label-primary">{{devices[0].groupName}}</big></strong><hr/>
                          <div id='dev'>
                              <div class="row" ng-repeat="device in devices">
                                  <div class="col-md-2">
                                       <h4 class="text-danger" ><small ng-if="device.newDevice"><a href="devManagement.php" data-toggle='tooltip' title='New Device {{device.created}}' class='text-info fa fa-circle-o-notch fa-spin fa-2x'></a> <br></small>{{device.deviceName}}</h4>
                                       <p><strong class='text-danger'>Type:</strong> {{device.type}}</p>
                                        <span ng-if="device.switchCount>=1">
                                          <p><button class="btn btn-info badge" ng-click="selectDevice(device.deviceId,$index)">Switches:</button> {{device.switchCount}}</p>
                                        </span>
                                        
                                  </div>
                                  <div class="col-md-5">
                                     <blockquote>
                                         <p>
                                            <span ng-if="device.status" class="label label-success">Online</span>
                                            <span ng-if="!device.status" class="label label-danger">Offline</span>
                                            <cite class="text-info">since {{device.seen}}</cite>
                                        </p>
                                        <p><strong class="text text-info">Device Id:</strong> {{device.deviceId}}</p>
                                        <!-- <p><strong class="text text-info">Status:</strong> {{device.action}}</p> -->
                                        <p>
                                          <strong ng-if="device.switchCount<2" class="text text-info">Battery Level:</strong>
                                          <blockquote ng-if="device.switchCount<2" >
                                            <span ng-if="device.switchCount==1" class='text-success'>Primary: <strong>{{device.PbatValue/1024 | number : 3}} Volts </strong></span>
                                            <span ng-if="device.typeId==2" class='text-success'>Primary: <strong>{{device.PbatValue/4096*4 | number : 3}} Volts </strong><!-- for the sensor --></span>
                                            <span class='text-danger' ng-if="device.switchCount==1"><br>Secondary:<strong>{{device.SbatValue/137.1428571428571 | number : 3}} Volts </strong></span><br>
                                            <small class="text-muted">Last updated {{device.batTime}}</small>
                                          </blockquote>
                                        </p>
                                    </blockquote>
                                  </div><!-- end inner div -->
                                  <div ng-if="devices[$index].switches[0].deviceId==device.deviceId" class="col-md-5" id="{{device.deviceId}}">
                                      <table class='table table-striped'>
                                       
                                        <caption class="text-info text-center">{{device.deviceId}}</caption>
                                        <thead>
                                          <th>SwitchId</th><th>Group</th><th>Status</th>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="switch in devices[$index].switches">
                                                <td>{{switch.switchId}} <small ng-if="switch.newSwitch"><a href="devManagement.php" data-toggle='tooltip' title='New Device {{switch.created}}' class='text-info fa fa-circle-o-notch fa-spin fa-2x'></a></small></td>
                                                <td>{{switch.groupName}}</td>
                                                <td ng-if="switch.action==1"><span data-toggle='tooltip' title='New Device {{switch.actionSince}}' class="label label-success">Opened</span></td>
                                                <td ng-if="switch.action==0"><span data-toggle='tooltip' title='New Device {{switch.actionSince}}' class="label label-danger">Closed</span></td>
                                            </tr><!-- loop ends here -->
                                        <tbody>
                                      </table>

                                  </div><!-- switches -->
                              </div><!-- loop ends here -->
                            </div>
                          </div><!-- ending dev div -->
                          <div class='row' id="chart" style="display:none;">
                            <div class="col-md-1" id="spacer"  >
                            </div>
                            <div class="col-md-11" id="deviceActivity"  style="height:170px;font-size:11px; ">
                            </div>
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
    <!-- AngularJs -->
    <script src="../bower_components/angular/angular.min.js"></script>
    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
     <script type="text/javascript" src="../dist/js/bootstrap-fullscreen-select.js"></script>
     <script src="../bower_components/amcharts3/amcharts/amcharts.js"></script>
     <script src="../bower_components/amcharts3/amcharts/serial.js"></script>
     <script src="../bower_components/amcharts3/amcharts/themes/dark.js"></script>
     <script src="../bower_components/amcharts3/amcharts/gantt.js"></script>
    
     <script>
      //var ws=null;
      function checkbattery() { //websocket
          //var wscon=null;
            var ws = new WebSocket("ws://10.129.139.139:8180");//changer later for production release;
            
          
          ws.onopen = function(e) {
            console.log('Connection to server opened');
            if(ws!=null){//sending data via websocket
          //if(ws.readyState == 1) {
              var jsonS={
                   "check":'battery',
                   "device":0,//0 for all device
                   "payload":2
                   };
                ws.send(JSON.stringify(jsonS));
                console.log('Battery request sent');
                document.getElementById('battery').innerHTML="<big><span class='label label-info'>Battery Status requested</span></big>";
               // var valElem = $('#sss');
                //valElem.html(JSON.stringify(jsonS));

           // }
          }
          }
              //var valElem = $('#sss');
          
          ws.onclose = function(e) {
            console.log("Connection closed");
          }
          ws.onerror = function(e) {
            console.log("Connection error");
            document.getElementById('battery').innerHTML="<big><span class='label label-danger'> Mosca Server Offline</span></big>";
          }
          function disconnect() {
            ws.close();
          }
      }
    </script>
     <script>
        var app = angular.module('IOT-App',[]);
       app.controller('devicesStatus', function($scope, $http) {
            var devices=null;
            var switches=null;
            var groupId=null;
            var columnWidth=0.5;
            var deviceActivities=null;
           /* $http.get("dd.php")
            .then(function(response) {
                $scope.devices = response.data;                
            });*/
            $scope.devices = devices;
            $scope.columnWidth = columnWidth;
            $scope.deviceActivities = deviceActivities;
            $scope.groupId = groupId;
            $scope.groupId = 1;//default

            $scope.selectGroup = function() {
                $http.get("dd.php?grp="+$scope.groupId)//calling dd.php for retrieving the data
                .then(function(response) {
                    $scope.devices = response.data;
                    $scope.showActivity();
                });
                
            }
            $scope.showDevice=function() {
                    $("#chart").fadeOut(100);
                    $("#back").fadeOut(100);
                    $('#demo').attr('id','page-wrapper');
                    $("#dev").fadeIn(500);
                    $("#activity").fadeIn(500);
                    $(".navigationIOT").fadeIn(500);

                  }
            $scope.showChart=function() {
                    $(".navigationIOT").fadeOut(100);
                   // $('#wrapper').addClass('col-md-12');
                    $('#page-wrapper').attr('id','demo');
                    //$('#page-wrapper').addClass('col-md-12');
                    $("#activity").fadeOut(100);
                    $("#dev").fadeOut(100); 
                    $("#chart").fadeIn(500);
                    $("#back").fadeIn(500);
                  }
            $scope.selectDevice = function(deviceId, index) {//getting switches
              //alert(index);
                if($scope.devices[index].temp!=0 && $scope.devices[index].temp!=1)
                  $scope.devices[index].temp=1;

                $http.get("dd.php?deviceId="+deviceId)//calling dd.php for retrieving the data
                .then(function(response) {
                    $scope.devices[index].switches = response.data;
                    if($scope.devices[index].temp==0){
                      $scope.devices[index].temp=1;
                      $scope.devices[index].switches[0].deviceId=0;
                      //console.log($scope.devices[index].temp);
                    }
                    else{
                      $scope.devices[index].temp=0;
                      //console.log($scope.devices[index].temp);

                    }
                });
                
            }
            $scope.showActivity = function() {
                $http.get("dd.php?deviceActivity="+1+"&grpId="+$scope.groupId)//calling dd.php for retrieving the data
                .then(function(response) {
                    $scope.deviceActivities = response.data;
                    //alert(JSON.stringify(response.data));
                    $("#deviceActivity").height(170);
                    if($scope.devices.length>2)
                    {
                      var height=45*$scope.devices.length;
                       $("#deviceActivity").height(height);
                    }
                    $scope.makechart();//create chart        
                });
                
            }
            $scope.makechart=function(){
            //alert(JSON.stringify($scope.deviceActivities));
            var chart = AmCharts.makeChart("deviceActivity", {
                "type": "gantt",
                "theme": "dark",
                "marginRight": 70,
                //"period": "ss",
                "backgroundColor": "#525263",
                //"dataDateFormat":"YYYY-MM-DD JJ:NN:SS",
                "backgroundAlpha": 5,
                "balloonDateFormat": "JJ:NN:SS, DD MMM",
                "columnWidth": 0.5,
                "autoDisplay":true,
                "tapToActivate": true,
                "autoResize": true,
                "creditsPosition": "bottom-right",
                "valueAxis": {
                    "title": "Online/offline Status",
                    "type": "date",
                    //"minimum": 0,
                    //"maximum": 31,
                    //"parseDates": true,
                    //"equalSpacing" : true,
                    //"Period":"ss"
                   //"dashLength": 1,
                    //"minorGridEnabled": true
                },
                "categoryAxis":{
                  "title": "Devices"
                },
                //"brightnessStep": 10,
                "graph": {
                    "title": "Online/offline Status",
                    "fillAlphas": 1,
                    "balloonText": "<b>[[task]]</b>: [[duration]]"
                },
                "rotate": true,
                "categoryField": "category",
                "segmentsField": "segments",
                "colorField": "color",
                "startDate": "2016-03-28 11:40:20",
                "startDateField": "start",
                "endDateField": "end",
                //"endDate":"2016-04-03 11:40:20",
                //"durationField": "duration",
                "dataProvider": $scope.deviceActivities,
                "valueScrollbar": {
                    "autoGridCount":true
                },
                "chartCursor": {
                    "cursorColor":"#33DDe4",
                    "valueBalloonsEnabled": false,
                    "cursorAlpha": 0,
                    "valueLineAlpha":$scope.columnWidth,
                    "valueLineBalloonEnabled": true,
                    "valueLineEnabled": true,
                    "zoomable":true,
                    "valueZoomable":true
                },
                "export": {
                    "enabled": true
                 }
            } );
  
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
                if($('.mobileSelect').val()!=null)
                   angular.element(document.getElementById('devIds')).scope().groupId=$('.mobileSelect').val();
                  angular.element(document.getElementById('devIds')).scope().selectGroup();
            },
            style: 'btn-info'
        });
        $('.mobileSelect').mobileSelect();
    </script>

       
</body>

</html>
