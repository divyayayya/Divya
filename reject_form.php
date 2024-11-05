<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reject Request</title>
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
        .table-responsive {
        max-width: 100%;
            overflow-x: auto; /* Allows horizontal scrolling on small screens */
        }

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

        form { 
            max-width: 600px; 
            background-color: #fff; 
            border-radius: 8px; 
        }

        label, input, textarea, button { 
            display: block; 
            width: 100%; 
            margin-top: 10px; 
        }

        input[type="date"], textarea { 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
        }

        textarea { 
            resize: vertical; 
            height: 100px; 
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
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
            <a href="home.php"><img src="images/logo.jpg" alt="Company Logo"></a> <!-- Link to homepage -->
            <div class="backbutton">
                <a href="pendingRequests.php">Back</a>
            </div>
    </div>
    <?php
        $requestID = $_POST['requestID'];
        $rDao = new RequestDAO;
        $eDao = new EmployeeDAO;
        $requestDetails = $rDao->retrieveByReqID($requestID);

        $staffID = $requestDetails['Staff_ID'];
        $staffName = $eDao->getStaffName($staffID);
        $arrangementDate = $requestDetails['Arrangement_Date'];
        $workingArrangement = $requestDetails['Working_Arrangement'];
        $reason = $requestDetails['Reason'];
        $workingLocation = $requestDetails['Working_Location'];
        
        // Request Details
        echo "<h1 style='display: inline-block; margin-right: 20px;'>Request Details for Request ID: {$requestID}</h2>";
        echo "<table border='1'>
                <tr>
                    <th>Staff ID</th><th>Staff Name</th><th>Arrangement Date</th><th>Working Arrangement</th><th>Reason</th><th>Working Location</th>
                </tr>";
        echo "<tr>
                <td>$staffID</td><td>$staffName</td><td>$arrangementDate</td><td>$workingArrangement</td><td>$reason</td><td>$workingLocation</td>
            </tr>";
        echo "</table></br>";

        // Rejection Field
        echo "<form action='reject_request.php' method='post'>
            <label for='rejectionReason'>Rejection Reason:</label><br>
            <textarea id='rejectReason' name='rejectReason' rows='4' cols='50' placeholder='Enter rejection reason here...'></textarea><br><br>
            <input type='hidden' name='requestID' value='{$requestID}'>
            <input type='submit' value='Submit Rejection Reason'>
        </form>"
        

    ?>
</body>
</html>
