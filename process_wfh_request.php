<?php
session_start(); // Ensure session is started

include '/Applications/MAMP/htdocs/GitHub/Divya/model/ConnectionManager.php'; // Include your connection manager

$connManager = new ConnectionManager();
$conn = $connManager->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging output for incoming POST data
    var_dump($_POST);

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
        // Insert single request
        $sql = "INSERT INTO employee_arrangement (Staff_ID, Arrangement_Date, Working_Arrangement, Reason, Request_Status, Working_Location) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        // Execute the statement
        $executed = $stmt->execute([$userID, $startDate, 'WFH', $reason, 'Pending', 'Home']);

        // Check for errors
        if (!$executed) {
            print_r($stmt->errorInfo());
        } else {
            echo "Request submitted successfully!";
        }
        
    } elseif ($requestType === 'recurring') {
        $endDate = $_POST['end_date'];
        $frequency = $_POST['frequency'];

        // Convert dates
        $date = new DateTime($startDate);
        $end = new DateTime($endDate);

        try {
            $conn->beginTransaction(); // Start transaction

            while ($date <= $end) {
                $sql = "INSERT INTO employee_arrangement (Staff_ID, Arrangement_Date, Working_Arrangement, Reason, Request_Status, Working_Location, Recurring_Days, Start_Date, End_Date) VALUES (?, NULL, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                
                // Execute statement
                $executed = $stmt->execute([$userID, 'WFH', $reason, 'Pending', 'Home', $_POST['recurring_days'], $startDate, $endDate]);

                // Check for errors
                if (!$executed) {
                    print_r($stmt->errorInfo());
                }

                // Update date based on frequency
                if ($frequency === 'daily') {
                    $date->modify('+1 day');
                } elseif ($frequency === 'weekly') {
                    $date->modify('+1 week');
                } elseif ($frequency === 'monthly') {
                    $date->modify('+1 month');
                }
            }

            $conn->commit(); // Commit transaction
            echo "Recurring request submitted successfully!";
        } catch (Exception $e) {
            $conn->rollBack(); // Rollback on error
            echo "Failed: " . $e->getMessage();
        }
    }
}
?>
