<?php
require_once "model/common.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['request_id'])) {
        $requestId = $_GET['request_id'];

        $dao = new RequestDAO();

        // Call the deleteRequest method
        if ($dao->deleteRequest($requestId)) {
            echo "Request deleted successfully.";
            // Optionally redirect back to the requests page
            header("Location: my_requests.php"); // Update this to your actual requests page
            exit();
        } else {
            echo "Error deleting request. Please try again.";
        }
    } else {
        echo "No request ID provided.";
    }
} else {
    echo "Invalid request method.";
}
?>

