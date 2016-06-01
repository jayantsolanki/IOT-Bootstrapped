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

    <title>Device responses</title>

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../bower_components/angular-growl-v2/build/angular-growl.min.css" rel="stylesheet" type="text/css">
    <link href="../bower_components/angular-dropdowns/angular-dropdowns.css" rel="stylesheet" type="text/css">
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
            <div id="automate" class="container-fluid" ng-controller="automat">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-info">System Health and Automation Control</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                        <div class="well col-md-4">
                            <span ng-show="errors!=null" class="row" ng-repeat="error in errors">
                                <li class='text-danger'><strong>{{error.type}}:</strong> {{error.value}}</li>
                            </span>
                            <form ng-submit="formsubmit()">
                                <fieldset class='form-group'>
                                  <label for='name'>Name</label>
                                  <input type='text' class='form-control' id='name' ng-model=name placeholder='Name of Task' value='{{name}}'>
                                </fieldset>
                                <fieldset class='form-group'>
                                  <label for='group'>Select Group</label><br/>
                                  <div class="pull-left" dropdown-select="groups" dropdown-model="group"  dropdown-item-label="text" >
                                  </div>
                                </fieldset>
                                <fieldset class='form-group'>
                                  <label for='field'>Select Field to monitor</label><br/>
                                  <div class="pull-left" dropdown-select="fields" dropdown-model="field"  dropdown-item-label="text" >
                                  </div>
                                </fieldset>
                                <fieldset class='form-group'>
                                  <label for='action'>Select Action to perform</label><br/>
                                  <div class="pull-left" dropdown-select="actions" dropdown-model="actionSelect"  dropdown-item-label="text" >
                                  </div>
                                </fieldset>
                                <fieldset ng-show="field.text!='Online/Offline'" class='form-group'>
                                  <label for='condition'>Select Condition case</label><br/>
                                  <div class="pull-left" dropdown-select="conditionCases" dropdown-model="conditionCase"  dropdown-item-label="text" >
                                  </div>
                                </fieldset>
                                <fieldset ng-show="field.text!='Online/Offline'" class='form-group'>
                                  <label for='conditionVal'>Select Condition value</label>
                                  <input type='text' class='form-control' id='conditionVal' ng-model=conditionVal placeholder='Condition Value' value='{{conditionVal}}'>
                                </fieldset>
                                <span ng-if="group.text!=null" class='alert alert-warning pull-right'>If <strong class="text-info">{{field.text}} {{conditionCase.text}} {{conditionVal}}</strong> in Group <strong class="text-info">{{group.text}}</strong> then do  <strong class="text-info">{{actionSelect.text}}</strong></span></br>
                                <button class="btn btn-success" id="button" type="submit" value="add">Add</button>
                            </form>
                        </div><!-- ending well-->
                        <div class=" small well col-md-7 col-md-offset-1" style="height:490px; overflow-y: scroll;">
                            <table class='table table-striped'>                                       
                                <caption class="text-info text-center">Device Health (Valves)</caption>
                                <thead>
                                  <th>DeviceId</th><th>PBat</th><th>SBat</th><th>Connection</th>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="deviceNotif in deviceNotifs | filter:{deviceType:'1'}">
                                        <td>{{deviceNotif.deviceId}}</td>
                                        <td>{{deviceNotif.Field1}}</td>
                                        <td>{{deviceNotif.Field2}}</td>
                                        <td align="center">{{deviceNotif.Field6}}</td>   
                                    </tr><!-- loop ends here -->
                                <tbody>
                            </table>
                            <table class='table table-striped'>                                       
                                <caption class="text-info text-center">Device Health (Sensors)</caption>
                                <thead>
                                  <th>DeviceId</th><th>Bat</th><th>Temp</th><th>Moisture</th><th>Humidity</th><th>Connection</th>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="deviceNotif in deviceNotifs | filter:{deviceType:'2'}">
                                        <td align="center">{{deviceNotif.deviceId}}</td>
                                        <td>{{deviceNotif.Field1}}</td>
                                        <td>{{deviceNotif.Field2}}</td>
                                        <td>{{deviceNotif.Field3}}</td>
                                        <td align="center">{{deviceNotif.Field4}}</td> 
                                        <td align="center">{{deviceNotif.Field6}}</td>   
                                    </tr><!-- loop ends here -->
                                <tbody>
                            </table>
                        </div>
                      
                </div>
                <div class="row">
                    <table class='table table-striped'>                                       
                        <caption class="text-info text-center">Monitoring List</caption>
                        <thead>
                          <th>Name</th><th>Group</th><th>Field</th><th>Action</th><th>Condition</th><th>Threshold</th><th></th>
                        </thead>
                        <tbody>
                            <tr ng-repeat="task in tasks">
                                <td align="center">{{task.name}}</td>
                                <td>{{task.groupName}}</td>
                                <td>{{task.field}}</td>
                                <td>{{task.actionName}}</td>
                                <td align="center">{{task.conditionCase}}</td> 
                                <td align="center">{{task.conditionValue}}</td>
                                <td><a class='text-danger glyphicon glyphicon-trash' data-toggle='tooltip' title='Delete' ng-click='del(task.id)'></a></td>    
                            </tr><!-- loop ends here -->
                        <tbody>
                    </table>
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
    <script src="../bower_components/ng-websocket/ng-websocket.js"></script>
    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
     <script type="text/javascript" src="../dist/js/bootstrap-fullscreen-select.js"></script>
     <script src="../bower_components/angular-growl-v2/build/angular-growl.min.js"></script>
     <script src="../bower_components/angular-animate/angular-animate.min.js"></script>
     <script src="../bower_components/angular-dropdowns/angular-dropdowns.js"></script>
 
     <script>
        var app = angular.module('IOT-App',['ngWebsocket', 'ngDropdowns']);
       app.controller('automat', function($scope, $http, $websocket, $window, $interval) {
            var name=null;
            var groups=null;
            var group=null;
            var actions=null;
            var tasks=null;
            var deviceNotifs=null;
            var fields=null;
            var conditionVal=null;
            var conditionCase=null;
            var errors=null;
            $scope.name=name;
            $scope.errors=errors;
            $scope.conditionVal=conditionVal;
            $scope.conditionCase=conditionCase;
            $scope.fields=fields;
            $scope.groups=groups;
            $scope.group=group;
            $scope.actions=actions;
            $scope.tasks=tasks;
            $scope.deviceNotifs=deviceNotifs;
            var url=null;
            $scope.fetchGroups = function() {
                $http.get("autotasks.php?groups=1")//calling dd.php for retrieving the data
                .then(function(response) {
                    $scope.groups = response.data;
                     $scope.fetchActions();
                });
                
            }
            $scope.fetchActions = function() {
                $http.get("autotasks.php?actions=1")//calling dd.php for retrieving the data
                .then(function(response) {
                    $scope.actions = response.data;
                });
                
            }
            $scope.fetchTasks = function() {
                $http.get("autotasks.php?reactJS=1")//calling dd.php for retrieving the data
                .then(function(response) {
                    $scope.tasks = response.data;
                    //console.log(JSON.stringify($scope.tasks));
                });
                
            }
            $scope.fetchDeviceNotif = function() {
                $http.get("autotasks.php?notif=1")//calling dd.php for retrieving the data
                .then(function(response) {
                    $scope.deviceNotifs= response.data;
                    console.log(JSON.stringify($scope.deviceNotifs));
                });
                
            }
             $scope.fetchDeviceNotif();
            /*$interval(function(){//for updating the deviceNotif ajax call 
                $scope.fetchDeviceNotif();
            }, 10000);*/
            $scope.del = function(id) {
                if($window.confirm('Confirm Delete')){
                    $http.get("autotasks.php?del="+id)//calling dd.php for retrieving the data
                    .then(function(response) {
                        $scope.errors = response.data;
                        //console.log(JSON.stringify($scope.errors));
                        $scope.fetchTasks();
                    });
                }
                
            }
            $scope.formsubmit = function () {
                //console.log(2);
                if($scope.name==null || $scope.name==''){
                    $scope.errors=[
                    {
                        type:'Error',
                        value:"Name field is blank"
                    }
                    ]
                }
                else{
                    if($scope.field.value==='Online/Offline'){
                        url="autotasks.php?name="+$scope.name+"&group="+$scope.group.value+"&field="+$scope.field.value+"&actionSelect="+$scope.actionSelect.value;
                    }
                    else
                        url="autotasks.php?name="+$scope.name+"&group="+$scope.group.value+"&field="+$scope.field.value+"&actionSelect="+$scope.actionSelect.value+"&conditionCase="+ $scope.conditionCase.value+"&conditionVal="+$scope.conditionVal;


                    $http.get(url)
                    .then(function(response) {
                        $scope.errors = response.data;
                        //console.log(JSON.stringify($scope.errors));
                    });
                }
                $scope.fetchTasks();
            }
            $scope.fields = [
                {
                    text:"battery",
                    value: "battery"
                },
                {
                    text:"moisture",
                    value: "moisture"
                },
                {
                    text:"Online/Offline",
                    value: "Online/Offline"
                }

            ];
            $scope.conditionCases = [
                {
                    text:"<",
                    value: "<"
                },
                {
                    text:">",
                    value: ">"
                },
                {
                    text:"==",
                    value: "=="
                }

            ];
            $scope.group = {}; // Must be an object
            $scope.actionSelect = {}; // Must be an object
            $scope.field = {};
            $scope.conditionCase = {};
            $scope.fetchGroups();
            $scope.fetchTasks();

        });
    </script>       
</body>

</html>
