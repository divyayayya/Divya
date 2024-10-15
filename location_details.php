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
    $dao = new EmployeeDAO;
    $result = $dao->retrieveEmployeeInfo($userID);
    $employee = new Employee(
        $result['Staff_ID'], $result['Staff_FName'], $result['Staff_LName'], 
        $result['Dept'], $result['Position'], $result['Country'], 
        $result['Email'], $result['Reporting_Manager'], $result['Role']
    );
    $userDept = $employee->getDept();

    echo "<a href='home.php' style='display: inline-block; vertical-align: middle;'>Home</a>";
    echo "<br><br>";

    // Set the selected date and department from the POST request
    $selectedDate = $_POST['date'] ?? null;
    $selectedDept = $_POST['department'] ?? 'All Departments';  // Default to show all departments

    echo "<u>Employees List for Date: $selectedDate</u><br><br>";
?>

<!-- Form to select the date and department (HR only) -->
<form method="POST" action="">
    <label for="arrangement_date">Select another date: </label>
    <input id="arrangement_date" type="date" name="date" value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>">    
    
    <?php
        if ($userDept == 'HR') {
            echo "<br><br>";
            echo "<label for='department'>Filter by Department: </label>";
            echo "<select name='department' id='dept_selector'>";
            echo "<option value='All Departments'" . ($selectedDept == 'All Departments' ? " selected" : "") . ">All Departments</option>";
            echo "<option value='CEO'" . ($selectedDept == 'CEO' ? " selected" : "") . ">CEO</option>";
            echo "<option value='Sales'" . ($selectedDept == 'Sales' ? " selected" : "") . ">Sales</option>";
            echo "<option value='Solutioning'" . ($selectedDept == 'Solutioning' ? " selected" : "") . ">Solutioning</option>";
            echo "<option value='Engineering'" . ($selectedDept == 'Engineering' ? " selected" : "") . ">Engineering</option>";
            echo "<option value='HR'" . ($selectedDept == 'HR' ? " selected" : "") . ">HR</option>";
            echo "<option value='Finance'" . ($selectedDept == 'Finance' ? " selected" : "") . ">Finance</option>";
            echo "<option value='Consultancy'" . ($selectedDept == 'Consultancy' ? " selected" : "") . ">Consultancy</option>";
            echo "<option value='IT'" . ($selectedDept == 'IT' ? " selected" : "") . ">IT</option>";
            echo "</select>";
        }
    ?>
    <br><br>
    <input type="submit" value="Retrieve Locations">
</form>
<br>

<?php
    // For non-HR users, limit to their own department
    if ($userDept != 'HR') {
        $employeesInDept = $dao->retrieveEmployeesInSameDept($userDept);
        echo "<br>In the <strong>$userDept</strong> department: <br>";
    } else {
        // For HR users, filter by selected department
        if ($selectedDept == 'All Departments') {
            $employeesInDept = $dao->retrieveAllEmployees();
        } else {
            $employeesInDept = $dao->retrieveEmployeesInSameDept($selectedDept);
            echo "In the <strong>$selectedDept</strong> department: </br>";
        }
    }

    // Display the filtered list of employees
    if (!empty($employeesInDept)) {
        echo "<table border=1>";
        echo "<tr><th>ID</th><th>Name</th><th>Department</th><th>Position</th><th>Country</th><th>Location</th><th>Time Block</th></tr>";

        foreach ($employeesInDept as $emp) {
            # Retrieve the arrangement details for the employee
            $arrangement = $dao->retrieveArrangementDetailsByDate($emp['Staff_ID'], $selectedDate);

            # Check if $arrangement is an array and contains the 'Working_Location'
            if ($arrangement && isset($arrangement['Working_Location'])) {
                var_dump($arrangement);
                $workingLocation = $arrangement['Working_Location'];
                $arrangementTime = $arrangement['Arrangement_Time'];
                echo "<tr bgcolor='fbec5d'><td>{$emp['Staff_ID']}</td><td>{$emp['Staff_FName']} {$emp['Staff_LName']}</td>
                <td>{$emp['Dept']}</td><td>{$emp['Position']}</td><td>{$emp['Country']}</td><td>{$workingLocation}</td><td>{$arrangementTime}</td></tr>";
            } else {
                $workingLocation = 'In-Office';  // Default if no arrangement is found
                echo "<tr><td>{$emp['Staff_ID']}</td><td>{$emp['Staff_FName']} {$emp['Staff_LName']}</td>
                <td>{$emp['Dept']}</td><td>{$emp['Position']}</td><td>{$emp['Country']}</td><td>{$workingLocation}</td><td>Full Day</td></tr>";
            }
        }
        echo "</table>";
    } else {
        echo "No employees found.";
    }
?>

</body>
</html>
