<?php
    require_once "model/common.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- calendar css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.0/main.min.css" />
    <title>Department Details</title>
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

    // If form is submitted, use the selected department to retrieve employees in that department
    $selectedDept = $employee->getDept(); // Default to the user's department

    echo "<h1 style='display: inline-block; margin-right: 20px;'>Department</h1><a href='home.php'>Back</a></br>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selectedDept = $_POST['department']; // Get the selected department from the form
    }

    if ($userRole == 1){
        
        echo "<form method='POST' action='' >";
        echo "<label for='dept'>Select Department: </label>";
        echo "<select id='dept' name='department'>";
    
        // Iterate over the departments array to populate the dropdown options
        foreach ($departments as $dept) {
            $selected = ($dept['Dept'] == $selectedDept) ? 'selected' : ''; // Set the default selected option
            echo "<option value='" . htmlspecialchars($dept['Dept']) . "' $selected>" . htmlspecialchars($dept['Dept']) . "</option>";
        }
    
        echo "</select>";
        echo "<input type='submit' value='Submit'>";
        echo "</form></br>";
    }
    
    
    // Retrieve employees in the selected department
    $employeesInSameDept = $dao->retrieveEmployeesInSameDept($selectedDept);

    echo "<table border=1>";
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

    echo "</br></br><h1>Calendar</h1>";
?>

    <!-- calendar js -->
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
</body>

<body>
    <div id='calendar'></div>
</body>
