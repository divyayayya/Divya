<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw WFH</title>
    <style>
        /* Navbar Styling */
        .navbar {
            background-color: #000;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            height: 80px;
            border-bottom: 1px solid #444;
        }

        .navbar a img {
            height: 60px;
        }

        button { 
            padding: 12px; 
            background-color: #000; 
            color: #fff; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        }

        button:hover { 
            background-color: #333; 
        }

        .backbutton a { 
            font-size: 16px;
            border: none;
            outline: none;
            color: white;
            padding: 14px 20px;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
            text-decoration: none;
        }

        .backbutton a:hover {
            background-color: #666; /* Change background on hover */
            color: #fff; /* Change text color on hover */
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
            font-size: 16px;
        }

        th {
            background-color: #f1f1f1;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Adjust padding and font size for smaller screens */
        @media (max-width: 768px) {
            th, td, form {
                padding: 10px;
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            th, td, form {
                padding: 8px;
                font-size: 12px;
            }
        }
        @media screen and (max-width: 480px) {
            th, td, form {
                font-size: 4vw;
            }

            .navbar img {
                height: 5vh;
            }
        }
    </style>
    <script>
      
        function confirmDelete(requestId, staffId, arrangementDate) {
            var message = "Are you sure you want to withdraw the request with:\n" +
                        "Request ID: " + requestId + "\n" +
                        "Staff ID: " + staffId + "\n" +
                        "Arrangement Date: " + arrangementDate + "?";
            
            if (confirm(message)) {
                // If confirmed, redirect to delete script with all parameters
                window.location.href = "withdraw_request.php?request_id=" + requestId +
                                    "&staff_id=" + staffId +
                                    "&arrangement_date=" + arrangementDate;
            }
        }
    
    </script>
</head>

<body>

    <!-- Navbar -->
    <div class="navbar">
            <a href="home.php"><img src="images/logo.jpg" alt="Company Logo"></a> <!-- Link to homepage -->
            <div class="backbutton">
                <a href="home.php">Back</a>
            </div>
    </div>
    <h1>Withdraw Approved</h1>
    <?php
        $userID = $_SESSION['userID'];
        $userRole = $_SESSION['userRole'];
        $eDao = new EmployeeDAO;
        $result = $eDao->retrieveEmployeeInfo($userID);   
        $employee = new Employee($result['Staff_ID'], $result['Staff_FName'], $result['Staff_LName'], $result['Dept'], $result['Position'], $result['Country'], $result['Email'], $result['Reporting_Manager'], $result['Role']);
        $userDept = $employee->getDept();
        $eDao_req = new RequestDAO;
        $eDao_req->rejectExpiredRequests();
        $requests = $eDao_req->retrieverequestInfo($userID);

        #Display User Details
        echo "<table border=1>";
        echo "<tr><th>ID</th><th>Name</th><th>Department</th><th>Position</th><th>Country</th><th>Email</th></tr>";
        echo "<tr><td>{$employee->getID()}</td><td>{$employee->getStaffName()}</td><td>{$employee->getDept()}</td><td>{$employee->getPosition()}</td><td>{$employee->getCountry()}</td><td>{$employee->getEmail()}</td></tr></table>";

        #Requests part
        echo "<h2>Underlings Requests</h2>";
        $rDao = new RequestDAO;
        $underlingIDs = $eDao->retrieveUnderlingsID($userID);

        echo "<table border=1>";
        echo "<tr><th>Staff Name</th><th>Request ID</th><th>Date</th><th>Arrangement</th><th>Arrangement Time</th><th>Reason</th><th>Withdraw</th></tr>";  
        foreach($underlingIDs as $id){
            $staffName = $eDao->getStaffName($id);
            $requests = $rDao->retrieveApprovedRequestsByUserID($id);
            if (count($requests) > 0) {
  
                foreach ($requests as $request) {
                    echo "<tr><td>{$staffName}</td><td>{$request['Request_ID']}</td><td>{$request['Arrangement_Date']}</td><td>{$request['Working_Arrangement']}</td><td>{$request['Arrangement_Time']}</td><td>{$request['Reason']}</td>";
                    echo "<td>
                            <button onclick='confirmDelete({$request['Request_ID']}, {$request['Staff_ID']}, \"{$request['Arrangement_Date']}\")'>Withdraw</button>
                        </td>
                        </tr>";
                    
                }
            }
        }
        
        echo "</table>"


    ?>
</body>
</html>

