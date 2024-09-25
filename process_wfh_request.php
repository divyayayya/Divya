<?php
    require_once "model/common.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userID = $_POST['userID'];
        $wfh_date = $_POST['wfh_date'];
        $arrangement_type = $_POST['arrangement_type']; // WFH or Leave
        $wfh_time = $_POST['wfh_time']; // AM, PM, or full_day
        $reason = $_POST['reason'];

        // Create a new DAO to handle WFH or leave requests
        $dao = new RequestDAO();

        if ($arrangement_type == 'WFH') {
            $result = $dao->submitWFHRequest($userID, $wfh_date, $wfh_time, $reason);
        } else {
            $result = $dao->submitLeaveRequest($userID, $wfh_date, $wfh_time, $reason);
        }

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
