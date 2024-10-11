<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reject Request</title>
</head>
<body>
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
        echo "<h2>Request Details for Request ID: {$requestID}</h2>";
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