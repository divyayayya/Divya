<?php
require_once "model/common.php";

if (isset($_GET['request_id'])) {
    $requestId = $_GET['request_id'];
    $dao = new RequestDAO;

    $result = $dao->deleteRequest($requestId);

    if ($result) {
        header("Location: my_requests.php?message=Request deleted successfully.");
        exit();
    } else {
        header("Location: my_requests.php?message=Failed to delete request.");
        exit();
    }
}
?>
