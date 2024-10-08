<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colleagues Location</title>
</head>
<body>

<?php
    # Start session and retrieve user details
    $userID = $_SESSION['userID'];
    $userRole = $_SESSION['userRole'];

    # Initialize DAO objects
    $dao = new EmployeeDAO;
    $result = $dao->retrieveEmployeeInfo($userID);

    # Create Employee object based on the current user’s details
    $employee = new Employee(
        $result['Staff_ID'], $result['Staff_FName'], $result['Staff_LName'], 
        $result['Dept'], $result['Position'], $result['Country'], 
        $result['Email'], $result['Reporting_Manager'], $result['Role']
    );

    # Retrieve the user's department
    $userDept = $employee->getDept();

    echo "<a href='login.php' style='display: inline-block; vertical-align: middle;'>Sign Out</a>";
    echo "<br><br>";
?>

<!-- Form to select the date -->
<form method="POST" action="">
    <label for="arrangement_date">Select Date: </label>
    <input id="arrangement_date" type="date" name="arrangement_date" value="<?php echo isset($_POST['arrangement_date']) ? htmlspecialchars($_POST['arrangement_date']) : ''; ?>">    
    <input type="submit" value="Retrieve Locations">
</form>

<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['arrangement_date'])) {
        $selectedDate = $_POST['arrangement_date'];

        # Retrieve employees in the same department using the DAO method
        $employeesInDept = $dao->retrieveEmployeesInSameDept($userDept);

        echo "<br><u>Employees List for Date: $selectedDate</u><br>";

        # Display employees from the same department and their location on the selected date
        if (!empty($employeesInDept)) {
            echo "<table border=1>";
            echo "<tr><th>ID</th><th>Name</th><th>Position</th><th>Country</th><th>Location</th></tr>";
            # Inside the foreach loop
            # Inside the foreach loop
            foreach ($employeesInDept as $emp) {
                # Retrieve the arrangement details for the employee
                $arrangement = $dao->retrieveArrangementDetailsByDate($emp['Staff_ID'], $selectedDate);
                # Check if $arrangement is an array and contains the 'Working_Location'
                if ($arrangement && isset($arrangement['Working_Location'])) {
                    $workingLocation = $arrangement['Working_Location'];
                    echo "<tr bgcolor='fbec5d'><td>{$emp['Staff_ID']}</td><td>{$emp['Staff_FName']} {$emp['Staff_LName']}</td>";
                    echo "<td>{$emp['Position']}</td><td>{$emp['Country']}</td><td>{$workingLocation}</td></tr>";
                } else {
                    $workingLocation = 'In-Office';  // Default if no arrangement is found
                    echo "<tr><td>{$emp['Staff_ID']}</td><td>{$emp['Staff_FName']} {$emp['Staff_LName']}</td>";
                    echo "<td>{$emp['Position']}</td><td>{$emp['Country']}</td><td>{$workingLocation}</td></tr>";
                }

                # Echo out individual fields in the array
            }

            echo "</table>";
        } else {
            echo "No employees found in the same department.";
        }
    }
?>

</body>
</html>
