
<?php
// Include database connection and common functions
require_once 'model/common.php';

session_start();
$userID = $_SESSION['userID'];  // Assuming userID is stored in the session after login

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted data from the POST request
    $staffId = $_POST['staff_id'];
    $requestId = $_POST['request_id'];
    $reason = $_POST['reason'];
    $deletionDate = date('Y-m-d');  // Capture current date

    // Insert the deletion request into the 'deletion_request' table
    $sql = "INSERT INTO deletion_request (Staff_ID, Request_ID, Deletion_Date, Reason, Status)
            VALUES (:staff_id, :request_id, :deletion_date, :reason, 'Pending')";

    try {
        $conn = new PDO("mysql:host=localhost;dbname=employeeDB", 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':staff_id', $staffId);
        $stmt->bindParam(':request_id', $requestId);
        $stmt->bindParam(':deletion_date', $deletionDate);
        $stmt->bindParam(':reason', $reason);
        $stmt->execute();

        echo json_encode(['message' => 'Deletion request submitted successfully and is now pending approval.']);
    } catch (PDOException $e) {
        echo json_encode(['message' => 'Failed to submit request: ' . $e->getMessage()]);
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Delete Work-from-Home Arrangement</title>
</head>
<body>
    <h1>Delete Work-from-Home Arrangement</h1>
    
    <form id="delete-form">
        <label for="staff_id">Staff ID:</label>
        <input type="text" id="staff_id" name="staff_id" required><br><br>

        <label for="request_id">Request ID:</label>
        <input type="text" id="request_id" name="request_id" required><br><br>

        <label for="reason">Reason for Deletion:</label><br>
        <textarea id="reason" name="reason" rows="4" cols="50" required></textarea><br><br>

        <button type="button" onclick="submitDeletionRequest()">Submit</button>
    </form>

    <script>
        function submitDeletionRequest() {
            const isConfirmed = confirm("Are you sure you want to submit this deletion request?");
            if (!isConfirmed) {
                return;
            }

            const staffId = document.getElementById('staff_id').value;
            const requestId = document.getElementById('request_id').value;  
            const reason = document.getElementById('reason').value;

            fetch('/delete_wfh.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    staff_id: staffId,
                    request_id: requestId,  
                    reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message); 
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>


