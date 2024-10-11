<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Requests</title>
</head>
<body>
    <h1 style='display: inline-block; margin-right: 20px;'>Requests Pending Approval</h1><a href='home.php'>Back</a></br>

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
            echo "<tr><th>Staff ID</th><th>Staff Name</th><th>Arrangement Date</th><th>Arrangement Type</th><th>Reasons</th><th>Actions</th></tr>";
        
            foreach ($pendingRequestsArray as $request) {
                $requestID = $request['Request_ID'];
                $staffID = $request['Staff_ID'];
                $staffName = $eDao->getStaffName($staffID);
                $arrangementDate = $request['Arrangement_Date'];
                $arrangementType = $request['Working_Arrangement'];
                $reasons = $request['Reason'];
        
                echo "<tr>";
                echo "<td>{$staffID}</td>";
                echo "<td>{$staffName}</td>";
                echo "<td>{$arrangementDate}</td>";
                echo "<td>{$arrangementType}</td>";
                echo "<td>{$reasons}</td>";
        
                // Create buttons for 'Approve' and 'Reject' actions
                echo "<td>
                        <form method='POST' action='handle_request.php' style='display:inline;'>
                            <input type='hidden' name='requestID' value='{$requestID}'>
                            <button type='submit' name='action' value='approve'>Approve</button>
                        </form>
                        <form method='POST' action='handle_request.php' style='display:inline;'>
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