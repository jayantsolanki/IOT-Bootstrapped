<?php
require 'settings/iotdb.php';
// mysqli_select_db($dbname) or die(mysqli_error());
$query="SELECT * FROM global_variables where variable_name='mqtt'";
$results=mysqli_query($con, $query);
$mqttaddress=null;
if (mysqli_num_rows($results) > 0) 
{
    while($row = mysqli_fetch_assoc($results))
    {
        $mqttaddress=$row['value'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Global Variables Configuration</title>

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

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include_once "layouts/navigation.php";?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header text-info">Global Variables Configuration</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <div class='col-lg-6'>
                    <label class='form-control'>Current Mqtt Address:&nbsp;</label><label id='mqttshow'><?php if($mqttaddress==null) echo 'No address saved'; else echo $mqttaddress; ?></label>
                        <div class="form-inline">
                          <div class="form-group row">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon" for="mqttaddress">MQTT Address</div>
                                  <input type="text" class="form-control" id="mqttaddress" placeholder="tcp://10.129.28.181:1880/">
                                </div>
                              </div>
                              <button id='savebutton' class="btn btn-primary" onclick="update();">Save</button>
                          </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <script>
    function update(){
        
    var value=document.getElementById("mqttaddress").value;
    //alert(value);
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
          document.getElementById("savebutton").innerHTML="Saving...";
          }
      if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
        document.getElementById("mqttshow").innerHTML=xmlhttp.responseText;
        document.getElementById("savebutton").innerHTML='Saved';
        }
      }
    //var sensor = document.getElementById("mqttshow").value;
    xmlhttp.open('GET','saveConfig.php?q='+value,true);
    //alert("Hello! I am an alert box!!");
    xmlhttp.send();
        }
    </script>
</body>

</html>
