<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejecting</title>
</head>
<body>
    <?php
        $requestID = $_POST['requestID'];
        $rejectionReason = $_POST['rejectReason'];
        $rDao = new RequestDAO;
        $rejectionSuccess = $rDao->rejectRequest($requestID, $rejectionReason);

        if ($rejectionSuccess) {
            echo "<script>
                alert('Request successfully Rejected.');
                window.location.href = 'pendingRequests.php';
            </script>";
        } else {
            echo "<script>
                alert('Request rejection failed. The request may already be approved or does not exist.');
                window.location.href = 'pendingRequests.php';
            </script>";
        }

    ?>
</body>
</html>