<?php
    require_once "model/common.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userID = $_POST['userID'];
        $wfh_date = $_POST['wfh_date'];
        $reason = $_POST['reason'];

        // Create a new DAO to handle WFH requests
        $dao = new RequestDAO();
        $result = $dao->submitWFHRequest($userID, $wfh_date, $reason);

        if ($result) {
            // Redirect to success page or show success message
            header("Location: home.php?message=Request submitted successfully.");
        } else {
            // Show error message
            echo "Error submitting request.";
        }
    } else {
        header("Location: apply_wfh.php");
        exit();
    }
?>
