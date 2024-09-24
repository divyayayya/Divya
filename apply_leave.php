<?php
    require_once "model/common.php";
    // Check if the user is logged in
    if (!isset($_SESSION['userID'])) {
        header("Location: login.php");
        exit();
    }

    // Fetch user details
    $userID = $_SESSION['userID'];
    $userRole = $_SESSION['userRole'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Leave</title>
</head>
<body>

    <h1 style='display: inline-block; margin-right: 20px;'>Apply for Work-from-Home Days</h1><a href='my_requests.php'>Back</a>

    <table border="1">
    <h3>Number of available leaves</h3>
    <tr>
        <th><bold>Category</bold></th>
        <th><bold>Number of Days Left</bold></th>
    </tr>
    <tr>
        <td>Annual</td>
        <td>20</td>
    </tr>
    <tr>
        <td>Sick</td>
        <td>20</td>
    </tr>
    <tr>
        <td>Hospital</td>
        <td>20</td>
    </tr>
    <tr>
        <td>Childcare</td>
        <td>20</td>
    </tr>
    <tr>
        <td>Maternity/Paternity</td>
        <td>20</td>
    </tr>
    <tr>
        <td>Other (please include reason)</td>
        <td>20</td>
    </tr>
    </table>

    <br>

    <form action="process_leave_request.php" method="POST">
        <label for="leave_date">Select Date(s):</label><br>
        <input type="date" name="leave_date" required><br><br>
        
        <label for="date">Select Time:</label><br>
        <select name="time" id="time" required>
            <option value='AM'>AM</option>
            <option value='PM'>PM</option>
            <option value='full_day'>Full Day</option>
        </select>
            
        <br><br>

        <label for="reason">Reason for WFH:</label><br>
        <textarea name="reason" required></textarea><br><br>
        
        <button type="submit">Submit Request</button>
    </form>

<?php
    $msg = '';
    if (isset($_POST['submit'])){
        $wfhDate = $_POST['wfh_date'];
        $reason = $_POST['reason'];
        $status = "Pending";

        $dao = new RequestDAO;


    }

?>

</body>
</html>
