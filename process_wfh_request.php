<?php
require_once "model/common.php";

// Enable error reporting to see any issues during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

$connManager = new ConnectionManager();
$conn = $connManager->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user ID is set in the session
    if (!isset($_SESSION['userID'])) {
        echo "Error: Employee ID is not set in session.";
        exit();
    }

    // Get necessary POST data
    $userID = $_SESSION['userID'];
    $reason = $_POST['reason'];
    $requestType = $_POST['request_type'];  // Single or Recurring request type

    //User Data
    $dao = new EmployeeDAO;
    $result = $dao->retrieveEmployeeInfo($userID);   
    $employee = new Employee($result['Staff_ID'], $result['Staff_FName'], $result['Staff_LName'], $result['Dept'], $result['Position'], $result['Country'], $result['Email'], $result['Reporting_Manager'], $result['Role']);
    $dept = $employee -> getDept();

    // Generate a new Request_ID based on the current number of requests
    $sql = "SELECT COUNT(*) as total_requests FROM employee_arrangement";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $requestID = $result['total_requests'] + 1;  // Increment the count for the new request ID

    // Check if the request is for 'single' day WFH
    if ($requestType === 'single_day') {
        $startDate = $_POST['single_start_date'];
        $time = $_POST['time'];  // AM, PM, or Full Day

        echo "Processing single day WFH request...<br>";
        echo "User ID: $userID<br>";
        echo "Start Date: $startDate<br>";
        echo "Time: $time<br>";
        echo "Reason: $reason<br>";
        echo $requestType;
        echo $time;

        // Prepare the SQL for a single request with time selection
        $sql = "INSERT INTO employee_arrangement (Staff_ID, Department, Request_ID, Arrangement_Date, Working_Arrangement, Arrangement_Time, Reason, Request_Status, Working_Location, Rejection_Reason) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Execute the query with user data
        $executed = $stmt->execute([$userID, $dept, $requestID, $startDate, 'WFH', $time, $reason, 'Pending', 'Home/ OOF', NULL]);

        // Error handling and redirection
        if (!$executed) {
            echo "Error during execution: " . implode(", ", $stmt->errorInfo());
            exit(); // Stop if there's an error
        } else {
            // Redirect to "my_requests.php" after successful submission
            header("Location: my_requests.php");
            exit();
        }

    } elseif ($requestType === 'recurring') {
        // Recurring request handling
        $startDate = $_POST['recurring_start_date'];  // Start date for recurring requests
        $endDate = $_POST['end_date'];  // End date for recurring requests
        $daysOfWeek = $_POST['days_of_week'];  // Days of the week selected (array)

        // Convert dates to DateTime objects for comparison
        $startDateObj = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        // Make sure user has not selected more than 2 days per week
        if (count($daysOfWeek) > 2) {
            echo "Error: You can only select a maximum of 2 days per week.";
            exit();
        }

        try {
            // Begin transaction to ensure data integrity
            $conn->beginTransaction();

            // Iterate over the date range (from start to end)
            while ($startDateObj <= $endDateObj) {
                $dayOfWeek = $startDateObj->format('l');  // Get the day of the week (Monday, Tuesday, etc.)

                // Check if the current day matches the selected days of the week
                if (in_array($dayOfWeek, $daysOfWeek)) {
                    // Prepare SQL for recurring requests
                    $sql = "INSERT INTO employee_arrangement (Staff_ID, Department, Request_ID, Arrangement_Date, Working_Arrangement, Arrangement_Time, Reason, Request_Status, Working_Location, Rejection_Reason) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    // Execute the SQL for each valid recurring day
                    $executed = $stmt->execute([$userID, $dept, $requestID, $startDateObj->format('Y-m-d'), 'WFH', 'Full Day', $reason, 'Pending', 'Home', NULL]);

                    // Error handling in case of execution issues
                    if (!$executed) {
                        echo "Error during execution (recurring): " . implode(", ", $stmt->errorInfo());
                        $conn->rollBack();  // Rollback in case of error
                        exit();
                    }
                }

                // Move to the next day
                $startDateObj->modify('+1 day');
            }

            // Commit the transaction after processing all days
            $conn->commit();

            // Redirect after successful submission
            header("Location: my_requests.php");
            exit();
        } catch (Exception $e) {
            // Rollback the transaction in case of any exception
            $conn->rollBack();
            echo "Failed to process recurring request: " . $e->getMessage();
        }
    }
}
