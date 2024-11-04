<?php
ob_start();
require_once "model/common.php";

$connMgr = new ConnectionManager();
$pdo = $connMgr->getConnection(); 

// Check if userID is set
if (isset($_POST['userID'])) {
    $userID = $_POST['userID'];

    // Prepare and execute the SQL statement
    $sql = 'select * from employee where Staff_ID = :userID';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);


    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();

    $result = $stmt->fetchAll();

    if (count($result)> 0) {
        // User ID exists
        $dao = new EmployeeDAO;
        $result = $dao->retrieveEmployeeInfo($userID);
        $employee = new Employee($result['Staff_ID'], $result['Staff_FName'], $result['Staff_LName'], $result['Dept'], $result['Position'], $result['Country'], $result['Email'], $result['Reporting_Manager'], $result['Role']);

        $_SESSION['userID'] = $userID;
        $_SESSION['userRole'] = $employee->getRole();
        header("Location: home.php"); // Redirect to another page
        exit();
    } else {
        // User ID does not exist
        header("Location: login.php?userID=" . urlencode($userID) . "&error=true");
        exit();
    }
} else {
    header("Location: home.php");
    exit();
}
ob_end_flush();
?>