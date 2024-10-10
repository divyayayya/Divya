<?php
    require_once "model/common.php";
    
    // Check if the user is logged in
    if (!isset($_SESSION['userID'])) {
        header("Location: login.php");
        exit();
    }

    // Fetch user details
    $userID = $_SESSION['userID'];

    // Fetch available leave days from the database (example logic)
    $dao = new RequestDAO();  // Assuming you have a DAO that retrieves leave data
    // $leaveDays = $dao->getAvailableLeaveDays($userID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Leave</title>
</head>
<body>

    <h1 style='display: inline-block; margin-right: 20px;'>Apply for Leave</h1><a href='my_requests.php'>Back</a>

    <!-- <h3>Number of Available Leaves</h3>
    <table border="1">
        <tr>
            <th>Category</th>
            <th>Number of Days Left</th>
        </tr>
        <?php foreach ($leaveDays as $category => $daysLeft): ?>
            <tr>
                <td><?php echo htmlspecialchars($category); ?></td>
                <td><?php echo htmlspecialchars($daysLeft); ?></td>
            </tr>
        <?php endforeach; ?>
    </table> -->

    <br>

    <form action="process_leave_request.php" method="POST">
        <input type="hidden" name="userID" value="<?php echo $userID; ?>">
        
        <label for="leave_date">Select Date(s):</label><br>
        <input type="date" name="leave_date" required><br><br>
        
        <label for="time">Select Time:</label><br>
        <select name="time" id="time" required>
            <option value='AM'>AM</option>
            <option value='PM'>PM</option>
            <option value='full_day'>Full Day</option>
        </select><br><br>

        <label for="reason">Reason for leave:</label><br>
        <textarea name="reason" required></textarea><br><br>
        
        <button type="submit">Submit Request</button>
    </form>
</body>
</html>
