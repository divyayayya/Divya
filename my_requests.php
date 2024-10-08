<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.0/main.min.css" />
    <title>My Requests</title>
    <style>
        .dropdown {
            display: inline-block;
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            display: block;  /* Each link on a new line */
            padding: 8px 16px;
            text-decoration: none;  /* No underline */
            color: black;  /* Link color */
        }

        .dropdown-content a:hover {
            background-color: #555; /* Change this to dark grey */
            color: white; /* Change text color to white for better contrast */
        }

        .hover-change {
            color: black; /* Default color */
            transition: color 0.3s; /* Smooth transition */
        }

        .hover-change:hover {
            color: grey; /* Change color on hover */
        }
    </style>

    <script>
      
        function confirmDelete(requestId, staffId, arrangementDate) {
            var message = "Are you sure you want to delete the request with:\n" +
                        "Request ID: " + requestId + "\n" +
                        "Staff ID: " + staffId + "\n" +
                        "Arrangement Date: " + arrangementDate + "?";
            
            if (confirm(message)) {
                // If confirmed, redirect to delete script with all parameters
                window.location.href = "delete_request.php?request_id=" + requestId +
                                    "&staff_id=" + staffId +
                                    "&arrangement_date=" + arrangementDate;
            }
        }
    
    </script>
</head>
<body>

<?php
    # Display User Details
    $userID = $_SESSION['userID'];
    $userRole = $_SESSION['userRole'];

    $dao = new RequestDAO;
    $requests = $dao->retrieveRequestInfo($userID);

    echo "<h1 style='display: inline-block; margin-right: 20px;'>My Requests</h1><a href='home.php'>Back</a></br>";
    
    // New Requests Dropdown
    echo "<div class='dropdown'>";
    echo "<h2 class='hover-change' style='display: inline-block; margin-right: 20px;'>New Requests</h2>";
    echo "<div class='dropdown-content'>";
    echo "<a href='apply_wfh.php'>Apply for Work-From-Home</a>";
    echo "<a href='apply_leave.php'>Apply for Leave</a>";
    echo "<a href='delete_wfh.php'>Delete Request</a>";
    echo "<a href='update_wfh.php'>Update Request</a>";
    echo "</div></div>";

    if (count($requests) > 0) {
        echo "<table border=1>";
        echo "<tr><th>ID</th><th>Request ID</th><th>Date</th><th>Arrangement</th><th>Reason</th><th>Status</th><th>Delete</th></tr>";    
        foreach ($requests as $request) {
            echo "<tr><td>{$request['Staff_ID']}</td><td>{$request['Request_ID']}</td><td>{$request['Arrangement_Date']}</td><td>{$request['Working_Arrangement']}</td><td>{$request['Reason']}</td><td>{$request['Request_Status']}</td>";
            echo "<td>
                    <button onclick='confirmDelete({$request['Request_ID']})'>Delete</button>
                </td>
                </tr>";
            
        }
        echo "</table>";
    } else {
        echo '<p style="color: red;">No Requests Found</p>';
    }
?>

</body>
</html>
