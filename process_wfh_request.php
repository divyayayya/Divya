<?php
session_start(); // Ensure session is started

include '/Applications/MAMP/htdocs/GitHub/Divya/model/ConnectionManager.php'; // Include your connection manager

// Enable error reporting to see any issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

$connManager = new ConnectionManager();
$conn = $connManager->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user ID is set
    if (!isset($_SESSION['userID'])) {
        echo "Error: Employee ID is not set in session.";
        exit();
    }

    $userID = $_SESSION['userID'];
    $reason = $_POST['reason'];
    $startDate = $_POST['start_date'];
    $requestType = $_POST['request_type'];
    
    // Check if the request type is single or recurring
    if ($requestType === 'single') {
        // Debugging information
        echo "Processing single request...<br>";
        echo "User ID: $userID<br>";
        echo "Start Date: $startDate<br>";
        echo "Reason: $reason<br>";

        // Insert single request
        $sql = "INSERT INTO employee_arrangement (Staff_ID, Arrangement_Date, Working_Arrangement, Reason, Request_Status, Working_Location) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        // Execute the statement
        $executed = $stmt->execute([$userID, $startDate, 'WFH', $reason, 'Pending', 'Home']);

        // Check for errors
        if (!$executed) {
            echo "Error during execution: " . implode(", ", $stmt->errorInfo());
            exit(); // Stop further execution if there's an error
        } else {
            // Redirect to my_requests.php after successful submission
            header("Location: my_requests.php");
            exit();
        }
        
    } elseif ($requestType === 'recurring') {
        $endDate = $_POST['end_date'];
        $daysOfWeek = $_POST['days_of_week']; // Get selected days of the week

        // Convert dates
        $startDateObj = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        if (count($daysOfWeek) > 2) {
            echo "Error: You can only select a maximum of 2 days per week.";
            exit();
        }

        try {
            $conn->beginTransaction(); // Start transaction

            while ($startDateObj <= $endDateObj) {
                $day_of_week = $startDateObj->format('l'); // Get the day of the week

                // Check if the current day is one of the selected days of the week
                if (in_array($day_of_week, $daysOfWeek)) {
                    $sql = "INSERT INTO employee_arrangement (Staff_ID, Arrangement_Date, Working_Arrangement, Reason, Request_Status, Working_Location) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    
                    // Execute statement
                    $executed = $stmt->execute([$userID, $startDateObj->format('Y-m-d'), 'WFH', $reason, 'Pending', 'Home']);

                    // Check for errors
                    if (!$executed) {
                        echo "Error during execution (recurring): " . implode(", ", $stmt->errorInfo());
                        exit(); // Stop further execution if there's an error
                    }
                }

                // Move to the next day
                $startDateObj->modify('+1 day');
            }

            $conn->commit(); // Commit transaction
            // Redirect to my_requests.php after successful submission
            header("Location: my_requests.php");
            exit();
        } catch (Exception $e) {
            $conn->rollBack(); // Rollback on error
            echo "Failed: " . $e->getMessage();
        }
    }
}
?>
