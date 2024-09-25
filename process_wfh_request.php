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
            header("Location: home.php?message=Request submitted successfully.");
        } else {
            echo "Error submitting request.";
        }
    } else {
        header("Location: apply_wfh.php");
        exit();
    }
?>
