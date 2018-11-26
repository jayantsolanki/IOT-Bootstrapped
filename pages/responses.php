<?php 
/*
*Project:IoT-Connected-valves-for-irrigation-of-greenhouse
*File name: deviceStatus.php
*Author: Jayant Solanki
*It is for displaying devices information
*/
include_once 'settings/iotdb.php';
include_once 'settings/mqttsetting.php'; //environmental variable for mqtt address and websocket

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
    <link rel="stylesheet" type="text/css" href="../dist/css/bootstrap-fullscreen-select.css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-113067729-3"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-113067729-3');
    </script>

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
                        <h1 class="page-header text-info">Console Logger</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row" style="overflow: hidden;">
                        <div class="well" style="height:750px; overflow-x:scroll ; overflow-y: scroll;">
                          <div growl inline="true"></div>
                        </div><!-- ending panel body -->
                      
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
 
     <script>
        var app = angular.module('IOT-App',['ngWebsocket', 'angular-growl', 'ngAnimate']);
       app.controller('devicesStatus', function($scope, $websocket, $filter, growl) {
            var ws=null;
            var batbtn="Check Battery";
            $scope.ws=ws;
            $scope.wsConnect = function() {//establishing the websocket connection with the server
                $scope.ws=$websocket.$new('ws://<?php echo $address; ?>:8180');
                // $scope.ws=$websocket.$new('ws://k-yantra.org:8080');
                $scope.ws.$on('$open', function () {
                 console.log('Connection to server opened');
                });
                $scope.ws.$on('$close', function () {
                 console.log('Connection to server closed');
                });
                $scope.ws.$on('$error', function () {
                 console.log('Error connecting to the server');
                });
                $scope.ws.$on('$message', function(data) {//receving the message

                  //$scope.devices[id].seen=$filter('date')(new Date(), 'HH:mm:ss, MMM dd ');
                  var test = "data";
                  if(data['status']==1)//online
                    growl.info(data.deviceId+' is online');
                  
                  else if(data['status']==0)//offline
                    growl.info(data.deviceId+' is offline');
                  
                  else 
                    $scope.showData(data);
                });

            }
            $scope.showInfo = function(data){
                growl.info(data);
            }
            $scope.showWarning = function(data){
                growl.warning(data);
            }
            $scope.showError = function(data){
                growl.error(data);
            }
            $scope.showData = function(data){
                growl.success("<p style='word-wrap: break-word;'>"+JSON.stringify(data)+"</p>");
            }
            $scope.wsConnect();//initialzing the websocket connection

        });
      app.config(['growlProvider', function (growlProvider) {
        growlProvider.globalTimeToLive(60000);
        growlProvider.onlyUniqueMessages(false);
        growlProvider.globalReversedOrder(true);
        growlProvider.globalInlineMessages(true);
      }]);
    </script>       
</body>

</html>
