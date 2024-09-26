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
    <title>Apply for Work-From-Home</title>
</head>
<body>

    <h1 style='display: inline-block; margin-right: 20px;'>Apply for Work-from-Home Days</h1><a href='my_requests.php'>Back</a>

    <br>

    <form action="process_wfh_request.php" method="POST">
        <input type="hidden" name="userID" value="<?php echo $userID; ?>">
        <label for="date">Select Date(s):</label><br>
        <input type="date" name="wfh_date" required><br><br>

        <label for="arrangement_type"></label><br>
        <input type="hidden" name="arrangement_type" id="arrangement_type" value="WFH" required></input>
        
        <label for="wfh_time">Select Time:</label><br>
        <select name="wfh_time" id="wfh_time" required>
            <option value="AM">AM</option>
            <option value="PM">PM</option>
            <option value="full_day">Full Day</option>
        </select><br><br>

        <label for="reason">Reason for WFH:</label><br>
        <textarea name="reason" required></textarea><br><br>
        
        <button type="submit">Submit Request</button>
    </form>

<<<<<<< HEAD
<?php
    if (isset($_POST['submit'])){
        $wfhDate = $_POST['wfh_date'];
        $reason = $_POST['reason'];
        $status = "Pending";
=======
>>>>>>> ffa5ae69b954b972c787a34d1036d0a00720958c


</body>
</html>
