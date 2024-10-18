<?php
require_once "model/common.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['request_id'], $_GET['staff_id'], $_GET['arrangement_date'])) {
    $requestId = $_GET['request_id'];
    $staffId = $_GET['staff_id'];
    $arrangementDate = $_GET['arrangement_date'];

    $dao = new RequestDAO();

    // Call the deleteRequest method with all three parameters
    if ($dao->withdrawRequest($requestId, $staffId, $arrangementDate)) {
        echo "Request deleted successfully.";
        // Optionally redirect back to the requests page
        header("Location: withdraw_wfh.php"); // Update this to your actual requests page
        exit();
    } else {
        echo "Error deleting request. Please try again.";
    }
} else {
    echo "No request ID, staff ID, or arrangement date provided or invalid request method.";
}
?>

