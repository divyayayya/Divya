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

        .tooltip {
            position: absolute;
            background-color: #f9f9f9;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
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
    $userID = $_SESSION['userID'];
    $userRole = $_SESSION['userRole'];

    $dao = new EmployeeDAO();

    echo "<div class='navbar'>";
    echo "<a href='home.php'><img src='images/logo.jpg' alt='Company Logo' style='height: 60px;'></a>"; // Link to homepage 
    echo "</div>";

    echo "<div>";
    // Check if the form has been submitted and the 'staffName' input is set
    if (isset($_POST['submit']) && isset($_POST['staffName'])) {
        $staffName = htmlspecialchars($_POST['staffName']); // Get the searched value
    } else {
        $staffName = ""; // Default empty value
    }
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

    //Retrieve Underling Requests
    $underlings = $dao->retrieveUnderlings($userID);
    $underlingCount = count($underlings);
    $requests = [];
    $dao_req = new RequestDAO;
    foreach($underlings as $underling){
        $single_request = $dao_req -> retrieveRequestInfo($underling['Staff_ID']);
        if(!empty($single_request)){
            $requests = array_merge($requests, $single_request);
        }
    }
    $data = ['requests' => $requests, 'underlingCount' => $underlingCount ];
    $data_json = json_encode($data);
?>  

    
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
    const data = <?php echo $data_json; ?>;
    document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    // Step 1: Get the full year range (for example, the current year)
    const currentYear = new Date().getFullYear();
    const startDate = new Date(currentYear, 0, 1);  // January 1st of the current year
    const endDate = new Date(currentYear, 11, 31);  // December 31st of the current year
    
    const groupedEvents = {};

    // Step 2: Group existing requests by date
    data.requests.forEach(function(request) {
        if (request.Request_Status === 'Approved') {
            const eventDate = request.Arrangement_Date.substring(0, 10);  // Extract YYYY-MM-DD
            if (!groupedEvents[eventDate]) {
                groupedEvents[eventDate] = { TotalWFH: 0, WFH_AM: 0, Office_AM: 0, WFH_PM: 0, Office_PM: 0 };
            }

            // Count WFH events based on Arrangement_Time
            if (request.Working_Arrangement === 'WFH') {
                if (request.Arrangement_Time === 'AM') {
                    groupedEvents[eventDate].TotalWFH++;
                    groupedEvents[eventDate].WFH_AM++;
                } else if (request.Arrangement_Time === 'PM') {
                    groupedEvents[eventDate].TotalWFH++;
                    groupedEvents[eventDate].WFH_PM++;
                } else if (request.Arrangement_Time === 'Full Day') {
                    groupedEvents[eventDate].TotalWFH++;
                    groupedEvents[eventDate].WFH_AM++;
                    groupedEvents[eventDate].WFH_PM++;
                }
            }
        }
    });

    // Step 3: Loop through all days in the year range and create events
    const events = [];
    for (let date = new Date(startDate); date <= endDate; date.setDate(date.getDate() + 1)) {
        const dateStr = date.toISOString().substring(0, 10);  // Convert date to YYYY-MM-DD format
        
        // Check if the day is a weekend 
        const dayOfWeek = date.getDay();
        if (dayOfWeek === 0 || dayOfWeek === 1) {
            continue;  // Skip weekends
        }

        // If the date has no requests, create an Office event
        if (!groupedEvents[dateStr]) {
            events.push({
                title: 'Office',
                start: dateStr,
                color: 'green',
                extendedProps: {
                    wfhCount: 0,
                    officeCount: data.underlingCount,  // All staff in office
                    wfh_am: 0,
                    office_am: data.underlingCount,
                    wfh_pm: 0,
                    office_pm: data.underlingCount
                }
            });
        } else {
            // If WFH events exist for the date, create WFH event
            const counts = groupedEvents[dateStr];
            events.push({
                title: 'WFH',
                start: dateStr,
                extendedProps: {
                    wfhCount: counts.TotalWFH,
                    officeCount: data.underlingCount - counts.TotalWFH,
                    wfh_am: counts.WFH_AM,
                    office_am: data.underlingCount - counts.WFH_AM,
                    wfh_pm: counts.WFH_PM,
                    office_pm: data.underlingCount - counts.WFH_PM
                }
            });
        }
    }

    // Step 4: Initialize the FullCalendar for a year
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',  // Change to year view (you might need a plugin for this)
        selectable: true,
        events: events,  // Add the complete list of events
        dateClick: function(info) {
            // Get day of the week
            var dayOfWeek = new Date(info.dateStr).getDay();

            // Prevent clicks on Saturday and Sunday
            if (dayOfWeek === 0 || dayOfWeek === 6) {
                return;  // Do nothing if it's a weekend
            }

            // Create and submit the form
            var form = document.createElement("form");
            form.method = "POST";
            form.action = "location_details.php";
            form.target = "_blank";  // Open in a new tab

            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "date";
            input.value = info.dateStr;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);  // Clean up
        },

        eventMouseEnter: function(info) {
            var tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.innerHTML = `<table><tr><td>WFH (AM)</td> <td>${info.event.extendedProps.wfh_am}</td></tr> 
                                 <tr><td>Office (AM)</td><td> ${info.event.extendedProps.office_am}</td></tr> 
                                 <tr><td>WFH (PM)</td> <td>${info.event.extendedProps.wfh_pm}</td></tr> 
                                 <tr><td>Office (PM)</td><td> ${info.event.extendedProps.office_pm}</td></tr></table>`;
            document.body.appendChild(tooltip);

            tooltip.style.position = 'absolute';
            tooltip.style.top = info.jsEvent.pageY + 'px';
            tooltip.style.left = info.jsEvent.pageX + 'px';
            tooltip.style.backgroundColor = '#f9f9f9';
            tooltip.style.padding = '5px';
            tooltip.style.border = '1px solid #ccc';
            tooltip.style.zIndex = '1000';
        },
        eventMouseLeave: function() {
            var tooltip = document.querySelector('.tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        }
    });

    calendar.render();
});

</script>
    <!-- Calendar container -->
    <div id='calendar'></div>

</body>
</html>

