<?php
require_once "model/common.php";

$connMgr = new ConnectionManager();
$pdo = $connMgr->getConnection();

session_start();
$userID = $_SESSION['userID'];

// Getformdata
$date1 = $_POST['date1'];
$date2 = isset($_POST['date2']) ? $_POST['date2'] : null;
$reason = $_POST['reason'];

// Insert request into the database
$sql = 'INSERT INTO employee_arrangement (Staff_ID, Arrangement_Date, Working_Arrangement) VALUES (:staffID, :date, :arrangement)';
$stmt = $pdo->prepare($sql);

$dates = [$date1];
if ($date2) {
    $dates[] = $date2;
}

foreach ($dates as $date) {
    $stmt->bindParam(':staffID', $userID, PDO::PARAM_INT);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':arrangement', $reason);
    $stmt->execute();
}

// not done yet lol
 ?>
