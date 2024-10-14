<?php
require_once "model/common.php";

$connManager = new ConnectionManager();
$conn = $connManager->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start(); // Ensure session is started
    $userID = $_SESSION['userID'];  // Get user ID from session

    // Retrieve user department
    $dao = new EmployeeDAO;
    $result = $dao->retrieveEmployeeInfo($userID);   
    $employee = new Employee($result['Staff_ID'], $result['Staff_FName'], $result['Staff_LName'], $result['Dept'], $result['Position'], $result['Country'], $result['Email'], $result['Reporting_Manager'], $result['Role']);
    $dept = $employee->getDept();

    // Get request type (single_day or recurring)
    $request_type = $_POST['request_type'];
    $reason = $_POST['reason'];
    $status = "Pending";  // Default status

    // Create a new DAO to handle leave requests
    $dao = new RequestDAO();
    
    // Generate new Request ID
    $requestID = $dao->generateReqID(); 

    // Handle single day WFH request
    if ($request_type === 'single_day') {
        $leave_date = $_POST['single_start_date'];
        $time = $_POST['time'];  // Get selected time (AM, PM, full_day)

        // Submit the single-day WFH request
        $result = $dao->submitWFHRequest($userID, $requestID, $dept, $leave_date, $time, $reason, $status);

    } elseif ($request_type === 'recurring') {  // Handle recurring WFH request
        $recurring_start_date = $_POST['recurring_start_date'];
        $end_date = $_POST['end_date'];
        $days_of_week = $_POST['days_of_week'];

        // Loop through selected days of the week and submit WFH request for each day
        foreach ($days_of_week as $day) {
            // For each day, create and submit a request for each week between the start and end date
            $date = $recurring_start_date;
            while (strtotime($date) <= strtotime($end_date)) {
                if (date('l', strtotime($date)) == $day) {
                    $result = $dao->submitWFHRequest($userID, $requestID, $dept, $date, 'full_day', $reason, $status);
                    if (!$result) {
                        // Show error message if any request fails
                        echo "Error submitting request for $date.";
                        exit();
                    }
                }
                $date = date('Y-m-d', strtotime($date . ' +1 day'));
            }
        }
    }

    // If all requests are successful, redirect to a success page
    if ($result) {
        header("Location: my_requests.php?message=Request submitted successfully.");
    } else {
        // Show error message if the final result failed
        echo "Error submitting request.";
    }
} else {
    header("Location: apply_wfh.php");
    exit();
}
?>
