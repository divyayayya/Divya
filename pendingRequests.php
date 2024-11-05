<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Requests</title>
    <style>
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

        /* Button Styling */

        button {
            width: 100%;
            padding: 12px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
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

        /* Adjust padding and font size for smaller screens */
        @media (max-width: 768px) {
            th, td {
                padding: 10px;
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            th, td {
                padding: 8px;
                font-size: 12px;
            }
        }
        @media screen and (max-width: 480px) {
            th, td {
                font-size: 4vw;
            }

            .navbar img {
                height: 5vh;
            }
        }

    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <a href="home.php"><img src="images/logo.jpg" alt="Company Logo"></a> <!-- Link to homepage -->
        <div class="backbutton">
            <a href="home.php">Back</a>
        </div>

    </div>

    <?php
        $userID = $_SESSION['userID'];

        $eDao = new EmployeeDAO;
        $rDao = new RequestDAO;
        $underling_IDs = $eDao->retrieveUnderlingsID($userID);
        
        $pendingRequestsArray = [];
        foreach ($underling_IDs as $staffID){
            $staffRequestArray = $rDao->retrievePendingArrangements($staffID);
            foreach ($staffRequestArray as $pendingRequest){
                $pendingRequestsArray[] = $pendingRequest;
            }
        }
        
        if (count($pendingRequestsArray) == 0){
            echo "NO PENDING REQUESTS~";
        } else {
            echo "<table border='1'>";
            echo "<tr><th>Staff ID</th><th>Staff Name</th><th>Arrangement Date</th><th>Arrangement Type</th><th>Time Block</th><th>Reasons</th><th>Actions</th></tr>";
        
            foreach ($pendingRequestsArray as $request) {
                $requestID = $request['Request_ID'];
                $staffID = $request['Staff_ID'];
                $staffName = $eDao->getStaffName($staffID);
                $arrangementDate = $request['Arrangement_Date'];
                $arrangementType = $request['Working_Arrangement'];
                $timeBlock = $request['Arrangement_Time'];
                $reasons = $request['Reason'];
        
                echo "<tr>";
                echo "<td>{$staffID}</td>";
                echo "<td>{$staffName}</td>";
                echo "<td>{$arrangementDate}</td>";
                echo "<td>{$arrangementType}</td>";
                echo "<td>{$timeBlock}</td>";
                echo "<td>{$reasons}</td>";
        
                // Create buttons for 'Approve' and 'Reject' actions
                echo "<td>
                        <form method='POST' action='approve_request.php' style='display:inline;'>
                            <input type='hidden' name='requestID' value='{$requestID}'>
                            <button type='submit' name='action' value='approve'>Approve</button>
                        </form>
                        <form method='POST' action='reject_form.php' style='display:inline;'>
                            <input type='hidden' name='requestID' value='{$requestID}'>
                            <button type='submit' name='action' value='reject'>Reject</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        
            echo "</table>";
        }


    ?>

</body>
</html>
