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
        /* Navbar Styling */
        .navbar {
            background-color: black;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center; /* This keeps items vertically centered */
            height: 80px; /* Set a fixed height for the navbar */
            padding: 0 20px; /* Adjust padding for left and right */
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .navbar img {
            height: 100px; /* Set the logo height */
            margin-top: 2px; /* Adjust the top margin to align the image properly */
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

        /* Link Styling */
        a {
            color: #fff; /* Change color to white for visibility */
            text-decoration: none;
            padding: 0; /* Remove padding */
            margin-right: 15px; /* Add some margin if needed */
            transition: color 0.3s ease;
        }

        a:hover {
            color: #ddd; /* Change color on hover */
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

    echo "<div class='navbar'>";
    echo "<a href='home.php'><img src='images/logo.jpg' alt='Company Logo' style='height: 60px;'></a>"; // Link to homepage 
    echo "</div>";

    echo "<div>";
    $staffName = "";
    echo "<form action='deptDetails.php' method='POST'>";
    echo "<label for='staffName'>Search Employee: </label>";
    echo "<input type='text' id='staffName' name='staffName' placeholder='Employee Name' value='$staffName'>";
    echo "<input type='submit' name='submit' value='Search'/>";


    // Retrieve employees in the selected department
    if (!isset($_POST['submit'])){
        $underlings = $dao->retrieveUnderlings($userID);

        echo "<table border=1>";
        echo "<tr><th>ID</th><th>Name</th><th>Position</th><th>Country</th><th>Email</th></tr>";
        foreach ($underlings as $underling) {
            echo "<tr>";
            echo "<td>{$underling['Staff_ID']}</td>";
            echo "<td>{$underling['Staff_FName']} {$underling['Staff_LName']}</td>";
            echo "<td>{$underling['Position']}</td>";
            echo "<td>{$underling['Country']}</td>";
            echo "<td>{$underling['Email']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else{
        $staffName = $_POST['staffName'];

        if ($staffName == ""){
            $underlings = $dao->retrieveUnderlings($userID);
            echo "<table border=1>";
            echo "<tr><th>ID</th><th>Name</th><th>Position</th><th>Country</th><th>Email</th></tr>";
            foreach ($underlings as $underling) {
                echo "<tr>";
                echo "<td>{$underling['Staff_ID']}</td>";
                echo "<td>{$underling['Staff_FName']} {$underling['Staff_LName']}</td>";
                echo "<td>{$underling['Position']}</td>";
                echo "<td>{$underling['Country']}</td>";
                echo "<td>{$underling['Email']}</td>";
                echo "</tr>";
            }
            echo "</table>";            
        } else{
            $sql = "SELECT * FROM employee WHERE Reporting_Manager = $userID AND (Staff_FName LIKE '$staffName' OR Staff_LName LIKE '$staffName' OR CONCAT(Staff_FName, ' ', Staff_LName) LIKE '$staffName');";
            $showData = $dao->searchEmployee($sql);
    
            if ($showData == "No employee found!"){
                echo "<h2>$showData</h2>";
            } else{
                echo "<table border=1>";
                echo "<tr><th>ID</th><th>Name</th><th>Position</th><th>Country</th><th>Email</th></tr>";
                echo "<tr>";
                    echo "<td>{$showData['Staff_ID']}</td>";
                    echo "<td>{$showData['Staff_FName']} {$showData['Staff_LName']}</td>";
                    echo "<td>{$showData['Position']}</td>";
                    echo "<td>{$showData['Country']}</td>";
                    echo "<td>{$showData['Email']}</td>";
                echo "</tr>";
                echo "</table>";
        
            }
            
        }
        }

        
    echo "</div>";

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

