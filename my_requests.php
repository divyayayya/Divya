<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- calendar css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.0/main.min.css" />
    <title>My Requests</title>
</head>
<body>

<?php

    #Display User Details
    $userID = $_SESSION['userID'];
    $userRole = $_SESSION['userRole'];

    $dao = new RequestDAO;
    $requests = $dao->retrieveRequestInfo($userID);


    echo "<h2 style='display: inline-block; margin-right: 20px;'>My Requests</h2></br>";
    echo "<h2 style='display: inline-block; margin-right: 20px;'>Request History</h2><a href='apply_wfh.php' style='display: inline-block; vertical-align: middle;'>New Request</a>";


    if(count($requests) > 1){
        echo "<table border=1>";
        echo "<tr><th>ID</th><th>Request ID</th><th>Date</th><th>Arrangement</th><th>Status</th></tr>";    
        foreach($requests as $request){
            echo "<tr><td>{$request['Staff_ID']}</td><td>{$request['Request_ID']}</td><td>{$request['Arrangement_Date']}</td><td>{$request['Working_Arrangement']}</td><td>{$request['Request_Status']}</td></tr>";
        }
    }
    else{
        echo '<p style="color: red;">No Requests Found</p>';
    }

?>
