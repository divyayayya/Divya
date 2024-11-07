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
    <style>
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
            font-size: 16px;
        }

        th {
            background-color: #f1f1f1;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Navbar Styling */
        .navbar {
            background-color: black;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 10vh; /* Adjust height relative to viewport */
            padding: 0 5vw; /* Padding adjusted for responsiveness */
            margin-bottom: 20px;
            border-radius: 5px;
            position: relative;
        }

        .navbar img {
            height: 8vh; /* Responsive height */
        }

        .navbar h1 {
            font-size: 24px;
            margin: 0;
        }

        .navbar form {
            display: flex;
            align-items: center;
        }

        .navbar label {
            margin-right: 10px;
            font-size: 16px;
            color: #fff;
        }

        .navbar select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #fff;
            color: #333;
            font-size: 16px;
        }

        .navbar input[type='submit'] {
            padding: 10px 20px;
            background-color: #fff;
            color: #333;
            border: none;
            border-radius: 5px;
            margin-left: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .navbar input[type='submit']:hover {
            background-color: #ddd;
        }
                /* For tablets and larger screens (width between 768px and 1024px) */
                @media (max-width: 900px) {
            .navbar {
                flex-direction: column;
                height: auto;
                padding: 20px;
            }
            
            .navbar img {
                height: 6vh; /* Reduce image height */
            }
            
            .navbar h1 {
                font-size: 20px;
            }
            
            .navbar form {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar label, .navbar select, .navbar input {
                margin: 5px 0;
            }

            table, th, td {
                font-size: 14px; /* Smaller font size */
                padding: 10px; /* Adjust padding */
            }
        }

        /* For mobile devices (width less than 768px) */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px;
            }

            .navbar img {
                height: 5vh; /* Smaller logo for mobile */
            }

            .navbar h1 {
                font-size: 18px;
            }

            .navbar form {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar label, .navbar select, .navbar input {
                width: 100%;
                margin: 5px 0;
            }

            table {
                font-size: 12px;
                overflow-x: auto; /* Add horizontal scroll for tables on small screens */
            }

            table, th, td {
                font-size: 12px; /* Smaller font size */
                padding: 8px;
            }
        }
    </style>
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


    // Set the selected date and department from the POST request
    $selectedDate = $_POST['date'] ?? null;
    $selectedDept = $_POST['department'] ?? 'All Departments';  // Default to show all departments
?>

<!-- Navbar -->
<div class="navbar">
        <a href="home.php"><img src="images/logo.jpg" alt="Company Logo"></a> <!-- Link to homepage -->

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

 </div>

<?php
    // For non-HR users, limit to their own department
    if ($userDept != 'HR') {
        $employeesInDept = $dao->retrieveEmployeesInSameDept($userDept);
        echo "<br>Colleagues in <strong>{$userDept}</strong><br>";
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
