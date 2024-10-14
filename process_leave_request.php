<?php
require_once "model/common.php";

$connManager = new ConnectionManager();
$conn = $connManager->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // session_start(); // Ensure session is started
    $userID = $_SESSION['userID'];  // Get user ID from session

    // Retrieve user department
    $dao = new EmployeeDAO;
    $result = $dao->retrieveEmployeeInfo($userID);   
    $employee = new Employee($result['Staff_ID'], $result['Staff_FName'], $result['Staff_LName'], $result['Dept'], $result['Position'], $result['Country'], $result['Email'], $result['Reporting_Manager'], $result['Role']);
    $dept = $employee->getDept();

    // POST necessary items
    $leave_date = $_POST['leave_date'];
    $time = $_POST['time'];  // Get selected time
    $reason = $_POST['reason'];
    $status = "Pending";  // Default status

    // Create a new DAO to handle leave requests
    $dao = new RequestDAO();
    
    // Generate new Request ID
    $requestID = $dao->generateReqID(); 

    // Submit the leave request
    $result = $dao->submitLeaveRequest($userID, $requestID, $dept, $leave_date, $time, $reason);

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