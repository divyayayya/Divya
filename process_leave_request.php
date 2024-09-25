<?php
require_once "model/common.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_SESSION['userID'];  // Get user ID from session
    $leave_date = $_POST['leave_date'];
    $time = $_POST['time'];  // Get selected time
    $reason = $_POST['reason'];
    $status = "Pending";  // Default status

    // Create a new DAO to handle leave requests
    $dao = new RequestDAO();
    $result = $dao->submitLeaveRequest($userID, $leave_date, $time, $reason, $status);

    if ($result) {
        // Redirect to success page or show success message
        header("Location: home.php?message=Request submitted successfully.");
    } else {
        // Show error message
        echo "Error submitting request.";
    }
} else {
    header("Location: apply_leave.php");
    exit();
}
?>
