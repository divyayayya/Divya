<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.0/main.min.css" />
    <title>Department Details</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f9f9f9;
            color: #333;
            padding: 20px;
        }

        /* Navbar Styling */
        .navbar {
            background-color: black;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
            padding: 0 30px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .navbar img {
            height: 60px;
        }

        .navbar h1 {
            font-size: 24px;
            margin: 0;
        }

        .navbar form {
            display: flex;
            align-items: center;
            gap: 15px; /* Spacing between form elements */
        }

        .navbar label {
            margin-right: 5px;
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
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .navbar input[type='submit']:hover {
            background-color: #ddd;
        }

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

        /* Calendar Section */
        #calendar {
            margin-top: 30px;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<?php
    $userID = $_SESSION['userID'];
    $userRole = $_SESSION['userRole'];

    $dao = new EmployeeDAO();
    $result = $dao->retrieveEmployeeInfo($userID);
    $employee = new Employee(
        $result['Staff_ID'], 
        $result['Staff_FName'], 
        $result['Staff_LName'], 
        $result['Dept'], 
        $result['Position'], 
        $result['Country'], 
        $result['Email'], 
        $result['Reporting_Manager'], 
        $result['Role']
    );

    # Fetch all departments in the company 
    $departments = $dao->getAllDepartments();
    # Fetch all positions in the company 
    $positions = $dao->getAllPositions(); 

    // If form is submitted, use the selected department and position to retrieve employees
    $selectedDept = $employee->getDept(); // Default to the user's department
    $selectedPosition = ''; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selectedDept = $_POST['department']; // Get the selected department from the form
        $selectedPosition = $_POST['position']; // Get the selected position from the form
    }

    echo "<div class='navbar'>";
    echo "<a href='home.php'><img src='images/logo.jpg' alt='Company Logo'></a>"; // Link to homepage 
    if ($userRole == 1){
        echo "<form method='POST' action='' >";
        
        // Department dropdown
        echo "<label for='dept'>Search Department: </label>";
        echo "<select id='dept' name='department'>";
        foreach ($departments as $dept) {
            $selected = ($dept['Dept'] == $selectedDept) ? 'selected' : ''; // Set the default selected option
            echo "<option value='" . htmlspecialchars($dept['Dept']) . "' $selected>" . htmlspecialchars($dept['Dept']) . "</option>";
        }
        echo "</select>";
        
        // Position dropdown
        echo "<label for='position'>Search Position: </label>";
        echo "<select id='position' name='position'>";
        echo "<option value=''>All Positions</option>"; // Add a default option for all positions
        foreach ($positions as $position) {
            $selected = ($position['Position'] == $selectedPosition) ? 'selected' : '';
            echo "<option value='" . htmlspecialchars($position['Position']) . "' $selected>" . htmlspecialchars($position['Position']) . "</option>";
        }
        echo "</select>";

        echo "<input type='submit' value='View'>";
        echo "</form>";
    }
    echo "</div>";

    // Retrieve employees in the selected department and position
    $employeesInSameDept = $dao->retrieveEmployeesByDeptAndPosition($selectedDept, $selectedPosition);

    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Position</th><th>Country</th><th>Email</th></tr>";
    foreach ($employeesInSameDept as $deptEmployee) {
        echo "<tr>";
        echo "<td>{$deptEmployee['Staff_ID']}</td>";
        echo "<td>{$deptEmployee['Staff_FName']} {$deptEmployee['Staff_LName']}</td>";
        echo "<td>{$deptEmployee['Position']}</td>";
        echo "<td>{$deptEmployee['Country']}</td>";
        echo "<td>{$deptEmployee['Email']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<br><h1>Calendar</h1>";
?>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                events: [
                    {
                        title: 'Event 1',
                        start: '2024-09-17',
                        end: '2024-09-18'
                    },
                ],
                dateClick: function() {
                    alert('a day has been clicked!');
                }
            });
            calendar.render();
        });
    </script>

    <!-- Calendar container -->
    <div id='calendar'></div>

</body>
</html>
