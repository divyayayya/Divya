<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colleagues Location</title>
</head>
<body>

<?php

    #Display User Details
    $userID = $_SESSION['userID'];
    $userRole = $_SESSION['userRole'];

    $dao = new EmployeeDAO;
    $result = $dao->retrieveEmployeeInfo($userID);
    $employee = new Employee($result['Staff_ID'], $result['Staff_FName'], $result['Staff_LName'], $result['Dept'], $result['Position'], $result['Country'], $result['Email'], $result['Reporting_Manager'], $result['Role']);
    $dao_req = new RequestDAO;
    $requests = $dao_req -> retrieverequestInfo($userID);
    // $arrangement = new Request($arrangements['Staff_ID'],$arrangements['Arrangement_Date'], $arrangements['Working_Arrangement'], $arrangements);

    echo "<h1 style='display: inline-block; margin-right: 20px;'>User Details</h1><a href='login.php' style='display: inline-block; vertical-align: middle;'>Sign Out</a>";

    echo "<table border=1>";
    echo "<tr><th>ID</th><th>Name</th><th>Department</th><th>Position</th><th>Country</th><th>Email</th></tr>";
    echo "<tr><td>{$employee->getID()}</td><td>{$employee->getStaffName()}</td><td>{$employee->getDept()}</td><td>{$employee->getPosition()}</td><td>{$employee->getCountry()}</td><td>{$employee->getEmail()}</td></tr></table>";



    echo "</br>";
    $deptDetails = '';
    $deptRequests = '';
    $userDept = $employee->getDept(); 
    $employeesInDept = $userDept -> retrieveEmployeesInSameDept();

    echo "Department: $userDept";
    echo "<br>";
    echo "Employees List";
    
    if ($userRole != 2){
        if ($userDept == "HR" || $userDept == "CEO"){ 
            $deptDetails = "<a href='deptDetails_HR.php'>Department Details</a>";
            $deptRequests = "<a href='pendingRequests.php'>Pending Requests</a>";
        } else { 
            $deptDetails = "<a href='deptDetails.php'>Department Details</a>";
            $deptRequests = "<a href='pendingRequests.php'>Pending Requests</a>";
        }
        
    }
    
    
    $requests_json = json_encode($requests);
?>
</body>
