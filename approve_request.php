<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Request</title>
</head>
<body>
    <?php
        $requestID = $_POST['requestID'];
        $rDao = new RequestDAO;
        $approvalSuccess = $rDao->approveRequest($requestID);

        if ($approvalSuccess) {
            echo "<script>
                alert('Request successfully approved.');
                window.location.href = 'pendingRequests.php';
            </script>";
        } else {
            echo "<script>
                alert('Request approval failed. The request may already be approved or does not exist.');
                window.location.href = 'pendingRequests.php';
            </script>";
        }

    ?>
</body>
</html>