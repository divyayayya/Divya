<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage</title>
</head>
<body>

<?php

    #Display User Details
    $userID = $_SESSION['userID'];
    $userRole = $_SESSION['userRole'];

    $dao = new EmployeeDAO;
    $result = $dao->retrieveEmployeeInfo($userID);
    $employee = new Employee($result['Staff_ID'], $result['Staff_FName'], $result['Staff_LName'], $result['Dept'], $result['Position'], $result['Country'], $result['Email'], $result['Reporting_Manager'], $result['Role']);
    
    echo "<h2 style='display: inline-block; margin-right: 20px;'>User Details</h2><a href='login.php' style='display: inline-block; vertical-align: middle;'>Sign Out</a>";

    echo "<table border=1>";
    echo "<tr><th>ID</th><th>Name</th><th>Department</th><th>Position</th><th>Country</th><th>Email</th></tr>";
    echo "<tr><td>{$employee->getID()}</td><td>{$employee->getStaffName()}</td><td>{$employee->getDept()}</td><td>{$employee->getPosition()}</td><td>{$employee->getCountry()}</td><td>{$employee->getEmail()}</td></tr></table>";

    echo "</br>";
    $deptDetails = '';
    $deptRequests = '';

    if ($userRole != 2){
        $deptDetails = "<a href='deptDetails.php'>Department Details</a>";
        $deptRequests = "<a href='pendingRequests'>Pending Requests</a>";
    }
    echo "<table style='border-collapse: separate; border-spacing: 20px;'><tr><td><a href=''>Requests</a></td><td>{$deptDetails}</td><td>{$deptRequests}</td></tr></table>";


    echo "</br></br><h1>Calender</h1>";
    echo "@CALENDAR PPL PLS PUT IT HERE TYVM";

?>
    
</body>