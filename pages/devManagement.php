<?php 
/*
*Project: IoT-Connected-valves-for-irrigation-of-greenhouse
*File name: devManagement.php
*Author: Jayant Solanki
*user interface for managing devices
*/
include_once 'settings/iotdb.php';?>
<?php
date_default_timezone_set('Asia/Kolkata');//setting IST
//echo "Time is ".date('Hi');?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Device Management</title>

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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <span class='pull-right'><button id="back" class="btn btn-primary glyphicon glyphicon-arrow-left" onclick="showMenu()" style="display:none;"></button><button id="fullscreen" class="btn btn-primary glyphicon glyphicon-resize-full" onclick="showDevice()" style="display:none;"></button></span><h1 class="page-header text-info">Device Management</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">                  
                  <div class="row"><!-- 1st inner row -->
                    <div class="col-md-12">
                    <div class = "panel panel-info ">
                       <div class = "panel-heading">
                          <big>Manage Devices</big> <small class="text-muted">Add your new type of device/group here</small>
                       </div>
                       <div class = "panel-body">
                        <div class='col-md-6'>
                            <form class="form-inline">
                              <fieldset  class="form-group form-inline">
                              <label>Add Type</label>
                              <input type='text' id='sensor' name='sensor' placeholder="new device type" required/>
                              </fieldset>
                              <button class="btn btn-primary" id='button' type='button' onclick='addsen();' value='add' required>Add</button>
                            </form>
                            <div id='sensors'>

                              <?php
                               // mysqli_select_db($dbname) or die(mysqli_error());
                                $query="SELECT * FROM sensors"; //displaying groups
                                $results=mysqli_query($con, $query);
                                if (mysqli_num_rows($results) > 0) 
                                {   $i=1;
                                    echo "</br></br><big>Device Types available</big><hr/>";        
                                    while($row=mysqli_fetch_assoc($results)) 
                                    {   //$id=$row['id'];
                                        $id=$row['id'];
                                        $sensor=$row['name'];
                                        echo "<span id='sens".$id."'><strong class='text-info'>".$i.".</strong>&nbsp; &nbsp; <big class=''>$sensor </big>&nbsp; &nbsp;<a class='text-muted glyphicon glyphicon-pencil' data-toggle='tooltip' title='Edit device type' href='javascript:editName($id, 0)'></a>&nbsp; &nbsp;<a class='text-danger glyphicon glyphicon-remove-circle' data-toggle='tooltip' title='Delete' href="."javascript:dels('$sensor')"."></a></span><hr>";
                                        $i++;
                                        
                                        
                                    }
                                }
                                else
                                {
                                    echo "</br><div class='notice'><b>No Sensors added yet.</b></div>";
                                }

                              ?>  
                            </div><!-- sensors ends -->
                          </div><!-- 1st column -->
                          <div class='col-md-6 '>
                            <form class="form-inline">
                              <fieldset  class="form-group form-inline">
                              <label>Add Group</label>
                              <input type='text' id='group' name='group' placeholder="new group name" required/>
                              </fieldset>
                              <button class="btn btn-primary" id='button' type='button' onclick='addgrp();' value='add'>Add</button>
                            </form>
                            <div id='groups'>

                              <?php
                                  //mysqli_select_db($dbname) or die(mysqli_error());
                                  $query="SELECT * FROM groups"; //displaying groups
                                  $results=mysqli_query($con, $query);
                                  if (mysqli_num_rows($results) > 0) 
                                  {   $i=1;
                                      echo "</br></br><big>Groups available</big><hr/>";     
                                      while($row=mysqli_fetch_assoc($results)) 
                                      {   //$id=$row['id'];
                                          $id=$row['id'];
                                          $group=$row['name'];
                                          echo "<span id='grp".$id."'><strong class='text-info'>".$i.".</strong>&nbsp; &nbsp; <big class=''>$group </big>&nbsp; &nbsp;<a class='text-muted glyphicon glyphicon-pencil' data-toggle='tooltip' title='Edit group name' href='javascript:editName($id, 1)'></a>&nbsp; &nbsp;<a class='text-danger glyphicon glyphicon-remove-circle' data-toggle='tooltip' title='Delete' href="."javascript:del('$group')"."></a></span><hr>";
                                          $i++;
                                          
                                          
                                      }
                                  }
                                  else
                                  {
                                      echo "</br><div class='notice'><b>No groups created yet.</b></div>";
                                  }

                              ?>  
                            </div><!-- groups ends -->
                          </div><!-- 2nd column -->
                      </div><!-- panel body -->
                    </div><!-- panel ends here -->
                  </div>
                  </div><!--1st inner row ends here-->
                  <div class="row"><!-- 2nd inner row -->
                    <div class='col-md-12'>
                      <div class = "panel panel-danger ">
                         <div class = "panel-heading">
                            <big>Devices available</big> <small class='text-muted'>Manage individual devices</small>
                         </div>
                         <div class = "panel-body">
                            <div id='items'>

                                <?php

                                //mysqli_select_db($dbname) or die(mysqli_error());
                               //include'settings/iotdb.php';
				 display($con);
                                function display($con){
                                  $query="SELECT devices.name as name,devices.groupId as dgroupId, devices.type as type, devices.status as status, devices.deviceId as deviceId, switches.switchId as switchId, switches.groupId as sgroupId, switches.newSwitch as newSwitch, switches.created_at as created_at FROM devices left join switches on switches.deviceId=devices.deviceId where devices.switches>=0"; //displaying groups
                                  $results=mysqli_query($con, $query);
                                  if (mysqli_num_rows($results) > 0) 
                                  {   $i=1;    
                                      while($row=mysqli_fetch_assoc($results)) 
                                      {   
                                          $deviceId=$row['deviceId'];//for switches
                                          $switchId=$row['switchId'];//for switches
                                          $deviceName=$row['name'];//for devices
                                          $sgroupId=$row['sgroupId'];//for switches
                                          $dgroupId=$row['dgroupId']; //for devices
                                          $sstatus=$row['newSwitch'];
                                          $dstatus=$row['status'];
                                          $type=$row['type'];
                                          $created_at=$row['created_at'];
                                          //$group=$row['name'];
                                          

                                          $query="SELECT name FROM sensors WHERE id=$type";
                                          $typename=mysqli_query($con, $query);
                                          $typerow=mysqli_fetch_assoc($typename);
                                          $type=$typerow['name'];
                                          $status=null;
                                          if($switchId==null){//if it is a switchless device like sensor node
                                            $groupId=$dgroupId;

                                            $switchId=0;
                                            //if($dstatus==1)
                                              //$status="<span data-toggle='tooltip' title='New Device' class='text-info fa fa-cog fa-spin fa-2x'></span>";
                                          }
                                          else
                                            $groupId=$sgroupId;
					if ($groupId==NULL)
						$name="Not Assigned";
					else
					{
                                          $query="SELECT name FROM groups WHERE id='$groupId'";
                                          $grps=mysqli_query($con, $query);
                                          $grp=mysqli_fetch_assoc($grps);
                                          $name=$grp['name'];
					}
                                          if($switchId!=0 and $sstatus==1)
                                            $status="<span data-toggle='tooltip' title='New Device' class='text-info fa fa-circle-o-notch fa-spin'></span>";
                                          if($switchId==0 and $dstatus==1)
                                            $status="<span data-toggle='tooltip' title='New Device' class='text-info fa fa-circle-o-notch fa-spin'></span>";
                                          echo "
                                          <span id='".$deviceId."".$switchId."'><strong class='text-info'>".$i.".</strong>&nbsp; &nbsp; 
                                          <big><strong>Name:</strong> <span class='text-danger'>$deviceName</span></big>
                                          <big><strong>Device:</strong> <span class='text-muted'>$deviceId";
                                          if($switchId!=0)
                                            echo"/<big class='text-danger'data-toggle='tooltip' title='Switch $switchId' >$switchId</big>";
                                          echo"</span></big> &nbsp;
                                          <big><strong>Type:</strong> <span class='text-danger'>$type</span></big>";
                                          
                                          echo"
                                          <big><strong>Group:</strong> <span class='text-danger'>$name</span></big>
                                           &nbsp; &nbsp;<a class='text-muted glyphicon glyphicon-pencil' data-toggle='tooltip' title='Edit Switch' href="."javascript:edit('$deviceId','$switchId')"."></a>
                                           &nbsp; &nbsp;";
                                           if($switchId!=0){//delete switches
                                             echo"
                                             <a class='text-danger glyphicon glyphicon-remove-circle' data-toggle='tooltip' title='Delete' href="."javascript:ddel('$deviceId','$switchId')"."></a>";
                                           }
                                           echo"
                                           &nbsp; &nbsp;<big><a class='text-danger glyphicon glyphicon-trash' data-toggle='tooltip' title='Remove device and its switches' href="."javascript:ddel('$deviceId',0)"."></a></big>";
                                          echo"
                                          &nbsp; &nbsp;<a class='text-danger glyphicon glyphicon-wrench' data-toggle='tooltip' title='Device Settings' href="."javascript:deviceSetting('$deviceId')"."></a>
                                           </span>&nbsp;<strong><big>$status</big></strong><br/><hr/></span>";
                                          $i++;
                                          
                                          
                                      }
                                  }
                                  else
                                  {
                                      echo "</br><div class='notice'><b>No devices added yet.</b></div>";
                                  }
                                }

                             ?>  
                            </div><!-- items ends -->
                          </div><!-- panel body -->
                    </div><!-- panel ends here -->
                    </div><!--column ends for the device list-->
                    <!-- Modal fullscreen -->
                    
                    <div class="modal modal-fullscreen fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title text text-danger text-center" id="myModalLabel">For Device <span id="devId"></span></h4>
                              </div>
                              <ul class="nav nav-tabs" role="tablist">
                                    <li id="devInfo" name='Device Info' role="presentation">
                                    <a class="text text-danger"  href="javascript:showTab(1)">Device Info</a></li>
                                    <li id="editInfo" name="Edit Info" role="presentation">
                                    <a class="text text-danger"  href="javascript:showTab(2)">Edit Info</a></li>
                                </ul>
                              <div class="modal-body" id="dumps">
                                 
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        
                    </div>
                </div><!--2nd inner row ends here-->
                </div><!-- outer.row -->
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
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuTDUpbZFeBQCPnoyH5HH1BlwcTHTqcQc"async defer></script>
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <script type='text/javascript'> 
              var map;
              function togglemap() {
                $("#map").toggle(500);
              }
              function showMenu(){
                    //$("#chart").fadeOut(100);
                    $("#back").fadeOut(100);
                    $('#demo').attr('id','page-wrapper');
                    //$("#dev").fadeIn(500);
                    $("#fullscreen").fadeIn(500);
                    $(".navigationIOT").fadeIn(500);

                  }
            function showDevice(){
                    $(".navigationIOT").fadeOut(100);
                   // $('#wrapper').addClass('col-md-12');
                    $('#page-wrapper').attr('id','demo');
                    //$('#page-wrapper').addClass('col-md-12');
                    $("#fullscreen").fadeOut(100);
                    //$("#dev").fadeOut(100); 
                    //$("#chart").fadeIn(500);
                    $("#back").fadeIn(500);
                  }
            $(function(){
                showDevice();
            });
    </script>
    <script type='text/javascript'>
        /*
         *
         * Function Name: addsen()
         * Input: -
         * Output: adds sensor in sensors table
         * Logic: It is a AJAX call
         * Example Call: addsen()
         *
         */
        function addsen()
        {
        var sensor = document.getElementById("sensor").value;
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
              document.getElementById("sensors").innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
            document.getElementById("sensors").innerHTML=xmlhttp.responseText;
            }
          }
        if(sensor=='')
          alert('Input field is blank');
        else{
          xmlhttp.open('GET','managedev.php?sensor='+sensor,true);
          //alert("Hello! I am an alert box!!");
          xmlhttp.send();
        }
        }
        </script>
        <script type='text/javascript'>
        /*
         *
         * Function Name: addgrp()
         * Input: -
         * Output: adds group in groups table
         * Logic: It is a AJAX call
         * Example Call: addgrp()
         *
         */
        function addgrp()
        {

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
              document.getElementById("groups").innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
            document.getElementById("groups").innerHTML=xmlhttp.responseText;
            }
          }
        var group = document.getElementById("group").value;
        if(group=='')
          alert('Input field is blank');
        else{
          xmlhttp.open('GET','managedev.php?q='+group,true);
          //alert("Hello! I am an alert box!!");
          xmlhttp.send();
        }
        }
        </script>
        <script type='text/javascript'>
        /*
         *
         * Function Name: editName(Id, type)
         * Input: -groupId, for editing the name, type is for identifying the sensor or group to be updated
         * Output: updates group table, changes the group name
         * Logic: It is a AJAX call
         * Example Call: editName(1, 1)
         *
         */
        function editName(Id, type)
        {
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
                if(type==1)
                  document.getElementById("grp"+Id).innerHTML="<span><img src='images/ajax.gif'/></span>";
                if(type==0)
                  document.getElementById("sens"+Id).innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
              if(type==1)
                document.getElementById("grp"+Id).innerHTML=xmlhttp.responseText;
              if(type==0)
                document.getElementById("sens"+Id).innerHTML=xmlhttp.responseText;
            }
          }
        if(type==1)
          xmlhttp.open('GET','managedev.php?editGrp='+Id,true);
        if(type==0)
          xmlhttp.open('GET','managedev.php?editSensor='+Id,true);
        //alert(macid);
        xmlhttp.send();
        }
        </script>
        <script type='text/javascript'>
        /*
         *
         * Function Name: edit(deviceId, switchId)
         * Input: -deviceId, for stroing device id of esp modules and switch id for storing the switch
         * Output: updates device table with group name, device name and sensor type
         * Logic: It is a AJAX call
         * Example Call: edit(deviceId, switchId)
         *
         */
        function edit(deviceId, switchId)
        {

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
              document.getElementById(deviceId+switchId).innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
            document.getElementById(deviceId+switchId).innerHTML=xmlhttp.responseText;
            }
          }
        xmlhttp.open('GET','managedev.php?editdev='+deviceId+'&editswi='+switchId,true);
        // alert(deviceId);
        xmlhttp.send();
        }
        </script>
        <script type='text/javascript'>
        /*
         *
         * Function Name: deviceSettings(deviceId)
         * Input: deviceId, for storing device id os the concerned device
         * Output: show modal dialog box
         * Logic: It is a AJAX call
         * Example Call: deviceSettings(deviceId)
         *
         */
         var DeviceId=null;//global variable
        function deviceSetting(deviceId)
        {
          //alert(deviceId);
          DeviceId=deviceId;
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
            document.getElementById('dumps').innerHTML=xmlhttp.responseText;
            document.getElementById('myModalLabel').innerHTML=deviceId;
            
            
            }
          }
        xmlhttp.open('GET','managedev.php?deviceSetting='+deviceId,true);
        //alert(macid);
        xmlhttp.send();
        $("#modal").modal('show');
        
        }
        function showTab(tab){
          //deviceSetting(DeviceId);
          if(tab==1){
            $("#editDevice").hide(400);
            $("#deviceInfo").show(400);
          }
          if(tab==2){
            $("#deviceInfo").hide(400);
            $("#editDevice").show(400);
          }
        }
        </script>
        <script type='text/javascript'>
        /*
         *
         * Function Name: deviceSettings(deviceId)
         * Input: deviceId, for storing device id os the concerned device
         * Output: show modal dialog box
         * Logic: It is a AJAX call
         * Example Call: deviceSettings(deviceId)
         *
         */
        function saveData(deviceId)
        {
          //alert(deviceId);
        var deviceId=deviceId;
        var name = $("#devicename").val();
        var regionId = $("#regionid").val();
        var groupId = $("#groupid").val();
        var deviceInfo = $("#deviceDesc").val();
        var latitude = $("#latitude").val();
        var longitude = $("#longitude").val();
        var elevation = $("#elevation").val();
        var field1 = $("#field1").val();
        var field2 = $("#field2").val();
        var field3 = $("#field3").val();
        var field4 = $("#field4").val();
        var field5 = $("#field5").val();
        var field6 = $("#field6").val();
        var dataString='deviceData='+1+'&deviceId='+deviceId+'&name='+name+'&regionId='+regionId+'&groupId='+groupId+'&deviceInfo='+deviceInfo+'&latitude='+latitude+'&longitude='+longitude+'&elevation='+elevation+'&field1='+field1+'&field2='+field2+'&field3='+field3+'&field4='+field4+'&field5='+field5+'&field6='+field6;
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
              document.getElementById('editDevice').innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
              document.getElementById('editDevice').innerHTML=xmlhttp.responseText;
              update(0,0);
              setTimeout(
              function() 
              {
                $("#modal").modal('hide');
              }, 500);
              
            //document.getElementById('myModalLabel').innerHTML=deviceId;
            
            
            }
          }
        xmlhttp.open('POST','managedev.php',true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //alert(macid);
        xmlhttp.send(dataString);
        //$("#modal").modal('show');
        }
        </script>
        <script type='text/javascript'>
        /*
         *
         * Function Name: update(macid)
         * Input: -macid, for stroing mac id of esp modules
         * Output: updates device table with group name, device name and sensor type
         * Logic: It is a AJAX call
         * Example Call: update(12-1A-34-54-DC-AA)
         *
         */
        function update(deviceId, switchId)
        {
        //var sentyp=document.getElementById("sensoradd").value;
       try{
          var gid=document.getElementById("groupadd").value;
          var dname=document.getElementById("dname").value;
        }
        catch(e){
          console.log("Null values");
          var gid=0;
          var dname=0;
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
              document.getElementById('items').innerHTML="<span><img src='images/ajax.gif'/></span>";
              }
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
            document.getElementById('items').innerHTML=xmlhttp.responseText;
            }
          }
        if(dname=='' && (deviceId!=0 && switchId!=0))
          alert('Name field is blank');
        else if(gid==0 && (deviceId!=0 && switchId!=0))
          alert('Choose a group');
        else{
          xmlhttp.open('GET','managedev.php?updatedev='+deviceId+'&updateswi='+switchId+'&gid='+gid+'&dname='+dname,true);
          xmlhttp.send();
        }
        }
        </script>
        <script type='text/javascript'>
        /*
         *
         * Function Name: updateName(Id, type)
         * Input: -Id, and type
         * Output: updates group table / sensor table
         * Logic: It is a AJAX call
         * Example Call: updategrp(2)
         *
         */
        function updateName(Id, type)
        {
          //var sentyp=document.getElementById("sensoradd").value;
          try{
              if(type==1)
                var name=document.getElementById("gname").value;
              if(type==0)
                var name=document.getElementById("sname").value;
          }
          catch(e){
            console.log("Null values");
            var name=0;
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
                  if(type==1)
                    document.getElementById('grp'+Id).innerHTML="<span><img src='images/ajax.gif'/></span>";
                  if(type==0)
                    document.getElementById('sens'+Id).innerHTML="<span><img src='images/ajax.gif'/></span>";
                }
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
              {
                if(type==1)
                  document.getElementById('groups').innerHTML=xmlhttp.responseText;
                if(type==0)
                  document.getElementById('sensors').innerHTML=xmlhttp.responseText;
              }
            }
          if(name=='')
            alert('Name field is blank');
          else{
            if(type==1)
              xmlhttp.open('GET','managedev.php?updategrpName='+name+'&updategrpId='+Id,true);
            if(type==0)
              xmlhttp.open('GET','managedev.php?updatesensorName='+name+'&updatesensorId='+Id,true);
            //alert(macid);
            xmlhttp.send();
          }
        }
        </script>
        <script type='text/javascript'>
        /*
         *
         * Function Name: del(str)
         * Input: -str, for stroing group id
         * Output: deletes the group
         * Logic: It is a AJAX call
         * Example Call: del(12-1A-34-54-DC-AA)
         *
         */
        function del(str)
        {
          if(confirm('Confirm Delete'))
            {
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
                    document.getElementById("groups").innerHTML="deleting...";
                    }
                if (xmlhttp.readyState==4 && xmlhttp.status==200)
                  {
                  document.getElementById("groups").innerHTML=xmlhttp.responseText;
                  }
                }
              xmlhttp.open('GET','managedev.php?del='+str,true);
              //alert(macid);
              xmlhttp.send();
            }
        }
        </script>
        <script type='text/javascript'>
        /*
         *
         * Function Name: dels(str)
         * Input: -str, for stroing sensor id
         * Output: deletes the sensor
         * Logic: It is a AJAX call
         * Example Call: dels(12-1A-34-54-DC-AA)
         *
         */
        function dels(str)
        {
          if(confirm('Confirm Delete'))
            {
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
                  document.getElementById("sensors").innerHTML="deleting...";
                  }
              if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {
                document.getElementById("sensors").innerHTML=xmlhttp.responseText;
                }
              }
            xmlhttp.open('GET','managedev.php?dels='+str,true);
            //alert(macid);
            xmlhttp.send();
            }
            else{}
          }
      </script>
      <script type='text/javascript'>
      function ddel(deviceId, switchId)
      {
        if(switchId!=0 && confirm('Switch '+switchId+' will be Deleted!'))
        {
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
                document.getElementById("items").innerHTML="deleting...";
                
                }
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
              {
              document.getElementById("items").innerHTML=xmlhttp.responseText;
              }
            }
          xmlhttp.open('GET','managedev.php?ddeldev='+deviceId+'&ddelswi='+switchId,true);
          //alert(macid);
          xmlhttp.send();
        }
        if(switchId==0 && confirm('Device '+deviceId+' and its switches will be Deleted!'))
        {
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
                document.getElementById("items").innerHTML="deleting...";
                
                }
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
              {
              document.getElementById("items").innerHTML=xmlhttp.responseText;
              }
            }
          xmlhttp.open('GET','managedev.php?ddeldev='+deviceId+'&ddelswi='+switchId,true);
          //alert(macid);
          xmlhttp.send();
        }
        }
    </script>

</body>

</html>
