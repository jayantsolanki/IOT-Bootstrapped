<?php
    include_once 'settings/iotdb.php';
  //echo "<h4>Valves grouped under <label class='badge'>".$gname."</label></h4>";
  $query="SELECT * FROM devices WHERE devices.status=1";//new dev
  $results=mysqli_query($GLOBALS["___mysqli_ston"], $query);
  $newdev=0;
  if(mysqli_num_rows($results)>0)
    $newdev=mysqli_num_rows($results);
$query="SELECT * FROM switches WHERE switches.newSwitch=1";//new switches
  $results=mysqli_query($GLOBALS["___mysqli_ston"], $query);
  $newSwitch=0;
  if(mysqli_num_rows($results)>0)
    $newSwitch=mysqli_num_rows($results);
  $query="SELECT * FROM tasks ";    
  $results=mysqli_query($GLOBALS["___mysqli_ston"], $query);
  $task=0;
  if(mysqli_num_rows($results)>0)
    $task=mysqli_num_rows($results);
 $query="SELECT * FROM tasks where active=2 ";    
  $results=mysqli_query($GLOBALS["___mysqli_ston"], $query);
  $newtask=0;
  if(mysqli_num_rows($results)>0)
    $newtask=mysqli_num_rows($newtask);

    $query="SELECT * FROM tasks where type=0";    
      $results=mysqli_query($GLOBALS["___mysqli_ston"], $query);
      $mantask=0;//manually started
      if(mysqli_num_rows($results)>0)
        $mantask=mysqli_num_rows($results);

    $query="SELECT * FROM tasks where active=1";    
      $results=mysqli_query($GLOBALS["___mysqli_ston"], $query);
      $runtask=0;//manually started
      if(mysqli_num_rows($results)>0)
        $runtask=mysqli_num_rows($results);
     $query="SELECT * FROM tasks where active=2";    
      $results=mysqli_query($GLOBALS["___mysqli_ston"], $query);
      $newtask=0;//manually started
      if(mysqli_num_rows($results)>0)
        $newtask=mysqli_num_rows($results);

     $statusquery="SELECT status from deviceStatus where id in (Select MAX(id) as cid from deviceStatus where deviceId in (Select deviceId from devices) group by deviceId) and status=1";    
      $results=mysqli_query($GLOBALS["___mysqli_ston"], $statusquery);
      $online=0;//
      if($results)
        $online=mysqli_num_rows($results);
    $statusquery="SELECT deviceStatus.status from deviceStatus where id in (Select MAX(id) as cid from deviceStatus where deviceId in (Select deviceId from devices) group by deviceId) and deviceStatus.status=0";    
      $results=mysqli_query($GLOBALS["___mysqli_ston"], $statusquery);
      $offline=0;//manually started
      if($results)
        $offline=mysqli_num_rows($results);


    ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>IOT-Dashboard</title>

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="../dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="glyphicon glyphicon-gift fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $newdev;?></div>
                                    <div>New Devices!</div>
                                </div>
                            </div>
                        </div>
                        <a href="deviceStatus.php">
                            <div class="panel-footer">
                                <span class="pull-left"><?php echo ($online+$offline)?> Devices</span>
                                <span class="pull-right">View Details <i class="fa fa-arrow-circle-right"></i></span>
                                <span class="pull-right"></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="glyphicon glyphicon-time fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $task;?></div>
                                    <div>Scheduled Tasks!</div>
                                </div>
                            </div>
                        </div>
                        <a href="schedule.php">
                            <div class="panel-footer">
                                <span class="pull-right">View Details <i class="fa fa-arrow-circle-right"></i></span>
                                <span class="pull-right"></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-support fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">2</div>
                                    <div>Server Checks!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-right">View Details <i class="fa fa-arrow-circle-right"></i></span>
                                <span class="pull-right"></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-8">
                    
                    <!-- /.panel -->
                    
                    <!-- /.panel -->
                   
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->
                <!-- <div class="col-lg-4"> -->
                
                <div class="col-lg-8 col-md-8">
                    <iframe id="forecast_embed" type="text/html" frameborder="0" height="245" width="100%" src="http://forecast.io/embed/#lat=42.9532&lon=78.8263&color=#113355&units=uk&name=50 Heath Street, Buffalo, NY"> 
                    </iframe>
                </div>
                <div class="col-lg-4 col-md-4 col-lg-offset-0">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> Notifications Panel
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
                                <a href="deviceStatus.php" class="list-group-item">
                                    <i class="fa fa-envelope fa-fw"></i> <?php echo $online;?> <span class="text text-success"> Online</span> Devices
                                    <span class="pull-right text-muted small"><em></em>
                                    </span>
                                </a>
                                <a href="deviceStatus.php" class="list-group-item">
                                    <i class="fa fa-envelope fa-fw"></i> <?php echo $offline;?><span class="text text-danger"> Offline</span> Devices
                                    <span class="pull-right text-muted small"><em></em>
                                    </span>
                                </a>
                                <?php if($runtask!=0) {?>
                                <a href="schedule.php" class="list-group-item">
                                    <i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;&nbsp;<?php echo $runtask;?> <span class="text text-info"></span>tasks running
                                    <span class="pull-right text-muted small"><em></em>
                                    </span>
                                </a>
                                <?php }?>
                                <?php if($newtask!=0) {?>
                                <a href="schedule.php" class="list-group-item">
                                    <i class="fa fa-envelope fa-fw"></i> <?php echo $newtask;?> <span class="text text-info"> new </span>Scheduled tasks
                                    <span class="pull-right text-muted small"><em></em>
                                    </span>
                                </a>
                                <?php }?>
                                <a href="schedule.php" class="list-group-item">
                                    <i class="fa fa-envelope fa-fw"></i> <?php echo $mantask;?> manually started tasks
                                    <span class="pull-right text-muted small"><em></em>
                                    </span>
                                </a>
                                <a href="#" class="list-group-item">
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="pull-right text-muted small"><em>11:32 AM</em>
                                    </span>
                                </a>
                                                         
                            </div>
                            <!-- /.list-group -->
                            <a href="#" class="btn btn-default btn-block">View All Alerts</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> KyantraIITB
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <a class="twitter-timeline" href="https://twitter.com/kyantraIITB" data-widget-id="738695381995769857">Tweets by @kyantraIITB</a>
                             
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    <!-- /.panel -->
                    
                    <!-- /.panel .chat-panel -->
                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
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

    <!-- Morris Charts JavaScript -->
    <!--<script src="../bower_components/raphael/raphael-min.js"></script>
    <script src="../bower_components/morrisjs/morris.min.js"></script>
    <script src="../js/morris-data.js"></script>-->

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <script>
        !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
    </script>

</body>

</html>
